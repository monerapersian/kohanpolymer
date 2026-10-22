@extends('layouts.site')

@section('title', 'بررسی اصالت سند | کهن پلیمر البرز')
@section('description', 'بررسی اصالت، وضعیت اعتبار و جزئیات سند صادرشده از کهن پلیمر البرز.')

@php
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

    $documentType = $invoice->type === 'proforma'
        ? 'پیش‌فاکتور'
        : 'فاکتور';

    $customerName = $invoice->customer->company_name
        ?? $invoice->customer->name
        ?? '—';

    $items = $invoice->items;

    $subtotal = (float) $items->sum(
        fn ($item) => (float) ($item->total_amount ?? 0)
    );

    $taxAmount = (float) $items->sum(
        fn ($item) => (float) ($item->vat_amount ?? 0)
    );

    $grandTotal = (float) $items->sum(
        fn ($item) => (float) (
            $item->grand_total
            ?? ((float) ($item->total_amount ?? 0) + (float) ($item->vat_amount ?? 0))
        )
    );

    $formatMoney = fn ($amount) => number_format((float) $amount, 0, '.', ',');

    $formatDate = fn ($date) => $date
        ? $date->format('Y/m/d')
        : '—';
@endphp

@push('styles')
<style>
    .verification-page {
        --brand: #681b21;
        --brand-dark: #4b1116;
        --brand-soft: #f8edef;
        --ink: #222222;
        --muted: #666666;
        --line: #e7e7e7;
        --surface: #f7f7f7;
        color: var(--ink);
        font-family: 'Vazirmatn', sans-serif;
    }

    .verification-page * {
        box-sizing: border-box;
    }

    .verification-page .document-shell {
        width: min(1120px, calc(100% - 32px));
        margin: 42px auto 64px;
    }

    .verification-page .document-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e9e9e9;
        border-radius: 22px;
        box-shadow: 0 20px 65px rgba(34, 34, 34, .07);
    }

    .verification-page .document-banner {
        position: relative;
        overflow: hidden;
        padding: 34px;
        color: #fff;
        background: linear-gradient(125deg, #681b21 0%, #4b1116 100%);
    }

    .verification-page .document-banner::after {
        position: absolute;
        top: -95px;
        left: -45px;
        width: 260px;
        height: 260px;
        content: "";
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 50%;
        box-shadow:
            0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
        pointer-events: none;
    }

    .verification-page .brand-row {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .verification-page .brand-info {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .verification-page .brand-logo {
        display: flex;
        width: 76px;
        height: 76px;
        flex: 0 0 76px;
        align-items: center;
        justify-content: center;
        padding: 8px;
        background: #fff;
        border-radius: 16px;
    }

    .verification-page .brand-logo img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .verification-page .brand-name {
        margin: 0;
        font-size: clamp(20px, 3vw, 29px);
        font-weight: 900;
        line-height: 1.7;
    }

    .verification-page .brand-caption {
        margin-top: 3px;
        color: rgba(255, 255, 255, .75);
        font-size: 13px;
    }

    .verification-page .document-type-box {
        position: relative;
        z-index: 1;
        min-width: 145px;
        padding: 13px 18px;
        text-align: center;
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 14px;
    }

    .verification-page .document-type-box span {
        display: block;
        color: rgba(255, 255, 255, .72);
        font-size: 12px;
    }

    .verification-page .document-type-box strong {
        display: block;
        margin-top: 3px;
        font-size: 19px;
        font-weight: 800;
    }

    .verification-page .banner-bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, .17);
    }

    .verification-page .document-number-label {
        color: rgba(255, 255, 255, .72);
        font-size: 12px;
    }

    .verification-page .document-number {
        margin-top: 4px;
        font-size: 18px;
        font-weight: 800;
        overflow-wrap: anywhere;
        direction: ltr;
        text-align: right;
    }

    .verification-page .verification-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        color: #fff;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 999px;
        font-size: 12px;
        white-space: nowrap;
    }

    .verification-page .document-content {
        padding: 32px;
    }

    .verification-page .status-panel {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        margin-bottom: 32px;
        border: 1px solid;
        border-radius: 16px;
    }

    .verification-page .status-panel.valid {
        color: #166534;
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .verification-page .status-panel.expired,
    .verification-page .status-panel.voided {
        color: #991b1b;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .verification-page .status-icon {
        display: flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, .8);
        font-size: 22px;
        font-weight: 900;
    }

    .verification-page .status-title {
        margin: 0;
        font-size: 17px;
        font-weight: 900;
    }

    .verification-page .status-description {
        margin: 5px 0 0;
        font-size: 13px;
        line-height: 2;
    }

    .verification-page .section {
        margin-top: 34px;
    }

    .verification-page .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 18px;
        font-size: 17px;
        font-weight: 900;
    }

    .verification-page .section-heading::before {
        width: 4px;
        height: 24px;
        flex: 0 0 4px;
        content: "";
        background: var(--brand);
        border-radius: 8px;
    }

    .verification-page .section-caption {
        margin: -10px 0 18px;
        color: var(--muted);
        font-size: 12px;
    }

    .verification-page .details-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .verification-page .detail-card {
        min-width: 0;
        padding: 17px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 13px;
    }

    .verification-page .detail-label {
        display: block;
        margin-bottom: 8px;
        color: var(--muted);
        font-size: 12px;
    }

    .verification-page .detail-value {
        display: block;
        font-size: 14px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .verification-page .table-frame {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 14px;
    }

    .verification-page .items-table {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
    }

    .verification-page .items-table thead {
        color: #fff;
        background: var(--brand);
    }

    .verification-page .items-table th {
        padding: 15px 13px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .verification-page .items-table td {
        padding: 16px 13px;
        border-bottom: 1px solid #eeeeee;
        font-size: 13px;
        vertical-align: middle;
    }

    .verification-page .items-table tbody tr:nth-child(even) {
        background: #fafafa;
    }

    .verification-page .items-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .verification-page .product-description {
        min-width: 160px;
        font-weight: 700;
        line-height: 1.9;
    }

    .verification-page .number-cell {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .verification-page .empty-row {
        padding: 28px !important;
        color: var(--muted);
        text-align: center;
    }

    .verification-page .summary-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 390px);
        gap: 22px;
        align-items: start;
    }

    .verification-page .summary-note {
        padding: 20px;
        background: #fafafa;
        border: 1px solid var(--line);
        border-radius: 15px;
    }

    .verification-page .summary-note-title {
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 900;
    }

    .verification-page .summary-note-text {
        margin: 0;
        color: #555;
        font-size: 13px;
        line-height: 2.2;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .verification-page .totals-card {
        padding: 22px;
        background: #fafafa;
        border: 1px solid var(--line);
        border-radius: 15px;
    }

    .verification-page .total-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 0;
        font-size: 13px;
    }

    .verification-page .total-label {
        color: var(--muted);
    }

    .verification-page .total-value {
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .verification-page .grand-total {
        margin-top: 10px;
        padding: 18px 0 0;
        border-top: 1px solid #dedede;
    }

    .verification-page .grand-total .total-label,
    .verification-page .grand-total .total-value {
        color: var(--brand);
        font-size: 16px;
        font-weight: 900;
    }

    .verification-page .currency-note {
        margin: 12px 0 0;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.9;
    }

    .verification-page .notes-list {
        display: grid;
        gap: 10px;
    }

    .verification-page .note-card {
        padding: 15px 18px;
        background: #fafafa;
        border: 1px solid var(--line);
        border-radius: 12px;
        font-size: 13px;
        line-height: 2.1;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .verification-page .document-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-top: 34px;
        padding-top: 26px;
        border-top: 1px solid var(--line);
    }

    .verification-page .verification-footnote {
        max-width: 620px;
        color: var(--muted);
        font-size: 12px;
        line-height: 2.1;
    }

    .verification-page .verification-footnote strong {
        display: block;
        margin-bottom: 5px;
        color: var(--ink);
        font-size: 13px;
    }

    .verification-page .print-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 0 22px;
        color: #fff;
        background: var(--brand);
        border: 1px solid var(--brand);
        border-radius: 11px;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
        white-space: nowrap;
    }

    .verification-page .print-button:hover {
        background: var(--brand-dark);
        transform: translateY(-1px);
    }

    .verification-page .document-footer {
        padding: 20px 32px;
        color: var(--muted);
        background: #fafafa;
        border-top: 1px solid var(--line);
        font-size: 11px;
        line-height: 2;
        text-align: center;
    }

    @media (max-width: 800px) {
        .verification-page .document-shell {
            margin-top: 24px;
        }

        .verification-page .document-banner,
        .verification-page .document-content {
            padding: 24px;
        }

        .verification-page .details-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .verification-page .summary-layout {
            grid-template-columns: 1fr;
        }

        .verification-page .document-bottom {
            align-items: stretch;
            flex-direction: column;
        }

        .verification-page .print-button {
            width: 100%;
        }
    }

    @media (max-width: 560px) {
        .verification-page .document-shell {
            width: calc(100% - 20px);
            margin: 14px auto 30px;
        }

        .verification-page .document-card {
            border-radius: 16px;
        }

        .verification-page .document-banner,
        .verification-page .document-content {
            padding: 18px;
        }

        .verification-page .brand-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 18px;
        }

        .verification-page .brand-info {
            gap: 12px;
        }

        .verification-page .brand-logo {
            width: 58px;
            height: 58px;
            flex-basis: 58px;
            border-radius: 12px;
        }

        .verification-page .brand-caption {
            font-size: 11px;
        }

        .verification-page .document-type-box {
            min-width: 0;
            padding: 8px 14px;
            text-align: right;
        }

        .verification-page .document-type-box strong {
            font-size: 16px;
        }

        .verification-page .banner-bottom {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .verification-page .document-number {
            font-size: 15px;
        }

        .verification-page .status-panel {
            gap: 12px;
            padding: 15px;
            margin-bottom: 26px;
        }

        .verification-page .status-icon {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
        }

        .verification-page .status-title {
            font-size: 15px;
        }

        .verification-page .details-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .verification-page .detail-card {
            padding: 13px 15px;
        }

        .verification-page .detail-label {
            margin-bottom: 4px;
        }

        .verification-page .section {
            margin-top: 28px;
        }

        .verification-page .section-heading {
            font-size: 15px;
        }

        .verification-page .table-frame {
            overflow-x: auto;
        }

        .verification-page .items-table {
            min-width: 680px;
        }

        .verification-page .document-footer {
            padding: 18px;
        }
    }

    @page {
        size: A4;
        margin: 12mm;
    }

    @media print {
        html,
        body {
            background: #fff !important;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .verification-page .document-shell {
            width: 100%;
            max-width: none;
            margin: 0;
        }

        .verification-page .document-card {
            overflow: visible;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .verification-page .document-banner {
            padding: 20px 24px;
        }

        .verification-page .document-content {
            padding: 22px 0;
        }

        .verification-page .status-panel,
        .verification-page .detail-card,
        .verification-page .totals-card,
        .verification-page .summary-note,
        .verification-page .note-card,
        .verification-page .table-frame {
            break-inside: avoid;
        }

        .verification-page .section {
            break-inside: avoid;
        }

        .verification-page .items-table {
            min-width: 0;
        }

        .verification-page .items-table th,
        .verification-page .items-table td {
            padding: 9px 7px;
            font-size: 10px;
        }

        .verification-page .details-grid {
            gap: 8px;
        }

        .verification-page .detail-card {
            padding: 11px;
        }

        .verification-page .document-bottom {
            margin-top: 22px;
        }

        .verification-page .print-button,
        .verification-page .no-print,
        body > header,
        body > footer,
        .site-header,
        .site-footer,
        #site-header,
        #site-footer {
            display: none !important;
        }

        a {
            color: inherit !important;
            text-decoration: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="verification-page">
    <div class="document-shell">
        <article class="document-card">

            <header class="document-banner">
                <div class="brand-row">
                    <div class="brand-info">
                        <div class="brand-logo">
                            <img
                                src="{{ asset('img/kohan-logo.png') }}"
                                alt="لوگوی کهن پلیمر البرز"
                            >
                        </div>

                        <div>
                            <h1 class="brand-name">کهن پلیمر البرز</h1>
                            <p class="brand-caption">
                                سامانه بررسی اصالت و اعتبار اسناد
                            </p>
                        </div>
                    </div>

                    <div class="document-type-box">
                        <span>نوع سند صادرشده</span>
                        <strong>{{ $documentType }}</strong>
                    </div>
                </div>

                <div class="banner-bottom">
                    <div>
                        <div class="document-number-label">شماره سند</div>
                        <div class="document-number">
                            {{ $invoice->document_number }}
                        </div>
                    </div>

                    <div class="verification-label">
                        <span>●</span>
                        <span>بررسی آنلاین سند</span>
                    </div>
                </div>
            </header>

            <div class="document-content">

                <section
                    class="status-panel {{ $isVoided ? 'voided' : ($isExpired ? 'expired' : 'valid') }}"
                    aria-live="polite"
                >
                    <div class="status-icon">
                        @if($isVoided)
                            ×
                        @elseif($isExpired)
                            !
                        @else
                            ✓
                        @endif
                    </div>

                    <div>
                        <h2 class="status-title">
                            وضعیت اعتبار سند: {{ $statusText }}
                        </h2>

                        <p class="status-description">
                            {{ $statusDescription }}
                        </p>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-heading">مشخصات سند</h2>

                    <div class="details-grid">
                        <div class="detail-card">
                            <span class="detail-label">شماره سند</span>
                            <strong class="detail-value">
                                {{ $invoice->document_number }}
                            </strong>
                        </div>

                        <div class="detail-card">
                            <span class="detail-label">نام مشتری</span>
                            <strong class="detail-value">
                                {{ $customerName }}
                            </strong>
                        </div>

                        <div class="detail-card">
                            <span class="detail-label">تاریخ صدور</span>
                            <strong class="detail-value">
                                {{ $formatDate($invoice->issue_date) }}
                            </strong>
                        </div>

                        @if($invoice->type === 'proforma')
                            <div class="detail-card">
                                <span class="detail-label">معتبر تا تاریخ</span>
                                <strong class="detail-value">
                                    {{ $formatDate($invoice->valid_until) }}
                                </strong>
                            </div>
                        @endif

                        <div class="detail-card">
                            <span class="detail-label">نوع پرداخت</span>
                            <strong class="detail-value">
                                {{ $invoice->payment_type ?: '—' }}
                            </strong>
                        </div>

                        <div class="detail-card">
                            <span class="detail-label">نوع صدور</span>
                            <strong class="detail-value">
                                {{ $invoice->is_formal ? 'رسمی' : 'غیررسمی' }}
                            </strong>
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-heading">جزئیات اقلام</h2>
                    <p class="section-caption">
                        فهرست اقلام و مبالغ ثبت‌شده در سند صادرشده
                    </p>

                    <div class="table-frame">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>شرح محصول</th>
                                    <th>تعداد</th>
                                    <th>واحد</th>
                                    <th>قیمت واحد</th>
                                    <th>مبلغ اقلام</th>
                                    <th>مالیات</th>
                                    <th>مبلغ نهایی</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($items as $item)
                                    @php
                                        $quantity = (float) ($item->quantity ?? 0);
                                        $unitPrice = (float) ($item->unit_price ?? 0);
                                        $lineSubtotal = (float) ($item->total_amount ?? ($quantity * $unitPrice));
                                        $lineTax = (float) ($item->vat_amount ?? 0);
                                        $lineGrandTotal = (float) (
                                            $item->grand_total
                                            ?? ($lineSubtotal + $lineTax)
                                        );
                                    @endphp

                                    <tr>
                                        <td class="number-cell">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="product-description">
                                            {{ $item->description ?: '—' }}

                                            @if($item->width || $item->thickness)
                                                <div class="text-xs font-normal text-gray-500">
                                                    @if($item->width)
                                                        عرض: {{ $item->width }}
                                                    @endif

                                                    @if($item->width && $item->thickness)
                                                        |
                                                    @endif

                                                    @if($item->thickness)
                                                        ضخامت: {{ $item->thickness }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <td class="number-cell">
                                            {{ number_format($quantity, 2, '.', ',') }}
                                        </td>

                                        <td>{{ $item->unit ?: '—' }}</td>

                                        <td class="number-cell">
                                            {{ $formatMoney($unitPrice) }}
                                        </td>

                                        <td class="number-cell">
                                            {{ $formatMoney($lineSubtotal) }}
                                        </td>

                                        <td class="number-cell">
                                            {{ $formatMoney($lineTax) }}
                                        </td>

                                        <td class="number-cell font-bold">
                                            {{ $formatMoney($lineGrandTotal) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty-row">
                                            هیچ قلمی برای این سند ثبت نشده است.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-heading">خلاصه مالی سند</h2>

                    <div class="summary-layout">
                        <div>
                            @if($invoice->description)
                                <div class="summary-note">
                                    <h3 class="summary-note-title">
                                        توضیحات سند
                                    </h3>

                                    <p class="summary-note-text">{{ $invoice->description }}</p>
                                </div>
                            @else
                                <div class="summary-note">
                                    <h3 class="summary-note-title">
                                        اطلاعات مالی
                                    </h3>

                                    <p class="summary-note-text">
                                        مبالغ این بخش بر اساس اطلاعات ثبت‌شده برای اقلام سند محاسبه شده‌اند.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="totals-card">
                            <div class="total-row">
                                <span class="total-label">جمع اقلام</span>
                                <strong class="total-value">
                                    {{ $formatMoney($subtotal) }}
                                </strong>
                            </div>

                            <div class="total-row">
                                <span class="total-label">مالیات و عوارض</span>
                                <strong class="total-value">
                                    {{ $formatMoney($taxAmount) }}
                                </strong>
                            </div>

                            <div class="total-row grand-total">
                                <span class="total-label">مبلغ نهایی</span>
                                <strong class="total-value">
                                    {{ $formatMoney($grandTotal) }}
                                </strong>
                            </div>

                            <p class="currency-note">
                                واحد پول مطابق اطلاعات ثبت‌شده در سامانه است.
                            </p>
                        </div>
                    </div>
                </section>

                @if($invoice->notes->isNotEmpty())
                    <section class="section">
                        <h2 class="section-heading">یادداشت‌ها</h2>

                        <div class="notes-list">
                            @foreach($invoice->notes as $note)
                                <div class="note-card">
                                    {{ $note->description ?? '—' }}
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="document-bottom">
                    <div class="verification-footnote">
                        <strong>درباره تأیید اصالت سند</strong>
                        این صفحه برای بررسی وضعیت سند صادرشده از سامانه کهن پلیمر البرز ارائه شده است.
                        وضعیت اعتبار در زمان مشاهده محاسبه می‌شود.
                    </div>

                    <button
                        type="button"
                        class="print-button no-print"
                        onclick="window.print()"
                    >
                        <span>▤</span>
                        <span>چاپ یا ذخیره PDF</span>
                    </button>
                </div>
            </div>

            <footer class="document-footer">
                کهن پلیمر البرز | مهندسی آینده در صنعت پلیمر
                <br>
                این صفحه صرفاً برای بررسی اصالت و وضعیت سند صادرشده است.
            </footer>
        </article>
    </div>
</div>
@endsection