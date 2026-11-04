@extends('layouts.admin')

@section('content')

@php
    $manager = [
        'id' => $managerId,
        'name' => 'علی محمدی',
        'email' => 'ali.mohammadi@example.com',
        'phone' => '09123456789',
        'role' => 'مدیر محتوا',
        'status' => 'active',
        'created_at' => '۱۴۰۵/۰۶/۱۵',
        'last_login' => '۱۴۰۵/۰۸/۰۵ - ۱۰:۳۰',
        'avatar' => null,
    ];

    $permissions = [
        ['key' => 'content', 'label' => 'محتوای اصلی', 'active' => true],
        ['key' => 'products', 'label' => 'محصولات', 'active' => true],
        ['key' => 'articles', 'label' => 'مقالات', 'active' => true],
        ['key' => 'inquiries', 'label' => 'درخواست‌ها', 'active' => true],
        ['key' => 'customers', 'label' => 'مشتریان', 'active' => false],
        ['key' => 'invoices', 'label' => 'فاکتورها', 'active' => false],
        ['key' => 'financial-reports', 'label' => 'گزارش‌های مالی', 'active' => false],
        ['key' => 'activity-reports', 'label' => 'گزارش فعالیت‌ها', 'active' => true],
    ];

    $activities = [
        [
            'title' => 'ویرایش اطلاعات یک محصول',
            'description' => 'اطلاعات محصول پلی‌اتیلن به‌روزرسانی شد.',
            'date' => '۱۴۰۵/۰۸/۰۵',
            'time' => '۱۰:۲۵',
            'type' => 'edit',
        ],
        [
            'title' => 'انتشار مقاله جدید',
            'description' => 'مقاله جدیدی در بخش وبلاگ منتشر شد.',
            'date' => '۱۴۰۵/۰۸/۰۴',
            'time' => '۱۴:۱۰',
            'type' => 'publish',
        ],
        [
            'title' => 'ورود به پنل مدیریت',
            'description' => 'مدیر وارد حساب کاربری خود شد.',
            'date' => '۱۴۰۵/۰۸/۰۴',
            'time' => '۰۹:۱۵',
            'type' => 'login',
        ],
        [
            'title' => 'ویرایش محتوای صفحه اصلی',
            'description' => 'متن بخش درباره ما تغییر کرد.',
            'date' => '۱۴۰۵/۰۸/۰۲',
            'time' => '۱۱:۴۰',
            'type' => 'edit',
        ],
    ];

    $activePermissions = collect($permissions)->where('active', true)->count();
@endphp

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
                    مدیریت کاربران سیستم
                </span>

                <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                    پروفایل مدیر
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                    مشاهده اطلاعات حساب کاربری، سطح دسترسی‌ها و آخرین فعالیت‌های مدیر
                </p>
            </div>

            <a
                href="{{ route('admin.managers.index') }}"
                class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm text-white transition duration-300 hover:-translate-y-1 hover:bg-white/20 kohan-bold"
            >
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                    <path d="M19 12H5m7-7-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                بازگشت به مدیران
            </a>

        </div>
    </div>

    {{-- Profile Overview --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="p-5 sm:p-7">

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">

                    {{-- Avatar --}}
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[#E8E1DE] bg-[#F3E9E9] text-2xl text-[#674040] kohan-bold">
                        @if($manager['avatar'])
                            <img src="{{ $manager['avatar'] }}" alt="{{ $manager['name'] }}" class="h-full w-full object-cover">
                        @else
                            {{ mb_substr($manager['name'], 0, 1) }}
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-xl text-[#362727] kohan-bold">
                            {{ $manager['name'] }}
                        </h2>

                        <p class="mt-2 text-sm text-[#827875]">
                            {{ $manager['role'] }}
                        </p>

                        <p class="mt-1 break-all text-xs text-[#9A8E89]">
                            {{ $manager['email'] }}
                        </p>
                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-3">

                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        حساب فعال
                    </span>

                    <button
                        type="button"
                        id="open-permissions-modal"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-2.5 text-sm text-[#674040] transition hover:bg-[#F3E9E9] kohan-bold"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        دسترسی‌ها
                    </button>

                </div>

            </div>

            <div class="mt-7 grid grid-cols-1 gap-3 border-t border-[#EEE8E5] pt-6 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-xs text-[#9A8E89]">شناسه مدیر</p>
                    <p class="mt-2 text-sm text-[#362727] kohan-bold">#{{ $manager['id'] }}</p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">تاریخ عضویت</p>
                    <p class="mt-2 text-sm text-[#362727]">{{ $manager['created_at'] }}</p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">آخرین ورود</p>
                    <p class="mt-2 text-sm text-[#362727]">{{ $manager['last_login'] }}</p>
                </div>

            </div>

        </div>
    </section>

    {{-- Profile Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">دسترسی‌های فعال</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <p class="permission-count mt-4 text-2xl text-[#362727] kohan-bold">{{ $activePermissions }}</p>
            <p class="mt-1 text-xs text-[#9A8E89]">از {{ count($permissions) }} دسترسی موجود</p>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">فعالیت‌های ثبت‌شده</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 12h4l3-8 4 16 3-8h4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-2xl text-[#362727] kohan-bold">{{ count($activities) }}</p>
            <p class="mt-1 text-xs text-[#9A8E89]">فعالیت‌های اخیر نمونه</p>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">وضعیت حساب</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                        <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-lg text-emerald-700 kohan-bold">فعال</p>
            <p class="mt-1 text-xs text-[#9A8E89]">دسترسی به پنل مدیریت</p>
        </div>

    </div>

    {{-- Permissions and Activities --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Permissions --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-5 sm:px-6">
                <div>
                    <h3 class="text-base text-[#362727] kohan-bold">دسترسی‌های مدیر</h3>
                    <p class="mt-1 text-xs text-[#9A8E89]">سطح دسترسی تعیین‌شده برای این حساب</p>
                </div>

                <span class="rounded-lg bg-[#F3E9E9] px-3 py-1.5 text-xs text-[#674040] kohan-bold">
                    {{ $activePermissions }} از {{ count($permissions) }}
                </span>
            </div>

            <div class="divide-y divide-[#F0EBE8] px-5 sm:px-6">

                @foreach($permissions as $permission)
                    <div
                        class="flex items-center justify-between gap-3 py-4"
                        data-permission-row="{{ $permission['key'] }}"
                    >

                        <span class="text-sm text-[#5F514D]">
                            {{ $permission['label'] }}
                        </span>

                        <span
                            data-permission-status="{{ $permission['key'] }}"
                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs {{ $permission['active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-[#F5F2F0] text-[#9A8E89]' }}"
                        >
                            @if($permission['active'])
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                فعال
                            @else
                                <span class="h-1.5 w-1.5 rounded-full bg-[#BFB4AF]"></span>
                                غیرفعال
                            @endif
                        </span>

                    </div>
                @endforeach

            </div>
        </section>

        {{-- Recent Activities --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="border-b border-[#EEE8E5] px-5 py-5 sm:px-6">
                <h3 class="text-base text-[#362727] kohan-bold">آخرین فعالیت‌ها</h3>
                <p class="mt-1 text-xs text-[#9A8E89]">مروری بر فعالیت‌های اخیر این مدیر</p>
            </div>

            <div class="divide-y divide-[#F0EBE8] px-5 sm:px-6">

                @foreach($activities as $activity)
                    <div class="flex gap-4 py-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">

                            @if($activity['type'] === 'login')
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                    <path d="M10 17l5-5-5-5M15 12H3M12 3h6a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3h-6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @elseif($activity['type'] === 'publish')
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 3v12m-5-5 5 5 5-5M5 17v3h14v-3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif

                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-[#362727] kohan-bold">
                                {{ $activity['title'] }}
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[#827875]">
                                {{ $activity['description'] }}
                            </p>

                            <p class="mt-2 text-[11px] text-[#A99D98]">
                                {{ $activity['date'] }} - {{ $activity['time'] }}
                            </p>
                        </div>

                    </div>
                @endforeach

            </div>
        </section>

    </div>

    {{-- Permissions Modal --}}
    <div
        id="permissions-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="permissions-modal-title"
        >

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-5 sm:px-6">

                <div>
                    <h3 id="permissions-modal-title" class="text-lg text-[#362727] kohan-bold">
                        مدیریت دسترسی‌ها
                    </h3>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        دسترسی‌های موردنظر این مدیر را انتخاب کنید.
                    </p>
                </div>

                <button
                    type="button"
                    id="close-permissions-modal"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg text-[#827875] transition hover:bg-[#F3E9E9] hover:text-[#674040]"
                    aria-label="بستن"
                >
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>

            </div>

            {{-- Modal Body --}}
            <div class="max-h-[65vh] overflow-y-auto p-5 sm:p-6">

                <div class="mb-5 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4">
                    <p class="text-sm text-[#362727] kohan-bold">
                        {{ $manager['name'] }}
                    </p>
                    <p class="mt-1 text-xs text-[#827875]">
                        {{ $manager['email'] }}
                    </p>
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-sm text-[#362727] kohan-bold">
                        انتخاب دسترسی‌ها
                    </h4>

                    <span id="selected-permissions-count" class="text-xs text-[#827875]">
                        {{ $activePermissions }} از {{ count($permissions) }} انتخاب شده
                    </span>
                </div>

                <div class="space-y-3">

                    @foreach($permissions as $permission)
                        <label
                            class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-[#E8E1DE] bg-white p-4 transition hover:border-[#baadad] hover:bg-[#FAF8F7]"
                        >
                            <span class="text-sm text-[#5F514D]">
                                {{ $permission['label'] }}
                            </span>

                            <input
                                type="checkbox"
                                name="manager_permissions[]"
                                value="{{ $permission['key'] }}"
                                data-permission-checkbox
                                class="h-4 w-4 cursor-pointer accent-[#674040]"
                                {{ $permission['active'] ? 'checked' : '' }}
                            >
                        </label>
                    @endforeach

                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="flex flex-col-reverse gap-3 border-t border-[#EEE8E5] bg-[#FAF8F7] p-5 sm:flex-row sm:justify-end sm:px-6">

                <button
                    type="button"
                    id="cancel-permissions-modal"
                    class="cursor-pointer rounded-xl border border-[#E8E1DE] bg-white px-5 py-3 text-sm text-[#674040] transition hover:bg-[#F3E9E9]"
                >
                    انصراف
                </button>

                <button
                    type="button"
                    id="save-permissions"
                    class="cursor-pointer rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#8d6666] kohan-bold"
                >
                    ذخیره تغییرات
                </button>

            </div>

        </div>
    </div>

</div>
<script>
    (() => {
        const modal = document.getElementById('permissions-modal');
        const openButton = document.getElementById('open-permissions-modal');
        const closeButton = document.getElementById('close-permissions-modal');
        const cancelButton = document.getElementById('cancel-permissions-modal');
        const saveButton = document.getElementById('save-permissions');
        const checkboxes = Array.from(
            document.querySelectorAll('[data-permission-checkbox]')
        );
        const countElement = document.getElementById('selected-permissions-count');

        if (!modal || !openButton || !saveButton) return;

        const originalPermissions = new Map(
            checkboxes.map(checkbox => [checkbox.value, checkbox.checked])
        );

        const updateSelectedCount = () => {
            const selectedCount = checkboxes.filter(
                checkbox => checkbox.checked
            ).length;

            countElement.textContent =
                `${selectedCount} از ${checkboxes.length} انتخاب شده`;
        };

        const openModal = () => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = originalPermissions.get(checkbox.value);
            });

            updateSelectedCount();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        openButton.addEventListener('click', openModal);

        closeButton.addEventListener('click', closeModal);
        cancelButton.addEventListener('click', closeModal);

        modal.addEventListener('click', event => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        saveButton.addEventListener('click', () => {
            checkboxes.forEach(checkbox => {
                const key = checkbox.value;
                const isActive = checkbox.checked;

                originalPermissions.set(key, isActive);

                const statusElement = document.querySelector(
                    `[data-permission-status="${key}"]`
                );

                if (!statusElement) return;

                statusElement.className = isActive
                    ? 'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs bg-emerald-50 text-emerald-700'
                    : 'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs bg-[#F5F2F0] text-[#9A8E89]';

                statusElement.innerHTML = isActive
                    ? '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>فعال'
                    : '<span class="h-1.5 w-1.5 rounded-full bg-[#BFB4AF]"></span>غیرفعال';
            });

            const selectedCount = checkboxes.filter(
                checkbox => checkbox.checked
            ).length;

            const permissionCount = document.querySelector(
                '.permission-count'
            );

            if (permissionCount) {
                permissionCount.textContent = selectedCount;
            }

            closeModal();
        });
    })();
</script>
@endsection