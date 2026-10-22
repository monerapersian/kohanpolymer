<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#222222">

    <title>ورود به سامانه | کهن پلیمر البرز</title>

    <link rel="icon" href="{{ asset('img/kohan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        .login-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        .login-glow {
            background: radial-gradient(ellipse, rgba(104,27,33,.24) 0%, rgba(104,27,33,.07) 42%, transparent 72%);
        }

        .login-field:focus-within {
            border-color: #a64c55;
            box-shadow: 0 0 0 3px rgba(104,27,33,.15);
        }

        .login-field input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #292929 inset;
            -webkit-text-fill-color: #fff;
        }

        .login-field input:focus {  
            border-color: #222222 !important;
            outline: none !important;
            ring: 0 !important;
        }

        .login-field input:focus {  
            border-color: #222222 !important;
            outline: none !important;
            ring: 0 !important;
        }

        .login-field input {  
            border-color: #222222 !important;
            outline: none !important;
            ring: 0 !important;
        }
    </style>
</head>

<body class="relative flex min-h-screen items-center justify-center overflow-x-hidden bg-[#222222] px-4 py-10 text-white antialiased sm:px-6">

    <div class="login-grid pointer-events-none absolute inset-0"></div>
    <div class="login-glow pointer-events-none absolute inset-0"></div>


    <main class="relative z-10 w-full max-w-[440px]">

        {{-- Brand --}}
        <header class="mb-8 flex flex-col items-center text-center">
            <a href="{{ url('/') }}" class="group mb-5 flex h-[76px] w-[76px] items-center justify-center rounded-[22px] border border-white/10 bg-white p-3 shadow-2xl shadow-black/20 transition duration-300 hover:-translate-y-1 hover:border-[#a64c55]/50">
                <img
                    src="{{ asset('img/kohan-logo.png') }}"
                    alt="لوگوی کهن پلیمر البرز"
                    class="h-full w-full object-contain"
                >
            </a>

            <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">
                کهن پلیمر البرز
            </h1>

            <div class="mt-2 flex items-center gap-2">
                <span class="h-px w-6 bg-[#681b21]"></span>
                <p class="text-[11px] tracking-wide text-white/40">سامانه مدیریت سازمانی</p>
                <span class="h-px w-6 bg-[#681b21]"></span>
            </div>
        </header>

        {{-- Login card --}}
        <section class="overflow-hidden rounded-[24px] border border-white/[0.09] bg-[#272727]/95 shadow-2xl shadow-black/30">

            <div class="h-[3px] w-full bg-gradient-to-l from-[#681b21] via-[#a64c55] to-transparent"></div>

            <div class="p-6 sm:p-9">

                <div class="mb-8">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#681b21]/40 bg-[#681b21]/15 px-3 py-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#c87981] shadow-[0_0_8px_rgba(200,121,129,.6)]"></span>
                        <span class="text-[10px] font-semibold text-[#d69ba0]">دسترسی کاربران مجاز</span>
                    </div>

                    <h2 class="text-2xl font-extrabold tracking-tight">
                        خوش آمدید
                    </h2>

                    <p class="mt-3 text-xs leading-7 text-white/40 sm:text-sm">
                        برای ورود به پنل مدیریت، اطلاعات حساب خود را وارد کنید.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/[0.08] px-4 py-3 text-sm leading-6 text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-[#a64c55]/30 bg-[#681b21]/15 px-4 py-3 text-sm leading-6 text-[#efb7bc]">
                        <p class="mb-1 font-bold">ورود انجام نشد</p>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email field --}}
                    <div>
                        <label for="email" class="mb-2.5 block text-xs font-semibold text-white/70">
                            آدرس ایمیل
                        </label>

                        <div class="login-field flex h-[54px] items-center gap-3 rounded-xl border border-white/[0.09] bg-[#222222] px-4 transition duration-200">
                            <svg class="h-[19px] w-[19px] shrink-0 text-white/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                dir="ltr"
                                placeholder="name@company.com"
                                class="h-full min-w-0 flex-1 bg-transparent text-left text-sm text-white outline-none placeholder:text-white/20 focus:outline-none focus:ring-0 focus:border-transparent"
                            >
                        </div>
                    </div>

                    {{-- Password field --}}
                    <div>
                        <div class="mb-2.5 flex items-center justify-between gap-3">
                            <label for="password" class="text-xs font-semibold text-white/70">
                                رمز عبور
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] text-[#c87981] transition hover:text-white">
                                    فراموشی رمز عبور
                                </a>
                            @endif
                        </div>

                        <div class="login-field flex h-[54px] items-center gap-3 rounded-xl border border-white/[0.09] bg-[#222222] px-4 transition duration-200">
                            <svg class="h-[19px] w-[19px] shrink-0 text-white/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="10" width="16" height="11" rx="2"/>
                                <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            </svg>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                dir="ltr"
                                autocomplete="current-password"
                                placeholder="رمز عبور حساب کاربری"
                                class="h-full min-w-0 flex-1 bg-transparent text-sm text-white outline-none placeholder:text-white/20"
                            >

                            <button
                                id="togglePassword"
                                type="button"
                                aria-label="نمایش رمز عبور"
                                aria-pressed="false"
                                class="shrink-0 text-white/35 transition hover:text-[#c87981]"
                            >
                                <svg id="eyeOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                                <svg id="eyeClosed" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m3 3 18 18"/>
                                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                    <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a16 16 0 0 1-3.1 3.8"/>
                                    <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1 0 1.9-.2 2.8-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label for="remember_me" class="flex w-fit cursor-pointer items-center gap-2.5 py-1">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-white/20 bg-[#222222] accent-[#a64c55] focus:ring-[#a64c55]"
                        >
                        <span class="text-xs text-white/45">مرا به خاطر بسپار</span>
                    </label>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="group relative flex h-[54px] w-full items-center justify-center gap-3 overflow-hidden rounded-xl bg-[#681b21] px-5 text-sm font-bold text-white transition duration-200 hover:bg-[#7b252d] focus:outline-none focus:ring-4 focus:ring-[#a64c55]/25"
                    >
                        <span class="absolute inset-y-0 right-0 w-1 bg-[#d69ba0] transition-all duration-300 group-hover:w-full group-hover:opacity-10"></span>

                        <span class="relative">ورود به پنل مدیریت</span>

                        <svg class="relative h-5 w-5 transition-transform duration-200 group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 12H5"/>
                            <path d="m12 19-7-7 7-7"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-7 flex items-center justify-center gap-2 border-t border-white/[0.07] pt-5">
                    <svg class="h-4 w-4 text-[#a64c55]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span class="text-[10px] text-white/35">اتصال امن به سامانه مدیریت</span>
                </div>
            </div>
        </section>

        <footer class="mt-6 text-center">
            <p class="text-[10px] text-white/25">
                © {{ date('Y') }} کهن پلیمر البرز · تمامی حقوق محفوظ است
            </p>
        </footer>

    </main>

    <script>
        (() => {
            const toggle = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (!toggle || !password || !eyeOpen || !eyeClosed) return;

            toggle.addEventListener('click', () => {
                const show = password.type === 'password';

                password.type = show ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', show);
                eyeClosed.classList.toggle('hidden', !show);
                toggle.setAttribute('aria-pressed', String(show));
                toggle.setAttribute('aria-label', show ? 'مخفی کردن رمز عبور' : 'نمایش رمز عبور');
            });
        })();
    </script>

</body>
</html>