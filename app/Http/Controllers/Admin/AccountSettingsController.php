<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('admin.settings', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'وارد کردن رمز عبور فعلی الزامی است.',
            'current_password.current_password' => 'رمز عبور فعلی صحیح نیست.',
            'password.required' => 'وارد کردن رمز عبور جدید الزامی است.',
            'password.min' => 'رمز عبور جدید باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور با رمز جدید مطابقت ندارد.',
        ]);

        $user = $request->user();

        $user->password = $request->password;
        $user->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'رمز عبور شما با موفقیت تغییر کرد.');
    }
}