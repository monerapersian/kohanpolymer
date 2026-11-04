<!-- Admin Panel Sidebar -->
<aside
    dir="rtl"
    class="hidden md:flex fixed inset-y-3 right-3 z-50 w-[272px] flex-col overflow-hidden rounded-[22px] border border-[#E8E1DE] bg-white p-5 text-[#362727] shadow-[0_12px_40px_rgba(54,39,39,0.07)]"
>
    <!-- Dust Particle Background -->
    <div class="sidebar-dust" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="relative z-10 flex h-full flex-col">

        <!-- Brand -->
        <a
            href="{{ route('admin.dashboard') }}"
            class="flex min-h-[62px] shrink-0 items-center gap-3 px-1 text-[#362727] no-underline"
        >
            <img
                src="{{ asset('img/kohan-logo.png') }}"
                alt="لوگوی کهن پلیمر البرز"
                class="h-12 w-12 shrink-0 rounded-xl bg-white object-contain"
            >

            <div class="flex min-w-0 flex-col gap-1">
                <span class="kohan-bold whitespace-nowrap text-[15px] leading-relaxed">
                    کهن پلیمر البرز
                </span>
                <span class="text-xs text-[#88807D]">
                    پنل مدیریت
                </span>
            </div>
        </a>

        <div class="my-5 h-px shrink-0 bg-[#EEE8E5]"></div>

        <!-- Navigation -->
        <nav class="flex min-h-0 flex-1 flex-col gap-1 overflow-x-hidden overflow-y-auto [scrollbar-width:thin] [scrollbar-color:#D8CBC7_transparent] [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-[#D8CBC7]">

            @php
                $sidebarGroups = [
                    [
                        'title' => 'منوی اصلی',
                        'items' => [
                            [
                                'label' => 'داشبورد',
                                'route' => 'admin.dashboard',
                                'active' => 'admin.dashboard',
                                'icon' => 'dashboard',
                            ],
                            [
                                'label' => 'محتوا سایت',
                                'route' => 'admin.site-content.edit',
                                'active' => 'admin.site-content.*',
                                'icon' => 'content',
                            ],
                            [
                                'label' => 'درخواست ها',
                                'route' => 'admin.inquiries.index',
                                'active' => 'admin.inquiries.*',
                                'icon' => 'inquiries',
                            ],
                        ],
                    ],
                    [
                        'title' => 'امور مالی و مشتریان',
                        'items' => [
                            [
                                'label' => 'مشتریان',
                                'route' => 'admin.customers.index',
                                'active' => 'admin.customers.*',
                                'icon' => 'customers',
                            ],
                            [
                                'label' => 'فاکتورها',
                                'route' => 'admin.invoices.index',
                                'active' => 'admin.invoices.*',
                                'icon' => 'invoices',
                            ],
                            [
                                'label' => 'گزارش‌های مالی',
                                'route' => 'admin.reports.financial',
                                'active' => 'admin.reports.*',
                                'icon' => 'reports',
                            ],
                        ],
                    ],
                    [
                        'title' => 'مدیریت سیستم',
                        'items' => [
                            [
                                'label' => 'مدیران و دسترسی‌ها',
                                'route' => 'admin.managers.index',
                                'active' => 'admin.managers.*',
                                'icon' => 'admins',
                            ],
                            [
                                'label' => 'گزارش فعالیت‌ها',
                                'route' => null,
                                'icon' => 'activity',
                            ],
                        ],
                    ],
                ];
            @endphp

            @foreach ($sidebarGroups as $group)
                <div class="mt-3 first:mt-0">

                    <span class="kohan-bold mx-3 mb-2 block text-[11px] text-[#9A918D]">
                        {{ $group['title'] }}
                    </span>

                    <div class="flex flex-col gap-1">

                        @foreach ($group['items'] as $item)

                            @php
                                $isActive = isset($item['active'])
                                    ? request()->routeIs($item['active'])
                                    : false;

                                $linkClasses = $isActive
                                    ? 'border-[#542b2e]/10 bg-[#F3E9E9] text-[#542b2e] before:absolute before:bottom-2.5 before:right-0 before:top-2.5 before:w-[3px] before:rounded-l before:bg-[#542b2e]'
                                    : 'border-transparent text-[#716966] hover:border-[#E8E1DE] hover:bg-[#FAF8F7] hover:text-[#542b2e] active:scale-[0.985]';
                            @endphp

                            <a
                                href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                @if ($isActive) aria-current="page" @endif
                                @if (!$item['route']) aria-disabled="true" @endif
                                class="group relative flex min-h-[48px] shrink-0 items-center gap-3 rounded-xl border px-3 text-[13px] transition-all duration-200 {{ $linkClasses }} {{ !$item['route'] ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }}"
                            >

                                @if ($item['icon'] === 'dashboard')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/>
                                        <rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/>
                                        <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/>
                                        <rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
                                    </svg>

                                @elseif ($item['icon'] === 'content')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                        <path d="M8 8h8M8 12h8M8 16h5"/>
                                    </svg>

                                @elseif ($item['icon'] === 'inquiries')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/>
                                    </svg>

                                @elseif ($item['icon'] === 'customers')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="8" r="3.5"/>
                                        <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
                                        <path d="M16 5a3.5 3.5 0 0 1 0 6.8M18 14a5 5 0 0 1 3.5 4.8V20"/>
                                    </svg>

                                @elseif ($item['icon'] === 'invoices')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M6 3h9l4 4v14H6z"/>
                                        <path d="M14 3v5h5M9 12h7M9 16h7"/>
                                    </svg>

                                @elseif ($item['icon'] === 'reports')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                                    </svg>

                                @elseif ($item['icon'] === 'admins')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="8" r="3.5"/>
                                        <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
                                        <path d="M17 8h5M19.5 5.5v5"/>
                                    </svg>

                                @elseif ($item['icon'] === 'activity')
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 2"/>
                                    </svg>
                                @endif

                                <span>{{ $item['label'] }}</span>

                            </a>

                        @endforeach

                    </div>
                </div>
            @endforeach

        </nav>


        <!-- Footer -->
        <div class="mt-4 shrink-0">

            <div class="my-3 h-px bg-[#EEE8E5]"></div>

            <!-- Admin Profile -->
            <div class="flex items-center gap-3 px-1 pb-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[#542b2e]/10 bg-[#F3E9E9] text-[#542b2e]">
                    <svg class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </div>

                <div class="flex min-w-0 flex-col gap-1">
                    <span class="text-xs text-[#8B827E]">
                        وارد شده به‌عنوان
                    </span>
                    <strong class="kohan-bold truncate text-sm text-[#362727]">
                        مدیر سیستم
                    </strong>
                </div>

            </div>


            <!-- Actions -->
            <div class="grid grid-cols-2 gap-2">

                <a
                    href="#"
                    aria-disabled="true"
                    class="flex min-h-[44px] min-w-0 cursor-not-allowed items-center justify-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-2 text-xs text-[#77716F] opacity-60 kohan-bold"
                >
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="m19.4 15 .1.1 1.4 1.1-1.5 2.6-1.7-.7a8 8 0 0 1-1.8 1l-.3 1.9h-3l-.3-1.9a8 8 0 0 1-1.8-1l-1.7.7-1.5-2.6 1.5-1.2a7 7 0 0 1 0-2l-1.5-1.2 1.5-2.6 1.7.7a8 8 0 0 1 1.8-1l.3-1.9h3l.3 1.9a8 8 0 0 1 1.8 1l1.7-.7 1.5 2.6-1.5 1.2a7 7 0 0 1 0 2Z"/>
                    </svg>
                    <span>تنظیمات</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="flex min-h-[44px] w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-2 text-xs text-[#77716F] transition-colors hover:border-[#542b2e]/20 hover:bg-[#F3E9E9] hover:text-[#542b2e] kohan-bold"
                    >
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10 17l5-5-5-5M15 12H3"/>
                            <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                        </svg>
                        <span>خروج</span>
                    </button>
                </form>

            </div>
        </div>

    </div>
</aside>