@extends('layouts.admin')

@section('content')

@php
    $selectedPermissions = old(
        'permissions',
        $managerModel->permissions ?? []
    );

    if (!is_array($selectedPermissions)) {
        $selectedPermissions = [];
    }

    $isActive = $manager['status'] === 'active';
    $createdAt = $managerModel->created_at;

    $activityActionLabels = [
        'login' => 'ورود به سیستم',
        'logout' => 'خروج از سیستم',
        'profile_updated' => 'ویرایش پروفایل',
        'account_deleted' => 'حذف حساب کاربری',
        'password_changed' => 'تغییر رمز عبور',
        'password_reset' => 'بازیابی رمز عبور',
        'password_reset_link_sent' => 'ارسال لینک بازیابی رمز عبور',
        'password_confirmed' => 'تأیید رمز عبور',
        'email_verified' => 'تأیید ایمیل',
        'verification_link_sent' => 'ارسال لینک تأیید ایمیل',
        'user_registered' => 'ثبت‌نام کاربر',

        'product_created' => 'ایجاد محصول',
        'product_updated' => 'ویرایش محصول',
        'product_deleted' => 'حذف محصول',

        'blog_post_created' => 'ایجاد مقاله',
        'blog_post_updated' => 'ویرایش مقاله',
        'blog_post_deleted' => 'حذف مقاله',

        'site_content_updated' => 'ویرایش محتوای سایت',

        'customer_created' => 'ایجاد مشتری',
        'customer_updated' => 'ویرایش مشتری',

        'invoice_created' => 'صدور فاکتور',
        'proforma_created' => 'صدور پیش‌فاکتور',
        'invoice_voided' => 'ابطال فاکتور',
        'proforma_voided' => 'ابطال پیش‌فاکتور',
        'proforma_converted' => 'تبدیل پیش‌فاکتور به فاکتور',

        'manager_created' => 'ایجاد مدیر',
        'manager_permissions_updated' => 'تغییر دسترسی‌های مدیر',

        'inquiry_received' => 'دریافت درخواست',
        'inquiry_marked_read' => 'علامت‌گذاری درخواست به‌عنوان خوانده‌شده',
    ];

    $activityPropertyLabels = [
        'name' => 'نام',
        'title' => 'عنوان',
        'status' => 'وضعیت',
        'old_status' => 'وضعیت قبلی',
        'new_status' => 'وضعیت جدید',
        'type' => 'نوع',
        'is_formal' => 'فاکتور رسمی',
        'is_featured' => 'محصول ویژه',
        'has_image' => 'دارای تصویر',
        'has_phone' => 'دارای شماره تماس',
        'items_count' => 'تعداد اقلام',
        'document_number' => 'شماره سند',
        'source_document_number' => 'شماره سند مبدأ',
        'new_document_number' => 'شماره سند جدید',
        'registration_method' => 'روش ثبت',
        'changed_fields' => 'فیلدهای تغییرکرده',
        'old' => 'مقدار قبلی',
        'new' => 'مقدار جدید',
        'old_is_read' => 'وضعیت قبلی مطالعه',
        'new_is_read' => 'وضعیت جدید مطالعه',
        'previous_status' => 'وضعیت قبلی',
        'source_proforma_id' => 'شناسه پیش‌فاکتور مبدأ',
        'new_invoice_id' => 'شناسه فاکتور جدید',
        'inquiry_id' => 'شناسه درخواست',
        'manager_id' => 'شناسه مدیر',
        'manager_name' => 'نام مدیر',
        'permissions' => 'دسترسی‌ها',
        'old_permissions' => 'دسترسی‌های قبلی',
        'new_permissions' => 'دسترسی‌های جدید',
    ];

    $permissionLabels = [
        'site_content' => 'محتوای اصلی',
        'products' => 'محصولات',
        'blog_posts' => 'مقالات',
        'inquiries' => 'درخواست‌ها',
        'customers' => 'مشتریان',
        'invoices' => 'فاکتورها',
        'financial_reports' => 'گزارش‌های مالی',
        'activity_logs' => 'گزارش فعالیت‌ها',
    ];

    $formatActivityValue = function ($value, $key = null) use ($permissionLabels) {
        if (is_bool($value)) {
            return $value ? 'بله' : 'خیر';
        }

        if (is_null($value)) {
            return 'ثبت نشده';
        }

        if (is_array($value)) {
            if ($key === 'permissions' || $key === 'old_permissions' || $key === 'new_permissions') {
                $labels = [];

                foreach ($value as $permission) {
                    if (is_string($permission)) {
                        $labels[] = $permissionLabels[$permission] ?? $permission;
                    }
                }

                return !empty($labels) ? implode('، ', $labels) : 'بدون دسترسی';
            }

            $items = [];

            foreach ($value as $itemKey => $itemValue) {
                if (is_array($itemValue)) {
                    $nestedItems = [];

                    foreach ($itemValue as $nestedKey => $nestedValue) {
                        if (is_scalar($nestedValue) || is_null($nestedValue)) {
                            $nestedItems[] = $nestedKey . ': ' . (
                                is_bool($nestedValue)
                                    ? ($nestedValue ? 'بله' : 'خیر')
                                    : (string) ($nestedValue ?? 'ثبت نشده')
                            );
                        }
                    }

                    if (!empty($nestedItems)) {
                        $items[] = implode('، ', $nestedItems);
                    }
                } elseif (is_scalar($itemValue) || is_null($itemValue)) {
                    $items[] = is_string($itemKey)
                        ? $itemKey . ': ' . (
                            is_bool($itemValue)
                                ? ($itemValue ? 'بله' : 'خیر')
                                : (string) ($itemValue ?? 'ثبت نشده')
                        )
                        : (string) ($itemValue ?? 'ثبت نشده');
                }
            }

            return !empty($items) ? implode('، ', $items) : 'موردی ثبت نشده';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return 'مقدار نامشخص';
    };
@endphp

<div class="space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="mb-2 kohan-bold">لطفاً خطاهای زیر را بررسی کنید:</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                            <img
                                src="{{ $manager['avatar'] }}"
                                alt="{{ $manager['name'] }}"
                                class="h-full w-full object-cover"
                            >
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

                    @if($isActive)
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            حساب فعال
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            حساب غیرفعال
                        </span>
                    @endif

                    <button
                        type="button"
                        id="open-permissions-modal"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-2.5 text-sm text-[#674040] transition hover:bg-[#F3E9E9] kohan-bold"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ویرایش دسترسی‌ها
                    </button>

                </div>

            </div>

            <div class="mt-7 grid grid-cols-1 gap-3 border-t border-[#EEE8E5] pt-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <p class="text-xs text-[#9A8E89]">شناسه مدیر</p>
                    <p class="mt-2 text-sm text-[#362727] kohan-bold">
                        #{{ $manager['id'] }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">تاریخ عضویت</p>
                    <p class="mt-2 text-sm text-[#362727]">
                        {{ $createdAt ? \App\Support\NumberHelper::toPersian(\App\Support\DateHelper::toPersian($createdAt, 'Y/m/d')) : 'ثبت نشده' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-[#9A8E89]">آخرین ورود</p>
                    <p class="mt-2 text-sm text-[#362727]">
                        {{ $manager['last_login'] }}
                    </p>
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

            <p class="permission-count mt-4 text-2xl text-[#362727] kohan-bold">
                {{ $activePermissions }}
            </p>

            <p class="mt-1 text-xs text-[#9A8E89]">
                از {{ count($permissions) }} دسترسی موجود
            </p>
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

            <p class="mt-4 text-2xl text-[#362727] kohan-bold">
                {{ \App\Support\NumberHelper::toPersian(number_format($activities->total())) }}
            </p>

            <p class="mt-1 text-xs text-[#9A8E89]">
                مجموع فعالیت‌های ثبت‌شده توسط این مدیر
            </p>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">وضعیت حساب</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                        <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-lg {{ $isActive ? 'text-emerald-700' : 'text-red-700' }} kohan-bold">
                {{ $isActive ? 'فعال' : 'غیرفعال' }}
            </p>

            <p class="mt-1 text-xs text-[#9A8E89]">
                {{ $isActive ? 'دارای دسترسی به پنل مدیریت' : 'بدون دسترسی فعال به پنل مدیریت' }}
            </p>
        </div>

    </div>

    {{-- Permissions and Activities --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Permissions --}}
        <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-[#EEE8E5] px-5 py-5 sm:px-6">
                <div>
                    <h3 class="text-base text-[#362727] kohan-bold">
                        دسترسی‌های مدیر
                    </h3>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        سطح دسترسی تعیین‌شده برای این حساب
                    </p>
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

            <div class="flex flex-col gap-2 border-b border-[#EEE8E5] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-base text-[#362727] kohan-bold">
                        آخرین فعالیت‌ها
                    </h3>

                    <p class="mt-1 text-xs text-[#9A8E89]">
                        فعالیت‌هایی که این مدیر در سیستم انجام داده است
                    </p>
                </div>

                <span class="inline-flex w-fit items-center rounded-lg bg-[#F3E9E9] px-3 py-1.5 text-xs text-[#674040] kohan-bold">
                    {{ \App\Support\NumberHelper::toPersian(number_format($activities->total())) }} فعالیت
                </span>
            </div>

            @if($activities->isNotEmpty())

                <div class="divide-y divide-[#F0EBE8] px-5 sm:px-6">

                    @foreach($activities as $activity)

                        @php
                            $actionLabel = $activityActionLabels[$activity->action]
                                ?? $activity->action;

                            $properties = is_array($activity->properties)
                                ? $activity->properties
                                : [];

                            $visibleProperties = array_intersect_key(
                                $properties,
                                $activityPropertyLabels
                            );

                            $activityDate = $activity->created_at
                                ? \App\Support\DateHelper::toPersian($activity->created_at, 'Y/m/d')
                                : null;

                            $activityTime = $activity->created_at
                                ? $activity->created_at->format('H:i')
                                : null;

                            $subjectType = $activity->subject_type
                                ? class_basename($activity->subject_type)
                                : null;
                        @endphp

                        <div class="flex gap-4 py-5">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm text-[#362727] kohan-bold">
                                    {{ $actionLabel }}
                                </p>

                                @if($activity->description)
                                    <p class="mt-1 text-xs leading-6 text-[#827875]">
                                        {{ $activity->description }}
                                    </p>
                                @endif

                                @if(!empty($visibleProperties))
                                    <div class="mt-3 space-y-2 rounded-xl border border-[#F0EBE8] bg-[#FAF8F7] p-3">

                                        @foreach($visibleProperties as $propertyKey => $propertyValue)
                                            <div class="flex flex-col gap-1 text-xs sm:flex-row sm:items-start sm:justify-between sm:gap-3">

                                                <span class="shrink-0 text-[#9A8E89]">
                                                    {{ $activityPropertyLabels[$propertyKey] }}
                                                </span>

                                                <span class="break-words text-[#5F514D] sm:text-left">
                                                    {{ $formatActivityValue($propertyValue, $propertyKey) }}
                                                </span>

                                            </div>
                                        @endforeach

                                    </div>
                                @endif

                                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-[#A99D98]">

                                    @if($activityDate)
                                        <span>
                                            {{ \App\Support\NumberHelper::toPersian($activityDate) }}
                                        </span>
                                    @endif

                                    @if($activityTime)
                                        <span>
                                            {{ \App\Support\NumberHelper::toPersian($activityTime) }}
                                        </span>
                                    @endif

                                    @if($subjectType)
                                        <span>
                                            موضوع:
                                            {{ $subjectType }}
                                            @if($activity->subject_id)
                                                #{{ \App\Support\NumberHelper::toPersian($activity->subject_id) }}
                                            @endif
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Activities Pagination --}}
                @if($activities->hasPages())
                    <div class="border-t border-[#EEE8E5] bg-[#FAF8F7] px-5 py-4 sm:px-6">

                        <div class="mb-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[#827875]">

                            <span>
                                نمایش
                                {{ \App\Support\NumberHelper::toPersian(number_format($activities->firstItem())) }}
                                تا
                                {{ \App\Support\NumberHelper::toPersian(number_format($activities->lastItem())) }}
                                از
                                {{ \App\Support\NumberHelper::toPersian(number_format($activities->total())) }}
                                فعالیت
                            </span>

                            <span>
                                صفحه
                                {{ \App\Support\NumberHelper::toPersian(number_format($activities->currentPage())) }}
                                از
                                {{ \App\Support\NumberHelper::toPersian(number_format($activities->lastPage())) }}
                            </span>

                        </div>

                        <div class="overflow-x-auto">
                            {{ $activities->onEachSide(1)->links() }}
                        </div>

                    </div>
                @endif

            @else

                <div class="flex min-h-[250px] flex-col items-center justify-center px-6 py-10 text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#674040]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" stroke="currentColor" stroke-width="1.6">
                            <path d="M12 8v4l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    </div>

                    <p class="mt-4 text-sm text-[#362727] kohan-bold">
                        هنوز فعالیتی ثبت نشده است
                    </p>

                    <p class="mt-2 max-w-xs text-xs leading-6 text-[#9A8E89]">
                        فعالیت‌هایی که این مدیر در پنل مدیریت انجام دهد، در این قسمت نمایش داده خواهند شد.
                    </p>

                </div>

            @endif

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

            {{-- Modal Form --}}
            <form
                id="permissions-form"
                method="POST"
                action="{{ route('admin.managers.permissions.update', $manager['id']) }}"
            >
                @csrf
                @method('PUT')

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
                            {{ count($selectedPermissions) }} از {{ count($permissions) }} انتخاب شده
                        </span>
                    </div>

                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-6 text-amber-800">
                        در صورت برداشتن تمام دسترسی‌ها، حساب این مدیر غیرفعال می‌شود و در درخواست محافظت‌شده بعدی از حساب کاربری خارج خواهد شد.
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
                                    name="permissions[]"
                                    value="{{ $permission['key'] }}"
                                    data-permission-checkbox
                                    class="h-4 w-4 cursor-pointer accent-[#674040]"
                                    {{ in_array($permission['key'], $selectedPermissions, true) ? 'checked' : '' }}
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
                        type="submit"
                        id="save-permissions"
                        class="cursor-pointer rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#8d6666] kohan-bold"
                    >
                        ذخیره تغییرات
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

<script>
    (() => {
        const modal = document.getElementById('permissions-modal');
        const openButton = document.getElementById('open-permissions-modal');
        const closeButton = document.getElementById('close-permissions-modal');
        const cancelButton = document.getElementById('cancel-permissions-modal');
        const form = document.getElementById('permissions-form');

        const checkboxes = Array.from(
            document.querySelectorAll('[data-permission-checkbox]')
        );

        const countElement = document.getElementById('selected-permissions-count');

        if (!modal || !openButton || !form) return;

        const updateSelectedCount = () => {
            const selectedCount = checkboxes.filter(
                checkbox => checkbox.checked
            ).length;

            countElement.textContent =
                `${selectedCount} از ${checkboxes.length} انتخاب شده`;
        };

        const openModal = () => {
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

        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', closeModal);
        }

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

        form.addEventListener('submit', () => {
            const submitButton = document.getElementById('save-permissions');

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'در حال ذخیره...';
                submitButton.classList.add('opacity-60', 'cursor-not-allowed');
            }
        });

        @if($errors->any())
            openModal();
        @endif
    })();
</script>

@endsection