@extends('layouts.site')

@section('title', 'مقالات و مطالب تخصصی | کهن پلیمر البرز')

@section('description', 'مطالب تخصصی، مقالات و تازه‌های صنعت پلیمر در کهن پلیمر البرز')

@push('styles')
<style>
    /* Blog Page */
    .blog-page {
        --blog-primary: #674040;
        --blog-dark: #362727;
        --blog-mid: #8d6666;
        --blog-gray: #baadad;
        --blog-paper: #F4F0EE;
        background: var(--blog-paper);
        min-height: 100vh;
        color: var(--blog-dark);
    }

    .blog-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    /* Hero */

    .blog-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 95px 0 90px;
        padding-top: 150px;
        background:
            radial-gradient(ellipse at 12% 15%, #362727, transparent 35%),
            radial-gradient(ellipse at 90% 90%, #362727, transparent 38%),
            linear-gradient(125deg, #362727, #362727 55%, #674040);
    }

    .blog-hero::before {
        content: "";
        position: absolute;
        z-index: -1;
        inset: 0;
        opacity: .13;
        background-image:
            linear-gradient(rgba(255,255,255,.18) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.18) 1px, transparent 1px);
        background-size: 55px 55px;
        mask-image: linear-gradient(to bottom, transparent, #000 25%, #000 75%, transparent);
    }

    .blog-hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        width: 600px;
        height: 600px;
        top: -350px;
        left: -180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(87, 43, 46, 0.35), #3f2829e0 68%);
        filter: blur(25px);
        animation: blogHeroLight 13s ease-in-out infinite alternate;
    }

    .blog-hero-content {
        position: relative;
        z-index: 2;
        max-width: 760px;
        margin: 0 auto;
        text-align: center;
        animation: blogFadeUp .8s ease both;
    }

    .blog-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 23px;
        color: #d8c1bc;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
    }

    .blog-hero-eyebrow span {
        width: 27px;
        height: 2px;
        background: #c5a6a0;
        box-shadow: 0 0 12px rgba(255,255,255,.5);
    }

    .blog-hero h1 {
        margin: 0 0 20px;
        color: #fff;
        font-size: clamp(30px, 4vw, 48px);
        font-weight: 900;
        line-height: 1.7;
    }

    .blog-hero h1 span {
        color: #d5b9b2;
    }

    .blog-hero-description {
        max-width: 620px;
        margin: 0 auto;
        color: rgba(255,255,255,.72);
        font-size: 14px;
        line-height: 2.5;
    }

    .blog-hero-line {
        width: 75px;
        height: 2px;
        margin: 30px auto 0;
        background: linear-gradient(90deg, transparent, #d5b9b2, transparent);
    }

    /* Main Content */

    .blog-main {
        padding: 75px 0 100px;
    }

    .blog-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
        margin-bottom: 32px;
    }

    .blog-section-title small {
        display: block;
        margin-bottom: 10px;
        color: var(--blog-mid);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 3px;
    }

    .blog-section-title h2 {
        margin: 0;
        color: var(--blog-dark);
        font-size: 25px;
        font-weight: 900;
    }

    .blog-total {
        color: #907d79;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Filters */

    .blog-filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 17px;
        margin-bottom: 35px;
        border: 1px solid rgba(103,64,64,.10);
        border-radius: 14px;
        background: rgba(255,255,255,.52);
        box-shadow: 0 8px 30px rgba(54,39,39,.035);
    }

    .blog-search {
        display: flex;
        align-items: center;
        flex: 1;
        width: 100%;
        max-width: 460px;
        min-width: 0;
        height: 47px;
        overflow: hidden;
        border: 1px solid rgba(103,64,64,.13);
        border-radius: 9px;
        background: #fff;
    }

    .blog-search input {
        flex: 1;
        min-width: 0;
        width: 100%;
        height: 100%;
        padding: 0 15px;
        border: 0;
        outline: none;
        background: transparent;
        color: var(--blog-dark);
        font-family: inherit;
        font-size: 12px;
    }

    .blog-search input::placeholder {
        color: #aa9995;
    }

    .blog-search button {
        flex-shrink: 0;
        height: 100%;
        padding: 0 19px;
        border: 0;
        background: var(--blog-primary);
        color: #fff;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background .25s ease;
    }

    .blog-search button:hover {
        background: var(--blog-dark);
    }

    .blog-sort {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #8e7a76;
        font-size: 12px;
        white-space: nowrap;
    }

    .blog-sort select {
        height: 47px;
        min-width: 145px;
        padding: 0 13px;
        border: 1px solid rgba(103,64,64,.13);
        border-radius: 9px;
        outline: none;
        background: #fff;
        color: var(--blog-dark);
        font-family: inherit;
        font-size: 12px;
        cursor: pointer;
    }

    /* Blog Grid */

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px;
    }

    .blog-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
        border: 1px solid rgba(103,64,64,.09);
        border-radius: 15px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        box-shadow: 0 10px 35px rgba(54,39,39,.045);
        transition:
            transform .4s ease,
            box-shadow .4s ease,
            border-color .4s ease;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        border-color: rgba(141,102,102,.3);
        box-shadow: 0 22px 45px rgba(54,39,39,.12);
    }

    .blog-card-image {
        position: relative;
        height: 235px;
        overflow: hidden;
        background: linear-gradient(135deg, #e8dfdc, #d5c5c1);
    }

    .blog-card-image img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .7s cubic-bezier(.2,.7,.2,1);
    }

    .blog-card:hover .blog-card-image img {
        transform: scale(1.07);
    }

    .blog-card-image::after {
        content: "";
        position: absolute;
        inset: 45% 0 0;
        background: linear-gradient(transparent, rgba(54,39,39,.25));
        pointer-events: none;
    }

    .blog-image-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        color: rgba(103,64,64,.55);
    }

    .blog-image-placeholder strong {
        font-size: 65px;
        font-weight: 900;
        letter-spacing: -5px;
    }

    .blog-image-placeholder small {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 4px;
    }

    .blog-card-number {
        position: absolute;
        z-index: 2;
        top: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: 1px solid rgba(255,255,255,.45);
        border-radius: 10px;
        background: rgba(54,39,39,.55);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        backdrop-filter: blur(8px);
    }

    .blog-card-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 23px 23px 20px;
    }

    .blog-card-label {
        margin-bottom: 13px;
        color: var(--blog-mid);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .blog-card h3 {
        margin: 0 0 13px;
        color: var(--blog-dark);
        font-size: 17px;
        font-weight: 900;
        line-height: 1.9;
        transition: color .3s ease;
    }

    .blog-card:hover h3 {
        color: var(--blog-primary);
    }

    .blog-card-summary {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: #827572;
        font-size: 12px;
        line-height: 2.2;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: auto;
        padding-top: 20px;
    }

    .blog-card-link {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        color: var(--blog-primary);
        font-size: 12px;
        font-weight: 800;
    }

    .blog-card-link span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 27px;
        height: 27px;
        border-radius: 50%;
        background: #f0e9e7;
        transition: transform .3s ease, background .3s ease;
    }

    .blog-card:hover .blog-card-link span {
        transform: translateX(-4px);
        background: #e5d8d4;
    }

    .blog-card-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--blog-gray);
    }

    /* Empty State */

    .blog-empty {
        padding: 65px 20px;
        border: 1px dashed rgba(103,64,64,.22);
        border-radius: 15px;
        background: rgba(255,255,255,.45);
        text-align: center;
    }

    .blog-empty-symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #eadfdb;
        color: var(--blog-primary);
        font-size: 25px;
        font-weight: 900;
    }

    .blog-empty h3 {
        margin: 0 0 10px;
        color: var(--blog-dark);
        font-size: 18px;
        font-weight: 900;
    }

    .blog-empty p {
        margin: 0;
        color: #8e7d79;
        font-size: 13px;
        line-height: 2;
    }

    .blog-empty a {
        display: inline-block;
        margin-top: 20px;
        color: var(--blog-primary);
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    /* Pagination */

    .blog-pagination {
        display: flex;
        justify-content: center;
        margin-top: 55px;
    }

    .blog-pagination nav {
        display: flex;
        justify-content: center;
    }

    .blog-pagination nav > div:first-child {
        display: none;
    }

    .blog-pagination nav > div:last-child > span,
    .blog-pagination nav > div:last-child > a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        margin: 0 3px;
        padding: 0 12px;
        border: 1px solid rgba(103,64,64,.12);
        border-radius: 8px;
        background: #fff;
        color: var(--blog-primary);
        font-size: 12px;
        text-decoration: none;
    }

    .blog-pagination nav > div:last-child span[aria-current="page"] span {
        border-color: var(--blog-primary);
        background: var(--blog-primary);
        color: #fff;
    }

    /* Animations */

    @keyframes blogHeroLight {
        from {
            transform: translate(0, 0) scale(1);
        }
        to {
            transform: translate(180px, 130px) scale(1.2);
        }
    }

    @keyframes blogFadeUp {
        from {
            opacity: 0;
            transform: translateY(22px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .blog-hero::after,
        .blog-hero-content {
            animation: none;
        }

        .blog-card,
        .blog-card-image img,
        .blog-card-link span {
            transition: none;
        }
    }

    /* Responsive */

    @media (max-width: 1000px) {
        .blog-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .blog-card-image {
            height: 220px;
        }
    }

    @media (max-width: 650px) {
        .blog-hero {
            padding: 70px 0;
            padding-top: 120px;
        }

        .blog-main {
            padding: 55px 0 75px;
        }

        .blog-section-heading {
            align-items: start;
            flex-direction: column;
            gap: 10px;
        }

        .blog-filters {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
        }

        .blog-search {
            width: 100%;
            max-width: none;
            flex: none;
        }

        .blog-search input {
            min-width: 0;
            font-size: 11px;
        }

        .blog-search button {
            padding: 0 16px;
            white-space: nowrap;
        }

        .blog-sort {
            width: 100%;
            flex-wrap: wrap;
        }

        .blog-sort select {
            flex: 1;
        }

        .blog-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .blog-card-image {
            height: 235px;
        }

        .blog-card-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="blog-page">

    {{-- Blog Hero --}}
    <section class="blog-hero">
        <div class="blog-container">
            <div class="blog-hero-content">

                <div class="blog-hero-eyebrow">
                    <span></span>
                    KOHAN POLYMER ALBORZ / BLOG
                    <span></span>
                </div>

                <h1>
                    دانش و تجربه در
                    <span>دنیای پلیمر</span>
                </h1>

                <p class="blog-hero-description">
                    در این بخش با مقالات تخصصی، مطالب آموزشی و تازه‌های صنعت پلیمر همراه شما هستیم؛
                    محتوایی برای شناخت بهتر محصولات، فناوری‌ها و مسیرهای نوین این صنعت.
                </p>

                <div class="blog-hero-line"></div>

            </div>
        </div>
    </section>

    {{-- Blog Main --}}
    <main class="blog-main">
        <div class="blog-container">

            {{-- Section Heading --}}
            <div class="blog-section-heading">

                <div class="blog-section-title">
                    <small>KNOWLEDGE & INSIGHTS</small>
                    <h2>آخرین مقالات و مطالب</h2>
                </div>

                <div class="blog-total">
                    {{ number_format($blogPosts->total()) }} مقاله منتشر شده
                </div>

            </div>

            {{-- Search and Sort --}}
            <form action="{{ route('blog.index') }}" method="GET" class="blog-filters">

                <div class="blog-search">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جست‌وجو در عنوان مقالات..."
                    >

                    <button type="submit">
                        جست‌وجو
                    </button>
                </div>

                <div class="blog-sort">
                    <label for="blog-sort">مرتب‌سازی:</label>

                    <select
                        name="sort"
                        id="blog-sort"
                        onchange="this.form.submit()"
                    >
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>
                            جدیدترین مقالات
                        </option>

                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>
                            قدیمی‌ترین مقالات
                        </option>
                    </select>

                    @if(request('search'))
                        <a
                            href="{{ route('blog.index') }}"
                            style="color: var(--blog-primary); font-size: 11px; text-decoration: none;"
                        >
                            پاک کردن جست‌وجو
                        </a>
                    @endif
                </div>

            </form>

            {{-- Blog Cards --}}
            @if($blogPosts->isNotEmpty())

                <div class="blog-grid">

                    @foreach($blogPosts as $blogPost)

                        <a
                            href="{{ route('blog.show', $blogPost->id) }}"
                            class="blog-card"
                        >

                            <div class="blog-card-image">

                                @if($blogPost->image)
                                    <img
                                        src="{{ asset('storage/' . $blogPost->image) }}"
                                        alt="{{ $blogPost->title }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="blog-image-placeholder">
                                        <strong>KP</strong>
                                        <small>KOHAN POLYMER</small>
                                    </div>
                                @endif

                                <span class="blog-card-number">
                                    {{ sprintf('%02d', $loop->iteration + ($blogPosts->currentPage() - 1) * $blogPosts->perPage()) }}
                                </span>

                            </div>

                            <div class="blog-card-content">

                                <span class="blog-card-label">
                                    KOHAN POLYMER / ARTICLE
                                </span>

                                <h3>
                                    {{ $blogPost->title }}
                                </h3>

                                <p class="blog-card-summary">
                                    {{ $blogPost->summary ?: \Illuminate\Support\Str::limit(strip_tags($blogPost->body), 180) }}
                                </p>

                                <div class="blog-card-footer">

                                    <span class="blog-card-link">
                                        مطالعه مقاله
                                        <span>←</span>
                                    </span>

                                    <span class="blog-card-dot"></span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

                {{-- Pagination --}}
                @if($blogPosts->hasPages())
                    <div class="blog-pagination">
                        {{ $blogPosts->links() }}
                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="blog-empty">

                    <div class="blog-empty-symbol">
                        KP
                    </div>

                    @if(request('search'))
                        <h3>مقاله‌ای پیدا نشد</h3>

                        <p>
                            مقاله‌ای با عبارت جست‌وجوشده پیدا نکردیم.
                            لطفاً عبارت دیگری را امتحان کنید.
                        </p>

                        <a href="{{ route('blog.index') }}">
                            مشاهده همه مقالات
                        </a>
                    @else
                        <h3>هنوز مقاله‌ای منتشر نشده است</h3>

                        <p>
                            به‌زودی مطالب و مقالات تخصصی جدیدی در این بخش منتشر خواهیم کرد.
                        </p>
                    @endif

                </div>

            @endif

        </div>
    </main>

</div>

@endsection