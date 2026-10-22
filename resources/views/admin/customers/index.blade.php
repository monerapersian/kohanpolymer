@extends('layouts.admin')

@section('title', 'مدیریت مشتریان | کهن پلیمر البرز')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mb-2 text-xs font-medium text-white/45">مدیریت ارتباط با مشتریان</p>
                <h1 class="text-2xl font-black text-white sm:text-3xl">مشتریان</h1>
                <p class="mt-2 text-sm text-white/55">
                    مدیریت اطلاعات مشتریان و دسترسی به فاکتورها و پیش‌فاکتورها
                </p>
            </div>

            <button
                type="button"
                onclick="openCustomerModal('create')"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#7d252c] kohan-bold cursor-pointer"
            >
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                </svg>
                مشتری جدید
            </button>
        </section>

        {{-- Statistics --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                <p class="text-sm text-white/55">کل مشتریان</p>
                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl font-black text-white kohan-bold">{{ number_format($totalCustomers) }}</span>
                    <span class="rounded-xl bg-white/5 p-3 text-white/60">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                <p class="text-sm text-white/55">شخص</p>
                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl font-black text-white kohan-bold">{{ number_format($personCount) }}</span>
                    <span class="rounded-xl bg-emerald-400/10 p-3 text-emerald-300">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                <p class="text-sm text-white/55">شرکت</p>
                <div class="mt-3 flex items-end justify-between">
                    <span class="text-3xl font-black text-white kohan-bold">{{ number_format($companyCount) }}</span>
                    <span class="rounded-xl bg-rose-400/10 p-3 text-rose-300">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                            <path d="M3 21h18M5 21V5l7-3 7 3v16M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h2v4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        {{-- Search and Filters --}}
        <section class="rounded-2xl border border-white/10 bg-[#222222]/80 p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.customers.index') }}"
                  class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_190px_auto]">

                <div class="relative">
                    <svg viewBox="0 0 24 24" fill="none"
                         class="absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35"
                         stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4" stroke-linecap="round"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جست‌وجو نام، تلفن یا شناسه مشتری..."
                        class="w-full rounded-xl border border-white/10 bg-white/[0.04] py-3 pr-11 pl-4 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21]"
                    >
                </div>

                <select
                    name="type"
                    class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21] cursor-pointer"
                >
                    <option value="">همه </option>
                    <option value="person" @selected(request('type') === 'person')>شخص</option>
                    <option value="company" @selected(request('type') === 'company')>شرکت</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 rounded-xl bg-white/10 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/15 sm:flex-none kohan-bold cursor-pointer">
                        جست‌وجو
                    </button>

                    @if(request()->filled('search') || request()->filled('type'))
                        <a href="{{ route('admin.customers.index') }}"
                           class="flex items-center justify-center rounded-xl border border-white/10 px-4 py-3 text-sm text-white/65 transition hover:bg-white/5">
                            پاک‌کردن
                        </a>
                    @endif
                </div>
            </form>
        </section>

        {{-- Customers Table --}}
        <section class="overflow-hidden rounded-2xl border border-white/10 bg-[#222222]/80">
            <div class="flex flex-col gap-2 border-b border-white/10 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-bold text-white">فهرست مشتریان</h2>
                <span class="text-xs text-white/40">
                    {{ number_format($customers->total()) }} مشتری
                </span>
            </div>

            @if(session('success'))
                <div class="mx-5 mt-4 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mx-5 mt-4 rounded-xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-300">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($customers->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-white/35">
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <circle cx="9" cy="8" r="4"/>
                            <path d="M3 21v-2a6 6 0 0 1 12 0v2m3-10h4m-2-2v4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 font-bold text-white">هنوز مشتری‌ای ثبت نشده است</h3>
                    <p class="mt-2 text-sm text-white/45">برای شروع، اولین مشتری را ثبت کنید.</p>
                    <button type="button" onclick="openCustomerModal('create')"
                            class="mt-5 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#7d252c] kohan-bold cursor-pointer">
                        ثبت مشتری جدید
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-right">
                        <thead class="bg-white/[0.025] text-xs text-white/45">
                            <tr>
                                <th class="px-5 py-4 font-medium">ردیف</th>
                                <th class="px-5 py-4 font-medium">نام مشتری</th>
                                <th class="px-5 py-4 font-medium">نوع مشتری</th>
                                <th class="px-5 py-4 font-medium">تلفن</th>
                                <th class="px-5 py-4 font-medium">شناسه / کد ملی</th>
                                <th class="px-5 py-4 text-center font-medium">ویرایش</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.06]">
                            @foreach($customers as $customer)
                                <tr
                                    class="cursor-pointer transition hover:bg-white/[0.035]"
                                    onclick="window.location.href='{{ route('admin.customers.show', $customer) }}'"
                                >
                                    <td class="px-5 py-4 text-sm text-white/40">
                                        {{ $customers->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-white kohan-bold">{{ $customer->name }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($customer->type === 'company')
                                            <span class="inline-flex rounded-lg bg-rose-400/10 px-2.5 py-1.5 text-xs text-rose-300">شخص حقوقی</span>
                                        @else
                                            <span class="inline-flex rounded-lg bg-emerald-400/10 px-2.5 py-1.5 text-xs text-emerald-300">شخص حقیقی</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-sm text-white/65" dir="ltr">
                                        {{ $customer->phone ?: '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-white/65">
                                        {{ $customer->national_id ?: '—' }}
                                    </td>

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
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 text-white/60 transition hover:border-[#681b21] hover:bg-[#681b21]/20 hover:text-white"
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

                <div class="border-t border-white/10 px-5 py-4">
                    {{ $customers->links() }}
                </div>
            @endif
        </section>
    </div>

    {{-- Customer Modal --}}
    <div id="customer-modal"
         class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
         aria-hidden="true">
        <div class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
             role="dialog" aria-modal="true" aria-labelledby="customer-modal-title">

            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 id="customer-modal-title" class="text-lg font-black text-white">مشتری جدید</h2>
                    <p class="mt-1 text-xs text-white/45">اطلاعات مشتری را وارد کنید.</p>
                </div>

                <button type="button" onclick="closeCustomerModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-white/55 transition hover:bg-white/10 hover:text-white kohan-bold cursor-pointer"
                        aria-label="بستن">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <form id="customer-form" method="POST" action="{{ route('admin.customers.store') }}" class="space-y-5 p-5 sm:p-6">
                @csrf
                <input id="customer-form-method" type="hidden" name="_method" value="POST" disabled>

                <div class="space-y-2">
                    <label for="customer-type" class="text-sm font-medium text-white/75">نوع مشتری <span class="text-rose-300">*</span></label>
                    <select id="customer-type" name="type" required
                            class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21] cursor-pointer">
                        <option value="person">شخص</option>
                        <option value="company">شرکت</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="customer-name" class="text-sm font-medium text-white/75">نام و نام خانوادگی / نام شرکت <span class="text-rose-300">*</span></label>
                    <input id="customer-name" name="name" type="text" maxlength="200" required
                           placeholder="نام مشتری یا شرکت"
                           class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white outline-none placeholder:text-white/25 focus:border-[#681b21]">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="customer-phone" class="text-sm font-medium text-white/75">شماره تلفن</label>
                        <input id="customer-phone" name="phone" type="tel" maxlength="16" dir="ltr"
                               placeholder="09xxxxxxxxx"
                               class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-left text-sm text-white outline-none placeholder:text-white/25 focus:border-[#681b21]">
                    </div>

                    <div class="space-y-2">
                        <label id="customer-national-id-label" for="customer-national-id" class="text-sm font-medium text-white/75">شناسه</label>
                        <input id="customer-national-id" name="national_id" type="text" maxlength="20" dir="ltr"
                               placeholder="کد ملی شخص یا شناسه شرکت"
                               class="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-left text-sm text-white outline-none placeholder:text-white/25 focus:border-[#681b21]">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="customer-description" class="text-sm font-medium text-white/75">توضیحات</label>
                    <textarea id="customer-description" name="description" rows="3" maxlength="5000"
                              placeholder="توضیحات تکمیلی درباره مشتری (اختیاری)"
                              class="w-full resize-y rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white outline-none placeholder:text-white/25 focus:border-[#681b21]"></textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" onclick="closeCustomerModal()"
                            class="rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-white/65 transition hover:bg-white/5 kohan-bold cursor-pointer">
                        انصراف
                    </button>
                    <button id="customer-submit-button" type="submit"
                            class="rounded-xl bg-[#681b21] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#7d252c] kohan-bold cursor-pointer">
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