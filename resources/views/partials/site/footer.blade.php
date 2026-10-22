
    <!-- ================= FOOTER ================= -->
    <footer class="border-t">
        <div class="container-custom pt-14 pb-7">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12">

                <div class="lg:col-span-2">
                    <a href="{{ url('/#home') }}" class="inline-flex items-center gap-4 mb-6">
                        <div class="brand-symbol">
                            <span class="brand-symbol-inner"></span>
                        </div>

                        <div>
                            <div class="font-extrabold text-white text-lg">کهن پلیمر البرز</div>
                            <div class="text-[10px] tracking-widest text-[#aaa] mt-1" dir="ltr">
                                KOHAN POLYMER ALBORZ
                            </div>
                        </div>
                    </a>

                    <p class="text-sm text-[#b5b5b5] leading-8 max-w-md">
                        همراه شما در مسیر انتخاب محصولات پلیمری صنعتی؛
                        با تمرکز بر نیاز واقعی، مشخصات محصول و ایجاد
                        زمینه همکاری حرفه‌ای.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-6">دسترسی سریع</h3>

                    <ul class="flex flex-col gap-4 text-sm">
                        <li><a class="footer-link" href="{{ url('/#home') }}">صفحه اصلی</a></li>
                        <li><a class="footer-link" href="{{ url('/#products') }}">محصولات</a></li>
                        <li><a class="footer-link" href="{{ url('/#about') }}">درباره ما</a></li>
                        <li><a class="footer-link" href="{{ url('/#advantages') }}">مزیت‌های ما</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-6">ارتباط با ما</h3>

                    <ul class="flex flex-col gap-4 text-sm">
                        <li>
                            <a class="footer-link" href="{{ url('/#contact') }}">
                                درخواست همکاری
                            </a>
                        </li>

                        @if(isset($content) && $content?->email)                            <li>
                                <a class="footer-link" href="mailto:{{ $content->email }}" dir="ltr">
                                    {{ $content->email }}
                                </a>
                            </li>
                        @endif

                        @if(isset($content) && $content?->phone)                            <li>
                                <a class="footer-link" href="tel:{{ $content->phone }}" dir="ltr">
                                    تلفن: {{ $content->phone }}
                                </a>
                            </li>
                        @endif

                        @if(isset($content) && $content?->instagram)
                            <li>
                                <a
                                    class="footer-link"
                                    href="https://instagram.com/{{ $content->instagram }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    dir="ltr"
                                >
                                    اینستاگرام
                                </a>
                            </li>
                        @endif

                        @if(isset($content) && $content?->whatsapp)
                            <li>
                                <a
                                    class="footer-link"
                                    href="https://wa.me/{{ $content->whatsapp }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    واتساپ
                                </a>
                            </li>
                        @endif
                    </ul>

                    <p class="footer-note text-[11px] mt-5 leading-6">
                        کافیست یکی از موارد بالا کلیک شود.
                    </p>
                </div>

            </div>

            <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="footer-copy text-xs text-center sm:text-right">
                    © تمامی حقوق این وب‌سایت برای کهن پلیمر البرز محفوظ است.
                </p>

                <a
                    href="{{ url('/#home') }}"
                    class="flex items-center gap-2 text-xs text-[#b5b5b5] hover:text-white transition"
                >
                    بازگشت به بالا <span aria-hidden="true">↑</span>
                </a>
            </div>

        </div>
    </footer>