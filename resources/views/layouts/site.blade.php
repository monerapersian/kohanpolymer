<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        // Mobile navigation
        const menuToggle = document.getElementById('menuToggle');
        const mobileNav = document.getElementById('mobileNav');

        menuToggle.addEventListener('click', function () {
            const isOpen = !mobileNav.classList.contains('hidden');

            mobileNav.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
            menuToggle.setAttribute('aria-label', isOpen ? 'باز کردن منو' : 'بستن منو');
        });

        document.querySelectorAll('.mobile-link').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileNav.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'باز کردن منو');
            });
        });

        // Reveal sections while scrolling
        const revealElements = document.querySelectorAll('.reveal');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -30px 0px'
            });

            revealElements.forEach(function (element) {
                observer.observe(element);
            });
        } else {
            revealElements.forEach(function (element) {
                element.classList.add('is-visible');
            });
        }

        // Respect reduced motion preferences
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            revealElements.forEach(function (element) {
                element.classList.add('is-visible');
            });
        }
    </script>
</body>

</html>