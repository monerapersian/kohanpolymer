<!-- Mobile Floating Glass Navigation -->
<nav
    dir="rtl"
    aria-label="منوی موبایل"
    class="fixed bottom-3 left-3 right-3 z-[60] flex h-[76px] items-center justify-around rounded-[24px] border border-white/[0.14] bg-gradient-to-br from-[#383030]/90 via-[#222222]/90 to-[#151515]/95 px-1 shadow-[0_12px_40px_rgba(0,0,0,0.4),inset_0_1px_0_rgba(255,255,255,0.10)] backdrop-blur-2xl backdrop-saturate-150 md:hidden"
>

    <!-- Website Content -->
    <a
        href="{{ route('admin.site-content.edit') }}"
        @if(request()->routeIs('admin.site-content.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 hover:text-white active:scale-90 {{ request()->routeIs('admin.site-content.*') ? 'text-white drop-shadow-[0_0_7px_rgba(185,92,100,0.75)]' : 'text-white/55' }}"
    >
        <svg
            class="h-[21px] w-[21px] transition-transform duration-200 group-hover:-translate-y-0.5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <rect x="4" y="3" width="16" height="18" rx="2"/>
            <path d="M8 8h8M8 12h8M8 16h5"/>
        </svg>

        <span class="whitespace-nowrap text-[10px] font-medium kohan-bold">
            محتوا سایت
        </span>
    </a>

    <!-- Invoices -->
    <a
        href="{{ route('admin.invoices.index') }}"
        @if(request()->routeIs('admin.invoices.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 hover:text-white active:scale-90 {{ request()->routeIs('admin.invoices.*') ? 'text-white drop-shadow-[0_0_7px_rgba(185,92,100,0.75)]' : 'text-white/55' }}"
    >
        <svg
            class="h-[21px] w-[21px] transition-transform duration-200 group-hover:-translate-y-0.5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
            <path d="M14 3v5h5M8 12h8M8 16h8"/>
        </svg>

        <span class="whitespace-nowrap text-[10px] font-medium kohan-bold">
            فاکتورها
        </span>
    </a>

    <!-- Dashboard: Featured Center Item -->
    <a
        href="{{ route('admin.dashboard') }}"
        @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
        class="group relative -mt-7 flex min-w-0 flex-1 flex-col items-center justify-center gap-1.5 text-white transition duration-200 active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'drop-shadow-[0_0_8px_rgba(185,92,100,0.8)]' : '' }}"
    >
        <span class="flex h-[54px] w-[54px] items-center justify-center rounded-[19px] border border-[#b95c64]/50 bg-gradient-to-br from-[#862a33] via-[#681b21] to-[#451318] text-[#ffe5e6] shadow-[0_8px_24px_rgba(104,27,33,0.5),inset_0_1px_0_rgba(255,255,255,0.18)] transition duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_10px_28px_rgba(104,27,33,0.6)]">
            <svg
                class="h-[25px] w-[25px]"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/>
                <rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/>
                <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/>
                <rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
            </svg>
        </span>

        <span class="whitespace-nowrap text-[10px] font-bold text-[#f0b6ba] kohan-bold">
            داشبورد
        </span>
    </a>

    <!-- Customers -->
    <a
        href="{{ route('admin.customers.index') }}"
        @if(request()->routeIs('admin.customers.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 hover:text-white active:scale-90 {{ request()->routeIs('admin.customers.*') ? 'text-white drop-shadow-[0_0_7px_rgba(185,92,100,0.75)]' : 'text-white/55' }}"
    >
        <svg
            class="h-[21px] w-[21px] transition-transform duration-200 group-hover:-translate-y-0.5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <circle cx="9" cy="8" r="3.5"/>
            <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
            <path d="M16 5a3.5 3.5 0 0 1 0 6.8M18 14a5 5 0 0 1 3.5 4.8V20"/>
        </svg>

        <span class="whitespace-nowrap text-[10px] font-medium kohan-bold">
            مشتریان
        </span>
    </a>

    <!-- Settings: Route Not Defined Yet -->
    <a
        href="#"
        aria-disabled="true"
        title="بخش تنظیمات هنوز آماده نیست"
        class="group flex min-w-0 flex-1 cursor-not-allowed flex-col items-center justify-center gap-1.5 rounded-2xl py-2 text-white/35 transition duration-200"
    >
        <svg
            class="h-[21px] w-[21px]"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <circle cx="12" cy="12" r="3"/>
            <path d="m19.4 15 .1.1 1.4 1.1-1.5 2.6-1.7-.7a8 8 0 0 1-1.8 1l-.3 1.9h-3l-.3-1.9a8 8 0 0 1-1.8-1l-1.7.7-1.5-2.6 1.5-1.2a7 7 0 0 1 0-2l-1.5-1.2 1.5-2.6 1.7.7a8 8 0 0 1 1.8-1l.3-1.9h3l.3 1.9a8 8 0 0 1 1.8 1l1.7-.7 1.5 2.6-1.5 1.2a7 7 0 0 1 0 2Z"/>
        </svg>

        <span class="whitespace-nowrap text-[10px] font-medium kohan-bold">
            تنظیمات
        </span>
    </a>

</nav>