@extends('layouts.admin')

@section('title', 'جزئیات مشتری | کهن پلیمر البرز')

@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <a href="{{ route('admin.customers.index') }}"
           class="inline-flex items-center gap-2 text-sm text-white/55 transition hover:text-white">
            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            بازگشت به فهرست مشتریان
        </a>

        {{-- Header --}}
        <section class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/[0.04] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#681b21]/25 text-[#e8a9ae]">
                    @if($customer->type === 'company')
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h2v4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2" stroke-linecap="round"/>
                        </svg>
                    @endif
                </div>

                <div class="min-w-0">
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-black text-white sm:text-2xl">{{ $customer->name }}</h1>

                        @if($customer->type === 'company')
                            <span class="rounded-lg bg-rose-400/10 px-2.5 py-1 text-xs text-rose-300">شخص حقوقی</span>
                        @else
                            <span class="rounded-lg bg-emerald-400/10 px-2.5 py-1 text-xs text-emerald-300">شخص حقیقی</span>
                        @endif
                    </div>
                    <p class="text-sm text-white/45">
                        شناسه مشتری: {{ $customer->id }}
                    </p>
                </div>
            </div>

            <button
                type="button"
                onclick="openCustomerEditModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-white/80 transition hover:border-[#681b21] hover:bg-[#681b21]/15 hover:text-white"
            >
                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                    <path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                ویرایش اطلاعات
            </button>
        </section>

        {{-- Customer Information --}}
        <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="border-b border-white/10 px-5 py-4 sm:px-6">
                <h2 class="font-bold text-white">اطلاعات مشتری</h2>
                <p class="mt-1 text-xs text-white/45">اطلاعات ثبت‌شده در پرونده مشتری</p>
            </div>

            <div class="grid grid-cols-1 gap-px bg-white/[0.06] sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-[#222222] p-5">
                    <p class="text-xs text-white/45">نام / نام شرکت</p>
                    <p class="mt-2 break-words text-sm font-semibold text-white">{{ $customer->name }}</p>
                </div>

                <div class="bg-[#222222] p-5">
                    <p class="text-xs text-white/45">شماره تلفن</p>
                    <p class="mt-2 text-sm font-semibold text-white" dir="ltr">
                        {{ $customer->phone ?: 'ثبت نشده' }}
                    </p>
                </div>

                <div class="bg-[#222222] p-5">
                    <p class="text-xs text-white/45">
                        {{ $customer->type === 'company' ? 'شماره ثبت شرکت' : 'کد ملی' }}
                    </p>
                    <p class="mt-2 text-sm font-semibold text-white">
                        {{ $customer->national_id ?: 'ثبت نشده' }}
                    </p>
                </div>

                <div class="bg-[#222222] p-5 sm:col-span-2 lg:col-span-3">
                    <p class="text-xs text-white/45">توضیحات</p>
                    <p class="mt-2 whitespace-pre-line break-words text-sm leading-7 text-white/75">{{ $customer->description ?: 'توضیحاتی برای این مشتری ثبت نشده است.' }}</p>
                </div>

                <div class="bg-[#222222] p-5">
                    <p class="text-xs text-white/45">تاریخ ثبت</p>
                    <p class="mt-2 text-sm font-semibold text-white">
                        {{ $customer->created_at?->format('Y/m/d') ?? '—' }}
                    </p>
                </div>

                <div class="bg-[#222222] p-5">
                    <p class="text-xs text-white/45">آخرین به‌روزرسانی</p>
                    <p class="mt-2 text-sm font-semibold text-white">
                        {{ $customer->updated_at?->format('Y/m/d') ?? '—' }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Invoices --}}
        <!-- <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-bold text-white">فاکتورها</h2>
                    <p class="mt-1 text-xs text-white/45">فاکتورهای صادرشده برای این مشتری</p>
                </div>
                <span class="rounded-lg bg-white/5 px-3 py-1.5 text-xs text-white/60">
                    {{ isset($customer->invoices) ? number_format($customer->invoices->count()) : '۰' }} فاکتور
                </span>
            </div>

            @if(isset($customer->invoices) && $customer->invoices->isNotEmpty())
                <div class="divide-y divide-white/[0.06]">
                    @foreach($customer->invoices as $invoice)
                        <a href="{{ route('admin.invoices.show', $invoice) }}"
                           class="flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-white/[0.035] sm:px-6">
                            <div>
                                <p class="text-sm font-semibold text-white">
                                    فاکتور شماره {{ $invoice->id }}
                                </p>
                                <p class="mt-1 text-xs text-white/45">
                                    {{ $invoice->created_at?->format('Y/m/d') ?? '—' }}
                                </p>
                            </div>
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 shrink-0 text-white/40" stroke="currentColor" stroke-width="1.8">
                                <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-white/65">هنوز فاکتوری برای این مشتری ثبت نشده است.</p>
                    <p class="mt-2 text-xs text-white/35">پس از راه‌اندازی بخش فاکتورها، فهرست آن‌ها در این قسمت نمایش داده می‌شود.</p>
                </div>
            @endif
        </section> -->
        {{-- Invoices --}}
        <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-bold text-white">فاکتورها</h2>
                    <p class="mt-1 text-xs text-white/45">فاکتورهای صادرشده برای این مشتری</p>
                </div>
                <span class="rounded-lg bg-white/5 px-3 py-1.5 text-xs text-white/60">
                    ۰ فاکتور
                </span>
            </div>

            <div class="px-5 py-10 text-center">
                <p class="text-sm text-white/65">هنوز فاکتوری برای این مشتری ثبت نشده است.</p>
                <p class="mt-2 text-xs text-white/35">پس از راه‌اندازی بخش فاکتورها، فهرست آن‌ها در این قسمت نمایش داده می‌شود.</p>
            </div>
        </section>

        {{-- Proformas --}}
        <!-- <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-bold text-white">پیش‌فاکتورها</h2>
                    <p class="mt-1 text-xs text-white/45">پیش‌فاکتورهای صادرشده برای این مشتری</p>
                </div>
                <span class="rounded-lg bg-white/5 px-3 py-1.5 text-xs text-white/60">
                    {{ isset($customer->proformas) ? number_format($customer->proformas->count()) : '۰' }} پیش‌فاکتور
                </span>
            </div>

            @if(isset($customer->proformas) && $customer->proformas->isNotEmpty())
                <div class="divide-y divide-white/[0.06]">
                    @foreach($customer->proformas as $proforma)
                        <a href="{{ route('admin.proformas.show', $proforma) }}"
                           class="flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-white/[0.035] sm:px-6">
                            <div>
                                <p class="text-sm font-semibold text-white">
                                    پیش‌فاکتور شماره {{ $proforma->id }}
                                </p>
                                <p class="mt-1 text-xs text-white/45">
                                    {{ $proforma->created_at?->format('Y/m/d') ?? '—' }}
                                </p>
                            </div>
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 shrink-0 text-white/40" stroke="currentColor" stroke-width="1.8">
                                <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-white/65">هنوز پیش‌فاکتوری برای این مشتری ثبت نشده است.</p>
                    <p class="mt-2 text-xs text-white/35">پس از راه‌اندازی بخش پیش‌فاکتورها، فهرست آن‌ها در این قسمت نمایش داده می‌شود.</p>
                </div>
            @endif
        </section> -->
        {{-- Proformas --}}
        <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-bold text-white">پیش‌فاکتورها</h2>
                    <p class="mt-1 text-xs text-white/45">پیش‌فاکتورهای صادرشده برای این مشتری</p>
                </div>
                <span class="rounded-lg bg-white/5 px-3 py-1.5 text-xs text-white/60">
                    ۰ پیش‌فاکتور
                </span>
            </div>

            <div class="px-5 py-10 text-center">
                <p class="text-sm text-white/65">هنوز پیش‌فاکتوری برای این مشتری ثبت نشده است.</p>
                <p class="mt-2 text-xs text-white/35">پس از راه‌اندازی بخش پیش‌فاکتورها، فهرست آن‌ها در این قسمت نمایش داده می‌شود.</p>
            </div>
        </section>
        
    </div>

    {{-- Edit Customer Modal --}}
    <div id="customer-edit-modal"
         class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
         aria-hidden="true">
        <div class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
             role="dialog" aria-modal="true" aria-labelledby="customer-edit-title">

            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 id="customer-edit-title" class="text-lg font-black text-white">ویرایش مشتری</h2>
                    <p class="mt-1 text-xs text-white/45">اطلاعات مشتری را به‌روزرسانی کنید.</p>
                </div>

                <button type="button" onclick="closeCustomerEditModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-white/55 transition hover:bg-white/10 hover:text-white"
                        aria-label="بستن">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-5 p-5 sm:p-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="detail-customer-type" class="text-sm font-medium text-white/75">نوع مشتری <span class="text-rose-300">*</span></label>
                    <select id="detail-customer-type" name="type" required
                            class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21]">
                        <option value="person" @selected($customer->type === 'person')>شخص حقیقی</option>
                        <option value="company" @selected($customer->type === 'company')>شخص حقوقی / شرکت</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="detail-customer-name" class="text-sm font-medium text-white/75">نام و نام خانوادگی / نام شرکت <span class="text-rose-300">*</span></label>
                    <input id="detail-customer-name" name="name" type="text" maxlength="200" required
                           value="{{ $customer->name }}"
                           class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21]">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="detail-customer-phone" class="text-sm font-medium text-white/75">شماره تلفن</label>
                        <input id="detail-customer-phone" name="phone" type="tel" maxlength="16" dir="ltr"
                               value="{{ $customer->phone }}"
                               class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-left text-sm text-white outline-none focus:border-[#681b21]">
                    </div>

                    <div class="space-y-2">
                        <label id="detail-customer-national-id-label" for="detail-customer-national-id" class="text-sm font-medium text-white/75">
                            {{ $customer->type === 'company' ? 'شماره ثبت شرکت' : 'کد ملی' }}
                        </label>
                        <input id="detail-customer-national-id" name="national_id" type="text" maxlength="20" dir="ltr"
                               value="{{ $customer->national_id }}"
                               class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-left text-sm text-white outline-none focus:border-[#681b21]">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="detail-customer-description" class="text-sm font-medium text-white/75">توضیحات</label>
                    <textarea id="detail-customer-description" name="description" rows="3" maxlength="5000"
                              class="w-full resize-y rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21]">{{ $customer->description }}</textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" onclick="closeCustomerEditModal()"
                            class="rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-white/65 transition hover:bg-white/5">
                        انصراف
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-[#681b21] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#7d252c]">
                        ذخیره تغییرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        (() => {
            const modal = document.getElementById('customer-edit-modal');
            const typeInput = document.getElementById('detail-customer-type');
            const nationalIdLabel = document.getElementById('detail-customer-national-id-label');
            const nationalIdInput = document.getElementById('detail-customer-national-id');

            window.openCustomerEditModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            window.closeCustomerEditModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            const updateNationalIdLabel = () => {
                const isCompany = typeInput.value === 'company';
                nationalIdLabel.textContent = isCompany ? 'شماره ثبت شرکت' : 'کد ملی';
                nationalIdInput.placeholder = isCompany ? 'شماره ثبت شرکت' : 'کد ملی';
            };

            typeInput.addEventListener('change', updateNationalIdLabel);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    window.closeCustomerEditModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    window.closeCustomerEditModal();
                }
            });
        })();
    </script>
    @endpush
@endsection