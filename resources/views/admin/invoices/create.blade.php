@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-24" dir="rtl" id="invoice-form-page">

    <div>
        <a href="{{ route('admin.invoices.index') }}"
           class="text-sm text-gray-400 transition hover:text-white">
            ← بازگشت به فهرست اسناد
        </a>

        <h1 class="mt-4 text-2xl font-bold text-white">ثبت سند جدید</h1>
        <p class="mt-2 text-sm text-gray-400">ثبت فاکتور یا پیش‌فاکتور مشتری</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.invoices.store') }}" id="invoice-form"
          class="space-y-6">
        @csrf

        <section class="space-y-5 rounded-2xl border border-white/10 bg-[#222222] p-5 sm:p-6">
            <h2 class="text-lg font-bold text-white">اطلاعات سند</h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="type" class="mb-2 block text-sm text-gray-300">نوع سند</label>
                    <select id="type" name="type" required
                            class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white">
                        <option value="invoice" @selected(old('type', 'invoice') === 'invoice')>فاکتور</option>
                        <option value="proforma" @selected(old('type') === 'proforma')>پیش‌فاکتور</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="customer-search" class="mb-2 block text-sm text-gray-300">مشتری</label>
                    <input type="text" id="customer-search" autocomplete="off"
                           value="{{ old('customer_id') ? ($customers->firstWhere('id', old('customer_id'))?->name ?? '') : '' }}"
                           placeholder="نام مشتری را جست‌وجو کنید..."
                           class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white placeholder:text-gray-500"
                           required>

                    <input type="hidden" name="customer_id" id="customer_id"
                           value="{{ old('customer_id') }}">

                    <div id="customer-results"
                         class="absolute inset-x-0 top-full z-20 mt-1 hidden max-h-56 overflow-y-auto rounded-xl border border-white/10 bg-[#292929] shadow-xl">
                    </div>

                    <p id="customer-message" class="mt-2 hidden text-xs text-red-400">
                        مشتری مورد نظر یافت نشد.
                    </p>
                </div>

                <div>
                    <label for="issue_date" class="mb-2 block text-sm text-gray-300">تاریخ صدور</label>
                    <input type="date" id="issue_date" name="issue_date"
                           value="{{ old('issue_date', now()->format('Y-m-d')) }}" required
                           class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white">
                </div>

                <div id="valid-until-wrapper">
                    <label for="valid_until" class="mb-2 block text-sm text-gray-300">معتبر تا تاریخ</label>
                    <input type="date" id="valid_until" name="valid_until"
                           value="{{ old('valid_until') }}"
                           class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white">
                    <p class="mt-1 text-xs text-gray-500">فقط برای پیش‌فاکتور قابل استفاده است.</p>
                </div>

                <div>
                    <label for="payment_type" class="mb-2 block text-sm text-gray-300">نوع پرداخت</label>
                    <input type="text" id="payment_type" name="payment_type"
                           value="{{ old('payment_type') }}" maxlength="100"
                           placeholder="مثلاً نقدی، اعتباری یا توافقی"
                           class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white placeholder:text-gray-500">
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm text-gray-300">توضیحات سند</label>
                    <textarea id="description" name="description" rows="2"
                              class="w-full rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white">{{ old('description') }}</textarea>
                </div>
            </div>

            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-white/10 p-4">
                <input type="checkbox" name="is_formal" value="0"
                       @checked(old('is_formal') === '0')
                       class="h-4 w-4 accent-[#681b21]">
                <span>
                    <span class="block text-sm font-semibold text-white">ثبت غیر رسمی</span>
                    <span class="mt-1 block text-xs text-gray-400">
                        با انتخاب این گزینه، سربرگ رسمی و QR اعتبارسنجی برای سند فعال نمی‌شود.
                    </span>
                </span>
            </label>
        </section>

        <section class="space-y-5 rounded-2xl border border-white/10 bg-[#222222] p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-white">اقلام سند</h2>
                <button type="button" id="add-item"
                        class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                    + افزودن ردیف
                </button>
            </div>

            <div id="items-container" class="space-y-5"></div>

            <div class="rounded-xl border border-white/10 bg-black/10 p-4 sm:mr-auto sm:max-w-sm">
                <div class="flex justify-between gap-4 py-2 text-sm text-gray-300">
                    <span>جمع اقلام</span>
                    <span id="subtotal-display">۰ ریال</span>
                </div>
                <div class="flex justify-between gap-4 py-2 text-sm text-gray-300">
                    <span>مجموع مالیات</span>
                    <span id="vat-display">۰ ریال</span>
                </div>
                <div class="mt-2 flex justify-between gap-4 border-t border-white/10 pt-4 font-bold text-white">
                    <span>مبلغ نهایی</span>
                    <span id="grand-total-display" class="text-lg">۰ ریال</span>
                </div>
            </div>
        </section>

        <section class="space-y-4 rounded-2xl border border-white/10 bg-[#222222] p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-white">متن‌ها و توضیحات تکمیلی</h2>
                <button type="button" id="add-note"
                        class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                    + افزودن متن
                </button>
            </div>

            <div id="notes-container" class="space-y-3"></div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.invoices.index') }}"
               class="rounded-xl border border-white/10 px-6 py-3 text-center text-sm font-semibold text-gray-300 transition hover:bg-white/5">
                انصراف
            </a>

            <button type="submit" id="submit-invoice"
                    class="rounded-xl bg-[#681b21] px-8 py-3 text-sm font-bold text-white transition hover:bg-[#81272e]">
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
                option.className = 'block w-full px-4 py-3 text-right text-sm text-white hover:bg-white/10';
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

            row.className = 'item-row rounded-xl border border-white/10 bg-black/10 p-4';
            row.innerHTML = `
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-white">ردیف <span class="item-number">${index + 1}</span></h3>
                    <button type="button" class="remove-item text-sm text-red-300 hover:text-red-200">حذف ردیف</button>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="mb-2 block text-xs text-gray-400">شرح کالا یا خدمت</label>
                        <input name="items[${index}][description]" required maxlength="500"
                               value="${escapeHtml(data.description || '')}"
                               class="item-description w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">تعداد</label>
                        <input type="number" name="items[${index}][quantity]" required min="0.001" step="0.001"
                               value="${escapeHtml(data.quantity ?? 1)}"
                               class="item-quantity w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">واحد</label>
                        <input name="items[${index}][unit]" maxlength="50"
                               value="${escapeHtml(data.unit || '')}" placeholder="عدد، کیلوگرم..."
                               class="w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">عرض</label>
                        <input type="number" name="items[${index}][width]" min="0" step="0.001"
                               value="${escapeHtml(data.width || '')}"
                               class="w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">ضخامت</label>
                        <input type="number" name="items[${index}][thickness]" min="0" step="0.001"
                               value="${escapeHtml(data.thickness || '')}"
                               class="w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">قیمت واحد (ریال)</label>
                        <input type="number" name="items[${index}][unit_price]" required min="0" step="1"
                               value="${escapeHtml(data.unit_price ?? 0)}"
                               class="item-price w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs text-gray-400">درصد مالیات</label>
                        <input type="number" name="items[${index}][vat_percent]" required min="0" max="100" step="0.01"
                               value="${escapeHtml(data.vat_percent ?? 0)}"
                               class="item-vat w-full rounded-lg border border-white/10 bg-[#292929] px-3 py-3 text-sm text-white">
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-400">جمع ردیف با مالیات</p>
                        <p class="item-total mt-2 font-bold text-white">۰ ریال</p>
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
            textarea.className = 'min-w-0 flex-1 rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white';

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.textContent = 'حذف';
            remove.className = 'rounded-lg px-3 py-2 text-sm text-red-300 hover:bg-white/5';

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