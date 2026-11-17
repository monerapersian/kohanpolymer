@extends('layouts.site')

@section('title', 'درباره ما | کهن پلیمر البرز')

@section('description', 'با کهن پلیمر البرز، رویکرد مهندسی، حوزه‌های فعالیت و ارزش‌های این مجموعه در صنعت پلیمر آشنا شوید.')

@section('content')

@php
    $activityAreas = is_array($content?->activity_areas)
        ? array_filter($content->activity_areas)
        : [];

    $phones = is_array($content?->phones)
        ? array_filter($content->phones)
        : [];

    $primaryPhone = reset($phones) ?: null;
@endphp

<!-- About Hero -->
<section class="about-page-hero">
    <div class="about-page-hero-grid"></div>
    <div class="about-page-hero-glow"></div>

    <div class="container-main">
        <div class="about-page-hero-content">

            <div class="about-page-breadcrumb reveal">
                <a href="{{ url('/') }}">صفحه اصلی</a>
                <span>/</span>
                <span>درباره ما</span>
            </div>

            <div class="about-page-hero-main">
                <div class="about-page-hero-copy reveal">

                    <span class="about-page-eyebrow">
                        <span></span>
                        KOHAN POLYMER ALBORZ
                    </span>

                    <h1>
                        شناخت عمیق‌تر،
                        <br>
                        <span>همکاری مؤثرتر.</span>
                    </h1>

                    <p>
                        {{ $content?->about_text ?: 'کهن پلیمر البرز با تمرکز بر حوزه مواد و محصولات پلیمری، به دنبال ایجاد ارتباطی حرفه‌ای میان نیازهای صنعتی و راهکارهای مناسب است.' }}
                    </p>

                    <a href="#company-introduction" class="about-page-scroll">
                        <span>آشنایی با مجموعه</span>
                        <span>↓</span>
                    </a>

                </div>

                <div class="about-page-hero-visual reveal reveal-delay-1">

                    <div class="about-page-visual-frame">
                        <img
                            src="{{ $content?->hero_image ? \Illuminate\Support\Facades\Storage::url($content->hero_image) : asset('img/hero-img.jpg') }}"
                            alt="مجموعه کهن پلیمر البرز"
                        >

                        <div class="about-page-visual-overlay"></div>

                        <div class="about-page-visual-caption">
                            <span>OUR PERSPECTIVE</span>
                            <strong>مهندسی برای کاربرد واقعی</strong>
                        </div>
                    </div>

                    <div class="about-page-visual-decoration">
                        <span>KPA</span>
                    </div>

                    @if ($content?->logo)
                        <div class="about-page-logo">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($content->logo) }}"
                                alt="لوگوی کهن پلیمر البرز"
                            >
                        </div>
                    @endif

                </div>
            </div>

            <div class="about-page-hero-bottom">
                <span>ENGINEERING THE FUTURE</span>
                <span>ALBORZ / IRAN</span>
            </div>

        </div>
    </div>
</section>

<!-- Company Introduction -->
<section class="about-page-introduction" id="company-introduction">
    <div class="container-main">

        <div class="about-page-intro-grid">

            <div class="about-page-intro-aside reveal">

                <div class="about-page-intro-brand">
                    <span class="about-page-intro-brand-small">WHO WE ARE</span>

                    @if ($content?->logo)
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($content->logo) }}"
                            alt="کهن پلیمر البرز"
                        >
                    @else
                        <span class="about-page-intro-monogram">KPA</span>
                    @endif

                    <span class="about-page-intro-brand-bottom">
                        KOHAN POLYMER ALBORZ
                    </span>
                </div>

                <!-- <div class="about-page-intro-index">
                    <span>01</span>
                    <span></span>
                    <span>04</span>
                </div> -->

            </div>

            <div class="about-page-intro-content reveal reveal-delay-1">

                <div class="about-page-section-label">
                    <span></span>
                    <span>درباره کهن پلیمر البرز</span>
                    <small>ABOUT US</small>
                </div>

                <h2>
                    {{ $content?->about_title ?: 'شناخت نیاز، آغاز یک همکاری مؤثر.' }}
                </h2>

                <div class="about-page-intro-text">
                    <span></span>
                    <p>
                        {{ $content?->about_text ?: 'کهن پلیمر البرز با تمرکز بر حوزه مواد و محصولات پلیمری، به دنبال ایجاد ارتباطی حرفه‌ای میان نیازهای صنعتی و راهکارهای مناسب است. هدف ما ارائه اطلاعات روشن، پاسخ‌گویی دقیق و فراهم کردن زمینه‌ای برای همکاری بلندمدت است.' }}
                    </p>
                </div>

                <div class="about-page-intro-footer">
                    <span>تخصص، کیفیت و همراهی</span>
                    <span>ABOUT</span>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- Industry Perspective -->
<section class="about-page-perspective">

    <div class="about-page-perspective-image" aria-hidden="true">
        <img
            src="{{ $content?->hero_image ? \Illuminate\Support\Facades\Storage::url($content->hero_image) : asset('img/hero-img.jpg') }}"
            alt=""
        >
    </div>

    <div class="container-main">

        <div class="about-page-perspective-heading reveal">

            <div>
                <div class="about-page-section-label">
                    <span></span>
                    <span>نگاه ما به صنعت</span>
                    <small>OUR PHILOSOPHY</small>
                </div>

                <h2>
                    {{ $content?->industry_title ?: 'هر ماده، وقتی ارزشمند است که راه‌حلی بسازد.' }}
                </h2>
            </div>

            <!-- <div class="about-page-perspective-number">
                <span>02</span>
                <span>OUR APPROACH</span>
            </div> -->

        </div>

        <div class="about-page-perspective-body reveal">
            <div class="about-page-perspective-line"></div>

            <p>
                {{ $content?->industry_text ?: 'در صنعت پلیمر، انتخاب درست تنها به مشخصات یک ماده محدود نمی‌شود. شناخت کاربرد، توجه به کیفیت و درک نیاز واقعی مشتری، بخشی از مسیری است که به یک نتیجه قابل اتکا می‌رسد.' }}
            </p>
        </div>

        <div class="about-page-values">

            <article class="about-page-value reveal">
                <span class="about-page-value-number">01</span>
                <div class="about-page-value-icon">↗</div>
                <span>رویکرد</span>
                <strong>نگاه مهندسی</strong>
            </article>

            <article class="about-page-value reveal reveal-delay-1">
                <span class="about-page-value-number">02</span>
                <div class="about-page-value-icon">◇</div>
                <span>تمرکز</span>
                <strong>کیفیت پایدار</strong>
            </article>

            <article class="about-page-value reveal reveal-delay-2">
                <span class="about-page-value-number">03</span>
                <div class="about-page-value-icon">＋</div>
                <span>همراهی</span>
                <strong>ارتباط حرفه‌ای</strong>
            </article>

        </div>

    </div>
</section>

<!-- Activity Areas -->
@if (count($activityAreas))
<section class="about-page-activities">
    <div class="container-main">

        <div class="about-page-activities-heading reveal">

            <div>
                <div class="about-page-section-label">
                    <span></span>
                    <span>حوزه‌های فعالیت</span>
                    <small>OUR ACTIVITIES</small>
                </div>

                <h2>
                    گستره فعالیت،
                    <span>نگاه تخصصی.</span>
                </h2>
            </div>

            <p>
                زمینه‌های فعالیت کهن پلیمر البرز را بشناسید و با حوزه‌های تخصصی این مجموعه آشنا شوید.
            </p>

        </div>

        <div class="about-page-activities-grid">

            @foreach ($activityAreas as $index => $area)
                <article class="about-page-activity-card reveal"
                    style="--activity-index: {{ $index }}">

                    <div class="about-page-activity-top">
                        <span>{{ sprintf('%02d', $index + 1) }}</span>
                        <span>↙</span>
                    </div>

                    <div class="about-page-activity-symbol">
                        ◇
                    </div>

                    <h3>{{ $area }}</h3>

                    <div class="about-page-activity-bottom">
                        <span>KOHAN POLYMER</span>
                        <span></span>
                    </div>

                </article>
            @endforeach

        </div>

    </div>
</section>
@endif

<!-- Different Perspective -->
<!-- <section class="about-page-difference">
    <div class="container-main">

        <div class="about-page-difference-panel">

            <div class="about-page-difference-decoration">
                <span>KPA</span>
                <div></div>
                <div></div>
            </div>

            <div class="about-page-difference-content reveal">

                <div class="about-page-section-label">
                    <span></span>
                    <span>نگاه متفاوت</span>
                    <small>A DIFFERENT PERSPECTIVE</small>
                </div>

                <h2>
                    {{ $content?->difference_title ?: 'محصول خوب، نتیجه تصمیم‌های درست در تمام مسیر است.' }}
                </h2>

                <p>
                    {{ $content?->difference_text ?: 'انتخاب مواد، شناخت کاربرد و توجه به جزئیات، هرکدام در کیفیت نتیجه نهایی نقش دارند. ما به ارزش تصمیم‌گیری آگاهانه در این مسیر باور داریم.' }}
                </p>

            </div>

            <div class="about-page-difference-footer">
                <span>QUALITY / ENGINEERING / TRUST</span>
                <span>03 / OUR APPROACH</span>
            </div>

        </div>

    </div>
</section> -->

<!-- Contact CTA -->
<section class="about-page-contact">
    <div class="container-main">

        <div class="about-page-contact-inner reveal">

            <div class="about-page-contact-copy">

                <div class="about-page-section-label">
                    <span></span>
                    <span>شروع یک همکاری</span>
                    <small>LET'S CONNECT</small>
                </div>

                <h2>
                    مسیر همکاری،
                    <br>
                    از یک گفت‌وگو آغاز می‌شود.
                </h2>

                <p>
                    برای دریافت اطلاعات محصولات، بررسی نیاز پروژه یا شروع همکاری، با ما در ارتباط باشید.
                </p>

            </div>

            <div class="about-page-contact-actions">

                @if ($primaryPhone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}"
                        class="about-page-contact-phone">
                        <span>تماس مستقیم</span>
                        <strong>{{ $primaryPhone }}</strong>
                        <span>↗</span>
                    </a>
                @endif

                @if ($content?->email)
                    <a href="mailto:{{ $content->email }}" class="about-page-contact-email">
                        {{ $content->email }}
                    </a>
                @endif

                <a href="{{ url('/') }}#contact" class="about-page-contact-button">
                    <span>ارسال درخواست همکاری</span>
                    <span>←</span>
                </a>

            </div>

        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
    .about-page-hero {
        position: relative;
        overflow: hidden;
        padding: 55px 0 30px;
        color: #F4F0EE;
        background: #362727;
    }

    .about-page-hero-grid {
        position: absolute;
        inset: 0;
        opacity: .13;
        background-image:
            linear-gradient(rgba(244,240,238,.12) 1px, transparent 1px),
            linear-gradient(90deg, rgba(244,240,238,.12) 1px, transparent 1px);
        background-size: 65px 65px;
        mask-image: linear-gradient(to bottom, black, transparent);
    }

    .about-page-hero-glow {
        position: absolute;
        width: 600px;
        height: 600px;
        top: -280px;
        right: -150px;
        border-radius: 50%;
        background: rgba(141,102,102,.24);
        filter: blur(100px);
        pointer-events: none;
    }

    .about-page-hero-content {
        position: relative;
        z-index: 1;
    }

    .about-page-breadcrumb {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #baadad;
        font-size: 12px;
        margin-bottom: 75px;
    }

    .about-page-breadcrumb a {
        color: #F4F0EE;
        transition: color .3s;
    }

    .about-page-breadcrumb a:hover {
        color: #baadad;
    }

    .about-page-hero-main {
        display: grid;
        grid-template-columns: 1fr .85fr;
        align-items: center;
        gap: 75px;
        padding-bottom: 75px;
    }

    .about-page-eyebrow,
    .about-page-section-label {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12px;
        color: #8d6666;
    }

    .about-page-eyebrow span,
    .about-page-section-label > span:first-child {
        width: 25px;
        height: 1px;
        background: #8d6666;
    }

    .about-page-hero-copy h1 {
        font-size: clamp(40px, 5.5vw, 76px);
        line-height: 1.5;
        font-weight: 900;
        margin: 25px 0;
        letter-spacing: -2px;
    }

    .about-page-hero-copy h1 span {
        color: #baadad;
    }

    .about-page-hero-copy > p {
        max-width: 540px;
        color: #d5caca;
        line-height: 2.5;
        font-size: 14px;
    }

    .about-page-scroll {
        display: inline-flex;
        align-items: center;
        gap: 22px;
        margin-top: 35px;
        padding-bottom: 10px;
        border-bottom: 1px solid #8d6666;
        font-size: 13px;
        color: #F4F0EE;
    }

    .about-page-scroll span:last-child {
        color: #baadad;
        font-size: 20px;
        transition: transform .3s;
    }

    .about-page-scroll:hover span:last-child {
        transform: translateY(5px);
    }

    .about-page-hero-visual {
        position: relative;
        padding: 0 25px 25px 0;
    }

    .about-page-visual-frame {
        height: 470px;
        position: relative;
        overflow: hidden;
        border-radius: 180px 12px 12px 12px;
        background: #674040;
    }

    .about-page-visual-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .8;
        transition: transform .8s;
    }

    .about-page-visual-frame:hover img {
        transform: scale(1.06);
    }

    .about-page-visual-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, rgba(54,39,39,.9), transparent 60%);
    }

    .about-page-visual-caption {
        position: absolute;
        right: 30px;
        bottom: 30px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .about-page-visual-caption span {
        font-size: 10px;
        letter-spacing: 2px;
        color: #baadad;
    }

    .about-page-visual-caption strong {
        font-size: 16px;
    }

    .about-page-visual-decoration {
        position: absolute;
        left: -35px;
        top: 65px;
        color: rgba(244,240,238,.12);
        font-size: 65px;
        font-weight: 900;
        writing-mode: vertical-rl;
        pointer-events: none;
    }

    .about-page-logo {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 85px;
        height: 85px;
        padding: 12px;
        background: #F4F0EE;
        border: 5px solid #362727;
        border-radius: 50%;
        display: grid;
        place-items: center;
    }

    .about-page-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .about-page-hero-bottom {
        border-top: 1px solid rgba(244,240,238,.15);
        padding-top: 20px;
        display: flex;
        justify-content: space-between;
        color: #baadad;
        font-size: 10px;
        letter-spacing: 2px;
    }

    .about-page-introduction {
        padding: 120px 0;
        background: #F4F0EE;
    }

    .about-page-intro-grid {
        display: grid;
        grid-template-columns: .7fr 1.3fr;
        gap: 90px;
        align-items: center;
    }

    .about-page-intro-brand {
        min-height: 390px;
        padding: 35px;
        background: #362727;
        color: #F4F0EE;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
        border-radius: 4px 110px 4px 4px;
    }

    .about-page-intro-brand::before,
    .about-page-intro-brand::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(186,173,173,.2);
        border-radius: 50%;
    }

    .about-page-intro-brand::before {
        top: 60px;
        right: -100px;
    }

    .about-page-intro-brand::after {
        bottom: -130px;
        left: -80px;
    }

    .about-page-intro-brand-small,
    .about-page-intro-brand-bottom {
        font-size: 10px;
        letter-spacing: 2px;
        color: #baadad;
        position: relative;
        z-index: 1;
    }

    .about-page-intro-brand img {
        width: 180px;
        height: 180px;
        object-fit: contain;
        position: relative;
        z-index: 1;
        background: #F4F0EE;
        border-radius: 50%;
        padding: 15px;
    }

    .about-page-intro-monogram {
        font-size: 85px;
        font-weight: 900;
        color: #baadad;
    }

    .about-page-intro-index {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 22px;
        color: #8d6666;
        font-size: 12px;
    }

    .about-page-intro-index span:nth-child(2) {
        width: 60px;
        height: 1px;
        background: #baadad;
    }

    .about-page-section-label {
        color: #674040;
        flex-wrap: wrap;
    }

    .about-page-section-label small {
        font-size: 9px;
        letter-spacing: 1.5px;
        color: #8d6666;
        margin-right: 5px;
    }

    .about-page-intro-content h2,
    .about-page-perspective-heading h2,
    .about-page-activities-heading h2 {
        font-size: clamp(25px, 3.5vw, 43px);
        line-height: 1.9;
        color: #362727;
        font-weight: 900;
        margin: 28px 0;
    }

    .about-page-intro-text {
        display: flex;
        gap: 20px;
        align-items: stretch;
    }

    .about-page-intro-text > span {
        flex-shrink: 0;
        width: 2px;
        background: #8d6666;
    }

    .about-page-intro-text p {
        color: #674040;
        line-height: 2.7;
        font-size: 14px;
    }

    .about-page-intro-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding-top: 25px;
        margin-top: 35px;
        border-top: 1px solid #ddd2ce;
        color: #8d6666;
        font-size: 11px;
    }

    .about-page-perspective {
        padding: 110px 0;
        background: #ebe3df;
    }

    .about-page-perspective-heading,
    .about-page-activities-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 40px;
    }

    .about-page-perspective-heading h2 {
        max-width: 700px;
        margin-bottom: 0;
    }

    .about-page-perspective-number {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 8px;
        color: #8d6666;
    }

    .about-page-perspective-number span:first-child {
        font-size: 55px;
        font-weight: 900;
        color: #baadad;
    }

    .about-page-perspective-number span:last-child {
        font-size: 9px;
        letter-spacing: 2px;
    }

    .about-page-perspective-body {
        display: flex;
        gap: 25px;
        margin-top: 35px;
        max-width: 850px;
    }

    .about-page-perspective-line {
        width: 3px;
        flex-shrink: 0;
        background: #8d6666;
    }

    .about-page-perspective-body p {
        font-size: 14px;
        line-height: 2.7;
        color: #674040;
    }

    .about-page-values {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-top: 65px;
    }

    .about-page-value {
        position: relative;
        min-height: 210px;
        padding: 25px;
        background: #F4F0EE;
        border: 1px solid rgba(103,64,64,.1);
        transition: transform .3s, background .3s;
    }

    .about-page-value:hover {
        transform: translateY(-7px);
        background: #362727;
        color: #F4F0EE;
    }

    .about-page-value-number {
        position: absolute;
        left: 25px;
        top: 22px;
        color: #baadad;
        font-size: 12px;
    }

    .about-page-value-icon {
        color: #8d6666;
        font-size: 32px;
        margin-bottom: 20px;
    }

    .about-page-value > span:nth-last-child(2) {
        display: block;
        font-size: 12px;
        color: #8d6666;
        margin-bottom: 8px;
    }

    .about-page-value strong {
        font-size: 17px;
    }

    .about-page-activities {
        padding: 110px 0;
        background: #362727;
        color: #F4F0EE;
        position: relative;
        overflow: hidden;
    }

    .about-page-activities::before {
        content: "KPA";
        position: absolute;
        left: -30px;
        top: 10px;
        font-size: 250px;
        font-weight: 900;
        color: rgba(244,240,238,.025);
        pointer-events: none;
    }

    .about-page-activities-heading {
        position: relative;
        z-index: 1;
        margin-bottom: 55px;
    }

    .about-page-activities-heading h2 {
        color: #F4F0EE;
    }

    .about-page-activities-heading h2 span {
        color: #baadad;
    }

    .about-page-activities-heading > p {
        max-width: 330px;
        color: #baadad;
        font-size: 13px;
        line-height: 2.4;
    }

    .about-page-activities-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        position: relative;
        z-index: 1;
    }

    .about-page-activity-card {
        min-height: 230px;
        padding: 25px;
        display: flex;
        flex-direction: column;
        background: rgba(244,240,238,.06);
        border: 1px solid rgba(244,240,238,.12);
        transition: background .3s, transform .3s;
    }

    .about-page-activity-card:hover {
        background: #674040;
        transform: translateY(-6px);
    }

    .about-page-activity-top,
    .about-page-activity-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .about-page-activity-top {
        color: #baadad;
        font-size: 12px;
    }

    .about-page-activity-top span:last-child {
        font-size: 19px;
    }

    .about-page-activity-symbol {
        color: #8d6666;
        font-size: 30px;
        margin: 20px 0;
    }

    .about-page-activity-card h3 {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: auto;
        line-height: 2;
    }

    .about-page-activity-bottom {
        margin-top: 25px;
        color: #baadad;
        font-size: 9px;
        letter-spacing: 1.5px;
    }

    .about-page-activity-bottom span:last-child {
        width: 35px;
        height: 1px;
        background: #8d6666;
    }

    .about-page-difference {
        padding: 100px 0;
        background: #F4F0EE;
    }

    .about-page-difference-panel {
        position: relative;
        padding: 75px;
        overflow: hidden;
        background: #674040;
        color: #F4F0EE;
        border-radius: 4px 100px 4px 4px;
    }

    .about-page-difference-decoration {
        position: absolute;
        left: -40px;
        top: -80px;
        width: 350px;
        height: 350px;
        opacity: .12;
        pointer-events: none;
    }

    .about-page-difference-decoration > span {
        font-size: 120px;
        font-weight: 900;
        color: #F4F0EE;
    }

    .about-page-difference-decoration div {
        position: absolute;
        width: 260px;
        height: 260px;
        border: 1px solid #F4F0EE;
        border-radius: 50%;
        left: 0;
        bottom: -80px;
    }

    .about-page-difference-decoration div:last-child {
        width: 180px;
        height: 180px;
        left: 40px;
        bottom: -40px;
    }

    .about-page-difference-content {
        position: relative;
        z-index: 1;
        max-width: 850px;
    }

    .about-page-difference-content .about-page-section-label {
        color: #baadad;
    }

    .about-page-difference-content h2 {
        font-size: clamp(25px, 3.5vw, 43px);
        line-height: 1.9;
        margin: 25px 0;
        font-weight: 900;
    }

    .about-page-difference-content p {
        max-width: 700px;
        color: #e0d4d1;
        font-size: 14px;
        line-height: 2.7;
    }

    .about-page-difference-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 65px;
        padding-top: 20px;
        border-top: 1px solid rgba(244,240,238,.2);
        color: #baadad;
        font-size: 9px;
        letter-spacing: 1.5px;
    }

    .about-page-contact {
        padding: 30px 0 110px;
        background: #F4F0EE;
    }

    .about-page-contact-inner {
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 70px;
        align-items: center;
        padding: 60px;
        border: 1px solid #d9cdca;
        background: #eee7e3;
    }

    .about-page-contact-copy h2 {
        font-size: clamp(25px, 3.5vw, 42px);
        line-height: 1.9;
        font-weight: 900;
        color: #362727;
        margin: 25px 0;
    }

    .about-page-contact-copy p {
        color: #674040;
        font-size: 13px;
        line-height: 2.5;
        max-width: 540px;
    }

    .about-page-contact-actions {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .about-page-contact-phone {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 20px;
        background: #362727;
        color: #F4F0EE;
    }

    .about-page-contact-phone > span:first-child {
        color: #baadad;
        font-size: 12px;
    }

    .about-page-contact-phone strong {
        font-size: 15px;
        direction: ltr;
    }

    .about-page-contact-phone > span:last-child {
        color: #baadad;
    }

    .about-page-contact-email {
        color: #674040;
        font-size: 13px;
        direction: ltr;
        text-align: right;
    }

    .about-page-contact-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 17px 20px;
        border: 1px solid #8d6666;
        color: #674040;
        font-size: 13px;
        transition: background .3s, color .3s;
    }

    .about-page-contact-button:hover {
        background: #674040;
        color: #F4F0EE;
    }

    @media (max-width: 900px) {
        .about-page-hero-main {
            gap: 35px;
        }

        .about-page-intro-grid {
            gap: 40px;
        }

        .about-page-activities-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .about-page-contact-inner {
            gap: 35px;
            padding: 40px;
        }
    }

    @media (max-width: 640px) {
        .about-page-hero {
            padding-top: 35px;
        }

        .about-page-breadcrumb {
            margin-bottom: 45px;
        }

        .about-page-hero-main {
            grid-template-columns: 1fr;
            gap: 45px;
            padding-bottom: 45px;
        }

        .about-page-hero-copy h1 {
            font-size: 43px;
        }

        .about-page-hero-copy > p {
            font-size: 13px;
        }

        .about-page-hero-visual {
            padding: 0 15px 15px 0;
        }

        .about-page-visual-frame {
            height: 340px;
            border-radius: 130px 10px 10px 10px;
        }

        .about-page-visual-decoration {
            left: -15px;
            font-size: 45px;
        }

        .about-page-logo {
            width: 70px;
            height: 70px;
        }

        .about-page-hero-bottom {
            font-size: 8px;
        }

        .about-page-introduction,
        .about-page-perspective,
        .about-page-activities {
            padding: 75px 0;
        }

        .about-page-intro-grid {
            grid-template-columns: 1fr;
            gap: 35px;
        }

        .about-page-intro-brand {
            min-height: 280px;
            border-radius: 4px 75px 4px 4px;
        }

        .about-page-intro-brand img {
            width: 130px;
            height: 130px;
        }

        .about-page-intro-content h2,
        .about-page-perspective-heading h2,
        .about-page-activities-heading h2 {
            font-size: 27px;
        }

        .about-page-intro-text p,
        .about-page-perspective-body p {
            font-size: 13px;
        }

        .about-page-perspective-heading,
        .about-page-activities-heading {
            align-items: start;
            flex-direction: column;
            gap: 20px;
        }

        .about-page-perspective-number {
            flex-direction: row;
            align-items: center;
        }

        .about-page-perspective-number span:first-child {
            font-size: 35px;
        }

        .about-page-values {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 40px;
        }

        .about-page-value {
            min-height: 160px;
        }

        .about-page-activities-heading > p {
            max-width: 100%;
        }

        .about-page-activities-grid {
            grid-template-columns: 1fr;
        }

        .about-page-activity-card {
            min-height: 190px;
        }

        .about-page-difference {
            padding: 65px 0;
        }

        .about-page-difference-panel {
            padding: 40px 25px;
            border-radius: 4px 65px 4px 4px;
        }

        .about-page-difference-content h2 {
            font-size: 27px;
        }

        .about-page-difference-content p {
            font-size: 13px;
        }

        .about-page-difference-footer {
            margin-top: 40px;
            font-size: 7px;
        }

        .about-page-contact {
            padding-bottom: 75px;
        }

        .about-page-contact-inner {
            grid-template-columns: 1fr;
            gap: 35px;
            padding: 30px 20px;
        }

        .about-page-contact-copy h2 {
            font-size: 28px;
        }

        .about-page-contact-phone {
            flex-wrap: wrap;
        }

        .about-page-contact-phone strong {
            font-size: 13px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .about-page-visual-frame img,
        .about-page-value,
        .about-page-contact-button {
            transition: none;
        }
    }


    .about-page-perspective {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 110px 0;
        background: #ebe3df;
    }

    /* Background image */
    .about-page-perspective-image {
        position: absolute;
        z-index: 0;
        left: 0;
        top: 0;
        bottom: 0;
        width: 43%;
        pointer-events: none;
        overflow: hidden;

        /* Smooth fading on all edges */
        -webkit-mask-image:
            linear-gradient(
                to right,
                transparent 0%,
                rgba(0, 0, 0, .35) 8%,
                #000 22%,
                #000 55%,
                rgba(0, 0, 0, .8) 68%,
                rgba(0, 0, 0, .35) 82%,
                transparent 100%
            ),
            linear-gradient(
                to bottom,
                transparent 0%,
                rgba(0, 0, 0, .4) 12%,
                #000 30%,
                #000 70%,
                rgba(0, 0, 0, .4) 90%,
                transparent 100%
            );

        -webkit-mask-composite: source-in;

        mask-image:
            linear-gradient(
                to right,
                transparent 0%,
                rgba(0, 0, 0, .35) 8%,
                #000 22%,
                #000 55%,
                rgba(0, 0, 0, .8) 68%,
                rgba(0, 0, 0, .35) 82%,
                transparent 100%
            ),
            linear-gradient(
                to bottom,
                transparent 0%,
                rgba(0, 0, 0, .4) 12%,
                #000 30%,
                #000 70%,
                rgba(0, 0, 0, .4) 90%,
                transparent 100%
            );

        mask-composite: intersect;
    }

    .about-page-perspective-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: .7;
        filter: saturate(.55) contrast(.9);
    }

    /* Brand color overlay */
    .about-page-perspective-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(103, 64, 64, .82),
            rgba(54, 39, 39, .48)
        );
        mix-blend-mode: multiply;
    }

    /* Keep content above the image */
    .about-page-perspective > .container-main {
        position: relative;
        z-index: 1;
    }

    /* Mobile */
    @media (max-width: 640px) {
        .about-page-perspective {
            padding: 75px 0;
        }

        .about-page-perspective-image {
            width: 90%;
            opacity: .65;

            -webkit-mask-image:
                linear-gradient(
                    to right,
                    transparent 0%,
                    rgba(0, 0, 0, .4) 15%,
                    #000 35%,
                    #000 55%,
                    transparent 100%
                ),
                linear-gradient(
                    to bottom,
                    transparent 0%,
                    rgba(0, 0, 0, .4) 10%,
                    #000 30%,
                    #000 70%,
                    transparent 100%
                );

            -webkit-mask-composite: source-in;

            mask-image:
                linear-gradient(
                    to right,
                    transparent 0%,
                    rgba(0, 0, 0, .4) 15%,
                    #000 35%,
                    #000 55%,
                    transparent 100%
                ),
                linear-gradient(
                    to bottom,
                    transparent 0%,
                    rgba(0, 0, 0, .4) 10%,
                    #000 30%,
                    #000 70%,
                    transparent 100%
                );

            mask-composite: intersect;
        }

        .about-page-perspective-image img {
            opacity: .6;
        }
    }

</style>
@endpush