
    <!-- ================= HEADER ================= -->
    <header class="glass-header fixed top-0 inset-x-0 z-50">
        <div class="container-custom flex items-center justify-between gap-5 py-4">

            <a href="#home" class="flex items-center gap-4 shrink-0" aria-label="صفحه اصلی کهن پلیمر البرز">
                <div class="brand-img">
                    <img
                        src="{{ asset('img/kohan-logo.png') }}"
                        alt="لوگوی کهن پلیمر البرز"
                        style="height:40px;"
                    >
                </div>

                <div>
                    <div class="header-brand-name font-extrabold text-lg leading-7">
                        کهن پلیمر
                        <span class="text-brand text-[#681b21]">البرز</span>
                    </div>

                    <div class="header-brand-subtitle text-xs tracking-widest">
                        KOHAN POLYMER
                        <span class="text-brand text-[#681b21]">ALBORZ</span>
                    </div>
                </div>
            </a>

            <nav id="mainNav" class="hidden md:flex items-center gap-8 text-sm kohan-bold">
                <a href="#home">صفحه اصلی</a>
                <a href="#products">محصولات</a>
                <a href="#about">درباره ما</a>
                <a href="#advantages">مزیت‌های ما</a>
                <a href="#contact">تماس با ما</a>
            </nav>

            <a href="#contact" class="btn-outline header-cta !hidden md:!inline-flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-bold kohan-bold">
                درخواست همکاری
            </a>

            <button
                id="menuToggle"
                type="button"
                class="md:hidden w-11 h-11 border grid place-items-center rounded-lg"
                aria-label="باز کردن منو"
                aria-expanded="false"
                aria-controls="mobileNav"
            >
                <svg id="menuIcon" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>

        <nav id="mobileNav" class="hidden md:hidden border-t px-5 py-4">
            <div class="flex flex-col gap-5 text-sm">
                <a href="#home" class="mobile-link">صفحه اصلی</a>
                <a href="#products" class="mobile-link">محصولات</a>
                <a href="#about" class="mobile-link">درباره ما</a>
                <a href="#advantages" class="mobile-link">مزیت‌های ما</a>
                <a href="#contact" class="mobile-link">تماس با ما</a>
            </div>
        </nav>
    </header>