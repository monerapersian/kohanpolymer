<!-- Mobile Navigation -->
<nav
    dir="rtl"
    aria-label="منوی موبایل"
    class="fixed bottom-3 left-3 right-3 z-[60] flex h-[78px] items-center justify-around rounded-[24px] border border-[#E8E1DE] bg-white/95 px-1 shadow-[0_8px_30px_rgba(54,39,39,0.12)] backdrop-blur-xl md:hidden"
>
    <!-- Website Content -->
    <a
        href="{{ route('admin.site-content.edit') }}"
        @if(request()->routeIs('admin.site-content.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 {{ request()->routeIs('admin.site-content.*') ? 'text-[#542b2e]' : 'text-[#88807D] hover:text-[#542b2e]' }}"
    >
        <svg class="h-[22px] w-[22px] transition-transform duration-200 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="4" y="3" width="16" height="18" rx="2"/>
            <path d="M8 8h8M8 12h8M8 16h5"/>
        </svg>
        <span class="kohan-bold whitespace-nowrap text-[11px]">
            محتوا سایت
        </span>
    </a>

    <!-- Invoices -->
    <a
        href="{{ route('admin.invoices.index') }}"
        @if(request()->routeIs('admin.invoices.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 {{ request()->routeIs('admin.invoices.*') ? 'text-[#542b2e]' : 'text-[#88807D] hover:text-[#542b2e]' }}"
    >
        <svg class="h-[22px] w-[22px] transition-transform duration-200 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
            <path d="M14 3v5h5M8 12h8M8 16h8"/>
        </svg>
        <span class="kohan-bold whitespace-nowrap text-[11px]">
            فاکتورها
        </span>
    </a>

    <!-- Dashboard -->
    <a
        href="{{ route('admin.dashboard') }}"
        @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
        class="group relative -mt-7 flex min-w-0 flex-1 cursor-pointer flex-col items-center justify-center gap-1.5 transition duration-200 active:scale-95"
    >
        <span class="flex h-[56px] w-[56px] items-center justify-center rounded-[19px] border border-[#542b2e]/20 bg-[#542b2e] text-white shadow-[0_7px_20px_rgba(84,43,46,0.28)] transition duration-300 group-hover:-translate-y-1">
            <svg class="h-[25px] w-[25px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/>
                <rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/>
                <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/>
                <rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
            </svg>
        </span>
        <span class="kohan-bold whitespace-nowrap text-[11px] text-[#542b2e]">
            داشبورد
        </span>
    </a>

    <!-- Customers -->
    <a
        href="{{ route('admin.customers.index') }}"
        @if(request()->routeIs('admin.customers.*')) aria-current="page" @endif
        class="group flex min-w-0 flex-1 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl py-2 transition duration-200 {{ request()->routeIs('admin.customers.*') ? 'text-[#542b2e]' : 'text-[#88807D] hover:text-[#542b2e]' }}"
    >
        <svg class="h-[22px] w-[22px] transition-transform duration-200 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="8" r="3.5"/>
            <path d="M2.5 20v-1.5a6.5 6.5 0 0 1 13 0V20z"/>
            <path d="M16 5a3.5 3.5 0 0 1 0 6.8M18 14a5 5 0 0 1 3.5 4.8V20"/>
        </svg>
        <span class="kohan-bold whitespace-nowrap text-[11px]">
            مشتریان
        </span>
    </a>

    <!-- Settings: Not Available Yet -->
    <a
        href="#"
        aria-disabled="true"
        title="بخش تنظیمات هنوز آماده نیست"
        class="group flex min-w-0 flex-1 cursor-not-allowed flex-col items-center justify-center gap-1.5 rounded-2xl py-2 text-[#C3BAB6]"
    >
        <svg class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="3"/>
            <path d="m19.4 15 .1.1 1.4 1.1-1.5 2.6-1.7-.7a8 8 0 0 1-1.8 1l-.3 1.9h-3l-.3-1.9a8 8 0 0 1-1.8-1l-1.7.7-1.5-2.6 1.5-1.2a7 7 0 0 1 0-2l-1.5-1.2 1.5-2.6 1.7.7a8 8 0 0 1 1.8-1l.3-1.9h3l.3 1.9a8 8 0 0 1 1.8 1l1.7-.7 1.5 2.6-1.5 1.2a7 7 0 0 1 0 2Z"/>
        </svg>
        <span class="kohan-bold whitespace-nowrap text-[11px]">
            تنظیمات
        </span>
    </a>
</nav>