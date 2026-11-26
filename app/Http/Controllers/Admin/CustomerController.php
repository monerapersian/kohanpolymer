<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SiteContent;
use App\Services\ActivityLogger;
use App\Support\DateHelper;
use App\Support\NumberHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->input('search'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($request->input('type'), ['company', 'person'], true),
                function ($query) use ($request) {
                    $query->where('type', $request->input('type'));
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCustomers = Customer::count();
        $companyCount = Customer::where('type', 'company')->count();
        $personCount = Customer::where('type', 'person')->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'companyCount',
            'personCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['company', 'person'])],
            'name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:16'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $customer = Customer::create($validated);

        app(ActivityLogger::class)->log(
            action: 'customer_created',
            description: 'مشتری جدید «' . $customer->name . '» ثبت شد.',
            subject: $customer,
            properties: [
                'name' => $customer->name,
                'type' => $customer->type,
                'has_phone' => ! empty($customer->phone),
            ]
        );

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'مشتری جدید با موفقیت ثبت شد.');
    }

    public function show(Customer $customer, Request $request): View|RedirectResponse
    {
        // Load all valid invoices for financial calculations.
        $financialInvoices = $customer->invoices()
            ->where('status', '!=', 'voided')
            ->with(['items', 'payments'])
            ->get();

        $totalPurchases = 0;
        $totalPaid = 0;
        $totalDebt = 0;

        foreach ($financialInvoices as $invoice) {
            $invoiceTotal = (float) $invoice->items->sum('grand_total');
            $invoicePaid = (float) $invoice->payments->sum('amount');

            $totalPurchases += $invoiceTotal;
            $totalPaid += $invoicePaid;
            $totalDebt += max($invoiceTotal - $invoicePaid, 0);
        }

        $financialTotals = [
            'purchases' => $totalPurchases,
            'paid' => $totalPaid,
            'debt' => $totalDebt,
        ];

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'document_type' => ['nullable', Rule::in(['invoice', 'proforma'])],
            'payment_status' => ['nullable', Rule::in(['settled', 'debt'])],
            'start_date' => ['nullable', 'string', 'max:20'],
            'end_date' => ['nullable', 'string', 'max:20'],
        ]);

        $search = trim($validated['search'] ?? '');
        $documentType = $validated['document_type'] ?? '';
        $paymentStatus = $validated['payment_status'] ?? '';
        $startDate = trim($validated['start_date'] ?? '');
        $endDate = trim($validated['end_date'] ?? '');

        $startGregorian = null;
        $endGregorian = null;

        try {
            if ($startDate !== '') {
                $startDate = NumberHelper::toEnglish($startDate);
                $startGregorian = DateHelper::toGregorian($startDate);
            }

            if ($endDate !== '') {
                $endDate = NumberHelper::toEnglish($endDate);
                $endGregorian = DateHelper::toGregorian($endDate);
            }
        } catch (Throwable $exception) {
            return redirect()
                ->route('admin.customers.show', $customer)
                ->withErrors([
                    'date' => 'تاریخ واردشده معتبر نیست. لطفاً تاریخ را دوباره انتخاب کنید.',
                ])
                ->withInput();
        }

        if (
            $startGregorian !== null
            && $endGregorian !== null
            && $startGregorian > $endGregorian
        ) {
            return redirect()
                ->route('admin.customers.show', $customer)
                ->withErrors([
                    'date' => 'تاریخ شروع نمی‌تواند بعد از تاریخ پایان باشد.',
                ])
                ->withInput();
        }

        // Build the document history query.
        $documentsQuery = $customer->documents()
            ->with(['items', 'payments'])
            ->when($documentType !== '', function ($query) use ($documentType) {
                $query->where('type', $documentType);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('document_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");

                    if (ctype_digit(NumberHelper::toEnglish($search))) {
                        $subQuery->orWhere('id', (int) NumberHelper::toEnglish($search));
                    }
                });
            })
            ->when($startGregorian !== null, function ($query) use ($startGregorian) {
                $query->whereDate('issue_date', '>=', $startGregorian);
            })
            ->when($endGregorian !== null, function ($query) use ($endGregorian) {
                $query->whereDate('issue_date', '<=', $endGregorian);
            });

        // Payment status applies only to valid invoices, not proformas.
        if ($paymentStatus !== '') {
            $documentsQuery
                ->where('type', 'invoice')
                ->where('status', '!=', 'voided');

            $balanceCondition = $paymentStatus === 'settled' ? '<=' : '>';

            $documentsQuery->whereRaw(
                "(
                    COALESCE((
                        SELECT SUM(invoice_items.grand_total)
                        FROM invoice_items
                        WHERE invoice_items.invoice_id = invoices.id
                    ), 0)
                    -
                    COALESCE((
                        SELECT SUM(invoice_payments.amount)
                        FROM invoice_payments
                        WHERE invoice_payments.invoice_id = invoices.id
                    ), 0)
                ) {$balanceCondition} 0"
            );
        }

        $documents = $documentsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.show', compact(
            'customer',
            'documents',
            'financialTotals',
            'search',
            'documentType',
            'paymentStatus',
            'startDate',
            'endDate'
        ));
    }

    public function printReport(Request $request, Customer $customer): View|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d'],
            'show_type' => ['sometimes', 'boolean'],
            'show_issue_date' => ['sometimes', 'boolean'],
            'show_payment_status' => ['sometimes', 'boolean'],
            'show_total' => ['sometimes', 'boolean'],
        ], [
            'start_date.required' => 'تاریخ شروع بازه گزارش را انتخاب کنید.',
            'start_date.date_format' => 'تاریخ شروع معتبر نیست.',
            'end_date.required' => 'تاریخ پایان بازه گزارش را انتخاب کنید.',
            'end_date.date_format' => 'تاریخ پایان معتبر نیست.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.customers.show', $customer)
                ->with('report_error', $validator->errors()->first());
        }

        $validated = $validator->validated();

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        if ($startDate > $endDate) {
            return redirect()
                ->route('admin.customers.show', $customer)
                ->with('report_error', 'تاریخ شروع نمی‌تواند بعد از تاریخ پایان باشد.');
        }

        $columns = [
            'type' => $request->boolean('show_type'),
            'issue_date' => $request->boolean('show_issue_date'),
            'payment_status' => $request->boolean('show_payment_status'),
            'total' => $request->boolean('show_total'),
        ];

        if (! in_array(true, $columns, true)) {
            return redirect()
                ->route('admin.customers.show', $customer)
                ->with('report_error', 'حداقل یک ستون برای گزارش انتخاب کنید.');
        }

        // Fetch documents independently of the customer page filters.
        $documents = $customer->documents()
            ->with(['items', 'payments'])
            ->whereDate('issue_date', '>=', $startDate)
            ->whereDate('issue_date', '<=', $endDate)
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->get();

        foreach ($documents as $document) {
            $document->report_total = (float) $document->items->sum('grand_total');
            $document->report_paid = (float) $document->payments->sum('amount');

            if ($document->type === 'proforma') {
                $document->report_payment_status = '—';
            } elseif ($document->status === 'voided') {
                $document->report_payment_status = 'باطل شده';
            } elseif ($document->report_total - $document->report_paid <= 0) {
                $document->report_payment_status = 'تسویه کامل';
            } else {
                $document->report_payment_status = 'دارای بدهی';
            }

            $document->report_type = $document->type === 'proforma'
                ? 'پیش‌فاکتور'
                : 'فاکتور';
        }

        $siteContent = SiteContent::query()->first();

        $logoUrl = $siteContent?->logo
            ? Storage::disk('public')->url($siteContent->logo)
            : asset('img/kohan-logo.png');

        $reportDate = NumberHelper::toPersian(
            DateHelper::toPersian(now(), 'Y/m/d')
        );

        $persianStartDate = NumberHelper::toPersian(
            DateHelper::toPersian($startDate, 'Y/m/d')
        );

        $persianEndDate = NumberHelper::toPersian(
            DateHelper::toPersian($endDate, 'Y/m/d')
        );

        return view('admin.customers.print-report', compact(
            'customer',
            'documents',
            'columns',
            'logoUrl',
            'reportDate',
            'persianStartDate',
            'persianEndDate'
        ));
    }

    public function update(
        Request $request,
        Customer $customer
    ): RedirectResponse {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['company', 'person'])],
            'name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:16'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldValues = [
            'name' => $customer->name,
            'type' => $customer->type,
            'has_phone' => ! empty($customer->phone),
        ];

        $customer->update($validated);

        $changes = [];

        foreach (['name', 'type'] as $field) {
            $oldValue = $oldValues[$field];
            $newValue = $customer->{$field};

            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        $newHasPhone = ! empty($customer->phone);

        if ($oldValues['has_phone'] !== $newHasPhone) {
            $changes['phone'] = [
                'old' => $oldValues['has_phone'],
                'new' => $newHasPhone,
            ];
        }

        app(ActivityLogger::class)->log(
            action: 'customer_updated',
            description: 'اطلاعات مشتری «' . $customer->name . '» ویرایش شد.',
            subject: $customer,
            properties: [
                'changes' => $changes,
            ]
        );

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'اطلاعات مشتری با موفقیت ویرایش شد.');
    }
}