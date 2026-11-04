@extends('layouts.admin')

@section('content')
<div class="space-y-6" dir="rtl">

    {{-- Page Header --}}
    <div class="hero-animated-bg relative overflow-hidden rounded-3xl border border-white/10 p-6 shadow-2xl sm:p-8">
        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="hero-orb hero-orb-three"></div>
        <div class="hero-wave"></div>
        <div class="hero-ring hero-ring-one"></div>
        <div class="hero-ring hero-ring-two"></div>
        <div class="hero-grid"></div>
        <div class="hero-shine"></div>

        <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/70">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#baadad]"></span>
                    مدیریت اسناد مالی
                </span>

                <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                    فاکتورها و پیش‌فاکتورها
                </h1>

                <p class="mt-3 text-sm leading-7 text-white/60">
                    مدیریت اسناد فروش و پیش‌فاکتورهای مشتریان
                </p>
            </div>

            <a
                href="{{ route('admin.invoices.create') }}"
                class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#8d6666] px-5 py-3 text-sm text-white transition duration-300 hover:bg-[#a07878] hover:shadow-lg hover:shadow-black/20 kohan-bold"
            >
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                </svg>
                ثبت سند جدید
            </a>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="rounded-2xl border border-[#E8E1DE] bg-white p-4 shadow-sm sm:p-5">
        <form
            method="GET"
            action="{{ route('admin.invoices.index') }}"
            class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_200px_auto_auto]"
        >

            {{-- Search --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="جست‌وجوی نام مشتری یا شماره سند..."
                class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
            >

            {{-- Document Type --}}
            <select
                name="type"
                class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
            >
                <option value="">همه اسناد</option>
                <option value="invoice" @selected(request('type') === 'invoice')>فاکتور</option>
                <option value="proforma" @selected(request('type') === 'proforma')>پیش‌فاکتور</option>
            </select>

            {{-- Search Button --}}
            <button
                type="submit"
                class="cursor-pointer rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
            >
                جست‌وجو
            </button>

            {{-- Clear Filters --}}
            <a
                href="{{ route('admin.invoices.index') }}"
                class="flex cursor-pointer items-center justify-center rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] transition hover:border-[#d6c5c2] hover:bg-[#FAF8F7] hover:text-[#542b2e]"
            >
                پاک کردن
            </a>

        </form>
    </div>

    {{-- Invoices Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-right text-sm">

                {{-- Table Header --}}
                <thead class="border-b border-[#EEE8E5] bg-[#FAF8F7] text-[#827875]">
                    <tr>
                        <th class="px-5 py-4 font-medium">ردیف</th>
                        <th class="px-5 py-4 font-medium">شماره سند</th>
                        <th class="px-5 py-4 font-medium">مشتری</th>
                        <th class="px-5 py-4 font-medium">نوع سند</th>
                        <th class="px-5 py-4 font-medium">تاریخ صدور</th>
                        <th class="px-5 py-4 font-medium">وضعیت</th>
                        <th class="px-5 py-4 font-medium">عملیات</th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="divide-y divide-[#F0EBE8] text-[#5F514D]">
                    @forelse ($invoices as $invoice)
                        <tr class="transition hover:bg-[#FAF8F7]">

                            {{-- Row Number --}}
                            <td class="px-5 py-4 text-[#A99D98]">
                                {{ $invoices->firstItem() + $loop->index }}
                            </td>

                            {{-- Document Number --}}
                            <td class="px-5 py-4 text-[#362727] kohan-bold" dir="ltr">
                                {{ $invoice->document_number }}
                            </td>

                            {{-- Customer --}}
                            <td class="px-5 py-4">
                                {{ $invoice->customer->name }}
                            </td>

                            {{-- Document Type --}}
                            <td class="px-5 py-4">
                                {{ $invoice->type === 'invoice' ? 'فاکتور' : 'پیش‌فاکتور' }}
                            </td>

                            {{-- Issue Date --}}
                            <td class="px-5 py-4">
                                {{ $invoice->issue_date?->format('Y/m/d') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if ($invoice->status === 'voided')
                                    <span class="inline-flex rounded-lg bg-rose-50 px-3 py-1.5 text-xs text-rose-700">
                                        باطل شده
                                    </span>
                                @elseif ($invoice->isExpired())
                                    <span class="inline-flex rounded-lg bg-amber-50 px-3 py-1.5 text-xs text-amber-700">
                                        منقضی
                                    </span>
                                @else
                                    <span class="inline-flex rounded-lg bg-emerald-50 px-3 py-1.5 text-xs text-emerald-700">
                                        معتبر
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <a
                                    href="{{ route('admin.invoices.show', $invoice) }}"
                                    class="cursor-pointer text-sm text-[#674040] transition hover:text-[#542b2e] kohan-bold"
                                >
                                    مشاهده جزئیات
                                </a>
                            </td>

                        </tr>
                    @empty
                        {{-- Empty State --}}
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="text-3xl text-[#C4B9B4]">—</div>

                                <p class="mt-3 text-[#5F514D] kohan-bold">
                                    هنوز سندی ثبت نشده است
                                </p>

                                <p class="mt-2 text-xs text-[#A99D98]">
                                    برای شروع، سند جدید ثبت کنید.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($invoices->hasPages())
            <div class="border-t border-[#EEE8E5] px-5 py-4">
                {{ $invoices->links() }}
            </div>
        @endif

    </div>

</div>
@endsection