<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
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

    private const PERMISSION_LABELS = [
        'site_content' => 'محتوای اصلی',
        'products' => 'محصولات',
        'blog_posts' => 'مقالات',
        'inquiries' => 'درخواست‌ها',
        'customers' => 'مشتریان',
        'invoices' => 'فاکتورها',
        'financial_reports' => 'گزارش‌های مالی',
        'activity_logs' => 'گزارش فعالیت‌ها',
    ];

    public function index()
    {
        $managers = User::query()
            ->where('role', 'admin')
            ->latest()
            ->get()
            ->map(function (User $manager) {
                $permissions = $this->validPermissions($manager);

                $lastActivity = ActivityLog::query()
                    ->where('user_id', $manager->id)
                    ->latest()
                    ->orderByDesc('id')
                    ->first();

                return [
                    'id' => $manager->id,
                    'name' => $manager->name,
                    'email' => $manager->email,
                    'status' => count($permissions) > 0 ? 'active' : 'inactive',
                    'permissions' => $permissions,
                    'image' => null,
                    'last_activity' => $lastActivity
                        ? \App\Support\NumberHelper::toPersian(
                            \App\Support\DateHelper::toPersian($lastActivity->created_at, 'Y/m/d - H:i')
                        )
                        : 'ثبت نشده',
                    'created_at' => $manager->created_at,
                ];
            });

        $totalManagers = $managers->count();

        $activeManagers = $managers->where('status', 'active')->count();

        $totalPermissions = count(self::PERMISSIONS);

        $permissions = self::PERMISSION_LABELS;

        return view('admin.managers.managers', compact(
            'managers',
            'totalManagers',
            'activeManagers',
            'totalPermissions',
            'permissions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'permissions' => [
                'required',
                'array',
                'min:1',
            ],
            'permissions.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(self::PERMISSIONS),
            ],
        ], [
            'name.required' => 'وارد کردن نام مدیر الزامی است.',
            'name.max' => 'نام مدیر نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل معتبر نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',

            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور با رمز عبور مطابقت ندارد.',

            'permissions.required' => 'حداقل یک دسترسی باید انتخاب شود.',
            'permissions.array' => 'ساختار دسترسی‌های انتخاب‌شده معتبر نیست.',
            'permissions.min' => 'حداقل یک دسترسی باید انتخاب شود.',
            'permissions.*.distinct' => 'یک دسترسی نباید بیشتر از یک بار انتخاب شود.',
            'permissions.*.in' => 'یکی از دسترسی‌های انتخاب‌شده معتبر نیست.',
        ]);

        $manager = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'admin',
            'permissions' => array_values(array_unique(
                $validated['permissions']
            )),
        ]);

        app(ActivityLogger::class)->log(
            action: 'manager_created',
            description: "مدیر جدید با نام {$manager->name} ایجاد شد.",
            subject: $manager,
            properties: [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'manager_email' => $manager->email,
                'permissions' => $this->validPermissions($manager),
            ]
        );

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'مدیر جدید با موفقیت ایجاد شد.');
    }

    public function show(User $manager)
    {
        abort_if($manager->role !== 'admin', 404);

        $managerPermissions = $this->validPermissions($manager);

        $permissions = collect(self::PERMISSION_LABELS)
            ->map(fn ($label, $key) => [
                'key' => $key,
                'label' => $label,
                'active' => in_array(
                    $key,
                    $managerPermissions,
                    true
                ),
            ])
            ->values();

        $activePermissions = count($managerPermissions);

        $activities = ActivityLog::query()
            ->where('user_id', $manager->id)
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $lastLogin = ActivityLog::query()
            ->where('user_id', $manager->id)
            ->where('action', 'login')
            ->latest()
            ->orderByDesc('id')
            ->first();

        $managerData = [
            'id' => $manager->id,
            'name' => $manager->name,
            'email' => $manager->email,
            'role' => 'مدیر',
            'status' => $activePermissions > 0 ? 'active' : 'inactive',
            'created_at' => $manager->created_at,
            'last_login' => $lastLogin
                ? \App\Support\NumberHelper::toPersian(
                    \App\Support\DateHelper::toPersian($lastLogin->created_at, 'Y/m/d - H:i')
                )
                : 'ثبت نشده',
            'avatar' => null,
        ];

        return view('admin.managers.manager-profile', [
            'manager' => $managerData,
            'permissions' => $permissions,
            'activePermissions' => $activePermissions,
            'activities' => $activities,
            'managerModel' => $manager,
        ]);
    }

    public function updatePermissions(Request $request, User $manager)
    {
        abort_if($manager->role !== 'admin', 404);

        $validated = $request->validate([
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(self::PERMISSIONS),
            ],
        ], [
            'permissions.array' => 'ساختار دسترسی‌های انتخاب‌شده معتبر نیست.',
            'permissions.*.distinct' => 'یک دسترسی نباید بیشتر از یک بار انتخاب شود.',
            'permissions.*.in' => 'یکی از دسترسی‌های انتخاب‌شده معتبر نیست.',
        ]);

        $oldPermissions = $this->validPermissions($manager);

        $newPermissions = array_values(array_intersect(
            self::PERMISSIONS,
            array_unique($validated['permissions'] ?? [])
        ));

        sort($oldPermissions);
        sort($newPermissions);

        if ($oldPermissions !== $newPermissions) {
            $manager->update([
                'permissions' => $newPermissions,
            ]);

            app(ActivityLogger::class)->log(
                action: 'manager_permissions_updated',
                description: "دسترسی‌های مدیر {$manager->name} تغییر کرد.",
                subject: $manager,
                properties: [
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                    'old_permissions' => $oldPermissions,
                    'new_permissions' => $newPermissions,
                ]
            );
        }

        return redirect()
            ->route('admin.managers.show', $manager)
            ->with('success', 'سطح دسترسی مدیر با موفقیت به‌روزرسانی شد.');
    }

    private function validPermissions(User $manager): array
    {
        $permissions = $manager->permissions ?? [];

        if (!is_array($permissions)) {
            return [];
        }

        return array_values(array_intersect(
            self::PERMISSIONS,
            $permissions
        ));
    }
}