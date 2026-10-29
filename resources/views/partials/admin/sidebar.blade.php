<!-- Admin Panel Sidebar Nav -->
<aside class="hidden md:flex fixed inset-y-3 right-3 z-50 flex w-[272px] flex-col overflow-hidden rounded-[22px] border border-[#DDDDDD] bg-white p-[22px_15px_15px] text-[#222222] shadow-[0_18px_55px_rgba(34,34,34,0.10)] max-md:inset-y-2 max-md:right-2 max-md:w-[min(272px,calc(100vw-16px))] max-md:rounded-[18px]">

    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}"
       class="flex min-h-[58px] shrink-0 items-center gap-3 px-[5px] text-[#222222] no-underline">

        <img
            src="{{ asset('img/kohan-logo.png') }}"
            alt="لوگوی کهن پلیمر البرز"
            class="h-[45px] w-[45px] shrink-0 rounded-lg bg-white object-contain"
        >

        <div class="flex min-w-0 flex-col gap-[5px]">
            <span class="kohan-bold whitespace-nowrap text-sm leading-relaxed">
                کهن پلیمر البرز
            </span>

            <span class="text-[11px] text-[#666666]">
                پنل مدیریت
            </span>
        </div>
    </a>

    <!-- Divider -->
    <div class="my-5 h-px shrink-0 bg-[#DDDDDD]"></div>

    {{-- Sidebar Navigation --}}
    <nav class="flex min-h-0 flex-1 flex-col gap-[5px] overflow-x-hidden overflow-y-auto [scrollbar-color:#BFBFBF_transparent] [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-[#BFBFBF]">

        <span class="kohan-bold mx-[11px] mb-2 mt-[13px] text-[10px] tracking-[0.2px] text-[#666666]">
            منوی اصلی
        </span>

        @php
            $sidebarItems = [
                [
                    'label' => 'داشبورد',
                    'route' => 'admin.dashboard',
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
                    'icon' => 'blog',
                ],
            ];
        @endphp

        @foreach ($sidebarItems as $item)
            @php
                $isActive = isset($item['active'])
                    ? request()->routeIs($item['active'])
                    : ($item['route'] ? request()->routeIs($item['route']) : false);

                $linkClasses = $isActive
                    ? 'relative border-[#681b21]/15 bg-[#681b21]/[0.07] text-[#681b21] before:absolute before:bottom-[10px] before:right-0 before:top-[10px] before:w-[3px] before:rounded-l before:bg-[#681b21]'
                    : 'border-transparent text-[#666666] hover:border-[#DDDDDD] hover:bg-[#F2F2F2] hover:text-[#222222] active:scale-[0.985]';
            @endphp

            <a
                href="{{ route($item['route']) }}"
                @if ($isActive) aria-current="page" @endif
                class="group flex min-h-[45px] shrink-0 items-center gap-3 rounded-xl border px-3 text-xs font-medium transition-all duration-200 {{ $linkClasses }}"
            >
                @if ($item['icon'] === 'dashboard')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/>
                        <rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/>
                        <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/>
                        <rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
                    </svg>
                @elseif ($item['icon'] === 'content')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                @elseif ($item['icon'] === 'products')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Z"/>
                        <path d="M3 12l9 4.5 9-4.5M3 16.5l9 4.5 9-4.5"/>
                    </svg>
                @elseif ($item['icon'] === 'blog')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 4h14v16H5z"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                @endif

                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <span class="kohan-bold mx-[11px] mb-2 mt-[13px] text-[10px] tracking-[0.2px] text-[#666666]">
            امور مالی و مشتریان
        </span>

        @php
            $sidebarItems = [
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
                    'route' => null,
                    'icon' => 'reports',
                ],
            ];
        @endphp

        @foreach ($sidebarItems as $item)
            @php
                $isActive = isset($item['active'])
                    ? request()->routeIs($item['active'])
                    : ($item['route'] ? request()->routeIs($item['route']) : false);

                $linkClasses = $isActive
                    ? 'relative border-[#681b21]/15 bg-[#681b21]/[0.07] text-[#681b21] before:absolute before:bottom-[10px] before:right-0 before:top-[10px] before:w-[3px] before:rounded-l before:bg-[#681b21]'
                    : 'border-transparent text-[#666666] hover:border-[#DDDDDD] hover:bg-[#F2F2F2] hover:text-[#222222] active:scale-[0.985]';
            @endphp

            <a
                href="{{ $item['route'] ? route($item['route']) : '#' }}"
                @if ($isActive) aria-current="page" @endif
                class="group flex min-h-[45px] shrink-0 items-center gap-3 rounded-xl border px-3 text-xs font-medium transition-all duration-200 {{ $linkClasses }}"
            >
                @if ($item['icon'] === 'customers')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="8" r="3.5"/>
                        <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
                        <path d="M16 5a3.5 3.5 0 0 1 0 6.8M18 14a5 5 0 0 1 3.5 4.8V20"/>
                    </svg>
                @elseif ($item['icon'] === 'invoices')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 3h9l4 4v14H6z"/>
                        <path d="M14 3v5h5M9 12h7M9 16h7"/>
                    </svg>
                @elseif ($item['icon'] === 'reports')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                    </svg>
                @endif

                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <span class="kohan-bold mx-[11px] mb-2 mt-[13px] text-[10px] tracking-[0.2px] text-[#666666]">
            مدیریت سیستم
        </span>

        @php
            $sidebarItems = [
                [
                    'label' => 'مدیران و دسترسی‌ها',
                    'route' => null,
                    'icon' => 'admins',
                ],
                [
                    'label' => 'گزارش فعالیت‌ها',
                    'route' => null,
                    'icon' => 'activity',
                ],
            ];
        @endphp

        @foreach ($sidebarItems as $item)
            @php
                $isActive = isset($item['active'])
                    ? request()->routeIs($item['active'])
                    : ($item['route'] ? request()->routeIs($item['route']) : false);

                $linkClasses = $isActive
                    ? 'relative border-[#681b21]/15 bg-[#681b21]/[0.07] text-[#681b21] before:absolute before:bottom-[10px] before:right-0 before:top-[10px] before:w-[3px] before:rounded-l before:bg-[#681b21]'
                    : 'border-transparent text-[#666666] hover:border-[#DDDDDD] hover:bg-[#F2F2F2] hover:text-[#222222] active:scale-[0.985]';
            @endphp

            <a
                href="{{ $item['route'] ? route($item['route']) : '#' }}"
                @if ($isActive) aria-current="page" @endif
                class="group flex min-h-[45px] shrink-0 items-center gap-3 rounded-xl border px-3 text-xs font-medium transition-all duration-200 {{ $linkClasses }}"
            >
                @if ($item['icon'] === 'admins')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="8" r="3.5"/>
                        <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
                        <path d="M17 8h5M19.5 5.5v5"/>
                    </svg>
                @elseif ($item['icon'] === 'activity')
                    <svg class="h-[19px] w-[19px] shrink-0 {{ $isActive ? 'text-[#681b21]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                @endif

                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

    </nav>

    <!-- Footer -->
    <div class="mt-3 shrink-0">

        <div class="my-3 h-px bg-[#DDDDDD]"></div>

        <!-- Admin profile -->
        <div class="flex items-center gap-[11px] px-1 pb-4">
            <div class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-[13px] border border-[#681b21]/15 bg-[#681b21]/[0.07] text-[#681b21]">
                <svg class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21a8 8 0 0 1 16 0"/>
                </svg>
            </div>

            <div class="flex min-w-0 flex-col gap-[5px]">
                <span class="text-[10px] text-[#666666]">
                    وارد شده به‌عنوان
                </span>

                <strong class="kohan-bold truncate text-xs text-[#222222]">
                    مدیر سیستم
                </strong>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 gap-2">

            <a href="#"
               class="flex min-h-[41px] min-w-0 items-center justify-center gap-[7px] rounded-[11px] border border-[#DDDDDD] bg-[#F2F2F2] px-1 py-2 text-[10px] font-semibold text-[#666666] no-underline transition-colors duration-200 hover:border-[#681b21]/20 hover:bg-[#681b21]/[0.07] hover:text-[#681b21] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#681b21] kohan-bold">

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
                    class="flex min-h-[41px] w-full min-w-0 cursor-pointer items-center justify-center gap-[7px] rounded-[11px] border border-[#DDDDDD] bg-[#F2F2F2] px-1 py-2 text-[10px] font-semibold text-[#666666] transition-colors duration-200 hover:border-[#681b21]/20 hover:bg-[#681b21]/[0.07] hover:text-[#681b21] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#681b21] kohan-bold"
                >
                    <svg
                        class="h-4 w-4 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M10 17l5-5-5-5M15 12H3"/>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                    </svg>

                    <span>خروج از حساب</span>
                </button>
            </form>

        </div>
    </div>

</aside>