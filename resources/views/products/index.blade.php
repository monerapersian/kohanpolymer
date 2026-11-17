@extends('layouts.site')

@section('title', 'محصولات | کهن پلیمر البرز')

@section('description', 'مشاهده محصولات پلیمری کهن پلیمر البرز؛ بررسی محصولات ویژه و آشنایی با مجموعه محصولات صنعتی.')

@section('content')

<div class="products-page">

    {{-- Page Hero --}}
    <section class="products-hero">
        <div class="products-hero-grid"></div>
        <div class="products-hero-glow products-hero-glow-one"></div>
        <div class="products-hero-glow products-hero-glow-two"></div>

        <div class="container-main products-hero-inner">
            <div class="products-hero-content">
                <div class="products-eyebrow">
                    <span></span>
                    محصولات کهن پلیمر البرز
                </div>

                <h1>
                    راهکارهای پلیمری
                    <span>برای صنعت امروز</span>
                </h1>

                <p>
                    مجموعه‌ای از محصولات پلیمری با تمرکز بر کیفیت ساخت،
                    عملکرد قابل اعتماد و پاسخ‌گویی به نیازهای متنوع صنایع.
                </p>

                <div class="products-hero-actions">
                    <a href="#all-products" class="products-btn-light">
                        مشاهده محصولات
                        <span>←</span>
                    </a>

                    <a href="#featured-products" class="products-btn-outline">
                        محصولات ویژه
                    </a>
                </div>
            </div>

            <div class="products-hero-visual">
                <div class="products-hero-orbit orbit-one"></div>
                <div class="products-hero-orbit orbit-two"></div>

                <div class="products-hero-symbol">
                    <span>KPA</span>
                    <small>KOHAN POLYMER ALBORZ</small>
                </div>

                <div class="products-hero-caption">
                    <span>مهندسی، کیفیت، ماندگاری</span>
                    <strong>راهکار مطمئن صنعت</strong>
                </div>
            </div>
        </div>

        <div class="container-main products-hero-bottom">
            <span>کهن پلیمر البرز</span>
            <span>کیفیت در جزئیات معنا پیدا می‌کند</span>
        </div>
    </section>


    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
        <section class="featured-section" id="featured-products">
            <div class="container-main">

                <div class="products-section-heading">
                    <div>
                        <span class="products-section-kicker">منتخب کهن پلیمر</span>
                        <h2>محصولات <span>ویژه</span></h2>
                        <p>
                            نگاهی به محصولات منتخب و ویژه مجموعه کهن پلیمر البرز
                        </p>
                    </div>

                    <div class="featured-controls">
                        <button
                            type="button"
                            class="featured-arrow"
                            id="featured-prev"
                            aria-label="محصول قبلی"
                        >
                            →
                        </button>

                        <button
                            type="button"
                            class="featured-arrow"
                            id="featured-next"
                            aria-label="محصول بعدی"
                        >
                            ←
                        </button>
                    </div>
                </div>

                <div class="featured-slider-wrapper">
                    <div class="featured-slider" id="featured-slider">

                        @foreach($featuredProducts as $product)
                            <article class="featured-card">

                                <div class="featured-card-image">
                                    @if($product->image)
                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="product-image-placeholder">
                                            <span>KPA</span>
                                            <small>KOHAN POLYMER ALBORZ</small>
                                        </div>
                                    @endif

                                    <!-- <span class="featured-badge">
                                        محصول ویژه
                                    </span> -->
                                </div>

                                <div class="featured-card-content">
                                    <span class="featured-card-number">
                                        محصول منتخب
                                    </span>

                                    <h3>{{ $product->name }}</h3>

                                    <p>
                                        {{ $product->description ?: 'برای دریافت اطلاعات بیشتر درباره مشخصات و کاربرد این محصول با کارشناسان ما در ارتباط باشید.' }}
                                    </p>

                                    <a href="{{ route('products.show', $product) }}" class="product-card-link">
                                        درخواست اطلاعات محصول
                                        <span>←</span>
                                    </a>
                                </div>

                            </article>
                        @endforeach

                    </div>
                </div>

                <div class="featured-slider-progress">
                    <span id="featured-progress"></span>
                </div>

            </div>
        </section>
    @endif


    {{-- All Products --}}
    <section class="all-products-section" id="all-products">
        <div class="container-main">

            <div class="products-section-heading all-products-heading">
                <div>
                    <span class="products-section-kicker">کاتالوگ محصولات</span>
                    <h2>همه <span>محصولات</span></h2>
                    <p>
                        محصولات منتشرشده کهن پلیمر البرز را بررسی کنید.
                    </p>
                </div>

                <div class="products-total">
                    <strong>{{ $products->total() }}</strong>
                    <span>محصول منتشرشده</span>
                </div>
            </div>


            {{-- Search and Sort --}}
            <form
                action="{{ route('products.index') }}"
                method="GET"
                class="products-filter"
                id="products-filter"
            >
                <div class="products-search">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/>
                        <path d="M16 16L21 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="نام محصول مورد نظر را جست‌وجو کنید..."
                        aria-label="جست‌وجوی محصول"
                    >

                    @if(!empty($search))
                        <a
                            href="{{ route('products.index', ['sort' => $sort]) }}"
                            class="products-clear-search"
                            aria-label="پاک کردن جست‌وجو"
                        >
                            ×
                        </a>
                    @endif
                </div>

                <div class="products-sort">
                    <label for="products-sort">مرتب‌سازی:</label>

                    <select name="sort" id="products-sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>
                            جدیدترین
                        </option>

                        <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>
                            قدیمی‌ترین
                        </option>
                    </select>
                </div>

                <button type="submit" class="products-filter-submit">
                    اعمال فیلتر
                </button>
            </form>


            {{-- Products Grid --}}
            @if($products->isNotEmpty())
                <div class="products-list-grid">

                    @foreach($products as $product)
                        <a
                            href="{{ route('products.show', $product->id) }}"
                            class="catalog-product-card"
                            style="display: block; color: inherit; text-decoration: none;"
                        >

                            <div class="catalog-product-image">

                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="product-image-placeholder">
                                        <span>KP</span>
                                        <small>KOHAN POLYMER</small>
                                    </div>
                                @endif

                                @if($product->is_featured)
                                    <span class="catalog-featured-badge">
                                        ویژه
                                    </span>
                                @endif

                                <span class="catalog-product-index">
                                    {{ sprintf('%02d', $loop->iteration + ($products->currentPage() - 1) * $products->perPage()) }}
                                </span>
                            </div>

                            <div class="catalog-product-content">
                                <span class="catalog-product-label">
                                    KOHAN POLYMER
                                </span>

                                <h3>{{ $product->name }}</h3>

                                <p>
                                    {{ $product->description ?: 'برای آشنایی بیشتر با مشخصات فنی و کاربردهای این محصول با کارشناسان ما در ارتباط باشید.' }}
                                </p>

                                <div class="catalog-product-footer">
                                    <span>
                                        مشاهده مشخصات محصول
                                        <span>←</span>
                                    </span>

                                    <span class="catalog-product-dot"></span>
                                </div>
                            </div>

                        </a>
                    @endforeach

                </div>


                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="products-pagination">
                        {{ $products->links() }}
                    </div>
                @endif

            @else
                <div class="products-empty">
                    <div class="products-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.6"/>
                            <path d="M16 16L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h3>محصولی پیدا نشد</h3>

                    <p>
                        محصولی با عبارت جست‌وجوشده پیدا نکردیم.
                        عبارت دیگری را امتحان کنید.
                    </p>

                    <a href="{{ route('products.index') }}">
                        مشاهده همه محصولات
                    </a>
                </div>
            @endif

        </div>
    </section>


    {{-- Contact CTA --}}
    <section class="products-contact-section">
        <div class="container-main">
            <div class="products-contact-panel">

                <div class="products-contact-decoration"></div>

                <div class="products-contact-content">
                    <span>در کنار شما برای انتخاب بهتر</span>

                    <h2>
                        برای انتخاب محصول مناسب
                        <strong>با ما در ارتباط باشید.</strong>
                    </h2>

                    <p>
                        کارشناسان کهن پلیمر البرز آماده پاسخ‌گویی به پرسش‌ها
                        و ارائه اطلاعات بیشتر درباره محصولات هستند.
                    </p>
                </div>

                <a href="{{ url('/#contact') }}" class="products-contact-button">
                    ارتباط با کارشناسان
                    <span>←</span>
                </a>

            </div>
        </div>
    </section>

</div>

@endsection


@push('styles')
<style>
    /* Products page */
    .products-page {
        background: var(--paper);
        overflow: hidden;
    }

    .products-page a {
        text-decoration: none;
    }

    /* Hero */
    .products-hero {
        position: relative;
        min-height: 560px;
        padding: 165px 0 30px;
        overflow: hidden;
        color: #fff;
        background: var(--dark);
        isolation: isolate;
    }

    .products-hero-grid {
        position: absolute;
        inset: 0;
        z-index: -1;
        opacity: .1;
        background-image:
            linear-gradient(rgba(244,240,238,.15) 1px, transparent 1px),
            linear-gradient(90deg, rgba(244,240,238,.15) 1px, transparent 1px);
        background-size: 65px 65px;
        mask-image: linear-gradient(to left, black, transparent 90%);
    }

    .products-hero-glow {
        position: absolute;
        z-index: -1;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        pointer-events: none;
        background: radial-gradient(
            circle,
            rgba(103,64,64,.8),
            rgba(103,64,64,.25) 45%,
            transparent 72%
        );
        filter: blur(5px);
    }

    .products-hero-glow-one {
        top: -300px;
        right: -120px;
        animation: productsGlowOne 12s ease-in-out infinite alternate;
    }

    .products-hero-glow-two {
        bottom: -400px;
        left: 10%;
        animation: productsGlowTwo 14s ease-in-out infinite alternate;
    }

    .products-hero-inner {
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        align-items: center;
        gap: 50px;
    }

    .products-hero-content {
        position: relative;
        z-index: 2;
    }

    .products-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 25px;
        color: #d9caca;
        font-size: 12px;
        font-weight: 700;
    }

    .products-eyebrow span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--gray);
        box-shadow: 0 0 0 5px rgba(186,173,173,.12);
    }

    .products-hero-content h1 {
        max-width: 650px;
        font-size: clamp(38px, 5.5vw, 72px);
        line-height: 1.5;
        font-weight: 900;
        letter-spacing: -.05em;
    }

    .products-hero-content h1 span {
        display: block;
        color: var(--gray);
    }

    .products-hero-content > p {
        max-width: 510px;
        margin-top: 18px;
        color: #d1c2c2;
        font-size: 13px;
        line-height: 2.6;
    }

    .products-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }

    .products-btn-light,
    .products-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        min-height: 49px;
        padding: 0 20px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 800;
        transition: .3s ease;
    }

    .products-btn-light {
        color: var(--dark);
        background: var(--paper);
    }

    .products-btn-light:hover {
        background: #fff;
        transform: translateY(-3px);
    }

    .products-btn-light span {
        font-size: 20px;
    }

    .products-btn-outline {
        color: #fff;
        border: 1px solid rgba(255,255,255,.25);
    }

    .products-btn-outline:hover {
        background: rgba(255,255,255,.09);
        border-color: rgba(255,255,255,.5);
    }

    /* Hero visual */
    .products-hero-visual {
        position: relative;
        display: grid;
        place-items: center;
        min-height: 320px;
    }

    .products-hero-orbit {
        position: absolute;
        width: 290px;
        height: 290px;
        border: 1px solid rgba(186,173,173,.2);
        border-radius: 50%;
    }

    .orbit-one {
        animation: orbitRotate 25s linear infinite;
    }

    .orbit-two {
        width: 230px;
        height: 230px;
        border-style: dashed;
        border-color: rgba(186,173,173,.3);
        animation: orbitRotate 35s linear infinite reverse;
    }

    .products-hero-symbol {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 185px;
        height: 185px;
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 38px;
        background: linear-gradient(145deg, #8d6666, #674040 55%, #362727);
        box-shadow:
            0 30px 80px rgba(0,0,0,.3),
            inset 0 1px 1px rgba(255,255,255,.2);
        transform: rotate(-7deg);
        animation: symbolFloat 6s ease-in-out infinite;
    }

    .products-hero-symbol span {
        color: #f4f0ee;
        font-size: 65px;
        font-weight: 900;
        letter-spacing: -7px;
    }

    .products-hero-symbol small {
        margin-top: 2px;
        color: #e1d4d4;
        font-size: 8px;
        letter-spacing: 3px;
    }

    .products-hero-caption {
        position: absolute;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 7px;
        padding: 15px 19px;
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 13px;
        background: rgba(54,39,39,.7);
        backdrop-filter: blur(12px);
    }

    .products-hero-caption span {
        color: #c9b7b7;
        font-size: 10px;
    }

    .products-hero-caption strong {
        color: #fff;
        font-size: 12px;
    }

    .products-hero-bottom {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,.15);
        color: #c9b7b7;
        font-size: 10px;
    }

    /* Shared section heading */
    .products-section-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 35px;
    }

    .products-section-kicker {
        display: inline-block;
        margin-bottom: 12px;
        color: var(--mid);
        font-size: 11px;
        font-weight: 800;
    }

    .products-section-heading h2 {
        color: var(--dark);
        font-size: clamp(29px, 4vw, 46px);
        line-height: 1.5;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .products-section-heading h2 span {
        color: var(--mid);
    }

    .products-section-heading p {
        margin-top: 7px;
        color: #807070;
        font-size: 12px;
        line-height: 2;
    }

    /* Featured section */
    .featured-section {
        position: relative;
        padding: 100px 0 90px;
        background: #eee7e3;
    }

    .featured-controls {
        display: flex;
        gap: 9px;
        flex-shrink: 0;
    }

    .featured-arrow {
        display: grid;
        place-items: center;
        width: 45px;
        height: 45px;
        border: 1px solid rgba(103,64,64,.2);
        border-radius: 12px;
        color: var(--wine);
        background: transparent;
        font-family: inherit;
        font-size: 20px;
        cursor: pointer;
        transition: .25s ease;
    }

    .featured-arrow:hover {
        color: #fff;
        background: var(--wine);
        border-color: var(--wine);
    }

    .featured-slider-wrapper {
        overflow: hidden;
        direction: rtl;
    }

    .featured-slider {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 5px 2px 18px;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        cursor: grab;
        touch-action: pan-y;
    }

    .featured-slider::-webkit-scrollbar {
        display: none;
    }

    .featured-slider.is-dragging {
        cursor: grabbing;
        scroll-behavior: auto;
        scroll-snap-type: none;
        user-select: none;
    }

    .featured-card {
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        flex: 0 0 calc((100% - 40px) / 3);
        min-width: 340px;
        min-height: 255px;
        overflow: hidden;
        border: 1px solid rgba(103,64,64,.1);
        border-radius: 18px;
        background: #f8f4f1;
        scroll-snap-align: start;
        box-shadow: 0 10px 30px rgba(54,39,39,.04);
        transition: transform .3s, box-shadow .3s;
    }

    .featured-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 35px rgba(54,39,39,.1);
    }

    .featured-card-image {
        position: relative;
        min-width: 0;
        min-height: 255px;
        overflow: hidden;
        background: #ded3ce;
    }

    .featured-card-image > img,
    .catalog-product-image > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .7s ease;
    }

    .featured-card:hover .featured-card-image > img,
    .catalog-product-card:hover .catalog-product-image > img {
        transform: scale(1.06);
    }

    .featured-badge {
        position: absolute;
        top: 13px;
        right: 13px;
        padding: 7px 11px;
        border-radius: 7px;
        color: #fff;
        background: var(--wine);
        font-size: 9px;
        font-weight: 800;
    }

    .featured-card-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 23px 18px;
        min-width: 0;
    }

    .featured-card-number {
        color: var(--mid);
        font-size: 9px;
        font-weight: 800;
    }

    .featured-card-content h3 {
        margin-top: 13px;
        color: var(--dark);
        font-size: 17px;
        font-weight: 900;
        line-height: 1.8;
    }

    .featured-card-content p {
        display: -webkit-box;
        overflow: hidden;
        margin-top: 9px;
        color: #806f6f;
        font-size: 10px;
        line-height: 2.2;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .product-card-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: auto;
        padding-top: 15px;
        color: var(--wine);
        font-size: 10px;
        font-weight: 800;
    }

    .product-card-link span {
        font-size: 17px;
        transition: transform .25s;
    }

    .product-card-link:hover span {
        transform: translateX(-4px);
    }

    .featured-slider-progress {
        height: 3px;
        margin-top: 12px;
        overflow: hidden;
        border-radius: 5px;
        background: rgba(103,64,64,.12);
    }

    .featured-slider-progress span {
        display: block;
        width: 0;
        height: 100%;
        border-radius: inherit;
        background: var(--wine);
        transition: width .2s;
    }

    /* Image placeholder */
    .product-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        min-height: inherit;
        color: rgba(255,255,255,.8);
        background:
            radial-gradient(circle at 70% 20%, rgba(186,173,173,.45), transparent 35%),
            linear-gradient(145deg, #8d6666, #674040 55%, #362727);
    }

    .product-image-placeholder span {
        font-size: 52px;
        font-weight: 900;
        letter-spacing: -5px;
    }

    .product-image-placeholder small {
        margin-top: 4px;
        font-size: 8px;
        letter-spacing: 3px;
    }

    /* All products */
    .all-products-section {
        padding: 100px 0;
        background: var(--paper);
    }

    .all-products-heading {
        margin-bottom: 30px;
    }

    .products-total {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 3px;
        padding: 13px 19px;
        border: 1px solid rgba(103,64,64,.14);
        border-radius: 12px;
        background: rgba(255,255,255,.35);
    }

    .products-total strong {
        color: var(--wine);
        font-size: 25px;
        font-weight: 900;
    }

    .products-total span {
        color: #887777;
        font-size: 10px;
    }

    /* Filter */
    .products-filter {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 35px;
        padding: 13px;
        border: 1px solid rgba(103,64,64,.12);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 8px 25px rgba(54,39,39,.035);
    }

    .products-search {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
        gap: 12px;
        padding: 0 12px;
    }

    .products-search svg {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        color: #987f7f;
    }

    .products-search input {
        width: 100%;
        min-width: 0;
        height: 43px;
        border: 0;
        outline: 0;
        color: var(--dark);
        background: transparent;
        font-family: inherit;
        font-size: 12px;
    }

    .products-search input::placeholder {
        color: #aa9b9b;
    }

    .products-clear-search {
        display: grid;
        place-items: center;
        width: 26px;
        height: 26px;
        flex-shrink: 0;
        border-radius: 50%;
        color: var(--wine);
        background: #f0e9e6;
        font-size: 20px;
    }

    .products-sort {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-inline: 15px;
        border-right: 1px solid #eee6e2;
        white-space: nowrap;
    }

    .products-sort label {
        color: #806f6f;
        font-size: 11px;
    }

    .products-sort select {
        min-width: 105px;
        height: 40px;
        padding: 0 10px;
        border: 0;
        outline: 0;
        color: var(--dark);
        background: transparent;
        font-family: inherit;
        font-size: 11px;
        cursor: pointer;
    }

    .products-filter-submit {
        min-height: 43px;
        padding: 0 20px;
        border: 0;
        border-radius: 10px;
        color: #fff;
        background: var(--wine);
        font-family: inherit;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition: .25s;
    }

    .products-filter-submit:hover {
        background: var(--dark);
    }

    /* Product cards */
    .products-list-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    .catalog-product-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid rgba(103,64,64,.1);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 25px rgba(54,39,39,.035);
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .catalog-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(54,39,39,.1);
    }

    .catalog-product-image {
        position: relative;
        height: 230px;
        overflow: hidden;
        background: #ded3ce;
    }

    .catalog-featured-badge {
        position: absolute;
        top: 13px;
        right: 13px;
        padding: 6px 10px;
        border-radius: 6px;
        color: #fff;
        background: var(--wine);
        font-size: 9px;
        font-weight: 800;
    }

    .catalog-product-index {
        position: absolute;
        bottom: 12px;
        left: 12px;
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border: 1px solid rgba(255,255,255,.4);
        border-radius: 9px;
        color: #fff;
        background: rgba(54,39,39,.65);
        backdrop-filter: blur(8px);
        font-size: 10px;
        font-weight: 800;
    }

    .catalog-product-content {
        padding: 19px 18px 17px;
    }

    .catalog-product-label {
        color: #a38d8d;
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .catalog-product-content h3 {
        min-height: 34px;
        margin-top: 9px;
        color: var(--dark);
        font-size: 15px;
        font-weight: 900;
        line-height: 1.9;
    }

    .catalog-product-content > p {
        display: -webkit-box;
        min-height: 66px;
        overflow: hidden;
        margin-top: 8px;
        color: #8c7c7c;
        font-size: 10px;
        line-height: 2.2;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .catalog-product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 17px;
        padding-top: 13px;
        border-top: 1px solid #f0eae7;
    }

    .catalog-product-footer a {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        color: var(--wine);
        font-size: 10px;
        font-weight: 800;
    }

    .catalog-product-footer a span {
        font-size: 17px;
        transition: transform .25s;
    }

    .catalog-product-footer a:hover span {
        transform: translateX(-4px);
    }

    .catalog-product-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--gray);
    }

    /* Empty state */
    .products-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 65px 20px;
        border: 1px dashed rgba(103,64,64,.25);
        border-radius: 18px;
        text-align: center;
        background: rgba(255,255,255,.4);
    }

    .products-empty-icon {
        display: grid;
        place-items: center;
        width: 65px;
        height: 65px;
        border-radius: 20px;
        color: var(--wine);
        background: #e9dfdb;
    }

    .products-empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .products-empty h3 {
        margin-top: 20px;
        color: var(--dark);
        font-size: 18px;
        font-weight: 900;
    }

    .products-empty p {
        margin-top: 8px;
        color: #887777;
        font-size: 12px;
        line-height: 2;
    }

    .products-empty a {
        margin-top: 20px;
        padding: 12px 20px;
        border-radius: 10px;
        color: #fff;
        background: var(--wine);
        font-size: 11px;
        font-weight: 800;
    }

    /* Pagination */
    .products-pagination {
        display: flex;
        justify-content: center;
        margin-top: 50px;
    }

    .products-pagination nav {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .products-pagination nav > div:first-child {
        display: none;
    }

    .products-pagination nav > div:last-child > span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px;
        border: 1px solid rgba(103,64,64,.12);
        border-radius: 12px;
        background: #fff;
    }

    .products-pagination nav a,
    .products-pagination nav span[aria-current="page"] > span,
    .products-pagination nav span[aria-disabled="true"] > span {
        display: grid;
        place-items: center;
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border-radius: 8px;
        color: #806f6f;
        font-size: 11px;
        transition: .2s;
    }

    .products-pagination nav a:hover {
        color: #fff;
        background: var(--mid);
    }

    .products-pagination nav span[aria-current="page"] > span {
        color: #fff;
        background: var(--wine);
        font-weight: 800;
    }

    .products-pagination nav span[aria-disabled="true"] > span {
        opacity: .4;
    }

    /* Contact CTA */
    .products-contact-section {
        padding: 25px 0 90px;
        background: var(--paper);
    }

    .products-contact-panel {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 35px;
        overflow: hidden;
        padding: 45px 50px;
        border-radius: 22px;
        color: #fff;
        background: linear-gradient(115deg, #362727, #674040);
        isolation: isolate;
    }

    .products-contact-decoration {
        position: absolute;
        z-index: -1;
        top: -170px;
        left: 15%;
        width: 400px;
        height: 400px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow:
            0 0 0 35px rgba(255,255,255,.025),
            0 0 0 70px rgba(255,255,255,.02);
    }

    .products-contact-content {
        max-width: 700px;
    }

    .products-contact-content > span {
        color: #d5c1c1;
        font-size: 11px;
        font-weight: 700;
    }

    .products-contact-content h2 {
        margin-top: 12px;
        color: #fff;
        font-size: clamp(22px, 3vw, 34px);
        font-weight: 900;
        line-height: 1.8;
    }

    .products-contact-content h2 strong {
        color: #c9b3b3;
    }

    .products-contact-content p {
        max-width: 570px;
        margin-top: 10px;
        color: #d0bebe;
        font-size: 11px;
        line-height: 2.4;
    }

    .products-contact-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        gap: 18px;
        min-height: 52px;
        padding: 0 22px;
        border-radius: 11px;
        color: var(--dark);
        background: var(--paper);
        font-size: 11px;
        font-weight: 900;
        transition: .3s;
    }

    .products-contact-button:hover {
        background: #fff;
        transform: translateY(-3px);
    }

    .products-contact-button span {
        font-size: 19px;
    }

    /* Animations */
    @keyframes productsGlowOne {
        from { transform: translate(0, 0) scale(.9); }
        to { transform: translate(-180px, 130px) scale(1.15); }
    }

    @keyframes productsGlowTwo {
        from { transform: translate(0, 0) scale(.9); }
        to { transform: translate(150px, -120px) scale(1.2); }
    }

    @keyframes orbitRotate {
        to { transform: rotate(360deg); }
    }

    @keyframes symbolFloat {
        0%, 100% { transform: translateY(0) rotate(-7deg); }
        50% { transform: translateY(-12px) rotate(-3deg); }
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .products-list-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .featured-card {
            flex-basis: calc((100% - 20px) / 2);
        }

        .products-hero-inner {
            gap: 25px;
        }
    }

    @media (max-width: 760px) {
        .products-hero {
            min-height: auto;
            padding: 135px 0 25px;
        }

        .products-hero-inner {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .products-hero-content h1 {
            font-size: clamp(38px, 10vw, 58px);
        }

        .products-hero-content > p {
            font-size: 12px;
        }

        .products-hero-visual {
            min-height: 260px;
        }

        .products-hero-orbit {
            width: 240px;
            height: 240px;
        }

        .orbit-two {
            width: 190px;
            height: 190px;
        }

        .products-hero-symbol {
            width: 150px;
            height: 150px;
        }

        .products-hero-symbol span {
            font-size: 52px;
        }

        .products-hero-caption {
            right: 0;
            bottom: -5px;
        }

        .products-hero-bottom {
            margin-top: 30px;
            font-size: 9px;
        }

        .featured-section,
        .all-products-section {
            padding: 70px 0;
        }

        .products-section-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 17px;
        }

        .featured-controls {
            align-self: flex-end;
        }

        .featured-card {
            flex-basis: 85%;
            min-width: 290px;
        }

        .products-filter {
            flex-wrap: wrap;
        }

        .products-search {
            flex-basis: 100%;
        }

        .products-sort {
            flex: 1;
            border-right: 0;
            padding-inline: 8px;
        }

        .products-filter-submit {
            padding-inline: 15px;
        }

        .products-list-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .catalog-product-image {
            height: 210px;
        }

        .catalog-product-content {
            padding: 15px 13px;
        }

        .catalog-product-content h3 {
            font-size: 13px;
        }

        .catalog-product-content > p {
            font-size: 9px;
        }

        .products-contact-panel {
            align-items: flex-start;
            flex-direction: column;
            padding: 32px 25px;
        }
    }

    @media (max-width: 480px) {
        .products-hero-bottom span:last-child {
            max-width: 50%;
            text-align: left;
        }

        .featured-card {
            grid-template-columns: 1fr;
            flex-basis: 82%;
        }

        .featured-card-image {
            min-height: 190px;
            height: 190px;
        }

        .featured-card-content {
            min-height: 210px;
            padding: 18px;
        }

        .products-list-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .catalog-product-image {
            height: 250px;
        }

        .catalog-product-content {
            padding: 18px;
        }

        .catalog-product-content h3 {
            font-size: 15px;
        }

        .catalog-product-content > p {
            font-size: 10px;
        }

        .products-total {
            align-items: flex-start;
        }

        .products-contact-section {
            padding-bottom: 65px;
        }

        .products-contact-content h2 {
            font-size: 25px;
        }

        .products-pagination nav a,
        .products-pagination nav span[aria-current="page"] > span,
        .products-pagination nav span[aria-disabled="true"] > span {
            min-width: 32px;
            height: 34px;
            padding: 0 7px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .products-page *,
        .products-page *::before,
        .products-page *::after {
            animation: none !important;
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
        }
    }
</style>
@endpush


@push('scripts')
<script>
    (() => {
        const slider = document.getElementById('featured-slider');

        if (slider) {
            const previousButton = document.getElementById('featured-prev');
            const nextButton = document.getElementById('featured-next');
            const progress = document.getElementById('featured-progress');

            let isDragging = false;
            let startX = 0;
            let scrollStart = 0;
            let moved = false;

            const getStep = () => {
                const card = slider.querySelector('.featured-card');

                if (!card) {
                    return 300;
                }

                const styles = window.getComputedStyle(slider);
                const gap = parseFloat(styles.columnGap || styles.gap) || 0;

                return card.getBoundingClientRect().width + gap;
            };

            const updateProgress = () => {
                if (!progress) return;

                const maxScroll = slider.scrollWidth - slider.clientWidth;

                if (maxScroll <= 0) {
                    progress.style.width = '100%';
                    return;
                }

                const percentage = (
                    slider.scrollLeft / maxScroll
                ) * 100;

                progress.style.width = `${Math.max(0, Math.min(100, percentage))}%`;
            };

            previousButton?.addEventListener('click', () => {
                slider.scrollBy({
                    left: getStep(),
                    behavior: 'smooth'
                });
            });

            nextButton?.addEventListener('click', () => {
                slider.scrollBy({
                    left: -getStep(),
                    behavior: 'smooth'
                });
            });

            slider.addEventListener('scroll', updateProgress, {
                passive: true
            });

            slider.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'touch') return;

                isDragging = true;
                moved = false;
                startX = event.clientX;
                scrollStart = slider.scrollLeft;

                slider.classList.add('is-dragging');
                slider.setPointerCapture(event.pointerId);
            });

            slider.addEventListener('pointermove', (event) => {
                if (!isDragging) return;

                const distance = event.clientX - startX;

                if (Math.abs(distance) > 5) {
                    moved = true;
                }

                slider.scrollLeft = scrollStart - distance;
            });

            const stopDragging = () => {
                if (!isDragging) return;

                isDragging = false;
                slider.classList.remove('is-dragging');
            };

            slider.addEventListener('pointerup', stopDragging);
            slider.addEventListener('pointercancel', stopDragging);
            slider.addEventListener('lostpointercapture', stopDragging);

            slider.addEventListener('click', (event) => {
                if (moved) {
                    event.preventDefault();
                    event.stopPropagation();
                    moved = false;
                }
            }, true);

            window.addEventListener('resize', updateProgress);

            updateProgress();
        }

        const sortSelect = document.getElementById('products-sort');
        const filterForm = document.getElementById('products-filter');

        sortSelect?.addEventListener('change', () => {
            filterForm?.requestSubmit();
        });
    })();
</script>
@endpush