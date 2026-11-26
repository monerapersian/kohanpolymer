<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceCommission;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InvoiceCommissionController extends Controller
{
    public function update(
        Request $request,
        Invoice $invoice,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        abort_unless($invoice->type === 'invoice', 404);

        $validated = $request->validate([
            'commissions' => [
                'nullable',
                'array',
            ],

            'commissions.*.recipient_type' => [
                'required',
                'in:user,external',
            ],

            'commissions.*.user_id' => [
                'nullable',
                'required_if:commissions.*.recipient_type,user',
                'integer',
                Rule::exists('users', 'id')
                    ->whereIn('role', ['admin', 'super_admin']),
            ],

            'commissions.*.external_name' => [
                'nullable',
                'required_if:commissions.*.recipient_type,external',
                'string',
                'max:255',
            ],

            'commissions.*.percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
        ], [
            'commissions.array' => 'اطلاعات پورسانت معتبر نیست.',
            'commissions.*.recipient_type.required' => 'نوع دریافت‌کننده را انتخاب کنید.',
            'commissions.*.recipient_type.in' => 'نوع دریافت‌کننده معتبر نیست.',
            'commissions.*.user_id.required_if' => 'مدیر موردنظر را انتخاب کنید.',
            'commissions.*.user_id.exists' => 'مدیر انتخاب‌شده معتبر نیست.',
            'commissions.*.external_name.required_if' => 'نام رابط را وارد کنید.',
            'commissions.*.external_name.max' => 'نام رابط نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'commissions.*.percentage.required' => 'درصد پورسانت را وارد کنید.',
            'commissions.*.percentage.numeric' => 'درصد پورسانت باید عددی باشد.',
            'commissions.*.percentage.min' => 'درصد پورسانت نمی‌تواند منفی باشد.',
            'commissions.*.percentage.max' => 'درصد پورسانت نمی‌تواند بیشتر از ۱۰۰ باشد.',
        ]);

        $commissions = $validated['commissions'] ?? [];

        DB::transaction(function () use (
            $invoice,
            $commissions,
            $activityLogger
        ) {
            $lockedInvoice = Invoice::query()
                ->whereKey($invoice->id)
                ->lockForUpdate()
                ->firstOrFail();

            $profitAmount = (float) $lockedInvoice->profit_amount;

            if (count($commissions) > 0 && $profitAmount <= 0) {
                throw ValidationException::withMessages([
                    'commissions' => 'ابتدا باید سود فاکتور را ثبت کنید.',
                ]);
            }

            $oldCommissions = $lockedInvoice->commissions()
                ->get()
                ->map(function ($commission) {
                    return [
                        'recipient_type' => $commission->recipient_type,
                        'user_id' => $commission->user_id,
                        'external_name' => $commission->external_name,
                        'percentage' => $commission->percentage,
                        'amount' => $commission->amount,
                    ];
                })
                ->values()
                ->toArray();

            $lockedInvoice->commissions()->delete();

            foreach ($commissions as $commission) {
                $percentage = (float) $commission['percentage'];

                $amount = round(
                    $profitAmount * $percentage / 100,
                    2
                );

                InvoiceCommission::create([
                    'invoice_id' => $lockedInvoice->id,
                    'recipient_type' => $commission['recipient_type'],
                    'user_id' => $commission['recipient_type'] === 'user'
                        ? $commission['user_id']
                        : null,
                    'external_name' => $commission['recipient_type'] === 'external'
                        ? trim($commission['external_name'])
                        : null,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'created_by' => auth()->id(),
                ]);
            }

            $newCommissions = $lockedInvoice->commissions()
                ->get()
                ->map(function ($commission) {
                    return [
                        'recipient_type' => $commission->recipient_type,
                        'user_id' => $commission->user_id,
                        'external_name' => $commission->external_name,
                        'percentage' => $commission->percentage,
                        'amount' => $commission->amount,
                    ];
                })
                ->values()
                ->toArray();

            $activityLogger->log(
                'invoice_commissions_updated',
                'ثبت یا ویرایش پورسانت‌های فاکتور شماره ' . $lockedInvoice->document_number,
                $lockedInvoice,
                [
                    'old_commissions' => $oldCommissions,
                    'new_commissions' => $newCommissions,
                ]
            );
        }, 5);

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', 'پورسانت‌های فاکتور با موفقیت ذخیره شدند.');
    }
}