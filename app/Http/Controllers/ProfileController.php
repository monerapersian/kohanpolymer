<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $changedFields = array_keys($user->getDirty());

        $user->save();

        if (! empty($changedFields)) {
            app(ActivityLogger::class)->log(
                action: 'profile_updated',
                description: 'اطلاعات پروفایل کاربر ویرایش شد.',
                subject: $user,
                properties: [
                    'changed_fields' => $changedFields,
                ],
                actor: $user
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        app(ActivityLogger::class)->log(
            action: 'account_deleted',
            description: 'حساب کاربری حذف شد.',
            subject: $user,
            properties: [
                'deleted_user_id' => $user->id,
            ],
            actor: $user
        );

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}