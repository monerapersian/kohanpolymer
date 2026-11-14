<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#362727">

    <title>@yield('title', 'کهن پلیمر البرز | مهندسی آینده در صنعت پلیمر')</title>

    <meta
        name="description"
        content="@yield('description', 'کهن پلیمر البرز؛ تولید و عرضه محصولات پلیمری صنعتی با تمرکز بر کیفیت، دوام و عملکرد.')"
    >

    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/kohan-logo.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    @stack('styles')
    <style>
        :root {
            --wine: #674040;
            --dark: #362727;
            --mid: #8d6666;
            --gray: #baadad;
            --paper: #F4F0EE;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            margin: 0;
            font-family: 'Vazirmatn', sans-serif;
            background: var(--paper);
            color: var(--dark);
            overflow-x: hidden;
        }

        ::selection {
            background: var(--wine);
            color: white;
        }

        a, button {
            -webkit-tap-highlight-color: transparent;
        }

        .container-main {
            width: min(1380px, calc(100% - 64px));
            margin-inline: auto;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
        }

        .eyebrow::before {
            content: "";
            width: 28px;
            height: 1px;
            background: currentColor;
        }

        .section-title {
            font-size: clamp(30px, 4.5vw, 62px);
            line-height: 1.45;
            font-weight: 800;
            letter-spacing: -.045em;
        }

        .outline-text {
            color: transparent;
            -webkit-text-stroke: 1px rgba(244,240,238,.55);
        }

        /* Header */

        .site-header {
            position: fixed;
            z-index: 100;
            top: 16px;
            left: 0;
            right: 0;
            padding-inline: 24px;
            transition: top .35s ease;
        }

        .header-inner {
            max-width: 1380px;
            margin-inline: auto;
            min-height: 76px;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            border: 1px solid #382929;
            border-radius: 20px;
            color: white;
            background: #572b2ee8;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 12px 40px rgba(30,15,15,.12);
            transition: background .3s, box-shadow .3s;
        }

        .site-header.scrolled .header-inner {
            background: #572b2ee8;
            box-shadow: 0 15px 45px rgba(30,15,15,.22);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-symbol {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 13px;
            color: white;
            font-size: 19px;
            font-weight: 900;
            transform: rotate(-5deg);
            transition: transform .4s;
        }

        .brand:hover .brand-symbol {
            transform: rotate(5deg) scale(1.06);
        }

        .brand-name {
            font-weight: 800;
            font-size: 16px;
            line-height: 1.5;
        }

        .brand-caption {
            color: #d1bebe;
            font-size: 10px;
            font-weight: 400;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: clamp(16px, 2.4vw, 34px);
        }

        .desktop-nav a {
            position: relative;
            color: #e5dada;
            font-size: 13px;
            transition: color .25s;
        }

        .desktop-nav a::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -8px;
            width: 0;
            height: 2px;
            background: #baadad;
            transition: width .3s;
        }

        .desktop-nav a:hover {
            color: white;
        }

        .desktop-nav a:hover::after {
            width: 100%;
        }

        .header-cta {
            padding: 12px 17px;
            border-radius: 12px;
            background: var(--paper);
            color: var(--dark);
            font-size: 12px;
            font-weight: 800;
            transition: background .3s, transform .3s;
        }

        .header-cta:hover {
            background: white;
            transform: translateY(-2px);
        }

        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 13px;
            color: white;
        }

        .menu-toggle span,
        .menu-toggle span::before,
        .menu-toggle span::after {
            display: block;
            width: 19px;
            height: 2px;
            background: white;
            border-radius: 5px;
            transition: transform .3s, top .3s;
        }

        .menu-toggle span {
            position: relative;
        }

        .menu-toggle span::before,
        .menu-toggle span::after {
            content: "";
            position: absolute;
            right: 0;
        }

        .menu-toggle span::before {
            top: -6px;
        }

        .menu-toggle span::after {
            top: 6px;
        }

        .menu-toggle.active span {
            background: transparent;
        }

        .menu-toggle.active span::before {
            top: 0;
            transform: rotate(45deg);
        }

        .menu-toggle.active span::after {
            top: 0;
            transform: rotate(-45deg);
        }

        .mobile-menu {
            display: none;
            position: absolute;
            top: calc(100% + 9px);
            left: 24px;
            right: 24px;
            padding: 12px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 18px;
            background: rgba(54,39,39,.98);
            box-shadow: 0 20px 50px rgba(30,15,15,.25);
            opacity: 0;
            transform: translateY(-12px) scale(.98);
            transform-origin: top center;
            transition: opacity .25s, transform .25s;
            pointer-events: none;
        }

        .mobile-menu.open {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .mobile-menu a {
            display: block;
            padding: 14px 12px;
            border-radius: 10px;
            color: #f4f0ee;
            font-size: 14px;
        }

        .mobile-menu a:hover {
            background: rgba(255,255,255,.08);
        }

        /* Hero */
        .hero {
            position: relative;
            min-height: 780px;
            padding: 170px 0 65px;
            overflow: hidden;
            color: white;
            background: #362727;
            isolation: isolate;
        }

        /* Animated background layers */
        .hero::before,
        .hero::after,
        .hero-glow {
            content: "";
            position: absolute;
            z-index: 0;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(
                circle,
                rgba(87, 43, 46, 0.95) 0%,
                rgba(87, 43, 46, 0.75) 30%,
                rgba(87, 43, 46, 0.35) 55%,
                transparent 75%
            );
            will-change: transform;
        }

        .hero::before {
            top: -250px;
            right: -180px;
            animation: redGlowOne 10s ease-in-out infinite alternate;
        }

        .hero::after {
            bottom: -350px;
            left: -180px;
            opacity: 0.9;
            animation: redGlowTwo 13s ease-in-out infinite alternate;
        }

        .hero-glow {
            top: 15%;
            left: 30%;
            width: 750px;
            height: 750px;
            opacity: 0.85;
            animation: redGlowThree 15s ease-in-out infinite alternate;
        }

        /* Grid overlay */
        .hero-grid {
            position: absolute;
            z-index: 0;
            inset: 0;
            opacity: 0.12;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(244, 240, 238, 0.15) 1px, transparent 1px),
                linear-gradient(90deg, rgba(244, 240, 238, 0.15) 1px, transparent 1px);
            background-size: 65px 65px;
            mask-image: linear-gradient(to left, black, transparent 90%);
        }

        /* Keep content above background */
        .hero .container-main {
            position: relative;
            z-index: 1;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            align-items: center;
            gap: clamp(35px, 6vw, 100px);
        }

        .hero-copy {
            position: relative;
            z-index: 2;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #d7c3c3;
            font-size: 12px;
            margin-bottom: 25px;
        }

        .hero-kicker::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #baadad;
            box-shadow: 0 0 0 5px rgba(186, 173, 173, 0.13);
        }

        .hero-title {
            max-width: 700px;
            font-size: clamp(42px, 6.2vw, 84px);
            font-weight: 900;
            line-height: 1.38;
            letter-spacing: -0.055em;
        }

        .hero-title .accent {
            color: #baadad;
        }

        .hero-description {
            max-width: 480px;
            margin-top: 24px;
            color: #d4c7c7;
            font-size: 14px;
            line-height: 2.4;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 13px;
            margin-top: 32px;
        }

        .btn-primary {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 15px 21px;
            border-radius: 12px;
            background: var(--paper);
            color: var(--dark);
            font-size: 13px;
            font-weight: 800;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            background: white;
        }

        .btn-arrow {
            font-size: 19px;
            transition: transform 0.3s;
        }

        .btn-primary:hover .btn-arrow,
        .text-link:hover .btn-arrow {
            transform: translateX(-4px);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 13px;
            transition: background 0.3s;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        /* Hero image */
        .hero-visual {
            position: relative;
            min-width: 0;
            padding: 22px 0 40px;
        }

        .hero-image-frame {
            position: relative;
            height: clamp(330px, 39vw, 510px);
            overflow: hidden;
            border-radius: 180px 180px 18px 18px;
            background: #554141;
            transform: translateY(0);
            animation: heroFloat 7s ease-in-out infinite;
        }

        .hero-image-frame::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                transparent 45%,
                rgba(36, 24, 24, 0.58)
            );
            pointer-events: none;
        }

        .hero-image {
            width: 100%;
            height: 115%;
            object-fit: cover;
            object-position: center;
            filter: saturate(0.65) contrast(1.04);
            transition: transform 1s cubic-bezier(0.2, 0.7, 0.2, 1);
        }

        .hero-visual:hover .hero-image {
            transform: scale(1.06);
        }

        .hero-image-label {
            position: absolute;
            z-index: 2;
            right: 25px;
            bottom: 25px;
            left: 25px;
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 15px;
        }

        .hero-image-label small {
            color: #e1d5d5;
            font-size: 11px;
        }

        .hero-image-label strong {
            display: block;
            margin-top: 5px;
            font-size: 16px;
        }

        .floating-note {
            position: absolute;
            z-index: 3;
            top: 55px;
            left: -28px;
            max-width: 190px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 15px;
            background: rgba(244, 240, 238, 0.94);
            color: var(--dark);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(12px);
            animation: noteFloat 5s ease-in-out infinite;
        }

        .floating-note span {
            display: block;
            color: var(--mid);
            font-size: 10px;
        }

        .floating-note strong {
            display: block;
            margin-top: 6px;
            font-size: 13px;
        }

        /* Hero footer */
        .hero-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-top: 55px;
            padding-top: 23px;
            border-top: 1px solid rgba(255, 255, 255, 0.16);
        }

        .hero-bottom p {
            color: #c9b8b8;
            font-size: 11px;
        }

        .scroll-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #e2d5d5;
            font-size: 11px;
        }

        .scroll-indicator span {
            display: block;
            width: 34px;
            height: 1px;
            background: #baadad;
            animation: linePulse 1.8s ease-in-out infinite;
        }

        /* Background animations */
        @keyframes redGlowOne {
            0% {
                transform: translate(0, 0) scale(0.85);
            }
            100% {
                transform: translate(-350px, 250px) scale(1.2);
            }
        }

        @keyframes redGlowTwo {
            0% {
                transform: translate(0, 0) scale(0.9);
            }
            100% {
                transform: translate(300px, -250px) scale(1.25);
            }
        }

        @keyframes redGlowThree {
            0% {
                transform: translate(-150px, -100px) scale(0.8);
            }
            100% {
                transform: translate(250px, 150px) scale(1.2);
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .hero::before,
            .hero::after,
            .hero-glow,
            .hero-image-frame,
            .floating-note,
            .scroll-indicator span {
                animation: none !important;
            }
        }


        /* Footer */

        .site-footer {
            padding: 65px 0 25px;
            background: var(--dark);
            color: white;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.3fr .7fr .7fr 1fr;
            gap: 40px;
            padding-bottom: 55px;
        }

        .footer-description {
            max-width: 320px;
            margin-top: 20px;
            color: #c9b7b7;
            font-size: 12px;
            line-height: 2.5;
        }

        .footer-heading {
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 800;
        }

        .footer-links {
            display: grid;
            gap: 14px;
        }

        .footer-links a {
            color: #c9b7b7;
            font-size: 12px;
            transition: color .25s, padding-right .25s;
        }

        .footer-links a:hover {
            color: white;
            padding-right: 5px;
        }

        .footer-contact {
            display: grid;
            gap: 17px;
            color: #c9b7b7;
            font-size: 12px;
            line-height: 2;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,.13);
            color: #b9a6a6;
            font-size: 10px;
        }

        .footer-bottom a:hover {
            color: white;
        }

        /* Scroll reveal */

        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition:
                opacity .75s ease,
                transform .75s cubic-bezier(.2,.7,.2,1);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: .1s;
        }

        .reveal-delay-2 {
            transition-delay: .2s;
        }

        .reveal-delay-3 {
            transition-delay: .3s;
        }

        .hero-enter {
            opacity: 0;
            animation: heroEnter .8s cubic-bezier(.2,.7,.2,1) forwards;
        }

        .hero-enter.delay-1 {
            animation-delay: .12s;
        }

        .hero-enter.delay-2 {
            animation-delay: .24s;
        }

        @keyframes heroEnter {
            from {
                opacity: 0;
                transform: translateY(22px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes heroFloat {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-9px);
            }
        }

        @keyframes noteFloat {
            0%, 100% {
                transform: translateY(0) rotate(-2deg);
            }
            50% {
                transform: translateY(-10px) rotate(0deg);
            }
        }

        @keyframes linePulse {
            0%, 100% {
                transform: scaleX(1);
                opacity: 1;
            }
            50% {
                transform: scaleX(.55);
                opacity: .55;
            }
        }

        /* Responsive */

        @media (max-width: 1050px) {
            .desktop-nav {
                gap: 15px;
            }

            .desktop-nav a {
                font-size: 12px;
            }

            .hero-content {
                gap: 35px;
            }

            .intro-grid {
                gap: 45px;
            }

            .products-grid,
            .articles-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-card.featured {
                grid-column: span 2;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .product-card.featured .product-visual {
                height: 100%;
                min-height: 340px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 760px) {
            .container-main {
                width: calc(100% - 36px);
            }

            .site-header {
                top: 10px;
                padding-inline: 12px;
            }

            .header-inner {
                min-height: 64px;
                padding: 9px 12px;
                border-radius: 16px;
            }

            .desktop-nav,
            .header-cta {
                display: none;
            }

            .menu-toggle {
                display: flex;
            }

            .mobile-menu {
                display: block;
                left: 12px;
                right: 12px;
            }

            .brand-symbol {
                width: 39px;
                height: 39px;
            }

            .brand-name {
                font-size: 14px;
            }

            .hero {
                min-height: auto;
                padding: 135px 0 38px;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .hero-title {
                font-size: clamp(40px, 11vw, 66px);
            }

            .hero-description {
                font-size: 13px;
            }

            .hero-visual {
                width: 88%;
                margin-inline: auto;
                padding: 15px 0 25px;
            }

            .hero-image-frame {
                height: 370px;
                border-radius: 150px 150px 14px 14px;
            }

            .floating-note {
                top: 50px;
                left: -22px;
                max-width: 160px;
                padding: 12px;
            }

            .hero-bottom {
                margin-top: 30px;
            }

            .hero-bottom p {
                max-width: 65%;
                line-height: 2;
            }


            .section-heading {
                align-items: start;
                flex-direction: column;
                gap: 16px;
                margin-bottom: 32px;
            }

            .section-subtitle {
                max-width: 100%;
            }

            .products-grid,
            .articles-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .product-card.featured {
                grid-column: auto;
                display: block;
            }

            .product-card.featured .product-visual,
            .product-visual {
                height: 270px;
                min-height: 0;
            }

            .statement-section {
                padding: 75px 0;
            }

            .statement-inner {
                gap: 22px;
            }

            .statement-title {
                font-size: clamp(34px, 9vw, 54px);
            }

            .about-visual {
                min-height: 370px;
            }

            .about-image {
                width: 90%;
                height: 370px;
                border-top-right-radius: 115px;
            }

            .about-stamp {
                width: 135px;
                padding: 18px 14px;
            }

            .about-points {
                gap: 18px 12px;
            }

            .article-image,
            .article-card:first-child .article-image {
                height: 250px;
            }

            .contact-section {
                padding: 20px 0 70px;
            }

            .contact-panel {
                align-items: start;
                flex-direction: column;
                gap: 25px;
                padding: 30px 23px;
            }

            .contact-copy h2 {
                font-size: 30px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 35px 20px;
            }

            .footer-grid > :first-child {
                grid-column: span 2;
            }

            .footer-bottom {
                align-items: start;
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 390px) {
            .hero-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .hero-actions a {
                justify-content: center;
            }

            .intro-values {
                grid-template-columns: 1fr;
            }

            .intro-aside {
                align-items: start;
                flex-direction: column;
            }

            .about-points {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid > :first-child {
                grid-column: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>

<body class="antialiased">

    @include('partials.site.header')

    <main>
        @yield('content')
    </main>

    @include('partials.site.footer')

    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
    <!-- ================= JAVASCRIPT ================= -->
    <script>
        (() => {
            // Mobile navigation
            const menuToggle = document.getElementById('menuToggle');
            const mobileNav = document.getElementById('mobileNav');

            if (!menuToggle || !mobileNav) return;

            menuToggle.addEventListener('click', function () {
                const isOpen = mobileNav.classList.contains('open');

                mobileNav.classList.toggle('open', !isOpen);
                menuToggle.classList.toggle('active', !isOpen);

                if (isOpen) {
                    mobileNav.setAttribute('inert', '');
                } else {
                    mobileNav.removeAttribute('inert');
                }

                menuToggle.setAttribute('aria-expanded', String(!isOpen));
                menuToggle.setAttribute(
                    'aria-label',
                    isOpen ? 'باز کردن منو' : 'بستن منو'
                );
            });

            mobileNav.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mobileNav.classList.remove('open');
                    menuToggle.classList.remove('active');

                    mobileNav.setAttribute('inert', '');

                    menuToggle.setAttribute('aria-expanded', 'false');
                    menuToggle.setAttribute('aria-label', 'باز کردن منو');
                });
            });
        })();
    </script>
</body>

</html>