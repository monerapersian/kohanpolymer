@extends('layouts.admin')

@section('title', 'مدیریت مشتریان | کهن پلیمر البرز')

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
            <!-- <div class="hero-shine"></div> -->

            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/70">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#baadad]"></span>
                        مدیریت ارتباط با مشتریان
                    </span>

                    <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                        مشتریان
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                        مدیریت اطلاعات مشتریان و دسترسی به فاکتورها و پیش‌فاکتورها
                    </p>
                </div>

                <button
                    type="button"
                    onclick="openCustomerModal('create')"
                    class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#8d6666] px-5 py-3 text-sm text-white kohan-bold transition duration-300 hover:bg-[#a07878] hover:shadow-lg hover:shadow-black/20"
                >
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                    </svg>
                    مشتری جدید
                </button>
            </div>
        </div>

        {{-- Statistics --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Total Customers --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-[#827875]">کل مشتریان</p>

                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl text-[#362727] kohan-bold">
                        {{ number_format($totalCustomers) }}
                    </span>

                    <span class="rounded-xl bg-[#F3E9E9] p-3 text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Person Customers --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-[#827875]">شخص</p>

                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl text-[#362727] kohan-bold">
                        {{ number_format($personCount) }}
                    </span>

                    <span class="rounded-xl bg-emerald-50 p-3 text-emerald-700">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Company Customers --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-[#827875]">شرکت</p>

                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl text-[#362727] kohan-bold">
                        {{ number_format($companyCount) }}
                    </span>

                    <span class="rounded-xl bg-rose-50 p-3 text-rose-700">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h2v4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

        </section>

        {{-- Search and Filters --}}
        <section class="rounded-2xl border border-[#E8E1DE] bg-white p-4 shadow-sm sm:p-5">
            <form
                method="GET"
                action="{{ route('admin.customers.index') }}"
                class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_190px_auto]"
            >

                <div class="relative">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        class="absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A99D98]"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4" stroke-linecap="round"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جست‌وجوی نام، تلفن یا شناسه مشتری..."
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] py-3 pr-11 pl-4 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                    >
                </div>

                <select
                    name="type"
                    class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666]"
                >
                    <option value="">همه</option>
                    <option value="person" @selected(request('type') === 'person')>شخص</option>
                    <option value="company" @selected(request('type') === 'company')>شرکت</option>
                </select>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="flex-1 cursor-pointer rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#674040] sm:flex-none kohan-bold"
                    >
                        جست‌وجو
                    </button>

                    @if(request()->filled('search') || request()->filled('type'))
                        <a
                            href="{{ route('admin.customers.index') }}"
                            class="flex cursor-pointer items-center justify-center rounded-xl border border-[#E8E1DE] px-4 py-3 text-sm text-[#827875] transition hover:border-[#d6c5c2] hover:bg-[#FAF8F7] hover:text-[#542b2e]"
                        >
                            پاک‌کردن
                        </a>
                    @endif
                </div>

            </form>
        </section>

        {{-- Customers Table --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            {{-- Header --}}
            <div class="flex flex-col gap-2 border-b border-[#EEE8E5] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-[#362727] kohan-bold">فهرست مشتریان</h2>

                <span class="text-xs text-[#9A8E89]">
                    {{ number_format($customers->total()) }} مشتری
                </span>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mx-5 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mx-5 mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Empty State --}}
            @if($customers->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#8d6666]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <circle cx="9" cy="8" r="4"/>
                            <path d="M3 21v-2a6 6 0 0 1 12 0v2m3-10h4m-2-2v4" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-[#362727] kohan-bold">
                        هنوز مشتری‌ای ثبت نشده است
                    </h3>

                    <p class="mt-2 text-sm text-[#9A8E89]">
                        برای شروع، اولین مشتری را ثبت کنید.
                    </p>

                    <button
                        type="button"
                        onclick="openCustomerModal('create')"
                        class="mt-5 cursor-pointer rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                    >
                        ثبت مشتری جدید
                    </button>
                </div>

            @else

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-right">

                        <thead class="bg-[#FAF8F7] text-xs text-[#827875]">
                            <tr>
                                <th class="px-5 py-4 font-medium">ردیف</th>
                                <th class="px-5 py-4 font-medium">نام مشتری</th>
                                <th class="px-5 py-4 font-medium">نوع مشتری</th>
                                <th class="px-5 py-4 font-medium">تلفن</th>
                                <th class="px-5 py-4 font-medium">شناسه / کد ملی</th>
                                <th class="px-5 py-4 text-center font-medium">ویرایش</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#F0EBE8]">

                            @foreach($customers as $customer)
                                <tr
                                    class="cursor-pointer transition hover:bg-[#FAF8F7]"
                                    onclick="window.location.href='{{ route('admin.customers.show', $customer) }}'"
                                >
                                    {{-- Row Number --}}
                                    <td class="px-5 py-4 text-sm text-[#A99D98]">
                                        {{ $customers->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Customer Name --}}
                                    <td class="px-5 py-4">
                                        <span class="text-sm text-[#362727] kohan-bold">
                                            {{ $customer->name }}
                                        </span>
                                    </td>

                                    {{-- Customer Type --}}
                                    <td class="px-5 py-4">
                                        @if($customer->type === 'company')
                                            <span class="inline-flex rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs text-rose-700">
                                                شخص حقوقی
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs text-emerald-700">
                                                شخص حقیقی
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Phone --}}
                                    <td class="px-5 py-4 text-sm text-[#5F514D]" dir="ltr">
                                        {{ $customer->phone ?: '—' }}
                                    </td>

                                    {{-- National ID --}}
                                    <td class="px-5 py-4 text-sm text-[#5F514D]">
                                        {{ $customer->national_id ?: '—' }}
                                    </td>

                                    {{-- Edit --}}
                                    <td class="px-5 py-4 text-center">
                                        <button
                                            type="button"
                                            title="ویرایش مشتری"
                                            aria-label="ویرایش مشتری"
                                            onclick="event.stopPropagation(); openCustomerModal('edit', {{ Illuminate\Support\Js::from([
                                                'id' => $customer->id,
                                                'type' => $customer->type,
                                                'name' => $customer->name,
                                                'phone' => $customer->phone,
                                                'national_id' => $customer->national_id,
                                                'description' => $customer->description,
                                            ]) }});"
                                            class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-[#E8E1DE] text-[#827875] transition hover:border-[#8d6666] hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                                                <path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-t border-[#EEE8E5] px-5 py-4">
                    {{ $customers->links() }}
                </div>

            @endif

        </section>
        
    </div>

    {{-- Customer Modal --}}
    <div
        id="customer-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="customer-modal-title"
        >

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-4 sm:px-6">
                <div>
                    <h2 id="customer-modal-title" class="text-lg text-[#362727] kohan-bold">
                        مشتری جدید
                    </h2>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        اطلاعات مشتری را وارد کنید.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeCustomerModal()"
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
                id="customer-form"
                method="POST"
                action="{{ route('admin.customers.store') }}"
                class="space-y-5 p-5 sm:p-6"
            >
                @csrf

                <input
                    id="customer-form-method"
                    type="hidden"
                    name="_method"
                    value="POST"
                    disabled
                >

                {{-- Customer Type --}}
                <div class="space-y-2">
                    <label for="customer-type" class="text-sm text-[#5F514D]">
                        نوع مشتری
                        <span class="text-rose-600">*</span>
                    </label>

                    <select
                        id="customer-type"
                        name="type"
                        required
                        class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                        <option value="person">شخص</option>
                        <option value="company">شرکت</option>
                    </select>
                </div>

                {{-- Customer Name --}}
                <div class="space-y-2">
                    <label for="customer-name" class="text-sm text-[#5F514D]">
                        نام و نام خانوادگی / نام شرکت
                        <span class="text-rose-600">*</span>
                    </label>

                    <input
                        id="customer-name"
                        name="name"
                        type="text"
                        maxlength="200"
                        required
                        placeholder="نام مشتری یا شرکت"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                    >
                </div>

                {{-- Phone and National ID --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="space-y-2">
                        <label for="customer-phone" class="text-sm text-[#5F514D]">
                            شماره تلفن
                        </label>

                        <input
                            id="customer-phone"
                            name="phone"
                            type="tel"
                            maxlength="16"
                            dir="ltr"
                            placeholder="09xxxxxxxxx"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-left text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label
                            id="customer-national-id-label"
                            for="customer-national-id"
                            class="text-sm text-[#5F514D]"
                        >
                            شناسه
                        </label>

                        <input
                            id="customer-national-id"
                            name="national_id"
                            type="text"
                            maxlength="20"
                            dir="ltr"
                            placeholder="کد ملی شخص یا شناسه شرکت"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-left text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                        >
                    </div>

                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label for="customer-description" class="text-sm text-[#5F514D]">
                        توضیحات
                    </label>

                    <textarea
                        id="customer-description"
                        name="description"
                        rows="3"
                        maxlength="5000"
                        placeholder="توضیحات تکمیلی درباره مشتری (اختیاری)"
                        class="w-full resize-y rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                    ></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-[#EEE8E5] pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="closeCustomerModal()"
                        class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-sm text-[#827875] transition hover:bg-[#FAF8F7] hover:text-[#542b2e] kohan-bold"
                    >
                        انصراف
                    </button>

                    <button
                        id="customer-submit-button"
                        type="submit"
                        class="cursor-pointer rounded-xl bg-[#542b2e] px-6 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                    >
                        ثبت مشتری
                    </button>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const modal = document.getElementById('customer-modal');
                const form = document.getElementById('customer-form');
                const methodInput = document.getElementById('customer-form-method');
                const title = document.getElementById('customer-modal-title');
                const submitButton = document.getElementById('customer-submit-button');
                const typeInput = document.getElementById('customer-type');
                const nameInput = document.getElementById('customer-name');
                const phoneInput = document.getElementById('customer-phone');
                const nationalIdInput = document.getElementById('customer-national-id');
                const nationalIdLabel = document.getElementById('customer-national-id-label');
                const descriptionInput = document.getElementById('customer-description');
                const storeUrl = @json(route('admin.customers.store'));
                const updateUrlTemplate = @json(route('admin.customers.update', ['customer' => '__CUSTOMER_ID__']));

                window.openCustomerModal = (mode, customer = null) => {
                    form.reset();

                    if (mode === 'edit' && customer) {
                        title.textContent = 'ویرایش مشتری';
                        submitButton.textContent = 'ذخیره تغییرات';
                        form.action = updateUrlTemplate.replace('__CUSTOMER_ID__', customer.id);
                        methodInput.disabled = false;
                        methodInput.value = 'PUT';

                        typeInput.value = customer.type;
                        nameInput.value = customer.name || '';
                        phoneInput.value = customer.phone || '';
                        nationalIdInput.value = customer.national_id || '';
                        descriptionInput.value = customer.description || '';
                    } else {
                        title.textContent = 'مشتری جدید';
                        submitButton.textContent = 'ثبت مشتری';
                        form.action = storeUrl;
                        methodInput.disabled = true;
                        methodInput.value = 'POST';
                        typeInput.value = 'person';
                    }

                    updateNationalIdLabel();
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                    nameInput.focus();
                };

                window.closeCustomerModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');
                };

                const updateNationalIdLabel = () => {
                    const isCompany = typeInput.value === 'company';
                    nationalIdLabel.textContent = isCompany ? 'شماره ثبت شرکت' : 'شناسه';
                    nationalIdInput.placeholder = isCompany ? 'شماره ثبت شرکت' : 'کد ملی شخص یا شناسه شرکت';
                };

                typeInput.addEventListener('change', updateNationalIdLabel);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        window.closeCustomerModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        window.closeCustomerModal();
                    }
                });
            })();
        </script>
    @endpush
@endsection