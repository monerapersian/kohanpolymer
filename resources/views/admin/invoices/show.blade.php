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
    @font-face {
        font-family: 'kohan';
        src: url('../font/Kohan-Medium.woff2') format('woff2');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
    }

    @font-face {
        font-family: 'kohanBold';
        src: url('../font/Kohan-Bold.woff2') format('woff2');
        font-weight: 700;
        font-style: normal;
        font-display: swap;
    }

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
            width: 100% !important;
            min-width: 0 !important;
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
            position: absolute !important;
            top: 0 !important;
            right: 0 !important;
            left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            color: #362727 !important;
            background: #fff !important;
            direction: rtl !important;
            font-family: 'kohan', Tahoma, Arial, sans-serif !important;
            font-size: 8pt !important;
            font-weight: 400;
            line-height: 1.7 !important;
            text-align: right;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet *,
        #invoice-print-sheet *::before,
        #invoice-print-sheet *::after {
            box-sizing: border-box !important;
            font-family: inherit;
        }

        #invoice-print-sheet h1,
        #invoice-print-sheet h2,
        #invoice-print-sheet h3,
        #invoice-print-sheet h4,
        #invoice-print-sheet strong,
        #invoice-print-sheet b,
        #invoice-print-sheet .kohan-bold {
            font-family: 'kohanBold', Tahoma, Arial, sans-serif !important;
            font-weight: 700 !important;
        }

        #invoice-print-sheet img {
            max-width: 100%;
            height: auto;
            border: 0;
        }

        #invoice-print-sheet a {
            color: inherit;
            text-decoration: none;
        }

        #invoice-print-sheet p {
            margin-top: 0;
            margin-bottom: 6px;
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
            min-width: 0;
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
            background: #F4F0EE !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet .print-qr-card img {
            display: block;
            flex: 0 0 70px;
            width: 70px;
            min-width: 70px;
            height: 70px;
            padding: 3px;
            background: #fff !important;
            object-fit: contain;
        }

        #invoice-print-sheet .print-qr-copy {
            min-width: 0;
            font-size: 7pt;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #invoice-print-sheet .print-qr-copy strong {
            display: block;
            margin-bottom: 4px;
            font-size: 8pt;
        }

        #invoice-print-sheet .print-main-title {
            min-width: 0;
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
            display: inline-block;
            max-width: 100%;
            padding: 7px 9px;
            border-radius: 24px;
            background: #674040 !important;
            color: #fff !important;
            font-size: 10pt;
            font-weight: bold;
            overflow-wrap: anywhere;
            word-break: break-word;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet .print-document-meta {
            display: grid;
            gap: 7px;
            min-width: 0;
            padding-right: 12px;
            border-right: 1px solid #baadad;
        }

        #invoice-print-sheet .print-meta-row {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        #invoice-print-sheet .print-meta-row strong {
            white-space: nowrap;
        }

        #invoice-print-sheet .print-meta-value {
            min-width: 0;
            text-align: left;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #invoice-print-sheet .print-status {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            background: #d8f5e2 !important;
            color: #16803d !important;
            font-weight: bold;
            text-align: center;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet .print-status.is-invalid {
            background: #f9e0e0 !important;
            color: #a51d2d !important;
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
            background: #fff !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        #invoice-print-sheet .print-card-title {
            display: flex;
            align-items: center;
            gap: 7px;
            min-height: 34px;
            padding: 7px 11px;
            background: #F4F0EE !important;
            color: #362727 !important;
            font-size: 9pt;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            min-width: 0;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #invoice-print-sheet .print-items {
            margin-bottom: 15px;
            overflow: hidden;
            border: 1px solid #d8dfe4;
            border-radius: 8px;
        }

        #invoice-print-sheet table {
            width: 100% !important;
            max-width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            table-layout: fixed !important;
            font-size: 6.5pt;
            direction: rtl;
        }

        #invoice-print-sheet th,
        #invoice-print-sheet td {
            padding: 7px 4px;
            border-left: 1px solid #e1e5e8;
            border-bottom: 1px solid #e1e5e8;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #invoice-print-sheet th {
            background: #674040 !important;
            color: #fff !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet tbody tr:nth-child(even) {
            background: #F4F0EE !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet tbody tr {
            break-inside: avoid;
            page-break-inside: avoid;
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
            word-break: break-word;
        }

        #invoice-print-sheet .print-notes-list li:last-child {
            margin-bottom: 0;
        }

        #invoice-print-sheet .print-note-text {
            white-space: pre-line;
            overflow-wrap: anywhere;
            word-break: break-word;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
            padding: 10px;
            border: 0;
            border-radius: 5px;
            background: #f8e9ec !important;
            color: #674040 !important;
            font-size: 10pt;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            min-width: 0;
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
            word-break: break-word;
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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #invoice-print-sheet .print-plain-url {
            display: block;
            margin-top: 4px;
            direction: ltr;
            text-align: right;
            overflow-wrap: anywhere;
            word-break: break-word;
            font-size: 6pt;
        }

        #invoice-print-sheet .print-page-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        #invoice-print-sheet .print-header,
        #invoice-print-sheet .print-overview,
        #invoice-print-sheet .print-footer,
        #invoice-print-sheet .print-bottom-bar {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

<div class="space-y-6" dir="rtl">

    {{-- Page Header --}}
    <section class="hero-animated-bg relative isolate overflow-hidden rounded-3xl p-6 sm:p-8 lg:p-10">

        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="hero-orb hero-orb-three"></div>

        <div class="hero-wave"></div>

        <div class="hero-ring hero-ring-one"></div>
        <div class="hero-ring hero-ring-two"></div>

        <div class="hero-grid"></div>
        <!-- <div class="hero-shine"></div> -->

        <div class="relative z-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-start">

            <div>
                <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs text-white/80 backdrop-blur-sm">
                    مدیریت اسناد مالی
                </span>

                <h1 class="mt-4 text-2xl text-white kohan-bold sm:text-3xl">
                    جزئیات {{ $documentType }}
                </h1>

                <p class="mt-3 text-sm text-white/65">
                    شماره سند:
                    <span class="text-white/90">
                        {{ $formatDigits($invoice->document_number) }}
                    </span>
                </p>
            </div>

            <a
                href="{{ route('admin.invoices.index') }}"
                class="inline-flex shrink-0 cursor-pointer items-center gap-2 self-start rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white backdrop-blur-sm transition hover:bg-white/20"
            >
                <span>←</span>
                بازگشت به فهرست اسناد
            </a>

        </div>
    </section>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Document Actions --}}
    <div class="flex flex-wrap gap-3">

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#362727]"
        >
            <span>چاپ سند</span>
        </button>

        <a
            href="{{ route('admin.invoices.export', $invoice) }}"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#baadad] bg-white px-5 py-3 text-sm text-[#674040] transition hover:border-[#674040] hover:bg-[#F4F0EE]"
        >
            خروجی اکسل
        </a>

        <a
            href="{{ $verificationUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#baadad] bg-white px-5 py-3 text-sm text-[#674040] transition hover:border-[#674040] hover:bg-[#F4F0EE]"
        >
            مشاهده صفحه تأیید سند
        </a>

    </div>


    {{-- Document Information --}}
    <section class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg text-[#362727] kohan-bold">
                اطلاعات سند
            </h2>

            @if($isVoided)
                <span class="rounded-full bg-rose-100 px-3 py-1.5 text-sm text-rose-700">
                    باطل شده
                </span>
            @elseif($isExpired)
                <span class="rounded-full bg-orange-100 px-3 py-1.5 text-sm text-orange-700">
                    منقضی
                </span>
            @else
                <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-sm text-emerald-700">
                    معتبر
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-sm text-[#9A8E89]">نوع سند</p>
                <p class="mt-2 text-sm text-[#362727] kohan-bold">
                    {{ $documentType }}
                </p>
            </div>

            <div>
                <p class="text-sm text-[#9A8E89]">شماره سند</p>
                <p class="mt-2 text-sm text-[#362727] kohan-bold">
                    {{ $formatDigits($invoice->document_number) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-[#9A8E89]">مشتری</p>
                <p class="mt-2 text-sm text-[#362727] kohan-bold">
                    {{ $customerName }}
                </p>
            </div>

            <div>
                <p class="text-sm text-[#9A8E89]">تاریخ صدور</p>
                <p class="mt-2 text-sm text-[#362727] kohan-bold">
                    {{ $invoice->issue_date ? $formatDate($invoice->issue_date) : '—' }}
                </p>
            </div>

            @if($invoice->type === 'proforma')
                <div>
                    <p class="text-sm text-[#9A8E89]">معتبر تا تاریخ</p>
                    <p class="mt-2 text-sm text-[#362727] kohan-bold">
                        {{ $invoice->valid_until ? $formatDate($invoice->valid_until) : '—' }}
                    </p>
                </div>
            @endif

            <div>
                <p class="text-sm text-[#9A8E89]">نوع پرداخت</p>
                <p class="mt-2 text-sm text-[#362727] kohan-bold">
                    {{ $invoice->payment_type ?: '—' }}
                </p>
            </div>

            @if($invoice->convertedFrom)
                <div>
                    <p class="text-sm text-[#9A8E89]">پیش‌فاکتور مبنا</p>

                    <a
                        href="{{ route('admin.invoices.show', $invoice->convertedFrom) }}"
                        class="mt-2 inline-block cursor-pointer text-sm text-[#674040] transition hover:text-[#362727] hover:underline"
                    >
                        {{ $formatDigits($invoice->convertedFrom->document_number) }}
                    </a>
                </div>
            @endif

            @if($invoice->convertedTo->isNotEmpty())
                <div>
                    <p class="text-sm text-[#9A8E89]">فاکتور صادرشده از این پیش‌فاکتور</p>

                    @foreach($invoice->convertedTo as $convertedInvoice)
                        <a
                            href="{{ route('admin.invoices.show', $convertedInvoice) }}"
                            class="mt-2 block cursor-pointer text-sm text-[#674040] transition hover:text-[#362727] hover:underline"
                        >
                            {{ $formatDigits($convertedInvoice->document_number) }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>

        @if($invoice->description)
            <div class="mt-6 border-t border-[#EEE8E5] pt-5">
                <p class="text-sm text-[#9A8E89]">
                    توضیحات سند
                </p>

                <p class="mt-3 whitespace-pre-line text-sm leading-8 text-[#5F514D]">
                    {{ $invoice->description }}
                </p>
            </div>
        @endif

    </section>


    {{-- Document Items --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="border-b border-[#EEE8E5] p-5 sm:p-6">
            <h2 class="text-lg text-[#362727] kohan-bold">
                اقلام سند
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-right text-sm">

                <thead class="bg-[#F3E9E9] text-[#674040]">
                    <tr>
                        <th class="px-4 py-4 kohan-bold">ردیف</th>
                        <th class="px-4 py-4 kohan-bold">شرح کالا</th>
                        <th class="px-4 py-4 kohan-bold">تعداد</th>
                        <th class="px-4 py-4 kohan-bold">واحد</th>
                        <th class="px-4 py-4 kohan-bold">عرض</th>
                        <th class="px-4 py-4 kohan-bold">ضخامت</th>
                        <th class="px-4 py-4 kohan-bold">قیمت واحد (ریال)</th>
                        <th class="px-4 py-4 kohan-bold">مبلغ کل (ریال)</th>
                        <th class="px-4 py-4 kohan-bold">مالیات (%)</th>
                        <th class="px-4 py-4 kohan-bold">مبلغ مالیات (ریال)</th>
                        <th class="px-4 py-4 kohan-bold">مبلغ نهایی (ریال)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#F0EBE8]">

                    @forelse($invoice->items as $item)
                        <tr class="text-[#5F514D] transition hover:bg-[#FAF8F7]">

                            <td class="px-4 py-4">
                                {{ $formatDigits($loop->iteration) }}
                            </td>

                            <td class="px-4 py-4 text-[#362727]">
                                {{ $item->description }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $formatNumber($item->quantity) }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->unit ?: '—' }}
                            </td>

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

                            <td class="px-4 py-4">
                                {{ $formatTaxRate($item->vat_percent) }}٪
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $formatNumber($item->vat_amount) }} ریال
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-[#674040] kohan-bold">
                                {{ $formatNumber($item->grand_total) }} ریال
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-10 text-center text-sm text-[#9A8E89]">
                                هیچ قلمی برای این سند ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

                @if($invoice->items->isNotEmpty())
                    <tfoot class="border-t-2 border-[#baadad] bg-[#F3E9E9]">

                        <tr>
                            <td colspan="7" class="px-4 py-5 text-left text-[#362727] kohan-bold">
                                جمع کل سند
                            </td>

                            <td class="whitespace-nowrap px-4 py-5 text-[#362727] kohan-bold">
                                {{ $formatNumber($totalAmount) }} ریال
                            </td>

                            <td></td>

                            <td class="whitespace-nowrap px-4 py-5 text-[#362727] kohan-bold">
                                {{ $formatNumber($totalVatAmount) }} ریال
                            </td>

                            <td class="whitespace-nowrap px-4 py-5 text-[#674040] kohan-bold">
                                {{ $formatNumber($grandTotal) }} ریال
                            </td>
                        </tr>

                    </tfoot>
                @endif

            </table>

        </div>
    </section>


    {{-- Additional Notes --}}
    @if($invoice->notes->isNotEmpty())
        <section class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

            <h2 class="mb-5 text-lg text-[#362727] kohan-bold">
                توضیحات تکمیلی
            </h2>

            <ol class="list-inside list-decimal space-y-3 text-sm leading-8 text-[#5F514D]">
                @foreach($invoice->notes as $note)
                    <li>{{ $note->description }}</li>
                @endforeach
            </ol>

        </section>
    @endif


    {{-- QR Verification --}}
    <section class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

        <h2 class="mb-5 text-lg text-[#362727] kohan-bold">
            تأیید اصالت سند با QR
        </h2>

        <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center">

            @if(!empty($qrCodeDataUri))
                <img
                    src="{{ $qrCodeDataUri }}"
                    alt="QR تأیید سند"
                    class="h-40 w-40 rounded-xl border border-[#E8E1DE] bg-white p-2"
                >
            @else
                <div class="flex h-40 w-40 items-center justify-center rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 text-center text-sm text-[#9A8E89]">
                    کد QR تولید نشده است
                </div>
            @endif

            <div class="min-w-0 space-y-3">

                <p class="text-sm text-[#362727] kohan-bold">
                    وضعیت فعلی: {{ $statusText }}
                </p>

                <p class="text-sm leading-7 text-[#827875]">
                    با اسکن این کد، صفحه عمومی بررسی اعتبار همین سند باز می‌شود.
                </p>

                <a
                    href="{{ $verificationUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block cursor-pointer break-all text-sm text-[#674040] transition hover:text-[#362727] hover:underline"
                >
                    {{ $verificationUrl }}
                </a>

            </div>
        </div>

    </section>


    {{-- Document Operations --}}
    <div class="flex flex-wrap gap-3">

        @if($invoice->type === 'proforma'
            && $invoice->status !== 'voided'
            && !$invoice->isExpired()
            && $invoice->convertedTo->isEmpty())

            <form
                action="{{ route('admin.invoices.convert', $invoice) }}"
                method="POST"
                onsubmit="return confirm('پیش‌فاکتور به فاکتور تبدیل شود؟');"
            >
                @csrf

                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#362727]"
                >
                    تبدیل به فاکتور
                </button>
            </form>

        @endif

        @if($invoice->status !== 'voided')

            <form
                action="{{ route('admin.invoices.void', $invoice) }}"
                method="POST"
                onsubmit="return confirm('از باطل کردن این سند مطمئن هستید؟ این عملیات قابل بازگشت نیست.');"
            >
                @csrf

                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-red-700 px-5 py-3 text-sm text-white transition hover:bg-red-600"
                >
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
            <h1 class="print-document-heading kohan-bold">{{ $documentType }}</h1>

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
            <div class="print-subtitle">تامین کننده مواد اولیه و محصولات پلیمری</div>
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