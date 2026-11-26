<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Services\ActivityLogger;
use App\Support\ImageConverter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class InvoicePaymentController extends Controller
{
    public function store(
        Request $request,
        Invoice $invoice,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        abort_unless($invoice->type === 'invoice', 404);

        if ($invoice->status === 'voided') {
            return back()->withErrors([
                'payment' => 'برای فاکتور باطل‌شده امکان ثبت پرداخت جدید وجود ندارد.',
            ]);
        }

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999999999999999.99',
            ],
            'payment_date' => [
                'required',
                'date',
            ],
            'method' => [
                'required',
                'string',
                'max:100',
            ],
            'reference_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ], [
            'amount.required' => 'وارد کردن مبلغ پرداخت الزامی است.',
            'amount.numeric' => 'مبلغ پرداخت باید عددی باشد.',
            'amount.gt' => 'مبلغ پرداخت باید بیشتر از صفر باشد.',
            'payment_date.required' => 'تاریخ پرداخت الزامی است.',
            'payment_date.date' => 'تاریخ پرداخت معتبر نیست.',
            'method.required' => 'روش پرداخت را وارد کنید.',
            'method.max' => 'روش پرداخت نباید بیشتر از ۱۰۰ کاراکتر باشد.',
            'reference_number.max' => 'شماره پیگیری نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'description.max' => 'توضیحات نباید بیشتر از ۱۰۰۰۰ کاراکتر باشد.',
            'document.mimes' => 'فرمت تصویر سند باید JPG، JPEG، PNG یا WebP باشد.',
            'document.max' => 'حجم تصویر سند نباید بیشتر از ۱۰ مگابایت باشد.',
        ]);

        $documentPath = null;
        $originalName = null;

        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $originalName = Str::limit(
                basename($file->getClientOriginalName()),
                255,
                ''
            );

            $documentPath = ImageConverter::convertToWebp(
                $file,
                'invoice-payments',
                'local'
            );
        }

        try {
            DB::transaction(function () use (
                $invoice,
                $validated,
                $documentPath,
                $originalName,
                $activityLogger
            ) {
                $payment = $invoice->payments()->create([
                    'amount' => $validated['amount'],
                    'payment_date' => $validated['payment_date'],
                    'method' => $validated['method'],
                    'reference_number' => $validated['reference_number'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'document_path' => $documentPath,
                    'document_original_name' => $originalName,
                    'created_by' => auth()->id(),
                ]);

                $activityLogger->log(
                    'invoice_payment_created',
                    'ثبت پرداخت برای فاکتور شماره ' . $invoice->document_number,
                    $payment,
                    [
                        'invoice_id' => $invoice->id,
                        'document_number' => $invoice->document_number,
                        'amount' => $payment->amount,
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                        'method' => $payment->method,
                        'reference_number' => $payment->reference_number,
                    ]
                );
            });
        } catch (Throwable $exception) {
            if ($documentPath) {
                Storage::disk('local')->delete($documentPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', 'پرداخت مشتری با موفقیت ثبت شد.');
    }

    public function download(
        Invoice $invoice,
        InvoicePayment $payment
    ): \Symfony\Component\HttpFoundation\StreamedResponse {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        abort_unless(
            $payment->document_path
            && Storage::disk('local')->exists($payment->document_path),
            404
        );

        return Storage::disk('local')->response(
            $payment->document_path,
            null,
            [
                'Content-Type' => 'image/webp',
                'Content-Disposition' => 'inline',
            ]
        );
    }
}