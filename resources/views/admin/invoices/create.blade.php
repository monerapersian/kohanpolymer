@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-24" dir="rtl" id="invoice-form-page">

    {{-- Page Header --}}
    <section class="hero-animated-bg relative isolate overflow-hidden rounded-3xl p-6 sm:p-8 lg:p-10">

        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="hero-orb hero-orb-three"></div>

        <div class="hero-wave"></div>

        <div class="hero-ring hero-ring-one"></div>
        <div class="hero-ring hero-ring-two"></div>

        <div class="hero-grid"></div>
        <div class="hero-shine"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-start">

            <div>
                <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs text-white/80 backdrop-blur-sm">
                    مدیریت اسناد مالی
                </span>

                <h1 class="mt-4 text-2xl text-white kohan-bold sm:text-3xl">
                    ثبت سند جدید
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-white/65">
                    از این بخش می‌توانید فاکتور یا پیش‌فاکتور جدیدی برای مشتریان ثبت کنید.
                </p>
            </div>

            <a
                href="{{ route('admin.invoices.index') }}"
                class="inline-flex shrink-0 cursor-pointer items-center gap-2 self-start rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white backdrop-blur-sm transition hover:bg-white/20"
            >
                <span>←</span>
                بازگشت به فهرست اسناد
            </a>

        </div>
    </section>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.invoices.store') }}"
        id="invoice-form"
        class="space-y-6"
    >
        @csrf

        {{-- Document Information --}}
        <section class="space-y-5 rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="M7 3h7l5 5v13H5V3h2Z" stroke-linejoin="round"/>
                        <path d="M14 3v6h5M8 13h8M8 17h8" stroke-linecap="round"/>
                    </svg>
                </span>

                <h2 class="text-lg text-[#362727] kohan-bold">
                    اطلاعات سند
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Document Type --}}
                <div>
                    <label for="type" class="mb-2 block text-sm text-[#5F514D]">
                        نوع سند
                    </label>

                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666]"
                    >
                        <option value="invoice" @selected(old('type', 'invoice') === 'invoice')>
                            فاکتور
                        </option>

                        <option value="proforma" @selected(old('type') === 'proforma')>
                            پیش‌فاکتور
                        </option>
                    </select>
                </div>

                {{-- Customer --}}
                <div class="relative">
                    <label for="customer-search" class="mb-2 block text-sm text-[#5F514D]">
                        مشتری
                    </label>

                    <input
                        type="text"
                        id="customer-search"
                        autocomplete="off"
                        value="{{ old('customer_id') ? ($customers->firstWhere('id', old('customer_id'))?->name ?? '') : '' }}"
                        placeholder="نام مشتری را جست‌وجو کنید..."
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666] focus:bg-white"
                        required
                    >

                    <input
                        type="hidden"
                        name="customer_id"
                        id="customer_id"
                        value="{{ old('customer_id') }}"
                    >

                    <div
                        id="customer-results"
                        class="absolute inset-x-0 top-full z-20 mt-1 hidden max-h-56 overflow-y-auto rounded-xl border border-[#E8E1DE] bg-white shadow-xl text-[#362727]"
                    ></div>

                    <p id="customer-message" class="mt-2 hidden text-xs text-rose-600">
                        مشتری مورد نظر یافت نشد.
                    </p>
                </div>

                {{-- Issue Date --}}
                <div>
                    <label for="issue_date" class="mb-2 block text-sm text-[#5F514D]">
                        تاریخ صدور
                    </label>

                    <input
                        type="date"
                        id="issue_date"
                        name="issue_date"
                        value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666]"
                    >
                </div>

                {{-- Valid Until --}}
                <div id="valid-until-wrapper">
                    <label for="valid_until" class="mb-2 block text-sm text-[#5F514D]">
                        معتبر تا تاریخ
                    </label>

                    <input
                        type="date"
                        id="valid_until"
                        name="valid_until"
                        value="{{ old('valid_until') }}"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666]"
                    >

                    <p class="mt-1 text-xs text-[#A99D98]">
                        فقط برای پیش‌فاکتور قابل استفاده است.
                    </p>
                </div>

                {{-- Payment Type --}}
                <div>
                    <label for="payment_type" class="mb-2 block text-sm text-[#5F514D]">
                        نوع پرداخت
                    </label>

                    <input
                        type="text"
                        id="payment_type"
                        name="payment_type"
                        value="{{ old('payment_type') }}"
                        maxlength="100"
                        placeholder="مثلاً نقدی، اعتباری یا توافقی"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#B0A5A0] focus:border-[#8d6666]"
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 block text-sm text-[#5F514D]">
                        توضیحات سند
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="2"
                        class="w-full resize-y rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666]"
                    >{{ old('description') }}</textarea>
                </div>

            </div>

            {{-- Formal Document --}}
            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition hover:border-[#d6c5c2]">

                <input
                    type="checkbox"
                    name="is_formal"
                    value="0"
                    @checked(old('is_formal') === '0')
                    class="mt-0.5 h-4 w-4 cursor-pointer accent-[#542b2e]"
                >

                <span>
                    <span class="block text-sm text-[#362727] kohan-bold">
                        ثبت غیر رسمی
                    </span>

                    <span class="mt-1 block text-xs leading-6 text-[#827875]">
                        با انتخاب این گزینه، سربرگ رسمی و QR اعتبارسنجی برای سند فعال نمی‌شود.
                    </span>
                </span>

            </label>

        </section>

        {{-- Document Items --}}
        <section class="space-y-5 rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                            <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-linecap="round"/>
                        </svg>
                    </span>

                    <h2 class="text-lg text-[#362727] kohan-bold">
                        اقلام سند
                    </h2>
                </div>

                <button
                    type="button"
                    id="add-item"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-2 text-sm text-[#674040] transition hover:border-[#d6c5c2] hover:bg-[#F3E9E9] kohan-bold"
                >
                    <span class="text-lg">+</span>
                    افزودن ردیف
                </button>
            </div>

            <div id="items-container" class="space-y-5"></div>

            {{-- Totals --}}
            <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 sm:mr-auto sm:max-w-sm">

                <div class="flex justify-between gap-4 py-2 text-sm text-[#827875]">
                    <span>جمع اقلام</span>
                    <span id="subtotal-display">۰ ریال</span>
                </div>

                <div class="flex justify-between gap-4 py-2 text-sm text-[#827875]">
                    <span>مجموع مالیات</span>
                    <span id="vat-display">۰ ریال</span>
                </div>

                <div class="mt-2 flex justify-between gap-4 border-t border-[#E8E1DE] pt-4 text-[#362727] kohan-bold">
                    <span>مبلغ نهایی</span>
                    <span id="grand-total-display" class="text-lg text-[#542b2e]">
                        ۰ ریال
                    </span>
                </div>

            </div>

        </section>

        {{-- Additional Notes --}}
        <section class="space-y-4 rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                            <path d="M8 4h13v16H3V4h5ZM7 9h10M7 13h10M7 17h6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>

                    <h2 class="text-lg text-[#362727] kohan-bold">
                        متن‌ها و توضیحات تکمیلی
                    </h2>
                </div>

                <button
                    type="button"
                    id="add-note"
                    class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-2 text-sm text-[#674040] transition hover:border-[#d6c5c2] hover:bg-[#F3E9E9] kohan-bold"
                >
                    <span class="text-lg">+</span>
                    افزودن متن
                </button>
            </div>

            <div id="notes-container" class="space-y-3"></div>

        </section>

        {{-- Form Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.invoices.index') }}"
                class="cursor-pointer rounded-xl border border-[#E8E1DE] px-6 py-3 text-center text-sm text-[#827875] transition hover:bg-[#FAF8F7] hover:text-[#542b2e] kohan-bold"
            >
                انصراف
            </a>

            <button
                type="submit"
                id="submit-invoice"
                class="cursor-pointer rounded-xl bg-[#542b2e] px-8 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
            >
                ثبت سند
            </button>

        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('invoice-form');
        const typeInput = document.getElementById('type');
        const validUntilWrapper = document.getElementById('valid-until-wrapper');
        const validUntilInput = document.getElementById('valid_until');

        const customerInput = document.getElementById('customer-search');
        const customerIdInput = document.getElementById('customer_id');
        const customerResults = document.getElementById('customer-results');
        const customerMessage = document.getElementById('customer-message');

        const itemsContainer = document.getElementById('items-container');
        const notesContainer = document.getElementById('notes-container');

        const oldItems = @json(old('items', []));
        const oldNotes = @json(old('notes', []));

        const customers = {{ Illuminate\Support\Js::from(
            $customers->map(fn ($customer) => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ])->values()
        ) }};

        const rialFormatter = new Intl.NumberFormat('fa-IR', {
            maximumFractionDigits: 2
        });

        const formatRial = value => `${rialFormatter.format(value)} ریال`;

        function updateDocumentType() {
            const isProforma = typeInput.value === 'proforma';

            validUntilWrapper.classList.toggle('hidden', !isProforma);
            validUntilInput.disabled = !isProforma;

            if (!isProforma) {
                validUntilInput.value = '';
            }
        }

        typeInput.addEventListener('change', updateDocumentType);
        updateDocumentType();

        function renderCustomerResults() {
            const search = customerInput.value.trim().toLocaleLowerCase('fa-IR');

            customerResults.replaceChildren();
            customerMessage.classList.add('hidden');

            if (!search) {
                customerResults.classList.add('hidden');
                return;
            }

            const matches = customers.filter(customer =>
                customer.name.toLocaleLowerCase('fa-IR').includes(search) ||
                (customer.phone || '').includes(search)
            );

            if (!matches.length) {
                customerResults.classList.add('hidden');
                customerMessage.classList.remove('hidden');
                customerMessage.textContent = 'مشتری مورد نظر یافت نشد.';
                return;
            }

            matches.forEach(customer => {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'block w-full px-4 py-3 text-right text-sm hover:bg-white/10';
                option.textContent = customer.phone
                    ? `${customer.name} — ${customer.phone}`
                    : customer.name;

                option.addEventListener('click', () => {
                    customerInput.value = customer.name;
                    customerIdInput.value = customer.id;
                    customerResults.classList.add('hidden');
                    customerMessage.classList.add('hidden');
                });

                customerResults.appendChild(option);
            });

            customerResults.classList.remove('hidden');
        }

        customerInput.addEventListener('input', () => {
            customerIdInput.value = '';
            renderCustomerResults();
        });

        customerInput.addEventListener('focus', renderCustomerResults);

        document.addEventListener('click', event => {
            if (!event.target.closest('#customer-search') &&
                !event.target.closest('#customer-results')) {
                customerResults.classList.add('hidden');
            }
        });

        function createItemRow(data = {}) {
            const index = itemsContainer.children.length;
            const row = document.createElement('div');

            row.className = 'item-row rounded-2xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 sm:p-5';

            row.innerHTML = `
                <div class="mb-5 flex items-center justify-between gap-3">
                    <h3 class="text-sm text-[#362727] kohan-bold">
                        ردیف <span class="item-number">${index + 1}</span>
                    </h3>

                    <button
                        type="button"
                        class="remove-item cursor-pointer rounded-lg px-3 py-2 text-xs text-rose-600 transition hover:bg-rose-50 hover:text-rose-700"
                    >
                        حذف ردیف
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="mb-2 block text-xs text-[#827875]">
                            شرح کالا یا خدمت
                        </label>

                        <input
                            name="items[${index}][description]"
                            required
                            maxlength="500"
                            value="${escapeHtml(data.description || '')}"
                            class="item-description w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#A99D98] focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">تعداد</label>
                        <input
                            type="number"
                            name="items[${index}][quantity]"
                            required
                            min="0.001"
                            step="0.001"
                            value="${escapeHtml(data.quantity ?? 1)}"
                            class="item-quantity w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">واحد</label>
                        <input
                            name="items[${index}][unit]"
                            maxlength="50"
                            value="${escapeHtml(data.unit || '')}"
                            placeholder="عدد، کیلوگرم..."
                            class="w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#A99D98] focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">عرض</label>
                        <input
                            type="number"
                            name="items[${index}][width]"
                            min="0"
                            step="0.001"
                            value="${escapeHtml(data.width || '')}"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">ضخامت</label>
                        <input
                            type="number"
                            name="items[${index}][thickness]"
                            min="0"
                            step="0.001"
                            value="${escapeHtml(data.thickness || '')}"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">قیمت واحد (ریال)</label>
                        <input
                            type="number"
                            name="items[${index}][unit_price]"
                            required
                            min="0"
                            step="1"
                            value="${escapeHtml(data.unit_price ?? 0)}"
                            class="item-price w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-[#827875]">درصد مالیات</label>
                        <input
                            type="number"
                            name="items[${index}][vat_percent]"
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            value="${escapeHtml(data.vat_percent ?? 0)}"
                            class="item-vat w-full rounded-xl border border-[#E8E1DE] bg-white px-3 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                        >
                    </div>

                    <div class="sm:col-span-2 flex flex-col justify-center rounded-xl border border-[#E8E1DE] bg-white px-4 py-3">
                        <p class="text-xs text-[#827875]">جمع ردیف با مالیات</p>
                        <p class="item-total mt-2 text-base text-[#542b2e] kohan-bold">۰ ریال</p>
                    </div>

                </div>
            `;

            itemsContainer.appendChild(row);

            row.querySelector('.remove-item').addEventListener('click', () => {
                row.remove();
                reindexItems();
                calculateTotals();
            });

            row.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });

            calculateTotals();
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[char]);
        }

        function reindexItems() {
            [...itemsContainer.querySelectorAll('.item-row')].forEach((row, index) => {
                row.querySelector('.item-number').textContent = index + 1;

                row.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
                });
            });
        }

        function calculateTotals() {
            let subtotal = 0;
            let totalVat = 0;

            itemsContainer.querySelectorAll('.item-row').forEach(row => {
                const quantity = Math.max(0, Number(row.querySelector('.item-quantity').value) || 0);
                const price = Math.max(0, Number(row.querySelector('.item-price').value) || 0);
                const vatPercent = Math.min(100, Math.max(0, Number(row.querySelector('.item-vat').value) || 0));

                const lineSubtotal = quantity * price;
                const vatAmount = lineSubtotal * vatPercent / 100;
                const lineTotal = lineSubtotal + vatAmount;

                subtotal += lineSubtotal;
                totalVat += vatAmount;

                row.querySelector('.item-total').textContent = formatRial(lineTotal);
            });

            document.getElementById('subtotal-display').textContent = formatRial(subtotal);
            document.getElementById('vat-display').textContent = formatRial(totalVat);
            document.getElementById('grand-total-display').textContent = formatRial(subtotal + totalVat);
        }

        document.getElementById('add-item').addEventListener('click', () => createItemRow());

        function createNoteRow(value = '') {
            const row = document.createElement('div');
            row.className = 'note-row flex items-start gap-3';

            const textarea = document.createElement('textarea');
            textarea.name = `notes[${notesContainer.children.length}][description]`;
            textarea.rows = 2;
            textarea.maxLength = 5000;
            textarea.placeholder = 'متن یا توضیحات تکمیلی...';
            textarea.value = value;

            textarea.className = 'min-w-0 flex-1 rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition placeholder:text-[#A99D98] focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10';

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.textContent = 'حذف';
            remove.className = 'cursor-pointer rounded-xl px-3 py-3 text-sm text-rose-600 transition hover:bg-rose-50 hover:text-rose-700';

            remove.addEventListener('click', () => {
                row.remove();
                reindexNotes();
            });

            row.append(textarea, remove);
            notesContainer.appendChild(row);
        }

        function reindexNotes() {
            [...notesContainer.querySelectorAll('.note-row textarea')].forEach((textarea, index) => {
                textarea.name = `notes[${index}][description]`;
            });
        }

        document.getElementById('add-note').addEventListener('click', () => createNoteRow());

        if (Array.isArray(oldItems) && oldItems.length) {
            oldItems.forEach(item => createItemRow(item));
        } else {
            createItemRow();
        }

        if (Array.isArray(oldNotes)) {
            oldNotes.forEach(note => {
                if (note.description) createNoteRow(note.description);
            });
        }

        form.addEventListener('submit', event => {
            if (!customerIdInput.value) {
                event.preventDefault();
                customerMessage.textContent = 'لطفاً یک مشتری موجود را از فهرست انتخاب کنید.';
                customerMessage.classList.remove('hidden');
                customerInput.focus();
                return;
            }

            if (itemsContainer.querySelectorAll('.item-row').length === 0) {
                event.preventDefault();
                alert('حداقل یک ردیف کالا یا خدمت لازم است.');
                return;
            }

            if (typeInput.value === 'proforma' &&
                validUntilInput.value &&
                validUntilInput.value < document.getElementById('issue_date').value) {
                event.preventDefault();
                alert('تاریخ اعتبار نمی‌تواند قبل از تاریخ صدور باشد.');
            }
        });
    });
</script>
@endsection