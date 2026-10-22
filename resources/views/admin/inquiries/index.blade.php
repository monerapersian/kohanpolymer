@extends('layouts.admin')

@section('title', 'مدیریت درخواست‌ها | کهن پلیمر البرز')

@section('content')
    <section class="space-y-6">

        {{-- Page Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#222222]/80 p-6 shadow-2xl sm:p-8">
            <div class="absolute -left-16 -top-20 h-56 w-56 rounded-full bg-[#681b21]/30 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-white/60">
                        <span class="h-2 w-2 rounded-full bg-[#681b21]"></span>
                        ارتباط با مشتریان
                    </span>

                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                        مدیریت درخواست‌ها
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/55">
                        درخواست‌های ثبت‌شده در وب‌سایت را مشاهده و پیگیری کن.
                    </p>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#681b21]/25 text-[#e8b8bb]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8l-9 6-9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs text-white/45">کل درخواست‌ها</p>
                        <p class="mt-1 text-xl font-extrabold">{{ number_format($totalInquiries) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-[#222222]/75 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-white/55">همه درخواست‌ها</p>
                        <p class="mt-3 text-3xl font-extrabold">{{ number_format($totalInquiries) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white/80">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-amber-300/15 bg-[#222222]/75 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-white/55">درخواست‌های مشاهده‌نشده</p>
                        <p data-unread-stat="{{ $unreadInquiries }}" class="mt-3 text-3xl font-extrabold text-amber-200">
                            {{ number_format($unreadInquiries) }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-300/10 text-amber-200">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Inquiry List --}}
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#222222]/80 shadow-xl">
            <div class="flex flex-col gap-4 border-b border-white/10 p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold">فهرست درخواست‌ها</h2>
                    <p class="mt-1 text-sm text-white/45">برای مشاهده جزئیات، روی هر درخواست کلیک کن.</p>
                </div>

                <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative">
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="m16 16 4 4"/>
                        </svg>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="جست‌وجوی نام یا شماره تماس"
                            class="w-full rounded-xl border border-white/10 bg-white/5 py-3 pr-10 pl-4 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#a64c53] sm:w-64"
                        >
                    </div>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-white/10 bg-[#292929] px-4 py-3 text-sm text-white outline-none focus:border-[#a64c53] cursor-pointer"
                    >
                        <option value="">همه درخواست‌ها</option>
                        <option value="unread" @selected(request('status') === 'unread')>مشاهده‌نشده</option>
                        <option value="read" @selected(request('status') === 'read')>مشاهده‌شده</option>
                    </select>

                    <button type="submit" class="rounded-xl bg-[#681b21] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#81272e] kohan-bold cursor-pointer">
                        جست‌وجو
                    </button>
                </form>
            </div>

            @if ($inquiries->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white/40">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>

                    <p class="mt-4 font-bold">درخواستی پیدا نشد</p>
                    <p class="mt-2 text-sm text-white/45">در حال حاضر موردی برای نمایش وجود ندارد.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-right text-sm">
                        <thead class="bg-white/[0.035] text-xs text-white/45">
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

                        <tbody class="divide-y divide-white/[0.07]">
                            @foreach ($inquiries as $inquiry)
                                <tr
                                    class="inquiry-row cursor-pointer transition hover:bg-white/[0.035]"
                                    data-inquiry-url="{{ route('admin.inquiries.show', $inquiry) }}"
                                    data-inquiry-id="{{ $inquiry->id }}"
                                    tabindex="0"
                                    role="button"
                                    aria-label="مشاهده درخواست {{ $inquiry->full_name }}"
                                >
                                    <td class="px-5 py-4 text-white/40">
                                        {{ $inquiries->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-white kohan-bold">{{ $inquiry->full_name }}</span>
                                    </td>

                                    <td class="px-5 py-4 text-white/65">
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

                                    <td class="px-5 py-4 text-white/65" dir="ltr">
                                        {{ $inquiry->phone }}
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap text-white/55">
                                        {{ $inquiry->created_at->format('Y/m/d') }}
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @if ($inquiry->is_read)
                                            <span class="inline-flex items-center justify-center text-emerald-300" title="مشاهده‌شده" aria-label="مشاهده‌شده">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center text-amber-200" title="مشاهده‌نشده" aria-label="مشاهده‌نشده">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-white/75 transition hover:bg-white/10 ">
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

                <div class="border-t border-white/10 px-5 py-4">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- Inquiry Details Modal --}}
    <div
        id="inquiry-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="inquiry-modal-title">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-6">
                <div>
                    <h2 id="inquiry-modal-title" class="text-lg font-bold">جزئیات درخواست</h2>
                    <p class="mt-1 text-xs text-white/45">اطلاعات ارسال‌شده از فرم وب‌سایت</p>
                </div>

                <button type="button" id="close-inquiry-modal" class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-white/60 transition hover:bg-white/10 hover:text-white cursor-pointer" aria-label="بستن پنجره">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18"/>
                    </svg>
                </button>
            </div>

            <div id="inquiry-modal-content" class="space-y-5 p-5 sm:p-6">
                <div class="py-8 text-center text-sm text-white/50">در حال دریافت اطلاعات...</div>
            </div>

            <div class="flex justify-end border-t border-white/10 px-5 py-4 sm:px-6">
                <button type="button" id="close-inquiry-modal-footer" class="rounded-xl bg-white/10 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15 kohan-bold cursor-pointer">
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

            const renderDetails = (inquiry) => {
                const subject = subjectLabels[inquiry.subject] || subjectLabels.other;

                modalContent.innerHTML = `
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4">
                            <p class="text-xs text-white/45">نام و نام خانوادگی</p>
                            <p class="mt-2 break-words text-sm font-semibold text-white kohan-bold">${escapeHtml(inquiry.full_name)}</p>
                        </div>

                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4">
                            <p class="text-xs text-white/45">شماره تماس</p>
                            <p class="mt-2 text-sm font-semibold text-white kohan-bold" dir="ltr">${escapeHtml(inquiry.phone)}</p>
                        </div>

                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4 sm:col-span-2">
                            <p class="text-xs text-white/45">موضوع درخواست</p>
                            <p class="mt-2 text-sm font-semibold text-white kohan-bold">${escapeHtml(subject)}</p>
                        </div>

                        <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4 sm:col-span-2">
                            <p class="text-xs text-white/45">توضیحات درخواست</p>
                            <p class="mt-3 whitespace-pre-wrap break-words text-sm leading-8 text-white/80">${escapeHtml(inquiry.message || 'توضیحی ثبت نشده است.')}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs text-white/45">تاریخ ثبت</p>
                            <p class="mt-2 text-sm text-white/75">${escapeHtml(formatDate(inquiry.created_at))}</p>
                        </div>
                    </div>
                `;
            };

            const showError = (message) => {
                modalContent.innerHTML = `
                    <div class="rounded-xl border border-red-400/20 bg-red-400/5 p-5 text-sm leading-7 text-red-200">
                        ${escapeHtml(message)}
                    </div>
                `;
            };

            const showInquiry = async (row) => {
                if (activeRequest) activeRequest.abort();

                activeRequest = new AbortController();

                modalContent.innerHTML = '<div class="py-8 text-center text-sm text-white/50">در حال دریافت اطلاعات...</div>';
                openModal();

                try {
                    const response = await fetch(row.dataset.inquiryUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: activeRequest.signal,
                    });

                    const inquiry = await response.json();

                    if (!response.ok) {
                        throw new Error(inquiry.message || 'دریافت جزئیات درخواست انجام نشد.');
                    }

                    renderDetails(inquiry);

                    const statusCell = row.children[5];

                    if (statusCell) {
                        statusCell.innerHTML = `
                            <span class="inline-flex items-center justify-center text-emerald-300" title="مشاهده‌شده" aria-label="مشاهده‌شده">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 12 4 4L20 4"/>
                                </svg>
                            </span>
                        `;
                    }

                    const unreadStat = document.querySelector('[data-unread-stat]');

                    if (unreadStat && !row.dataset.wasRead) {
                        const currentCount = Number(unreadStat.dataset.unreadStat || 0);
                        unreadStat.dataset.unreadStat = String(Math.max(0, currentCount - 1));
                        unreadStat.textContent = new Intl.NumberFormat('fa-IR').format(Math.max(0, currentCount - 1));
                        row.dataset.wasRead = 'true';
                    }
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        showError(error.message || 'خطایی هنگام دریافت اطلاعات رخ داد.');
                    }
                } finally {
                    activeRequest = null;
                }
            };

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

            closeButtons.forEach((button) => {
                button?.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>

@endpush