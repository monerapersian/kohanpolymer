@extends('layouts.admin')

@section('content')

@php
    $actionCount = $activities->total();

    $propertyLabels = [
        'name' => 'نام',
        'title' => 'عنوان',
        'status' => 'وضعیت',
        'old_status' => 'وضعیت قبلی',
        'new_status' => 'وضعیت جدید',
        'type' => 'نوع',
        'is_formal' => 'رسمی',
        'is_featured' => 'محصول ویژه',
        'has_image' => 'دارای تصویر',
        'has_phone' => 'دارای شماره تماس',
        'items_count' => 'تعداد اقلام',
        'document_number' => 'شماره سند',
        'source_document_number' => 'شماره پیش‌فاکتور مبدا',
        'new_document_number' => 'شماره فاکتور جدید',
        'registration_method' => 'روش ثبت‌نام',
        'changed_fields' => 'فیلدهای تغییرکرده',
        'old' => 'مقدار قبلی',
        'new' => 'مقدار جدید',
        'old_is_read' => 'وضعیت قبلی مطالعه',
        'new_is_read' => 'وضعیت جدید مطالعه',
        'previous_status' => 'وضعیت قبلی',
        'source_proforma_id' => 'شناسه پیش‌فاکتور مبدا',
        'new_invoice_id' => 'شناسه فاکتور جدید',
        'inquiry_id' => 'شناسه درخواست',
        'deleted_user_id' => 'شناسه کاربر حذف‌شده',
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

    $subjectLabels = [
        'User' => 'کاربر',
        'Product' => 'محصول',
        'BlogPost' => 'مقاله',
        'Customer' => 'مشتری',
        'Invoice' => 'فاکتور',
        'Inquiry' => 'درخواست تماس',
        'SiteContent' => 'محتوای سایت',
        'Manager' => 'مدیر',
        'ActivityLog' => 'گزارش فعالیت',
    ];

    $formatPropertyValue = function ($value) use ($permissionLabels) {
        if (is_bool($value)) {
            return $value ? 'بله' : 'خیر';
        }

        if (is_null($value)) {
            return '—';
        }

        if (is_array($value)) {
            $formatted = [];

            foreach ($value as $key => $item) {
                if (is_array($item)) {
                    $nested = [];

                    foreach ($item as $nestedKey => $nestedValue) {
                        if (!is_scalar($nestedValue) && !is_null($nestedValue)) {
                            continue;
                        }

                        $nested[] = (string) $nestedKey . ': ' .
                            ($permissionLabels[$nestedValue] ?? (string) $nestedValue);
                    }

                    $formatted[] = implode('، ', $nested);

                    continue;
                }

                if (is_bool($item)) {
                    $formatted[] = $item ? 'بله' : 'خیر';
                    continue;
                }

                if (is_null($item)) {
                    $formatted[] = '—';
                    continue;
                }

                if (is_scalar($item)) {
                    $formatted[] = $permissionLabels[$item] ?? (string) $item;
                }
            }

            return implode('، ', $formatted);
        }

        if (is_scalar($value)) {
            return $permissionLabels[$value] ?? (string) $value;
        }

        return '—';
    };

    $getSubjectLabel = function ($activity) use ($subjectLabels) {
        if (!$activity->subject_type) {
            return null;
        }

        $subjectName = class_basename($activity->subject_type);

        return $subjectLabels[$subjectName] ?? $subjectName;
    };

    $getSafeProperties = function ($activity) use ($propertyLabels) {
        $properties = is_array($activity->properties)
            ? $activity->properties
            : [];

        return array_intersect_key($properties, $propertyLabels);
    };
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
                    نظارت و بررسی عملکرد سیستم
                </span>

                <h1 class="text-2xl text-white kohan-bold sm:text-3xl">
                    گزارش فعالیت‌ها
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                    مشاهده و بررسی سوابق ورود، تغییرات و عملیات انجام‌شده توسط مدیران سیستم
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-5 py-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-[#baadad]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6" stroke="currentColor" stroke-width="1.7">
                        <path d="M12 8v4l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs text-white/60">مجموع فعالیت‌های ثبت‌شده</p>
                    <p class="mt-1 text-xl text-white kohan-bold">
                        {{ \App\Support\NumberHelper::toPersian(number_format($actionCount)) }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">تعداد نتایج این صفحه</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-2xl text-[#362727] kohan-bold">
                {{ \App\Support\NumberHelper::toPersian($activities->count()) }}
            </p>

            <p class="mt-1 text-xs text-[#9A8E89]">
                از {{ \App\Support\NumberHelper::toPersian(number_format($activities->total())) }} فعالیت پیدا شده
            </p>
        </div>

        <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-[#827875]">مدیران دارای فعالیت</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-2xl text-[#362727] kohan-bold">
                {{ \App\Support\NumberHelper::toPersian($users->count()) }}
            </p>

            <p class="mt-1 text-xs text-[#9A8E89]">
                مدیران دارای سوابق ثبت‌شده
            </p>
        </div>

    </div>

    {{-- Filters --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="border-b border-[#EEE8E5] px-5 py-5 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7">
                        <path d="M4 5h16M7 12h10m-7 7h4" stroke-linecap="round"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base text-[#362727] kohan-bold">
                        جست‌وجو و فیلتر فعالیت‌ها
                    </h2>
                    <p class="mt-1 text-xs text-[#9A8E89]">
                        برای محدود کردن نتایج از فیلترهای زیر استفاده کنید.
                    </p>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-5 p-5 sm:p-6">

            <div>
                <label for="search" class="mb-2 block text-sm text-[#5F514D]">
                    جست‌وجو
                </label>

                <div class="relative">
                    <svg viewBox="0 0 24 24" fill="none" class="absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A99D98]" stroke="currentColor" stroke-width="1.7">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4" stroke-linecap="round"/>
                    </svg>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="نام مدیر، شرح فعالیت، IP یا شناسه موضوع..."
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] py-3 pr-12 pl-4 text-sm text-[#362727] outline-none transition placeholder:text-[#B5AAA5] focus:border-[#8d6666] focus:bg-white focus:ring-2 focus:ring-[#8d6666]/10"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div>
                    <label for="user_id" class="mb-2 block text-sm text-[#5F514D]">
                        مدیر
                    </label>

                    <select
                        id="user_id"
                        name="user_id"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                        <option value="">همه مدیران</option>

                        @foreach($users as $user)
                            <option
                                value="{{ $user->id }}"
                                @selected((string) request('user_id') === (string) $user->id)
                            >
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="action" class="mb-2 block text-sm text-[#5F514D]">
                        نوع فعالیت
                    </label>

                    <select
                        id="action"
                        name="action"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                        <option value="">همه فعالیت‌ها</option>

                        @foreach($actions as $action)
                            <option
                                value="{{ $action }}"
                                @selected(request('action') === $action)
                            >
                                {{ $actionLabels[$action] ?? $action }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="from" class="mb-2 block text-sm text-[#5F514D]">
                        از تاریخ
                    </label>

                    <input
                        type="date"
                        id="from"
                        name="from"
                        value="{{ request('from') }}"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                </div>

                <div>
                    <label for="to" class="mb-2 block text-sm text-[#5F514D]">
                        تا تاریخ
                    </label>

                    <input
                        type="date"
                        id="to"
                        name="to"
                        value="{{ request('to') }}"
                        min="{{ request('from') }}"
                        class="w-full rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#5F514D] outline-none transition focus:border-[#8d6666] focus:bg-white"
                    >
                </div>

            </div>

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-col gap-3 border-t border-[#EEE8E5] pt-5 sm:flex-row sm:items-center sm:justify-between">

                <p class="text-xs text-[#9A8E89]">
                    فیلترها را انتخاب کنید و دکمه جست‌وجو را بزنید.
                </p>

                <div class="flex flex-col gap-2 sm:flex-row">

                    <a
                        href="{{ route('admin.activity-logs.index') }}"
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#E8E1DE] bg-white px-5 py-3 text-sm text-[#674040] transition hover:bg-[#F3E9E9]"
                    >
                        پاک کردن فیلترها
                    </a>

                    <button
                        type="submit"
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#674040] px-6 py-3 text-sm text-white transition hover:bg-[#8d6666] kohan-bold"
                    >
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4" stroke-linecap="round"/>
                        </svg>
                        جست‌وجو
                    </button>

                </div>
            </div>

        </form>
    </section>

    {{-- Activity Logs --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-[#EEE8E5] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            <div>
                <h2 class="text-base text-[#362727] kohan-bold">
                    سوابق فعالیت‌ها
                </h2>

                <p class="mt-1 text-xs text-[#9A8E89]">
                    فهرست عملیات ثبت‌شده در پنل مدیریت
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-lg bg-[#F3E9E9] px-3 py-2 text-xs text-[#674040] kohan-bold">
                {{ \App\Support\NumberHelper::toPersian(number_format($activities->total())) }} نتیجه
            </span>

        </div>

        @if($activities->isNotEmpty())

            {{-- Desktop Table --}}
            <div class="hidden overflow-x-auto lg:block">

                <table class="w-full min-w-[1000px] text-right">

                    <thead class="bg-[#FAF8F7]">
                        <tr class="text-xs text-[#827875]">
                            <th class="px-5 py-4 font-normal">مدیر</th>
                            <th class="px-5 py-4 font-normal">نوع فعالیت</th>
                            <th class="px-5 py-4 font-normal">شرح فعالیت</th>
                            <th class="px-5 py-4 font-normal">موضوع</th>
                            <th class="px-5 py-4 font-normal">IP</th>
                            <th class="px-5 py-4 font-normal">تاریخ و زمان</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#F0EBE8]">

                        @foreach($activities as $activity)

                            @php
                                $subjectLabel = $getSubjectLabel($activity);
                                $safeProperties = $getSafeProperties($activity);
                            @endphp

                            <tr class="align-top transition hover:bg-[#FCFAF9]">

                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#F3E9E9] text-sm text-[#674040] kohan-bold">
                                            {{ mb_substr($activity->actor_name ?: '؟', 0, 1) }}
                                        </div>

                                        <div class="min-w-[110px]">
                                            <p class="text-sm text-[#362727] kohan-bold">
                                                {{ $activity->actor_name ?: 'کاربر حذف‌شده' }}
                                            </p>

                                            @if(!$activity->user_id)
                                                <p class="mt-1 text-[10px] text-[#A99D98]">
                                                    حساب حذف شده یا کاربر مهمان
                                                </p>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex whitespace-nowrap rounded-lg border border-[#E8E1DE] bg-[#F8F4F2] px-3 py-1.5 text-xs text-[#674040]">
                                        {{ $actionLabels[$activity->action] ?? $activity->action }}
                                    </span>
                                </td>

                                <td class="max-w-[280px] px-5 py-5">
                                    <p class="text-sm leading-7 text-[#5F514D]">
                                        {{ $activity->description }}
                                    </p>

                                    @if(!empty($safeProperties))
                                        <details class="mt-2">
                                            <summary class="cursor-pointer text-xs text-[#8d6666]">
                                                مشاهده جزئیات تغییرات
                                            </summary>

                                            <div class="mt-3 space-y-2 rounded-xl bg-[#FAF8F7] p-3">

                                                @foreach($safeProperties as $key => $value)
                                                    <div class="flex flex-wrap items-start justify-between gap-2 text-xs">
                                                        <span class="text-[#827875]">
                                                            {{ $propertyLabels[$key] ?? $key }}
                                                        </span>

                                                        <span class="max-w-[65%] break-words text-left text-[#5F514D]">
                                                            {{ $formatPropertyValue($value) }}
                                                        </span>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </details>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    @if($subjectLabel)
                                        <p class="text-xs text-[#5F514D]">
                                            {{ $subjectLabel }}
                                        </p>

                                        @if($activity->subject_id)
                                            <p class="mt-1 text-[11px] text-[#A99D98]">
                                                شناسه: {{ \App\Support\NumberHelper::toPersian($activity->subject_id) }}
                                            </p>
                                        @endif
                                    @else
                                        <span class="text-xs text-[#A99D98]">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    <span class="whitespace-nowrap text-xs text-[#827875]" dir="ltr">
                                        {{ $activity->ip_address ?: '—' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-5">
                                    <p class="text-xs text-[#5F514D]">
                                        {{ \App\Support\NumberHelper::toPersian(\App\Support\DateHelper::toPersian($activity->created_at, 'Y/m/d')) }}
                                    </p>

                                    <p class="mt-1 text-[11px] text-[#A99D98]" dir="ltr">
                                        {{ \App\Support\NumberHelper::toPersian($activity->created_at->format('H:i')) }}
                                    </p>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="divide-y divide-[#F0EBE8] lg:hidden">

                @foreach($activities as $activity)

                    @php
                        $subjectLabel = $getSubjectLabel($activity);
                        $safeProperties = $getSafeProperties($activity);
                    @endphp

                    <div class="space-y-4 p-5">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#F3E9E9] text-[#674040] kohan-bold">
                                    {{ mb_substr($activity->actor_name ?: '؟', 0, 1) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate text-sm text-[#362727] kohan-bold">
                                        {{ $activity->actor_name ?: 'کاربر حذف‌شده' }}
                                    </p>

                                    @if(!$activity->user_id)
                                        <p class="mt-1 text-[10px] text-[#A99D98]">
                                            حساب حذف شده یا کاربر مهمان
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <span class="shrink-0 rounded-lg border border-[#E8E1DE] bg-[#F8F4F2] px-2.5 py-1.5 text-[10px] text-[#674040]">
                                {{ $actionLabels[$activity->action] ?? $activity->action }}
                            </span>

                        </div>

                        <div>
                            <p class="text-sm leading-7 text-[#5F514D]">
                                {{ $activity->description }}
                            </p>

                            @if(!empty($safeProperties))
                                <details class="mt-2">
                                    <summary class="cursor-pointer text-xs text-[#8d6666]">
                                        مشاهده جزئیات تغییرات
                                    </summary>

                                    <div class="mt-3 space-y-2 rounded-xl bg-[#FAF8F7] p-3">

                                        @foreach($safeProperties as $key => $value)
                                            <div class="flex flex-wrap items-start justify-between gap-2 text-xs">
                                                <span class="text-[#827875]">
                                                    {{ $propertyLabels[$key] ?? $key }}
                                                </span>

                                                <span class="max-w-[65%] break-words text-left text-[#5F514D]">
                                                    {{ $formatPropertyValue($value) }}
                                                </span>
                                            </div>
                                        @endforeach

                                    </div>
                                </details>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 border-t border-[#F0EBE8] pt-4">

                            <div>
                                <p class="text-[11px] text-[#A99D98]">موضوع</p>
                                <p class="mt-1 text-xs text-[#5F514D]">
                                    {{ $subjectLabel ?: '—' }}

                                    @if($activity->subject_id)
                                        <span class="text-[#A99D98]">
                                            ({{ \App\Support\NumberHelper::toPersian($activity->subject_id) }})
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] text-[#A99D98]">نشانی IP</p>
                                <p class="mt-1 text-xs text-[#5F514D]" dir="ltr">
                                    {{ $activity->ip_address ?: '—' }}
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center justify-between border-t border-[#F0EBE8] pt-3">
                            <p class="text-xs text-[#827875]">
                                {{ \App\Support\NumberHelper::toPersian(\App\Support\DateHelper::toPersian($activity->created_at, 'Y/m/d')) }}
                            </p>

                            <p class="text-xs text-[#A99D98]" dir="ltr">
                                {{ \App\Support\NumberHelper::toPersian($activity->created_at->format('H:i')) }}
                            </p>
                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Pagination --}}
            @if($activities->hasPages())
                <div class="border-t border-[#EEE8E5] px-5 py-5 sm:px-6">
                    {{ $activities->links() }}
                </div>
            @endif

        @else

            {{-- Empty State --}}
            <div class="flex min-h-[300px] flex-col items-center justify-center px-6 py-12 text-center">

                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F3E9E9] text-[#674040]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-8 w-8" stroke="currentColor" stroke-width="1.6">
                        <path d="M12 8v4l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-base text-[#362727] kohan-bold">
                    فعالیتی پیدا نشد
                </h3>

                <p class="mt-2 max-w-sm text-xs leading-7 text-[#9A8E89]">
                    در حال حاضر فعالیتی با شرایط انتخاب‌شده وجود ندارد. فیلترها را تغییر دهید یا دوباره جست‌وجو کنید.
                </p>

                @if(request()->hasAny(['search', 'user_id', 'action', 'from', 'to']))
                    <a
                        href="{{ route('admin.activity-logs.index') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#674040] px-5 py-3 text-sm text-white transition hover:bg-[#8d6666]"
                    >
                        پاک کردن فیلترها
                    </a>
                @endif

            </div>

        @endif

    </section>

</div>

@endsection