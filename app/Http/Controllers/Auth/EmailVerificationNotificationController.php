<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        app(ActivityLogger::class)->log(
            action: 'verification_link_sent',
            description: 'لینک تأیید آدرس ایمیل مجدداً ارسال شد.',
            actor: $request->user()
        );

        return back()->with('status', 'verification-link-sent');
    }
}