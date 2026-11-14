@extends('layouts.site')

@section('title', $blogPost->title . ' | مقالات کهن پلیمر البرز')

@section('description', $blogPost->summary ?: \Illuminate\Support\Str::limit(strip_tags($blogPost->body), 160))

@push('styles')
<style>
    /* Blog Article Page */

    .blog-article-page {
        --article-primary: #674040;
        --article-dark: #362727;
        --article-mid: #8d6666;
        --article-gray: #baadad;
        --article-paper: #F4F0EE;

        background: var(--article-paper);
        color: var(--article-dark);
        min-height: 100vh;
    }

    .blog-article-container {
        width: min(1120px, calc(100% - 40px));
        margin: 0 auto;
    }

    /* Article Hero */

    .blog-article-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 65px 0 75px;

        background:
            radial-gradient(ellipse at 12% 15%, #362727, transparent 35%),
            radial-gradient(ellipse at 90% 90%, #362727, transparent 38%),
            linear-gradient(125deg, #362727, #362727 55%, #674040);
    }

    .blog-article-hero::before {
        content: "";
        position: absolute;
        z-index: -1;
        inset: 0;
        opacity: .12;

        background-image:
            linear-gradient(rgba(255,255,255,.17) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.17) 1px, transparent 1px);

        background-size: 55px 55px;
        mask-image: linear-gradient(to bottom, transparent, #000 25%, #000 75%, transparent);
    }

    .blog-article-hero::after {
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

    .blog-article-hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
        animation: articleFadeUp .8s ease both;
    }

    /* Breadcrumb */

    .blog-article-breadcrumb {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 32px;

        color: rgba(255,255,255,.55);
        font-size: 11px;
    }

    .blog-article-breadcrumb a {
        color: #d5b9b2;
        text-decoration: none;
        transition: color .25s ease;
    }

    .blog-article-breadcrumb a:hover {
        color: #fff;
    }

    .blog-article-breadcrumb span {
        color: rgba(255,255,255,.35);
    }

    /* Eyebrow */

    .blog-article-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;

        color: #d5b9b2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 3px;
    }

    .blog-article-eyebrow span {
        width: 25px;
        height: 2px;
        background: #c5a6a0;
    }

    .blog-article-hero h1 {
        max-width: 850px;
        margin: 0 auto 23px;

        color: #fff;
        font-size: clamp(26px, 4vw, 43px);
        font-weight: 900;
        line-height: 1.9;
    }

    .blog-article-summary {
        max-width: 740px;
        margin: 0 auto;

        color: rgba(255,255,255,.73);
        font-size: 14px;
        line-height: 2.5;
    }

    .blog-article-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 25px;
        margin-top: 30px;

        color: rgba(255,255,255,.62);
        font-size: 11px;
    }

    .blog-article-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }

    .blog-article-meta-item span {
        color: #d5b9b2;
        font-weight: 800;
    }

    .blog-article-hero-line {
        width: 75px;
        height: 2px;
        margin: 30px auto 0;

        background: linear-gradient(
            90deg,
            transparent,
            #d5b9b2,
            transparent
        );
    }

    /* Main Article */

    .blog-article-main {
        padding: 65px 0 90px;
    }

    .blog-article-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 270px;
        align-items: start;
        gap: 35px;
    }

    /* Article Content */

    .blog-article-content {
        min-width: 0;
    }

    .blog-article-cover {
        position: relative;
        width: 100%;
        height: 440px;
        overflow: hidden;

        border-radius: 18px;
        border: 1px solid rgba(103,64,64,.1);

        background: linear-gradient(135deg, #e8dfdc, #d5c5c1);

        box-shadow: 0 18px 45px rgba(54,39,39,.09);
    }

    .blog-article-cover img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .blog-article-cover-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;

        color: rgba(103,64,64,.55);
    }

    .blog-article-cover-placeholder strong {
        font-size: 100px;
        font-weight: 900;
        letter-spacing: -8px;
        line-height: 1;
    }

    .blog-article-cover-placeholder small {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 5px;
    }

    /* Article Body */

    .blog-article-body-wrapper {
        margin-top: 35px;
        padding: 40px 42px;

        border: 1px solid rgba(103,64,64,.08);
        border-radius: 16px;

        background: #fff;

        box-shadow: 0 12px 40px rgba(54,39,39,.035);
    }

    .blog-article-body {
        color: #554846;
        font-size: 15px;
        line-height: 2.7;
        overflow-wrap: anywhere;
    }

    .blog-article-body p {
        margin: 0 0 22px;
    }

    .blog-article-body p:last-child {
        margin-bottom: 0;
    }

    .blog-article-body h2 {
        margin: 38px 0 17px;
        color: var(--article-dark);
        font-size: 23px;
        font-weight: 900;
        line-height: 1.9;
    }

    .blog-article-body h3 {
        margin: 30px 0 14px;
        color: var(--article-primary);
        font-size: 19px;
        font-weight: 800;
        line-height: 1.9;
    }

    .blog-article-body ul,
    .blog-article-body ol {
        margin: 18px 0 25px;
        padding-right: 25px;
    }

    .blog-article-body li {
        margin-bottom: 10px;
        padding-right: 5px;
    }

    .blog-article-body ul li::marker {
        color: var(--article-mid);
    }

    .blog-article-body blockquote {
        margin: 30px 0;
        padding: 20px 25px;

        border-right: 3px solid var(--article-mid);
        border-radius: 8px 0 0 8px;

        background: #f7f2f0;
        color: var(--article-primary);
        font-weight: 700;
    }

    .blog-article-body img {
        max-width: 100%;
        height: auto;
        margin: 25px auto;
        border-radius: 12px;
    }

    .blog-article-body a {
        color: var(--article-primary);
        text-decoration: underline;
        text-underline-offset: 4px;
    }

    /* Article Footer */

    .blog-article-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 28px;
        padding-top: 22px;

        border-top: 1px solid rgba(103,64,64,.12);
    }

    .blog-article-footer-label {
        color: #9b8985;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .blog-article-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;

        color: var(--article-primary);
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;

        transition: gap .25s ease;
    }

    .blog-article-back:hover {
        gap: 15px;
    }

    /* Sidebar */

    .blog-article-sidebar {
        position: sticky;
        top: 30px;
    }

    .blog-sidebar-card {
        padding: 25px;
        border: 1px solid rgba(103,64,64,.1);
        border-radius: 14px;
        background: #fff;

        box-shadow: 0 10px 35px rgba(54,39,39,.045);
    }

    .blog-sidebar-title {
        margin: 0 0 18px;
        color: var(--article-dark);
        font-size: 16px;
        font-weight: 900;
    }

    .blog-sidebar-line {
        width: 45px;
        height: 2px;
        margin-bottom: 23px;
        background: var(--article-mid);
    }

    .blog-sidebar-description {
        margin: 0;
        color: #887874;
        font-size: 12px;
        line-height: 2.3;
    }

    .blog-sidebar-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

        width: 100%;
        margin-top: 22px;
        padding: 13px 15px;

        border-radius: 8px;
        background: var(--article-primary);
        color: #fff;

        font-size: 12px;
        font-weight: 800;
        text-decoration: none;

        transition: background .25s ease, transform .25s ease;
    }

    .blog-article-sidebar {
        position: sticky;
        top: 120px;
        align-self: start;
        height: fit-content;
        z-index: 2;
    }

    .blog-sidebar-card {
        position: relative;
        overflow: hidden;
    }

    .blog-sidebar-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(141, 102, 102, 0.12);
        color: #674040;
        margin-bottom: 20px;
    }

    .blog-sidebar-icon svg {
        width: 25px;
        height: 25px;
    }

    .blog-sidebar-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    /* Mobile */
    @media (max-width: 991px) {
        .blog-article-sidebar {
            position: static;
            top: auto;
        }
    }

    .blog-sidebar-button:hover {
        background: var(--article-dark);
        transform: translateY(-2px);
    }

    /* Related Articles */

    .blog-related {
        padding: 75px 0 90px;
        background: #eee7e4;
    }

    .blog-related-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
        margin-bottom: 30px;
    }

    .blog-related-heading small {
        display: block;
        margin-bottom: 9px;
        color: var(--article-mid);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 3px;
    }

    .blog-related-heading h2 {
        margin: 0;
        color: var(--article-dark);
        font-size: 24px;
        font-weight: 900;
    }

    .blog-related-all {
        color: var(--article-primary);
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .blog-related-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 23px;
    }

    .blog-related-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;

        border: 1px solid rgba(103,64,64,.09);
        border-radius: 13px;
        background: #fff;
        color: inherit;
        text-decoration: none;

        box-shadow: 0 8px 25px rgba(54,39,39,.035);

        transition: transform .35s ease, box-shadow .35s ease;
    }

    .blog-related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px rgba(54,39,39,.1);
    }

    .blog-related-image {
        height: 170px;
        overflow: hidden;
        background: linear-gradient(135deg, #e8dfdc, #d5c5c1);
    }

    .blog-related-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s ease;
    }

    .blog-related-card:hover .blog-related-image img {
        transform: scale(1.06);
    }

    .blog-related-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;

        color: rgba(103,64,64,.55);
        font-size: 45px;
        font-weight: 900;
    }

    .blog-related-content {
        padding: 20px;
    }

    .blog-related-content h3 {
        margin: 0;
        color: var(--article-dark);
        font-size: 14px;
        font-weight: 900;
        line-height: 2;
        transition: color .25s ease;
    }

    .blog-related-card:hover h3 {
        color: var(--article-primary);
    }

    /* Animations */

    @keyframes articleHeroLight {
        from {
            transform: translate(0, 0) scale(1);
        }

        to {
            transform: translate(150px, 110px) scale(1.2);
        }
    }

    @keyframes articleFadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .blog-article-hero::after,
        .blog-article-hero-content {
            animation: none;
        }

        .blog-related-card,
        .blog-related-image img,
        .blog-sidebar-button {
            transition: none;
        }
    }

    /* Responsive */

    @media (max-width: 900px) {
        .blog-article-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .blog-article-sidebar {
            position: static;
        }

        .blog-article-cover {
            height: 380px;
        }
    }

    @media (max-width: 650px) {
        .blog-article-hero {
            padding: 50px 0 60px;
        }

        .blog-article-main {
            padding: 40px 0 60px;
        }

        .blog-article-cover {
            height: 260px;
            border-radius: 12px;
        }

        .blog-article-body-wrapper {
            margin-top: 22px;
            padding: 25px 20px;
            border-radius: 12px;
        }

        .blog-article-body {
            font-size: 13px;
            line-height: 2.5;
        }

        .blog-article-body h2 {
            font-size: 20px;
        }

        .blog-article-body h3 {
            font-size: 17px;
        }

        .blog-article-meta {
            gap: 14px;
        }

        .blog-article-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .blog-related {
            padding: 55px 0 65px;
        }

        .blog-related-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .blog-related-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .blog-related-card {
            display: grid;
            grid-template-columns: 115px minmax(0, 1fr);
            align-items: center;
        }

        .blog-related-image {
            height: 115px;
        }

        .blog-related-content {
            padding: 13px;
        }

        .blog-related-content h3 {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')

<div class="blog-article-page">

    {{-- Article Hero --}}
    <section class="blog-article-hero">
        <div class="blog-article-container">

            <div class="blog-article-hero-content">

                {{-- Breadcrumb --}}
                <nav class="blog-article-breadcrumb">
                    <a href="{{ url('/') }}">صفحه اصلی</a>
                    <span>/</span>
                    <a href="{{ route('blog.index') }}">مقالات</a>
                    <span>/</span>
                    <span>{{ $blogPost->title }}</span>
                </nav>

                <div class="blog-article-eyebrow">
                    <span></span>
                    KOHAN POLYMER ALBORZ / ARTICLE
                    <span></span>
                </div>

                <h1>{{ $blogPost->title }}</h1>

                @if($blogPost->summary)
                    <p class="blog-article-summary">
                        {{ $blogPost->summary }}
                    </p>
                @endif

                <div class="blog-article-meta">

                    <div class="blog-article-meta-item">
                        <span>●</span>
                        کهن پلیمر البرز
                    </div>

                    @if($blogPost->published_at)
                        <div class="blog-article-meta-item">
                            <span>◷</span>
                            {{ $blogPost->published_at->format('Y/m/d') }}
                        </div>
                    @endif

                </div>

                <div class="blog-article-hero-line"></div>

            </div>

        </div>
    </section>

    {{-- Article Main --}}
    <main class="blog-article-main">
        <div class="blog-article-container">

            <div class="blog-article-layout">

                {{-- Main Content --}}
                <article class="blog-article-content">

                    {{-- Cover Image --}}
                    <div class="blog-article-cover">

                        @if($blogPost->image)
                            <img
                                src="{{ asset('storage/' . $blogPost->image) }}"
                                alt="{{ $blogPost->title }}"
                            >
                        @else
                            <div class="blog-article-cover-placeholder">
                                <strong>KP</strong>
                                <small>KOHAN POLYMER</small>
                            </div>
                        @endif

                    </div>

                    {{-- Article Body --}}
                    <div class="blog-article-body-wrapper">

                        <div class="blog-article-body">
                            {!! nl2br(e($blogPost->body)) !!}
                        </div>

                        <div class="blog-article-footer">

                            <span class="blog-article-footer-label">
                                KOHAN POLYMER ALBORZ
                            </span>

                            <a
                                href="{{ route('blog.index') }}"
                                class="blog-article-back"
                            >
                                <span>←</span>
                                بازگشت به همه مقالات
                            </a>

                        </div>

                    </div>

                </article>

                {{-- Sidebar --}}
                <aside class="blog-article-sidebar">

                    <div class="blog-sidebar-card">

                        <div class="blog-sidebar-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                    19.79 19.79 0 0 1-8.63-3.07
                                    19.5 19.5 0 0 1-6-6
                                    19.79 19.79 0 0 1-3.07-8.67
                                    A2 2 0 0 1 4.11 2h3
                                    a2 2 0 0 1 2 1.72
                                    c.12.96.36 1.9.72 2.79
                                    a2 2 0 0 1-.45 2.11L8.11 11.89
                                    a16 16 0 0 0 6 6l1.27-1.27
                                    a2 2 0 0 1 2.11-.45
                                    c.89.36 1.83.6 2.79.72
                                    A2 2 0 0 1 22 16.92Z"/>
                            </svg>
                        </div>

                        <h3 class="blog-sidebar-title">
                            مشاوره و ثبت سفارش
                        </h3>

                        <div class="blog-sidebar-line"></div>

                        <p class="blog-sidebar-description">
                            برای دریافت مشاوره تخصصی، استعلام قیمت و ثبت سفارش محصولات پلیمری،
                            با کارشناسان کهن پلیمر البرز در ارتباط باشید.
                        </p>

                        <a
                            href="{{ url('/#contact') }}"
                            class="blog-sidebar-button"
                        >
                            درخواست مشاوره و ثبت سفارش
                            <span>←</span>
                        </a>

                    </div>

                </aside>

            </div>

        </div>
    </main>

    {{-- Related Articles --}}
    @if($relatedPosts->isNotEmpty())

        <section class="blog-related">
            <div class="blog-article-container">

                <div class="blog-related-heading">

                    <div>
                        <small>MORE ARTICLES</small>
                        <h2>مطالب مرتبط</h2>
                    </div>

                    <a
                        href="{{ route('blog.index') }}"
                        class="blog-related-all"
                    >
                        مشاهده همه مقالات ←
                    </a>

                </div>

                <div class="blog-related-grid">

                    @foreach($relatedPosts as $relatedPost)

                        <a
                            href="{{ route('blog.show', $relatedPost->id) }}"
                            class="blog-related-card"
                        >

                            <div class="blog-related-image">

                                @if($relatedPost->image)
                                    <img
                                        src="{{ asset('storage/' . $relatedPost->image) }}"
                                        alt="{{ $relatedPost->title }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="blog-related-placeholder">
                                        KP
                                    </div>
                                @endif

                            </div>

                            <div class="blog-related-content">
                                <h3>{{ $relatedPost->title }}</h3>
                            </div>

                        </a>

                    @endforeach

                </div>

            </div>
        </section>

    @endif

</div>

@endsection