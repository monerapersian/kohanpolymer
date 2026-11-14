@extends('layouts.site')

@section('title', $product->name . ' | کهن پلیمر البرز')

@section('content')

@php
    $properties = $product->properties;
    $sizes = $product->sizes;
    $eps = $product->eps;
    $goodsDescriptions = $product->goodsDescriptions;
@endphp

<div class="product-detail-page">

    {{-- Breadcrumb --}}
    <section class="product-detail-breadcrumb">
        <div class="product-detail-container">
            <div class="product-detail-breadcrumb-inner">
                <a href="{{ url('/') }}">صفحه اصلی</a>
                <span>/</span>
                <a href="{{ route('products.index') }}">محصولات</a>
                <span>/</span>
                <span class="current">{{ $product->name }}</span>
            </div>
        </div>
    </section>

    {{-- Product Hero --}}
    <section class="product-detail-hero">
        <div class="product-detail-container">

            <div class="product-detail-hero-grid">

                {{-- Product Image --}}
                <div class="product-detail-visual">

                    <div class="product-detail-image-frame">

                        <div class="product-detail-image-glow"></div>

                        @if($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="product-detail-main-image"
                            >
                        @else
                            <div class="product-detail-placeholder">
                                <span>KP</span>
                                <small>KOHAN POLYMER</small>
                            </div>
                        @endif

                        @if($product->is_featured)
                            <span class="product-detail-featured">
                                محصول ویژه
                            </span>
                        @endif

                        <span class="product-detail-image-caption">
                            KOHAN POLYMER
                        </span>

                    </div>

                    <div class="product-detail-visual-bottom">
                        <span></span>
                        <span>QUALITY IN EVERY DETAIL</span>
                    </div>

                </div>

                {{-- Product Information --}}
                <div class="product-detail-intro">

                    <div class="product-detail-eyebrow">
                        <span></span>
                        KOHAN POLYMER ALBORZ / PRODUCT
                    </div>

                    <h1>{{ $product->name }}</h1>

                    <div class="product-detail-title-line"></div>

                    @if($product->description)
                        <div class="product-detail-description">
                            {{ $product->description }}
                        </div>
                    @else
                        <div class="product-detail-description">
                            برای دریافت اطلاعات بیشتر درباره این محصول و بررسی مشخصات فنی آن، با کارشناسان کهن پلیمر البرز در ارتباط باشید.
                        </div>
                    @endif

                    <div class="product-detail-intro-meta">
                        <div>
                            <span class="meta-number">01</span>
                            <span>کیفیت در تولید</span>
                        </div>

                        <div>
                            <span class="meta-number">02</span>
                            <span>استاندارد در مشخصات</span>
                        </div>

                        <div>
                            <span class="meta-number">03</span>
                            <span>اعتماد در همکاری</span>
                        </div>
                    </div>

                    <div class="product-detail-actions">
                        <a href="{{ url('/#contact') }}" class="product-detail-primary-button">
                            درخواست اطلاعات و مشاوره
                            <span>←</span>
                        </a>

                        <a href="{{ route('products.index') }}" class="product-detail-back-button">
                            بازگشت به محصولات
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- Technical Specifications --}}
    @if(
        $properties->isNotEmpty() ||
        $sizes->isNotEmpty() ||
        $eps->isNotEmpty() ||
        $goodsDescriptions->isNotEmpty()
    )
        <section class="product-detail-specifications">
            <div class="product-detail-container">

                <div class="product-detail-section-heading">
                    <div>

                        <h2>مشخصات و اطلاعات محصول</h2>

                        <span class="product-detail-section-label">
                            PRODUCT INFORMATION
                        </span>

                        <p>
                            جزئیات فنی و اطلاعات ثبت‌شده برای این محصول را در بخش‌های زیر مشاهده کنید.
                        </p>
                    </div>

                    <!-- <div class="product-detail-section-number">
                        01
                    </div> -->
                </div>

                {{-- Properties --}}
                @if($properties->isNotEmpty())
                    <div class="product-detail-info-section">

                        <div class="product-detail-info-heading">
                            <span class="product-detail-info-icon">PP</span>

                            <div>
                                <h3>ویژگی‌های محصول</h3>
                                <span>PRODUCT PROPERTIES</span>
                            </div>
                        </div>

                        <div class="product-detail-property-grid">
                            @foreach($properties as $property)
                                <div class="product-detail-property">
                                    <span class="property-marker"></span>
                                    <span>{{ $property->value }}</span>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

                {{-- Sizes --}}
                @if($sizes->isNotEmpty())
                    <div class="product-detail-info-section">

                        <div class="product-detail-info-heading">
                            <span class="product-detail-info-icon">Size</span>

                            <div>
                                <h3>ابعاد و اندازه‌ها</h3>
                                <span>PRODUCT DIMENSIONS</span>
                            </div>
                        </div>

                        <div class="product-detail-table-wrapper">
                            <table class="product-detail-table">
                                <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>ضخامت (میلی‌متر)</th>
                                        <th>عرض (سانتی‌متر)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($sizes as $size)
                                        <tr>
                                            <td>
                                                {{ sprintf('%02d', $loop->iteration) }}
                                            </td>

                                            <td>
                                                {{ $size->thickness !== null ? number_format((float) $size->thickness, 1, '.', '') : '—' }}
                                            </td>

                                            <td>
                                                {{ $size->width !== null ? number_format((float) $size->width, 1, '.', '') : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif

                {{-- EP --}}
                @if($eps->isNotEmpty())
                    <div class="product-detail-info-section">

                        <div class="product-detail-info-heading">
                            <span class="product-detail-info-icon">EP</span>

                            <div>
                                <h3>اطلاعات EP</h3>
                                <span>EP INFORMATION</span>
                            </div>
                        </div>

                        <div class="product-detail-property-grid">
                            @foreach($eps as $ep)
                                <div class="product-detail-property">
                                    <span class="property-marker"></span>
                                    <span>{{ $ep->value }}</span>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

                {{-- Goods Description --}}
                @if($goodsDescriptions->isNotEmpty())
                    <div class="product-detail-info-section">

                        <div class="product-detail-info-heading">
                            <span class="product-detail-info-icon">DES</span>

                            <div>
                                <h3>شرح کالا</h3>
                                <span>GOODS DESCRIPTION</span>
                            </div>
                        </div>

                        <div class="product-detail-goods-list">
                            @foreach($goodsDescriptions as $description)
                                <div class="product-detail-goods-item">
                                    <span>{{ sprintf('%02d', $loop->iteration) }}</span>
                                    <p>{{ $description->value }}</p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

            </div>
        </section>
    @endif

    {{-- Contact CTA --}}
    <section class="product-detail-contact">
        <div class="product-detail-container">

            <div class="product-detail-contact-box">

                <div class="product-detail-contact-decoration">
                    KPA
                </div>

                <div class="product-detail-contact-content">
                    <span>LET'S WORK TOGETHER</span>

                    <h2>
                        برای دریافت اطلاعات بیشتر درباره
                        {{ $product->name }}
                        آماده پاسخگویی هستیم.
                    </h2>

                    <p>
                        برای دریافت مشاوره، استعلام مشخصات فنی و بررسی شرایط همکاری با کارشناسان ما در ارتباط باشید.
                    </p>

                    <a href="{{ url('/#contact') }}">
                        ارتباط با کارشناسان
                        <span>←</span>
                    </a>
                </div>

                <div class="product-detail-contact-mark">
                    KOHAN<br>POLYMER<br>ALBORZ
                </div>

            </div>

        </div>
    </section>

    {{-- Back to Products --}}
    <section class="product-detail-more">
        <div class="product-detail-container">
            <a href="{{ route('products.index') }}">
                <span>مشاهده سایر محصولات</span>
                <span>←</span>
            </a>
        </div>
    </section>

</div>

@endsection


@push('styles')
<style>
    .product-detail-page {
        --pd-primary: #674040;
        --pd-secondary: #8d6666;
        --pd-muted: #baadad;
        --pd-dark: #362727;
        --pd-paper: #F4F0EE;
        --pd-white: #ffffff;

        direction: rtl;
        color: var(--pd-dark);
        background: var(--pd-paper);
        overflow: hidden;
    }

    .product-detail-container {
        width: min(1200px, calc(100% - 48px));
        margin-inline: auto;
    }

    /* Breadcrumb */

    .product-detail-breadcrumb {
        padding: 25px 0;
        background: #eee7e4;
        border-bottom: 1px solid rgba(103, 64, 64, .08);
    }

    .product-detail-breadcrumb-inner {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        color: #9a8582;
        font-size: 13px;
    }

    .product-detail-breadcrumb-inner a {
        color: var(--pd-secondary);
        text-decoration: none;
        transition: color .25s ease;
    }

    .product-detail-breadcrumb-inner a:hover {
        color: var(--pd-primary);
    }

    .product-detail-breadcrumb-inner .current {
        color: var(--pd-dark);
        font-weight: 700;
    }

    /* Hero */

    /* Hero Background */
    .product-detail-hero {
        padding: 75px 0 95px;
        position: relative;
        isolation: isolate;
        overflow: hidden;

        background:
            radial-gradient(
                ellipse at 12% 18%,
                rgba(141, 102, 102, .19),
                transparent 35%
            ),
            radial-gradient(
                ellipse at 88% 85%,
                rgba(103, 64, 64, .11),
                transparent 38%
            ),
            linear-gradient(
                125deg,
                #faf8f6 0%,
                #F4F0EE 48%,
                #eee5e2 100%
            );
    }

    /* Animated Background Lights */

    .product-detail-hero::before {
        content: "";
        position: absolute;
        z-index: -1;
        width: 600px;
        height: 600px;
        top: -280px;
        left: -180px;
        border-radius: 50%;

        background: radial-gradient(
            circle,
            rgba(141, 102, 102, .20) 0%,
            rgba(186, 173, 173, .12) 35%,
            transparent 70%
        );

        filter: blur(35px);
        pointer-events: none;

        animation: heroLightMove 13s ease-in-out infinite alternate;
    }

    /* Second Moving Light */

    .product-detail-hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        width: 550px;
        height: 550px;
        right: -200px;
        bottom: -300px;
        border-radius: 50%;

        background: radial-gradient(
            circle,
            rgba(103, 64, 64, .16) 0%,
            rgba(141, 102, 102, .09) 42%,
            transparent 72%
        );

        filter: blur(45px);
        pointer-events: none;

        animation: heroLightMoveTwo 16s ease-in-out infinite alternate;
    }

    /* Fine Technical Pattern */

    .product-detail-hero .product-detail-container {
        position: relative;
        z-index: 1;
    }

    .product-detail-hero .product-detail-container::before {
        content: "";
        position: absolute;
        inset: -75px -100px -95px;
        z-index: -1;
        pointer-events: none;

        opacity: .22;

        background-image:
            radial-gradient(
                circle,
                rgba(103, 64, 64, .24) 1px,
                transparent 1.5px
            );

        background-size: 27px 27px;

        mask-image: linear-gradient(
            110deg,
            transparent 5%,
            #000 45%,
            transparent 95%
        );
    }

    /* Light Sweep */

    .product-detail-hero-grid {
        position: relative;
    }

    .product-detail-hero-grid::before {
        content: "";
        position: absolute;
        top: -120px;
        left: -30%;
        width: 25%;
        height: 750px;

        pointer-events: none;

        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, .42),
            transparent
        );

        transform: skewX(-25deg);
        animation: heroLightSweep 12s ease-in-out infinite;
    }

    /* Background Animations */

    @keyframes heroLightMove {
        0% {
            transform: translate(0, 0) scale(1);
        }

        100% {
            transform: translate(180px, 100px) scale(1.25);
        }
    }

    @keyframes heroLightMoveTwo {
        0% {
            transform: translate(0, 0) scale(1);
        }

        100% {
            transform: translate(-140px, -100px) scale(1.2);
        }
    }

    @keyframes heroLightSweep {
        0%, 15% {
            left: -30%;
            opacity: 0;
        }

        30% {
            opacity: .7;
        }

        65% {
            opacity: .5;
        }

        85%, 100% {
            left: 120%;
            opacity: 0;
        }
    }

    /* Respect Reduced Motion */

    @media (prefers-reduced-motion: reduce) {
        .product-detail-hero::before,
        .product-detail-hero::after,
        .product-detail-hero-grid::before {
            animation: none;
        }
    }

    .product-detail-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 75px;
        align-items: center;
    }

    /* Image */

    .product-detail-visual {
        min-width: 0;
    }

    .product-detail-image-frame {
        min-height: 480px;
        height: 480px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 120px 14px 14px 14px;
        background:
            linear-gradient(145deg, #e7ddda, #d5c6c2);
        border: 1px solid rgba(103, 64, 64, .13);
        box-shadow: 0 25px 65px rgba(54, 39, 39, .10);
    }

    .product-detail-image-glow {
        position: absolute;
        width: 360px;
        height: 360px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .38);
        filter: blur(40px);
    }

    .product-detail-main-image {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 30px;
        transition: transform .6s ease;
    }

    .product-detail-image-frame:hover .product-detail-main-image {
        transform: scale(1.045);
    }

    .product-detail-placeholder {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: var(--pd-primary);
    }

    .product-detail-placeholder span {
        font-size: 100px;
        font-weight: 900;
        letter-spacing: -8px;
        line-height: 1;
    }

    .product-detail-placeholder small {
        font-size: 12px;
        letter-spacing: 5px;
        font-weight: 700;
    }

    .product-detail-featured {
        position: absolute;
        z-index: 2;
        top: 25px;
        right: 25px;
        padding: 9px 17px;
        border-radius: 50px;
        color: #fff;
        background: var(--pd-primary);
        font-size: 12px;
        font-weight: 700;
    }

    .product-detail-image-caption {
        position: absolute;
        z-index: 2;
        bottom: 23px;
        left: 25px;
        color: rgba(54, 39, 39, .40);
        font-size: 10px;
        letter-spacing: 4px;
        font-weight: 800;
    }

    .product-detail-visual-bottom {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-top: 19px;
        color: #9a8582;
        font-size: 10px;
        letter-spacing: 3px;
        font-weight: 700;
    }

    .product-detail-visual-bottom span:first-child {
        width: 35px;
        height: 2px;
        background: var(--pd-secondary);
    }

    /* Intro */

    .product-detail-intro {
        padding: 15px 0;
    }

    .product-detail-eyebrow {
        display: flex;
        align-items: center;
        gap: 11px;
        color: var(--pd-secondary);
        font-size: 11px;
        letter-spacing: 2px;
        font-weight: 800;
        margin-bottom: 25px;
    }

    .product-detail-eyebrow span {
        width: 30px;
        height: 2px;
        background: var(--pd-primary);
    }

    .product-detail-intro h1 {
        margin: 0;
        color: var(--pd-dark);
        font-size: clamp(30px, 4vw, 47px);
        line-height: 1.65;
        font-weight: 900;
    }

    .product-detail-title-line {
        width: 65px;
        height: 4px;
        margin: 22px 0 25px;
        border-radius: 10px;
        background: var(--pd-primary);
    }

    .product-detail-description {
        color: #746563;
        font-size: 15px;
        line-height: 2.5;
        text-align: justify;
        white-space: pre-line;
    }

    .product-detail-intro-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 35px;
        padding: 20px 0;
        border-top: 1px solid rgba(103, 64, 64, .15);
        border-bottom: 1px solid rgba(103, 64, 64, .15);
    }

    .product-detail-intro-meta > div {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .product-detail-intro-meta > div > span:last-child {
        color: #796967;
        font-size: 11px;
        font-weight: 600;
    }

    .product-detail-intro-meta .meta-number {
        color: var(--pd-secondary);
        font-size: 19px;
        font-weight: 900;
        letter-spacing: 1px;
    }

    .product-detail-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 30px;
    }

    .product-detail-primary-button,
    .product-detail-back-button {
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 0 23px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: all .3s ease;
    }

    .product-detail-primary-button {
        color: #fff;
        background: var(--pd-primary);
        box-shadow: 0 10px 25px rgba(103, 64, 64, .18);
    }

    .product-detail-primary-button:hover {
        background: var(--pd-dark);
        transform: translateY(-3px);
    }

    .product-detail-primary-button span {
        font-size: 19px;
    }

    .product-detail-back-button {
        color: var(--pd-primary);
        border: 1px solid rgba(103, 64, 64, .25);
        background: transparent;
    }

    .product-detail-back-button:hover {
        color: #fff;
        background: var(--pd-secondary);
        border-color: var(--pd-secondary);
    }

    /* Specifications */

    .product-detail-specifications {
        padding: 95px 0;
        background: #fff;
    }

    .product-detail-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        padding-bottom: 35px;
        border-bottom: 1px solid rgba(103, 64, 64, .13);
        margin-bottom: 45px;
    }

    .product-detail-section-label {
        display: block;
        color: var(--pd-secondary);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 14px;
    }

    .product-detail-section-heading h2 {
        margin: 0;
        color: var(--pd-dark);
        font-size: 29px;
        font-weight: 900;
        line-height: 1.8;
    }

    .product-detail-section-heading p {
        margin: 10px 0 0;
        color: #8b7b78;
        font-size: 13px;
        line-height: 2;
    }

    .product-detail-section-number {
        color: rgba(103, 64, 64, .13);
        font-size: 75px;
        font-weight: 900;
        line-height: 1;
    }

    .product-detail-info-section {
        margin-bottom: 42px;
        padding: 30px;
        border: 1px solid rgba(103, 64, 64, .10);
        border-radius: 13px;
        background: #faf8f7;
    }

    .product-detail-info-section:last-child {
        margin-bottom: 0;
    }

    .product-detail-info-heading {
        display: flex;
        align-items: center;
        gap: 17px;
        margin-bottom: 27px;
    }

    .product-detail-info-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #fff;
        background: var(--pd-primary);
        border-radius: 9px;
        font-size: 14px;
        font-weight: 800;
    }

    .product-detail-info-heading h3 {
        margin: 0 0 5px;
        color: var(--pd-dark);
        font-size: 17px;
        font-weight: 800;
    }

    .product-detail-info-heading div span {
        color: #a18c88;
        font-size: 9px;
        letter-spacing: 2px;
        font-weight: 700;
    }

    /* Properties and EP */

    .product-detail-property-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .product-detail-property {
        min-height: 58px;
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 17px;
        border-radius: 7px;
        border: 1px solid rgba(103, 64, 64, .09);
        background: #fff;
        color: #665452;
        font-size: 13px;
        line-height: 2;
    }

    .product-detail-property .property-marker {
        width: 7px;
        height: 7px;
        flex-shrink: 0;
        border-radius: 50%;
        background: var(--pd-secondary);
    }

    /* Sizes Table */

    .product-detail-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid rgba(103, 64, 64, .10);
        border-radius: 8px;
        background: #fff;
    }

    .product-detail-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 420px;
        text-align: center;
    }

    .product-detail-table th {
        padding: 18px 15px;
        color: #fff;
        background: var(--pd-primary);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .product-detail-table td {
        padding: 17px 15px;
        color: #6e5d5a;
        border-bottom: 1px solid rgba(103, 64, 64, .09);
        font-size: 13px;
        font-weight: 600;
    }

    .product-detail-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .product-detail-table tbody tr {
        transition: background .2s ease;
    }

    .product-detail-table tbody tr:hover {
        background: #f5efed;
    }

    /* Goods Description */

    .product-detail-goods-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .product-detail-goods-item {
        display: flex;
        align-items: flex-start;
        gap: 17px;
        padding: 17px 18px;
        border-radius: 7px;
        background: #fff;
        border: 1px solid rgba(103, 64, 64, .09);
    }

    .product-detail-goods-item > span {
        color: var(--pd-secondary);
        font-size: 13px;
        font-weight: 900;
    }

    .product-detail-goods-item p {
        margin: 0;
        color: #6e5d5a;
        font-size: 13px;
        line-height: 2;
    }

    /* Contact CTA */

    .product-detail-contact {
        padding: 80px 0;
        background: #eee7e4;
    }

    .product-detail-contact-box {
        position: relative;
        min-height: 320px;
        display: flex;
        align-items: center;
        overflow: hidden;
        padding: 55px 60px;
        border-radius: 13px;
        background:
            radial-gradient(circle at 85% 15%, rgba(186, 173, 173, .18), transparent 30%),
            linear-gradient(120deg, #362727, #674040);
        color: #fff;
    }

    .product-detail-contact-decoration {
        position: absolute;
        left: 22%;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, .045);
        font-size: 250px;
        font-weight: 900;
        letter-spacing: -20px;
        pointer-events: none;
    }

    .product-detail-contact-content {
        position: relative;
        z-index: 1;
        max-width: 690px;
    }

    .product-detail-contact-content > span {
        color: #d0b9b6;
        font-size: 10px;
        letter-spacing: 3px;
        font-weight: 800;
    }

    .product-detail-contact-content h2 {
        margin: 18px 0 13px;
        color: #fff;
        font-size: 25px;
        line-height: 2;
        font-weight: 900;
    }

    .product-detail-contact-content p {
        margin: 0;
        color: #d7c9c6;
        font-size: 13px;
        line-height: 2.4;
    }

    .product-detail-contact-content a {
        display: inline-flex;
        align-items: center;
        gap: 20px;
        margin-top: 25px;
        padding: 14px 22px;
        border-radius: 6px;
        color: var(--pd-dark);
        background: #e4d7d3;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
        transition: all .3s ease;
    }

    .product-detail-contact-content a:hover {
        background: #fff;
        transform: translateY(-3px);
    }

    .product-detail-contact-content a span {
        font-size: 19px;
    }

    .product-detail-contact-mark {
        position: absolute;
        left: 55px;
        bottom: 35px;
        color: rgba(255, 255, 255, .35);
        font-size: 11px;
        letter-spacing: 3px;
        line-height: 1.8;
        font-weight: 800;
        text-align: left;
    }

    /* More Products */

    .product-detail-more {
        padding: 25px 0;
        background: #fff;
    }

    .product-detail-more a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        color: var(--pd-primary);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: gap .25s ease;
    }

    .product-detail-more a:hover {
        gap: 23px;
    }

    .product-detail-more a span:last-child {
        font-size: 20px;
    }

    /* Responsive */

    @media (max-width: 1000px) {
        .product-detail-hero-grid {
            gap: 40px;
        }

        .product-detail-image-frame {
            height: 400px;
            min-height: 400px;
        }

        .product-detail-contact-box {
            padding: 45px;
        }
    }

    @media (max-width: 760px) {
        .product-detail-container {
            width: min(100% - 32px, 600px);
        }

        .product-detail-breadcrumb {
            padding: 19px 0;
        }

        .product-detail-breadcrumb-inner {
            gap: 8px;
            font-size: 11px;
        }

        .product-detail-hero {
            padding: 40px 0 60px;
        }

        .product-detail-hero-grid {
            grid-template-columns: 1fr;
            gap: 35px;
        }

        .product-detail-image-frame {
            height: 360px;
            min-height: 360px;
            border-radius: 12px 100px 12px 12px;
        }

        .product-detail-main-image {
            padding: 20px;
        }

        .product-detail-intro {
            padding: 0;
        }

        .product-detail-eyebrow {
            margin-bottom: 15px;
        }

        .product-detail-intro h1 {
            font-size: 30px;
        }

        .product-detail-description {
            font-size: 13px;
        }

        .product-detail-intro-meta {
            margin-top: 25px;
        }

        .product-detail-intro-meta > div > span:last-child {
            font-size: 10px;
            line-height: 1.8;
        }

        .product-detail-specifications {
            padding: 60px 0;
        }

        .product-detail-section-heading {
            margin-bottom: 30px;
        }

        .product-detail-section-heading h2 {
            font-size: 22px;
        }

        .product-detail-section-heading p {
            font-size: 12px;
        }

        .product-detail-section-number {
            font-size: 50px;
        }

        .product-detail-info-section {
            padding: 20px 15px;
            margin-bottom: 25px;
        }

        .product-detail-property-grid {
            grid-template-columns: 1fr;
        }

        .product-detail-contact {
            padding: 50px 0;
        }

        .product-detail-contact-box {
            min-height: 300px;
            padding: 35px 25px;
        }

        .product-detail-contact-content h2 {
            font-size: 20px;
        }

        .product-detail-contact-content p {
            font-size: 12px;
        }

        .product-detail-contact-mark {
            left: 20px;
            bottom: 15px;
            font-size: 9px;
        }

        .product-detail-contact-decoration {
            font-size: 160px;
        }
    }

    @media (max-width: 480px) {
        .product-detail-container {
            width: calc(100% - 28px);
        }

        .product-detail-image-frame {
            height: 290px;
            min-height: 290px;
        }

        .product-detail-placeholder span {
            font-size: 75px;
        }

        .product-detail-intro h1 {
            font-size: 25px;
        }

        .product-detail-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .product-detail-primary-button,
        .product-detail-back-button {
            width: 100%;
        }

        .product-detail-section-heading h2 {
            font-size: 19px;
        }

        .product-detail-section-number {
            font-size: 40px;
        }

        .product-detail-info-heading h3 {
            font-size: 15px;
        }

        .product-detail-info-icon {
            width: 42px;
            height: 42px;
        }

        .product-detail-property {
            font-size: 12px;
        }

        .product-detail-contact-content h2 {
            font-size: 18px;
        }

        .product-detail-contact-mark {
            display: none;
        }
    }
</style>
@endpush