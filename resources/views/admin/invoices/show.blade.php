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
@endphp

<style>
    .invoice-print-sheet {
        display: none;
    }

    @media print {
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            background: #fff !important;
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
            inset: 0 auto auto 0;
            width: 100%;
            color: #222;
            background: #fff;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 10pt;
            direction: rtl;
        }

        #invoice-print-sheet .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 3px solid #681b21;
        }

        #invoice-print-sheet .print-brand {
            color: #681b21;
            font-size: 21pt;
            font-weight: bold;
        }

        #invoice-print-sheet .print-subtitle {
            margin-top: 4px;
            color: #666;
            font-size: 9pt;
        }

        #invoice-print-sheet .print-section-title {
            margin: 18px 0 10px;
            padding-right: 8px;
            border-right: 3px solid #681b21;
            font-size: 11pt;
            font-weight: bold;
        }

        #invoice-print-sheet .print-details {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        #invoice-print-sheet .print-detail {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow-wrap: anywhere;
        }

        #invoice-print-sheet .print-label {
            display: block;
            margin-bottom: 3px;
            color: #666;
            font-size: 8pt;
        }

        #invoice-print-sheet table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        #invoice-print-sheet th,
        #invoice-print-sheet td {
            padding: 7px 5px;
            border: 1px solid #ccc;
            text-align: right;
            vertical-align: top;
        }

        #invoice-print-sheet th {
            background: #681b21 !important;
            color: #fff !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        #invoice-print-sheet tr {
            break-inside: avoid;
        }

        #invoice-print-sheet .print-totals {
            width: 55%;
            margin: 14px 0 0 auto;
        }

        #invoice-print-sheet .print-total-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        #invoice-print-sheet .print-grand-total {
            color: #681b21;
            font-size: 12pt;
            font-weight: bold;
        }

        #invoice-print-sheet .print-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
        }

        #invoice-print-sheet .print-qr {
            width: 95px;
            height: 95px;
            object-fit: contain;
        }

        #invoice-print-sheet .print-signature {
            min-width: 150px;
            padding-top: 35px;
            text-align: center;
        }

        #invoice-print-sheet .print-note {
            margin-top: 12px;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }
    }
</style>

<div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6" dir="rtl">

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#222222]">
                جزئیات {{ $documentType }}
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                شماره سند: {{ $invoice->document_number }}
            </p>
        </div>

        <a href="{{ route('admin.invoices.index') }}"
           class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
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
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#51151a]">
            <span>چاپ سند</span>
        </button>

        <a href="{{ route('admin.invoices.export', $invoice) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-[#681b21] px-5 py-3 text-sm font-semibold text-[#681b21] transition hover:bg-[#681b21] hover:text-white">
            خروجی اکسل
        </a>

        <a href="{{ $verificationUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
            مشاهده صفحه تأیید سند
        </a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-[#222222]">اطلاعات سند</h2>

            @if($isVoided)
                <span class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700">
                    باطل شده
                </span>
            @elseif($isExpired)
                <span class="rounded-full bg-orange-100 px-3 py-1.5 text-sm font-semibold text-orange-700">
                    منقضی
                </span>
            @else
                <span class="rounded-full bg-green-100 px-3 py-1.5 text-sm font-semibold text-green-700">
                    معتبر
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">نوع سند</p>
                <p class="mt-1 font-semibold text-gray-800">{{ $documentType }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">شماره سند</p>
                <p class="mt-1 font-semibold text-gray-800">{{ $invoice->document_number }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">مشتری</p>
                <p class="mt-1 font-semibold text-gray-800">{{ $customerName }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">تاریخ صدور</p>
                <p class="mt-1 font-semibold text-gray-800">
                    {{ $invoice->issue_date?->format('Y/m/d') ?? '—' }}
                </p>
            </div>

            @if($invoice->type === 'proforma')
                <div>
                    <p class="text-sm text-gray-500">معتبر تا تاریخ</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $invoice->valid_until?->format('Y/m/d') ?? '—' }}
                    </p>
                </div>
            @endif

            <div>
                <p class="text-sm text-gray-500">نوع پرداخت</p>
                <p class="mt-1 font-semibold text-gray-800">{{ $invoice->payment_type ?: '—' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">نوع ثبت</p>
                <p class="mt-1 font-semibold text-gray-800">
                    {{ $invoice->is_formal ? 'رسمی' : 'غیررسمی' }}
                </p>
            </div>

            @if($invoice->convertedFrom)
                <div>
                    <p class="text-sm text-gray-500">پیش‌فاکتور مبنا</p>
                    <a href="{{ route('admin.invoices.show', $invoice->convertedFrom) }}"
                       class="mt-1 inline-block font-semibold text-[#681b21] hover:underline">
                        {{ $invoice->convertedFrom->document_number }}
                    </a>
                </div>
            @endif

            @if($invoice->convertedTo->isNotEmpty())
                <div>
                    <p class="text-sm text-gray-500">فاکتور صادرشده از این پیش‌فاکتور</p>
                    @foreach($invoice->convertedTo as $convertedInvoice)
                        <a href="{{ route('admin.invoices.show', $convertedInvoice) }}"
                           class="mt-1 block font-semibold text-[#681b21] hover:underline">
                            {{ $convertedInvoice->document_number }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if($invoice->description)
            <div class="mt-6 border-t border-gray-100 pt-5">
                <p class="text-sm text-gray-500">توضیحات سند</p>
                <p class="mt-2 whitespace-pre-line leading-7 text-gray-700">{{ $invoice->description }}</p>
            </div>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 p-5">
            <h2 class="text-lg font-bold text-[#222222]">اقلام سند</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-right text-sm">
                <thead class="bg-gray-50 text-gray-600">
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

                <tbody class="divide-y divide-gray-100">
                    @forelse($invoice->items as $item)
                        <tr class="text-gray-700">
                            <td class="px-4 py-4">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 font-medium">{{ $item->description }}</td>
                            <td class="px-4 py-4">{{ number_format((float) $item->quantity, 3) }}</td>
                            <td class="px-4 py-4">{{ $item->unit ?: '—' }}</td>
                            <td class="px-4 py-4">{{ $item->width ?? '—' }}</td>
                            <td class="px-4 py-4">{{ $item->thickness ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-4">{{ number_format((float) $item->unit_price) }}</td>
                            <td class="whitespace-nowrap px-4 py-4">{{ number_format((float) $item->total_amount) }}</td>
                            <td class="px-4 py-4">{{ number_format((float) $item->vat_percent, 2) }}</td>
                            <td class="whitespace-nowrap px-4 py-4">{{ number_format((float) $item->vat_amount) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 font-semibold">
                                {{ number_format((float) $item->grand_total) }}
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
                    <tfoot class="border-t-2 border-gray-200 bg-gray-50">
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-left font-bold text-gray-800">
                                جمع کل سند (ریال)
                            </td>
                            <td class="px-4 py-4 font-bold text-gray-800">
                                {{ number_format($invoice->items->sum('total_amount')) }}
                            </td>
                            <td></td>
                            <td class="px-4 py-4 font-bold text-gray-800">
                                {{ number_format($invoice->items->sum('vat_amount')) }}
                            </td>
                            <td class="px-4 py-4 font-bold text-[#681b21]">
                                {{ number_format($invoice->items->sum('grand_total')) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if($invoice->notes->isNotEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="mb-4 text-lg font-bold text-[#222222]">توضیحات تکمیلی</h2>

            <ol class="list-inside list-decimal space-y-3 text-sm leading-7 text-gray-700">
                @foreach($invoice->notes as $note)
                    <li>{{ $note->description }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="mb-4 text-lg font-bold text-[#222222]">تأیید اصالت سند با QR</h2>

        <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center">
            @if(!empty($qrCodeDataUri))
                <img src="{{ $qrCodeDataUri }}"
                     alt="QR تأیید سند"
                     class="h-40 w-40 rounded-xl border border-gray-200 bg-white p-2">
            @else
                <div class="flex h-40 w-40 items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-center text-sm text-gray-500">
                    کد QR تولید نشده است
                </div>
            @endif

            <div class="min-w-0 space-y-2">
                <p class="font-semibold text-gray-800">
                    وضعیت فعلی: {{ $statusText }}
                </p>

                <p class="text-sm leading-6 text-gray-500">
                    با اسکن این کد، صفحه عمومی بررسی اعتبار همین سند باز می‌شود.
                </p>

                <a href="{{ $verificationUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="block break-all text-sm text-[#681b21] hover:underline">
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
                        class="cursor-pointer rounded-xl bg-[#003260] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#002448]">
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
    <header class="print-header">
        <div>
            <div class="print-brand">کهن پلیمر البرز</div>
            <div class="print-subtitle">فاکتور فروش و پیش‌فاکتور</div>
        </div>

        <div style="text-align:left;">
            <div style="font-size:17pt;font-weight:bold;">{{ $documentType }}</div>
            <div>شماره سند: {{ $invoice->document_number }}</div>
            <div>وضعیت: {{ $statusText }}</div>
        </div>
    </header>

    <h2 class="print-section-title">مشخصات سند و مشتری</h2>

    <div class="print-details">
        <div class="print-detail">
            <span class="print-label">نام مشتری</span>
            <strong>{{ $customerName }}</strong>
        </div>

        <div class="print-detail">
            <span class="print-label">تاریخ صدور</span>
            <strong>{{ $invoice->issue_date?->format('Y/m/d') ?? '—' }}</strong>
        </div>

        <div class="print-detail">
            <span class="print-label">نوع پرداخت</span>
            <strong>{{ $invoice->payment_type ?: '—' }}</strong>
        </div>

        @if($invoice->type === 'proforma')
            <div class="print-detail">
                <span class="print-label">معتبر تا تاریخ</span>
                <strong>{{ $invoice->valid_until?->format('Y/m/d') ?? '—' }}</strong>
            </div>
        @endif

        <div class="print-detail">
            <span class="print-label">نوع ثبت</span>
            <strong>{{ $invoice->is_formal ? 'رسمی' : 'غیررسمی' }}</strong>
        </div>

        @if($invoice->convertedFrom)
            <div class="print-detail">
                <span class="print-label">پیش‌فاکتور مبنا</span>
                <strong>{{ $invoice->convertedFrom->document_number }}</strong>
            </div>
        @endif
    </div>

    <h2 class="print-section-title">اقلام سند</h2>

    <table>
        <thead>
            <tr>
                <th>ردیف</th>
                <th>شرح کالا</th>
                <th>تعداد</th>
                <th>واحد</th>
                <th>عرض</th>
                <th>ضخامت</th>
                <th>قیمت واحد</th>
                <th>مبلغ کل</th>
                <th>مالیات</th>
                <th>مبلغ مالیات</th>
                <th>مبلغ نهایی</th>
            </tr>
        </thead>

        <tbody>
            @forelse($invoice->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format((float) $item->quantity, 3) }}</td>
                    <td>{{ $item->unit ?: '—' }}</td>
                    <td>{{ $item->width ?? '—' }}</td>
                    <td>{{ $item->thickness ?? '—' }}</td>
                    <td>{{ number_format((float) $item->unit_price) }}</td>
                    <td>{{ number_format((float) $item->total_amount) }}</td>
                    <td>{{ number_format((float) $item->vat_percent, 2) }}٪</td>
                    <td>{{ number_format((float) $item->vat_amount) }}</td>
                    <td>{{ number_format((float) $item->grand_total) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">هیچ قلمی برای این سند ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>

        @if($invoice->items->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="7" style="font-weight:bold;">جمع کل</td>
                    <td style="font-weight:bold;">
                        {{ number_format($invoice->items->sum('total_amount')) }}
                    </td>
                    <td></td>
                    <td style="font-weight:bold;">
                        {{ number_format($invoice->items->sum('vat_amount')) }}
                    </td>
                    <td style="font-weight:bold;color:#681b21;">
                        {{ number_format($invoice->items->sum('grand_total')) }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    @if($invoice->description)
        <h2 class="print-section-title">توضیحات سند</h2>
        <div class="print-note">{{ $invoice->description }}</div>
    @endif

    @if($invoice->notes->isNotEmpty())
        <h2 class="print-section-title">توضیحات تکمیلی</h2>

        @foreach($invoice->notes as $note)
            <div class="print-note">{{ $note->description }}</div>
        @endforeach
    @endif

    <footer class="print-footer">
        <div>
            <strong>کهن پلیمر البرز</strong>
            <div style="margin-top:6px;">بررسی اصالت سند با اسکن کد QR</div>
            <div>نشانی تأیید: {{ $verificationUrl }}</div>
        </div>

        <div class="print-signature">
            مهر و امضا
        </div>

        @if(!empty($qrCodeDataUri))
            <img src="{{ $qrCodeDataUri }}" alt="QR" class="print-qr">
        @endif
    </footer>
</div>
@endsection