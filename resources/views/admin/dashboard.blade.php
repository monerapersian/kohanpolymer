@extends('layouts.admin')

@section('title', 'داشبورد | کهن پلیمر البرز')

@section('content')

            <!-- Dashboard Hero -->
            <section class="relative isolate overflow-hidden rounded-[28px] border border-[#a66f72]/40 bg-[#362727] p-6 shadow-[0_12px_40px_rgba(54,39,39,0.2)] sm:p-8">

                <!-- Animated Background -->
                <div class="hero-animated-bg pointer-events-none absolute inset-0 -z-10 overflow-hidden rounded-[28px]">

                    <div class="hero-orb hero-orb-one"></div>
                    <div class="hero-orb hero-orb-two"></div>
                    <div class="hero-orb hero-orb-three"></div>

                    <div class="hero-wave"></div>

                    <div class="hero-ring hero-ring-one"></div>
                    <div class="hero-ring hero-ring-two"></div>

                    <div class="hero-grid"></div>

                    <div class="hero-shine"></div>

                </div>

                <div class="relative flex flex-col justify-between gap-8 lg:flex-row lg:items-center">

                    <!-- Welcome Content -->
                    <div class="max-w-2xl">

                        <!-- Brand Badge -->
                        <div class="mb-5 inline-flex items-center gap-2.5 rounded-full border border-white/20 bg-white/[0.09] px-4 py-2.5 text-xs text-[#eadcda] shadow-[0_4px_20px_rgba(0,0,0,0.08)] backdrop-blur-md">

                            <span class="relative flex h-2.5 w-2.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#baadad] opacity-50"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#baadad]"></span>
                            </span>

                            پنل مدیریت کهن پلیمر البرز

                        </div>

                        <!-- Title -->
                        <h1 class="kohan-bold text-2xl tracking-tight text-white sm:text-3xl lg:text-[34px]">
                            به داشبورد خوش آمدید
                        </h1>

                        <!-- Description -->
                        <p class="mt-4 max-w-xl text-sm leading-8 text-white/75">
                            نمای کلی کسب‌وکار، وضعیت اسناد مالی و آخرین فعالیت‌های مجموعه را از اینجا مدیریت کنید.
                        </p>

                        <!-- Actions -->
                        <div class="mt-7 flex flex-wrap items-center gap-3">

                            <!-- Primary Action -->
                            <a
                                href="#quick-actions"
                                class="inline-flex min-h-[48px] cursor-pointer items-center gap-2.5 rounded-xl border border-white/15 bg-[#a66f72] px-5 py-3 text-sm text-white shadow-[0_6px_20px_rgba(0,0,0,0.2)] transition duration-300 hover:-translate-y-1 hover:border-white/25 hover:bg-[#b77d80] hover:shadow-[0_10px_25px_rgba(0,0,0,0.25)] kohan-bold"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>

                                ایجاد سند جدید
                            </a>

                            <!-- Secondary Action -->
                            <a
                                href="#recent-activity"
                                class="inline-flex min-h-[48px] cursor-pointer items-center gap-2.5 rounded-xl border border-white/20 bg-white/[0.08] px-5 py-3 text-sm text-white/90 shadow-[0_4px_15px_rgba(0,0,0,0.08)] backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:border-white/40 hover:bg-white/[0.16] hover:text-white"
                            >
                                مشاهده فعالیت‌ها

                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                        </div>
                    </div>

                    <!-- Date Card -->
                    <div class="flex min-w-[250px] items-center gap-4 rounded-2xl border border-white/20 bg-white/[0.10] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.12)] backdrop-blur-xl">

                        <!-- Calendar Icon -->
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-[#a66f72] text-white shadow-[0_5px_18px_rgba(0,0,0,0.15)]">

                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                                <path d="m9 16 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                        </div>

                        <!-- Date Information -->
                        <div class="min-w-0">

                            <p class="text-xs text-white/60">
                                امروز
                            </p>

                            <p id="dashboard-date" class="kohan-bold mt-2 text-sm text-white">
                                در حال دریافت تاریخ...
                            </p>

                            <p id="dashboard-weekday" class="kohan-bold mt-1 text-xs text-[#d7bfc0]"></p>

                        </div>

                    </div>

                </div>
            </section>


            <!-- Section Heading -->
            <section>

                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">

                    <div>
                        <h2 class="kohan-bold text-xl text-[#362727]">
                            وضعیت کلی مجموعه
                        </h2>

                        <p class="mt-2 text-sm text-[#918783]">
                            خلاصه‌ای از اطلاعات ثبت‌شده در سیستم
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 rounded-xl border border-[#E8E1DE] bg-white px-4 py-2.5 text-xs text-[#8d6666] shadow-[0_3px_12px_rgba(54,39,39,0.03)]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#a66f72]"></span>
                        آمار نمونه
                    </span>

                </div>


                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- Active Invoices -->
                    <article class="group relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-[0_12px_30px_rgba(54,39,39,0.08)]">

                        <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-emerald-500/[0.07] blur-3xl transition duration-500 group-hover:bg-emerald-500/[0.13]"></div>

                        <div class="relative flex items-start justify-between gap-3">

                            <div>
                                <p class="text-sm text-[#77716F]">
                                    فاکتورهای فعال
                                </p>

                                <p class="kohan-bold mt-4 text-3xl tracking-tight text-[#362727]">
                                    ۱۲
                                </p>

                                <p class="mt-3 text-xs text-[#a49a95]">
                                    فاکتورهای دارای اعتبار
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-100 bg-emerald-50 text-emerald-700 transition duration-300 group-hover:scale-110 group-hover:bg-emerald-100">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M14 3v5h5M9 13h6M9 17h6"/>
                                </svg>

                            </div>

                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-[#F0ECEA] pt-4">

                            <span class="text-xs text-[#918783]">
                                مدیریت فاکتورها
                            </span>

                            <a href="#" class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-emerald-700 transition hover:text-emerald-900 kohan-bold">
                                مشاهده
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    <!-- Active Proformas -->
                    <article class="group relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] transition duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-[0_12px_30px_rgba(54,39,39,0.08)]">

                        <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-amber-500/[0.07] blur-3xl transition duration-500 group-hover:bg-amber-500/[0.13]"></div>

                        <div class="relative flex items-start justify-between gap-3">

                            <div>
                                <p class="text-sm text-[#77716F]">
                                    پیش‌فاکتورهای فعال
                                </p>

                                <p class="kohan-bold mt-4 text-3xl tracking-tight text-[#362727]">
                                    ۸
                                </p>

                                <p class="mt-3 text-xs text-[#a49a95]">
                                    پیش‌فاکتورهای معتبر
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-amber-100 bg-amber-50 text-amber-700 transition duration-300 group-hover:scale-110 group-hover:bg-amber-100">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6M9 12h6"/>
                                </svg>

                            </div>

                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-[#F0ECEA] pt-4">

                            <span class="text-xs text-[#918783]">
                                مدیریت پیش‌فاکتورها
                            </span>

                            <a href="#" class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-amber-700 transition hover:text-amber-900 kohan-bold">
                                مشاهده
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    <!-- Customers -->
                    <article class="group relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] transition duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-[0_12px_30px_rgba(54,39,39,0.08)]">

                        <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-sky-500/[0.07] blur-3xl transition duration-500 group-hover:bg-sky-500/[0.13]"></div>

                        <div class="relative flex items-start justify-between gap-3">

                            <div>
                                <p class="text-sm text-[#77716F]">
                                    مشتریان ثبت‌شده
                                </p>

                                <p class="kohan-bold mt-4 text-3xl tracking-tight text-[#362727]">
                                    ۱۲۶
                                </p>

                                <p class="mt-3 text-xs text-[#a49a95]">
                                    مجموع مشتریان سیستم
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 text-sky-700 transition duration-300 group-hover:scale-110 group-hover:bg-sky-100">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2M17 4.5a4 4 0 0 1 0 7.5M19 14a5 5 0 0 1 3 4.5V21"/>
                                </svg>

                            </div>

                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-[#F0ECEA] pt-4">

                            <span class="text-xs text-[#918783]">
                                بانک اطلاعات مشتریان
                            </span>

                            <a href="#" class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-sky-700 transition hover:text-sky-900 kohan-bold">
                                مشاهده
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                        </div>

                    </article>


                    <!-- Products -->
                    <article class="group relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#d9bfc0] hover:shadow-[0_12px_30px_rgba(54,39,39,0.08)]">

                        <div class="pointer-events-none absolute -left-10 -top-10 h-32 w-32 rounded-full bg-[#a66f72]/[0.09] blur-3xl transition duration-500 group-hover:bg-[#a66f72]/[0.16]"></div>

                        <div class="relative flex items-start justify-between gap-3">

                            <div>
                                <p class="text-sm text-[#77716F]">
                                    محصولات ثبت‌شده
                                </p>

                                <p class="kohan-bold mt-4 text-3xl tracking-tight text-[#362727]">
                                    ۳۴
                                </p>

                                <p class="mt-3 text-xs text-[#a49a95]">
                                    محصولات کاتالوگ
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-[#ead9d9] bg-[#f8eeee] text-[#8d555a] transition duration-300 group-hover:scale-110 group-hover:bg-[#f2e1e2]">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5M12 13v8"/>
                                </svg>

                            </div>

                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-[#F0ECEA] pt-4">

                            <span class="text-xs text-[#918783]">
                                مدیریت محصولات
                            </span>

                            <a href="#" class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-[#8d555a] transition hover:text-[#542b2e] kohan-bold">
                                مشاهده
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                        </div>

                    </article>

                </div>

            </section>


            <!-- Financial Summary -->
            <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                <!-- Sales -->
                <div class="relative overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] sm:p-6 lg:col-span-2">

                    <!-- Decorative Background -->
                    <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-[#a66f72]/[0.07] blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-24 right-1/3 h-48 w-48 rounded-full bg-[#8d6666]/[0.06] blur-3xl"></div>

                    <div class="relative flex flex-wrap items-center justify-between gap-3">

                        <div>
                            <h2 class="kohan-bold text-lg text-[#362727]">
                                خلاصه مالی
                            </h2>

                            <p class="mt-2 text-sm text-[#918783]">
                                مروری بر عملکرد فروش مجموعه
                            </p>
                        </div>

                        <div class="inline-flex items-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-3 py-2.5 text-xs text-[#8d6666]">

                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>

                            آمار نمونه

                        </div>

                    </div>

                    <!-- Total Sales -->
                    <div class="relative mt-6 overflow-hidden rounded-2xl border border-[#674040]/20 bg-gradient-to-l from-[#362727] via-[#542b2e] to-[#674040] p-5 shadow-[0_8px_25px_rgba(84,43,46,0.12)] sm:p-6">

                        <div class="pointer-events-none absolute -left-10 -top-16 h-48 w-48 rounded-full bg-[#baadad]/20 blur-3xl"></div>

                        <div class="relative">

                            <p class="text-sm text-white/65">
                                مجموع مبلغ فاکتورهای صادرشده
                            </p>

                            <div class="mt-4 flex flex-wrap items-baseline gap-2">

                                <span class="kohan-bold text-3xl tracking-tight text-white sm:text-4xl">
                                    ۸,۴۵۰,۰۰۰,۰۰۰
                                </span>

                                <span class="text-sm text-white/65">
                                    تومان
                                </span>

                            </div>

                            <div class="mt-5 flex items-start gap-2 border-t border-white/15 pt-4">

                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#d7bfc0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 11v5m0-8h.01" stroke-linecap="round"/>
                                </svg>

                                <p class="text-xs leading-6 text-white/60">
                                    این مبلغ صرفاً جهت نمایش قالب است و لزوماً معادل مبلغ وصول‌شده نیست.
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- Invoice Statistics -->
                    <div class="relative mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:border-emerald-200 hover:bg-emerald-50/40">

                            <div class="flex items-center gap-2 text-xs text-[#77716F]">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                فاکتورهای صادرشده
                            </div>

                            <p class="kohan-bold mt-3 text-2xl text-[#362727]">
                                ۲۴
                                <span class="text-xs text-[#918783]">فاکتور</span>
                            </p>

                        </div>

                        <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:border-amber-200 hover:bg-amber-50/40">

                            <div class="flex items-center gap-2 text-xs text-[#77716F]">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                پیش‌فاکتورهای در انتظار
                            </div>

                            <p class="kohan-bold mt-3 text-2xl text-[#362727]">
                                ۵
                                <span class="text-xs text-[#918783]">پیش‌فاکتور</span>
                            </p>

                        </div>

                    </div>

                </div>


                <!-- Follow-up -->
                <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] sm:p-6">

                    <div>
                        <h2 class="kohan-bold text-lg text-[#362727]">
                            نیازمند پیگیری
                        </h2>

                        <p class="mt-2 text-sm text-[#918783]">
                            مواردی که بررسی آن‌ها مهم است
                        </p>
                    </div>

                    <div class="mt-6 space-y-3">

                        <!-- Expiring Proformas -->
                        <a href="#" class="group flex cursor-pointer items-center gap-3 rounded-xl border border-amber-200/70 bg-amber-50/60 p-3 transition duration-300 hover:-translate-y-0.5 hover:border-amber-300 hover:bg-amber-50">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-white text-amber-600 shadow-sm transition group-hover:scale-105">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2" stroke-linecap="round"/>
                                </svg>

                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm text-[#362727] kohan-bold">
                                    پیش‌فاکتورهای نزدیک به انقضا
                                </p>

                                <p class="mt-1 text-xs text-[#918783]">
                                    نیازمند بررسی تاریخ اعتبار
                                </p>

                            </div>

                            <span class="kohan-bold text-xl text-amber-700">
                                ۳
                            </span>

                        </a>


                        <!-- Invoices to Review -->
                        <a href="#" class="group flex cursor-pointer items-center gap-3 rounded-xl border border-rose-200/70 bg-rose-50/60 p-3 transition duration-300 hover:-translate-y-0.5 hover:border-rose-300 hover:bg-rose-50">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-600 shadow-sm transition group-hover:scale-105">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M12 3 2.8 19h18.4L12 3Z" stroke-linejoin="round"/>
                                    <path d="M12 9v4m0 3h.01" stroke-linecap="round"/>
                                </svg>

                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm text-[#362727] kohan-bold">
                                    فاکتورهای نیازمند بررسی
                                </p>

                                <p class="mt-1 text-xs text-[#918783]">
                                    موارد علامت‌گذاری‌شده
                                </p>

                            </div>

                            <span class="kohan-bold text-xl text-rose-700">
                                ۲
                            </span>

                        </a>


                        <!-- New Customers -->
                        <a href="#" class="group flex cursor-pointer items-center gap-3 rounded-xl border border-sky-200/70 bg-sky-50/60 p-3 transition duration-300 hover:-translate-y-0.5 hover:border-sky-300 hover:bg-sky-50">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-sky-100 bg-white text-sky-600 shadow-sm transition group-hover:scale-105">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2" stroke-linecap="round"/>
                                </svg>

                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm text-[#362727] kohan-bold">
                                    مشتریان جدید
                                </p>

                                <p class="mt-1 text-xs text-[#918783]">
                                    ثبت‌شده در این ماه
                                </p>

                            </div>

                            <span class="kohan-bold text-xl text-sky-700">
                                ۹
                            </span>

                        </a>

                    </div>

                </div>

            </section>


            <!-- Recent Activity + Quick Actions -->
            <section class="grid grid-cols-1 gap-4 xl:grid-cols-5">

                <!-- Recent Activity -->
                <div
                    id="recent-activity"
                    class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] sm:p-6 xl:col-span-3"
                >

                    <div class="flex flex-wrap items-center justify-between gap-3">

                        <div>
                            <h2 class="kohan-bold text-lg text-[#362727]">
                                آخرین فعالیت‌ها
                            </h2>

                            <p class="mt-2 text-sm text-[#918783]">
                                نمونه‌ای از رویدادهای پنل مدیریت
                            </p>
                        </div>

                        <a
                            href="#"
                            class="cursor-pointer rounded-lg px-3 py-2 text-xs text-[#8d6666] transition duration-300 hover:bg-[#F3E9E9] hover:text-[#542b2e]"
                        >
                            همه فعالیت‌ها ←
                        </a>

                    </div>


                    <!-- Activity List -->
                    <div class="mt-5 divide-y divide-[#E8E1DE]">

                        <!-- Invoice Activity -->
                        <div class="flex items-start gap-3 py-4 first:pt-0">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M14 3v5h5M9 13h6M9 17h4"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm leading-7 text-[#362727] kohan-bold">
                                    یک فاکتور جدید صادر شد
                                </p>

                                <p class="mt-1 text-xs leading-6 text-[#918783]">
                                    توسط مدیر سیستم · شماره نمونه: INV-1025
                                </p>

                            </div>

                            <span class="shrink-0 rounded-lg bg-[#FAF8F7] px-2.5 py-1.5 text-[11px] text-[#918783]">
                                ۱۰:۳۵
                            </span>

                        </div>


                        <!-- Proforma Activity -->
                        <div class="flex items-start gap-3 py-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6M9 12h6"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm leading-7 text-[#362727] kohan-bold">
                                    یک پیش‌فاکتور ثبت شد
                                </p>

                                <p class="mt-1 text-xs leading-6 text-[#918783]">
                                    توسط مدیر فروش · شماره نمونه: PRO-1082
                                </p>

                            </div>

                            <span class="shrink-0 rounded-lg bg-[#FAF8F7] px-2.5 py-1.5 text-[11px] text-[#918783]">
                                ۰۹:۵۰
                            </span>

                        </div>


                        <!-- Customer Activity -->
                        <div class="flex items-start gap-3 py-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-sky-100 bg-sky-50 text-sky-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2M17 4.5a4 4 0 0 1 0 7.5"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm leading-7 text-[#362727] kohan-bold">
                                    اطلاعات یک مشتری ثبت شد
                                </p>

                                <p class="mt-1 text-xs leading-6 text-[#918783]">
                                    توسط مدیر سیستم · بخش مشتریان
                                </p>

                            </div>

                            <span class="shrink-0 rounded-lg bg-[#FAF8F7] px-2.5 py-1.5 text-[11px] text-[#918783]">
                                دیروز
                            </span>

                        </div>


                        <!-- Product Activity -->
                        <div class="flex items-start gap-3 py-4 last:pb-0">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-rose-100 bg-rose-50 text-rose-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M12 3v18M17 7H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-sm leading-7 text-[#362727] kohan-bold">
                                    اطلاعات یک محصول ویرایش شد
                                </p>

                                <p class="mt-1 text-xs leading-6 text-[#918783]">
                                    توسط مدیر محتوا · بخش محصولات
                                </p>

                            </div>

                            <span class="shrink-0 rounded-lg bg-[#FAF8F7] px-2.5 py-1.5 text-[11px] text-[#918783]">
                                دیروز
                            </span>

                        </div>

                    </div>

                </div>


                <!-- Quick Actions -->
                <div
                    id="quick-actions"
                    class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-[0_5px_20px_rgba(54,39,39,0.035)] sm:p-6 xl:col-span-2"
                >

                    <div>
                        <h2 class="kohan-bold text-lg text-[#362727]">
                            دسترسی سریع
                        </h2>

                        <p class="mt-2 text-sm text-[#918783]">
                            عملیات پرکاربرد پنل
                        </p>
                    </div>


                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <!-- New Invoice -->
                        <a
                            href="#"
                            class="group cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:-translate-y-1 hover:border-[#542b2e]/30 hover:bg-[#F3E9E9] hover:shadow-[0_8px_20px_rgba(84,43,46,0.07)]"
                        >

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#542b2e]/10 bg-white text-[#542b2e] shadow-sm transition duration-300 group-hover:scale-105 group-hover:bg-[#542b2e] group-hover:text-white">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>

                            </div>

                            <p class="kohan-bold mt-4 text-sm text-[#362727]">
                                صدور فاکتور
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[#918783]">
                                ثبت فاکتور جدید
                            </p>

                        </a>


                        <!-- New Proforma -->
                        <a
                            href="#"
                            class="group cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:bg-amber-50/60 hover:shadow-[0_8px_20px_rgba(180,120,30,0.06)]"
                        >

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-amber-100 bg-white text-amber-700 shadow-sm transition duration-300 group-hover:scale-105 group-hover:bg-amber-600 group-hover:text-white">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6"/>
                                </svg>

                            </div>

                            <p class="kohan-bold mt-4 text-sm text-[#362727]">
                                پیش‌فاکتور جدید
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[#918783]">
                                ایجاد پیش‌فاکتور
                            </p>

                        </a>


                        <!-- New Customer -->
                        <a
                            href="#"
                            class="group cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:-translate-y-1 hover:border-sky-300 hover:bg-sky-50/60 hover:shadow-[0_8px_20px_rgba(30,120,180,0.06)]"
                        >

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-sky-100 bg-white text-sky-700 shadow-sm transition duration-300 group-hover:scale-105 group-hover:bg-sky-600 group-hover:text-white">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                                    <path d="M19 8v6m-3-3h6" stroke-linecap="round"/>
                                </svg>

                            </div>

                            <p class="kohan-bold mt-4 text-sm text-[#362727]">
                                ثبت مشتری
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[#918783]">
                                افزودن مشتری جدید
                            </p>

                        </a>


                        <!-- New Product -->
                        <a
                            href="#"
                            class="group cursor-pointer rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] p-4 transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:bg-emerald-50/60 hover:shadow-[0_8px_20px_rgba(30,140,90,0.06)]"
                        >

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-100 bg-white text-emerald-700 shadow-sm transition duration-300 group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
                                </svg>

                            </div>

                            <p class="kohan-bold mt-4 text-sm text-[#362727]">
                                افزودن محصول
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[#918783]">
                                ثبت محصول جدید
                            </p>

                        </a>

                    </div>

                </div>

            </section>

@endsection