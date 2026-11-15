@extends('layouts.admin')

@section('content')

<div dir="rtl" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-2">
        <h1 class="kohan-bold text-xl text-[#362727]">
            تنظیمات حساب کاربری
        </h1>

        <p class="text-sm text-[#8B827E]">
            مشاهده مشخصات حساب و تغییر رمز عبور
        </p>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Account Information --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="border-b border-[#EEE8E5] px-5 py-4">
            <h2 class="kohan-bold text-base text-[#362727]">
                مشخصات حساب
            </h2>

            <p class="mt-1 text-xs text-[#8B827E]">
                اطلاعات حساب کاربری شما
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">

            <div>
                <span class="mb-2 block text-xs text-[#8B827E]">
                    نام مدیر
                </span>

                <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $managerData['name'] }}
                </div>
            </div>

            <div>
                <span class="mb-2 block text-xs text-[#8B827E]">
                    آدرس ایمیل
                </span>

                <div dir="ltr" class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] px-4 py-3 text-left text-sm text-[#362727]">
                    {{ $managerData['email'] }}
                </div>
            </div>

            <div>
                <span class="mb-2 block text-xs text-[#8B827E]">
                    نقش کاربری
                </span>

                <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $managerData['role'] }}
                </div>
            </div>

            <div>
                <span class="mb-2 block text-xs text-[#8B827E]">
                    تاریخ عضویت
                </span>

                <div class="rounded-xl border border-[#EEE8E5] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $managerData['created_at'] }}
                </div>
            </div>

        </div>
    </section>

    {{-- Change Password --}}
    <section class="overflow-hidden rounded-2xl border border-[#E8E1DE] bg-white shadow-sm">

        <div class="border-b border-[#EEE8E5] px-5 py-4">
            <h2 class="kohan-bold text-base text-[#362727]">
                تغییر رمز عبور
            </h2>

            <p class="mt-1 text-xs text-[#8B827E]">
                برای تغییر رمز عبور، ابتدا رمز فعلی خود را وارد کنید.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.settings.password.update') }}"
            class="space-y-5 p-5"
        >
            @csrf
            @method('PUT')

            {{-- Current Password --}}
            <div>
                <label for="current_password" class="mb-2 block text-sm text-[#362727]">
                    رمز عبور فعلی
                </label>

                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    autocomplete="current-password"
                    required
                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8D6666] focus:ring-2 focus:ring-[#8D6666]/10"
                >

                @error('current_password')
                    <p class="mt-2 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- New Password --}}
                <div>
                    <label for="password" class="mb-2 block text-sm text-[#362727]">
                        رمز عبور جدید
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8D6666] focus:ring-2 focus:ring-[#8D6666]/10"
                    >

                    @error('password')
                        <p class="mt-2 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-[#8B827E]">
                        رمز عبور باید حداقل ۸ کاراکتر باشد.
                    </p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm text-[#362727]">
                        تکرار رمز عبور جدید
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8D6666] focus:ring-2 focus:ring-[#8D6666]/10"
                    >
                </div>

            </div>

            <div class="flex justify-start border-t border-[#EEE8E5] pt-5">
                <button
                    type="submit"
                    class="inline-flex min-h-[46px] cursor-pointer items-center justify-center rounded-xl bg-[#674040] px-6 text-sm text-white transition hover:bg-[#362727] active:scale-[0.98] kohan-bold"
                >
                    تغییر رمز عبور
                </button>
            </div>

        </form>
    </section>

</div>

@endsection