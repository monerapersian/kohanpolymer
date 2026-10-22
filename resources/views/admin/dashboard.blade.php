@extends('layouts.admin')

@section('title', 'داشبورد | کهن پلیمر البرز')

@section('content')

            <!-- Dashboard Hero -->
            <section class="relative isolate overflow-hidden rounded-[28px] border border-white/[0.09] bg-gradient-to-l from-[#681b21]/45 via-[#252525]/95 to-[#222222] p-6 shadow-2xl sm:p-8">

                <div class="pointer-events-none absolute -left-16 -top-24 -z-10 h-64 w-64 rounded-full bg-[#681b21]/25 blur-[90px]"></div>
                <div class="pointer-events-none absolute -bottom-24 right-1/3 -z-10 h-48 w-48 rounded-full bg-[#0f4c3a]/20 blur-[80px]"></div>

                <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-center">

                    <div>
                        <div class="mb-3 flex items-center gap-2 text-xs font-medium text-white/50">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            پنل مدیریت کهن پلیمر البرز
                        </div>

                        <h1 class="kohan-bold text-2xl font-bold tracking-tight sm:text-3xl">
                            به داشبورد خوش آمدید
                        </h1>

                        <p class="mt-3 max-w-xl text-sm leading-7 text-white/55">
                            نمای کلی کسب‌وکار، وضعیت اسناد مالی و آخرین فعالیت‌های مجموعه را از اینجا مدیریت کنید.
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <a href="#quick-actions"
                               class="inline-flex items-center gap-2 rounded-xl bg-[#681b21] px-4 py-3 text-sm font-semibold transition hover:bg-[#7b252c] kohan-bold">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>
                                ایجاد سند جدید
                            </a>

                            <a href="#recent-activity"
                               class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white/75 transition hover:bg-white/[0.08]">
                                مشاهده فعالیت‌ها
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="flex min-w-[210px] items-center gap-4 rounded-2xl border border-white/[0.08] bg-black/20 p-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/[0.07] text-white/80">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                                <path d="m9 16 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs text-white/45">امروز</p>
                            <p id="dashboard-date" class="mt-1 text-sm font-semibold text-white kohan-bold">
                                در حال دریافت تاریخ...
                            </p>
                            <p id="dashboard-weekday" class="mt-1 text-xs text-white/40 kohan-bold"></p>
                        </div>
                    </div>

                </div>
            </section>


            <!-- Section Heading -->
            <section>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="kohan-bold text-lg font-bold">وضعیت کلی مجموعه</h2>
                        <p class="mt-1 text-xs text-white/40">خلاصه‌ای از اطلاعات ثبت‌شده در سیستم</p>
                    </div>

                    <span class="rounded-lg border border-white/[0.08] bg-white/[0.03] px-3 py-2 text-xs text-white/45">
                        آمار نمونه
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- Active Invoices -->
                    <article class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 transition duration-300 hover:-translate-y-1 hover:border-emerald-500/30">
                        <div class="absolute -left-8 -top-8 h-24 w-24 rounded-full bg-emerald-500/[0.07] blur-2xl transition group-hover:bg-emerald-500/[0.12]"></div>

                        <div class="relative flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm text-white/55">فاکتورهای فعال</p>
                                <p class="mt-4 text-3xl font-bold tracking-tight kohan-bold">۱۲</p>
                                <p class="mt-3 text-xs text-white/40">فاکتورهای دارای اعتبار</p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M14 3v5h5M9 13h6M9 17h6"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-white/[0.07] pt-4">
                            <span class="text-xs text-white/40">مدیریت فاکتورها</span>
                            <a href="#" class="text-xs text-emerald-400 transition hover:text-emerald-300">مشاهده</a>
                        </div>
                    </article>


                    <!-- Active Proformas -->
                    <article class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 transition duration-300 hover:-translate-y-1 hover:border-amber-500/30">
                        <div class="absolute -left-8 -top-8 h-24 w-24 rounded-full bg-amber-500/[0.07] blur-2xl transition group-hover:bg-amber-500/[0.12]"></div>

                        <div class="relative flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm text-white/55">پیش‌فاکتورهای فعال</p>
                                <p class="mt-4 text-3xl font-bold tracking-tight kohan-bold">۸</p>
                                <p class="mt-3 text-xs text-white/40">پیش‌فاکتورهای معتبر</p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6M9 12h6"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-white/[0.07] pt-4">
                            <span class="text-xs text-white/40">مدیریت پیش‌فاکتورها</span>
                            <a href="#" class="text-xs text-amber-400 transition hover:text-amber-300">مشاهده</a>
                        </div>
                    </article>


                    <!-- Customers -->
                    <article class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 transition duration-300 hover:-translate-y-1 hover:border-sky-500/30">
                        <div class="absolute -left-8 -top-8 h-24 w-24 rounded-full bg-sky-500/[0.07] blur-2xl transition group-hover:bg-sky-500/[0.12]"></div>

                        <div class="relative flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm text-white/55">مشتریان ثبت‌شده</p>
                                <p class="mt-4 text-3xl font-bold tracking-tight kohan-bold">۱۲۶</p>
                                <p class="mt-3 text-xs text-white/40">مجموع مشتریان سیستم</p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-500/10 text-sky-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2M17 4.5a4 4 0 0 1 0 7.5M19 14a5 5 0 0 1 3 4.5V21"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-white/[0.07] pt-4">
                            <span class="text-xs text-white/40">بانک اطلاعات مشتریان</span>
                            <a href="#" class="text-xs text-sky-400 transition hover:text-sky-300">مشاهده</a>
                        </div>
                    </article>


                    <!-- Products -->
                    <article class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 transition duration-300 hover:-translate-y-1 hover:border-rose-500/30">
                        <div class="absolute -left-8 -top-8 h-24 w-24 rounded-full bg-rose-500/[0.07] blur-2xl transition group-hover:bg-rose-500/[0.12]"></div>

                        <div class="relative flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm text-white/55">محصولات ثبت‌شده</p>
                                <p class="mt-4 text-3xl font-bold tracking-tight kohan-bold">۳۴</p>
                                <p class="mt-3 text-xs text-white/40">محصولات کاتالوگ</p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-500/10 text-rose-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5M12 13v8"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative mt-5 flex items-center justify-between border-t border-white/[0.07] pt-4">
                            <span class="text-xs text-white/40">مدیریت محصولات</span>
                            <a href="#" class="text-xs text-rose-400 transition hover:text-rose-300">مشاهده</a>
                        </div>
                    </article>

                </div>
            </section>


            <!-- Financial Summary -->
            <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                <!-- Sales -->
                <div class="rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 sm:p-6 lg:col-span-2">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="kohan-bold text-base font-bold">خلاصه مالی</h2>
                            <p class="mt-1 text-xs text-white/40">مروری بر عملکرد فروش مجموعه</p>
                        </div>

                        <div class="flex items-center gap-2 rounded-lg bg-white/[0.04] px-3 py-2 text-xs text-white/55">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>
                            آمار نمونه
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-white/[0.06] bg-gradient-to-l from-[#681b21]/25 to-white/[0.02] p-5">
                        <p class="text-sm text-white/50">مجموع مبلغ فاکتورهای صادرشده</p>

                        <div class="mt-3 flex flex-wrap items-baseline gap-2">
                            <span class="text-3xl font-bold tracking-tight sm:text-4xl">۸,۴۵۰,۰۰۰,۰۰۰</span>
                            <span class="text-sm text-white/50">تومان</span>
                        </div>

                        <p class="mt-3 text-xs leading-6 text-white/35">
                            این مبلغ صرفاً جهت نمایش قالب است و لزوماً معادل مبلغ وصول‌شده نیست.
                        </p>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.025] p-4">
                            <div class="flex items-center gap-2 text-xs text-white/45">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                فاکتورهای صادرشده
                            </div>
                            <p class="mt-3 text-xl font-bold kohan-bold">۲۴ <span class="text-xs font-normal text-white/40">فاکتور</span></p>
                        </div>

                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.025] p-4">
                            <div class="flex items-center gap-2 text-xs text-white/45">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                پیش‌فاکتورهای در انتظار
                            </div>
                            <p class="mt-3 text-xl font-bold kohan-bold">۵ <span class="text-xs font-normal text-white/40">پیش‌فاکتور</span></p>
                        </div>
                    </div>
                </div>


                <!-- Follow-up -->
                <div class="rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 sm:p-6">
                    <div>
                        <h2 class="kohan-bold text-base font-bold">نیازمند پیگیری</h2>
                        <p class="mt-1 text-xs text-white/40">مواردی که بررسی آن‌ها مهم است</p>
                    </div>

                    <div class="mt-5 space-y-3">

                        <a href="#" class="flex items-center gap-3 rounded-xl border border-amber-400/10 bg-amber-400/[0.04] p-3 transition hover:bg-amber-400/[0.08]">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/10 text-amber-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium">پیش‌فاکتورهای نزدیک به انقضا</p>
                                <p class="mt-1 text-xs text-white/40">نیازمند بررسی تاریخ اعتبار</p>
                            </div>
                            <span class="text-lg font-bold text-amber-400">۳</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 rounded-xl border border-rose-400/10 bg-rose-400/[0.04] p-3 transition hover:bg-rose-400/[0.08]">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-400/10 text-rose-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 3 2.8 19h18.4L12 3Z" stroke-linejoin="round"/>
                                    <path d="M12 9v4m0 3h.01" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium">فاکتورهای نیازمند بررسی</p>
                                <p class="mt-1 text-xs text-white/40">موارد علامت‌گذاری‌شده</p>
                            </div>
                            <span class="text-lg font-bold text-rose-400">۲</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 rounded-xl border border-sky-400/10 bg-sky-400/[0.04] p-3 transition hover:bg-sky-400/[0.08]">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-400/10 text-sky-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium">مشتریان جدید</p>
                                <p class="mt-1 text-xs text-white/40">ثبت‌شده در این ماه</p>
                            </div>
                            <span class="text-lg font-bold text-sky-400">۹</span>
                        </a>

                    </div>
                </div>

            </section>


            <!-- Recent Activity + Quick Actions -->
            <section class="grid grid-cols-1 gap-4 xl:grid-cols-5">

                <!-- Recent Activity -->
                <div id="recent-activity" class="rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 sm:p-6 xl:col-span-3">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="kohan-bold text-base font-bold">آخرین فعالیت‌ها</h2>
                            <p class="mt-1 text-xs text-white/40">نمونه‌ای از رویدادهای پنل مدیریت</p>
                        </div>

                        <a href="#" class="text-xs text-white/50 transition hover:text-white">همه فعالیت‌ها ←</a>
                    </div>

                    <div class="mt-5 divide-y divide-white/[0.06]">

                        <div class="flex items-start gap-3 py-4 first:pt-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M14 3v5h5M9 13h6M9 17h4"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-6 text-white/85">یک فاکتور جدید صادر شد</p>
                                <p class="mt-1 text-xs text-white/40">توسط مدیر سیستم · شماره نمونه: INV-1025</p>
                            </div>
                            <span class="shrink-0 text-[11px] text-white/30">۱۰:۳۵</span>
                        </div>

                        <div class="flex items-start gap-3 py-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/10 text-amber-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6M9 12h6"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-6 text-white/85">یک پیش‌فاکتور ثبت شد</p>
                                <p class="mt-1 text-xs text-white/40">توسط مدیر فروش · شماره نمونه: PRO-1082</p>
                            </div>
                            <span class="shrink-0 text-[11px] text-white/30">۰۹:۵۰</span>
                        </div>

                        <div class="flex items-start gap-3 py-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-400/10 text-sky-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2M17 4.5a4 4 0 0 1 0 7.5"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-6 text-white/85">اطلاعات یک مشتری ثبت شد</p>
                                <p class="mt-1 text-xs text-white/40">توسط مدیر سیستم · بخش مشتریان</p>
                            </div>
                            <span class="shrink-0 text-[11px] text-white/30">دیروز</span>
                        </div>

                        <div class="flex items-start gap-3 py-4 last:pb-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-400/10 text-rose-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 3v18M17 7H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-6 text-white/85">اطلاعات یک محصول ویرایش شد</p>
                                <p class="mt-1 text-xs text-white/40">توسط مدیر محتوا · بخش محصولات</p>
                            </div>
                            <span class="shrink-0 text-[11px] text-white/30">دیروز</span>
                        </div>

                    </div>
                </div>


                <!-- Quick Actions -->
                <div id="quick-actions" class="rounded-2xl border border-white/[0.08] bg-[#252525]/85 p-5 sm:p-6 xl:col-span-2">

                    <div>
                        <h2 class="kohan-bold text-base font-bold">دسترسی سریع</h2>
                        <p class="mt-1 text-xs text-white/40">عملیات پرکاربرد پنل</p>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <a href="#" class="group rounded-xl border border-white/[0.07] bg-white/[0.025] p-4 transition hover:border-[#681b21]/60 hover:bg-[#681b21]/10">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#681b21]/20 text-rose-300 transition group-hover:bg-[#681b21]/35">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-semibold kohan-bold">صدور فاکتور</p>
                            <p class="mt-1 text-xs leading-5 text-white/40">ثبت فاکتور جدید</p>
                        </a>

                        <a href="#" class="group rounded-xl border border-white/[0.07] bg-white/[0.025] p-4 transition hover:border-amber-500/30 hover:bg-amber-500/[0.05]">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400 transition group-hover:bg-amber-500/20">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-7-4-7 4V5a2 2 0 0 1 2-2Z"/>
                                    <path d="M9 8h6"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-semibold kohan-bold">پیش‌فاکتور جدید</p>
                            <p class="mt-1 text-xs leading-5 text-white/40">ایجاد پیش‌فاکتور</p>
                        </a>

                        <a href="#" class="group rounded-xl border border-white/[0.07] bg-white/[0.025] p-4 transition hover:border-sky-500/30 hover:bg-sky-500/[0.05]">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-400 transition group-hover:bg-sky-500/20">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <circle cx="9" cy="8" r="4"/>
                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                                    <path d="M19 8v6m-3-3h6" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-semibold kohan-bold">ثبت مشتری</p>
                            <p class="mt-1 text-xs leading-5 text-white/40">افزودن مشتری جدید</p>
                        </a>

                        <a href="#" class="group rounded-xl border border-white/[0.07] bg-white/[0.025] p-4 transition hover:border-emerald-500/30 hover:bg-emerald-500/[0.05]">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 transition group-hover:bg-emerald-500/20">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-semibold kohan-bold">افزودن محصول</p>
                            <p class="mt-1 text-xs leading-5 text-white/40">ثبت محصول جدید</p>
                        </a>

                    </div>
                </div>

            </section>

@endsection