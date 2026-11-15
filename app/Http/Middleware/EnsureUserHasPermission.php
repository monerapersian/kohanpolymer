<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    private const PERMISSIONS = [
        'site_content',
        'products',
        'blog_posts',
        'inquiries',
        'customers',
        'invoices',
        'financial_reports',
        'activity_logs',
    ];

    public function handle(
        Request $request,
        Closure $next,
        ?string $permission = null
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if ($user->role === 'super_admin') {
            return $next($request);
        }

        $permissions = $user->permissions ?? [];

        if (!is_array($permissions)) {
            $permissions = [];
        }

        $validPermissions = array_intersect(
            self::PERMISSIONS,
            $permissions
        );

        if (empty($validPermissions)) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'حساب کاربری شما غیرفعال است. برای فعال‌سازی با مدیر کل تماس بگیرید.',
            ]);
        }

        if (
            $permission !== null &&
            !in_array($permission, $validPermissions, true)
        ) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}