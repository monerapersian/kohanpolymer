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
    $isSuperAdmin = auth()->user()?->role === 'super_admin';
    $commissionUsers = \App\Models\User::query()
        ->whereIn('role', ['admin', 'super_admin'])
        ->orderBy('name')
        ->get();

    $commissionRows = old(
        'commissions',
        $invoice->commissions->map(fn ($commission) => [
            'recipient_type' => $commission->recipient_type,
            'user_id' => $commission->user_id,
            'external_name' => $commission->external_name,
            'percentage' => $commission->percentage,
        ])->values()->all()
    );

    $commissionTotal = (float) $invoice->commissions->sum('amount');

    $profitAmount = $invoice->profit_amount;
    $profitPercentage = $invoice->profit_percentage;
    $profitInputType = $invoice->profit_input_type;
    $paidAmount = (float) $invoice->payments->sum('amount');
    $paymentBalance = round((float) $grandTotal - $paidAmount, 2);
    $remainingAmount = max($paymentBalance, 0);
    $creditAmount = max(-$paymentBalance, 0);

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
        #invoice-print-sheet .print-overview.print-overview-informal {
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 14px;
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
                    <span class="text-white/90" style="direction: ltr; unicode-bidi: isolate; display: inline-block;">
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

        @if($invoice->is_formal)
            <a
                href="{{ $verificationUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#baadad] bg-white px-5 py-3 text-sm text-[#674040] transition hover:border-[#674040] hover:bg-[#F4F0EE]"
            >
                مشاهده صفحه تأیید سند
            </a>
        @endif
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
    @if($invoice->is_formal)
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
    @endif

    {{-- Invoice Profit --}}
    @if($invoice->type === 'invoice')
        <section
            class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm"
            id="invoice-profit-section"
        >

            <div class="border-b border-[#EEE8E5] p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">

                    <div>
                        <h2 class="text-lg text-[#362727] kohan-bold">
                            سود فاکتور
                        </h2>

                        <p class="mt-2 text-sm text-[#9A8E89]">
                            اطلاعات سود این فاکتور فقط در پنل مدیریت نمایش داده می‌شود.
                        </p>
                    </div>

                    @if($profitAmount !== null)
                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
                            سود ثبت شده
                        </span>
                    @else
                        <span class="rounded-full border border-[#E8E1DE] bg-[#F4F0EE] px-3 py-2 text-xs text-[#827875]">
                            سود ثبت نشده
                        </span>
                    @endif

                </div>
            </div>

            <div class="space-y-6 p-5 sm:p-6">

                {{-- Invoice Total --}}
                <div class="rounded-2xl border border-[#E8E1DE] bg-[#F4F0EE] p-5">

                    <p class="text-sm text-[#827875]">
                        مبلغ نهایی فاکتور
                    </p>

                    <div class="mt-3 flex flex-wrap items-baseline gap-2">
                        <span class="text-2xl text-[#362727] kohan-bold">
                            {{ $formatNumber($grandTotal) }}
                        </span>

                        <span class="text-sm text-[#827875]">
                            ریال
                        </span>
                    </div>

                </div>

                {{-- Saved Profit --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="rounded-2xl border border-[#E8E1DE] p-5">

                        <p class="text-sm text-[#9A8E89]">
                            میزان سود ثبت‌شده
                        </p>

                        <div class="mt-3 flex flex-wrap items-baseline gap-2">

                            <span class="text-xl text-[#674040] kohan-bold">
                                {{ $profitAmount !== null ? $formatNumber($profitAmount) : '—' }}
                            </span>

                            <span class="text-sm text-[#827875]">
                                ریال
                            </span>

                        </div>

                    </div>

                    <div class="rounded-2xl border border-[#E8E1DE] p-5">

                        <p class="text-sm text-[#9A8E89]">
                            روش تعیین سود
                        </p>

                        <p class="mt-3 text-base text-[#362727] kohan-bold">

                            @if($profitInputType === 'percentage')
                                درصدی
                                @if($profitPercentage !== null)
                                    ({{ $formatTaxRate($profitPercentage) }}٪)
                                @endif
                            @elseif($profitInputType === 'amount')
                                مبلغ ثابت
                            @else
                                ثبت نشده
                            @endif

                        </p>

                    </div>

                </div>

                {{-- Profit Form --}}
                @if($isSuperAdmin)

                    <div class="border-t border-[#EEE8E5] pt-6">

                        <h3 class="mb-2 text-base text-[#362727] kohan-bold">
                            میزان سود این فاکتور را مشخص نمایید
                        </h3>

                        <p class="mb-5 text-sm leading-7 text-[#9A8E89]">
                            می‌توانید سود را به‌صورت درصدی از مبلغ نهایی فاکتور یا به‌صورت مبلغ ثابت وارد کنید.
                        </p>

                        <form
                            action="{{ route('admin.invoices.profit.update', $invoice) }}"
                            method="POST"
                            id="invoice-profit-form"
                            class="space-y-5"
                        >
                            @csrf
                            @method('PUT')

                            {{-- Input Type --}}
                            <div>
                                <label
                                    for="profit_input_type"
                                    class="mb-2 block text-sm text-[#5F514D]"
                                >
                                    روش تعیین سود
                                </label>

                                <select
                                    name="profit_input_type"
                                    id="profit_input_type"
                                    required
                                    class="h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                >
                                    <option
                                        value="percentage"
                                        @selected(old('profit_input_type', $profitInputType) === 'percentage')
                                    >
                                        وارد کردن درصد
                                    </option>

                                    <option
                                        value="amount"
                                        @selected(old('profit_input_type', $profitInputType) === 'amount')
                                    >
                                        وارد کردن مبلغ
                                    </option>
                                </select>
                            </div>

                            {{-- Percentage Input --}}
                            <div id="profit-percentage-wrapper">

                                <label
                                    for="profit_percentage"
                                    class="mb-2 block text-sm text-[#5F514D]"
                                >
                                    درصد سود
                                </label>

                                <div class="relative">

                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        name="profit_percentage"
                                        id="profit_percentage"
                                        value="{{ old('profit_percentage', $profitInputType === 'percentage' ? $profitPercentage : '') }}"
                                        placeholder="مثلاً ۱۵"
                                        autocomplete="off"
                                        class="h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 pl-12 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                    >

                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#827875]">
                                        ٪
                                    </span>

                                </div>

                                <p class="mt-2 text-xs text-[#9A8E89]">
                                    حداکثر درصد قابل ثبت ۱۰۰ درصد است.
                                </p>

                            </div>

                            {{-- Fixed Amount Input --}}
                            <div
                                id="profit-amount-wrapper"
                                class="hidden"
                            >

                                <label
                                    for="profit_amount"
                                    class="mb-2 block text-sm text-[#5F514D]"
                                >
                                    مبلغ سود
                                </label>

                                <div class="relative">

                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        name="profit_amount"
                                        id="profit_amount"
                                        value="{{ old('profit_amount', $profitInputType === 'amount' ? $profitAmount : '') }}"
                                        placeholder="مبلغ سود را وارد کنید"
                                        autocomplete="off"
                                        class="h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 pl-16 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6668]/10"
                                    >

                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[#827875]">
                                        ریال
                                    </span>

                                </div>

                            </div>

                            {{-- Live Calculation --}}
                            <div
                                id="profit-live-preview"
                                class="rounded-2xl border border-[#E8E1DE] bg-[#F4F0EE] p-5"
                            >

                                <p class="text-sm text-[#827875]">
                                    مبلغ سود محاسبه‌شده
                                </p>

                                <div class="mt-3 flex flex-wrap items-baseline gap-2">

                                    <strong
                                        id="profit-preview-value"
                                        class="text-2xl text-[#674040] kohan-bold"
                                    >
                                        ۰
                                    </strong>

                                    <span class="text-sm text-[#827875]">
                                        ریال
                                    </span>

                                </div>

                            </div>

                            {{-- Submit --}}
                            <div class="flex flex-wrap items-center gap-3">

                                <button
                                    type="submit"
                                    class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#362727]"
                                >
                                    {{ $profitAmount !== null ? 'ذخیره تغییرات سود' : 'ثبت سود فاکتور' }}
                                </button>

                                <span class="text-xs text-[#9A8E89]">
                                    با ذخیره، مقدار قبلی سود جایگزین می‌شود.
                                </span>

                            </div>

                        </form>

                    </div>

                @else

                    <div class="rounded-xl border border-[#E8E1DE] bg-[#F4F0EE] p-4 text-sm leading-7 text-[#827875]">
                        فقط مدیر اصلی امکان ثبت یا ویرایش سود این فاکتور را دارد.
                    </div>

                @endif

            </div>

        </section>
    @endif

    {{-- Invoice Commissions --}}
    @if($invoice->type === 'invoice')
        <section
            id="invoice-commissions-section"
            class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm"
        >

            <div class="flex flex-col justify-between gap-3 border-b border-[#EEE8E5] p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <h2 class="text-lg text-[#362727] kohan-bold">
                        مدیریت پورسانت‌ها
                    </h2>

                    <p class="mt-2 text-sm text-[#9A8E89]">
                        تعیین دریافت‌کنندگان و درصد پورسانت هر شخص از سود فاکتور
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-xl bg-[#F3E9E9] px-4 py-2 text-sm text-[#674040] kohan-bold">
                    {{ $formatDigits($invoice->commissions->count()) }} دریافت‌کننده
                </span>
            </div>

            <div class="space-y-5 p-5 sm:p-6">

                {{-- Commission Summary --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                        <p class="text-xs text-[#827875]">
                            سود مبنای محاسبه
                        </p>

                        <p class="mt-2 text-base text-[#362727] kohan-bold">
                            {{ $profitAmount !== null ? $formatNumber($profitAmount) : '—' }}
                            <span class="text-xs">ریال</span>
                        </p>
                    </div>

                    <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                        <p class="text-xs text-[#827875]">
                            مجموع مبالغ پورسانت
                        </p>

                        <p class="mt-2 text-base text-[#674040] kohan-bold">
                            {{ $formatNumber($commissionTotal) }}
                            <span class="text-xs">ریال</span>
                        </p>
                    </div>

                </div>

                @if($profitAmount === null || (float) $profitAmount <= 0)

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-7 text-amber-800">
                        برای ثبت پورسانت ابتدا باید سود این فاکتور را ثبت کنید.
                    </div>

                @else

                    <form
                        action="{{ route('admin.invoices.commissions.update', $invoice) }}"
                        method="POST"
                        id="invoice-commissions-form"
                        class="space-y-5"
                    >
                        @csrf
                        @method('PUT')

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base text-[#362727] kohan-bold">
                                    دریافت‌کنندگان پورسانت
                                </h3>

                                <p class="mt-1 text-xs text-[#9A8E89]">
                                    برای هر شخص می‌توانید درصد مستقلی تعیین کنید.
                                </p>
                            </div>

                            <button
                                type="button"
                                id="add-commission-row"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#baadad] bg-[#F4F0EE] px-4 py-2.5 text-sm text-[#674040] transition hover:border-[#674040] hover:bg-[#EEE5E2]"
                            >
                                <span>＋</span>
                                افزودن دریافت‌کننده
                            </button>
                        </div>

                        <div
                            id="commission-rows"
                            class="space-y-4"
                        >

                            @foreach($commissionRows as $index => $row)
                                <div class="commission-row rounded-2xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 sm:p-5">

                                    <div class="mb-4 flex items-center justify-between gap-3">
                                        <span class="text-sm text-[#674040] kohan-bold">
                                            دریافت‌کننده {{ $formatDigits($index + 1) }}
                                        </span>

                                        <button
                                            type="button"
                                            class="remove-commission-row cursor-pointer rounded-lg px-3 py-2 text-xs text-red-600 transition hover:bg-red-50"
                                        >
                                            حذف ردیف
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                        <div>
                                            <label class="mb-2 block text-sm text-[#5F514D]">
                                                نوع دریافت‌کننده
                                            </label>

                                            <select
                                                name="commissions[{{ $index }}][recipient_type]"
                                                class="commission-type h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                                required
                                            >
                                                <option value="user" @selected(($row['recipient_type'] ?? '') === 'user')>
                                                    مدیر پنل
                                                </option>

                                                <option value="external" @selected(($row['recipient_type'] ?? '') === 'external')>
                                                    رابط خارجی
                                                </option>
                                            </select>
                                        </div>

                                        <div class="commission-user-wrapper">
                                            <label class="mb-2 block text-sm text-[#5F514D]">
                                                انتخاب مدیر
                                            </label>

                                            <select
                                                name="commissions[{{ $index }}][user_id]"
                                                class="commission-user h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                            >
                                                <option value="">
                                                    انتخاب مدیر
                                                </option>

                                                @foreach($commissionUsers as $manager)
                                                    <option
                                                        value="{{ $manager->id }}"
                                                        @selected((string) ($row['user_id'] ?? '') === (string) $manager->id)
                                                    >
                                                        {{ $manager->name }}
                                                        {{ $manager->role === 'super_admin' ? '(مدیر اصلی)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="commission-external-wrapper hidden">
                                            <label class="mb-2 block text-sm text-[#5F514D]">
                                                نام رابط خارجی
                                            </label>

                                            <input
                                                type="text"
                                                name="commissions[{{ $index }}][external_name]"
                                                value="{{ $row['external_name'] ?? '' }}"
                                                maxlength="255"
                                                placeholder="نام دریافت‌کننده"
                                                class="commission-external-name h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                            >
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm text-[#5F514D]">
                                                درصد پورسانت
                                            </label>

                                            <div class="relative">
                                                <input
                                                    type="text"
                                                    inputmode="decimal"
                                                    name="commissions[{{ $index }}][percentage]"
                                                    value="{{ $row['percentage'] ?? '' }}"
                                                    placeholder="مثلاً ۱۰"
                                                    autocomplete="off"
                                                    required
                                                    class="commission-percentage h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 pl-12 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                                >

                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#827875]">
                                                    ٪
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-4 rounded-xl border border-[#EEE8E5] bg-white p-3">
                                        <p class="text-xs text-[#827875]">
                                            مبلغ پورسانت محاسبه‌شده
                                        </p>

                                        <p class="commission-preview mt-2 text-base text-[#674040] kohan-bold">
                                            ۰ ریال
                                        </p>
                                    </div>

                                </div>
                            @endforeach

                        </div>

                        <div
                            id="commission-empty-message"
                            class="{{ count($commissionRows) ? 'hidden' : '' }} rounded-xl border border-dashed border-[#D8CECA] bg-[#FAF8F7] px-5 py-8 text-center"
                        >
                            <p class="text-sm text-[#827875]">
                                هنوز دریافت‌کننده‌ای برای پورسانت ثبت نشده است.
                            </p>

                            <p class="mt-2 text-xs text-[#A69B96]">
                                در صورت نیاز، از دکمه افزودن دریافت‌کننده استفاده کنید.
                            </p>
                        </div>

                        <template id="commission-row-template">
                            <div class="commission-row rounded-2xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 sm:p-5">

                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <span class="commission-row-title text-sm text-[#674040] kohan-bold">
                                        دریافت‌کننده
                                    </span>

                                    <button
                                        type="button"
                                        class="remove-commission-row cursor-pointer rounded-lg px-3 py-2 text-xs text-red-600 transition hover:bg-red-50"
                                    >
                                        حذف ردیف
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                    <div>
                                        <label class="mb-2 block text-sm text-[#5F514D]">
                                            نوع دریافت‌کننده
                                        </label>

                                        <select
                                            name="commissions[__INDEX__][recipient_type]"
                                            class="commission-type h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                            required
                                        >
                                            <option value="user">مدیر پنل</option>
                                            <option value="external">رابط خارجی</option>
                                        </select>
                                    </div>

                                    <div class="commission-user-wrapper">
                                        <label class="mb-2 block text-sm text-[#5F514D]">
                                            انتخاب مدیر
                                        </label>

                                        <select
                                            name="commissions[__INDEX__][user_id]"
                                            class="commission-user h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                        >
                                            <option value="">انتخاب مدیر</option>

                                            @foreach($commissionUsers as $manager)
                                                <option value="{{ $manager->id }}">
                                                    {{ $manager->name }}
                                                    {{ $manager->role === 'super_admin' ? '(مدیر اصلی)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="commission-external-wrapper hidden">
                                        <label class="mb-2 block text-sm text-[#5F514D]">
                                            نام رابط خارجی
                                        </label>

                                        <input
                                            type="text"
                                            name="commissions[__INDEX__][external_name]"
                                            maxlength="255"
                                            placeholder="نام دریافت‌کننده"
                                            class="commission-external-name h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm text-[#5F514D]">
                                            درصد پورسانت
                                        </label>

                                        <div class="relative">
                                            <input
                                                type="text"
                                                inputmode="decimal"
                                                name="commissions[__INDEX__][percentage]"
                                                placeholder="مثلاً ۱۰"
                                                autocomplete="off"
                                                required
                                                class="commission-percentage h-12 w-full rounded-xl border border-[#E8E1DE] bg-white px-4 pl-12 text-sm text-[#362727] outline-none focus:border-[#8d6666]"
                                            >

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#827875]">
                                                ٪
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-4 rounded-xl border border-[#EEE8E5] bg-white p-3">
                                    <p class="text-xs text-[#827875]">
                                        مبلغ پورسانت محاسبه‌شده
                                    </p>

                                    <p class="commission-preview mt-2 text-base text-[#674040] kohan-bold">
                                        ۰ ریال
                                    </p>
                                </div>

                            </div>
                        </template>

                        <div class="rounded-xl border border-[#E8E1DE] bg-[#F4F0EE] p-4 text-xs leading-7 text-[#827875]">
                            مبلغ پورسانت هر شخص بر اساس درصد او از سود فاکتور محاسبه می‌شود.
                            مجموع درصدها محدودیت ثابتی ندارد.
                            با تغییر سود فاکتور، مبلغ پورسانت‌ها به‌صورت خودکار به‌روزرسانی خواهد شد.
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#362727]"
                            >
                                ذخیره پورسانت‌ها
                            </button>

                            <span class="text-xs text-[#9A8E89]">
                                با ذخیره، فهرست قبلی با اطلاعات جدید جایگزین می‌شود.
                            </span>
                        </div>

                    </form>

                @endif

            </div>

        </section>
    @endif

    {{-- Invoice Payments --}}
    @if($invoice->type === 'invoice')
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="flex flex-col justify-between gap-3 border-b border-[#EEE8E5] p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <h2 class="text-lg text-[#362727] kohan-bold">
                        پرداخت‌های مشتری
                    </h2>
                    <p class="mt-1 text-xs text-[#9A8E89]">
                        ثبت و پیگیری پرداخت‌های انجام‌شده برای این فاکتور
                    </p>
                </div>

                @if(!$isVoided)
                    <span class="inline-flex items-center rounded-xl bg-[#F3E9E9] px-4 py-2 text-sm text-[#674040] kohan-bold">
                        {{ $formatDigits($invoice->payments->count()) }} پرداخت ثبت‌شده
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-3 sm:p-6">

                <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                    <p class="text-xs text-[#827875]">مبلغ کل فاکتور</p>
                    <p class="mt-2 text-base text-[#362727] kohan-bold">
                        {{ $formatNumber($grandTotal) }}
                        <span class="text-xs">ریال</span>
                    </p>
                </div>

                <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                    <p class="text-xs text-[#827875]">مجموع پرداخت‌ها</p>
                    <p class="mt-2 text-base text-[#674040] kohan-bold">
                        {{ $formatNumber($paidAmount) }}
                        <span class="text-xs">ریال</span>
                    </p>
                </div>

                <div class="rounded-xl border p-4
                    {{ $paymentBalance > 0
                        ? 'border-amber-200 bg-amber-50'
                        : ($paymentBalance < 0
                            ? 'border-blue-200 bg-blue-50'
                            : 'border-emerald-200 bg-emerald-50') }}">

                    <p class="text-xs
                        {{ $paymentBalance > 0
                            ? 'text-amber-700'
                            : ($paymentBalance < 0
                                ? 'text-blue-700'
                                : 'text-emerald-700') }}">

                        @if($paymentBalance > 0)
                            مانده بدهی مشتری
                        @elseif($paymentBalance < 0)
                            بستانکاری مشتری
                        @else
                            وضعیت پرداخت
                        @endif
                    </p>

                    <p class="mt-2 text-base kohan-bold
                        {{ $paymentBalance > 0
                            ? 'text-amber-800'
                            : ($paymentBalance < 0
                                ? 'text-blue-800'
                                : 'text-emerald-800') }}">

                        @if($paymentBalance > 0)
                            {{ $formatNumber($remainingAmount) }} ریال
                        @elseif($paymentBalance < 0)
                            {{ $formatNumber($creditAmount) }} ریال
                        @else
                            تسویه کامل
                        @endif
                    </p>
                </div>

            </div>

            @if(!$isVoided)
                <div class="border-t border-[#EEE8E5] p-5 sm:p-6">

                    <h3 class="mb-5 text-base text-[#362727] kohan-bold">
                        ثبت پرداخت جدید
                    </h3>

                    <form
                        action="{{ route('admin.invoices.payments.store', $invoice) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-5"
                    >
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <div>
                                <label for="payment-amount" class="mb-2 block text-sm text-[#5F514D]">
                                    مبلغ پرداخت (ریال)
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="payment-amount"
                                    type="number"
                                    name="amount"
                                    min="0.01"
                                    step="0.01"
                                    required
                                    value="{{ old('amount') }}"
                                    placeholder="مبلغ پرداخت را وارد کنید"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                >

                                @error('amount')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment-date" class="mb-2 block text-sm text-[#5F514D]">
                                    تاریخ پرداخت
                                    <span class="text-red-500">*</span>
                                </label>

                                <x-admin.jalali-date-picker
                                    name="payment_date"
                                    id="payment-date"
                                    :value="old('payment_date', now()->toDateString())"
                                    placeholder="تاریخ پرداخت"
                                    :required="true"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                />

                                @error('payment_date')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment-method" class="mb-2 block text-sm text-[#5F514D]">
                                    روش پرداخت
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="payment-method"
                                    type="text"
                                    name="method"
                                    maxlength="100"
                                    required
                                    value="{{ old('method') }}"
                                    placeholder="مثلاً کارت به کارت، حواله، نقدی"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                >

                                @error('method')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment-reference" class="mb-2 block text-sm text-[#5F514D]">
                                    شماره پیگیری / شماره سند
                                </label>

                                <input
                                    id="payment-reference"
                                    type="text"
                                    name="reference_number"
                                    maxlength="255"
                                    value="{{ old('reference_number') }}"
                                    placeholder="شماره پیگیری یا سند پرداخت"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                >

                                @error('reference_number')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="payment-description" class="mb-2 block text-sm text-[#5F514D]">
                                    توضیحات
                                </label>

                                <textarea
                                    id="payment-description"
                                    name="description"
                                    rows="3"
                                    maxlength="10000"
                                    placeholder="توضیحات مربوط به این پرداخت..."
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="payment-document" class="mb-2 block text-sm text-[#5F514D]">
                                    تصویر یا فایل سند پرداخت
                                </label>

                                <input
                                    id="payment-document"
                                    type="file"
                                    name="document"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                                    class="block w-full rounded-xl border border-[#E8E1DE] bg-white text-sm text-[#5F514D] file:ml-4 file:cursor-pointer file:border-0 file:bg-[#F3E9E9] file:px-4 file:py-3 file:text-[#674040] hover:file:bg-[#EEE0E0]"
                                >

                                <p class="mt-2 text-xs text-[#9A8E89]">
                                    فرمت‌های مجاز: PDF، JPG، PNG و WebP؛ حداکثر حجم ۱۰ مگابایت
                                </p>

                                @error('document')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#362727]"
                            >
                                <span>＋</span>
                                ثبت پرداخت
                            </button>
                        </div>

                    </form>
                </div>
            @else
                <div class="border-t border-red-100 bg-red-50 px-5 py-4 text-sm text-red-700">
                    این فاکتور باطل شده است و امکان ثبت پرداخت جدید برای آن وجود ندارد.
                    سوابق پرداخت‌های قبلی همچنان قابل مشاهده هستند.
                </div>
            @endif

            {{-- Payment History --}}
            <div class="border-t border-[#EEE8E5] p-5 sm:p-6">

                <h3 class="mb-5 text-base text-[#362727] kohan-bold">
                    سوابق پرداخت
                </h3>

                @forelse($invoice->payments as $payment)
                    <div class="mb-4 rounded-xl border border-[#EEE8E5] p-4 last:mb-0">

                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">

                            <div>
                                <p class="text-base text-[#674040] kohan-bold">
                                    {{ $formatNumber($payment->amount) }} ریال
                                </p>

                                <p class="mt-2 text-xs text-[#827875]">
                                    تاریخ پرداخت:
                                    {{ $formatDate($payment->payment_date) }}
                                </p>
                            </div>

                            <span class="inline-flex w-fit rounded-lg bg-[#F3E9E9] px-3 py-2 text-xs text-[#674040]">
                                {{ $payment->method }}
                            </span>

                        </div>

                        @if($payment->reference_number)
                            <div class="mt-4 text-sm text-[#5F514D]">
                                <span class="text-[#9A8E89]">شماره پیگیری / سند:</span>
                                <span class="font-medium">{{ $payment->reference_number }}</span>
                            </div>
                        @endif

                        @if($payment->description)
                            <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#5F514D]">
                                {{ $payment->description }}
                            </p>
                        @endif

                        <div class="mt-4 flex flex-col justify-between gap-4 border-t border-[#F0EBE8] pt-4 sm:flex-row sm:items-end">

                            <div class="flex items-center gap-3">

                                @if($payment->document_path)
                                    <a
                                        href="{{ route('admin.invoices.payments.document', [$invoice, $payment]) }}"
                                        target="_blank"
                                        class="group relative block h-16 w-16 shrink-0 overflow-hidden rounded-lg border border-[#E8E1DE] bg-[#FAF8F7]"
                                        title="مشاهده تصویر سند پرداخت"
                                    >
                                        <img
                                            src="{{ route('admin.invoices.payments.document', [$invoice, $payment]) }}"
                                            alt="تصویر سند پرداخت"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-110"
                                        >

                                        <span class="absolute inset-0 flex items-center justify-center bg-black/0 text-white opacity-0 transition group-hover:bg-black/30 group-hover:opacity-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="7" />
                                                <path stroke-linecap="round" d="m20 20-4-4" />
                                            </svg>
                                        </span>
                                    </a>

                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-[#5F514D]">
                                            تصویر سند پرداخت
                                        </span>

                                        <a
                                            href="{{ route('admin.invoices.payments.document', [$invoice, $payment]) }}"
                                            class="text-xs text-[#8d6666] transition hover:text-[#674040]"
                                        >
                                            مشاهده تصویر
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-[#A69B96]">
                                        تصویر سندی ثبت نشده است.
                                    </span>
                                @endif

                            </div>

                            <span class="text-xs text-[#9A8E89]">
                                ثبت‌کننده:
                                {{ $payment->createdBy?->name ?? 'کاربر حذف‌شده' }}
                            </span>

                        </div>

                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-[#D8CECA] bg-[#FAF8F7] px-5 py-10 text-center">
                        <p class="text-sm text-[#827875]">
                            هنوز پرداختی برای این فاکتور ثبت نشده است.
                        </p>
                    </div>
                @endforelse

            </div>

        </section>
    @endif

    {{-- Document Operations --}}
    <div class="flex flex-wrap gap-3">

        <!-- @if($invoice->type === 'proforma'
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

        @endif -->

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
            $printNotes = $invoice->is_formal
                ? collect([
                    'این سند بر اساس اطلاعات ثبت‌شده در سامانه کهن پلیمر البرز صادر شده است.',
                    'برای بررسی اصالت سند، کد QR را اسکن کنید.',
                ])
                : collect([
                    'این سند بر اساس اطلاعات ثبت‌شده صادر شده است.',
                ]);
        }
    @endphp

    <!-- <header class="print-header">
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
    </header> -->
    @if($invoice->is_formal)
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
    @endif

    <!-- <section class="print-overview"> -->
    <section class="print-overview {{ !$invoice->is_formal ? 'print-overview-informal' : '' }}">
        @if($invoice->is_formal)
            <div class="print-qr-card">
                @if(!empty($qrCodeDataUri))
                    <img src="{{ $qrCodeDataUri }}" alt="QR اعتبارسنجی فاکتور">
                @endif

                <div class="print-qr-copy kohan-bold">
                    <strong>کد اعتبارسنجی سند</strong>
                    <div>برای بررسی اصالت سند، کد QR را اسکن کنید.</div>
                </div>
            </div>
        @endif

        <div class="print-main-title">
            <h1 class="print-document-heading kohan-bold">{{ $documentType }}</h1>
            @if($invoice->is_formal)
                <div class="print-document-number kohan-bold">
                    شماره {{ $invoice->type === 'invoice' ? 'فاکتور' : 'پیش‌فاکتور' }}:
                    <span dir="ltr">{{ $formatDigits($invoice->document_number) }}</span>
                </div>
            @endif
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

            @if($invoice->is_formal)
                <div class="print-meta-row kohan-bold">
                    <strong>وضعیت:</strong>
                    <span class="print-meta-value">
                        <span class="print-status {{ $isVoided || $isExpired ? 'is-invalid' : '' }}">
                            {{ $statusText }}
                        </span>
                    </span>
                </div>
            @endif

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
                @if($invoice->is_formal)
                    <div class="print-info-row">
                        <span class="print-info-label">شماره سند:</span>
                        <span class="print-info-value">
                            {{ $formatDigits($invoice->document_number) }}
                        </span>
                    </div>
                @endif

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

    <!-- <footer class="print-footer print-page-break kohan-bold">
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
    </footer> -->
    @if($invoice->is_formal)
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
    @endif

    <!-- <div class="print-bottom-bar kohan-bold">
        کهن پلیمر البرز
        &nbsp; | &nbsp;
        کیفیت امروز، اعتماد فردا
    </div> -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('invoice-profit-form');

        if (!form) {
            return;
        }

        const inputType = document.getElementById('profit_input_type');

        const percentageInput = document.getElementById('profit_percentage');
        const amountInput = document.getElementById('profit_amount');

        const percentageWrapper = document.getElementById('profit-percentage-wrapper');
        const amountWrapper = document.getElementById('profit-amount-wrapper');

        const previewValue = document.getElementById('profit-preview-value');

        const invoiceTotal = Number(@json((float) $grandTotal));

        const persianDigits = '۰۱۲۳۴۵۶۷۸۹';
        const arabicDigits = '٠١٢٣٤٥٦٧٨٩';

        function normalizeNumber(value) {
            return String(value)
                .replace(/[۰-۹]/g, digit => persianDigits.indexOf(digit))
                .replace(/[٠-٩]/g, digit => arabicDigits.indexOf(digit))
                .replace(/[٬,،\s]/g, '')
                .replace(/٫/g, '.')
                .trim();
        }

        function formatPersianNumber(value) {
            return new Intl.NumberFormat('fa-IR', {
                maximumFractionDigits: 2
            }).format(value);
        }

        function getNumericValue(input) {
            const normalized = normalizeNumber(input.value);

            if (normalized === '') {
                return 0;
            }

            const number = Number(normalized);

            return Number.isFinite(number) ? number : 0;
        }

        function updatePreview() {
            let profit = 0;

            if (inputType.value === 'percentage') {
                const percentage = getNumericValue(percentageInput);

                profit = invoiceTotal * percentage / 100;
            } else {
                profit = getNumericValue(amountInput);
            }

            previewValue.textContent = formatPersianNumber(
                Math.round(profit * 100) / 100
            );
        }

        function updateInputVisibility() {
            const isPercentage = inputType.value === 'percentage';

            percentageWrapper.classList.toggle('hidden', !isPercentage);
            amountWrapper.classList.toggle('hidden', isPercentage);

            percentageInput.required = isPercentage;
            amountInput.required = !isPercentage;

            percentageInput.disabled = !isPercentage;
            amountInput.disabled = isPercentage;

            updatePreview();
        }

        function normalizeInputBeforeSubmit(input) {
            const normalized = normalizeNumber(input.value);

            input.value = normalized;
        }

        inputType.addEventListener('change', updateInputVisibility);

        percentageInput.addEventListener('input', updatePreview);
        amountInput.addEventListener('input', updatePreview);

        form.addEventListener('submit', function (event) {
            normalizeInputBeforeSubmit(percentageInput);
            normalizeInputBeforeSubmit(amountInput);

            const isPercentage = inputType.value === 'percentage';

            const selectedValue = isPercentage
                ? getNumericValue(percentageInput)
                : getNumericValue(amountInput);

            if (!Number.isFinite(selectedValue) || selectedValue < 0) {
                event.preventDefault();
                alert('مقدار سود واردشده معتبر نیست.');
                return;
            }

            if (isPercentage && selectedValue > 100) {
                event.preventDefault();
                alert('درصد سود نمی‌تواند بیشتر از ۱۰۰ باشد.');
                return;
            }

            if (!isPercentage && selectedValue > invoiceTotal) {
                event.preventDefault();
                alert('مبلغ سود نمی‌تواند بیشتر از مبلغ نهایی فاکتور باشد.');
            }
        });

        updateInputVisibility();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('invoice-commissions-form');

        if (!form) {
            return;
        }

        const rowsContainer = document.getElementById('commission-rows');
        const template = document.getElementById('commission-row-template');
        const addButton = document.getElementById('add-commission-row');
        const emptyMessage = document.getElementById('commission-empty-message');

        const profitAmount = Number(@json((float) $profitAmount));

        const persianDigits = '۰۱۲۳۴۵۶۷۸۹';
        const arabicDigits = '٠١٢٣٤٥٦٧٨٩';

        let nextIndex = Math.max(
            0,
            ...Array.from(rowsContainer.querySelectorAll('[name]')).map(input => {
                const match = input.name.match(/^commissions\[(\d+)\]/);
                return match ? Number(match[1]) + 1 : 0;
            })
        );

        function normalizeNumber(value) {
            return String(value)
                .replace(/[۰-۹]/g, digit => persianDigits.indexOf(digit))
                .replace(/[٠-٩]/g, digit => arabicDigits.indexOf(digit))
                .replace(/[٬,،\s]/g, '')
                .replace(/٫/g, '.')
                .trim();
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('fa-IR', {
                maximumFractionDigits: 2
            }).format(value);
        }

        function updateRow(row) {
            const type = row.querySelector('.commission-type');
            const userWrapper = row.querySelector('.commission-user-wrapper');
            const externalWrapper = row.querySelector('.commission-external-wrapper');

            const userInput = row.querySelector('.commission-user');
            const externalInput = row.querySelector('.commission-external-name');
            const percentageInput = row.querySelector('.commission-percentage');

            const isExternal = type.value === 'external';

            userWrapper.classList.toggle('hidden', isExternal);
            externalWrapper.classList.toggle('hidden', !isExternal);

            userInput.disabled = isExternal;
            userInput.required = !isExternal;

            externalInput.disabled = !isExternal;
            externalInput.required = isExternal;

            const percentage = Number(normalizeNumber(percentageInput.value)) || 0;

            const amount = profitAmount * percentage / 100;

            row.querySelector('.commission-preview').textContent =
                formatNumber(Math.round(amount * 100) / 100) + ' ریال';
        }

        function updateRows() {
            const rows = rowsContainer.querySelectorAll('.commission-row');

            emptyMessage.classList.toggle('hidden', rows.length > 0);

            rows.forEach((row, index) => {
                const title = row.querySelector('.commission-row-title');

                if (title) {
                    title.textContent = 'دریافت‌کننده ' + formatNumber(index + 1);
                }

                updateRow(row);
            });
        }

        addButton.addEventListener('click', function () {
            const html = template.innerHTML.replaceAll('__INDEX__', nextIndex++);

            rowsContainer.insertAdjacentHTML('beforeend', html);

            updateRows();
        });

        rowsContainer.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.remove-commission-row');

            if (!removeButton) {
                return;
            }

            removeButton.closest('.commission-row').remove();

            updateRows();
        });

        rowsContainer.addEventListener('change', function (event) {
            if (event.target.matches('.commission-type')) {
                updateRow(event.target.closest('.commission-row'));
            }
        });

        rowsContainer.addEventListener('input', function (event) {
            if (event.target.matches('.commission-percentage')) {
                updateRow(event.target.closest('.commission-row'));
            }
        });

        form.addEventListener('submit', function () {
            rowsContainer.querySelectorAll('.commission-percentage').forEach(input => {
                input.value = normalizeNumber(input.value);
            });
        });

        updateRows();
    });
</script>
@endsection