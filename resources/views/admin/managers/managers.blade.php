@extends('layouts.admin')

@section('content')

<div id="managers-page" class="space-y-7">

    @php
        $permissions = [
            'site_content' => 'محتوای اصلی',
            'products' => 'محصولات',
            'blog_posts' => 'مقالات',
            'inquiries' => 'درخواست‌ها',
            'customers' => 'مشتریان',
            'invoices' => 'فاکتورها',
            'financial_reports' => 'گزارش‌های مالی',
            'activity_logs' => 'گزارش فعالیت‌ها',
        ];

        $managers = [
            [
                'id' => 1,
                'name' => 'علی محمدی',
                'email' => 'ali.mohammadi@example.com',
                'image' => null,
                'status' => 'active',
                'permissions' => ['site_content', 'products', 'blog_posts', 'inquiries'],
                'last_activity' => 'امروز، ساعت ۱۰:۳۰',
            ],
            [
                'id' => 2,
                'name' => 'محمد رضایی',
                'email' => 'm.rezaei@example.com',
                'image' => null,
                'status' => 'active',
                'permissions' => ['customers', 'invoices', 'financial_reports'],
                'last_activity' => 'امروز، ساعت ۰۹:۱۵',
            ],
            [
                'id' => 3,
                'name' => 'سارا احمدی',
                'email' => 's.ahmadi@example.com',
                'image' => null,
                'status' => 'active',
                'permissions' => ['blog_posts', 'products', 'activity_logs'],
                'last_activity' => 'دیروز، ساعت ۱۶:۴۵',
            ],
            [
                'id' => 4,
                'name' => 'رضا کریمی',
                'email' => 'reza.karimi@example.com',
                'image' => null,
                'status' => 'inactive',
                'permissions' => ['inquiries', 'customers'],
                'last_activity' => '۳ روز پیش',
            ],
        ];

        $totalManagers = count($managers);
        $activeManagers = count(array_filter($managers, fn ($manager) => $manager['status'] === 'active'));
        $totalPermissions = count($permissions);
    @endphp

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
                    مدیریت کاربران سیستم
                </span>

                <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                    مدیران و دسترسی‌ها
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                    مدیریت مدیران پنل، تعیین سطح دسترسی‌ها و بررسی فعالیت کاربران سیستم
                </p>
            </div>

            <button
                type="button"
                id="open-manager-modal"
                class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#8d6666] px-5 py-3 text-sm text-white kohan-bold transition duration-300 hover:bg-[#a07878] hover:shadow-lg hover:shadow-black/20"
            >
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                </svg>
                افزودن مدیر جدید
            </button>
        </div>
    </div>

    {{-- Statistics --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">تعداد کل مدیران</p>
                    <p class="kohan-bold mt-3 text-3xl text-[#362727]">{{ $totalManagers }}</p>
                    <p class="mt-2 text-[11px] text-[#9A8E89]">مدیر ثبت‌شده در سیستم</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#674040]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">مدیران فعال</p>
                    <p class="kohan-bold mt-3 text-3xl text-[#362727]">{{ $activeManagers }}</p>
                    <p class="mt-2 text-[11px] text-[#9A8E89]">دارای وضعیت فعال</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EAF3EC] text-[#477B55]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md sm:col-span-2 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#827875]">بخش‌های قابل دسترسی</p>
                    <p class="kohan-bold mt-3 text-3xl text-[#362727]">{{ $totalPermissions }}</p>
                    <p class="mt-2 text-[11px] text-[#9A8E89]">سطوح دسترسی تعریف‌شده</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F5F0E9] text-[#8D6666]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <path d="M8 9h8M8 13h5"/>
                    </svg>
                </div>
            </div>
        </div>

    </section>

    {{-- Managers List --}}
    <section class="space-y-5">

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="kohan-bold text-lg text-[#362727]">لیست مدیران</h2>
                <p class="mt-1 text-xs text-[#9A8E89]">مدیران پنل و سطح دسترسی آن‌ها</p>
            </div>

            <div class="relative w-full sm:max-w-xs">
                <svg class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#9A8E89]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>

                <input
                    type="search"
                    id="manager-search"
                    placeholder="جستجوی نام مدیر..."
                    class="w-full rounded-xl border border-[#E8E1DE] bg-white py-3 pr-10 pl-4 text-xs text-[#362727] outline-none transition-all placeholder:text-[#A99D98] focus:border-[#8D6666] focus:ring-4 focus:ring-[#8D6666]/10"
                >
            </div>
        </div>

        <div id="managers-grid" class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

            @foreach ($managers as $manager)
                <article
                    class="manager-card group overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#BAADAD] hover:shadow-[0_14px_35px_rgba(54,39,39,0.08)]"
                    data-name="{{ $manager['name'] }}"
                >

                    <div class="h-1 bg-gradient-to-l from-[#674040] via-[#8D6666] to-[#BAADAD]"></div>

                    <div class="p-5">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[#E8E1DE] bg-[#F3E9E9] text-lg text-[#674040]">
                                    @if ($manager['image'])
                                        <img src="{{ $manager['image'] }}" alt="{{ $manager['name'] }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="kohan-bold">{{ mb_substr($manager['name'], 0, 1) }}</span>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <h3 class="kohan-bold truncate text-sm text-[#362727]">
                                        {{ $manager['name'] }}
                                    </h3>
                                    <p class="mt-1 truncate text-[11px] text-[#9A8E89]" dir="ltr">
                                        {{ $manager['email'] }}
                                    </p>
                                </div>

                            </div>

                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] {{ $manager['status'] === 'active' ? 'bg-[#EAF3EC] text-[#477B55]' : 'bg-[#F3EEEE] text-[#8D6666]' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $manager['status'] === 'active' ? 'bg-[#57956A]' : 'bg-[#A98B8B]' }}"></span>
                                {{ $manager['status'] === 'active' ? 'فعال' : 'غیرفعال' }}
                            </span>

                        </div>

                        <div class="my-5 flex items-center justify-between border-y border-[#F0EBE8] py-3">
                            <span class="text-[11px] text-[#827875]">دسترسی‌های اختصاص‌یافته</span>
                            <span class="kohan-bold text-sm text-[#674040]">{{ count($manager['permissions']) }} از {{ $totalPermissions }}</span>
                        </div>

                        <div class="flex min-h-[66px] flex-wrap content-start gap-2">
                            @foreach ($manager['permissions'] as $permission)
                                <span class="rounded-lg border border-[#E8E1DE] bg-[#FAF8F7] px-2.5 py-1.5 text-[10px] text-[#716966]">
                                    {{ $permissions[$permission] }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-[#F0EBE8] pt-4">
                            <div>
                                <p class="text-[10px] text-[#A99D98]">آخرین فعالیت</p>
                                <p class="mt-1 text-[11px] text-[#716966]">{{ $manager['last_activity'] }}</p>
                            </div>

                            <a
                                href="#"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-[#F3E9E9] px-3 py-2 text-[11px] text-[#674040] transition-all hover:bg-[#674040] hover:text-white"
                            >
                                <span>مشاهده پروفایل</span>
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </article>
            @endforeach

        </div>

        <div id="manager-empty-state" class="hidden rounded-2xl border border-dashed border-[#D8CBC7] bg-white px-5 py-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#8D6666]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>
            </div>
            <h3 class="kohan-bold mt-4 text-sm text-[#362727]">مدیری پیدا نشد</h3>
            <p class="mt-2 text-xs text-[#9A8E89]">عبارت جستجو را تغییر دهید و دوباره امتحان کنید.</p>
        </div>

    </section>

</div>

{{-- Add Manager Modal --}}
<div
    id="manager-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
    aria-hidden="true"
>
    <div
        class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-white/10 bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="manager-modal-title"
    >

        <div class="flex items-center justify-between border-b border-[#E8E1DE] bg-[#FAF8F7] px-5 py-4 sm:px-6">
            <div>
                <h2 id="manager-modal-title" class="kohan-bold text-base text-[#362727]">افزودن مدیر جدید</h2>
                <p class="mt-1 text-[11px] text-[#9A8E89]">اطلاعات و دسترسی‌های مدیر را مشخص کنید.</p>
            </div>

            <button
                type="button"
                id="close-manager-modal"
                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl text-[#827875] transition-colors hover:bg-[#F3E9E9] hover:text-[#674040]"
                aria-label="بستن"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="m18 6-12 12M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="manager-form" class="max-h-[calc(100vh-160px)] space-y-6 overflow-y-auto p-5 sm:p-6">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label for="manager-name" class="mb-2 block text-xs text-[#5F514D]">نام و نام خانوادگی</label>
                    <input
                        type="text"
                        id="manager-name"
                        name="name"
                        required
                        placeholder="نام مدیر را وارد کنید"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-xs text-[#362727] outline-none transition-all placeholder:text-[#A99D98] focus:border-[#8D6666] focus:ring-4 focus:ring-[#8D6666]/10"
                    >
                </div>

                <div>
                    <label for="manager-email" class="mb-2 block text-xs text-[#5F514D]">آدرس ایمیل</label>
                    <input
                        type="email"
                        id="manager-email"
                        name="email"
                        required
                        dir="ltr"
                        placeholder="manager@example.com"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-left text-xs text-[#362727] outline-none transition-all placeholder:text-[#A99D98] focus:border-[#8D6666] focus:ring-4 focus:ring-[#8D6666]/10"
                    >
                </div>

                <div class="sm:col-span-2">
                    <label for="manager-password" class="mb-2 block text-xs text-[#5F514D]">رمز عبور</label>
                    <input
                        type="password"
                        id="manager-password"
                        name="password"
                        required
                        minlength="8"
                        dir="ltr"
                        placeholder="حداقل ۸ کاراکتر"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-left text-xs text-[#362727] outline-none transition-all placeholder:text-[#A99D98] focus:border-[#8D6666] focus:ring-4 focus:ring-[#8D6666]/10"
                    >
                </div>

            </div>

            <div class="border-t border-[#F0EBE8] pt-5">

                <div class="mb-4 flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="kohan-bold text-sm text-[#362727]">تعیین سطح دسترسی</h3>
                        <p class="mt-1 text-[11px] text-[#9A8E89]">بخش‌هایی را که مدیر مجاز به دسترسی است انتخاب کنید.</p>
                    </div>

                    <span id="selected-permissions-count" class="rounded-lg bg-[#F3E9E9] px-3 py-1.5 text-[10px] text-[#674040]">
                        ۰ از ۸ دسترسی انتخاب شده
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    @foreach ($permissions as $key => $label)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-3 py-3 transition-all hover:border-[#BAADAD] hover:bg-[#F3E9E9]/50">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $key }}"
                                class="manager-permission h-4 w-4 cursor-pointer accent-[#674040]"
                            >
                            <span class="text-xs text-[#5F514D]">{{ $label }}</span>
                        </label>
                    @endforeach

                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-[10px] text-[#9A8E89]">انتخاب دسترسی‌ها اختیاری است.</span>
                    <button type="button" id="clear-manager-permissions" class="cursor-pointer text-[10px] text-[#8D6666] transition-colors hover:text-[#362727]">
                        حذف همه انتخاب‌ها
                    </button>
                </div>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#F0EBE8] pt-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="cancel-manager-modal"
                    class="cursor-pointer rounded-xl border border-[#E8E1DE] px-5 py-3 text-xs text-[#716966] transition-colors hover:bg-[#FAF8F7]"
                >
                    انصراف
                </button>

                <button
                    type="submit"
                    class="kohan-bold cursor-pointer rounded-xl bg-[#542b2e] px-6 py-3 text-xs text-white transition-all hover:bg-[#674040] active:scale-[0.98]"
                >
                    ثبت مدیر
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    (() => {
        const page = document.getElementById('managers-page');

        if (!page) return;

        const modal = document.getElementById('manager-modal');
        const openButton = document.getElementById('open-manager-modal');
        const closeButton = document.getElementById('close-manager-modal');
        const cancelButton = document.getElementById('cancel-manager-modal');
        const form = document.getElementById('manager-form');

        const searchInput = document.getElementById('manager-search');
        const managerCards = Array.from(document.querySelectorAll('.manager-card'));
        const emptyState = document.getElementById('manager-empty-state');

        const permissionCheckboxes = Array.from(
            document.querySelectorAll('.manager-permission')
        );

        const selectedCount = document.getElementById('selected-permissions-count');
        const clearPermissionsButton = document.getElementById('clear-manager-permissions');

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            setTimeout(() => {
                document.getElementById('manager-name')?.focus();
            }, 100);
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        openButton?.addEventListener('click', openModal);
        closeButton?.addEventListener('click', closeModal);
        cancelButton?.addEventListener('click', closeModal);

        modal?.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        searchInput?.addEventListener('input', () => {
            const query = searchInput.value.trim().toLocaleLowerCase('fa');

            let visibleCount = 0;

            managerCards.forEach((card) => {
                const name = card.dataset.name.toLocaleLowerCase('fa');
                const isVisible = name.includes(query);

                card.classList.toggle('hidden', !isVisible);

                if (isVisible) visibleCount++;
            });

            emptyState.classList.toggle('hidden', visibleCount !== 0);
        });

        const updatePermissionCount = () => {
            const count = permissionCheckboxes.filter((checkbox) => checkbox.checked).length;

            selectedCount.textContent = `${count} از ${permissionCheckboxes.length} دسترسی انتخاب شده`;
        };

        permissionCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updatePermissionCount);
        });

        clearPermissionsButton?.addEventListener('click', () => {
            permissionCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            updatePermissionCount();
        });

        form?.addEventListener('submit', (event) => {
            event.preventDefault();

            alert('قالب فرم آماده است؛ ثبت و ذخیره مدیر در مرحله اتصال به دیتابیس پیاده‌سازی می‌شود.');
        });
    })();
</script>

@endsection