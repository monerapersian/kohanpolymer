<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F4F1EF">

    <title>@yield('title', 'پنل مدیریت | کهن پلیمر البرز')</title>

    <meta name="description" content="@yield('description', 'پنل مدیریت کهن پلیمر البرز')">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

    <!-- Admin Panel Background -->
    <div class="admin-background" aria-hidden="true">
        <div class="admin-background__glow admin-background__glow--wine"></div>
        <div class="admin-background__glow admin-background__glow--green"></div>
        <div class="admin-background__grid"></div>
    </div>

    <!-- Admin Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Mobile Navigation -->
    @include('partials.admin.mobnav')

    <!-- Admin Main Content -->
    <main class="min-h-screen pr-[308px] pl-6 py-7 max-md:pr-4 max-md:pl-4 max-md:py-5 max-md:pb-28">

        <div class="mx-auto w-full max-w-[1600px] space-y-7 text-[#362727]" dir="rtl">

            @yield('content')

            @include('partials.admin.footer')

        </div>

    </main>

    <!-- Dashboard Date -->
    <script>
        (() => {
            const dateElement = document.getElementById('dashboard-date');
            const weekdayElement = document.getElementById('dashboard-weekday');

            if (!dateElement || !weekdayElement) return;

            const now = new Date();

            const dateFormatter = new Intl.DateTimeFormat('fa-IR-u-ca-persian', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const weekdayFormatter = new Intl.DateTimeFormat('fa-IR', {
                weekday: 'long'
            });

            dateElement.textContent = dateFormatter.format(now);
            weekdayElement.textContent = weekdayFormatter.format(now);
        })();
    </script>

    @stack('scripts')

</body>
</html>