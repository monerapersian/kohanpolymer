@extends('layouts.site')

@section('title', 'بررسی اصالت سند | کهن پلیمر البرز')
@section('description', 'بررسی اصالت، وضعیت اعتبار و جزئیات سند صادرشده از کهن پلیمر البرز.')

@php
    $siteContent = \App\Models\SiteContent::first();

    $isVoided = $invoice->status === 'voided';
    $isExpired = !$isVoided && $invoice->isExpired();
    $isValid = !$isVoided && !$isExpired;

    $statusText = $isVoided
        ? 'باطل شده'
        : ($isExpired ? 'منقضی شده' : 'معتبر');

    $statusDescription = $isVoided
        ? 'این سند توسط مدیریت باطل شده و اعتبار ندارد.'
        : ($isExpired
            ? 'مهلت اعتبار این سند به پایان رسیده است.'
            : 'این سند در حال حاضر از نظر سامانه معتبر است.');

    $documentType = $invoice->type === 'invoice'
        ? 'فاکتور'
        : 'پیش‌فاکتور';

    $customerName = $invoice->customer?->company_name
        ?: ($invoice->customer?->name ?: '—');

    $items = $invoice->items;

    $totalAmount = (float) $items->sum(
        fn ($item) => (float) ($item->total_amount ?? 0)
    );

    $totalVatAmount = (float) $items->sum(
        fn ($item) => (float) ($item->vat_amount ?? 0)
    );

    $grandTotal = (float) $items->sum(
        fn ($item) => (float) (
            $item->grand_total
            ?? ((float) ($item->total_amount ?? 0) + (float) ($item->vat_amount ?? 0))
        )
    );

    $formatNumber = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 0, true);

    $formatTaxRate = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 1, true);

    $formatMeasurement = static fn ($value) =>
        \App\Support\NumberHelper::format($value, 2, true);

    $formatDigits = static fn ($value) =>
        \App\Support\NumberHelper::toPersian($value);

    $formatDate = static fn ($value) => $value
        ? \App\Support\NumberHelper::toPersian(
            \App\Support\DateHelper::toPersian($value, 'Y/m/d')
        )
        : '—';

    $logoUrl = $siteContent?->logo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($siteContent->logo)
        : asset('img/kohan-logo.png');

    $printPhone = $siteContent?->phone;
    $printEmail = $siteContent?->email;
    $printWebsite = 'www.kohanpolymeralborz.ir';

    $verificationUrl = route('invoices.verify', $invoice->qr_token);

    $printNotes = collect();

    if ($invoice->description) {
        $printNotes->push($invoice->description);
    }

    foreach ($invoice->notes as $note) {
        if ($note->description) {
            $printNotes->push($note->description);
        }
    }

    if ($printNotes->isEmpty()) {
        $printNotes = collect([
            'این سند بر اساس اطلاعات ثبت‌شده در سامانه کهن پلیمر البرز صادر شده است.',
            'برای بررسی اصالت سند، کد QR را اسکن کنید.',
        ]);
    }

    $qrCodeDataUri = $qrCodeDataUri ?? null;
@endphp

@push('styles')
<style>
    .verification-page {
        color: #362727;
        font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
        direction: rtl;
        padding: 28px 16px 48px;
        background: #f2f2f2;
    }

    .verification-page *,
    .verification-page *::before,
    .verification-page *::after {
        box-sizing: border-box;
    }

    .verification-page .verification-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        width: min(210mm, 100%);
        margin: 0 auto 16px;
    }

    .verification-page .verification-message {
        color: #666;
        font-size: 13px;
        line-height: 2;
    }

    .verification-page .print-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-shrink: 0;
        min-height: 42px;
        padding: 9px 18px;
        color: #fff;
        background: #674040;
        border: 1px solid #674040;
        border-radius: 9px;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease;
    }

    .verification-page .print-button:hover {
        background: #4d2d2d;
    }

    .verification-page #invoice-print-sheet {
        width: min(210mm, 100%);
        min-height: 297mm;
        margin: 0 auto;
        padding: 10mm;
        color: #362727;
        background: #fff;
        border: 1px solid #e5e5e5;
        box-shadow: 0 12px 40px rgba(34, 34, 34, .09);
        font-family: Tahoma, Arial, sans-serif;
        font-size: 10px;
        line-height: 1.8;
    }

    .verification-page #invoice-print-sheet * {
        box-sizing: border-box;
    }

    .verification-page .print-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 5px 0 13px;
        border-bottom: 2px solid #674040;
    }

    .verification-page .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .verification-page .print-logo {
        display: block;
        width: auto;
        max-width: 100px;
        max-height: 75px;
        object-fit: contain;
    }

    .verification-page .print-brand-name {
        color: #362727;
        font-size: 19px;
        font-weight: 900;
        line-height: 1.5;
    }

    .verification-page .print-subtitle {
        margin-top: 2px;
        color: #674040;
        font-size: 9px;
    }

    .verification-page .print-company-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
        color: #362727;
        font-size: 9px;
    }

    .verification-page .print-company-line {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-wrap: anywhere;
    }

    .verification-page .print-company-icon {
        display: inline-flex;
        flex: 0 0 14px;
        align-items: center;
        justify-content: center;
        color: #674040;
        font-weight: bold;
    }

    .verification-page .print-overview {
        display: grid;
        grid-template-columns: 1fr 1.1fr 1fr;
        align-items: center;
        gap: 12px;
        margin: 14px 0 16px;
    }

    .verification-page .print-qr-card {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        padding: 9px;
        border: 1px solid #d8dfe4;
        border-radius: 8px;
        background: #F4F0EE;
    }

    .verification-page .print-qr-card img {
        flex: 0 0 65px;
        width: 65px;
        height: 65px;
        padding: 3px;
        background: #fff;
        object-fit: contain;
    }

    .verification-page .print-qr-copy {
        min-width: 0;
        font-size: 8px;
        overflow-wrap: anywhere;
    }

    .verification-page .print-qr-copy strong {
        display: block;
        margin-bottom: 4px;
        font-size: 9px;
    }

    .verification-page .print-main-title {
        text-align: center;
    }

    .verification-page .print-document-heading {
        margin: 0 0 8px;
        color: #362727;
        font-size: 20px;
        font-weight: 900;
        line-height: 1.5;
    }

    .verification-page .print-document-number {
        padding: 7px 9px;
        border-radius: 24px;
        background: #674040;
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        overflow-wrap: anywhere;
    }

    .verification-page .print-document-meta {
        display: grid;
        gap: 7px;
        padding-right: 12px;
        border-right: 1px solid #baadad;
    }

    .verification-page .print-meta-row {
        display: grid;
        grid-template-columns: auto 1fr;
        align-items: center;
        gap: 8px;
    }

    .verification-page .print-meta-row strong {
        white-space: nowrap;
    }

    .verification-page .print-meta-value {
        text-align: left;
        overflow-wrap: anywhere;
    }

    .verification-page .print-status {
        display: inline-block;
        padding: 2px 12px;
        border-radius: 20px;
        background: #d8f5e2;
        color: #16803d;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
    }

    .verification-page .print-status.is-invalid {
        background: #f9e0e0;
        color: #a51d2d;
    }

    .verification-page .print-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 15px;
    }

    .verification-page .print-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #d8dfe4;
        border-radius: 8px;
        background: #fff;
        break-inside: avoid;
    }

    .verification-page .print-card-title {
        display: flex;
        align-items: center;
        gap: 7px;
        min-height: 34px;
        padding: 7px 11px;
        background: #F4F0EE;
        color: #362727;
        font-size: 10px;
        font-weight: bold;
    }

    .verification-page .print-card-title .print-card-icon {
        color: #674040;
        font-size: 14px;
    }

    .verification-page .print-card-body {
        padding: 8px 11px;
    }

    .verification-page .print-info-row {
        display: grid;
        grid-template-columns: 40% 60%;
        gap: 6px;
        padding: 5px 0;
        align-items: start;
    }

    .verification-page .print-info-row + .print-info-row {
        border-top: 1px solid #f0ebeb;
    }

    .verification-page .print-info-label {
        font-weight: bold;
    }

    .verification-page .print-info-value {
        overflow-wrap: anywhere;
    }

    .verification-page .print-items {
        margin-bottom: 15px;
        overflow: hidden;
        border: 1px solid #d8dfe4;
        border-radius: 8px;
    }

    .verification-page .print-items table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 8px;
    }

    .verification-page .print-items th,
    .verification-page .print-items td {
        padding: 7px 4px;
        border-left: 1px solid #e1e5e8;
        border-bottom: 1px solid #e1e5e8;
        text-align: center;
        vertical-align: middle;
        overflow-wrap: anywhere;
    }

    .verification-page .print-items th {
        background: #674040;
        color: #fff;
        font-weight: bold;
    }

    .verification-page .print-items tbody tr:nth-child(even) {
        background: #F4F0EE;
    }

    .verification-page .print-items tbody tr {
        break-inside: avoid;
    }

    .verification-page .print-product-name {
        text-align: right !important;
    }

    .verification-page .print-product-dimensions {
        display: block;
        margin-top: 2px;
        color: #8d6666;
        font-size: 8px;
    }

    .verification-page .print-empty-row {
        padding: 20px !important;
        text-align: center !important;
    }

    .verification-page .print-bottom-grid {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 12px;
        align-items: stretch;
    }

    .verification-page .print-notes-list {
        margin: 0;
        padding: 0 15px 0 0;
    }

    .verification-page .print-notes-list li {
        margin: 0 0 5px;
        padding-right: 2px;
        overflow-wrap: anywhere;
    }

    .verification-page .print-notes-list li:last-child {
        margin-bottom: 0;
    }

    .verification-page .print-totals-body {
        padding: 8px 11px;
    }

    .verification-page .print-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        padding: 7px 0;
        border-bottom: 1px solid #e8e0e0;
    }

    .verification-page .print-total-row strong {
        white-space: nowrap;
    }

    .verification-page .print-grand-total {
        margin-top: 5px;
        padding: 10px;
        border: 0;
        border-radius: 5px;
        background: #f8e9ec;
        color: #674040;
        font-size: 12px;
        font-weight: bold;
    }

    .verification-page .print-footer {
        display: grid;
        grid-template-columns: 1fr 1.3fr .8fr;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        padding: 8px 0 12px;
    }

    .verification-page .print-verification-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 54px;
        padding: 8px;
        border-radius: 8px;
        background: #674040;
        color: #fff;
        text-align: center;
    }

    .verification-page .print-verification-badge strong {
        display: block;
        font-size: 10px;
    }

    .verification-page .print-verification-badge small {
        display: block;
        margin-top: 3px;
        font-size: 8px;
    }

    .verification-page .print-shield {
        font-size: 25px;
        line-height: 1;
    }

    .verification-page .print-footer-qr {
        text-align: center;
        font-size: 8px;
        overflow-wrap: anywhere;
    }

    .verification-page .print-footer-qr img {
        display: block;
        width: 65px;
        height: 65px;
        margin: 0 auto 4px;
        object-fit: contain;
    }

    .verification-page .print-footer-brand {
        color: #362727;
        font-weight: bold;
        text-align: center;
    }

    .verification-page .print-bottom-bar {
        margin-top: 3px;
        padding: 12px 8px;
        background: #674040;
        color: #fff;
        text-align: center;
        font-size: 10px;
        font-weight: bold;
    }

    .verification-page .print-note-text {
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .verification-page .print-plain-url {
        display: block;
        margin-top: 4px;
        direction: ltr;
        text-align: center;
        overflow-wrap: anywhere;
        font-size: 8px;
    }

    @media (max-width: 800px) {
        .verification-page {
            padding: 18px 10px 30px;
        }

        .verification-page #invoice-print-sheet {
            padding: 18px;
        }

        .verification-page .print-overview {
            grid-template-columns: 1fr 1fr;
        }

        .verification-page .print-main-title {
            grid-column: 1 / -1;
            grid-row: 1;
        }

        .verification-page .print-qr-card,
        .verification-page .print-document-meta {
            grid-row: 2;
        }

        .verification-page .print-info-grid {
            grid-template-columns: 1fr;
        }

        .verification-page .print-bottom-grid {
            grid-template-columns: 1fr;
        }

        .verification-page .print-footer {
            grid-template-columns: 1fr 1fr;
        }

        .verification-page .print-verification-badge {
            grid-column: 1 / -1;
            grid-row: 1;
        }
    }

    @media (max-width: 560px) {
        .verification-page .verification-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .verification-page .print-button {
            width: 100%;
        }

        .verification-page #invoice-print-sheet {
            padding: 12px;
            font-size: 9px;
        }

        .verification-page .print-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .verification-page .print-brand-name {
            font-size: 17px;
        }

        .verification-page .print-overview {
            grid-template-columns: 1fr;
        }

        .verification-page .print-main-title,
        .verification-page .print-qr-card,
        .verification-page .print-document-meta {
            grid-column: auto;
            grid-row: auto;
        }

        .verification-page .print-document-meta {
            padding-right: 0;
            border-right: 0;
        }

        .verification-page .print-footer {
            grid-template-columns: 1fr;
        }

        .verification-page .print-verification-badge {
            grid-column: auto;
            grid-row: auto;
        }

        .verification-page .print-items {
            overflow-x: auto;
        }

        .verification-page .print-items table {
            min-width: 680px;
        }
    }

    @page {
        size: A4 portrait;
        margin: 7mm;
    }

    @media print {
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

        .verification-page,
        .verification-page * {
            visibility: visible !important;
        }

        .verification-page {
            position: absolute !important;
            top: 0 !important;
            right: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .verification-page .verification-actions,
        .verification-page .no-print,
        body > header,
        body > footer,
        .site-header,
        .site-footer,
        #site-header,
        #site-footer {
            display: none !important;
        }

        .verification-page #invoice-print-sheet {
            display: block !important;
            width: 100% !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            box-shadow: none !important;
            color: #362727 !important;
            background: #fff !important;
            font-family: Tahoma, Arial, sans-serif !important;
            font-size: 8pt !important;
            line-height: 1.7 !important;
        }

        .verification-page #invoice-print-sheet .print-header {
            padding: 5px 0 13px;
        }

        .verification-page #invoice-print-sheet .print-brand-name {
            font-size: 19pt;
        }

        .verification-page #invoice-print-sheet .print-subtitle {
            font-size: 8pt;
        }

        .verification-page #invoice-print-sheet .print-company-info {
            font-size: 7.5pt;
        }

        .verification-page #invoice-print-sheet .print-overview {
            grid-template-columns: 1fr 1.1fr 1fr;
            margin: 14px 0 16px;
        }

        .verification-page #invoice-print-sheet .print-main-title {
            grid-column: auto;
            grid-row: auto;
        }

        .verification-page #invoice-print-sheet .print-qr-card,
        .verification-page #invoice-print-sheet .print-document-meta {
            grid-row: auto;
        }

        .verification-page #invoice-print-sheet .print-document-heading {
            font-size: 20pt;
        }

        .verification-page #invoice-print-sheet .print-document-number {
            font-size: 10pt;
        }

        .verification-page #invoice-print-sheet .print-info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .verification-page #invoice-print-sheet .print-bottom-grid {
            grid-template-columns: 1.05fr 1fr;
        }

        .verification-page #invoice-print-sheet .print-items {
            overflow: hidden;
        }

        .verification-page #invoice-print-sheet .print-items table {
            min-width: 0;
            font-size: 6.5pt;
        }

        .verification-page #invoice-print-sheet .print-items th,
        .verification-page #invoice-print-sheet .print-items td {
            padding: 7px 4px;
            font-size: 6.5pt;
        }

        .verification-page #invoice-print-sheet .print-footer {
            grid-template-columns: 1fr 1.3fr .8fr;
        }

        .verification-page #invoice-print-sheet .print-verification-badge {
            grid-column: auto;
            grid-row: auto;
        }

        .verification-page #invoice-print-sheet .print-footer-qr img {
            width: 65px;
            height: 65px;
        }

        .verification-page #invoice-print-sheet .print-card,
        .verification-page #invoice-print-sheet .print-items,
        .verification-page #invoice-print-sheet .print-bottom-grid,
        .verification-page #invoice-print-sheet .print-footer {
            break-inside: avoid;
        }

        .verification-page #invoice-print-sheet .print-status,
        .verification-page #invoice-print-sheet .print-document-number,
        .verification-page #invoice-print-sheet .print-card-title,
        .verification-page #invoice-print-sheet .print-items th,
        .verification-page #invoice-print-sheet .print-items tbody tr:nth-child(even),
        .verification-page #invoice-print-sheet .print-grand-total,
        .verification-page #invoice-print-sheet .print-verification-badge,
        .verification-page #invoice-print-sheet .print-bottom-bar {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        a {
            color: inherit !important;
            text-decoration: none !important;
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
@endpush

@section('content')
<div class="verification-page" style="padding-top: 125px;">
    <div class="verification-actions no-print ">
        <div class="verification-message kohan-bold">
            وضعیت اعتبار سند در زمان مشاهده بررسی می‌شود.
        </div>

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            <span>چاپ یا ذخیره PDF</span>
        </button>
    </div>

    <div id="invoice-print-sheet" dir="rtl">
        <header class="print-header kohan-bold">
            <div class="print-brand">
                @if($logoUrl)
                    <img
                        src="{{ $logoUrl }}"
                        alt="لوگوی کهن پلیمر البرز"
                        class="print-logo"
                    >
                @endif

                <div>
                    <div class="print-brand-name">کهن پلیمر البرز</div>
                    <div class="print-subtitle">KOHAN POLYMER ALBORZ</div>
                </div>
            </div>

            <div class="print-company-info" style="direction: ltr;">
                @if($printPhone)
                    <div class="print-company-line">
                        <span class="print-company-icon">☎</span>
                        <span>{{ $formatDigits($printPhone) }}</span>
                    </div>
                @endif

                @if($printEmail)
                    <div class="print-company-line">
                        <span class="print-company-icon">✉</span>
                        <span>{{ $printEmail }}</span>
                    </div>
                @endif

                <div class="print-company-line">
                    <span class="print-company-icon">◎</span>
                    <span>{{ $printWebsite }}</span>
                </div>
            </div>
        </header>

        <section class="print-overview kohan-bold">
            <div class="print-qr-card">
                @if(!empty($qrCodeDataUri))
                    <img
                        src="{{ $qrCodeDataUri }}"
                        alt="QR اعتبارسنجی سند"
                    >
                @endif

                <div class="print-qr-copy">
                    <strong>کد اعتبارسنجی سند</strong>
                    <div>برای بررسی اصالت سند، کد QR را اسکن کنید.</div>
                </div>
            </div>

            <div class="print-main-title">
                <h1 class="print-document-heading">
                    {{ $documentType }}
                </h1>

                <div class="print-document-number">
                    شماره {{ $documentType }}:
                    {{ $formatDigits($invoice->document_number) }}
                </div>
            </div>

            <div class="print-document-meta">
                <div class="print-meta-row">
                    <strong>تاریخ صدور:</strong>
                    <span class="print-meta-value">
                        {{ $formatDate($invoice->issue_date) }}
                    </span>
                </div>

                @if($invoice->type === 'proforma')
                    <div class="print-meta-row">
                        <strong>تاریخ اعتبار:</strong>
                        <span class="print-meta-value">
                            {{ $formatDate($invoice->valid_until) }}
                        </span>
                    </div>
                @endif

                <div class="print-meta-row">
                    <strong>وضعیت:</strong>
                    <span class="print-meta-value">
                        <span class="print-status {{ $isVoided || $isExpired ? 'is-invalid' : '' }}">
                            {{ $statusText }}
                        </span>
                    </span>
                </div>

                <div class="print-meta-row">
                    <strong>نوع پرداخت:</strong>
                    <span class="print-meta-value">
                        {{ $invoice->payment_type ?: '—' }}
                    </span>
                </div>
            </div>
        </section>

        <section class="print-info-grid kohan-bold">
            <div class="print-card">
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
                            <span class="print-info-value">
                                {{ $invoice->customer->address }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="print-card">
                <div class="print-card-title">
                    <span class="print-card-icon">▤</span>
                    اطلاعات سند
                </div>

                <div class="print-card-body">
                    <div class="print-info-row">
                        <span class="print-info-label">شماره سند:</span>
                        <span class="print-info-value">
                            {{ $formatDigits($invoice->document_number) }}
                        </span>
                    </div>

                    <div class="print-info-row">
                        <span class="print-info-label">تاریخ صدور:</span>
                        <span class="print-info-value">
                            {{ $formatDate($invoice->issue_date) }}
                        </span>
                    </div>

                    @if($invoice->type === 'proforma')
                        <div class="print-info-row">
                            <span class="print-info-label">معتبر تا:</span>
                            <span class="print-info-value">
                                {{ $formatDate($invoice->valid_until) }}
                            </span>
                        </div>
                    @endif

                    <div class="print-info-row">
                        <span class="print-info-label">وضعیت:</span>
                        <span class="print-info-value">{{ $statusText }}</span>
                    </div>

                    <div class="print-info-row">
                        <span class="print-info-label">نوع صدور:</span>
                        <span class="print-info-value">
                            {{ $invoice->is_formal ? 'رسمی' : 'غیررسمی' }}
                        </span>
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
                    @forelse($items as $item)
                        @php
                            $quantity = (float) ($item->quantity ?? 0);
                            $unitPrice = (float) ($item->unit_price ?? 0);
                            $lineSubtotal = (float) (
                                $item->total_amount ?? ($quantity * $unitPrice)
                            );
                            $lineTax = (float) ($item->vat_amount ?? 0);
                            $lineGrandTotal = (float) (
                                $item->grand_total ?? ($lineSubtotal + $lineTax)
                            );
                        @endphp

                        <tr>
                            <td>{{ $formatDigits($loop->iteration) }}</td>

                            <td class="print-product-name">
                                {{ $item->description ?: '—' }}

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

                            <td>{{ $formatNumber($quantity) }}</td>
                            <td>{{ $item->unit ?: '—' }}</td>
                            <td>{{ $formatNumber($unitPrice) }}</td>
                            <td>{{ $formatNumber($lineSubtotal) }}</td>
                            <td>{{ $formatTaxRate($item->vat_percent ?? 0) }}٪</td>
                            <td>{{ $formatNumber($lineTax) }}</td>
                            <td>{{ $formatNumber($lineGrandTotal) }}</td>
                            <td>ریال</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="print-empty-row">
                                هیچ قلمی برای این سند ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="print-bottom-grid kohan-bold">
            <div class="print-card">
                <div class="print-card-title">
                    <span class="print-card-icon">▤</span>
                    توضیحات
                </div>

                <div class="print-card-body">
                    <ul class="print-notes-list">
                        @foreach($printNotes as $printNote)
                            <li class="print-note-text">{{ $printNote }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="print-card">
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

        <footer class="print-footer kohan-bold">
            <div class="print-footer-brand">
                <div>کهن پلیمر البرز</div>
                <div class="print-subtitle">تامین کننده مواد اولیه و محصولات پلیمری</div>
            </div>

            <div class="print-verification-badge">
                <span class="print-shield">
                    {{ $isValid ? '✓' : '×' }}
                </span>

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

                    <small>وضعیت اعتبار در زمان مشاهده بررسی شده است.</small>
                </div>
            </div>

            <div class="print-footer-qr">
                @if(!empty($qrCodeDataUri))
                    <img
                        src="{{ $qrCodeDataUri }}"
                        alt="QR بررسی اصالت"
                    >
                @endif

                <strong>بررسی آنلاین اصالت سند</strong>
            </div>
        </footer>

        <div class="print-bottom-bar kohan-bold">
            کهن پلیمر البرز
            &nbsp; | &nbsp;
            کیفیت امروز، اعتماد فردا
        </div>
    </div>
</div>
@endsection