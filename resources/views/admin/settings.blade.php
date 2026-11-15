@extends('layouts.admin')

@section('content')

@php
    $roleLabels = [
        'super_admin' => 'مدیر کل',
        'admin' => 'مدیر',
    ];

    $joinedAt = $user->created_at
        ? \App\Support\NumberHelper::toPersian(
            \App\Support\DateHelper::toPersian($user->created_at, 'Y/m/d')
        )
        : 'ثبت نشده';
@endphp

<div class="space-y-6" dir="rtl">

    <div>
        <h1 class="kohan-bold text-xl text-[#362727]">تنظیمات حساب کاربری</h1>
        <p class="mt-2 text-sm text-[#8B827E]">
            مشاهده مشخصات حساب و تغییر رمز عبور
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-6">
            <h2 class="kohan-bold text-base text-[#362727]">مشخصات حساب</h2>
            <p class="mt-1 text-xs text-[#8B827E]">
                اطلاعات حساب کاربری شما
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div>
                <label class="mb-2 block text-xs text-[#8B827E]">نام مدیر</label>
                <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $user->name }}
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#8B827E]">ایمیل</label>
                <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $user->email }}
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#8B827E]">نقش کاربری</label>
                <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $roleLabels[$user->role] ?? 'مدیر' }}
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs text-[#8B827E]">تاریخ عضویت</label>
                <div class="rounded-xl border border-[#E8E1DE] bg-[#FAF8F7] px-4 py-3 text-sm text-[#362727]">
                    {{ $joinedAt }}
                </div>
            </div>

        </div>
    </div>

    <div class="rounded-2xl border border-[#E8E1DE] bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-6">
            <h2 class="kohan-bold text-base text-[#362727]">تغییر رمز عبور</h2>
            <p class="mt-1 text-xs text-[#8B827E]">
                برای تغییر رمز عبور، ابتدا رمز فعلی خود را وارد کنید.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.settings.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

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
                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                >

                @error('current_password')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                >

                @error('password')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                    class="w-full rounded-xl border border-[#E8E1DE] bg-white px-4 py-3 text-sm text-[#362727] outline-none transition focus:border-[#8d6666] focus:ring-2 focus:ring-[#8d6666]/10"
                >
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
    </div>

</div>

@endsection