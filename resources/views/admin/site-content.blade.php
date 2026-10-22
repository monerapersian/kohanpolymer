@extends('layouts.admin')

@section('title', 'مدیریت محتوای سایت | کهن پلیمر البرز')

@section('content')
    <div class="space-y-6">

        {{-- Page Hero --}}
        <section class="relative overflow-hidden rounded-3xl border border-white/[0.08] bg-[#222222]/80 p-6 shadow-2xl sm:p-8 lg:p-10">
            <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-[#681b21]/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 right-1/3 h-48 w-48 rounded-full bg-emerald-900/20 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5 text-xs text-white/60">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        مدیریت وب‌سایت
                    </div>

                    <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                        محتوا سایت
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/55 sm:text-base">
                        محتوای اصلی وب‌سایت، اطلاعات معرفی مجموعه و راه‌های ارتباطی را از این بخش مدیریت کنید.
                    </p>
                </div>

                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.05]">
                    <svg class="h-8 w-8 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                    </svg>
                </div>
            </div>
        </section>

        {{-- Success Message --}}
        @if (session('success'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/[0.08] px-5 py-4 text-sm text-emerald-200">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-400/20 bg-red-500/[0.08] px-5 py-4 text-sm text-red-200">
                <p class="mb-2 font-bold">لطفاً خطاهای زیر را بررسی کنید:</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Content Tabs --}}
        <section
            class="overflow-hidden rounded-3xl border border-white/[0.08] bg-[#222222]/75 shadow-xl"
            id="site-content-manager"
        >
            <div class="border-b border-white/[0.08] p-3 sm:p-4">
                <div class="grid grid-cols-3 gap-2" role="tablist" aria-label="بخش‌های مدیریت محتوا">
                    <button
                        type="button"
                        id="tab-main"
                        class="content-tab is-active rounded-xl px-3 py-3 text-sm font-bold transition-all duration-200 sm:px-5 kohan-bold cursor-pointer"
                        role="tab"
                        aria-selected="true"
                        aria-controls="panel-main"
                        data-tab="main"
                    >
                        محتوای اصلی
                    </button>

                    <button
                        type="button"
                        id="tab-products"
                        class="content-tab rounded-xl px-3 py-3 text-sm font-bold transition-all duration-200 sm:px-5 kohan-bold cursor-pointer"
                        role="tab"
                        aria-selected="false"
                        aria-controls="panel-products"
                        data-tab="products"
                    >
                        محصولات
                    </button>

                    <button
                        type="button"
                        id="tab-blog"
                        class="content-tab rounded-xl px-3 py-3 text-sm font-bold transition-all duration-200 sm:px-5 kohan-bold cursor-pointer"
                        role="tab"
                        aria-selected="false"
                        aria-controls="panel-blog"
                        data-tab="blog"
                    >
                        بلاگ
                    </button>
                </div>
            </div>

            {{-- Main Content Tab --}}
            <div
                id="panel-main"
                class="content-panel p-5 sm:p-7 lg:p-8"
                role="tabpanel"
                aria-labelledby="tab-main"
                data-panel="main"
            >
                <form action="{{ route('admin.site-content.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div>
                        <div class="mb-5">
                            <h2 class="text-lg font-extrabold text-white sm:text-xl">
                                محتوای اصلی وب‌سایت
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-white/45">
                                متن‌ها و اطلاعات ارتباطی نمایش‌داده‌شده در سایت عمومی را ویرایش کنید.
                            </p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="hero_slogan" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    شعار هیرو
                                </label>
                                <textarea
                                    id="hero_slogan"
                                    name="hero_slogan"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="در کهن پلیمر البرز، به جزئیاتی فکر می‌کنیم که عملکرد یک مجموعه را متحول می‌کنند؛ راهکارهای پلیمری برای دوام بیشتر، عملکرد بهتر و حرکت رو به جلوی صنعت."
                                    class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3.5 text-sm leading-7 text-white outline-none transition placeholder:text-white/25 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                                >{{ old('hero_slogan', $content?->hero_slogan) }}</textarea>
                                <p class="mt-2 text-xs text-white/35">متن اصلی بخش ابتدایی صفحه اصلی سایت.</p>
                            </div>

                            <div>
                                <label for="about_text" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    درباره کهن پلیمر
                                </label>
                                <textarea
                                    id="about_text"
                                    name="about_text"
                                    rows="6"
                                    maxlength="10000"
                                    placeholder="کهن پلیمر البرز با تمرکز بر محصولات پلیمری صنعتی، در مسیر فراهم‌کردن راهکارهای متناسب با نیاز مشتریان حرکت می‌کند. هدف ما ایجاد ارتباطی روشن میان نیاز، مشخصات محصول و انتخاب آگاهانه است."
                                    class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3.5 text-sm leading-7 text-white outline-none transition placeholder:text-white/25 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                                >{{ old('about_text', $content?->about_text) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/[0.08] pt-7">
                        <div class="mb-5">
                            <h2 class="text-lg font-extrabold text-white">راه‌های ارتباطی</h2>
                            <p class="mt-2 text-sm leading-6 text-white/45">
                                اطلاعات تماس مجموعه را وارد کنید تا در بخش‌های مرتبط سایت قابل استفاده باشند.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    شماره تماس
                                </label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    maxlength="16"
                                    value="{{ old('phone', $content?->phone) }}"
                                    placeholder="02612345678"
                                    dir="ltr"
                                    class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3.5 text-left text-sm text-white outline-none transition placeholder:text-white/25 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                                >
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    ایمیل
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    maxlength="150"
                                    value="{{ old('email', $content?->email) }}"
                                    placeholder="info@example.com"
                                    dir="ltr"
                                    class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3.5 text-left text-sm text-white outline-none transition placeholder:text-white/25 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                                >
                            </div>

                            <div>
                                <label for="instagram" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    آیدی اینستاگرام
                                </label>

                                <div class="flex overflow-hidden rounded-2xl border border-white/10 bg-black/20 transition focus-within:border-[#681b21] focus-within:ring-2 focus-within:ring-[#681b21]/20" dir="ltr">
                                    <span class="flex shrink-0 items-center border-r border-white/10 bg-white/[0.04] px-3 text-xs text-white/45 sm:px-4">
                                        instagram.com/
                                    </span>
                                    <input
                                        type="text"
                                        id="instagram"
                                        name="instagram"
                                        maxlength="150"
                                        value="{{ old('instagram', $content?->instagram) }}"
                                        placeholder="kohanpolymer"
                                        class="min-w-0 w-full bg-transparent px-3 py-3.5 text-sm text-white outline-none placeholder:text-white/25 sm:px-4"
                                    >
                                </div>
                                <p class="mt-2 text-xs text-white/35">فقط آیدی را وارد کنید؛ نیازی به نوشتن آدرس کامل نیست.</p>
                            </div>

                            <div>
                                <label for="whatsapp" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                                    شماره واتساپ
                                </label>

                                <div class="flex overflow-hidden rounded-2xl border border-white/10 bg-black/20 transition focus-within:border-[#681b21] focus-within:ring-2 focus-within:ring-[#681b21]/20" dir="ltr">
                                    <span class="flex shrink-0 items-center border-r border-white/10 bg-white/[0.04] px-3 text-xs text-white/45 sm:px-4">
                                        wa.me/
                                    </span>
                                    <input
                                        type="text"
                                        id="whatsapp"
                                        name="whatsapp"
                                        maxlength="50"
                                        value="{{ old('whatsapp', $content?->whatsapp) }}"
                                        placeholder="989121234567"
                                        inputmode="tel"
                                        class="min-w-0 w-full bg-transparent px-3 py-3.5 text-sm text-white outline-none placeholder:text-white/25 sm:px-4"
                                    >
                                </div>
                                <p class="mt-2 text-xs text-white/35">شماره را همراه کد کشور و بدون صفر ابتدایی وارد کنید.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-white/[0.08] pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-6 text-white/35">
                            تغییرات پس از ذخیره در پایگاه داده ثبت می‌شوند.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#681b21] px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-[#681b21]/20 transition hover:bg-[#7c242b] focus:outline-none focus:ring-2 focus:ring-[#a64b52] focus:ring-offset-2 focus:ring-offset-[#222222] kohan-bold cursor-pointer"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            بروزرسانی محتوا
                        </button>
                    </div>
                </form>
            </div>

            {{-- Products Tab --}}
            <div
                id="panel-products"
                class="content-panel hidden p-5 sm:p-7 lg:p-8"
                role="tabpanel"
                aria-labelledby="tab-products"
                data-panel="products"
                hidden
            >
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-white sm:text-xl">
                            مدیریت محصولات
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-white/45">
                            محصولات سایت را ثبت، ویرایش و مدیریت کنید.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="add-product-button"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#7c242b] focus:outline-none focus:ring-2 focus:ring-[#a64b52] kohan-bold cursor-pointer"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        محصول جدید
                    </button>
                </div>

                <form
                    action="{{ route('admin.site-content.edit') }}"
                    method="GET"
                    class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_180px_auto]"
                >
                    <input type="hidden" name="tab" value="products">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جست‌وجوی نام محصول..."
                        class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                    >

                    <select
                        name="sort"
                        class="w-full rounded-xl border border-white/10 bg-[#222222] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21] cursor-pointer"
                    >
                        <option value="newest" @selected(request('sort', 'newest') === 'newest')>
                            جدیدترین
                        </option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>
                            قدیمی‌ترین
                        </option>
                    </select>

                    <button
                        type="submit"
                        class="rounded-xl border border-white/10 bg-white/[0.06] px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10 kohan-bold cursor-pointer"
                    >
                        اعمال فیلتر
                    </button>
                </form>

                <div class="overflow-x-auto rounded-2xl border border-white/[0.08]">
                    <table class="w-full min-w-[650px] text-right text-sm">
                        <thead class="bg-white/[0.04] text-white/55 kohan-bold">
                            <tr>
                                <th class="px-4 py-4 font-bold">ردیف</th>
                                <th class="px-4 py-4 font-bold">نام محصول</th>
                                <th class="px-4 py-4 font-bold">تاریخ انتشار</th>
                                <th class="px-4 py-4 font-bold">وضعیت</th>
                                <th class="px-4 py-4 font-bold">عملیات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.06]">
                            @forelse ($products as $product)
                                <tr class="transition hover:bg-white/[0.03]">
                                    <td class="px-4 py-4 text-white/45">
                                        {{ $products->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-4 py-4 font-bold text-white kohan-bold">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-4 py-4 text-white/60">
                                        {{ $product->published_at?->format('Y/m/d') ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        @if ($product->status === 'published')
                                            <span class="rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-bold text-emerald-400 kohan-bold">
                                                منتشر شده
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-300 kohan-bold">
                                                پیش‌نویس
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="edit-product-button inline-flex cursor-pointer items-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-xs font-bold text-white/75 transition hover:border-[#681b21]/60 hover:bg-[#681b21]/15 hover:text-white kohan-bold"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-description="{{ $product->description }}"
                                                data-status="{{ $product->status }}"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16 4 4 4M4 20l4-.8L19.2 8a2.26 2.26 0 0 0-3.2-3.2L4.8 16 4 20Z"/>
                                                </svg>
                                                ویرایش
                                            </button>

                                            <button
                                                type="button"
                                                class="delete-product-button inline-flex cursor-pointer items-center gap-2 rounded-lg border border-red-400 px-3 py-2 text-xs font-bold text-red-300 transition hover:border-red-400/40 hover:bg-red-500/60 hover:text-red-200 kohan-bold"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6m4-6v6M5 7l1 13h12l1-13M9 7V4h6v3"/>
                                                </svg>
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-white/45">
                                        @if (request()->filled('search'))
                                            محصولی با این نام پیدا نشد.
                                        @else
                                            هنوز محصولی ثبت نشده است.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="mt-5">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

            {{-- Blog Tab --}}
            <div
                id="panel-blog"
                class="content-panel hidden p-5 sm:p-7 lg:p-8"
                role="tabpanel"
                aria-labelledby="tab-blog"
                data-panel="blog"
                hidden
            >
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-white sm:text-xl kohan-bold">
                            مدیریت مقالات
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-white/45">
                            مقالات سایت را ثبت، ویرایش و مدیریت کنید.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="add-blog-post-button"
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#681b21] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#7c242b] focus:outline-none focus:ring-2 focus:ring-[#a64b52] kohan-bold"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        مقاله جدید
                    </button>
                </div>

                <form
                    action="{{ route('admin.site-content.edit') }}"
                    method="GET"
                    class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_180px_auto]"
                >
                    <input type="hidden" name="tab" value="blog">

                    <input
                        type="search"
                        name="blog_search"
                        value="{{ request('blog_search') }}"
                        placeholder="جست‌وجوی عنوان مقاله..."
                        class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                    >

                    <select
                        name="blog_sort"
                        class="w-full cursor-pointer rounded-xl border border-white/10 bg-[#222222] px-4 py-3 text-sm text-white outline-none focus:border-[#681b21]"
                    >
                        <option value="newest" @selected(request('blog_sort', 'newest') === 'newest')>
                            جدیدترین
                        </option>
                        <option value="oldest" @selected(request('blog_sort') === 'oldest')>
                            قدیمی‌ترین
                        </option>
                    </select>

                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl border border-white/10 bg-white/[0.06] px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10 kohan-bold"
                    >
                        اعمال فیلتر
                    </button>
                </form>

                <div class="overflow-x-auto rounded-2xl border border-white/[0.08]">
                    <table class="w-full min-w-[750px] text-right text-sm">
                        <thead class="bg-white/[0.04] text-white/55 kohan-bold">
                            <tr>
                                <th class="px-4 py-4 font-bold">ردیف</th>
                                <th class="px-4 py-4 font-bold">عنوان مقاله</th>
                                <th class="px-4 py-4 font-bold">تاریخ انتشار</th>
                                <th class="px-4 py-4 font-bold">وضعیت</th>
                                <th class="px-4 py-4 font-bold">عملیات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.06]">
                            @forelse ($blogPosts as $blogPost)
                                <tr class="transition hover:bg-white/[0.03]">
                                    <td class="px-4 py-4 text-white/45">
                                        {{ $blogPosts->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-4 py-4 font-bold text-white kohan-bold">
                                        {{ $blogPost->title }}
                                    </td>

                                    <td class="px-4 py-4 text-white/60">
                                        {{ $blogPost->published_at?->format('Y/m/d') ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        @if ($blogPost->status === 'published')
                                            <span class="rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-bold text-emerald-400 kohan-bold">
                                                منتشر شده
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-300 kohan-bold">
                                                پیش‌نویس
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="edit-blog-post-button inline-flex cursor-pointer items-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-xs font-bold text-white/75 transition hover:border-[#681b21]/60 hover:bg-[#681b21]/15 hover:text-white kohan-bold"
                                                data-id="{{ $blogPost->id }}"
                                                data-title="{{ $blogPost->title }}"
                                                data-summary="{{ $blogPost->summary }}"
                                                data-body="{{ $blogPost->body }}"
                                                data-status="{{ $blogPost->status }}"
                                            >
                                                ویرایش
                                            </button>

                                            <button
                                                type="button"
                                                class="delete-blog-post-button inline-flex cursor-pointer items-center gap-2 rounded-lg border border-red-400 px-3 py-2 text-xs font-bold text-red-300 transition hover:bg-red-500/20 hover:text-red-200 kohan-bold"
                                                data-id="{{ $blogPost->id }}"
                                                data-title="{{ $blogPost->title }}"
                                            >
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-white/45">
                                        @if (request()->filled('blog_search'))
                                            مقاله‌ای با این عنوان پیدا نشد.
                                        @else
                                            هنوز مقاله‌ای ثبت نشده است.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($blogPosts->hasPages())
                    <div class="mt-5">
                        {{ $blogPosts->links() }}
                    </div>
                @endif
            </div>
        </section>

    </div>

    {{-- Product Modal --}}
    <div
        id="product-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="product-modal-title"
        >
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-7">
                <div>
                    <h2 id="product-modal-title" class="text-lg font-extrabold text-white kohan-bold">
                        افزودن محصول جدید
                    </h2>
                    <p id="product-modal-subtitle" class="mt-1 text-xs text-white/45">
                        اطلاعات محصول را وارد کنید.
                    </p>
                </div>

                <button
                    type="button"
                    id="close-product-modal"
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-white/60 transition hover:bg-white/10 hover:text-white cursor-pointer"
                    aria-label="بستن پنجره"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18 6-12 12M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form
                id="product-form"
                action="{{ route('admin.products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <input type="hidden" name="_product_form" value="1">
                <input type="hidden" id="product-method" name="_method" value="POST">

                <div class="space-y-5 px-5 py-6 sm:px-7">
                    <div>
                        <label for="product-name" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            نام محصول <span class="text-red-400">*</span>
                        </label>

                        <input
                            type="text"
                            id="product-name"
                            name="name"
                            required
                            maxlength="255"
                            value="{{ old('_product_form') ? old('name') : '' }}"
                            placeholder="نام محصول را وارد کنید"
                            class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                        >
                    </div>

                    <div>
                        <label for="product-image" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            تصویر محصول
                        </label>

                        <input
                            type="file"
                            id="product-image"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full cursor-pointer rounded-xl border border-white/10 bg-black/20 text-sm text-white/70 file:ml-4 file:border-0 file:bg-white/[0.06] file:px-4 file:py-3 file:font-bold file:text-white hover:file:bg-white/10 kohan-bold"
                        >

                        <p class="mt-2 text-xs text-white/40">
                            فرمت‌های مجاز: JPG، PNG و WebP؛ حداکثر حجم ۵ مگابایت.
                        </p>

                        <p id="product-image-hint" class="mt-1 hidden text-xs text-white/45">
                            اگر تصویر جدید انتخاب نکنید، تصویر فعلی حفظ می‌شود.
                        </p>
                    </div>

                    <div>
                        <label for="product-description" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            توضیحات محصول
                        </label>

                        <textarea
                            id="product-description"
                            name="description"
                            rows="5"
                            placeholder="توضیحات محصول را وارد کنید..."
                            class="w-full resize-y rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm leading-7 text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                        >{{ old('_product_form') ? old('description') : '' }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-white/10 px-5 py-4 sm:flex-row sm:justify-between sm:px-7">
                    <button
                        type="button"
                        id="cancel-product-modal"
                        class="cursor-pointer rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-white/70 transition hover:bg-white/[0.06] hover:text-white kohan-bold"
                    >
                        انصراف
                    </button>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            name="status"
                            value="draft"
                            class="cursor-pointer rounded-xl border border-white/15 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/[0.06] kohan-bold"
                        >
                            ذخیره پیش‌نویس
                        </button>

                        <button
                            type="submit"
                            name="status"
                            value="published"
                            class="cursor-pointer rounded-xl bg-[#681b21] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#7c242b] kohan-bold"
                        >
                            انتشار محصول
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Blog Post Modal --}}
    <div
        id="blog-post-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="my-auto w-full max-w-3xl overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="blog-post-modal-title"
        >
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-7">
                <div>
                    <h2 id="blog-post-modal-title" class="text-lg font-extrabold text-white kohan-bold">
                        افزودن مقاله جدید
                    </h2>
                    <p id="blog-post-modal-subtitle" class="mt-1 text-xs text-white/45">
                        اطلاعات مقاله را وارد کنید.
                    </p>
                </div>

                <button
                    type="button"
                    id="close-blog-post-modal"
                    class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl text-white/60 transition hover:bg-white/10 hover:text-white"
                    aria-label="بستن پنجره"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18 6-12 12M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form
                id="blog-post-form"
                action="{{ route('admin.blog-posts.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <input type="hidden" name="_blog_form" value="1">
                <input type="hidden" id="blog-post-method" name="_method" value="POST">

                <div class="max-h-[70vh] space-y-5 overflow-y-auto px-5 py-6 sm:px-7">
                    <div>
                        <label for="blog-post-title" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            عنوان مقاله <span class="text-red-400">*</span>
                        </label>

                        <input
                            type="text"
                            id="blog-post-title"
                            name="title"
                            required
                            maxlength="255"
                            value="{{ old('_blog_form') ? old('title') : '' }}"
                            placeholder="عنوان مقاله را وارد کنید"
                            class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                        >
                    </div>

                    <div>
                        <label for="blog-post-summary" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            خلاصه مقاله
                        </label>

                        <textarea
                            id="blog-post-summary"
                            name="summary"
                            rows="3"
                            placeholder="خلاصه کوتاهی از مقاله بنویسید..."
                            class="w-full resize-y rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm leading-7 text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                        >{{ old('_blog_form') ? old('summary') : '' }}</textarea>
                    </div>

                    <div>
                        <label for="blog-post-image" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            تصویر مقاله
                        </label>

                        <input
                            type="file"
                            id="blog-post-image"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full cursor-pointer rounded-xl border border-white/10 bg-black/20 text-sm text-white/70 file:ml-4 file:border-0 file:bg-white/[0.06] file:px-4 file:py-3 file:font-bold file:text-white hover:file:bg-white/10 kohan-bold"
                        >

                        <p class="mt-2 text-xs text-white/40">
                            فرمت‌های مجاز: JPG، PNG و WebP؛ حداکثر حجم ۵ مگابایت.
                        </p>

                        <p id="blog-post-image-hint" class="mt-1 hidden text-xs text-white/45">
                            اگر تصویر جدید انتخاب نکنید، تصویر فعلی حفظ می‌شود.
                        </p>
                    </div>

                    <div>
                        <label for="blog-post-body" class="mb-2 block text-sm font-bold text-white/80 kohan-bold">
                            متن کامل مقاله <span class="text-red-400">*</span>
                        </label>

                        <textarea
                            id="blog-post-body"
                            name="body"
                            rows="10"
                            required
                            placeholder="متن کامل مقاله را وارد کنید..."
                            class="w-full resize-y rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-sm leading-7 text-white outline-none transition placeholder:text-white/30 focus:border-[#681b21] focus:ring-2 focus:ring-[#681b21]/20"
                        >{{ old('_blog_form') ? old('body') : '' }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-white/10 px-5 py-4 sm:flex-row sm:justify-between sm:px-7">
                    <button
                        type="button"
                        id="cancel-blog-post-modal"
                        class="cursor-pointer rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-white/70 transition hover:bg-white/[0.06] hover:text-white kohan-bold"
                    >
                        انصراف
                    </button>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            name="status"
                            value="draft"
                            class="cursor-pointer rounded-xl border border-white/15 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/[0.06] kohan-bold"
                        >
                            ذخیره پیش‌نویس
                        </button>

                        <button
                            type="submit"
                            name="status"
                            value="published"
                            class="cursor-pointer rounded-xl bg-[#681b21] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#7c242b] kohan-bold"
                        >
                            انتشار مقاله
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Product Modal --}}
    <div
        id="delete-product-modal"
        class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="w-full max-w-md overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-product-modal-title"
        >
            <div class="p-6 sm:p-7">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl border border-red-400/20 bg-red-500/10 text-red-300">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.9 18a2 2 0 0 0 1.7 3h16.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                    </svg>
                </div>

                <h2
                    id="delete-product-modal-title"
                    class="text-lg font-extrabold text-white kohan-bold"
                >
                    حذف محصول
                </h2>

                <p class="mt-3 text-sm leading-7 text-white/60">
                    آیا از حذف محصول
                    <span id="delete-product-name" class="font-bold text-white kohan-bold"></span>
                    مطمئن هستید؟ این عملیات قابل بازگشت نیست.
                </p>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 px-6 py-4 sm:flex-row sm:justify-end sm:px-7">
                <button
                    type="button"
                    id="cancel-delete-product"
                    class="cursor-pointer rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-white/70 transition hover:bg-white/[0.06] hover:text-white kohan-bold"
                >
                    انصراف
                </button>

                <form id="delete-product-form" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-xl bg-red-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-red-700 kohan-bold"
                    >
                        بله، حذف شود
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Blog Post Modal --}}
    <div
        id="delete-blog-post-modal"
        class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
        aria-hidden="true"
    >
        <div
            class="w-full max-w-md overflow-hidden rounded-2xl border border-white/10 bg-[#222222] shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-blog-post-title"
        >
            <div class="p-6">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-red-500/10 text-red-400">
                    <svg
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.9 1.9 18a2 2 0 0 0 1.7 3h16.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"
                        />
                    </svg>
                </div>

                <h2
                    id="delete-blog-post-title"
                    class="text-lg font-extrabold text-white kohan-bold"
                >
                    حذف مقاله
                </h2>

                <p class="mt-3 text-sm leading-7 text-white/60">
                    آیا از حذف مقاله
                    <span
                        id="delete-blog-post-name"
                        class="font-bold text-white"
                    ></span>
                    مطمئن هستید؟ این عملیات قابل بازگشت نیست.
                </p>
            </div>

            <form
                id="delete-blog-post-form"
                action=""
                method="POST"
                class="flex items-center justify-end gap-3 border-t border-white/10 px-6 py-4"
            >
                @csrf
                @method('DELETE')

                <button
                    type="button"
                    id="cancel-delete-blog-post"
                    class="cursor-pointer rounded-xl border border-white/10 px-4 py-2.5 text-sm font-bold text-white/70 transition hover:bg-white/[0.06] hover:text-white kohan-bold"
                >
                    انصراف
                </button>

                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 kohan-bold"
                >
                    بله، حذف شود
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #site-content-manager .content-tab {
            color: rgba(255, 255, 255, 0.5);
            background: transparent;
            border: 1px solid transparent;
        }

        #site-content-manager .content-tab:hover {
            color: rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.035);
        }

        #site-content-manager .content-tab.is-active {
            color: #fff;
            background: rgba(104, 27, 33, 0.25);
            border-color: rgba(166, 75, 82, 0.28);
            box-shadow: inset 0 0 0 1px rgba(104, 27, 33, 0.12);
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const container = document.getElementById('site-content-manager');

            if (!container) return;

            const tabs = Array.from(container.querySelectorAll('[data-tab]'));
            const panels = Array.from(container.querySelectorAll('[data-panel]'));

            if (!tabs.length || !panels.length) return;

            const activateTab = (tabName) => {
                tabs.forEach((tab) => {
                    const isActive = tab.dataset.tab === tabName;

                    tab.classList.toggle('is-active', isActive);
                    tab.setAttribute('aria-selected', String(isActive));
                    tab.tabIndex = isActive ? 0 : -1;
                });

                panels.forEach((panel) => {
                    const isActive = panel.dataset.panel === tabName;

                    panel.hidden = !isActive;
                    panel.classList.toggle('hidden', !isActive);
                });
            };

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    activateTab(tab.dataset.tab);
                });

                tab.addEventListener('keydown', (event) => {
                    let nextIndex = index;

                    if (event.key === 'ArrowLeft') {
                        nextIndex = (index + 1) % tabs.length;
                    } else if (event.key === 'ArrowRight') {
                        nextIndex = (index - 1 + tabs.length) % tabs.length;
                    } else if (event.key === 'Home') {
                        nextIndex = 0;
                    } else if (event.key === 'End') {
                        nextIndex = tabs.length - 1;
                    } else {
                        return;
                    }

                    event.preventDefault();

                    tabs[nextIndex].focus();
                    activateTab(tabs[nextIndex].dataset.tab);
                });
            });

            const requestedTab = @json(
                session('active_tab')
                ?? (
                    $errors->any() && old('_blog_form')
                        ? 'blog'
                        : (
                            $errors->any() && old('_product_form')
                                ? 'products'
                                : request('tab', 'main')
                        )
                )
            );

            const validTabs = tabs.map((tab) => tab.dataset.tab);

            activateTab(
                validTabs.includes(requestedTab)
                    ? requestedTab
                    : 'main'
            );
        })();
    </script>

    <script>
        (() => {
            const modal = document.getElementById('product-modal');
            const openButton = document.getElementById('add-product-button');
            const closeButton = document.getElementById('close-product-modal');
            const cancelButton = document.getElementById('cancel-product-modal');

            const form = document.getElementById('product-form');
            const methodInput = document.getElementById('product-method');
            const nameInput = document.getElementById('product-name');
            const descriptionInput = document.getElementById('product-description');
            const title = document.getElementById('product-modal-title');
            const subtitle = document.getElementById('product-modal-subtitle');
            const imageHint = document.getElementById('product-image-hint');

            if (
                !modal ||
                !openButton ||
                !closeButton ||
                !cancelButton ||
                !form ||
                !methodInput ||
                !nameInput ||
                !descriptionInput ||
                !title
            ) {
                return;
            }

            const storeUrl = @json(route('admin.products.store'));
            const updateUrlTemplate = @json(route('admin.products.update', ['product' => '__PRODUCT_ID__']));

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                window.requestAnimationFrame(() => {
                    nameInput.focus();
                });
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');

                openButton.focus();
            };

            const resetToCreateMode = () => {
                form.action = storeUrl;
                methodInput.value = 'POST';
                nameInput.value = '';
                descriptionInput.value = '';

                const imageInput = document.getElementById('product-image');

                if (imageInput) {
                    imageInput.value = '';
                }

                title.textContent = 'افزودن محصول جدید';
                subtitle.textContent = 'اطلاعات محصول را وارد کنید.';

                if (imageHint) {
                    imageHint.classList.add('hidden');
                }

                form.querySelectorAll('button[type="submit"]').forEach((button) => {
                    button.classList.remove('hidden');
                });
            };

            openButton.addEventListener('click', () => {
                resetToCreateMode();
                openModal();
            });

            document.querySelectorAll('.edit-product-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;

                    if (!productId) {
                        return;
                    }

                    form.action = updateUrlTemplate.replace(
                        '__PRODUCT_ID__',
                        encodeURIComponent(productId)
                    );

                    methodInput.value = 'PUT';
                    nameInput.value = button.dataset.name || '';
                    descriptionInput.value = button.dataset.description || '';

                    title.textContent = 'ویرایش محصول';
                    subtitle.textContent = 'اطلاعات محصول را ویرایش کنید.';

                    if (imageHint) {
                        imageHint.classList.remove('hidden');
                    }

                    form.querySelectorAll('button[type="submit"]').forEach((submitButton) => {
                        submitButton.classList.remove('hidden');
                    });

                    openModal();
                });
            });

            closeButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });

            const shouldReopenModal = @json(
                $errors->any() && old('_product_form')
            );

            if (shouldReopenModal) {
                openModal();
            }
        })();
    </script>

    <!-- Blog-post-modal -->
    <script>
        (() => {
            const modal = document.getElementById('blog-post-modal');
            const openButton = document.getElementById('add-blog-post-button');
            const closeButton = document.getElementById('close-blog-post-modal');
            const cancelButton = document.getElementById('cancel-blog-post-modal');
            const form = document.getElementById('blog-post-form');
            const methodInput = document.getElementById('blog-post-method');
            const titleInput = document.getElementById('blog-post-title');
            const summaryInput = document.getElementById('blog-post-summary');
            const bodyInput = document.getElementById('blog-post-body');
            const imageInput = document.getElementById('blog-post-image');
            const modalTitle = document.getElementById('blog-post-modal-title');
            const modalSubtitle = document.getElementById('blog-post-modal-subtitle');
            const imageHint = document.getElementById('blog-post-image-hint');

            if (
                !modal ||
                !openButton ||
                !closeButton ||
                !cancelButton ||
                !form ||
                !methodInput ||
                !titleInput ||
                !summaryInput ||
                !bodyInput ||
                !imageInput ||
                !modalTitle
            ) {
                return;
            }

            const storeUrl = @json(route('admin.blog-posts.store'));

            const updateUrlTemplate = @json(
                route('admin.blog-posts.update', ['blogPost' => '__BLOG_POST_ID__'])
            );

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                window.requestAnimationFrame(() => titleInput.focus());
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                openButton.focus();
            };

            const resetToCreateMode = () => {
                form.action = storeUrl;
                methodInput.value = 'POST';

                titleInput.value = '';
                summaryInput.value = '';
                bodyInput.value = '';
                imageInput.value = '';

                modalTitle.textContent = 'افزودن مقاله جدید';
                modalSubtitle.textContent = 'اطلاعات مقاله را وارد کنید.';

                if (imageHint) {
                    imageHint.classList.add('hidden');
                }
            };

            openButton.addEventListener('click', () => {
                resetToCreateMode();
                openModal();
            });

            document.querySelectorAll('.edit-blog-post-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const postId = button.dataset.id;

                    if (!postId) {
                        return;
                    }

                    form.action = updateUrlTemplate.replace(
                        '__BLOG_POST_ID__',
                        encodeURIComponent(postId)
                    );

                    methodInput.value = 'PUT';

                    titleInput.value = button.dataset.title || '';
                    summaryInput.value = button.dataset.summary || '';
                    bodyInput.value = button.dataset.body || '';
                    imageInput.value = '';

                    modalTitle.textContent = 'ویرایش مقاله';
                    modalSubtitle.textContent = 'اطلاعات مقاله را ویرایش کنید.';

                    if (imageHint) {
                        imageHint.classList.remove('hidden');
                    }

                    openModal();
                });
            });

            closeButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });

            const shouldReopenModal = @json(
                $errors->any() && old('_blog_form')
            );

            if (shouldReopenModal) {
                openModal();
            }
        })();
    </script>

    <!-- Delete-product-modal -->
    <script>
        (() => {
            const modal = document.getElementById('delete-product-modal');
            const nameElement = document.getElementById('delete-product-name');
            const deleteForm = document.getElementById('delete-product-form');
            const cancelButton = document.getElementById('cancel-delete-product');

            if (!modal || !nameElement || !deleteForm || !cancelButton) {
                return;
            }

            const deleteUrlTemplate = @json(
                route('admin.products.destroy', ['product' => '__PRODUCT_ID__'])
            );

            let lastFocusedElement = null;

            const openModal = (button) => {
                const productId = button.dataset.id;
                const productName = button.dataset.name || 'این محصول';

                if (!productId) {
                    return;
                }

                lastFocusedElement = button;

                nameElement.textContent = productName;

                deleteForm.action = deleteUrlTemplate.replace(
                    '__PRODUCT_ID__',
                    encodeURIComponent(productId)
                );

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                cancelButton.focus();
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');

                lastFocusedElement?.focus();
            };

            document.querySelectorAll('.delete-product-button').forEach((button) => {
                button.addEventListener('click', () => {
                    openModal(button);
                });
            });

            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });
        })();
    </script>

    <!-- Delete-blog-modal -->
    <script>
        (() => {
            const modal = document.getElementById('delete-blog-post-modal');
            const form = document.getElementById('delete-blog-post-form');
            const postName = document.getElementById('delete-blog-post-name');
            const cancelButton = document.getElementById('cancel-delete-blog-post');

            if (!modal || !form || !postName || !cancelButton) {
                return;
            }

            const deleteUrlTemplate = @json(
                route('admin.blog-posts.destroy', ['blogPost' => '__BLOG_POST_ID__'])
            );

            let activeButton = null;

            const openModal = (button) => {
                const postId = button.dataset.id;

                if (!postId) {
                    return;
                }

                activeButton = button;
                form.action = deleteUrlTemplate.replace(
                    '__BLOG_POST_ID__',
                    encodeURIComponent(postId)
                );

                postName.textContent = button.dataset.title || 'این مقاله';

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                cancelButton.focus();
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');

                if (activeButton) {
                    activeButton.focus();
                    activeButton = null;
                }
            };

            document.querySelectorAll('.delete-blog-post-button').forEach((button) => {
                button.addEventListener('click', () => openModal(button));
            });

            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });
        })();
    </script>
@endpush