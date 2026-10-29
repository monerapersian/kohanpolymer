<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\DocumentNumberService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->input('search'));

                $query->where(function ($query) use ($search) {
                    $query->where('document_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(
                in_array($request->input('type'), ['invoice', 'proforma'], true),
                fn ($query) => $query->where('type', $request->input('type'))
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('admin.invoices.create', compact('customers'));
    }

    public function store(Request $request, DocumentNumberService $numberService)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:invoice,proforma'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'issue_date' => ['required', 'date'],
            'valid_until' => [
                'nullable',
                'required_if:type,proforma',
                'date',
                'after_or_equal:issue_date',
            ],
            'payment_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:10000'],
            'is_formal' => ['nullable', 'in:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:999999999999'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.width' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'items.*.thickness' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'max:9999999999999999'],
            'items.*.vat_percent' => ['required', 'numeric', 'between:0,100'],

            'notes' => ['nullable', 'array'],
            'notes.*.description' => ['required', 'string', 'max:5000'],
        ]);

        $type = $validated['type'];

        $invoice = DB::transaction(function () use ($validated, $type, $numberService) {
            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'],
                'document_number' => $numberService->generate($type),
                'type' => $type,
                'issue_date' => $validated['issue_date'],
                'valid_until' => $type === 'proforma'
                    ? $validated['valid_until']
                    : null,
                'payment_type' => $validated['payment_type'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => 'valid',
                'is_formal' => !isset($validated['is_formal']),
                'qr_token' => Str::random(64),
            ]);

            foreach ($validated['items'] as $index => $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $vatPercent = (float) $item['vat_percent'];

                $totalAmount = round($quantity * $unitPrice, 2);
                $vatAmount = round($totalAmount * $vatPercent / 100, 2);

                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit' => $item['unit'] ?? null,
                    'width' => $item['width'] ?? null,
                    'thickness' => $item['thickness'] ?? null,
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'vat_percent' => $vatPercent,
                    'vat_amount' => $vatAmount,
                    'grand_total' => round($totalAmount + $vatAmount, 2),
                    'sort_order' => $index,
                ]);
            }

            foreach ($validated['notes'] ?? [] as $index => $note) {
                $invoice->notes()->create([
                    'description' => $note['description'],
                    'sort_order' => $index,
                ]);
            }

            return $invoice;
        }, 5);

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', 'سند با موفقیت ثبت شد.');
    }

    public function show(Invoice $invoice)
    {
        if (!$invoice->qr_token) {
            $invoice->update([
                'qr_token' => Str::random(64),
            ]);

            $invoice->refresh();
        }

        $invoice->load([
            'customer',
            'items',
            'notes',
            'convertedFrom',
            'convertedTo',
        ]);

        $verificationUrl = route('invoices.verify', $invoice->qr_token);

        $qrCode = new QrCode(
            data: $verificationUrl,
            size: 220,
            margin: 10,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view('admin.invoices.show', compact(
            'invoice',
            'verificationUrl',
            'qrCodeDataUri'
        ));
    }

    public function verify(string $token)
    {
        $invoice = Invoice::with([
            'customer',
            'items',
            'notes',
        ])
            ->where('qr_token', $token)
            ->firstOrFail();

        $verificationUrl = route('invoices.verify', $invoice->qr_token);

        $qrCode = new QrCode(
            data: $verificationUrl,
            size: 220,
            margin: 10,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view('invoices.verify', compact(
            'invoice',
            'verificationUrl',
            'qrCodeDataUri'
        ));
    }

    public function export(Invoice $invoice)
    {
        $invoice->load([
            'customer',
            'items',
            'notes',
        ]);

        return Excel::download(
            new InvoiceExport($invoice),
            $invoice->document_number . '.xlsx'
        );
    }

    public function void(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $lockedInvoice = Invoice::whereKey($invoice->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedInvoice->status === 'voided') {
                throw ValidationException::withMessages([
                    'invoice' => 'این سند قبلاً باطل شده است.',
                ]);
            }

            $lockedInvoice->update([
                'status' => 'voided',
            ]);
        }, 5);

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', 'سند با موفقیت باطل شد.');
    }

    public function convert(Invoice $invoice, DocumentNumberService $numberService)
    {
        $newInvoice = DB::transaction(function () use ($invoice, $numberService) {
            $source = Invoice::with(['items', 'notes'])
                ->whereKey($invoice->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($source->type !== 'proforma') {
                throw ValidationException::withMessages([
                    'invoice' => 'فقط پیش‌فاکتور قابل تبدیل به فاکتور است.',
                ]);
            }

            if ($source->status === 'voided') {
                throw ValidationException::withMessages([
                    'invoice' => 'پیش‌فاکتور باطل‌شده قابل تبدیل نیست.',
                ]);
            }

            if ($source->isExpired()) {
                throw ValidationException::withMessages([
                    'invoice' => 'پیش‌فاکتور منقضی‌شده قابل تبدیل نیست.',
                ]);
            }

            if ($source->convertedTo()->exists()) {
                throw ValidationException::withMessages([
                    'invoice' => 'این پیش‌فاکتور قبلاً به فاکتور تبدیل شده است.',
                ]);
            }

            $newInvoice = Invoice::create([
                'customer_id' => $source->customer_id,
                'converted_from_id' => $source->id,
                'document_number' => $numberService->generate('invoice'),
                'type' => 'invoice',
                'issue_date' => now()->toDateString(),
                'valid_until' => null,
                'payment_type' => $source->payment_type,
                'status' => 'valid',
                'is_formal' => $source->is_formal,
                'qr_token' => Str::random(64),
                'description' => $source->description,
            ]);

            foreach ($source->items as $item) {
                $newInvoice->items()->create([
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'width' => $item->width,
                    'thickness' => $item->thickness,
                    'unit_price' => $item->unit_price,
                    'total_amount' => $item->total_amount,
                    'vat_percent' => $item->vat_percent,
                    'vat_amount' => $item->vat_amount,
                    'grand_total' => $item->grand_total,
                    'sort_order' => $item->sort_order,
                ]);
            }

            foreach ($source->notes as $note) {
                $newInvoice->notes()->create([
                    'description' => $note->description,
                    'sort_order' => $note->sort_order,
                ]);
            }

            return $newInvoice;
        }, 5);

        return redirect()
            ->route('admin.invoices.show', $newInvoice)
            ->with('success', 'پیش‌فاکتور با موفقیت به فاکتور تبدیل شد.');
    }
}