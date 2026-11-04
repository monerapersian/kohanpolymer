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
            <div class="intro-grid">

                <aside class="intro-aside reveal">
                    <div>
                        <span class="eyebrow">نگاه ما به صنعت</span>
                        <span class="intro-number">KPA</span>
                        <p class="intro-number-label">فراتر از یک تأمین‌کننده</p>
                    </div>
                </aside>

                <div class="intro-copy reveal reveal-delay-1">
                    <h2 class="section-title">
                        {{ $content?->industry_title ?: 'هر ماده، وقتی ارزشمند است که راه‌حلی بسازد.' }}
                    </h2>

                    <p>
                        {{ $content?->industry_text ?: 'در صنعت پلیمر، انتخاب درست تنها به مشخصات یک ماده محدود نمی‌شود. شناخت کاربرد، توجه به کیفیت و درک نیاز واقعی مشتری، بخشی از مسیری است که به یک نتیجه قابل اتکا می‌رسد.' }}
                    </p>

                    <div class="intro-values">
                        <div class="value-item">
                            <span>01 / رویکرد</span>
                            <strong>نگاه مهندسی</strong>
                        </div>

                        <div class="value-item">
                            <span>02 / تمرکز</span>
                            <strong>کیفیت پایدار</strong>
                        </div>

                        <div class="value-item">
                            <span>03 / همراهی</span>
                            <strong>ارتباط حرفه‌ای</strong>
                        </div>                        
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="products-section" id="products">
        <div class="container-main">

            <div class="section-heading reveal">
                <div>
                    <span class="eyebrow">حوزه محصولات</span>
                    <h2 class="section-title">
                        مواد و راهکارها،
                متناسب با نیاز صنعت.
                    </h2>
                </div>

                <div>
                    <p class="section-subtitle">
                        آشنایی با محصولات منتشرشده کهن پلیمر البرز
                        و زمینه‌های کاربردی آن‌ها.
                    </p>

                    <a href="#contact" class="text-link">
                        دریافت مشاوره
                        <span class="btn-arrow">←</span>
                    </a>
                </div>
            </div>

            <div class="products-grid">
                @forelse ($publishedProducts as $index => $product)
                    <article class="product-card {{ $loop->first ? 'featured' : '' }} reveal {{ $loop->iteration === 2 ? 'reveal-delay-1' : ($loop->iteration === 3 ? 'reveal-delay-2' : '') }}">
                        <div class="product-visual">
                            <img
                                src="{{ $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : asset('img/product-1.jpg') }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >

                            <span class="product-index">
                                {{ sprintf('%02d', $loop->iteration) }}
                            </span>
                        </div>

                        <div class="product-content">
                            <span class="product-category">
                                {{ $loop->first ? 'گروه محصول / منتخب' : 'گروه محصول' }}
                            </span>

                            <h3>{{ $product->name }}</h3>

                            <p>
                                {{ $product->description ?: 'برای دریافت اطلاعات بیشتر درباره این محصول با مجموعه کهن پلیمر البرز در ارتباط باشید.' }}
                            </p>

                            <div class="product-footer">
                                <small>دریافت اطلاعات محصول</small>

                                <a
                                    href="#contact"
                                    class="product-arrow"
                                    aria-label="اطلاعات بیشتر درباره {{ $product->name }}"
                                >↙</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-10 text-center text-[#666666]">
                        در حال حاضر محصولی برای نمایش منتشر نشده است.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- Brand statement -->
    <section class="statement-section">
        <div class="container-main">
            <div class="statement-inner">

                <div class="reveal">
                    <span class="eyebrow text-[#baadad]">نگاه متفاوت</span>

                    <h2 class="statement-title mt-6">
                        {{ $content?->difference_title ?: 'محصول خوب، نتیجه تصمیم‌های درست در تمام مسیر است.' }}
                    </h2>
                </div>

                <div class="statement-side reveal reveal-delay-1">
                    <p>
                        {{ $content?->difference_text ?: 'انتخاب مواد، شناخت کاربرد و توجه به جزئیات، هرکدام در کیفیت نتیجه نهایی نقش دارند. ما به ارزش تصمیم‌گیری آگاهانه در این مسیر باور داریم.' }}
                    </p>

                    <a href="#about" class="btn-primary">
                        بیشتر درباره ما
                        <span class="btn-arrow">←</span>
                    </a>
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

                    <a href="#contact" class="text-link mt-8">
                        شروع گفت‌وگو
                        <span class="btn-arrow">←</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Articles -->
    <section class="articles-section" id="articles">
        <div class="container-main">

            <div class="section-heading reveal">
                <div>
                    <span class="eyebrow">دانش و دیدگاه</span>
                    <h2 class="section-title">
                        مقالات
                    </h2>
                </div>

                <div>
                    <p class="section-subtitle">
                        نگاهی عمیق‌تر به دنیای پلیمر.
                    </p>

                    <a href="#articles" class="text-link">
                        همه مقالات
                        <span class="btn-arrow">←</span>
                    </a>
                </div>
            </div>

            <div class="articles-grid">
                @forelse ($publishedBlogPosts as $post)
                    <article class="article-card reveal {{ $loop->iteration === 2 ? 'reveal-delay-1' : ($loop->iteration === 3 ? 'reveal-delay-2' : '') }}">
                        <a href="#articles" class="block">
                            <div class="article-image">
                                <img
                                    src="{{ $post->image ? \Illuminate\Support\Facades\Storage::url($post->image) : asset('img/product-1.jpg') }}"
                                    alt="{{ $post->title }}"
                                    loading="lazy"
                                >

                                <span class="article-date">
                                    {{ $post->published_at ? $post->published_at->format('Y/m/d') : 'مقاله تخصصی' }}
                                </span>
                            </div>
                        </a>

                        <div class="article-content">
                            <span class="article-category">دانش و دیدگاه</span>

                            <a href="#articles">
                                <h3>{{ $post->title }}</h3>
                            </a>

                            <p>
                                {{ $post->summary ?: \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 150) }}
                            </p>

                            <a href="#articles" class="article-read">
                                ادامه مطلب <span>←</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-10 text-center text-[#666666]">
                        در حال حاضر مقاله‌ای برای نمایش منتشر نشده است.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- Contact -->
    <section class="contact-section" id="contact">
        <div class="container-main">
            <div class="contact-panel reveal">

                <div class="contact-copy">
                    <span class="eyebrow text-[#eadada]">شروع یک همکاری</span>

                    <h2>
                        درباره نیازتان<br>
                        با ما گفت‌وگو کنید.
                    </h2>

                    <p>
                        برای دریافت اطلاعات محصولات، بررسی نیاز پروژه
                        یا شروع همکاری، با مجموعه کهن پلیمر البرز
                        در ارتباط باشید.
                    </p>
                </div>

                @if ($primaryPhone)
                    <a
                        href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}"
                        class="btn-primary"
                    >
                        ارتباط با مجموعه
                        <span class="btn-arrow">←</span>
                    </a>
                @else
                    <a href="#contact" class="btn-primary">
                        ارتباط با مجموعه
                        <span class="btn-arrow">←</span>
                    </a>
                @endif

            </div>
        </div>
    </section>

    <script>
        (() => {
            const header = document.getElementById('siteHeader');
            const menuToggle = document.getElementById('menuToggle');
            const mobileNav = document.getElementById('mobileNav');

            if (header) {
                const updateHeader = () => {
                    header.classList.toggle('scrolled', window.scrollY > 30);
                };

                updateHeader();
                window.addEventListener('scroll', updateHeader, { passive: true });
            }

            if (menuToggle && mobileNav) {
                const mobileLinks = mobileNav.querySelectorAll('a');
                let menuCloseTimer;

                const openMenu = () => {
                    clearTimeout(menuCloseTimer);

                    mobileNav.inert = false;
                    mobileNav.classList.add('open');
                    menuToggle.classList.add('active');

                    menuToggle.setAttribute('aria-expanded', 'true');
                    menuToggle.setAttribute('aria-label', 'بستن منو');
                };

                const closeMenu = () => {
                    mobileNav.classList.remove('open');
                    menuToggle.classList.remove('active');

                    menuToggle.setAttribute('aria-expanded', 'false');
                    menuToggle.setAttribute('aria-label', 'باز کردن منو');

                    menuCloseTimer = setTimeout(() => {
                        if (!mobileNav.classList.contains('open')) {
                            mobileNav.inert = true;
                        }
                    }, 260);
                };

                menuToggle.addEventListener('click', () => {
                    const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';

                    if (isOpen) {
                        closeMenu();
                    } else {
                        openMenu();
                    }
                });

                mobileLinks.forEach(link => {
                    link.addEventListener('click', closeMenu);
                });

                document.addEventListener('click', event => {
                    if (
                        menuToggle.getAttribute('aria-expanded') === 'true' &&
                        !mobileNav.contains(event.target) &&
                        !menuToggle.contains(event.target)
                    ) {
                        closeMenu();
                    }
                });

                document.addEventListener('keydown', event => {
                    if (
                        event.key === 'Escape' &&
                        menuToggle.getAttribute('aria-expanded') === 'true'
                    ) {
                        closeMenu();
                        menuToggle.focus();
                    }
                });
            }

            const revealElements = document.querySelectorAll('.reveal');

            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -35px 0px'
                });

                revealElements.forEach(element => {
                    revealObserver.observe(element);
                });
            } else {
                revealElements.forEach(element => {
                    element.classList.add('is-visible');
                });
            }
        })();
    </script>

@endsection