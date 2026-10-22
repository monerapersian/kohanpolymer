<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceExport implements FromArray, WithHeadings, ShouldAutoSize, WithTitle
{
    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function headings(): array
    {
        return [
            'نوع سند',
            'شماره سند',
            'وضعیت',
            'نام مشتری',
            'تاریخ صدور',
            'معتبر تا تاریخ',
            'نوع پرداخت',
            'رسمی',
            'شرح سند',
            'شرح محصول',
            'تعداد',
            'واحد',
            'عرض',
            'ضخامت',
            'قیمت واحد',
            'مبلغ ردیف',
            'درصد مالیات',
            'مبلغ مالیات',
            'مبلغ نهایی',
            'توضیحات',
        ];
    }

    public function array(): array
    {
        $invoice = $this->invoice;
        $invoice->loadMissing(['customer', 'items', 'notes']);

        $documentType = $invoice->type === 'proforma'
            ? 'پیش‌فاکتور'
            : 'فاکتور';

        $status = $invoice->status === 'voided'
            ? 'باطل شده'
            : ($invoice->isExpired() ? 'منقضی شده' : 'معتبر');

        $customerName = $invoice->customer->name
            ?? $invoice->customer->company_name
            ?? '—';

        $notes = $invoice->notes
            ->map(fn ($note) => $note->content ?? $note->text ?? '')
            ->filter()
            ->implode(' | ');

        $items = $invoice->items;

        if ($items->isEmpty()) {
            return [[
                $documentType,
                $invoice->document_number,
                $status,
                $customerName,
                $invoice->issue_date?->format('Y-m-d'),
                $invoice->valid_until?->format('Y-m-d'),
                $invoice->payment_type,
                $invoice->is_formal ? 'بله' : 'خیر',
                $invoice->description,
                '—',
                0,
                '—',
                '—',
                '—',
                0,
                0,
                0,
                0,
                0,
                $notes,
            ]];
        }

        return $items->map(function ($item) use (
            $invoice,
            $documentType,
            $status,
            $customerName,
            $notes
        ) {
            $quantity = (float) ($item->quantity ?? 0);
            $unitPrice = (float) ($item->unit_price ?? $item->price ?? 0);
            $lineTotal = (float) (
                $item->total
                ?? $item->total_price
                ?? ($quantity * $unitPrice)
            );

            return [
                $documentType,
                $invoice->document_number,
                $status,
                $customerName,
                $invoice->issue_date?->format('Y-m-d'),
                $invoice->valid_until?->format('Y-m-d'),
                $invoice->payment_type,
                $invoice->is_formal ? 'بله' : 'خیر',
                $invoice->description,
                $item->description ?? $item->product_name ?? $item->name ?? '—',
                $item->quantity ?? 0,
                $item->unit ?? '—',
                $item->width ?? '—',
                $item->thickness ?? '—',
                $unitPrice,
                $lineTotal,
                $item->tax_percent ?? $item->tax_rate ?? 0,
                $item->tax_amount ?? 0,
                $invoice->total_amount
                    ?? $invoice->grand_total
                    ?? $invoice->total
                    ?? $lineTotal,
                $notes,
            ];
        })->all();
    }

    public function title(): string
    {
        return 'اطلاعات سند';
    }
}