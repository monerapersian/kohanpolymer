@extends('layouts.admin')

@section('title', 'مدیریت درخواست‌ها | کهن پلیمر البرز')

@section('content')

    <section class="space-y-6">

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
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/65">
                        <span class="h-2 w-2 rounded-full bg-[#d9a3a6] shadow-[0_0_10px_#d9a3a6]"></span>
                        ارتباط با مشتریان
                    </span>

                    <h1 class="text-2xl tracking-tight text-white kohan-bold sm:text-3xl">
                        مدیریت درخواست‌ها
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/60">
                        درخواست‌های ثبت‌شده در وب‌سایت را مشاهده، بررسی و پیگیری کن.
                    </p>
                </div>

                <div class="relative flex shrink-0 items-center gap-4 overflow-hidden rounded-2xl border border-white/10 bg-white/[0.07] px-5 py-4 backdrop-blur-md">
                    <div class="absolute -left-8 -top-8 h-24 w-24 rounded-full bg-[#a66f72]/20 blur-2xl"></div>

                    <div class="relative flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-[#a66f72]/20 text-[#e8c5c6]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8l-9 6-9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    <div class="relative">
                        <p class="text-xs text-white/50">کل درخواست‌ها</p>
                        <p class="mt-1 text-2xl text-white kohan-bold tabular-nums">
                            {{ number_format($totalInquiries) }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- Total Inquiries --}}
            <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_4px_18px_rgba(54,39,39,0.04)]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-[#827875]">همه درخواست‌ها</p>
                        <p class="mt-3 text-3xl text-[#362727] kohan-bold">
                            {{ number_format($totalInquiries) }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#542b2e]/10 text-[#542b2e]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Unread Inquiries --}}
            <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-[0_4px_18px_rgba(54,39,39,0.04)]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-[#827875]">درخواست‌های مشاهده‌نشده</p>
                        <p
                            data-unread-stat="{{ $unreadInquiries }}"
                            class="mt-3 text-3xl text-amber-600 kohan-bold"
                        >
                            {{ number_format($unreadInquiries) }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Inquiry List --}}
        <div class="overflow-hidden rounded-3xl border border-[#E8E1DE] bg-white shadow-[0_12px_40px_rgba(54,39,39,0.05)]">

            {{-- List Header --}}
            <div class="flex flex-col gap-4 border-b border-[#EEE8E5] bg-[#FAF8F7] p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg text-[#362727] kohan-bold">
                        فهرست درخواست‌ها
                    </h2>
                    <p class="mt-1 text-sm text-[#9A8E89]">
                        برای مشاهده جزئیات، روی هر درخواست کلیک کن.
                    </p>
                </div>

                <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">

                    {{-- Search --}}
                    <div class="relative">
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A99D98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="m16 16 4 4"/>
                        </svg>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="جست‌وجوی نام یا شماره تماس"
                            class="w-full rounded-xl border border-[#E8E1DE] bg-white py-3 pr-10 pl-4 text-sm text-[#362727] outline-none transition placeholder:text-[#B5AAA5] focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10 sm:w-64"
                        >
                    </div>

                    {{-- Status Filter --}}
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="cursor-pointer rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                    >
                        <option value="">همه درخواست‌ها</option>
                        <option value="unread" @selected(request('status') === 'unread')>مشاهده‌نشده</option>
                        <option value="read" @selected(request('status') === 'read')>مشاهده‌شده</option>
                    </select>

                    {{-- Search Button --}}
                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-[#542b2e] px-5 py-3 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                    >
                        جست‌وجو
                    </button>
                </form>
            </div>

            {{-- Empty State --}}
            @if ($inquiries->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#EEE8E5] bg-[#FAF8F7] text-[#A99D98]">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    <p class="mt-4 text-[#362727] kohan-bold">
                        درخواستی پیدا نشد
                    </p>
                    <p class="mt-2 text-sm text-[#9A8E89]">
                        در حال حاضر موردی برای نمایش وجود ندارد.
                    </p>
                </div>
            @else

                {{-- Inquiries Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-right text-sm">

                        <thead class="bg-[#FAF8F7] text-xs text-[#827875]">
                            <tr>
                                <th class="px-5 py-4 font-medium">ردیف</th>
                                <th class="px-5 py-4 font-medium">نام و نام خانوادگی</th>
                                <th class="px-5 py-4 font-medium">موضوع درخواست</th>
                                <th class="px-5 py-4 font-medium">شماره تماس</th>
                                <th class="px-5 py-4 font-medium">تاریخ ثبت</th>
                                <th class="px-5 py-4 text-center font-medium">وضعیت</th>
                                <th class="px-5 py-4 text-center font-medium">جزئیات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#F0EBE8]">

                            @foreach ($inquiries as $inquiry)
                                <tr
                                    class="inquiry-row cursor-pointer transition hover:bg-[#FAF8F7]"
                                    data-inquiry-url="{{ route('admin.inquiries.show', $inquiry) }}"
                                    data-inquiry-id="{{ $inquiry->id }}"
                                    tabindex="0"
                                    role="button"
                                    aria-label="مشاهده درخواست {{ $inquiry->full_name }}"
                                >
                                    <td class="px-5 py-4 text-[#A99D98]">
                                        {{ $inquiries->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-[#362727] kohan-bold">
                                            {{ $inquiry->full_name }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-[#6F625E]">
                                        @switch($inquiry->subject)
                                            @case('product')
                                                استعلام محصولات
                                                @break
                                            @case('technical')
                                                مشاوره فنی
                                                @break
                                            @case('cooperation')
                                                درخواست همکاری
                                                @break
                                            @default
                                                سایر موارد
                                        @endswitch
                                    </td>

                                    <td class="px-5 py-4 text-[#6F625E]" dir="ltr">
                                        {{ $inquiry->phone }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-[#827875]">
                                        {{ $inquiry->created_at->format('Y/m/d') }}
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @if ($inquiry->is_read)
                                            <span
                                                class="inline-flex items-center justify-center rounded-lg bg-emerald-50 p-2 text-emerald-600"
                                                title="مشاهده‌شده"
                                                aria-label="مشاهده‌شده"
                                            >
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center rounded-lg bg-amber-50 p-2 text-amber-600"
                                                title="مشاهده‌نشده"
                                                aria-label="مشاهده‌نشده"
                                            >
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <button
                                            type="button"
                                            class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-[#E8E1DE] bg-white px-3 py-2 text-xs text-[#674040] transition hover:border-[#d4c1bd] hover:bg-[#FAF8F7]"
                                        >
                                            مشاهده
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                                <circle cx="12" cy="12" r="3"/>
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
                    {{ $inquiries->links() }}
                </div>

            @endif
        </div>
    </section>

    {{-- Inquiry Details Modal --}}
    <div
        id="inquiry-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-[#362727]/50 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-[0_24px_80px_rgba(54,39,39,0.2)]"
            role="dialog"
            aria-modal="true"
            aria-labelledby="inquiry-modal-title"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#EEE8E5] bg-[#FAF8F7] px-5 py-4 sm:px-6">
                <div>
                    <h2 id="inquiry-modal-title" class="text-lg text-[#362727] kohan-bold">
                        جزئیات درخواست
                    </h2>
                    <p class="mt-1 text-xs text-[#9A8E89]">
                        اطلاعات ارسال‌شده از فرم وب‌سایت
                    </p>
                </div>

                <button
                    type="button"
                    id="close-inquiry-modal"
                    class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-[#E8E1DE] bg-white text-[#827875] transition hover:border-[#d4c1bd] hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                    aria-label="بستن پنجره"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div
                id="inquiry-modal-content"
                class="max-h-[65vh] space-y-5 overflow-y-auto p-5 sm:p-6"
            >
                <div class="py-8 text-center text-sm text-[#9A8E89]">
                    در حال دریافت اطلاعات...
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex justify-end border-t border-[#EEE8E5] bg-[#FAF8F7] px-5 py-4 sm:px-6">
                <button
                    type="button"
                    id="close-inquiry-modal-footer"
                    class="cursor-pointer rounded-xl bg-[#542b2e] px-5 py-2.5 text-sm text-white transition hover:bg-[#674040] kohan-bold"
                >
                    بستن
                </button>
            </div>
        </div>
    </div>

@endsection

    @push('scripts')

    <script>
        (() => {
            const modal = document.getElementById('inquiry-modal');
            const modalContent = document.getElementById('inquiry-modal-content');

            const closeButtons = [
                document.getElementById('close-inquiry-modal'),
                document.getElementById('close-inquiry-modal-footer'),
            ];

            if (!modal || !modalContent) return;

            let lastFocusedElement = null;
            let activeRequest = null;

            const subjectLabels = {
                product: 'استعلام محصولات',
                technical: 'مشاوره فنی',
                cooperation: 'درخواست همکاری',
                other: 'سایر موارد',
            };

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[char]);

            const formatDate = (value) => {
                if (!value) return '—';

                const date = new Date(value);

                if (Number.isNaN(date.getTime())) return '—';

                return new Intl.DateTimeFormat('fa-IR-u-ca-persian', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                }).format(date);
            };

            const openModal = () => {
                lastFocusedElement = document.activeElement;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');

                document.body.classList.add('overflow-hidden');

                document.getElementById('close-inquiry-modal')?.focus();
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');

                document.body.classList.remove('overflow-hidden');

                if (activeRequest) {
                    activeRequest.abort();
                    activeRequest = null;
                }

                if (lastFocusedElement instanceof HTMLElement) {
                    lastFocusedElement.focus();
                }
            };

            // Render inquiry details
            const renderDetails = (inquiry) => {
                const subject = subjectLabels[inquiry.subject] || subjectLabels.other;

                modalContent.innerHTML = `
                    <div class="grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                            <p class="text-xs text-[#9A8E89]">نام و نام خانوادگی</p>
                            <p class="mt-2 break-words text-sm text-[#362727] kohan-bold">
                                ${escapeHtml(inquiry.full_name)}
                            </p>
                        </div>

                        <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4">
                            <p class="text-xs text-[#9A8E89]">شماره تماس</p>
                            <p class="mt-2 text-sm text-[#362727] kohan-bold" dir="ltr">
                                ${escapeHtml(inquiry.phone)}
                            </p>
                        </div>

                        <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4 sm:col-span-2">
                            <p class="text-xs text-[#9A8E89]">موضوع درخواست</p>
                            <p class="mt-2 text-sm text-[#542b2e] kohan-bold">
                                ${escapeHtml(subject)}
                            </p>
                        </div>

                        <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4 sm:col-span-2">
                            <p class="text-xs text-[#9A8E89]">توضیحات درخواست</p>
                            <p class="mt-3 whitespace-pre-wrap break-words text-sm leading-8 text-[#5F514D]">
                                ${escapeHtml(inquiry.message || 'توضیحی ثبت نشده است.')}
                            </p>
                        </div>

                        <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] p-4 sm:col-span-2">
                            <p class="text-xs text-[#9A8E89]">تاریخ ثبت</p>
                            <p class="mt-2 text-sm text-[#5F514D]">
                                ${escapeHtml(formatDate(inquiry.created_at))}
                            </p>
                        </div>

                    </div>
                `;
            };

            // Render error message
            const showError = (message) => {
                modalContent.innerHTML = `
                    <div class="rounded-xl border border-red-200 bg-red-50 p-5 text-sm leading-7 text-red-700">
                        ${escapeHtml(message)}
                    </div>
                `;
            };

            // Render loading message
            const showLoading = () => {
                modalContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center gap-3 py-10">
                        <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#E8E1DE] border-t-[#542b2e]"></div>
                        <p class="text-sm text-[#9A8E89]">در حال دریافت اطلاعات...</p>
                    </div>
                `;
            };

            // Update inquiry status in table
            const markAsRead = (row) => {
                const statusCell = row.children[5];

                if (statusCell) {
                    statusCell.innerHTML = `
                        <span
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-50 p-2 text-emerald-600"
                            title="مشاهده‌شده"
                            aria-label="مشاهده‌شده"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4 12 4 4L20 4"/>
                            </svg>
                        </span>
                    `;
                }

                const unreadStat = document.querySelector('[data-unread-stat]');

                if (unreadStat && !row.dataset.wasRead) {
                    const currentCount = Number(unreadStat.dataset.unreadStat || 0);
                    const newCount = Math.max(0, currentCount - 1);

                    unreadStat.dataset.unreadStat = String(newCount);
                    unreadStat.textContent = new Intl.NumberFormat('fa-IR').format(newCount);

                    row.dataset.wasRead = 'true';
                }
            };

            // Fetch and display inquiry details
            const showInquiry = async (row) => {
                if (activeRequest) {
                    activeRequest.abort();
                }

                const controller = new AbortController();

                activeRequest = controller;

                showLoading();
                openModal();

                try {
                    const response = await fetch(row.dataset.inquiryUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: controller.signal,
                    });

                    const inquiry = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            inquiry.message || 'دریافت جزئیات درخواست انجام نشد.'
                        );
                    }

                    if (activeRequest !== controller) return;

                    renderDetails(inquiry);
                    markAsRead(row);

                } catch (error) {
                    if (error.name !== 'AbortError' && activeRequest === controller) {
                        showError(
                            error.message || 'خطایی هنگام دریافت اطلاعات رخ داد.'
                        );
                    }
                } finally {
                    if (activeRequest === controller) {
                        activeRequest = null;
                    }
                }
            };

            // Inquiry row click events
            document.querySelectorAll('.inquiry-row').forEach((row) => {

                row.addEventListener('click', (event) => {
                    if (event.target.closest('a, input, select, button')) {
                        if (!event.target.closest('button')) return;
                    }

                    showInquiry(row);
                });

                row.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        showInquiry(row);
                    }
                });

            });

            // Close buttons
            closeButtons.forEach((button) => {
                button?.addEventListener('click', closeModal);
            });

            // Close modal when clicking outside
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });

        })();
    </script>

@endpush