
@extends('layouts.site')

@section('title', 'کهن پلیمر البرز | مهندسی آینده در صنعت پلیمر')

@section('description', 'کهن پلیمر البرز؛ تولید و عرضه محصولات پلیمری صنعتی، نوار نقاره و تسمه نقاره با تمرکز بر کیفیت، دوام و عملکرد.')

@section('content')


        <!-- ================= HERO ================= -->
        <!-- طراحی و تصویرسازی اصلی Hero حفظ شده است -->
        <section id="home" class="relative min-h-screen flex items-center pt-28 pb-16 overflow-hidden noise-overlay bg-[#222222]">

            <div class="absolute inset-0 grid-pattern opacity-70"></div>
            <div class="absolute top-24 right-0 w-80 h-80 rounded-full blur-[150px] bg-[#380a0d]/50 pointer-events-none"></div>

            <div class="container-custom relative z-10 hero-glass p-4 rounded-lg">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                    <div class="reveal">
                        <div class="inline-flex items-center gap-3 border border-white/15 bg-white/[0.03] px-4 py-2 mb-8 rounded-lg">
                            <span class="w-2 h-2 bg-[#9d343b] rounded-full"></span>
                            <span class="text-xs sm:text-sm text-[#BFBFBF]">مهندسی پلیمر، فراتر از استاندارد</span>
                            <span class="text-[10px] tracking-widest text-[#777]" dir="ltr">KP / 01</span>
                        </div>

                        <h1 class="hero-title font-black text-white mb-7">
                            پلیمر،
                            <br>
                            <span class="text-[#8b252c]">فراتر از</span>
                            یک ماده<span class="text-[#8b252c]">.</span>
                        </h1>

                        <p class="text-[#BFBFBF] leading-9 text-sm sm:text-base max-w-xl mb-9">
                            {{ $content?->hero_slogan ?: 'در کهن پلیمر البرز، به جزئیاتی فکر می‌کنیم که
                            عملکرد یک مجموعه را متحول می‌کنند؛
                            راهکارهای پلیمری برای دوام بیشتر، عملکرد بهتر
                            و حرکت رو به جلوی صنعت.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="#products" class="btn-primary px-6 py-4 text-sm rounded-lg">
                                کشف محصولات 
                                <span aria-hidden="true">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M19 12H5m7 7-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </a>

                            <a href="#about" class="btn-outline px-6 py-4 text-sm text-[#ffffff] rounded-lg" style="color:#fff !important;">
                                شناخت مجموعه 
                                <span aria-hidden="true">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M19 12H5m7 7-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </a>
                        </div>

                        <div class="mt-12 pt-7 border-t border-white/10 grid grid-cols-3 gap-4">
                            <div>
                                <div class="text-xl sm:text-2xl font-bold text-white mb-2">پلیمر</div>
                                <div class="text-[11px] sm:text-xs text-[#888]">تخصص محوری</div>
                            </div>
                            <div class="border-r border-white/10 pr-4">
                                <div class="text-xl sm:text-2xl font-bold text-white mb-2">صنعت</div>
                                <div class="text-[11px] sm:text-xs text-[#888]">نگاه کاربردی</div>
                            </div>
                            <div class="border-r border-white/10 pr-4">
                                <div class="text-xl sm:text-2xl font-bold text-white mb-2">کیفیت</div>
                                <div class="text-[11px] sm:text-xs text-[#888]">اصل همکاری</div>
                            </div>
                        </div>
                    </div>

                    <div class="hero-visual reveal rounded-lg">
                        <div class="orbit-ring"></div>

                        <div class="hero-badge hero-badge-one">
                            <div class="text-[10px] text-[#929292] mb-1" dir="ltr">MATERIAL / 001</div>
                            <div class="text-xs sm:text-sm font-semibold text-white">راهکارهای پلیمری</div>
                        </div>

                        <svg class="product-illustration" viewBox="0 0 460 440" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="تصویر مفهومی سه‌بعدی تسمه و قطعه پلیمری صنعتی">
                            <defs>
                                <linearGradient id="beltTop" x1="90" y1="90" x2="380" y2="350" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#E3E3E3"/>
                                    <stop offset=".35" stop-color="#8D8D8D"/>
                                    <stop offset=".68" stop-color="#D0D0D0"/>
                                    <stop offset="1" stop-color="#606060"/>
                                </linearGradient>
                                <linearGradient id="beltSide" x1="90" y1="180" x2="370" y2="370" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#777"/>
                                    <stop offset=".5" stop-color="#393939"/>
                                    <stop offset="1" stop-color="#161616"/>
                                </linearGradient>
                                <linearGradient id="innerRing" x1="180" y1="140" x2="290" y2="310" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#444"/>
                                    <stop offset=".5" stop-color="#171717"/>
                                    <stop offset="1" stop-color="#666"/>
                                </linearGradient>
                                <linearGradient id="wineRing" x1="160" y1="80" x2="330" y2="340" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#762027"/>
                                    <stop offset=".5" stop-color="#380a0d"/>
                                    <stop offset="1" stop-color="#160608"/>
                                </linearGradient>
                                <filter id="objectShadow" x="25" y="20" width="420" height="410" filterUnits="userSpaceOnUse">
                                    <feGaussianBlur stdDeviation="12"/>
                                </filter>
                            </defs>

                            <ellipse cx="233" cy="357" rx="150" ry="30" fill="#000" opacity=".45" filter="url(#objectShadow)"/>

                            <g transform="rotate(-17 230 218)">
                                <path d="M113 158 C113 107 163 76 231 76 C299 76 349 107 349 158 L349 245 C349 296 299 327 231 327 C163 327 113 296 113 245 Z" fill="url(#beltSide)" stroke="#8E8E8E" stroke-width="1.5"/>
                                <ellipse cx="231" cy="158" rx="118" ry="82" fill="url(#beltTop)" stroke="#F0F0F0" stroke-opacity=".6" stroke-width="2"/>
                                <ellipse cx="231" cy="158" rx="94" ry="63" fill="url(#wineRing)" stroke="#C7C7C7" stroke-opacity=".5" stroke-width="2"/>
                                <ellipse cx="231" cy="158" rx="69" ry="45" fill="url(#innerRing)" stroke="#D0D0D0" stroke-opacity=".5" stroke-width="2"/>
                                <ellipse cx="231" cy="158" rx="43" ry="27" fill="#252525" stroke="#888" stroke-width="1.5"/>
                                <path d="M114 158 C114 201 166 229 231 229 C296 229 348 201 348 158" stroke="#EEEEEE" stroke-opacity=".24" stroke-width="2"/>
                                <path d="M137 181 C151 219 186 237 231 237 C276 237 311 219 325 181" stroke="#111" stroke-opacity=".55" stroke-width="3"/>
                                <path d="M116 216 C137 252 179 272 231 272 C283 272 325 252 346 216" stroke="#777" stroke-opacity=".55" stroke-width="2"/>
                                <path d="M122 238 C145 268 184 286 231 286 C278 286 317 268 340 238" stroke="#151515" stroke-width="2"/>
                                <ellipse cx="231" cy="158" rx="104" ry="72" stroke="#F7F7F7" stroke-opacity=".3" stroke-width="1.2"/>
                                <path d="M137 120 C161 91 199 79 231 79" stroke="white" stroke-opacity=".7" stroke-width="2" stroke-linecap="round"/>
                                <path d="M137 125 C155 107 173 99 192 95" stroke="#FFF" stroke-opacity=".35" stroke-width="1.5" stroke-linecap="round"/>
                            </g>

                            <g stroke="#BFBFBF" stroke-opacity=".7" stroke-width="1">
                                <path d="M72 100H126"/>
                                <path d="M72 100V115"/>
                                <circle cx="126" cy="100" r="3" fill="#BFBFBF"/>
                                <path d="M329 310H390"/>
                                <path d="M390 310V294"/>
                                <circle cx="329" cy="310" r="3" fill="#BFBFBF"/>
                                <path d="M344 104L372 76H398"/>
                                <circle cx="344" cy="104" r="3" fill="#BFBFBF"/>
                            </g>

                            <g fill="#BFBFBF" font-family="sans-serif" font-size="9" letter-spacing="2">
                                <text x="43" y="89" direction="ltr">POLYMER</text>
                                <text x="351" y="65" direction="ltr">KP / 01</text>
                                <text x="331" y="329" direction="ltr">INDUSTRIAL</text>
                            </g>
                        </svg>

                        <div class="hero-badge hero-badge-two">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 border border-white/20 grid place-items-center text-sm">↗</div>
                                <div>
                                    <div class="text-xs text-white font-semibold">نگاه رو به آینده</div>
                                    <div class="text-[10px] text-[#929292] mt-1">ENGINEERED FOR INDUSTRY</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-4 right-5 text-[10px] text-[#777] tracking-[3px]" dir="ltr">
                            KOHAN / ALBORZ
                        </div>
                    </div>

                </div>
            </div>

            <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-l from-transparent via-white/20 to-transparent"></div>
        </section>

        <!-- ================= BRAND STRIP ================= -->
        <section class="brand-strip border-b">
            <div class="container-custom py-6 flex flex-wrap items-center justify-between gap-5">
                <div class="strip-intro text-xs sm:text-sm">رویکرد ما در توسعه محصولات</div>

                <div class="strip-items flex flex-wrap items-center gap-x-8 gap-y-3 text-xs sm:text-sm">
                    <span class="flex items-center gap-2"><span class="text-[#8b252c]">◆</span> انتخاب هوشمندانه مواد</span>
                    <span class="flex items-center gap-2"><span class="text-[#8b252c]">◆</span> توجه به کاربرد صنعتی</span>
                    <span class="flex items-center gap-2"><span class="text-[#8b252c]">◆</span> همکاری بلندمدت</span>
                </div>
            </div>
        </section>

        <!-- ================= PRODUCTS ================= -->
        <section id="products" class="py-24 sm:py-32 relative">
            <div class="container-custom">

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-14 reveal">
                    <div>
                        <div class="section-label mb-5">محصولات و راهکارها</div>
                        <h2 class="products-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-relaxed">
                            اجزای کوچک،
                            <br class="sm:hidden">
                            <span class="heading-muted">تأثیرهای بزرگ.</span>
                        </h2>
                        <div class="accent-line mt-5"></div>
                    </div>

                    <p class="products-intro text-sm leading-8 max-w-md">
                        شناخت درست محصول، اولین قدم برای انتخاب درست است.
                        با گروه محصولات کهن پلیمر البرز آشنا شوید و
                        متناسب با نیاز مجموعه خود تصمیم بگیرید.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

                    <!-- Product 01 -->
                    <article class="product-card reveal rounded-lg">
                        <div class="product-art">
                            <span class="product-number">PRODUCT / 01</span>
                            <svg class="product-visual" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="تصویر مفهومی نوار نقاره">
                                <defs>
                                    <linearGradient id="stripA" x1="25" y1="35" x2="150" y2="145">
                                        <stop stop-color="#E4E4E4"/>
                                        <stop offset=".48" stop-color="#8A8A8A"/>
                                        <stop offset="1" stop-color="#D4D4D4"/>
                                    </linearGradient>
                                    <linearGradient id="stripB" x1="30" y1="60" x2="130" y2="140">
                                        <stop stop-color="#4B4B4B"/>
                                        <stop offset="1" stop-color="#171717"/>
                                    </linearGradient>
                                </defs>
                                <path d="M35 58L103 32L151 52L83 79Z" fill="#777" stroke="#BFBFBF" stroke-width="1"/>
                                <path d="M35 58L83 79V99L35 78Z" fill="url(#stripB)" stroke="#777"/>
                                <path d="M83 79L151 52V72L83 99Z" fill="#333" stroke="#777"/>
                                <path d="M26 81L94 55L142 75L74 102Z" fill="url(#stripA)" stroke="#E2E2E2"/>
                                <path d="M26 81L74 102V119L26 98Z" fill="#737373" stroke="#A7A7A7"/>
                                <path d="M74 102L142 75V92L74 119Z" fill="#393939" stroke="#8B8B8B"/>
                                <path d="M19 106L87 80L135 100L67 127Z" fill="#BFBFBF" stroke="#E7E7E7"/>
                                <path d="M19 106L67 127V143L19 122Z" fill="#666" stroke="#AAA"/>
                                <path d="M67 127L135 100V116L67 143Z" fill="#292929" stroke="#858585"/>
                                <path d="M33 109L85 129M40 83L91 103M49 60L100 80" stroke="#FFF" stroke-opacity=".45"/>
                            </svg>
                        </div>

                        <div class="p-6 sm:p-7">
                            <div class="product-category text-xs mb-3">01 / نوار پلیمری</div>
                            <h3 class="product-title text-xl font-bold mb-4">نوار نقاره</h3>
                            <p class="product-description text-sm leading-8 mb-7">
                                راهکاری پلیمری برای کاربردهای متناسب با نیاز صنعت؛
                                انتخاب محصول نهایی بر اساس ابعاد، شرایط استفاده
                                و مشخصات فنی موردنیاز انجام می‌شود.
                            </p>
                            <a href="#contact" class="product-link flex items-center justify-between gap-3 pt-5 border-t text-sm">
                                <span>استعلام مشخصات محصول</span>
                                <span class="product-arrow rounded-lg" aria-hidden="true">←</span>
                            </a>
                        </div>
                    </article>

                    <!-- Product 02 -->
                    <article class="product-card reveal rounded-lg">
                        <div class="product-art">
                            <span class="product-number">PRODUCT / 02</span>
                            <svg class="product-visual" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="تصویر مفهومی تسمه نقاره">
                                <defs>
                                    <linearGradient id="beltA" x1="35" y1="50" x2="130" y2="135">
                                        <stop stop-color="#E2E2E2"/>
                                        <stop offset=".35" stop-color="#A0A0A0"/>
                                        <stop offset=".7" stop-color="#555"/>
                                        <stop offset="1" stop-color="#BFBFBF"/>
                                    </linearGradient>
                                </defs>
                                <g transform="rotate(-22 90 90)">
                                    <path d="M45 39C45 24 66 14 91 14C116 14 137 24 137 39V117C137 132 116 142 91 142C66 142 45 132 45 117Z" fill="#333" stroke="#929292" stroke-width="1.5"/>
                                    <ellipse cx="91" cy="39" rx="46" ry="25" fill="url(#beltA)" stroke="#E5E5E5"/>
                                    <ellipse cx="91" cy="39" rx="29" ry="15" fill="#292929" stroke="#666"/>
                                    <ellipse cx="91" cy="39" rx="15" ry="7" fill="#171717" stroke="#777"/>
                                    <path d="M45 63C45 78 66 88 91 88C116 88 137 78 137 63" stroke="#777" stroke-width="1.5"/>
                                    <path d="M45 84C45 99 66 109 91 109C116 109 137 99 137 84" stroke="#BFBFBF" stroke-opacity=".5" stroke-width="1.5"/>
                                    <path d="M45 105C45 120 66 130 91 130C116 130 137 120 137 105" stroke="#777" stroke-width="1.5"/>
                                    <ellipse cx="91" cy="39" rx="39" ry="21" stroke="white" stroke-opacity=".35"/>
                                </g>
                                <path d="M22 133H55M55 133L65 123" stroke="#BFBFBF" stroke-opacity=".65"/>
                                <circle cx="22" cy="133" r="2" fill="#BFBFBF"/>
                            </svg>
                        </div>

                        <div class="p-6 sm:p-7">
                            <div class="product-category text-xs mb-3">02 / تسمه صنعتی</div>
                            <h3 class="product-title text-xl font-bold mb-4">تسمه نقاره</h3>
                            <p class="product-description text-sm leading-8 mb-7">
                                محصولی برای نیازهای صنعتی که در انتخاب آن،
                                توجه به ابعاد، ساختار، شرایط کاری و
                                تناسب با مجموعه اهمیت دارد.
                            </p>
                            <a href="#contact" class="product-link flex items-center justify-between gap-3 pt-5 border-t text-sm">
                                <span>استعلام مشخصات محصول</span>
                                <span class="product-arrow rounded-lg" aria-hidden="true">←</span>
                            </a>
                        </div>
                    </article>

                    <!-- Product 03 -->
                    <article class="product-card reveal sm:col-span-2 lg:col-span-1 rounded-lg">
                        <div class="product-art">
                            <span class="product-number">PRODUCT / 03</span>
                            <svg class="product-visual" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="تصویر مفهومی قطعه پلیمری صنعتی">
                                <path d="M45 48L91 27L137 48L91 69Z" fill="#D2D2D2" stroke="#EEE"/>
                                <path d="M45 48L91 69V125L45 103Z" fill="#686868" stroke="#AAA"/>
                                <path d="M91 69L137 48V104L91 125Z" fill="#303030" stroke="#888"/>
                                <path d="M60 58L91 44L122 58L91 73Z" fill="#333" stroke="#888"/>
                                <path d="M60 58L91 73V105L60 90Z" fill="#444" stroke="#777"/>
                                <path d="M91 73L122 58V90L91 105Z" fill="#1D1D1D" stroke="#666"/>
                                <path d="M39 117L91 143L143 117M48 130L91 152L134 130" stroke="#BFBFBF" stroke-width="2"/>
                                <path d="M48 130L91 152L134 130" stroke="#8B252C" stroke-width="3"/>
                                <path d="M91 143V155" stroke="#BFBFBF" stroke-width="2"/>
                            </svg>
                        </div>

                        <div class="p-6 sm:p-7">
                            <div class="product-category text-xs mb-3">03 / راهکار سفارشی</div>
                            <h3 class="product-title text-xl font-bold mb-4">محصولات پلیمری صنعتی</h3>
                            <p class="product-description text-sm leading-8 mb-7">
                                برای بررسی سایر محصولات و نیازهای اختصاصی،
                                مشخصات کاربرد موردنظر خود را با کارشناسان
                                مجموعه مطرح کنید.
                            </p>
                            <a href="#contact" class="product-link flex items-center justify-between gap-3 pt-5 border-t text-sm">
                                <span>ثبت درخواست مشاوره</span>
                                <span class="product-arrow rounded-lg" aria-hidden="true">←</span>
                            </a>
                        </div>
                    </article>

                </div>

                <div class="product-note mt-8 text-xs leading-7">
                    * تصاویر محصولات در این نسخه به‌صورت تصویرسازی مفهومی طراحی شده‌اند؛
                    برای معرفی دقیق کالاها می‌توان تصاویر واقعی و مشخصات فنی تأییدشده را جایگزین کرد.
                </div>
            </div>
        </section>

        <!-- ================= ABOUT ================= -->
        <section id="about" class="py-20 sm:py-28 border-y">
            <div class="container-custom">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

                    <div class="about-visual reveal rounded-lg">
                        <div class="about-big-number">KPA</div>

                        <div class="absolute inset-0 grid place-items-center z-10">

                            <div class="relative w-[75%] max-w-[320px] aspect-square">

                                <!-- خطوط و دایره‌های هندسی -->
                                <svg
                                    viewBox="0 0 320 320"
                                    class="absolute inset-0 w-full h-full"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    role="img"
                                    aria-label="نشان هندسی کهن پلیمر البرز"
                                >
                                    <circle
                                        cx="160"
                                        cy="160"
                                        r="115"
                                        stroke="#DDDDDD"
                                        stroke-opacity=".17"
                                    />

                                    <circle
                                        cx="160"
                                        cy="160"
                                        r="93"
                                        stroke="#DDDDDD"
                                        stroke-opacity=".2"
                                        stroke-dasharray="3 7"
                                    />

                                    <circle
                                        cx="160"
                                        cy="160"
                                        r="70"
                                        stroke="#8B252C"
                                        stroke-width="2"
                                    />

                                    <g fill="#BFBFBF">
                                        <circle cx="160" cy="38" r="3"/>
                                        <circle cx="282" cy="160" r="3"/>
                                        <circle cx="160" cy="282" r="3"/>
                                        <circle cx="38" cy="160" r="3"/>
                                    </g>

                                    <path
                                        d="M160 15V38M305 160H282M160 305V282M15 160H38"
                                        stroke="#BFBFBF"
                                        stroke-opacity=".5"
                                    />
                                </svg>

                                <!-- لوگو در مرکز دایره -->
                                <div class="absolute inset-0 flex items-center justify-center -top-2.5">
                                    <img
                                        src="{{ asset('img/kohan-logo.png') }}"
                                        alt="لوگوی کهن پلیمر البرز"
                                        class="w-30 h-30 object-contain"
                                    >
                                </div>

                            </div>

                        </div>

                        <div class="absolute bottom-9 right-9 z-10">
                            <div class="text-white text-lg font-bold">کهن پلیمر البرز</div>
                            <div class="text-xs text-[#BFBFBF] mt-2 tracking-widest" dir="ltr">MATERIALS / ENGINEERING</div>
                        </div>
                    </div>

                    <div class="reveal">
                        <div class="section-label mb-5">درباره کهن پلیمر البرز</div>

                        <h2 class="about-heading text-3xl sm:text-4xl font-extrabold leading-relaxed mb-7">
                            فراتر از یک محصول؛
                            <br>
                            <span class="heading-muted">نگاهی به عملکرد.</span>
                        </h2>

                        <div class="accent-line mb-7"></div>

                        <p class="about-copy-primary text-sm sm:text-base leading-9 mb-5">
                            در دنیای صنعت، انتخاب متریال مناسب تنها یک خرید نیست؛
                            تصمیمی است که می‌تواند بر عملکرد، دوام و هزینه‌های
                            نگهداری اثر بگذارد.
                        </p>

                        <p class="about-copy-secondary text-sm leading-9 mb-9">
                            {{ $content?->about_text ?: 'کهن پلیمر البرز با تمرکز بر محصولات پلیمری صنعتی،
                            در مسیر فراهم‌کردن راهکارهای متناسب با نیاز مشتریان
                            حرکت می‌کند. هدف ما ایجاد ارتباطی روشن میان نیاز،
                            مشخصات محصول و انتخاب آگاهانه است.' }}
                        </p>

                        <a href="#contact" class="btn-outline px-6 py-4 text-sm text-white rounded-lg">
                            شروع یک همکاری 
                            <span aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M19 12H5m7 7-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= ADVANTAGES ================= -->
        <section id="advantages" class="py-20 sm:py-20">
            <div class="container-custom">

                <div class="max-w-2xl mb-14 reveal">
                    <div class="section-label mb-5">چرا کهن پلیمر البرز؟</div>

                    <h2 class="advantages-heading text-3xl sm:text-4xl font-extrabold leading-relaxed mb-5">
                        انتخابی بر پایه
                        <span class="heading-muted">شناخت و اعتماد.</span>
                    </h2>

                    <p class="advantages-intro text-sm leading-8">
                        یک همکاری حرفه‌ای از درک درست نیاز آغاز می‌شود؛
                        از بررسی مشخصات محصول تا انتخاب راهکار مناسب.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">

                    <article class="feature-card reveal">
                        <div class="feature-icon mb-6 rounded-lg">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                                <path d="M12 3L20 7V12C20 17 16.5 20 12 22C7.5 20 4 17 4 12V7L12 3Z"/>
                                <path d="M8 12L11 15L16 9"/>
                            </svg>
                        </div>
                        <h3 class="feature-title font-bold text-lg mb-4">توجه به کیفیت</h3>
                        <p class="feature-description text-sm leading-8">
                            تمرکز بر مشخصات محصول و تناسب آن با کاربرد موردنظر
                            برای انتخابی آگاهانه‌تر.
                        </p>
                    </article>

                    <article class="feature-card reveal">
                        <div class="feature-icon mb-6 rounded-lg">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7V12L16 14"/>
                                <path d="M4 4L6 6M20 4L18 6"/>
                            </svg>
                        </div>
                        <h3 class="feature-title font-bold text-lg mb-4">صرفه‌جویی در زمان</h3>
                        <p class="feature-description text-sm leading-8">
                            ارائه اطلاعات روشن درباره نیاز مشتری و کمک به
                            کوتاه‌تر شدن مسیر انتخاب محصول.
                        </p>
                    </article>

                    <article class="feature-card reveal">
                        <div class="feature-icon mb-6 rounded-lg">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                                <path d="M3 7L12 3L21 7L12 11L3 7Z"/>
                                <path d="M3 12L12 16L21 12"/>
                                <path d="M3 17L12 21L21 17"/>
                            </svg>
                        </div>
                        <h3 class="feature-title font-bold text-lg mb-4">انتخاب متناسب</h3>
                        <p class="feature-description text-sm leading-8">
                            بررسی نیازهای کاربردی پیش از انتخاب محصول
                            برای رسیدن به راهکار مناسب‌تر.
                        </p>
                    </article>

                    <article class="feature-card reveal">
                        <div class="feature-icon mb-6 rounded-lg">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                                <path d="M16 21V19C16 16.8 14.2 15 12 15H6C3.8 15 2 16.8 2 19V21"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M16 11C18.2 11 20 12.8 20 15V16"/>
                                <path d="M16 3.2C18 3.8 19 5.1 19 7S18 10.2 16 10.8"/>
                            </svg>
                        </div>
                        <h3 class="feature-title font-bold text-lg mb-4">ارتباط حرفه‌ای</h3>
                        <p class="feature-description text-sm leading-8">
                            ایجاد ارتباط شفاف و فراهم‌کردن زمینه همکاری
                            متناسب با نیاز کسب‌وکارها.
                        </p>
                    </article>

                </div>
            </div>
        </section>

        <!-- ================= PROCESS ================= -->
        <section class="process-section process-section py-20 sm:py-24 border-y">
            <div class="container-custom">

                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-14 reveal">
                    <div>
                        <div class="section-label mb-5">مسیر همکاری</div>
                        <h2 class="process-heading text-3xl sm:text-4xl font-extrabold leading-relaxed">
                            از نیاز شما تا
                            <span class="heading-muted">انتخاب محصول.</span>
                        </h2>
                    </div>

                    <p class="process-intro text-sm leading-8 max-w-md">
                        فرایندی روشن برای بررسی درخواست و رسیدن به
                        انتخابی متناسب با شرایط پروژه شما.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-9">

                    <article class="reveal">
                        <div class="flex items-center justify-between mb-6">
                            <span class="process-number">01</span>
                            <span class="text-[#8b252c] text-2xl" aria-hidden="true">↙</span>
                        </div>
                        <div class="process-divider h-px mb-6"></div>
                        <h3 class="process-title font-bold mb-3">ثبت نیاز</h3>
                        <p class="process-description text-sm leading-8">کاربرد محصول و نیاز مجموعه خود را مطرح کنید.</p>
                    </article>

                    <article class="reveal">
                        <div class="flex items-center justify-between mb-6">
                            <span class="process-number">02</span>
                            <span class="text-[#8b252c] text-2xl" aria-hidden="true">⌕</span>
                        </div>
                        <div class="process-divider h-px mb-6"></div>
                        <h3 class="process-title font-bold mb-3">بررسی مشخصات</h3>
                        <p class="process-description text-sm leading-8">مشخصات فنی و شرایط کاربرد محصول بررسی می‌شود.</p>
                    </article>

                    <article class="reveal">
                        <div class="flex items-center justify-between mb-6">
                            <span class="process-number">03</span>
                            <span class="text-[#8b252c] text-2xl" aria-hidden="true">◇</span>
                        </div>
                        <div class="process-divider h-px mb-6"></div>
                        <h3 class="process-title font-bold mb-3">انتخاب راهکار</h3>
                        <p class="process-description text-sm leading-8">گزینه مناسب با توجه به اطلاعات درخواست مشخص می‌شود.</p>
                    </article>

                    <article class="reveal">
                        <div class="flex items-center justify-between mb-6">
                            <span class="process-number">04</span>
                            <span class="text-[#8b252c] text-2xl" aria-hidden="true">↗</span>
                        </div>
                        <div class="process-divider h-px mb-6"></div>
                        <h3 class="process-title font-bold mb-3">هماهنگی همکاری</h3>
                        <p class="process-description text-sm leading-8">جزئیات نهایی و شرایط همکاری هماهنگ می‌شود.</p>
                    </article>

                </div>
            </div>
        </section>

        <!-- ================= CONTACT CTA ================= -->
        <section id="contact" class="py-24 sm:py-32">
            <div class="container-custom">

                <div class="contact-panel p-7 sm:p-12 lg:p-16 relative overflow-hidden reveal rounded-lg">
                    <div class="absolute -left-24 -top-24 w-72 h-72 border border-white/[0.06] rounded-full"></div>
                    <div class="absolute -left-12 -top-12 w-48 h-48 border border-white/[0.06] rounded-full"></div>

                    <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">

                        <div>
                            <div class="section-label mb-6">شروع یک همکاری حرفه‌ای</div>

                            <h2 class="contact-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-relaxed mb-6">
                                درخواست خود را ثبت کنید
                            </h2>

                            <p class="contact-description text-sm leading-8 max-w-lg">
                                اطلاعات اولیه را وارد کنید تا بتوانیم درخواست شما را بررسی کنیم.
                            </p>

                            <div class="mt-8 space-y-5">
                                @if($content?->email)
                                    <div class="flex items-center gap-3 text-sm text-[#fff]">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-[#681b21]">
                                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                                <path d="m3 7 9 6 9-6"/>
                                            </svg>
                                        </span>
                                        <a href="mailto:{{ $content->email }}" class="hover:underline" dir="ltr">
                                            {{ $content->email }}
                                        </a>
                                    </div>
                                @endif

                                @if($content?->phone)
                                    <div class="flex items-center gap-3 text-sm text-[#fff]">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-[#681b21]">
                                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path d="M21 16.5v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 10.2 18a19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 1.8 3.2 2 2 0 0 1 3.8 1h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.9L7 9.3a16 16 0 0 0 6 6l1.7-1.7a2 2 0 0 1 1.9-.6l3 .5a2 2 0 0 1 1.4 3Z" transform="translate(1 1) scale(.92)"/>
                                            </svg>
                                        </span>
                                        <a href="tel:{{ $content->phone }}" class="hover:underline" dir="ltr">
                                            {{ $content->phone }}
                                        </a>
                                    </div>
                                @endif

                                @if($content?->instagram)
                                    <div class="flex items-center gap-3 text-sm text-[#fff]">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-[#681b21]">
                                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                                <circle cx="12" cy="12" r="4"/>
                                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                                            </svg>
                                        </span>
                                        <a href="https://instagram.com/{{ $content->instagram }}" target="_blank" rel="noopener noreferrer" class="hover:underline" dir="ltr">
                                            {{ $content->instagram }}
                                        </a>
                                    </div>
                                @endif

                                @if($content?->whatsapp)
                                    <div class="flex items-center gap-3 text-sm text-[#fff]">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-[#681b21]">
                                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                <path d="M20.5 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-4.6a8.5 8.5 0 1 1 16.3-3.9Z"/>
                                                <path d="M8.5 8.2c.3-.6.6-.6 1-.6h.4c.2 0 .4.1.5.4l.8 1.8c.1.2.1.4-.1.6l-.6.7c-.2.2-.2.4 0 .6.5.9 1.3 1.7 2.3 2.2.2.1.4.1.6-.1l.8-.9c.2-.2.4-.2.6-.1l1.7.8c.3.1.4.3.4.5 0 .4-.2 1.2-.8 1.6-.5.4-1.1.6-1.8.5-1-.2-2.4-.8-3.8-2-1.2-1.1-2.1-2.5-2.3-3.4-.2-.8.1-1.5.3-1.9Z"/>
                                            </svg>
                                        </span>
                                        <a href="https://wa.me/{{ $content->whatsapp }}" target="_blank" rel="noopener noreferrer" class="hover:underline" dir="ltr">
                                            ارتباط از طریق واتساپ
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="contact-options border p-6 sm:p-8 rounded-lg">
                            <form
                                id="inquiryForm"
                                action="{{ route('inquiries.store') }}"
                                method="POST"
                                class="grid gap-5 sm:grid-cols-2"
                            >
                                @csrf
                                <div>
                                    <label for="fullName" class="mb-2 block text-sm font-bold text-white kohan-bold">نام و نام خانوادگی</label>
                                    <input id="fullName" name="fullName" type="text" required autocomplete="name"
                                           placeholder="نام شما"
                                           class="w-full rounded-xl border border-neutral-200 bg-[#fafafa] px-4 py-3.5 text-sm outline-none transition placeholder:text-neutral-400 focus:border-brand focus:ring-4 focus:ring-brand/10">
                                </div>

                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-bold text-white kohan-bold">شماره تماس</label>
                                    <input id="phone" name="phone" type="tel" required autocomplete="tel"
                                           placeholder="09123456789"
                                           class="w-full rounded-xl border border-neutral-200 bg-[#fafafa] px-4 py-3.5 text-sm outline-none transition placeholder:text-neutral-400 focus:border-brand focus:ring-4 focus:ring-brand/10">
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="subject" class="mb-2 block text-sm font-bold text-white kohan-bold">موضوع درخواست</label>
                                    <select id="subject" name="subject" required
                                            class="w-full rounded-xl border border-neutral-200 bg-[#fafafa] px-4 py-3.5 text-sm outline-none transition focus:border-brand focus:ring-4 focus:ring-brand/10 cursor-pointer">
                                        <option value="" selected disabled>موضوع موردنظر را انتخاب کنید</option>
                                        <option value="product">استعلام محصولات</option>
                                        <option value="technical">مشاوره فنی</option>
                                        <option value="cooperation">درخواست همکاری</option>
                                        <option value="other">سایر موارد</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="message" class="mb-2 block text-sm font-bold text-white kohan-bold">توضیحات درخواست</label>
                                    <textarea id="message" name="message" rows="4"
                                              placeholder="کمی درباره نیاز یا درخواست خود بنویسید..."
                                              class="w-full resize-y rounded-xl border border-neutral-200 bg-[#fafafa] px-4 py-3.5 text-sm outline-none transition placeholder:text-neutral-400 focus:border-brand focus:ring-4 focus:ring-brand/10"></textarea>
                                </div>

                                <div class="sm:col-span-2">
                                    <button type="submit" class="btn-primary inline-flex w-full items-center justify-center gap-3 rounded-xl px-5 py-3 text-sm font-bold sm:w-auto cursor-pointer">
                                        ثبت درخواست
                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M19 12H5m7 7-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <p id="formMessage" class="mt-4 text-sm leading-7" role="status" aria-live="polite"></p>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <script>
            (() => {
                const form = document.getElementById('inquiryForm');
                const formMessage = document.getElementById('formMessage');

                if (!form || !formMessage) {
                    return;
                }

                const submitButton = form.querySelector('button[type="submit"]');

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (form.dataset.submitting === 'true') {
                        return;
                    }

                    form.dataset.submitting = 'true';

                    const originalButtonContent = submitButton.innerHTML;

                    submitButton.disabled = true;
                    submitButton.innerHTML = 'در حال ارسال...';
                    formMessage.textContent = '';
                    formMessage.className = 'mt-4 text-sm leading-7';

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(form),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (response.status === 422 && data.errors) {
                                const firstError = Object.values(data.errors).flat()[0];

                                throw new Error(firstError || 'اطلاعات واردشده معتبر نیست.');
                            }

                            throw new Error(data.message || 'ثبت درخواست انجام نشد. دوباره تلاش کنید.');
                        }

                        formMessage.textContent = data.message || 'درخواست شما با موفقیت ثبت شد.';
                        formMessage.classList.add('text-green-400');

                        form.reset();
                    } catch (error) {
                        formMessage.textContent = error.message || 'خطایی رخ داد. لطفاً دوباره تلاش کنید.';
                        formMessage.classList.add('text-red-400');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonContent;
                        form.dataset.submitting = 'false';
                    }
                });
            })();
        </script>
@endsection