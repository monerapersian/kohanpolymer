@extends('layouts.admin')

@section('content')
@php
    $isVoided = $invoice->status === 'voided';
    $isExpired = !$isVoided && $invoice->isExpired();

    $statusText = $isVoided
        ? 'باطل شده'
        : ($isExpired ? 'منقضی شده' : 'معتبر');

    $documentType = $invoice->type === 'invoice' ? 'فاکتور' : 'پیش‌فاکتور';

    $customerName = $invoice->customer->name
        ?? $invoice->customer->company_name
        ?? '—';

    $verificationUrl = route('invoices.verify', $invoice->qr_token);

    $siteContent = \App\Models\SiteContent::first();

    $logoUrl = $siteContent?->logo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($siteContent->logo)
        : null;

    $totalAmount = $invoice->items->sum('total_amount');
    $totalVatAmount = $invoice->items->sum('vat_amount');
    $grandTotal = $invoice->items->sum('grand_total');

    // Format monetary amounts without decimal places.
    $formatNumber = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 0, true);

    // Format VAT rates with one decimal place.
    $formatTaxRate = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 1, true);

    // Format measurements with up to two decimal places.
    $formatMeasurement = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 2, true);

    // Convert digits to Persian numerals.
    $formatDigits = static fn ($value) =>
        \App\Support\NumberHelper::toPersian($value);

    // Format dates using the Jalali calendar and Persian numerals.
    $formatDate = static fn ($value) =>
        \App\Support\NumberHelper::toPersian(
            \App\Support\DateHelper::toPersian($value, 'Y/m/d')
        );
@endphp

<style>
    .invoice-print-sheet {
        display: none;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 7mm;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden !important;
        }

        #invoice-print-sheet,
        #invoice-print-sheet * {
            visibility: visible !important;
        }

        #invoice-print-sheet {
            display: block !important;
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            color: #362727;
            background: #fff;
            direction: rtl;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.7;
        }

        #invoice-print-sheet * {
            box-sizing: border-box;
        }

        #invoice-print-sheet .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 5px 0 13px;
            border-bottom: 2px solid #674040;
        }

        #invoice-print-sheet .print-company-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
            color: #362727;
            font-size: 7.5pt;
        }

        #invoice-print-sheet .print-company-line {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #invoice-print-sheet .print-company-icon {
            display: inline-flex;
            flex: 0 0 14px;
            align-items: center;
            justify-content: center;
            color: #674040;
            font-weight: bold;
        }

        #invoice-print-sheet .print-brand {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
        }

        #invoice-print-sheet .print-logo {
            display: block;
            width: auto;
            max-width: 100px;
            max-height: 75px;
            object-fit: contain;
        }

        #invoice-print-sheet .print-brand-name {
            color: #362727;
            font-size: 19pt;
            font-weight: bold;
            line-height: 1.5;
        }

        #invoice-print-sheet .print-subtitle {
            margin-top: 2px;
            color: #674040;
            font-size: 8pt;
        }

        #invoice-print-sheet .print-overview {
            display: grid;
            grid-template-columns: 1fr 1.1fr 1fr;
            align-items: center;
            gap: 12px;
            margin: 14px 0 16px;
        }

        #invoice-print-sheet .print-qr-card {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            padding: 9px;
            border: 1px solid #d8dfe4;
            border-radius: 8px;
            background: #F4F0EE;
        }

        #invoice-print-sheet .print-qr-card img {
            flex: 0 0 70px;
            width: 70px;
            height: 70px;
            padding: 3px;
            background: #fff;
            object-fit: contain;
        }

        #invoice-print-sheet .print-qr-copy {
            min-width: 0;
            font-size: 7pt;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-qr-copy strong {
            display: block;
            margin-bottom: 4px;
            font-size: 8pt;
        }

        #invoice-print-sheet .print-main-title {
            text-align: center;
        }

        #invoice-print-sheet .print-document-heading {
            margin: 0 0 8px;
            color: #362727;
            font-size: 20pt;
            font-weight: bold;
            line-height: 1.5;
        }

        #invoice-print-sheet .print-document-number {
            padding: 7px 9px;
            border-radius: 24px;
            background: #674040;
            color: #fff;
            font-size: 10pt;
            font-weight: bold;
            overflow-wrap: anywhere;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-document-meta {
            display: grid;
            gap: 7px;
            padding-right: 12px;
            border-right: 1px solid #baadad;
        }

        #invoice-print-sheet .print-meta-row {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 8px;
        }

        #invoice-print-sheet .print-meta-row strong {
            white-space: nowrap;
        }

        #invoice-print-sheet .print-meta-value {
            text-align: left;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-status {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            background: #d8f5e2;
            color: #16803d;
            font-weight: bold;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-status.is-invalid {
            background: #f9e0e0;
            color: #a51d2d;
        }

        #invoice-print-sheet .print-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }

        #invoice-print-sheet .print-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #d8dfe4;
            border-radius: 8px;
            background: #fff;
            break-inside: avoid;
        }

        #invoice-print-sheet .print-card-title {
            display: flex;
            align-items: center;
            gap: 7px;
            min-height: 34px;
            padding: 7px 11px;
            background: #F4F0EE;
            color: #362727;
            font-size: 9pt;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-card-title .print-card-icon {
            color: #674040;
            font-size: 12pt;
        }

        #invoice-print-sheet .print-card-body {
            padding: 8px 11px;
        }

        #invoice-print-sheet .print-info-row {
            display: grid;
            grid-template-columns: 40% 60%;
            gap: 6px;
            padding: 5px 0;
            align-items: start;
        }

        #invoice-print-sheet .print-info-row + .print-info-row {
            border-top: 1px solid #f0ebeb;
        }

        #invoice-print-sheet .print-info-label {
            font-weight: bold;
        }

        #invoice-print-sheet .print-info-value {
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-items {
            margin-bottom: 15px;
            overflow: hidden;
            border: 1px solid #d8dfe4;
            border-radius: 8px;
        }

        #invoice-print-sheet table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 6.5pt;
        }

        #invoice-print-sheet th,
        #invoice-print-sheet td {
            padding: 7px 4px;
            border-left: 1px solid #e1e5e8;
            border-bottom: 1px solid #e1e5e8;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet th {
            background: #674040 !important;
            color: #fff !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet tbody tr:nth-child(even) {
            background: #F4F0EE !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet tbody tr {
            break-inside: avoid;
        }

        #invoice-print-sheet .print-product-name {
            text-align: right;
        }

        #invoice-print-sheet .print-product-dimensions {
            display: block;
            margin-top: 2px;
            color: #8d6666;
            font-size: 6pt;
        }

        #invoice-print-sheet .print-bottom-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 12px;
            align-items: stretch;
        }

        #invoice-print-sheet .print-notes-list {
            margin: 0;
            padding: 0 15px 0 0;
        }

        #invoice-print-sheet .print-notes-list li {
            margin: 0 0 5px;
            padding-right: 2px;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-notes-list li:last-child {
            margin-bottom: 0;
        }

        #invoice-print-sheet .print-totals-body {
            padding: 8px 11px;
        }

        #invoice-print-sheet .print-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            padding: 7px 0;
            border-bottom: 1px solid #e8e0e0;
        }

        #invoice-print-sheet .print-total-row strong {
            white-space: nowrap;
        }

        #invoice-print-sheet .print-grand-total {
            margin-top: 5px;
            padding: 10px;
            border: 0;
            border-radius: 5px;
            background: #f8e9ec !important;
            color: #674040;
            font-size: 10pt;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-footer {
            display: grid;
            grid-template-columns: 1fr 1.3fr 0.8fr;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            padding: 8px 0 12px;
        }

        #invoice-print-sheet .print-verification-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 54px;
            padding: 8px;
            border-radius: 8px;
            background: #674040 !important;
            color: #fff !important;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-verification-badge strong {
            display: block;
            font-size: 8pt;
        }

        #invoice-print-sheet .print-verification-badge small {
            display: block;
            margin-top: 3px;
            font-size: 6pt;
        }

        #invoice-print-sheet .print-shield {
            font-size: 23pt;
            line-height: 1;
        }

        #invoice-print-sheet .print-footer-qr {
            text-align: center;
            font-size: 7pt;
        }

        #invoice-print-sheet .print-footer-qr img {
            display: block;
            width: 65px;
            height: 65px;
            margin: 0 auto 4px;
            object-fit: contain;
        }

        #invoice-print-sheet .print-footer-qr a {
            color: #674040;
            overflow-wrap: anywhere;
            text-decoration: none;
        }

        #invoice-print-sheet .print-footer-brand {
            color: #362727;
            font-weight: bold;
            text-align: center;
        }

        #invoice-print-sheet .print-bottom-bar {
            margin-top: 3px;
            padding: 12px 8px;
            background: #674040 !important;
            color: #fff !important;
            text-align: center;
            font-size: 8pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #invoice-print-sheet .print-note-text {
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-plain-url {
            display: block;
            margin-top: 4px;
            direction: ltr;
            text-align: right;
            overflow-wrap: anywhere;
            font-size: 6pt;
        }

        #invoice-print-sheet .print-page-break {
            break-inside: avoid;
        }

        @font-face {
            font-family: 'kohan';
            src: url('../font/Kohan-Medium.woff2');
        }

        @font-face {
            font-family: 'kohanBold';
            src: url('../font/Kohan-Bold.woff2');
        }

        body {
            font-family: 'kohan';
        }

        h1, h2, h3, h4{
            font-family: 'kohanBold';
        }

        .kohan-bold{
            font-family: kohanBold;
        }
    }
</style>

<div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6" dir="rtl">

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#fff]">
                جزئیات {{ $documentType }}
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                شماره سند: {{ $formatDigits($invoice->document_number) }}
            </p>
        </div>

        <a href="{{ route('admin.invoices.index') }}"
           class="rounded-xl border border-[#baadad] px-4 py-2 text-sm font-semibold text-[#674040] transition hover:bg-[#F4F0EE]">
            بازگشت به فهرست
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-wrap gap-3">
        <button type="button"
                onclick="window.print()"
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#674040] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#362727]">
            <span>چاپ سند</span>
        </button>

        <a href="{{ route('admin.invoices.export', $invoice) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-[#8d6666] px-5 py-3 text-sm font-semibold text-[#674040] transition hover:bg-[#674040] hover:text-white">
            خروجی اکسل
        </a>

        <a href="{{ $verificationUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center gap-2 rounded-xl border border-[#baadad] px-5 py-3 text-sm font-semibold text-[#674040] transition hover:bg-[#F4F0EE]">
            مشاهده صفحه تأیید سند
        </a>
    </div>

    <div class="rounded-2xl border border-[#baadad] bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-[#362727]">اطلاعات سند</h2>

            @if($isVoided)
                <span class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700">
                    باطل شده
                </span>
            @elseif($isExpired)
                <span class="rounded-full bg-orange-100 px-3 py-1.5 text-sm font-semibold text-orange-700">
                    منقضی
                </span>
            @else
                <span class="rounded-full bg-[#F4F0EE] px-3 py-1.5 text-sm font-semibold text-[#674040]">
                    معتبر
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">نوع سند</p>
                <p class="mt-1 font-semibold text-[#362727]">{{ $documentType }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">شماره سند</p>
                <p class="mt-1 font-semibold text-[#362727]">
                    {{ $formatDigits($invoice->document_number) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">مشتری</p>
                <p class="mt-1 font-semibold text-[#362727]">{{ $customerName }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">تاریخ صدور</p>
                <p class="mt-1 font-semibold text-[#362727]">
                    {{ $invoice->issue_date ? $formatDate($invoice->issue_date) : '—' }}
                </p>
            </div>

            @if($invoice->type === 'proforma')
                <div>
                    <p class="text-sm text-gray-500">معتبر تا تاریخ</p>
                    <p class="mt-1 font-semibold text-[#362727]">
                        {{ $invoice->valid_until ? $formatDate($invoice->valid_until) : '—' }}
                    </p>
                </div>
            @endif

            <div>
                <p class="text-sm text-gray-500">نوع پرداخت</p>
                <p class="mt-1 font-semibold text-[#362727]">{{ $invoice->payment_type ?: '—' }}</p>
            </div>

            @if($invoice->convertedFrom)
                <div>
                    <p class="text-sm text-gray-500">پیش‌فاکتور مبنا</p>
                    <a href="{{ route('admin.invoices.show', $invoice->convertedFrom) }}"
                       class="mt-1 inline-block font-semibold text-[#674040] hover:underline">
                        {{ $formatDigits($invoice->convertedFrom->document_number) }}
                    </a>
                </div>
            @endif

            @if($invoice->convertedTo->isNotEmpty())
                <div>
                    <p class="text-sm text-gray-500">فاکتور صادرشده از این پیش‌فاکتور</p>
                    @foreach($invoice->convertedTo as $convertedInvoice)
                        <a href="{{ route('admin.invoices.show', $convertedInvoice) }}"
                           class="mt-1 block font-semibold text-[#674040] hover:underline">
                            {{ $formatDigits($convertedInvoice->document_number) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if($invoice->description)
            <div class="mt-6 border-t border-[#F4F0EE] pt-5">
                <p class="text-sm text-gray-500">توضیحات سند</p>
                <p class="mt-2 whitespace-pre-line leading-7 text-[#362727]">{{ $invoice->description }}</p>
            </div>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#baadad] bg-white shadow-sm">
        <div class="border-b border-[#F4F0EE] p-5">
            <h2 class="text-lg font-bold text-[#362727]">اقلام سند</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-right text-sm">
                <thead class="bg-[#F4F0EE] text-[#674040]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ردیف</th>
                        <th class="px-4 py-3 font-semibold">شرح کالا</th>
                        <th class="px-4 py-3 font-semibold">تعداد</th>
                        <th class="px-4 py-3 font-semibold">واحد</th>
                        <th class="px-4 py-3 font-semibold">عرض</th>
                        <th class="px-4 py-3 font-semibold">ضخامت</th>
                        <th class="px-4 py-3 font-semibold">قیمت واحد (ریال)</th>
                        <th class="px-4 py-3 font-semibold">مبلغ کل (ریال)</th>
                        <th class="px-4 py-3 font-semibold">مالیات (%)</th>
                        <th class="px-4 py-3 font-semibold">مبلغ مالیات (ریال)</th>
                        <th class="px-4 py-3 font-semibold">مبلغ نهایی (ریال)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#F4F0EE]">
                    @forelse($invoice->items as $item)
                        <tr class="text-[#362727]">
                            <td class="px-4 py-4">{{ $formatDigits($loop->iteration) }}</td>
                            <td class="px-4 py-4 font-medium">{{ $item->description }}</td>
                            <td class="px-4 py-4">{{ $formatNumber($item->quantity) }}</td>
                            <td class="px-4 py-4">{{ $item->unit ?: '—' }}</td>
                            <td class="px-4 py-4">
                                {{ $item->width !== null ? $formatMeasurement($item->width) : '—' }}
                            </td>
                            <td class="px-4 py-4">
                                {{ $item->thickness !== null ? $formatMeasurement($item->thickness) : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $formatNumber($item->unit_price) }} ریال
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $formatNumber($item->total_amount) }} ریال
                            </td>
                            <td class="px-4 py-4">{{ $formatTaxRate($item->vat_percent) }}٪</td>
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $formatNumber($item->vat_amount) }} ریال
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-semibold text-[#674040]">
                                {{ $formatNumber($item->grand_total) }} ریال
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                                هیچ قلمی برای این سند ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($invoice->items->isNotEmpty())
                    <tfoot class="border-t-2 border-[#baadad] bg-[#F4F0EE]">
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-left font-bold text-[#362727]">
                                جمع کل سند
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-bold text-[#362727]">
                                {{ $formatNumber($totalAmount) }} ریال
                            </td>
                            <td></td>
                            <td class="whitespace-nowrap px-4 py-4 font-bold text-[#362727]">
                                {{ $formatNumber($totalVatAmount) }} ریال
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 font-bold text-[#674040]">
                                {{ $formatNumber($grandTotal) }} ریال
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if($invoice->notes->isNotEmpty())
        <div class="rounded-2xl border border-[#baadad] bg-white p-5 shadow-sm sm:p-6">
            <h2 class="mb-4 text-lg font-bold text-[#362727]">توضیحات تکمیلی</h2>

            <ol class="list-inside list-decimal space-y-3 text-sm leading-7 text-[#362727]">
                @foreach($invoice->notes as $note)
                    <li>{{ $note->description }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    <div class="rounded-2xl border border-[#baadad] bg-white p-5 shadow-sm sm:p-6">
        <h2 class="mb-4 text-lg font-bold text-[#362727]">تأیید اصالت سند با QR</h2>

        <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center">
            @if(!empty($qrCodeDataUri))
                <img src="{{ $qrCodeDataUri }}"
                     alt="QR تأیید سند"
                     class="h-40 w-40 rounded-xl border border-[#baadad] bg-white p-2">
            @else
                <div class="flex h-40 w-40 items-center justify-center rounded-xl border border-[#baadad] bg-[#F4F0EE] text-center text-sm text-gray-500">
                    کد QR تولید نشده است
                </div>
            @endif

            <div class="min-w-0 space-y-2">
                <p class="font-semibold text-[#362727]">
                    وضعیت فعلی: {{ $statusText }}
                </p>

                <p class="text-sm leading-6 text-gray-500">
                    با اسکن این کد، صفحه عمومی بررسی اعتبار همین سند باز می‌شود.
                </p>

                <a href="{{ $verificationUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="block break-all text-sm text-[#674040] hover:underline">
                    {{ $verificationUrl }}
                </a>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        @if($invoice->type === 'proforma'
            && $invoice->status !== 'voided'
            && !$invoice->isExpired()
            && $invoice->convertedTo->isEmpty())
            <form action="{{ route('admin.invoices.convert', $invoice) }}"
                  method="POST"
                  onsubmit="return confirm('پیش‌فاکتور به فاکتور تبدیل شود؟');">
                @csrf
                <button type="submit"
                        class="cursor-pointer rounded-xl bg-[#674040] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#362727]">
                    تبدیل به فاکتور
                </button>
            </form>
        @endif

        @if($invoice->status !== 'voided')
            <form action="{{ route('admin.invoices.void', $invoice) }}"
                  method="POST"
                  onsubmit="return confirm('از باطل کردن این سند مطمئن هستید؟ این عملیات قابل بازگشت نیست.');">
                @csrf
                <button type="submit"
                        class="cursor-pointer rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    باطل کردن سند
                </button>
            </form>
        @endif
    </div>
</div>

<div id="invoice-print-sheet" class="invoice-print-sheet" dir="rtl">
    @php
        $printPhone = $siteContent?->phone;
        $printEmail = $siteContent?->email;
        $printWebsite = 'www.kohanpolymeralborz.ir';

        $printNotes = collect();

        if ($invoice->description) {
            $printNotes->push($invoice->description);
        }

        foreach ($invoice->notes as $note) {
            $printNotes->push($note->description);
        }

        if ($printNotes->isEmpty()) {
            $printNotes = collect([
                'این سند بر اساس اطلاعات ثبت‌شده در سامانه کهن پلیمر البرز صادر شده است.',
                'برای بررسی اصالت سند، کد QR را اسکن کنید.',
            ]);
        }
    @endphp

    <header class="print-header">
        <div class="print-brand">
            @if($logoUrl)
                <img src="{{ $logoUrl }}"
                     alt="لوگوی کهن پلیمر البرز"
                     class="print-logo">
            @endif

            <div>
                <div class="print-brand-name kohan-bold">کهن پلیمر البرز</div>
                <div class="print-subtitle">KOHAN POLYMER ALBORZ</div>
            </div>
        </div>

        <div class="print-company-info" style="direction: ltr;">
            @if($printPhone)
                <div class="print-company-line kohan-bold">
                    <span class="print-company-icon">☎</span>
                    <span>{{ $formatDigits($printPhone) }}</span>
                </div>
            @endif

            @if($printEmail)
                <div class="print-company-line kohan-bold">
                    <span class="print-company-icon">✉</span>
                    <span>{{ $printEmail }}</span>
                </div>
            @endif

            <div class="print-company-line kohan-bold">
                <span class="print-company-icon">◎</span>
                <span>{{ $printWebsite }}</span>
            </div>
        </div>
    </header>

    <section class="print-overview">
        <div class="print-qr-card">
            @if(!empty($qrCodeDataUri))
                <img src="{{ $qrCodeDataUri }}" alt="QR اعتبارسنجی فاکتور">
            @endif

            <div class="print-qr-copy kohan-bold">
                <strong>کد اعتبارسنجی سند</strong>
                <div>برای بررسی اصالت سند، کد QR را اسکن کنید.</div>
            </div>
        </div>

        <div class="print-main-title">
            <h1 class="print-document-heading kohan-bold">{{ $documentType }} فروش</h1>

            <div class="print-document-number kohan-bold">
                شماره {{ $invoice->type === 'invoice' ? 'فاکتور' : 'پیش‌فاکتور' }}:
                {{ $formatDigits($invoice->document_number) }}
            </div>
        </div>

        <div class="print-document-meta">
            <div class="print-meta-row kohan-bold">
                <strong>تاریخ صدور:</strong>
                <span class="print-meta-value">
                    {{ $invoice->issue_date ? $formatDate($invoice->issue_date) : '—' }}
                </span>
            </div>

            @if($invoice->type === 'proforma')
                <div class="print-meta-row kohan-bold">
                    <strong>تاریخ اعتبار:</strong>
                    <span class="print-meta-value">
                        {{ $invoice->valid_until ? $formatDate($invoice->valid_until) : '—' }}
                    </span>
                </div>
            @endif

            <div class="print-meta-row kohan-bold">
                <strong>وضعیت:</strong>
                <span class="print-meta-value">
                    <span class="print-status {{ $isVoided || $isExpired ? 'is-invalid' : '' }}">
                        {{ $statusText }}
                    </span>
                </span>
            </div>

            <div class="print-meta-row kohan-bold">
                <strong>نوع پرداخت:</strong>
                <span class="print-meta-value">{{ $invoice->payment_type ?: '—' }}</span>
            </div>
        </div>
    </section>

    <section class="print-info-grid">
        <div class="print-card kohan-bold">
            <div class="print-card-title">
                <span class="print-card-icon">♙</span>
                مشخصات مشتری
            </div>

            <div class="print-card-body">
                <div class="print-info-row">
                    <span class="print-info-label">نام / نام شرکت:</span>
                    <span class="print-info-value">{{ $customerName }}</span>
                </div>

                @if(!empty($invoice->customer?->phone))
                    <div class="print-info-row">
                        <span class="print-info-label">شماره تماس:</span>
                        <span class="print-info-value">
                            {{ $formatDigits($invoice->customer->phone) }}
                        </span>
                    </div>
                @endif

                @if(!empty($invoice->customer?->address))
                    <div class="print-info-row">
                        <span class="print-info-label">آدرس:</span>
                        <span class="print-info-value">{{ $invoice->customer->address }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="print-card kohan-bold">
            <div class="print-card-title">
                <span class="print-card-icon">▤</span>
                اطلاعات سند
            </div>

            <div class="print-card-body kohan-bold">
                <div class="print-info-row">
                    <span class="print-info-label">شماره سند:</span>
                    <span class="print-info-value">
                        {{ $formatDigits($invoice->document_number) }}
                    </span>
                </div>

                <div class="print-info-row">
                    <span class="print-info-label">تاریخ صدور:</span>
                    <span class="print-info-value">
                        {{ $invoice->issue_date ? $formatDate($invoice->issue_date) : '—' }}
                    </span>
                </div>

                @if($invoice->type === 'proforma')
                    <div class="print-info-row">
                        <span class="print-info-label">معتبر تا:</span>
                        <span class="print-info-value">
                            {{ $invoice->valid_until ? $formatDate($invoice->valid_until) : '—' }}
                        </span>
                    </div>
                @endif

                <div class="print-info-row">
                    <span class="print-info-label">وضعیت:</span>
                    <span class="print-info-value">{{ $statusText }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="print-items kohan-bold">
        <table>
            <colgroup>
                <col style="width: 4%">
                <col style="width: 22%">
                <col style="width: 7%">
                <col style="width: 7%">
                <col style="width: 10%">
                <col style="width: 12%">
                <col style="width: 7%">
                <col style="width: 9%">
                <col style="width: 10%">
                <col style="width: 12%">
            </colgroup>

            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام کالا / شرح و ابعاد</th>
                    <th>تعداد</th>
                    <th>واحد</th>
                    <th>قیمت واحد (ریال)</th>
                    <th>قیمت کل (ریال)</th>
                    <th>مالیات (%)</th>
                    <th>مالیات (ریال)</th>
                    <th>مبلغ نهایی (ریال)</th>
                    <th>جزئیات</th>
                </tr>
            </thead>

            <tbody>
                @forelse($invoice->items as $item)
                    <tr>
                        <td>{{ $formatDigits($loop->iteration) }}</td>

                        <td class="print-product-name">
                            {{ $item->description }}

                            @if($item->width !== null || $item->thickness !== null)
                                <span class="print-product-dimensions">
                                    @if($item->width !== null)
                                        عرض: {{ $formatMeasurement($item->width) }}
                                    @endif

                                    @if($item->width !== null && $item->thickness !== null)
                                        |
                                    @endif

                                    @if($item->thickness !== null)
                                        ضخامت: {{ $formatMeasurement($item->thickness) }}
                                    @endif
                                </span>
                            @endif
                        </td>

                        <td>{{ $formatNumber($item->quantity) }}</td>
                        <td>{{ $item->unit ?: '—' }}</td>
                        <td>{{ $formatNumber($item->unit_price) }}</td>
                        <td>{{ $formatNumber($item->total_amount) }}</td>
                        <td>{{ $formatTaxRate($item->vat_percent) }}٪</td>
                        <td>{{ $formatNumber($item->vat_amount) }}</td>
                        <td>{{ $formatNumber($item->grand_total) }}</td>
                        <td>ریال</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">هیچ قلمی برای این سند ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="print-bottom-grid print-page-break">
        <div class="print-card">
            <div class="print-card-title kohan-bold">
                <span class="print-card-icon">▤</span>
                توضیحات
            </div>

            <div class="print-card-body kohan-bold">
                <ul class="print-notes-list">
                    @foreach($printNotes as $printNote)
                        <li>{{ $printNote }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="print-card kohan-bold">
            <div class="print-card-title" style="background:#674040;color:#fff;">
                جمع کل
            </div>

            <div class="print-totals-body">
                <div class="print-total-row">
                    <span>جمع جزئیات کالاها</span>
                    <strong>{{ $formatNumber($totalAmount) }} ریال</strong>
                </div>

                <div class="print-total-row">
                    <span>مالیات بر ارزش افزوده</span>
                    <strong>{{ $formatNumber($totalVatAmount) }} ریال</strong>
                </div>

                <div class="print-total-row print-grand-total">
                    <span>مبلغ کل</span>
                    <strong>{{ $formatNumber($grandTotal) }} ریال</strong>
                </div>
            </div>
        </div>
    </section>

    <footer class="print-footer print-page-break kohan-bold">
        <div class="print-footer-brand">
            <div>کهن پلیمر البرز</div>
            <div class="print-subtitle">عرضه کننده محصولات پلیمری</div>
        </div>

        <div class="print-verification-badge">
            <span class="print-shield">✓</span>
            <div>
                <strong>
                    @if($isVoided)
                        این سند باطل شده است
                    @elseif($isExpired)
                        اعتبار این سند به پایان رسیده است
                    @else
                        وضعیت سند: معتبر
                    @endif
                </strong>
            </div>
        </div>

        <div class="print-footer-qr kohan-bold">
            @if(!empty($qrCodeDataUri))
                <img src="{{ $qrCodeDataUri }}" alt="QR بررسی اصالت">
            @endif

            <strong>اسکن برای بررسی اصالت</strong>
        </div>
    </footer>

    <div class="print-bottom-bar kohan-bold">
        کهن پلیمر البرز
        &nbsp; | &nbsp;
        کیفیت امروز، اعتماد فردا
    </div>
</div>
@endsection