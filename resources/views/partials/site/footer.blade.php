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

<!-- Footer -->
<footer class="site-footer">
    <div class="container-main">

        <div class="footer-grid">

            <!-- Brand -->
            <div>
                <a href="{{ url('/') }}" class="brand">
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
                        <span class="brand-caption block">مهندسی در ساختار مواد</span>
                    </span>
                </a>

                <p class="footer-description">
                    {{ $siteContent?->about_text ?: 'همراهی در مسیر انتخاب مواد و راهکارهای پلیمری، با رویکردی حرفه‌ای، نگاهی دقیق و توجه به نیاز صنعت.' }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="footer-heading">دسترسی سریع</h3>

                <nav class="footer-links">
                    <a href="{{ url('/') }}#home">صفحه اصلی</a>
                    <a href="{{ url('/') }}#about">درباره ما</a>
                    <a href="{{ url('/') }}#products">محصولات</a>
                    <a href="{{ url('/') }}#articles">مقالات</a>
                    <a href="{{ url('/') }}#contact">ارتباط با ما</a>
                </nav>
            </div>

            <!-- Activity Areas -->
            <div>
                <h3 class="footer-heading">حوزه‌های فعالیت</h3>

                <nav class="footer-links">
                    @forelse ($footerActivityAreas as $area)
                        <a href="{{ url('/') }}#products">
                            {{ $area }}
                        </a>
                    @empty
                        <a href="{{ url('/') }}#products">محصولات پلیمری</a>
                        <a href="{{ url('/') }}#products">راهکارهای صنعتی</a>
                    @endforelse
                </nav>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="footer-heading">ارتباط با ما</h3>

                <div class="footer-contact">
                    @forelse ($footerPhones as $phone)
                        <p>
                            تلفن:
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                                {{ $phone }}
                            </a>
                        </p>
                    @empty
                        <p>شماره تماس ثبت نشده است.</p>
                    @endforelse

                    @if ($siteContent?->email)
                        <p>
                            ایمیل:
                            <a href="mailto:{{ $siteContent->email }}">
                                {{ $siteContent->email }}
                            </a>
                        </p>
                    @endif

                    @if ($siteContent?->instagram)
                        <p>
                            اینستاگرام:
                            <a
                                href="https://instagram.com/{{ ltrim($siteContent->instagram, '@') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ ltrim($siteContent->instagram, '@') }}
                            </a>
                        </p>
                    @endif

                    @if ($siteContent?->whatsapp)
                        <p>
                            <a
                                href="https://wa.me/{{ preg_replace('/\D+/', '', $siteContent->whatsapp) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                واتساپ
                            </a>
                        </p>
                    @endif
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© تمامی حقوق این وب‌سایت محفوظ است.</p>

            <a href="{{ url('/') }}#home">
                بازگشت به ابتدای صفحه ↑
            </a>
        </div>

    </div>
</footer>