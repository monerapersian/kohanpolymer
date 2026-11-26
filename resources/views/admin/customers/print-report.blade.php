@php
    use App\Support\NumberHelper;
    use App\Support\DateHelper;
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ $logoUrl }}">
    <title>گزارش اسناد مشتری - {{ $customer->name }}</title>

    <style>

        @font-face {
            font-family: 'Kohan';
            src: url('{{ asset('font/Kohan-Bold.woff2') }}') format('woff2');
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #f4f0ee;
            color: #362727;
            font-family: 'Kohan', Tahoma, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.9;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 18mm 15mm;
            background: #fff;
            box-shadow: 0 3px 20px rgba(54, 39, 39, .10);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 3px solid #674040;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand img {
            width: 85px;
            max-height: 75px;
            object-fit: contain;
        }

        .brand-name {
            font-family: 'Kohan', Tahoma, sans-serif;
            font-size: 21px;
            font-weight: bold;
            color: #362727;
        }

        .brand-subtitle {
            color: #8d6666;
            font-size: 11px;
        }

        .report-heading {
            text-align: left;
        }

        .report-heading h1 {
            margin: 0 0 5px;
            font-size: 17px;
            color: #674040;
        }

        .report-heading p {
            margin: 0;
            color: #777;
            font-size: 10px;
        }

        .report-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 20px 0;
        }

        .info-box {
            padding: 10px 12px;
            background: #f4f0ee;
            border: 1px solid #e5dcda;
            border-radius: 6px;
        }

        .info-label {
            display: block;
            margin-bottom: 3px;
            color: #8d6666;
            font-size: 10px;
        }

        .info-value {
            font-weight: bold;
            color: #362727;
            font-size: 12px;
        }

        .section-title {
            margin: 20px 0 10px;
            padding-right: 10px;
            border-right: 3px solid #8d6666;
            font-size: 13px;
            font-weight: bold;
            color: #674040;
        }

        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
            padding: 13px;
            border: 1px solid #e5dcda;
            border-radius: 6px;
        }

        .customer-item {
            display: flex;
            gap: 7px;
        }

        .customer-item strong {
            color: #674040;
            min-width: 85px;
        }

        table {
            width: 100%;
            margin-top: 12px;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th {
            padding: 10px 6px;
            background: #674040;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border: 1px solid #674040;
        }

        td {
            padding: 9px 6px;
            text-align: center;
            border: 1px solid #e5dcda;
            color: #362727;
        }

        tbody tr:nth-child(even) {
            background: #faf7f6;
        }

        .document-number {
            font-weight: bold;
            direction: ltr;
        }

        .total-cell {
            font-weight: bold;
            white-space: nowrap;
        }

        .empty-row {
            padding: 25px;
            color: #888;
            text-align: center;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            padding: 12px 15px;
            background: #f4f0ee;
            border: 1px solid #e5dcda;
            border-radius: 6px;
        }

        .summary-label {
            color: #674040;
            font-weight: bold;
        }

        .summary-value {
            font-weight: bold;
            font-size: 14px;
            color: #362727;
        }

        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            color: #888;
            font-size: 9px;
        }

        .print-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px auto;
        }

        .print-button {
            padding: 10px 25px;
            border: 0;
            border-radius: 6px;
            background: #674040;
            color: #fff;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
        }

        .back-button {
            padding: 10px 25px;
            border: 1px solid #8d6666;
            border-radius: 6px;
            background: #fff;
            color: #674040;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .print-actions {
                display: none !important;
            }

            .footer {
                page-break-inside: avoid;
            }
        }

        @media screen and (max-width: 800px) {
            body {
                padding: 10px;
            }

            .page {
                width: 100%;
                padding: 15px;
            }

            .header {
                flex-wrap: wrap;
                gap: 15px;
            }

            .report-info {
                grid-template-columns: 1fr;
            }

            .customer-info {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="print-actions">
        <button type="button" class="print-button" onclick="window.print()">
            چاپ گزارش / ذخیره PDF
        </button>

        <button type="button" class="back-button" onclick="window.close()">
            بستن صفحه
        </button>
    </div>

    <main class="page">

        <header class="header">
            <div class="brand">
                <img src="{{ $logoUrl }}" alt="لوگوی کهن پلیمر البرز">

                <div>
                    <div class="brand-name">کهن پلیمر البرز</div>
                    <div class="brand-subtitle">KohanPolymerAlborz</div>
                </div>
            </div>

            <div class="report-heading">
                <h1>گزارش اسناد مشتری</h1>
                <p>تاریخ تهیه گزارش: {{ $reportDate }}</p>
            </div>
        </header>

        <section class="report-info">
            <div class="info-box">
                <span class="info-label">تاریخ شروع گزارش</span>
                <span class="info-value">{{ $persianStartDate }}</span>
            </div>

            <div class="info-box">
                <span class="info-label">تاریخ پایان گزارش</span>
                <span class="info-value">{{ $persianEndDate }}</span>
            </div>

            <div class="info-box">
                <span class="info-label">تعداد اسناد</span>
                <span class="info-value">{{ NumberHelper::toPersian($documents->count()) }} سند</span>
            </div>
        </section>

        <h2 class="section-title">مشخصات مشتری</h2>

        <section class="customer-info">
            <div class="customer-item">
                <strong>نام مشتری:</strong>
                <span>{{ $customer->name }}</span>
            </div>

            <div class="customer-item">
                <strong>نوع مشتری:</strong>
                <span>
                    {{ $customer->type === 'company' ? 'حقوقی' : 'حقیقی' }}
                </span>
            </div>

            <div class="customer-item">
                <strong>شماره تماس:</strong>
                <span>{{ $customer->phone ?: '—' }}</span>
            </div>

            <div class="customer-item">
                <strong>
                    {{ $customer->type === 'company' ? 'شماره ثبت:' : 'کد ملی:' }}
                </strong>
                <span>{{ $customer->national_id ?: '—' }}</span>
            </div>
        </section>

        <h2 class="section-title">فهرست اسناد</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 45px;">ردیف</th>
                        <th>شماره سند</th>

                        @if($columns['type'])
                            <th>نوع سند</th>
                        @endif

                        @if($columns['issue_date'])
                            <th>تاریخ صدور</th>
                        @endif

                        @if($columns['payment_status'])
                            <th>وضعیت پرداخت</th>
                        @endif

                        @if($columns['total'])
                            <th>مبلغ جمع کل (ریال)</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($documents as $index => $document)
                        <tr>
                            <td>{{ NumberHelper::toPersian($index + 1) }}</td>

                            <td class="document-number">
                                {{ $document->document_number ?: $document->id }}
                            </td>

                            @if($columns['type'])
                                <td>{{ $document->report_type }}</td>
                            @endif

                            @if($columns['issue_date'])
                                <td>
                                    {{ NumberHelper::toPersian(
                                        DateHelper::toPersian($document->issue_date, 'Y/m/d')
                                    ) }}
                                </td>
                            @endif

                            @if($columns['payment_status'])
                                <td>{{ $document->report_payment_status }}</td>
                            @endif

                            @if($columns['total'])
                                <td class="total-cell">
                                    {{ NumberHelper::toPersian(
                                        number_format($document->report_total)
                                    ) }}
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + collect($columns)->filter()->count() }}"
                                class="empty-row">
                                در بازه زمانی انتخاب‌شده هیچ سندی برای این مشتری ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($columns['total'] && $documents->isNotEmpty())
            <section class="summary">
                <span class="summary-label">مجموع مبالغ اسناد</span>

                <span class="summary-value">
                    {{ NumberHelper::toPersian(
                        number_format($documents->sum('report_total'))
                    ) }}
                    ریال
                </span>
            </section>
        @endif

        <footer class="footer">
            <span>کهن پلیمر البرز</span>
            <span>گزارش سوابق اسناد مشتری</span>
        </footer>

    </main>

</body>
</html>