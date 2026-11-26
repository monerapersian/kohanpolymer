<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceProfitController extends Controller
{
    public function update(
        Request $request,
        Invoice $invoice,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        abort_unless($invoice->type === 'invoice', 404);

        $totalAmount = (float) $invoice->items()->sum('grand_total');

        if ($totalAmount <= 0) {
            throw ValidationException::withMessages([
                'profit' => 'مبلغ فاکتور معتبر نیست و امکان ثبت سود وجود ندارد.',
            ]);
        }

        $validated = $request->validate([
            'profit_input_type' => [
                'required',
                'in:percentage,amount',
            ],
            'profit_percentage' => [
                'nullable',
                'required_if:profit_input_type,percentage',
                'numeric',
                'min:0',
                'max:100',
            ],
            'profit_amount' => [
                'nullable',
                'required_if:profit_input_type,amount',
                'numeric',
                'min:0',
                'max:' . $totalAmount,
            ],
        ], [
            'profit_input_type.required' => 'روش تعیین سود را انتخاب کنید.',
            'profit_input_type.in' => 'روش تعیین سود معتبر نیست.',
            'profit_percentage.required_if' => 'درصد سود را وارد کنید.',
            'profit_percentage.numeric' => 'درصد سود باید عددی باشد.',
            'profit_percentage.min' => 'درصد سود نمی‌تواند منفی باشد.',
            'profit_percentage.max' => 'درصد سود نمی‌تواند بیشتر از ۱۰۰ باشد.',
            'profit_amount.required_if' => 'مبلغ سود را وارد کنید.',
            'profit_amount.numeric' => 'مبلغ سود باید عددی باشد.',
            'profit_amount.min' => 'مبلغ سود نمی‌تواند منفی باشد.',
            'profit_amount.max' => 'مبلغ سود نمی‌تواند بیشتر از مبلغ فاکتور باشد.',
        ]);

        $percentage = $validated['profit_input_type'] === 'percentage'
            ? (float) $validated['profit_percentage']
            : null;

        $profitAmount = $validated['profit_input_type'] === 'percentage'
            ? round($totalAmount * $percentage / 100, 2)
            : round((float) $validated['profit_amount'], 2);

        DB::transaction(function () use (
            $invoice,
            $validated,
            $percentage,
            $profitAmount,
            $activityLogger
        ) {
            $lockedInvoice = Invoice::query()
                ->whereKey($invoice->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldProfitAmount = $lockedInvoice->profit_amount;

            $lockedInvoice->forceFill([
                'profit_input_type' => $validated['profit_input_type'],
                'profit_percentage' => $percentage,
                'profit_amount' => $profitAmount,
            ])->save();

            $commissions = $lockedInvoice->commissions()->get();

            foreach ($commissions as $commission) {
                $commission->update([
                    'amount' => round(
                        $profitAmount * (float) $commission->percentage / 100,
                        2
                    ),
                ]);
            }

            $activityLogger->log(
                'invoice_profit_updated',
                'ثبت یا ویرایش سود فاکتور شماره ' . $lockedInvoice->document_number,
                $lockedInvoice,
                [
                    'old_profit_amount' => $oldProfitAmount,
                    'new_profit_amount' => $profitAmount,
                    'profit_input_type' => $validated['profit_input_type'],
                    'profit_percentage' => $percentage,
                ]
            );
        }, 5);

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', 'سود فاکتور با موفقیت ذخیره شد.');
    }
}