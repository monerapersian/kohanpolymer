@php
    $siteContent = $content ?? null;

    $footerPhones = is_array($siteContent?->phones)
        ? array_filter($siteContent->phones)
        : [];

    if (empty($footerPhones) && !empty($siteContent?->phone)) {
        $footerPhones = [$siteContent->phone];
    }

    $footerActivityAreas = is_array($siteContent?->activity_areas)
        ? array_filter($siteContent->activity_areas)
        : [];
@endphp

<style>
    /* ================================
       Footer - Technology Particle Effect
    ================================ */

    .site-footer {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 80px 0 0;
        background:
            radial-gradient(
                ellipse at 80% 15%,
                rgba(141, 102, 102, .2),
                transparent 42%
            ),
            linear-gradient(
                135deg,
                #422124 0%,
                #261b1b 50%,
                #422124 100%
            );
        color: #f4eeee;
    }

    /* Background Effects */

    .site-footer .footer-effects {
        position: absolute;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    /* Particle Network */

    .site-footer .footer-grid-pattern {
        position: absolute;
        inset: 0;
        opacity: .15;
        background-image:
            radial-gradient(circle, rgba(186,173,173,.8) 1.3px, transparent 1.8px),
            radial-gradient(circle, rgba(141,102,102,.7) 1px, transparent 1.6px),
            linear-gradient(115deg, transparent 49.8%, rgba(186,173,173,.12) 50%, transparent 50.2%),
            linear-gradient(25deg, transparent 49.8%, rgba(186,173,173,.09) 50%, transparent 50.2%);
        background-size:
            75px 75px,
            115px 115px,
            150px 150px,
            190px 190px;
        background-position:
            0 0,
            35px 25px,
            0 0,
            40px 20px;
        mask-image: linear-gradient(
            to bottom,
            transparent 0%,
            black 15%,
            black 85%,
            transparent 100%
        );
        animation: footerNetworkMove 35s linear infinite;
    }

    /* Glowing Nodes */

    .site-footer .footer-orb {
        position: absolute;
        display: block;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(0);
    }

    .site-footer .footer-orb-one {
        top: 12%;
        right: 12%;
        width: 9px;
        height: 9px;
        background: #baadad;
        box-shadow:
            0 0 8px 3px rgba(186,173,173,.65),
            0 0 25px 8px rgba(141,102,102,.35);
        animation: footerNodeOne 7s ease-in-out infinite;
    }

    .site-footer .footer-orb-two {
        top: 55%;
        left: 12%;
        width: 7px;
        height: 7px;
        background: #baadad;
        box-shadow:
            0 0 8px 3px rgba(186,173,173,.6),
            0 0 24px 8px rgba(141,102,102,.3);
        animation: footerNodeTwo 9s ease-in-out infinite;
    }

    .site-footer .footer-orb-three {
        top: 30%;
        left: 48%;
        width: 6px;
        height: 6px;
        background: #d4c3c3;
        box-shadow:
            0 0 8px 3px rgba(186,173,173,.7),
            0 0 22px 7px rgba(141,102,102,.3);
        animation: footerNodeThree 8s ease-in-out infinite;
    }

    /* Connecting Light Lines */

    .site-footer .footer-glow-line {
        position: absolute;
        top: 0;
        right: 10%;
        width: 80%;
        height: 2px;
        background: linear-gradient(
            90deg,
            transparent,
            #592d30,
            #baadad,
            #592d30,
            transparent
        );
        box-shadow: 0 0 15px rgba(186,173,173,.45);
        animation: footerLineGlow 5s ease-in-out infinite alternate;
    }

    .site-footer .footer-effects::before,
    .site-footer .footer-effects::after {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        border: 1px solid rgba(186,173,173,.12);
        border-radius: 50%;
        pointer-events: none;
    }

    .site-footer .footer-effects::before {
        top: -190px;
        right: 8%;
        box-shadow:
            0 0 0 28px rgba(186,173,173,.025),
            0 0 0 58px rgba(186,173,173,.02);
        animation: footerRingOne 18s linear infinite;
    }

    .site-footer .footer-effects::after {
        bottom: -230px;
        left: 12%;
        width: 380px;
        height: 380px;
        border-color: rgba(141,102,102,.18);
        box-shadow:
            0 0 0 35px rgba(141,102,102,.025),
            0 0 0 70px rgba(141,102,102,.02);
        animation: footerRingTwo 22s linear infinite;
    }

    /* Main Footer */

    .site-footer .footer-main {
        position: relative;
        padding-bottom: 60px;
    }

    .site-footer .footer-grid {
        display: grid;
        grid-template-columns: 1.35fr .8fr .9fr 1fr;
        gap: 42px;
    }

    .site-footer .footer-brand-column,
    .site-footer .footer-column {
        min-width: 0;
    }

    /* Brand */

    .site-footer .footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 13px;
        text-decoration: none;
    }

    .site-footer .footer-brand .brand-symbol {
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        padding: 5px;
        border-radius: 14px;
        object-fit: contain;
        box-shadow: 0 6px 20px rgba(0,0,0,.2);
    }

    .site-footer .footer-brand .brand-name {
        color: #fff;
        font-size: 18px;
    }

    .site-footer .footer-brand .brand-caption {
        margin-top: 4px;
        color: #baadad;
        font-size: 11px;
    }

    .site-footer .footer-description {
        max-width: 350px;
        margin-top: 24px;
        color: #c9bdbd;
        font-size: 13px;
        line-height: 2.3;
        text-align: justify;
    }

    .site-footer .footer-brand-line {
        width: 58px;
        height: 2px;
        margin-top: 21px;
        border-radius: 10px;
        background: linear-gradient(90deg, #baadad, #674040);
    }

    .site-footer .footer-brand-caption {
        display: block;
        margin-top: 12px;
        color: rgba(255,255,255,.4);
        font-size: 8px;
        letter-spacing: 2px;
        direction: ltr;
    }

    /* Headings */

    .site-footer .footer-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 24px;
        color: #fff;
        font-size: 15px;
    }

    .site-footer .footer-heading::after {
        content: "";
        flex: 1;
        height: 1px;
        margin-right: 5px;
        background: linear-gradient(
            90deg,
            rgba(186,173,173,.4),
            transparent
        );
    }

    .site-footer .footer-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border: 1px solid rgba(186,173,173,.25);
        border-radius: 10px;
        background: rgba(141,102,102,.16);
        color: #baadad;
        transition: .3s ease;
    }

    .site-footer .footer-heading-icon svg {
        width: 17px;
        height: 17px;
    }

    .site-footer .footer-column:hover .footer-heading-icon {
        background: rgba(141,102,102,.35);
        border-color: rgba(186,173,173,.5);
        box-shadow: 0 0 15px rgba(141,102,102,.2);
        transform: translateY(-3px);
    }

    /* Links */

    .site-footer .footer-links {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .site-footer .footer-links a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: fit-content;
        max-width: 100%;
        color: #c9bdbd;
        font-size: 12px;
        text-decoration: none;
        transition: color .25s ease, padding .25s ease;
    }

    .site-footer .footer-links a svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
        color: #baadad;
        opacity: .55;
        transition: .25s ease;
    }

    .site-footer .footer-links a:hover {
        color: #fff;
        padding-right: 4px;
    }

    .site-footer .footer-links a:hover svg {
        opacity: 1;
        transform: translateX(-4px);
    }

    /* Contact */

    .site-footer .footer-contact {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .site-footer .footer-contact p {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0;
        color: #c9bdbd;
        font-size: 12px;
        line-height: 2;
        overflow-wrap: anywhere;
    }

    .site-footer .footer-contact-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: rgba(141,102,102,.2);
        color: #baadad;
        transition: .3s ease;
    }

    .site-footer .footer-contact-icon svg {
        width: 15px;
        height: 15px;
    }

    .site-footer .footer-contact p:hover .footer-contact-icon {
        background: #674040;
        color: #fff;
        box-shadow: 0 0 12px rgba(141,102,102,.3);
    }

    .site-footer .footer-contact a {
        color: #c9bdbd;
        text-decoration: none;
        transition: color .25s ease;
    }

    .site-footer .footer-contact a:hover {
        color: #fff;
    }

    /* Footer Bottom */

    .site-footer .footer-bottom {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        min-height: 75px;
        border-top: 1px solid rgba(255,255,255,.12);
    }

    .site-footer .footer-bottom p {
        margin: 0;
        color: rgba(255,255,255,.55);
        font-size: 11px;
    }

    .site-footer .footer-back-top {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        border: 1px solid rgba(186,173,173,.25);
        border-radius: 30px;
        background: rgba(255,255,255,.05);
        color: #d7caca;
        font-size: 11px;
        text-decoration: none;
        transition: .3s ease;
    }

    .site-footer .footer-back-top svg {
        width: 17px;
        height: 17px;
        color: #baadad;
        transition: transform .3s ease;
    }

    .site-footer .footer-back-top:hover {
        border-color: rgba(186,173,173,.55);
        background: rgba(141,102,102,.2);
        color: #fff;
    }

    .site-footer .footer-back-top:hover svg {
        transform: translateY(-4px);
    }

    /* Animations */

    @keyframes footerNetworkMove {
        from {
            background-position:
                0 0,
                35px 25px,
                0 0,
                40px 20px;
        }
        to {
            background-position:
                75px 75px,
                -80px 140px,
                150px 150px,
                -150px 210px;
        }
    }

    @keyframes footerNodeOne {
        0%, 100% {
            transform: translate(0, 0);
            opacity: .65;
        }
        50% {
            transform: translate(-90px, 65px);
            opacity: 1;
        }
    }

    @keyframes footerNodeTwo {
        0%, 100% {
            transform: translate(0, 0);
            opacity: .6;
        }
        50% {
            transform: translate(100px, -45px);
            opacity: 1;
        }
    }

    @keyframes footerNodeThree {
        0%, 100% {
            transform: translate(0, 0);
            opacity: .6;
        }
        50% {
            transform: translate(55px, 75px);
            opacity: 1;
        }
    }

    @keyframes footerLineGlow {
        from {
            opacity: .45;
            filter: brightness(.8);
        }
        to {
            opacity: 1;
            filter: brightness(1.5);
        }
    }

    @keyframes footerRingOne {
        from {
            transform: rotate(0deg) scale(1);
        }
        to {
            transform: rotate(360deg) scale(1.08);
        }
    }

    @keyframes footerRingTwo {
        from {
            transform: rotate(360deg) scale(1);
        }
        to {
            transform: rotate(0deg) scale(1.06);
        }
    }

    /* Responsive */

    @media (max-width: 1024px) {
        .site-footer .footer-grid {
            grid-template-columns: 1.2fr 1fr;
            gap: 42px 35px;
        }

        .site-footer .footer-description {
            max-width: 420px;
        }
    }

    @media (max-width: 640px) {
        .site-footer {
            padding-top: 58px;
        }

        .site-footer .footer-main {
            padding-bottom: 42px;
        }

        .site-footer .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 38px 20px;
        }

        .site-footer .footer-brand-column {
            grid-column: 1 / -1;
        }

        .site-footer .footer-description {
            max-width: 100%;
            margin-top: 18px;
            font-size: 12px;
        }

        .site-footer .footer-heading {
            gap: 7px;
            margin-bottom: 19px;
            font-size: 13px;
        }

        .site-footer .footer-heading-icon {
            width: 29px;
            height: 29px;
            flex-basis: 29px;
        }

        .site-footer .footer-heading-icon svg {
            width: 15px;
            height: 15px;
        }

        .site-footer .footer-links {
            gap: 12px;
        }

        .site-footer .footer-links a,
        .site-footer .footer-contact p {
            font-size: 11px;
        }

        .site-footer .footer-bottom {
            min-height: 85px;
            flex-direction: column;
            justify-content: center;
            gap: 12px;
            padding: 18px 0;
            text-align: center;
        }

        .site-footer .footer-orb-three {
            left: 75%;
        }
    }

    @media (max-width: 380px) {
        .site-footer .footer-grid {
            grid-template-columns: 1fr;
        }

        .site-footer .footer-brand-column {
            grid-column: auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .site-footer .footer-effects *,
        .site-footer .footer-effects::before,
        .site-footer .footer-effects::after {
            animation: none !important;
        }

        .site-footer *,
        .site-footer *::before,
        .site-footer *::after {
            transition: none !important;
        }
    }
</style>

<!-- Footer -->
<footer class="site-footer">
    <!-- Background Effects -->
    <div class="footer-effects" aria-hidden="true">
        <!-- <span class="footer-orb footer-orb-one"></span>
        <span class="footer-orb footer-orb-two"></span>
        <span class="footer-orb footer-orb-three"></span> -->
        <div class="footer-grid-pattern"></div>
        <div class="footer-glow-line"></div>
    </div>

    <div class="container-main relative z-10">

        <!-- Footer Main -->
        <div class="footer-main">

            <div class="footer-grid">

                <!-- Brand -->
                <div class="footer-brand-column">

                    <a href="{{ url('/') }}" class="brand footer-brand">
                        @if ($siteContent?->logo)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($siteContent->logo) }}"
                                alt="لوگوی کهن پلیمر البرز"
                                class="brand-symbol object-contain bg-white"
                                loading="lazy"
                            >
                        @else
                            <span class="brand-symbol">ک</span>
                        @endif

                        <span>
                            <span class="brand-name block">کهن پلیمر البرز</span>
                            <span class="brand-caption block">تامین کننده مواد اولیه و محصولات پلیمری</span>
                        </span>
                    </a>

                    <p class="footer-description">
                        {{ $siteContent?->about_text ?: 'همراهی در مسیر انتخاب مواد و راهکارهای پلیمری، با رویکردی حرفه‌ای، نگاهی دقیق و توجه به نیاز صنعت.' }}
                    </p>

                    <div class="footer-brand-line"></div>

                    <span class="footer-brand-caption">
                        ENGINEERING THE FUTURE OF POLYMERS
                    </span>

                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                        </span>
                        دسترسی سریع
                    </h3>

                    <nav class="footer-links">
                        <a href="{{ url('/') }}#home">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7"/>
                            </svg>
                            <span>صفحه اصلی</span>
                        </a>

                        <a href="{{ url('/') }}#about">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7"/>
                            </svg>
                            <span>درباره ما</span>
                        </a>

                        <a href="{{ url('/') }}#products">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7"/>
                            </svg>
                            <span>محصولات</span>
                        </a>

                        <a href="{{ url('/') }}#articles">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7"/>
                            </svg>
                            <span>مقالات</span>
                        </a>

                        <a href="{{ url('/') }}#contact">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7"/>
                            </svg>
                            <span>ارتباط با ما</span>
                        </a>
                    </nav>
                </div>

                <!-- Activity Areas -->
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="5" width="7" height="7" rx="1"/>
                                <rect x="13" y="5" width="7" height="7" rx="1"/>
                                <rect x="4" y="14" width="7" height="6" rx="1"/>
                                <rect x="13" y="14" width="7" height="6" rx="1"/>
                            </svg>
                        </span>
                        حوزه‌های فعالیت
                    </h3>

                    <nav class="footer-links">
                        @forelse ($footerActivityAreas as $area)
                            <a href="{{ url('/') }}#products">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5m7 7-7-7 7-7"/>
                                </svg>
                                <span>{{ $area }}</span>
                            </a>
                        @empty
                            <a href="{{ url('/') }}#products">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5m7 7-7-7 7-7"/>
                                </svg>
                                <span>محصولات پلیمری</span>
                            </a>

                            <a href="{{ url('/') }}#products">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5m7 7-7-7 7-7"/>
                                </svg>
                                <span>راهکارهای صنعتی</span>
                            </a>
                        @endforelse
                    </nav>
                </div>

                <!-- Contact -->
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-heading-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 13a8 8 0 0 1 16 0"/>
                                <path d="M4 13v4a2 2 0 0 0 2 2h2v-6H4zM20 13v4a2 2 0 0 1-2 2h-2v-6h4z"/>
                                <path d="M12 19h2"/>
                            </svg>
                        </span>
                        ارتباط با ما
                    </h3>

                    <div class="footer-contact">

                        @forelse ($footerPhones as $phone)
                            <p>
                                <span class="footer-contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z"/>
                                    </svg>
                                </span>

                                <span>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                                        {{ $phone }}
                                    </a>
                                </span>
                            </p>
                        @empty
                            <p>
                                <span class="footer-contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z"/>
                                    </svg>
                                </span>
                                شماره تماس ثبت نشده است.
                            </p>
                        @endforelse

                        @if ($siteContent?->email)
                            <p>
                                <span class="footer-contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path d="m3 7 9 6 9-6"/>
                                    </svg>
                                </span>

                                <span>
                                    <a href="mailto:{{ $siteContent->email }}">
                                        {{ $siteContent->email }}
                                    </a>
                                </span>
                            </p>
                        @endif

                        @if ($siteContent?->instagram)
                            <p>
                                <span class="footer-contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                                        <circle cx="12" cy="12" r="4"/>
                                        <circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/>
                                    </svg>
                                </span>

                                <span>
                                    <a
                                        href="https://instagram.com/{{ ltrim($siteContent->instagram, '@') }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        {{ ltrim($siteContent->instagram, '@') }}
                                    </a>
                                </span>
                            </p>
                        @endif

                        @if ($siteContent?->whatsapp)
                            <p>
                                <span class="footer-contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5z"/>
                                        <path d="M9 9c.5 2 2 3.5 4 4l1-1 2 1-1 2c-4 0-7-3-7-7l2-1 1 2z"/>
                                    </svg>
                                </span>

                                <span>
                                    <a
                                        href="https://wa.me/{{ preg_replace('/\D+/', '', $siteContent->whatsapp) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        ارتباط از طریق واتساپ
                                    </a>
                                </span>
                            </p>
                        @endif

                    </div>
                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">

            <p>
                © تمامی حقوق این وب‌سایت محفوظ است.
            </p>

            <a href="{{ url('/') }}#home" class="footer-back-top">
                <span>بازگشت به ابتدای صفحه</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 19V5m-7 7 7-7 7 7"/>
                </svg>
            </a>

        </div>

    </div>
</footer>