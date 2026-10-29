<!-- Header -->
<header class="site-header" id="siteHeader">
    <div class="header-inner">

        <a
            href="{{ url('/') }}"
            class="brand"
            aria-label="صفحه اصلی کهن پلیمر البرز"
        >
            @if (isset($content) && $content?->logo)
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($content->logo) }}"
                    alt="لوگوی کهن پلیمر البرز"
                    class="brand-symbol object-contain bg-white"
                >
            @else
                <span class="brand-symbol">ک</span>
            @endif

            <span>
                <span class="brand-name block">کهن پلیمر البرز</span>
                <span class="brand-caption block">مهندسی در ساختار مواد</span>
            </span>
        </a>

        <nav class="desktop-nav" aria-label="منوی اصلی">
            <a href="{{ url('/') }}#home">صفحه اصلی</a>
            <a href="{{ url('/') }}#about">درباره ما</a>
            <a href="{{ url('/') }}#products">محصولات</a>
            <a href="{{ url('/') }}#articles">دانش و مقالات</a>
            <a href="{{ url('/') }}#contact">تماس با ما</a>
        </nav>

        <a href="{{ url('/') }}#contact" class="header-cta">
            درخواست همکاری <span class="mr-2">↗</span>
        </a>

        <button
            class="menu-toggle"
            id="menuToggle"
            type="button"
            aria-label="باز کردن منو"
            aria-expanded="false"
            aria-controls="mobileNav"
        >
            <span></span>
        </button>
    </div>

    <nav class="mobile-menu" id="mobileNav" aria-label="منوی موبایل" inert>
        <a href="{{ url('/') }}#home">صفحه اصلی</a>
        <a href="{{ url('/') }}#about">درباره ما</a>
        <a href="{{ url('/') }}#products">محصولات</a>
        <a href="{{ url('/') }}#articles">دانش و مقالات</a>
        <a href="{{ url('/') }}#contact">تماس با ما</a>
    </nav>
</header>