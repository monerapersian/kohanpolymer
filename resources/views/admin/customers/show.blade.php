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
                    <p class="mt-2 text-sm text-[#362727] kohan-bold">
                        {{ $customer->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">نوع مشتری</p>
                    <p class="mt-2 text-sm text-[#5F514D]">
                        {{ $customer->type === 'company' ? 'شخص حقوقی' : 'شخص حقیقی' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">شماره تلفن</p>
                    <p class="mt-2 text-sm text-[#5F514D]" dir="ltr">
                        {{ $customer->phone ?: '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">
                        {{ $customer->type === 'company' ? 'شماره ثبت شرکت' : 'کد ملی / شناسه' }}
                    </p>
                    <p class="mt-2 text-sm text-[#5F514D]">
                        {{ $customer->national_id ?: '—' }}
                    </p>
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

            @if($documents->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#8d6666]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke-linejoin="round"/>
                            <path d="M14 3v5h5M9 13h6m-6 4h6" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-[#362727] kohan-bold">
                        هنوز سندی برای این مشتری ثبت نشده است
                    </h3>

                    <p class="mt-2 text-sm text-[#9A8E89]">
                        پس از صدور فاکتور یا پیش‌فاکتور، سوابق آن در این بخش نمایش داده می‌شود.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-right">

                        <thead class="bg-[#FAF8F7] text-xs text-[#827875]">
                            <tr>
                                <th class="px-5 py-4 font-medium">ردیف</th>
                                <th class="px-5 py-4 font-medium">شماره سند</th>
                                <th class="px-5 py-4 font-medium">نوع سند</th>
                                <th class="px-5 py-4 font-medium">تاریخ صدور</th>
                                <th class="px-5 py-4 text-center font-medium">مشاهده</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#F0EBE8]">
                            @foreach($documents as $document)
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
                                            <span class="inline-flex rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs text-amber-700">
                                                پیش‌فاکتور
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-700">
                                                فاکتور
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-sm text-[#5F514D]">
                                        @if($document->issue_date)
                                            {{ \App\Support\NumberHelper::toPersian(\App\Support\DateHelper::toPersian($document->issue_date, 'Y/m/d')) }}
                                        @else
                                            —
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
                        <label for="edit-customer-phone" class="text-sm text-[#5F514D]">
                            شماره تلفن
                        </label>

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
                    <label for="edit-customer-description" class="text-sm text-[#5F514D]">
                        توضیحات
                    </label>

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
                const modal = document.getElementById('customer-edit-modal');
                const typeInput = document.getElementById('edit-customer-type');
                const nationalIdLabel = document.getElementById('edit-customer-national-id-label');
                const nationalIdInput = document.getElementById('edit-customer-national-id');

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

                    nationalIdLabel.textContent = isCompany
                        ? 'شماره ثبت شرکت'
                        : 'کد ملی / شناسه';
                    
                    nationalIdInput.placeholder = isCompany
                        ? 'شماره ثبت شرکت'
                        : 'کد ملی شخص';
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

                updateNationalIdLabel();

                @if($errors->any())
                    window.openCustomerEditModal();
                @endif
            })();
        </script>
    @endpush
@endsection