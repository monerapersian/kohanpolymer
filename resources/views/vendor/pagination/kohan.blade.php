@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();

        $startPage = max(1, $currentPage - 2);
        $endPage = min($lastPage, $currentPage + 2);

        $toPersian = fn ($value) => strtr((string) $value, [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ]);
    @endphp

    <div class="border-t border-[#E8E1DE] bg-[#FAF8F7] px-4 py-5 sm:px-6">
        <div class="flex flex-col items-center justify-between gap-5 sm:flex-row">

            {{-- Results information --}}
            <div class="text-center text-xs text-[#8D7771] sm:text-right">
                نمایش
                <span class="font-bold text-[#674040]">
                    {{ $toPersian($paginator->firstItem()) }}
                </span>
                تا
                <span class="font-bold text-[#674040]">
                    {{ $toPersian($paginator->lastItem()) }}
                </span>
                از مجموع
                <span class="font-bold text-[#674040]">
                    {{ $toPersian($paginator->total()) }}
                </span>
                سابقه فعالیت
            </div>

            {{-- Pagination buttons --}}
            <nav aria-label="صفحه‌بندی سوابق فعالیت"
                 class="flex items-center gap-1.5"
                 dir="rtl">

                {{-- Previous page --}}
                @if ($paginator->onFirstPage())
                    <span class="flex h-10 items-center gap-1.5 rounded-xl border border-[#E8E1DE] bg-white px-3 text-xs text-[#C5B9B4]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        قبلی
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                       rel="prev"
                       class="flex h-10 items-center gap-1.5 rounded-xl border border-[#E8E1DE] bg-white px-3 text-xs font-medium text-[#674040] transition hover:border-[#8D6666] hover:bg-[#F4F0EE]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        قبلی
                    </a>
                @endif

                {{-- First page --}}
                @if ($startPage > 1)
                    <a href="{{ $paginator->url(1) }}"
                       class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#E8E1DE] bg-white px-3 text-sm font-medium text-[#674040] transition hover:border-[#8D6666] hover:bg-[#F4F0EE]">
                        {{ $toPersian(1) }}
                    </a>

                    @if ($startPage > 2)
                        <span class="px-1 text-sm text-[#A9958E]">...</span>
                    @endif
                @endif

                {{-- Page numbers --}}
                @foreach ($paginator->getUrlRange($startPage, $endPage) as $page => $url)
                    @if ($page == $currentPage)
                        <span aria-current="page"
                              class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#674040] bg-[#674040] px-3 text-sm font-bold text-white shadow-sm">
                            {{ $toPersian($page) }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#E8E1DE] bg-white px-3 text-sm font-medium text-[#674040] transition hover:border-[#8D6666] hover:bg-[#F4F0EE]">
                            {{ $toPersian($page) }}
                        </a>
                    @endif
                @endforeach

                {{-- Last page --}}
                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <span class="px-1 text-sm text-[#A9958E]">...</span>
                    @endif

                    <a href="{{ $paginator->url($lastPage) }}"
                       class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#E8E1DE] bg-white px-3 text-sm font-medium text-[#674040] transition hover:border-[#8D6666] hover:bg-[#F4F0EE]">
                        {{ $toPersian($lastPage) }}
                    </a>
                @endif

                {{-- Next page --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                       rel="next"
                       class="flex h-10 items-center gap-1.5 rounded-xl border border-[#E8E1DE] bg-white px-3 text-xs font-medium text-[#674040] transition hover:border-[#8D6666] hover:bg-[#F4F0EE]">
                        بعدی
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @else
                    <span class="flex h-10 items-center gap-1.5 rounded-xl border border-[#E8E1DE] bg-white px-3 text-xs text-[#C5B9B4]">
                        بعدی
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @endif

            </nav>
        </div>
    </div>
@endif