<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display and filter administrative activity logs.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'action' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ]);

        $query = ActivityLog::query()
            ->with('user');

        // Search across safe activity fields.
        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('actor_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhere('subject_id', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter by administrator.
        if (!empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        // Filter by activity type.
        if (!empty($validated['action'])) {
            $query->where('action', $validated['action']);
        }

        // Filter by date range.
        if (!empty($validated['from'])) {
            $query->whereDate('created_at', '>=', $validated['from']);
        }

        if (!empty($validated['to'])) {
            $query->whereDate('created_at', '<=', $validated['to']);
        }

        $activities = $query
            ->latest('created_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        // Get administrators who have activity records.
        $userIds = ActivityLog::query()
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        $users = User::query()
            ->whereIn('id', $userIds)
            ->whereIn('role', ['admin', 'super_admin'])
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get available activity types.
        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Persian labels for activity types.
        $actionLabels = [
            'login' => 'ورود به سیستم',
            'logout' => 'خروج از سیستم',

            'profile_updated' => 'ویرایش پروفایل',
            'account_deleted' => 'حذف حساب کاربری',

            'password_changed' => 'تغییر رمز عبور',
            'password_reset' => 'بازیابی رمز عبور',
            'password_reset_link_sent' => 'ارسال لینک بازیابی رمز عبور',
            'password_confirmed' => 'تأیید رمز عبور',

            'email_verified' => 'تأیید ایمیل',
            'verification_link_sent' => 'ارسال لینک تأیید ایمیل',

            'user_registered' => 'ثبت‌نام کاربر',

            'product_created' => 'ایجاد محصول',
            'product_updated' => 'ویرایش محصول',
            'product_deleted' => 'حذف محصول',

            'blog_post_created' => 'ایجاد مقاله',
            'blog_post_updated' => 'ویرایش مقاله',
            'blog_post_deleted' => 'حذف مقاله',

            'site_content_updated' => 'ویرایش محتوای سایت',

            'customer_created' => 'ایجاد مشتری',
            'customer_updated' => 'ویرایش مشتری',

            'invoice_created' => 'صدور فاکتور',
            'proforma_created' => 'صدور پیش‌فاکتور',
            'invoice_voided' => 'ابطال فاکتور',
            'proforma_voided' => 'ابطال پیش‌فاکتور',
            'proforma_converted' => 'تبدیل پیش‌فاکتور به فاکتور',

            'manager_created' => 'ایجاد مدیر',
            'manager_permissions_updated' => 'تغییر دسترسی‌های مدیر',

            'inquiry_received' => 'دریافت درخواست تماس',
            'inquiry_marked_read' => 'علامت‌گذاری درخواست به‌عنوان خوانده‌شده',
        ];

        return view('admin.activity-logs.index', [
            'activities' => $activities,
            'users' => $users,
            'actions' => $actions,
            'actionLabels' => $actionLabels,
            'filters' => $validated,
        ]);
    }
}