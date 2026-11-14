@extends('layouts.site')

@section('title', 'کهن پلیمر البرز | مهندسی آینده در صنعت پلیمر')

@section('description', 'کهن پلیمر البرز؛ تولید و عرضه محصولات پلیمری صنعتی با تمرکز بر کیفیت، دوام و عملکرد.')

@section('content')

    @php
        $activityAreas = is_array($content?->activity_areas)
            ? array_filter($content->activity_areas)
            : [];

        $phones = is_array($content?->phones)
            ? array_filter($content->phones)
            : [];

        if (empty($phones) && !empty($content?->phone)) {
            $phones = [$content->phone];
        }

        $primaryPhone = reset($phones) ?: null;

        $publishedProducts = $products ?? collect();
        $publishedBlogPosts = $blogPosts ?? collect();
    @endphp

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="hero-grid"></div>
        <div class="hero-glow"></div>

        <div class="container-main">
            <div class="hero-content">

                <div class="hero-copy">
                    <div class="hero-kicker hero-enter">
                        راهکارهای نوین در صنعت پلیمر
                    </div>

                    <h1 class="hero-title hero-enter delay-1">
                        فراتر از<br>
                        <span class="accent">ماده،</span>
                        <br class="hidden sm:block">
                        آغاز یک راهکار.
                    </h1>

                    <p class="hero-description hero-enter delay-2">
                        {{ $content?->hero_slogan ?: 'از انتخاب مواد اولیه تا رسیدن به محصول نهایی؛ با نگاهی مهندسی، شناخت دقیق نیاز صنعت و تمرکز بر کیفیت در هر مرحله از مسیر.' }}
                    </p>

                    <div class="hero-actions hero-enter delay-2">
                        <a href="#products" class="btn-primary">
                            آشنایی با محصولات
                            <span class="btn-arrow">←</span>
                        </a>

                        <a href="#about" class="btn-outline">
                            شناخت مجموعه
                            <span>↗</span>
                        </a>
                    </div>
                </div>

                <div class="hero-visual hero-enter delay-2">
                    <div class="hero-image-frame">
                        <img
                            class="hero-image"
                            src="{{ $content?->hero_image ? \Illuminate\Support\Facades\Storage::url($content->hero_image) : asset('img/hero-img.jpg') }}"
                            alt="محیط صنعتی و تجهیزات تولید"
                        >

                        <div class="hero-image-label">
                            <div>
                                <small>رویکرد ما</small>
                                <strong>کیفیت، از نقطه شروع</strong>
                            </div>
                            <span class="text-2xl">↙</span>
                        </div>
                    </div>

                    <div class="floating-note">
                        <span>نگاه متفاوت به مواد</span>
                        <strong>مهندسی برای کاربرد واقعی</strong>
                    </div>
                </div>

            </div>

            <div class="hero-bottom">
                <p>
                    کهن پلیمر البرز — همراه مسیر تولید و توسعه صنعتی
                </p>

                <a href="#intro" class="scroll-indicator">
                    کشف بیشتر
                    <span></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section" id="intro">
        <div class="container-main">

            <div class="intro-shell">

                <!-- Brand Visual -->
                <aside class="intro-brand-panel reveal">

                    <div class="intro-panel-top">
                        <span class="intro-brand-name">KOHAN POLYMER</span>
                        <span class="intro-brand-code">ALBORZ / IRAN</span>
                    </div>

                    <div class="intro-brand-art">
                        <div class="intro-art-circle intro-art-circle-one"></div>
                        <div class="intro-art-circle intro-art-circle-two"></div>
                        <div class="intro-art-circle intro-art-circle-three"></div>

                        <div class="intro-brand-symbol">K</div>

                        <div class="intro-brand-monogram">KPA</div>
                    </div>

                    <div class="intro-panel-bottom">
                        <div class="intro-panel-line"></div>

                        <div class="intro-panel-caption">
                            <span>OUR APPROACH</span>
                            <strong>فراتر از یک تأمین‌کننده</strong>
                        </div>

                        <span class="intro-panel-number">01 / 03</span>
                    </div>

                </aside>

                <!-- Content -->
                <div class="intro-main reveal reveal-delay-1">

                    <div class="intro-topline">
                        <div class="intro-eyebrow">
                            <span class="intro-eyebrow-dot"></span>
                            <span>نگاه ما به صنعت</span>
                        </div>

                        <span class="intro-topline-en">OUR PHILOSOPHY</span>
                    </div>

                    <h2 class="intro-title">
                        {{ $content?->industry_title ?: 'هر ماده، وقتی ارزشمند است که راه‌حلی بسازد.' }}
                    </h2>

                    <div class="intro-description-wrap">
                        <span class="intro-description-line"></span>

                        <p class="intro-description">
                            {{ $content?->industry_text ?: 'در صنعت پلیمر، انتخاب درست تنها به مشخصات یک ماده محدود نمی‌شود. شناخت کاربرد، توجه به کیفیت و درک نیاز واقعی مشتری، بخشی از مسیری است که به یک نتیجه قابل اتکا می‌رسد.' }}
                        </p>
                    </div>

                    <!-- Values -->
                    <div class="intro-values">

                        <div class="intro-value">
                            <div class="intro-value-top">
                                <span class="intro-value-number">01</span>
                                <span class="intro-value-icon">↗</span>
                            </div>

                            <div class="intro-value-content">
                                <span>رویکرد</span>
                                <strong>نگاه مهندسی</strong>
                            </div>
                        </div>

                        <div class="intro-value">
                            <div class="intro-value-top">
                                <span class="intro-value-number">02</span>
                                <span class="intro-value-icon">◇</span>
                            </div>

                            <div class="intro-value-content">
                                <span>تمرکز</span>
                                <strong>کیفیت پایدار</strong>
                            </div>
                        </div>

                        <div class="intro-value">
                            <div class="intro-value-top">
                                <span class="intro-value-number">03</span>
                                <span class="intro-value-icon">＋</span>
                            </div>

                            <div class="intro-value-content">
                                <span>همراهی</span>
                                <strong>ارتباط حرفه‌ای</strong>
                            </div>
                        </div>

                    </div>

                    <div class="intro-footer">
                        <span class="intro-footer-line"></span>
                        <span>تخصص، کیفیت و همراهی در مسیر انتخاب درست</span>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Products -->
    <section class="home-products-section" id="products">

        <!-- Background Effects -->
        <div class="home-products-background">
            <div class="home-products-glow home-products-glow-one"></div>
            <div class="home-products-glow home-products-glow-two"></div>
            <div class="home-products-grid-pattern"></div>
            <div class="home-products-decoration">KPA</div>
        </div>

        <div class="container-main">

            <!-- Section Header -->
            <div class="home-products-heading reveal">

                <div class="home-products-heading-main">

                    <span class="home-products-eyebrow">
                        <span class="home-products-eyebrow-line"></span>
                        محصولات کهن پلیمر
                    </span>

                    <h2 class="home-products-title">
                        کیفیتی فراتر از
                        <span>انتظار صنعت.</span>
                    </h2>

                </div>

                <div class="home-products-heading-side">
                    <p>
                        با مجموعه‌ای از محصولات و مواد اولیه پلیمری کهن پلیمر آشنا شوید؛
                        انتخابی مطمئن برای نیازهای متنوع صنایع.
                    </p>
                </div>

            </div>

            <!-- Products Grid -->
            <div class="home-products-grid">

                @forelse ($products as $product)

                    <article class="home-product-card reveal">

                        <div class="home-product-image">

                            <img
                                src="{{ $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : asset('img/product-1.jpg') }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >

                            <span class="home-product-number">
                                <strong>{{ sprintf('%02d', $products->count()) }}</strong>
                            </span>

                            <div class="home-product-image-shade"></div>

                        </div>

                        <div class="home-product-content">

                            <span class="home-product-category">
                                <span></span>
                                محصول پلیمری
                            </span>

                            <h3>{{ $product->name }}</h3>

                            <p>
                                {{ $product->description ?: 'برای آشنایی بیشتر با ویژگی‌ها و کاربردهای این محصول با ما در ارتباط باشید.' }}
                            </p>

                            <a href="#contact" class="home-product-link">
                                <span>اطلاعات بیشتر</span>

                                <span class="home-product-link-arrow">
                                    ←
                                </span>
                            </a>

                        </div>

                    </article>

                @empty

                    <div class="home-products-empty">
                        <span>◇</span>
                        <strong>هنوز محصولی منتشر نشده است.</strong>
                        <p>به‌زودی محصولات مجموعه در این بخش نمایش داده می‌شوند.</p>
                    </div>

                @endforelse

            </div>

            <!-- Section Footer -->
            <div class="home-products-footer reveal">

                <div class="home-products-count">
                    <strong>{{ sprintf('%02d', min($publishedProducts->count(), 4)) }}</strong>
                    <span>محصول منتخب</span>
                </div>

                <a href="/products" class="home-products-more">
                    <span>مشاهده همه محصولات</span>

                    <span class="home-products-more-arrow">
                        ←
                    </span>
                </a>

            </div>

        </div>
    </section>

    <!-- Brand statement -->
    <section class="statement-section">
        <div class="container-main">
            <div class="statement-frame">

                <div class="statement-decoration" aria-hidden="true">
                    <span class="statement-number">KPA</span>
                    <span class="statement-circle statement-circle-one"></span>
                    <span class="statement-circle statement-circle-two"></span>
                    <span class="statement-light"></span>
                </div>

                <div class="statement-inner">

                    <div class="statement-content reveal">
                        <div class="statement-label">
                            <span class="statement-label-line"></span>
                            <span class="eyebrow">نگاه متفاوت</span>
                            <span class="statement-label-en">OUR APPROACH</span>
                        </div>

                        <h2 class="statement-title">
                            {{ $content?->difference_title ?: 'محصول خوب، نتیجه تصمیم‌های درست در تمام مسیر است.' }}
                        </h2>
                    </div>

                    <div class="statement-side reveal reveal-delay-1">
                        <span class="statement-side-index">A DIFFERENT PERSPECTIVE</span>

                        <p>
                            {{ $content?->difference_text ?: 'انتخاب مواد، شناخت کاربرد و توجه به جزئیات، هرکدام در کیفیت نتیجه نهایی نقش دارند. ما به ارزش تصمیم‌گیری آگاهانه در این مسیر باور داریم.' }}
                        </p>

                        <a href="#about" class="statement-link">
                            <span>بیشتر درباره ما</span>
                            <span class="statement-link-arrow">←</span>
                        </a>
                    </div>

                </div>

                <div class="statement-bottom-line">
                    <span>KOHAN POLYMER ALBORZ</span>
                    <span>ENGINEERING THE FUTURE</span>
                </div>

            </div>
        </div>
    </section>

    <!-- About -->
    <section class="about-section" id="about">
        <div class="container-main">
            <div class="about-grid">

                <div class="about-visual reveal">
                    <div class="about-image overflow-hidden">
                        <img
                            src="{{ $content?->logo ? \Illuminate\Support\Facades\Storage::url($content->logo) : asset('img/kohan-logo.png') }}"
                            alt="لوگوی کهن پلیمر البرز"
                            loading="lazy"
                            class="block w-full object-cover"
                        >
                    </div>

                    <div class="about-stamp">
                        <strong>کهن پلیمر</strong>
                        <span>تجربه، دقت و نگاهی رو به آینده</span>
                    </div>
                </div>

                <div class="about-copy reveal reveal-delay-1">
                    <span class="eyebrow">درباره کهن پلیمر البرز</span>

                    <h2 class="section-title">
                        {{ $content?->about_title ?: 'شناخت نیاز، آغاز یک همکاری مؤثر.' }}
                    </h2>

                    <p>
                        {{ $content?->about_text ?: 'کهن پلیمر البرز با تمرکز بر حوزه مواد و محصولات پلیمری، به دنبال ایجاد ارتباطی حرفه‌ای میان نیازهای صنعتی و راهکارهای مناسب است. هدف ما ارائه اطلاعات روشن، پاسخ‌گویی دقیق و فراهم کردن زمینه‌ای برای همکاری بلندمدت است.' }}
                    </p>

                    <div class="about-points">
                        <div class="about-point">
                            <div class="about-point-icon">↗</div>
                            <div>
                                <strong>پاسخ‌گویی هدفمند</strong>
                                <span>توجه به نیاز واقعی هر درخواست</span>
                            </div>
                        </div>

                        <div class="about-point">
                            <div class="about-point-icon">◇</div>
                            <div>
                                <strong>رویکرد تخصصی</strong>
                                <span>تمرکز بر اطلاعات و کاربرد محصول</span>
                            </div>
                        </div>

                        <div class="about-point">
                            <div class="about-point-icon">◎</div>
                            <div>
                                <strong>ارتباط شفاف</strong>
                                <span>مسیر ارتباطی روشن با مشتریان</span>
                            </div>
                        </div>

                        <div class="about-point">
                            <div class="about-point-icon">⌁</div>
                            <div>
                                <strong>نگاه بلندمدت</strong>
                                <span>توجه به کیفیت رابطه کاری</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Articles -->
    <section class="articles-section" id="articles">

        <!-- Animated Background -->
        <div class="articles-background">
            <div class="articles-glow articles-glow-one"></div>
            <div class="articles-glow articles-glow-two"></div>

            <svg class="articles-waves" viewBox="0 0 1440 600" preserveAspectRatio="none">
                <path class="wave wave-one"
                    d="M-100 280 C150 100 300 440 560 260 S950 100 1200 260 S1500 400 1600 180" />

                <path class="wave wave-two"
                    d="M-100 350 C180 180 350 500 620 330 S980 170 1220 330 S1500 470 1600 250" />

                <path class="wave wave-three"
                    d="M-100 420 C180 250 370 560 650 400 S1000 240 1250 400 S1500 520 1600 340" />
            </svg>

            <div class="articles-tech-line"></div>
        </div>

        <div class="container-main articles-container">

            <div class="articles-heading reveal">
                <div class="articles-heading-content">
                    <span class="articles-eyebrow">
                        KNOWLEDGE & INNOVATION
                    </span>

                    <h2>دانش، تجربه و نوآوری</h2>

                    <p>
                        تازه‌ترین مطالب و دیدگاه‌های تخصصی درباره صنعت پلیمر
                    </p>
                </div>

                <a href="{{ route('blog.index') }}" class="articles-all-link">
                    مشاهده همه مقالات

                    <span>
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            width="18"
                            height="18"
                        >
                            <path d="M19 12H5"/>
                            <path d="m12 19-7-7 7-7"/>
                        </svg>
                    </span>
                </a>
            </div>

            <div class="articles-grid">

                @forelse ($publishedBlogPosts->take(4) as $post)

                    <article class="article-card reveal">

                        <a href="#articles" class="article-image">
                            <img
                                src="{{ $post->image ? \Illuminate\Support\Facades\Storage::url($post->image) : asset('img/product-1.jpg') }}"
                                alt="{{ $post->title }}"
                                loading="lazy"
                            >

                            <span class="article-date">
                                {{ $post->published_at ? $post->published_at->format('Y/m/d') : 'مقاله تخصصی' }}
                            </span>
                        </a>

                        <div class="article-content">

                            <span class="article-category">
                                دانش و دیدگاه
                            </span>

                            <h3>
                                <a href="#articles">{{ $post->title }}</a>
                            </h3>

                            <p>
                                {{ $post->summary ?: \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 100) }}
                            </p>

                            <a href="#articles" class="article-read">
                                ادامه مطلب
                                <span>←</span>
                            </a>

                        </div>

                    </article>

                @empty

                    <div class="articles-empty">
                        در حال حاضر مقاله‌ای برای نمایش منتشر نشده است.
                    </div>

                @endforelse

            </div>

        </div>
    </section>
    
    <!-- Contact -->
    <section class="contact-section" id="contact">

        <div class="contact-background">
            <div class="contact-glow contact-glow-one"></div>
            <div class="contact-glow contact-glow-two"></div>

            <svg class="contact-waves" viewBox="0 0 1440 500" preserveAspectRatio="none">
                <path d="M-100 320 C180 180 350 440 620 280 S1050 150 1500 300" />
                <path d="M-100 370 C180 230 350 490 620 330 S1050 200 1500 350" />
            </svg>
        </div>

        <div class="container-main">

            <div class="contact-panel reveal">

                <!-- Contact Introduction -->
                <div class="contact-copy">

                    <span class="contact-eyebrow">
                        شروع یک همکاری
                    </span>

                    <h2>
                        درباره نیازتان
                        <br>
                        با ما گفت‌وگو کنید.
                    </h2>

                    <p>
                        برای دریافت اطلاعات محصولات، بررسی نیاز پروژه،
                        مشاوره فنی یا شروع همکاری، درخواست خود را ثبت کنید.
                        کارشناسان ما در اولین فرصت با شما تماس خواهند گرفت.
                    </p>

                    <div class="contact-benefits">

                        <div class="contact-benefit">
                            <span class="contact-benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 8V12L15 14" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/>
                                </svg>
                            </span>

                            <span>پیگیری درخواست در اولین فرصت</span>
                        </div>

                        <div class="contact-benefit">
                            <span class="contact-benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <span>ارتباط مستقیم با مجموعه</span>
                        </div>

                    </div>

                    @if ($primaryPhone)
                        <a
                            href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}"
                            class="contact-phone-link"
                        >
                            <span class="contact-phone-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M6.5 3.5H9L11 8.5L8.5 10C9.6 12.3 11.7 14.4 14 15.5L15.5 13L20.5 15V17.5C20.5 18.6 19.6 19.5 18.5 19.5C10.8 19.1 4.9 13.2 4.5 5.5C4.5 4.4 5.4 3.5 6.5 3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <span>
                                <small>تماس مستقیم با ما</small>
                                <strong>{{ $primaryPhone }}</strong>
                            </span>
                        </a>
                    @endif

                </div>

                <!-- Contact Form -->
                <div class="contact-form-wrapper">

                    <div class="contact-form-heading">
                        <div>
                            <span>فرم ارتباط با ما</span>
                            <h3>ثبت درخواست</h3>
                        </div>

                        <span class="contact-form-number">01</span>
                    </div>

                    <form
                        action="{{ route('inquiries.store') }}"
                        method="POST"
                        class="contact-form"
                    >
                        @csrf

                        <div class="contact-form-grid">

                            <!-- Full Name -->
                            <div class="contact-field">
                                <label for="contact-name">
                                    نام و نام خانوادگی
                                    <span>*</span>
                                </label>

                                <input
                                    type="text"
                                    id="contact-name"
                                    name="fullName"
                                    value="{{ old('fullName') }}"
                                    placeholder="نام و نام خانوادگی خود را وارد کنید"
                                    required
                                    maxlength="150"
                                    autocomplete="name"
                                >

                                @error('name')
                                    <span class="contact-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="contact-field">
                                <label for="contact-phone">
                                    شماره تماس
                                    <span>*</span>
                                </label>

                                <input
                                    type="tel"
                                    id="contact-phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="مثلاً 09123456789"
                                    required
                                    maxlength="20"
                                    inputmode="tel"
                                    autocomplete="tel"
                                >

                                @error('phone')
                                    <span class="contact-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div class="contact-field contact-field-full">
                                <label for="contact-subject">
                                    موضوع درخواست
                                    <span>*</span>
                                </label>

                                 <select
                                    id="contact-subject"
                                    name="subject"
                                    required
                                >
                                    <option value="" disabled @selected(!old('subject'))>
                                        موضوع درخواست خود را انتخاب کنید
                                    </option>

                                    <option value="product" @selected(old('subject') === 'product')>
                                        استعلام محصولات
                                    </option>

                                    <option value="technical" @selected(old('subject') === 'technical')>
                                        مشاوره فنی
                                    </option>

                                    <option value="cooperation" @selected(old('subject') === 'cooperation')>
                                        درخواست همکاری
                                    </option>

                                    <option value="other" @selected(old('subject') === 'other')>
                                        سایر
                                    </option>
                                </select>

                                @error('subject')
                                    <span class="contact-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="contact-field contact-field-full">
                                <label for="contact-message">
                                    توضیحات درخواست
                                </label>

                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows="4"
                                    maxlength="3000"
                                    placeholder="لطفاً درخواست یا توضیحات تکمیلی خود را بنویسید..."
                                >{{ old('message') }}</textarea>

                                @error('message')
                                    <span class="contact-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="contact-submit">
                            <span>ثبت درخواست</span>

                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </section>



@endsection