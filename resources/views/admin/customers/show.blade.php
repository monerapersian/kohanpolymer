@extends('layouts.admin')

@section('title', 'پروفایل مشتری | کهن پلیمر البرز')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="hero-animated-bg relative overflow-hidden rounded-3xl border border-white/10 p-6 shadow-2xl sm:p-8">
            <div class="hero-orb hero-orb-one"></div>
            <div class="hero-orb hero-orb-two"></div>
            <div class="hero-orb hero-orb-three"></div>
            <div class="hero-wave"></div>
            <div class="hero-ring hero-ring-one"></div>
            <div class="hero-ring hero-ring-two"></div>
            <div class="hero-grid"></div>

            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/70">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#baadad]"></span>
                        مدیریت ارتباط با مشتریان
                    </span>

                    <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                        پروفایل مشتری
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                        مشاهده اطلاعات مشتری و سوابق فاکتورها و پیش‌فاکتورهای صادرشده
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm text-white transition hover:bg-white/10"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                            <path d="M19 12H5m7 7-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        بازگشت به مشتریان
                    </a>

                    <button
                        type="button"
                        onclick="openCustomerEditModal()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8d6666] px-5 py-3 text-sm text-white kohan-bold transition duration-300 hover:bg-[#a07878] hover:shadow-lg hover:shadow-black/20"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                            <path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ویرایش اطلاعات
                    </button>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Customer Overview --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Customer Name --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#827875]">نام مشتری</p>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <span class="min-w-0 truncate text-base text-[#362727] kohan-bold">
                        {{ $customer->name }}
                    </span>

                    <span class="shrink-0 rounded-xl bg-[#F3E9E9] p-3 text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Customer Type --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#827875]">نوع مشتری</p>

                <div class="mt-3 flex items-center justify-between gap-3">
                    @if($customer->type === 'company')
                        <span class="text-base text-rose-700 kohan-bold">شخص حقوقی</span>
                        <span class="rounded-xl bg-rose-50 p-3 text-rose-700">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                <path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h2v4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    @else
                        <span class="text-base text-emerald-700 kohan-bold">شخص حقیقی</span>
                        <span class="rounded-xl bg-emerald-50 p-3 text-emerald-700">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 21v-2a8 8 0 0 1 16 0v2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Phone --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#827875]">شماره تلفن</p>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <span class="text-base text-[#362727] kohan-bold" dir="ltr">
                        {{ $customer->phone ?: '—' }}
                    </span>

                    <span class="rounded-xl bg-[#F3E9E9] p-3 text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                            <path d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Documents Count --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#827875]">تعداد اسناد صادرشده</p>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <span class="text-3xl text-[#362727] kohan-bold">
                        {{ number_format($documents->total()) }}
                    </span>

                    <span class="rounded-xl bg-[#F3E9E9] p-3 text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke-linejoin="round"/>
                            <path d="M14 3v5h5M9 13h6m-6 4h6" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
            </div>

        </section>

        {{-- Customer Financial Status --}}
        <section class="space-y-4">
            <div>
                <h2 class="text-lg text-[#362727] kohan-bold">
                    وضعیت مالی مشتری
                </h2>
                <p class="mt-1 text-xs text-[#9A8E89]">
                    خلاصه خریدها و پرداخت‌های ثبت‌شده از ابتدای همکاری
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Total Purchases --}}
                <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-blue-50 p-5">
                    <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-blue-100/70"></div>

                    <div class="relative flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm text-blue-700">مجموع خرید مشتری</p>

                            <p class="mt-4 text-xl text-blue-900 kohan-bold">
                                {{ \App\Support\NumberHelper::toPersian(number_format((float) $financialTotals['purchases'])) }}
                            </p>

                            <p class="mt-2 text-xs text-blue-600">ریال</p>
                        </div>

                        <span class="rounded-xl bg-blue-100 p-3 text-blue-700">
                            <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                                <path d="M3 3h2l2.2 12.1a2 2 0 0 0 2 1.7h8.9a2 2 0 0 0 2-1.6L22 8H6" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="10" cy="20" r="1"/>
                                <circle cx="18" cy="20" r="1"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Total Paid --}}
                <div class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                    <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-emerald-100/70"></div>

                    <div class="relative flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm text-emerald-700">مجموع مبالغ پرداخت‌شده</p>

                            <p class="mt-4 text-xl text-emerald-900 kohan-bold">
                                {{ \App\Support\NumberHelper::toPersian(number_format((float) $financialTotals['paid'])) }}
                            </p>

                            <p class="mt-2 text-xs text-emerald-600">ریال</p>
                        </div>

                        <span class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                            <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                                <path d="M12 2v20M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Total Debt --}}
                <div class="relative overflow-hidden rounded-2xl border border-rose-100 bg-rose-50 p-5">
                    <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-rose-100/70"></div>

                    <div class="relative flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm text-rose-700">مانده بدهی مشتری</p>

                            <p class="mt-4 text-xl text-rose-900 kohan-bold">
                                {{ \App\Support\NumberHelper::toPersian(number_format((float) $financialTotals['debt'])) }}
                            </p>

                            <p class="mt-2 text-xs text-rose-600">ریال</p>
                        </div>

                        <span class="rounded-xl bg-rose-100 p-3 text-rose-700">
                            <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                                <path d="M12 8v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3l-7.6-13.1a2 2 0 0 0-3.4 0Z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>

            </div>
        </section>

        {{-- Customer Details --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="flex flex-col gap-2 border-b border-[#EEE8E5] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-[#362727] kohan-bold">اطلاعات مشتری</h2>

                <button
                    type="button"
                    onclick="openCustomerEditModal()"
                    class="inline-flex items-center gap-2 text-sm text-[#8d6666] transition hover:text-[#542b2e]"
                >
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                        <path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    ویرایش اطلاعات
                </button>
            </div>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2 sm:p-6">

                <div>
                    <p class="text-xs text-[#9A8E89]">نام و نام خانوادگی / نام شرکت</p>
                    <p class="mt-2 text-sm text-[#362727] kohan-bold">{{ $customer->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">نوع مشتری</p>
                    <p class="mt-2 text-sm text-[#5F514D]">
                        {{ $customer->type === 'company' ? 'شخص حقوقی' : 'شخص حقیقی' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">شماره تلفن</p>
                    <p class="mt-2 text-sm text-[#5F514D]" dir="rtl">{{ $customer->phone ?: '—' }}</p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">
                        {{ $customer->type === 'company' ? 'شماره ثبت شرکت' : 'کد ملی / شناسه' }}
                    </p>
                    <p class="mt-2 text-sm text-[#5F514D]">{{ $customer->national_id ?: '—' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-[#9A8E89]">توضیحات</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-7 text-[#5F514D]">
                        {{ $customer->description ?: 'توضیحاتی برای این مشتری ثبت نشده است.' }}
                    </p>
                </div>

            </div>
        </section>

        {{-- Customer Documents --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="flex flex-col gap-2 border-b border-[#EEE8E5] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-[#362727] kohan-bold">فاکتورها و پیش‌فاکتورها</h2>
                    <p class="mt-1 text-xs text-[#9A8E89]">
                        سوابق تمام اسناد صادرشده برای این مشتری
                    </p>
                </div>

                <span class="text-xs text-[#9A8E89]">
                    {{ number_format($documents->total()) }} سند
                </span>
            </div>

            {{-- Document Filters --}}
            <div class="border-b border-[#EEE8E5] bg-[#FAF8F7] p-5 sm:p-6">

                <form
                    method="GET"
                    action="{{ route('admin.customers.show', $customer) }}"
                    id="customer-documents-filter"
                    class="space-y-5"
                >

                    {{-- Search --}}
                    <div>
                        <label for="document-search" class="mb-2 block text-xs text-[#827875]">
                            جستجوی اسناد
                        </label>

                        <div class="relative">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[#A99D98]">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m16 16 4 4" stroke-linecap="round"/>
                                </svg>
                            </span>

                            <input
                                type="text"
                                name="search"
                                id="document-search"
                                value="{{ $search }}"
                                placeholder="شماره سند یا توضیحات سند را وارد کنید..."
                                class="w-full rounded-xl border border-[#E8E1DE] bg-white py-3 pr-12 pl-4 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                            >
                        </div>
                    </div>

                    {{-- Select Filters --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <label for="document-type-filter" class="mb-2 block text-xs text-[#827875]">نوع سند</label>

                            <select
                                name="document_type"
                                id="document-type-filter"
                                class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                            >
                                <option value="">همه اسناد</option>
                                <option value="invoice" @selected($documentType === 'invoice')>فاکتور</option>
                                <option value="proforma" @selected($documentType === 'proforma')>پیش‌فاکتور</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment-status-filter" class="mb-2 block text-xs text-[#827875]">وضعیت پرداخت</label>

                            <select
                                name="payment_status"
                                id="payment-status-filter"
                                class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                            >
                                <option value="">همه وضعیت‌ها</option>
                                <option value="settled" @selected($paymentStatus === 'settled')>تسویه شده</option>
                                <option value="debt" @selected($paymentStatus === 'debt')>دارای بدهی</option>
                            </select>
                        </div>

                    </div>

                    {{-- Date Range --}}
                    <div>
                        <p class="mb-3 text-xs text-[#827875]">بازه تاریخ صدور سند</p>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-xs text-[#9A8E89]">از تاریخ</label>

                                <x-admin.jalali-date-picker
                                    name="start_date"
                                    id="customer-documents-start-date"
                                    :value="$startDate"
                                    placeholder="تاریخ شروع"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                                />
                            </div>

                            <div>
                                <label class="mb-2 block text-xs text-[#9A8E89]">تا تاریخ</label>

                                <x-admin.jalali-date-picker
                                    name="end_date"
                                    id="customer-documents-end-date"
                                    :value="$endDate"
                                    placeholder="تاریخ پایان"
                                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                                />
                            </div>

                        </div>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="flex flex-col gap-3 border-t border-[#EEE8E5] pt-4 sm:flex-row sm:items-center sm:justify-between">

                        <a
                            href="{{ route('admin.customers.show', $customer) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#E8E1DE] bg-white px-5 py-3 text-xs text-[#827875] transition hover:border-[#8d6666] hover:text-[#674040]"
                        >
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 3v5h5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            پاک کردن فیلترها
                        </a>

                        <div class="flex flex-col gap-3 sm:flex-row">

                            <button
                                type="button"
                                id="print-customer-documents-report"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#674040] bg-white px-5 py-3 text-xs text-[#674040] transition hover:bg-[#F3E9E9]"
                            >
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                                    <path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 14h10v7H7z" stroke-linejoin="round"/>
                                    <path d="M17 11h.01" stroke-linecap="round"/>
                                </svg>
                                چاپ گزارش
                            </button>

                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#674040] px-6 py-3 text-xs text-white transition hover:bg-[#362727]"
                            >
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m16 16 4 4" stroke-linecap="round"/>
                                </svg>
                                اعمال فیلترها
                            </button>

                        </div>

                    </div>

                </form>

            </div>

            @if($documents->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#8d6666]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke-linejoin="round"/>
                            <path d="M14 3v5h5M9 13h6m-6 4h6" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-[#362727] kohan-bold">هنوز سندی برای این مشتری ثبت نشده است</h3>

                    <p class="mt-2 text-sm text-[#9A8E89]">
                        پس از صدور فاکتور یا پیش‌فاکتور، سوابق آن در این بخش نمایش داده می‌شود.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-right">

                        <thead class="bg-[#FAF8F7] text-xs text-[#827875]">
                            <tr>
                                <th class="px-5 py-4 font-medium">ردیف</th>
                                <th class="px-5 py-4 font-medium">شماره سند</th>
                                <th class="px-5 py-4 font-medium">نوع سند</th>
                                <th class="px-5 py-4 font-medium">تاریخ صدور</th>
                                <th class="px-5 py-4 font-medium">وضعیت پرداخت</th>
                                <th class="px-5 py-4 text-center font-medium">مشاهده</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#F0EBE8]">
                            @foreach($documents as $document)
                                @php
                                    $documentGrandTotal = (float) $document->items->sum('grand_total');
                                    $documentPaidAmount = (float) $document->payments->sum('amount');
                                    $documentRemaining = max($documentGrandTotal - $documentPaidAmount, 0);
                                @endphp

                                <tr class="transition hover:bg-[#FAF8F7]">
                                    <td class="px-5 py-4 text-sm text-[#A99D98]">
                                        {{ $documents->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-[#362727] kohan-bold" dir="ltr">
                                            {{ $document->document_number ?: $document->id }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($document->type === 'proforma')
                                            <span class="inline-flex rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs text-amber-700">پیش‌فاکتور</span>
                                        @else
                                            <span class="inline-flex rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-700">فاکتور</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-sm text-[#5F514D]">
                                        @if($document->issue_date)
                                            {{ \App\Support\NumberHelper::toPersian(\App\Support\DateHelper::toPersian($document->issue_date, 'Y/m/d')) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($document->type === 'proforma')
                                            <span class="text-sm text-[#A99D98]">—</span>
                                        @elseif($document->status === 'voided')
                                            <span class="inline-flex rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs text-gray-600">باطل شده</span>
                                        @elseif($documentRemaining <= 0)
                                            <span class="inline-flex rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-700">تسویه کامل</span>
                                        @else
                                            <span
                                                class="inline-block text-sm text-rose-600 kohan-bold"
                                                dir="ltr"
                                                style="direction: ltr; unicode-bidi: isolate;"
                                            >
                                                -{{ \App\Support\NumberHelper::toPersian(number_format($documentRemaining)) }}
                                            </span>
                                            <span class="mr-1 text-xs text-rose-500">ریال</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <a
                                            href="{{ route('admin.invoices.show', $document) }}"
                                            title="مشاهده سند"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-[#E8E1DE] text-[#827875] transition hover:border-[#8d6666] hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke-linejoin="round"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-t border-[#EEE8E5] px-5 py-4">
                    {{ $documents->links('vendor.pagination.kohan') }}
                </div>
            @endif

        </section>

    </div>

    {{-- Customer Report Modal --}}
    <div
        id="customer-report-modal"
        class="fixed inset-0 z-[110] hidden items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="customer-report-modal-title"
        >

            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-4 sm:px-6">
                <div>
                    <h2 id="customer-report-modal-title" class="text-lg text-[#362727] kohan-bold">
                        تنظیم گزارش مشتری
                    </h2>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        بازه زمانی و ستون‌های موردنظر گزارش را مشخص کنید.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeCustomerReportModal()"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl text-[#9A8E89] transition hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                    aria-label="بستن"
                >
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form
                method="GET"
                action="{{ route('admin.customers.report', $customer) }}"
                target="_blank"
                id="customer-report-form"
                class="space-y-6 p-5 sm:p-6"
            >

                @if(session('report_error'))
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('report_error') }}
                    </div>
                @endif
                {{-- Report Date Range --}}
                <div>
                    <p class="mb-3 text-sm text-[#5F514D] kohan-bold">
                        بازه زمانی گزارش
                    </p>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs text-[#827875]">
                                از تاریخ
                            </label>

                            <x-admin.jalali-date-picker
                                name="start_date"
                                id="customer-report-start-date"
                                :value="now()->startOfYear()->format('Y-m-d')"
                                placeholder="تاریخ شروع"
                                :required="true"
                                class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs text-[#827875]">
                                تا تاریخ
                            </label>

                            <x-admin.jalali-date-picker
                                name="end_date"
                                id="customer-report-end-date"
                                :value="now()->format('Y-m-d')"
                                placeholder="تاریخ پایان"
                                :required="true"
                                class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                            />
                        </div>
                    </div>
                </div>

                {{-- Report Columns --}}
                <div class="border-t border-[#EEE8E5] pt-5">
                    <p class="mb-3 text-sm text-[#5F514D] kohan-bold">
                        ستون‌های گزارش
                    </p>

                    <p class="mb-4 text-xs text-[#9A8E89]">
                        ستون‌هایی را که می‌خواهید در گزارش نمایش داده شوند انتخاب کنید.
                    </p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 transition hover:border-[#8d6666]">
                            <input
                                type="checkbox"
                                name="show_type"
                                value="1"
                                checked
                                class="h-4 w-4 cursor-pointer rounded border-[#CDBFBB] text-[#674040] focus:ring-[#8d6666]"
                            >
                            <span class="text-sm text-[#5F514D]">نوع سند</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 transition hover:border-[#8d6666]">
                            <input
                                type="checkbox"
                                name="show_issue_date"
                                value="1"
                                checked
                                class="h-4 w-4 cursor-pointer rounded border-[#CDBFBB] text-[#674040] focus:ring-[#8d6666]"
                            >
                            <span class="text-sm text-[#5F514D]">تاریخ صدور</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 transition hover:border-[#8d6666]">
                            <input
                                type="checkbox"
                                name="show_payment_status"
                                value="1"
                                checked
                                class="h-4 w-4 cursor-pointer rounded border-[#CDBFBB] text-[#674040] focus:ring-[#8d6666]"
                            >
                            <span class="text-sm text-[#5F514D]">وضعیت پرداخت</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 transition hover:border-[#8d6666]">
                            <input
                                type="checkbox"
                                name="show_total"
                                value="1"
                                checked
                                class="h-4 w-4 cursor-pointer rounded border-[#CDBFBB] text-[#674040] focus:ring-[#8d6666]"
                            >
                            <span class="text-sm text-[#5F514D]">مبلغ جمع کل</span>
                        </label>

                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-[#EEE8E5] pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="closeCustomerReportModal()"
                        class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] transition hover:bg-[#FAF8F7] hover:text-[#542b2e] kohan-bold"
                    >
                        انصراف
                    </button>

                    <button
                        type="submit"
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#542b2e] px-6 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                            <path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 14h10v7H7z" stroke-linejoin="round"/>
                        </svg>
                        تهیه گزارش و چاپ
                    </button>

                </div>
            </form>
        </div>
    </div>

    {{-- Edit Customer Modal --}}
    <div
        id="customer-edit-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="customer-edit-modal-title"
        >

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-4 sm:px-6">
                <div>
                    <h2 id="customer-edit-modal-title" class="text-lg text-[#362727] kohan-bold">
                        ویرایش اطلاعات مشتری
                    </h2>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        اطلاعات مشتری را ویرایش و ذخیره کنید.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeCustomerEditModal()"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl text-[#9A8E89] transition hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                    aria-label="بستن"
                >
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form
                method="POST"
                action="{{ route('admin.customers.update', $customer) }}"
                class="space-y-5 p-5 sm:p-6"
            >
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="edit-customer-type" class="text-sm text-[#5F514D]">
                        نوع مشتری
                        <span class="text-rose-600">*</span>
                    </label>

                    <select
                        id="edit-customer-type"
                        name="type"
                        required
                        class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                        <option value="person" @selected(old('type', $customer->type) === 'person')>شخص</option>
                        <option value="company" @selected(old('type', $customer->type) === 'company')>شرکت</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="edit-customer-name" class="text-sm text-[#5F514D]">
                        نام و نام خانوادگی / نام شرکت
                        <span class="text-rose-600">*</span>
                    </label>

                    <input
                        id="edit-customer-name"
                        name="name"
                        type="text"
                        maxlength="200"
                        required
                        value="{{ old('name', $customer->name) }}"
                        placeholder="نام مشتری یا شرکت"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                    >
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="space-y-2">
                        <label for="edit-customer-phone" class="text-sm text-[#5F514D]">شماره تلفن</label>

                        <input
                            id="edit-customer-phone"
                            name="phone"
                            type="tel"
                            maxlength="16"
                            dir="ltr"
                            value="{{ old('phone', $customer->phone) }}"
                            placeholder="09xxxxxxxxx"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-left text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="edit-customer-national-id" id="edit-customer-national-id-label" class="text-sm text-[#5F514D]">
                            شناسه
                        </label>

                        <input
                            id="edit-customer-national-id"
                            name="national_id"
                            type="text"
                            maxlength="20"
                            dir="ltr"
                            value="{{ old('national_id', $customer->national_id) }}"
                            placeholder="کد ملی شخص یا شناسه شرکت"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-left text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                        >
                    </div>

                </div>

                <div class="space-y-2">
                    <label for="edit-customer-description" class="text-sm text-[#5F514D]">توضیحات</label>

                    <textarea
                        id="edit-customer-description"
                        name="description"
                        rows="3"
                        maxlength="5000"
                        placeholder="توضیحات تکمیلی درباره مشتری (اختیاری)"
                        class="w-full resize-y rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                    >{{ old('description', $customer->description) }}</textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-[#EEE8E5] pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="closeCustomerEditModal()"
                        class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] transition hover:bg-[#FAF8F7] hover:text-[#542b2e] kohan-bold"
                    >
                        انصراف
                    </button>

                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-[#542b2e] px-6 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                    >
                        ذخیره تغییرات
                    </button>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const editModal = document.getElementById('customer-edit-modal');
                const typeInput = document.getElementById('edit-customer-type');
                const nationalIdLabel = document.getElementById('edit-customer-national-id-label');
                const nationalIdInput = document.getElementById('edit-customer-national-id');

                const reportModal = document.getElementById('customer-report-modal');
                const reportButton = document.getElementById('print-customer-documents-report');

                window.openCustomerEditModal = () => {
                    if (!editModal) return;

                    editModal.classList.remove('hidden');
                    editModal.classList.add('flex');
                    editModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                };

                window.closeCustomerEditModal = () => {
                    if (!editModal) return;

                    editModal.classList.add('hidden');
                    editModal.classList.remove('flex');
                    editModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');
                };

                window.openCustomerReportModal = () => {
                    if (!reportModal) return;

                    reportModal.classList.remove('hidden');
                    reportModal.classList.add('flex');
                    reportModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                };

                window.closeCustomerReportModal = () => {
                    if (!reportModal) return;

                    reportModal.classList.add('hidden');
                    reportModal.classList.remove('flex');
                    reportModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');
                };

                const updateNationalIdLabel = () => {
                    if (!typeInput || !nationalIdLabel || !nationalIdInput) return;

                    const isCompany = typeInput.value === 'company';

                    nationalIdLabel.textContent = isCompany
                        ? 'شماره ثبت شرکت'
                        : 'کد ملی / شناسه';

                    nationalIdInput.placeholder = isCompany
                        ? 'شماره ثبت شرکت'
                        : 'کد ملی شخص';
                };

                if (typeInput) {
                    typeInput.addEventListener('change', updateNationalIdLabel);
                    updateNationalIdLabel();
                }

                if (editModal) {
                    editModal.addEventListener('click', (event) => {
                        if (event.target === editModal) {
                            window.closeCustomerEditModal();
                        }
                    });
                }

                if (reportModal) {
                    reportModal.addEventListener('click', (event) => {
                        if (event.target === reportModal) {
                            window.closeCustomerReportModal();
                        }
                    });
                }

                if (reportButton) {
                    reportButton.addEventListener('click', window.openCustomerReportModal);
                }

                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') return;

                    if (editModal && !editModal.classList.contains('hidden')) {
                        window.closeCustomerEditModal();
                    }

                    if (reportModal && !reportModal.classList.contains('hidden')) {
                        window.closeCustomerReportModal();
                    }
                });

                @if(session('report_error'))
                    window.openCustomerReportModal();
                @elseif($errors->any())
                    window.openCustomerEditModal();
                @endif
            })();
        </script>
    @endpush
@endsection