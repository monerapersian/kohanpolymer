@extends('layouts.admin')

@section('content')
<div class="space-y-6" dir="rtl">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">فاکتورها و پیش‌فاکتورها</h1>
            <p class="mt-2 text-sm text-gray-400">مدیریت اسناد فروش و پیش‌فاکتورهای مشتریان</p>
        </div>

        <a href="{{ route('admin.invoices.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#81272e]">
            <span class="text-lg">+</span>
            ثبت سند جدید
        </a>
    </div>

    <div class="rounded-2xl border border-white/10 bg-[#222222] p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.invoices.index') }}"
              class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_200px_auto_auto]">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="جست‌وجوی نام مشتری یا شماره سند..."
                class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-[#681b21] focus:outline-none"
            >

            <select
                name="type"
                class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white focus:outline-none"
            >
                <option value="">همه اسناد</option>
                <option value="invoice" @selected(request('type') === 'invoice')>فاکتور</option>
                <option value="proforma" @selected(request('type') === 'proforma')>پیش‌فاکتور</option>
            </select>

            <button type="submit"
                    class="rounded-xl bg-white/10 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                جست‌وجو
            </button>

            <a href="{{ route('admin.invoices.index') }}"
               class="rounded-xl border border-white/10 px-5 py-3 text-center text-sm text-gray-300 transition hover:bg-white/5">
                پاک کردن
            </a>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-right text-sm">
                <thead class="border-b border-white/10 bg-white/[0.03] text-gray-400">
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

                <tbody class="divide-y divide-white/5 text-gray-200">
                    @forelse ($invoices as $invoice)
                        <tr class="transition hover:bg-white/[0.025]">
                            <td class="px-5 py-4 text-gray-400">
                                {{ $invoices->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-white kohan-bold" dir="ltr">
                                {{ $invoice->document_number }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $invoice->customer->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $invoice->type === 'invoice' ? 'فاکتور' : 'پیش‌فاکتور' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $invoice->issue_date?->format('Y/m/d') }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($invoice->status === 'voided')
                                    <span class="rounded-lg bg-red-500/10 px-3 py-1.5 text-xs text-red-300">
                                        باطل شده
                                    </span>
                                @elseif ($invoice->isExpired())
                                    <span class="rounded-lg bg-amber-500/10 px-3 py-1.5 text-xs text-amber-300">
                                        منقضی
                                    </span>
                                @else
                                    <span class="rounded-lg bg-emerald-500/10 px-3 py-1.5 text-xs text-emerald-300">
                                        معتبر
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                   class="text-sm font-semibold text-gray-300 transition hover:text-white">
                                    مشاهده جزئیات
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="text-3xl text-gray-600">—</div>
                                <p class="mt-3 font-semibold text-gray-300">هنوز سندی ثبت نشده است</p>
                                <p class="mt-2 text-xs text-gray-500">برای شروع، سند جدید ثبت کنید.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="border-t border-white/10 px-5 py-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection