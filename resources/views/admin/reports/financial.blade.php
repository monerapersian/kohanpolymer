@extends('layouts.admin')

@section('content')

@php
    $financialStats = [
        'sales' => '۲,۴۸۰,۰۰۰,۰۰۰',
        'expenses' => '۳۸۵,۰۰۰,۰۰۰',
        'income' => '۲,۶۵۰,۰۰۰,۰۰۰',
    ];

    $financialPeriods = [
        ['key' => 'current', 'label' => 'ماه جاری'],
        ['key' => 'previous', 'label' => 'ماه گذشته'],
        ['key' => 'three-months', 'label' => 'سه ماه گذشته'],
        ['key' => 'six-months', 'label' => 'شش ماه گذشته'],
        ['key' => 'year', 'label' => 'یک سال گذشته'],
    ];
@endphp

<div id="financial-report-page" class="space-y-8">

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
                <div class="mb-4 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-[#baadad]"></span>
                    <span class="text-xs text-white/60">مدیریت و بررسی وضعیت مالی</span>
                </div>

                <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                    گزارش مالی
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                    بررسی درآمدها، هزینه‌ها و عملکرد مالی مجموعه در بازه‌های زمانی مختلف.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/10">
                    <svg class="h-7 w-7 text-[#baadad]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3v18h18M7 14l4-4 4 4 6-7" />
                    </svg>
                </div>
            </div>

        </div>
    </div>


    {{-- Income Calculation Setting --}}
    <section class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M12 8c-2.2 0-4 1-4 2.5S9.8 13 12 13s4 1 4 2.5S14.2 18 12 18m0-10V6m0 2v10m0 0v2m9-8a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-sm kohan-bold text-[#362727]">
                        تنظیم محاسبه درآمد
                    </h2>

                    <p class="mt-1 text-xs leading-6 text-[#9A8E89]">
                        تعیین کنید درآمد حاصل از کدام فاکتورها در گزارش مالی محاسبه شود.
                    </p>
                </div>
            </div>

            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3">
                <input
                    type="checkbox"
                    id="include-unofficial-invoices"
                    class="h-4 w-4 cursor-pointer accent-[#674040]"
                >

                <span class="text-xs text-[#5F514D]">
                    محاسبه درآمد با احتساب همه فاکتورهای رسمی و غیررسمی
                </span>
            </label>

        </div>
    </section>


    {{-- Action Buttons --}}
    <section class="flex flex-col gap-3 sm:flex-row sm:justify-end">

        <button
            type="button"
            id="open-income-modal"
            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#674040] px-5 py-3 text-sm text-white transition hover:bg-[#362727]"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v14m-7-7h14" />
            </svg>
            ثبت درآمد جدید
        </button>

        <button
            type="button"
            id="open-expense-modal"
            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#E8E1DE] bg-white px-5 py-3 text-sm text-[#674040] transition hover:bg-[#F3E9E9]"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v14m-7-7h14" />
            </svg>
            ثبت هزینه جدید
        </button>

    </section>


    {{-- Period Filters --}}
    <section class="space-y-4">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base kohan-bold text-[#362727]">
                    خلاصه وضعیت مالی
                </h2>
                <p class="mt-1 text-xs text-[#9A8E89]">
                    آمار مالی بر اساس بازه زمانی انتخاب‌شده
                </p>
            </div>
        </div>

        <div class="flex gap-2 overflow-x-auto pb-2" id="financial-periods">

            @foreach($financialPeriods as $index => $period)
                <button
                    type="button"
                    data-period="{{ $period['key'] }}"
                    class="period-button shrink-0 cursor-pointer rounded-xl border px-4 py-2.5 text-xs transition
                    {{ $index === 0
                        ? 'active-period border-[#674040] bg-[#674040] text-white'
                        : 'border-[#E8E1DE] bg-white text-[#827875] hover:border-[#8d6666]' }}"
                >
                    {{ $period['label'] }}
                </button>
            @endforeach

        </div>

    </section>


    {{-- Statistics Cards --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Sales --}}
        <div class="relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">

            <div class="absolute -left-8 -top-8 h-28 w-28 rounded-full bg-blue-50"></div>

            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">مجموع فروش</p>

                    <h3
                        id="sales-stat"
                        class="mt-4 text-xl kohan-bold text-[#362727] sm:text-2xl"
                        dir="rtl"
                    >
                        {{ $financialStats['sales'] }}
                    </h3>

                    <span class="mt-2 block text-xs text-[#9A8E89]">تومان</span>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="M3 3v18h18M7 14l4-4 4 4 6-7" />
                    </svg>
                </div>
            </div>

            <div class="relative mt-5 flex items-center gap-2 border-t border-[#F0EBE8] pt-4">
                <span class="text-xs text-[#9A8E89]">فروش حاصل از فاکتورها</span>
            </div>
        </div>


        {{-- Expenses --}}
        <div class="relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">

            <div class="absolute -left-8 -top-8 h-28 w-28 rounded-full bg-red-50"></div>

            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">مجموع هزینه‌ها</p>

                    <h3
                        id="expenses-stat"
                        class="mt-4 text-xl kohan-bold text-[#362727] sm:text-2xl"
                        dir="rtl"
                    >
                        {{ $financialStats['expenses'] }}
                    </h3>

                    <span class="mt-2 block text-xs text-[#9A8E89]">تومان</span>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M12 9v4m0 4h.01M5.2 19h13.6c1.5 0 2.4-1.6 1.6-2.9L13.6 4.3c-.7-1.3-2.5-1.3-3.2 0L3.6 16.1c-.8 1.3.1 2.9 1.6 2.9Z" />
                    </svg>
                </div>
            </div>

            <div class="relative mt-5 flex items-center gap-2 border-t border-[#F0EBE8] pt-4">
                <span class="text-xs text-[#9A8E89]">هزینه‌های ثبت‌شده مجموعه</span>
            </div>
        </div>


        {{-- Total Income --}}
        <div class="relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:col-span-2 xl:col-span-1">

            <div class="absolute -left-8 -top-8 h-28 w-28 rounded-full bg-emerald-50"></div>

            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">مجموع درآمد</p>

                    <h3
                        id="income-stat"
                        class="mt-4 text-xl kohan-bold text-[#362727] sm:text-2xl"
                        dir="rtl"
                    >
                        {{ $financialStats['income'] }}
                    </h3>

                    <span class="mt-2 block text-xs text-[#9A8E89]">تومان</span>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M12 3v18m9-9H3m18 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>

            <div class="relative mt-5 flex items-center gap-2 border-t border-[#F0EBE8] pt-4">
                <span class="text-xs text-[#9A8E89]">فاکتورها به‌علاوه درآمدهای جانبی</span>
            </div>
        </div>

    </section>


    {{-- Financial Chart --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="border-b border-[#F0EBE8] p-5 sm:p-6">

            <div class="flex flex-col gap-5">

                <div>
                    <h2 class="text-base kohan-bold text-[#362727]">
                        نمودار تحلیل مالی
                    </h2>

                    <p class="mt-1 text-xs leading-6 text-[#9A8E89]">
                        مقایسه درآمدهای فاکتوری، درآمدهای جانبی و هزینه‌ها
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div>
                        <label for="chart-start-date" class="mb-2 block text-xs text-[#827875]">
                            از تاریخ
                        </label>

                        <input
                            type="date"
                            id="chart-start-date"
                            class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-3 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                        >
                    </div>

                    <div>
                        <label for="chart-end-date" class="mb-2 block text-xs text-[#827875]">
                            تا تاریخ
                        </label>

                        <input
                            type="date"
                            id="chart-end-date"
                            class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-3 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                        >
                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-x-5 gap-y-3">

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#674040]"></span>
                        <span class="text-xs text-[#827875]">فاکتور رسمی</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#8d6666]"></span>
                        <span class="text-xs text-[#827875]">فاکتور غیررسمی</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#baadad]"></span>
                        <span class="text-xs text-[#827875]">درآمد جانبی</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                        <span class="text-xs text-[#827875]">هزینه‌ها</span>
                    </div>

                </div>

            </div>

        </div>


        <div class="p-4 sm:p-6">

            <div class="hidden min-h-[320px] md:block">
                <canvas id="financial-line-chart"></canvas>
            </div>

            <div class="mx-auto max-w-sm py-5 md:hidden">
                <div class="h-[280px]">
                    <canvas id="financial-doughnut-chart"></canvas>
                </div>
            </div>

        </div>

    </section>

</div>


{{-- Income Modal --}}
<div
    id="income-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
    aria-hidden="true"
>
    <div class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-white/10 bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-[#F0EBE8] p-5 sm:p-6">
            <div>
                <h3 class="text-base kohan-bold text-[#362727]">ثبت درآمد جدید</h3>
                <p class="mt-1 text-xs text-[#9A8E89]">ثبت درآمدهای خارج از فاکتورهای فروش</p>
            </div>

            <button type="button" data-close-modal="income-modal" class="cursor-pointer rounded-lg p-2 text-[#827875] transition hover:bg-[#F3E9E9]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <form id="income-form" class="space-y-5 p-5 sm:p-6">

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">عنوان درآمد</label>
                <input type="text" name="title" placeholder="عنوان درآمد را وارد کنید"
                    class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs text-[#5F514D]">مبلغ (ریال)</label>
                    <input type="number" name="amount" min="0" placeholder="مثلاً ۵۰۰۰۰۰۰"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
                </div>

                <div>
                    <label class="mb-2 block text-xs text-[#5F514D]">تاریخ درآمد</label>
                    <input type="date" name="date"
                        class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">تصویر سند <span class="text-[#9A8E89]">(اختیاری)</span></label>
                <input type="file" name="document" accept="image/*"
                    class="block w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-3 text-xs text-[#827875]">
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">توضیحات</label>
                <textarea name="description" rows="3" placeholder="توضیحات تکمیلی..."
                    class="w-full resize-none rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]"></textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#F0EBE8] pt-5 sm:flex-row sm:justify-end">
                <button type="button" data-close-modal="income-modal"
                    class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] hover:bg-[#FAF8F7]">
                    انصراف
                </button>

                <button type="submit"
                    class="cursor-pointer rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#362727]">
                    ثبت درآمد
                </button>
            </div>

        </form>
    </div>
</div>


{{-- Expense Modal --}}
<div
    id="expense-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
    aria-hidden="true"
>
    <div class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-white/10 bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-[#F0EBE8] p-5 sm:p-6">
            <div>
                <h3 class="text-base kohan-bold text-[#362727]">ثبت هزینه جدید</h3>
                <p class="mt-1 text-xs text-[#9A8E89]">ثبت هزینه‌های مجموعه</p>
            </div>

            <button type="button" data-close-modal="expense-modal" class="cursor-pointer rounded-lg p-2 text-[#827875] transition hover:bg-[#F3E9E9]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <form id="expense-form" class="space-y-5 p-5 sm:p-6">

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">عنوان هزینه</label>
                <input type="text" name="title" placeholder="عنوان هزینه را وارد کنید"
                    class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs text-[#5F514D]">مبلغ (ریال)</label>
                    <input type="number" name="amount" min="0" placeholder="مثلاً ۳۰۰۰۰۰۰"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
                </div>

                <div>
                    <label class="mb-2 block text-xs text-[#5F514D]">تاریخ هزینه</label>
                    <input type="date" name="date"
                        class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]" required>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">تصویر سند <span class="text-[#9A8E89]">(اختیاری)</span></label>
                <input type="file" name="document" accept="image/*"
                    class="block w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-3 text-xs text-[#827875]">
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#5F514D]">توضیحات</label>
                <textarea name="description" rows="3" placeholder="توضیحات تکمیلی..."
                    class="w-full resize-none rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm outline-none focus:border-[#8d6666]"></textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#F0EBE8] pt-5 sm:flex-row sm:justify-end">
                <button type="button" data-close-modal="expense-modal"
                    class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] hover:bg-[#FAF8F7]">
                    انصراف
                </button>

                <button type="submit"
                    class="cursor-pointer rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#362727]">
                    ثبت هزینه
                </button>
            </div>

        </form>
    </div>
</div>


{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (() => {
        const page = document.getElementById('financial-report-page');

        if (!page) return;

        // Modal handling
        const openModal = (id) => {
            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = (id) => {
            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        document.getElementById('open-income-modal')?.addEventListener('click', () => {
            openModal('income-modal');
        });

        document.getElementById('open-expense-modal')?.addEventListener('click', () => {
            openModal('expense-modal');
        });

        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                closeModal(button.dataset.closeModal);
            });
        });

        document.querySelectorAll('#income-modal, #expense-modal').forEach(modal => {
            modal.addEventListener('click', event => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                document.querySelectorAll('#income-modal, #expense-modal').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modal.id);
                    }
                });
            }
        });

        // Demo form handling
        document.querySelectorAll('#income-form, #expense-form').forEach(form => {
            form.addEventListener('submit', event => {
                event.preventDefault();

                alert('این فرم فعلاً نمایشی است و ذخیره اطلاعات در مرحله بعد پیاده‌سازی می‌شود.');

                form.reset();
                closeModal(form.id === 'income-form' ? 'income-modal' : 'expense-modal');
            });
        });

        // Period filter visual state
        const periodButtons = document.querySelectorAll('.period-button');

        periodButtons.forEach(button => {
            button.addEventListener('click', () => {
                periodButtons.forEach(item => {
                    item.classList.remove('active-period', 'border-[#674040]', 'bg-[#674040]', 'text-white');
                    item.classList.add('border-[#E8E1DE]', 'bg-white', 'text-[#827875]');
                });

                button.classList.remove('border-[#E8E1DE]', 'bg-white', 'text-[#827875]');
                button.classList.add('active-period', 'border-[#674040]', 'bg-[#674040]', 'text-white');
            });
        });

        // Demo chart data
        const chartLabels = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'];

        const chartData = {
            labels: chartLabels,
            datasets: [
                {
                    label: 'فاکتور رسمی',
                    data: [180, 220, 190, 280, 250, 320],
                    borderColor: '#674040',
                    backgroundColor: '#674040',
                    tension: 0.45,
                    borderWidth: 3,
                },
                {
                    label: 'فاکتور غیررسمی',
                    data: [90, 120, 100, 160, 130, 180],
                    borderColor: '#8d6666',
                    backgroundColor: '#8d6666',
                    tension: 0.45,
                    borderWidth: 3,
                },
                {
                    label: 'درآمد جانبی',
                    data: [35, 50, 40, 65, 55, 80],
                    borderColor: '#baadad',
                    backgroundColor: '#baadad',
                    tension: 0.45,
                    borderWidth: 3,
                },
                {
                    label: 'هزینه‌ها',
                    data: [45, 60, 55, 75, 65, 90],
                    borderColor: '#dc5656',
                    backgroundColor: '#dc5656',
                    tension: 0.45,
                    borderWidth: 3,
                }
            ]
        };

        let lineChart = null;
        let doughnutChart = null;

        const createCharts = () => {
            if (typeof Chart === 'undefined') return;

            const isMobile = window.matchMedia('(max-width: 767px)').matches;

            if (lineChart) {
                lineChart.destroy();
                lineChart = null;
            }

            if (doughnutChart) {
                doughnutChart.destroy();
                doughnutChart = null;
            }

            if (isMobile) {
                const canvas = document.getElementById('financial-doughnut-chart');

                if (!canvas) return;

                doughnutChart = new Chart(canvas, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            'فاکتور رسمی',
                            'فاکتور غیررسمی',
                            'درآمد جانبی',
                            'هزینه‌ها'
                        ],
                        datasets: [{
                            data: [320, 180, 80, 90],
                            backgroundColor: [
                                '#674040',
                                '#8d6666',
                                '#baadad',
                                '#dc5656'
                            ],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                rtl: true,
                                labels: {
                                    usePointStyle: true,
                                    padding: 18,
                                    font: {
                                        family: 'sans-serif',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });

            } else {
                const canvas = document.getElementById('financial-line-chart');

                if (!canvas) return;

                lineChart = new Chart(canvas, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F0EBE8'
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        };

        createCharts();

        let resizeTimer;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(createCharts, 200);
        });

        // Date filters are visual placeholders for now.
        const startDate = document.getElementById('chart-start-date');
        const endDate = document.getElementById('chart-end-date');

        endDate?.addEventListener('change', () => {
            if (startDate.value && endDate.value && endDate.value < startDate.value) {
                alert('تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد.');
                endDate.value = '';
            }
        });
    })();
</script>

@endsection