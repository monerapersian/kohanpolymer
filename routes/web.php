<?php

use Illuminate\Support\Facades\Route;

use App\Models\SiteContent;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Visit;
use App\Models\Inquiry;
use App\Models\ActivityLog;

use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoicePaymentController;
use App\Http\Controllers\Admin\InvoiceProfitController;
use App\Http\Controllers\Admin\InvoiceCommissionController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\FinancialReportController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\PublicBlogPostController;
use App\Http\Controllers\InquiryController;

// Public Website Routes
Route::get('/', function () {
    Visit::record('home');

    $content = SiteContent::first();

    $products = Product::query()
        ->where('status', 'published')
        ->where('is_featured', true)
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->take(4)
        ->get();

    $blogPosts = BlogPost::query()
        ->where('status', 'published')
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->take(3)
        ->get();

    return view('home', compact(
        'content',
        'products',
        'blogPosts'
    ));
});

// Public About Page
Route::get('/about', function () {
    Visit::record('about');

    $content = SiteContent::first();

    return view('about', compact('content'));
})->name('about');

// Public Products Page
Route::get('/products', [ProductController::class, 'publicIndex'])
    ->name('products.index');

// Public Product Details Page
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->whereNumber('product')
    ->name('products.show');

// Public Blog Page
Route::get('/blog', [PublicBlogPostController::class, 'index'])
    ->name('blog.index');

// Public Blog Post Details Page
Route::get('/blog/{blogPost}', [PublicBlogPostController::class, 'show'])
    ->whereNumber('blogPost')
    ->name('blog.show');

// Public Document Verification
Route::get('/document/verify/{token}', [InvoiceController::class, 'verify'])
    ->name('invoices.verify');

// Account Settings Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [AccountSettingsController::class, 'edit'])
        ->name('settings.edit');

    Route::put('/settings/password', [AccountSettingsController::class, 'updatePassword'])
        ->name('settings.password.update');
});

// Admin Panel Routes
Route::middleware(['auth', 'permission'])->group(function () {

    // Dashboard
    Route::get('/admin', function () {

        $validPermissions = [
            'site_content',
            'products',
            'blog_posts',
            'inquiries',
            'customers',
            'invoices',
            'financial_reports',
            'activity_logs',
        ];

        $activeManagers = User::query()
            ->where('role', 'admin')
            ->get()
            ->filter(function (User $manager) use ($validPermissions) {
                $permissions = $manager->permissions ?? [];

                return is_array($permissions)
                    && count(array_intersect($validPermissions, $permissions)) > 0;
            })
            ->count();

        $activeProformas = Invoice::query()
            ->where('type', 'proforma')
            ->where('status', 'valid')
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', today());
            })
            ->count();

        $totalCustomers = Customer::query()->count();

        $totalProducts = Product::query()->count();

        // Website visit statistics for the last 30 days
        $since = now()->subDays(30);

        $siteVisits30Days = Visit::query()
            ->where('created_at', '>=', $since)
            ->count();

        $productVisits30Days = Visit::query()
            ->whereIn('type', ['products', 'product'])
            ->where('created_at', '>=', $since)
            ->count();

        $blogVisits30Days = Visit::query()
            ->whereIn('type', ['blogs', 'blog_post'])
            ->where('created_at', '>=', $since)
            ->count();

        $unreadInquiries = Inquiry::query()
            ->where('is_read', false)
            ->count();

        // Get the four latest administrative activities
        $recentActivities = ActivityLog::query()
            ->latest('created_at')
            ->latest('id')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'activeManagers',
            'activeProformas',
            'totalCustomers',
            'totalProducts',
            'siteVisits30Days',
            'productVisits30Days',
            'blogVisits30Days',
            'unreadInquiries',
            'recentActivities'
        ));

    })->name('admin.dashboard');

    // Admin Management Routes
    Route::prefix('admin/managers')
        ->name('admin.managers.')
        ->middleware('role:super_admin')
        ->controller(ManagerController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::post('/', 'store')->name('store');

            Route::get('/{manager}', 'show')
                ->whereNumber('manager')
                ->name('show');

            Route::put('/{manager}/permissions', 'updatePermissions')
                ->whereNumber('manager')
                ->name('permissions.update');
        });

    // Site Content Routes
    Route::middleware('permission:site_content')->group(function () {
        Route::get('/admin/content', [SiteContentController::class, 'edit'])
            ->name('admin.site-content.edit');

        Route::put('/admin/content', [SiteContentController::class, 'update'])
            ->name('admin.site-content.update');
    });

    // Product Routes
    Route::middleware('permission:products')->group(function () {
        Route::get('/admin/products', [ProductController::class, 'index'])
            ->name('admin.products.index');

        Route::post('/admin/products', [ProductController::class, 'store'])
            ->name('admin.products.store');

        Route::put('/admin/products/{product}', [ProductController::class, 'update'])
            ->name('admin.products.update');

        Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
            ->name('admin.products.destroy');
    });

    // Blog Post Routes
    Route::middleware('permission:blog_posts')->group(function () {
        Route::get('/admin/blog-posts', [BlogPostController::class, 'index'])
            ->name('admin.blog-posts.index');

        Route::post('/admin/blog-posts', [BlogPostController::class, 'store'])
            ->name('admin.blog-posts.store');

        Route::put('/admin/blog-posts/{blogPost}', [BlogPostController::class, 'update'])
            ->name('admin.blog-posts.update');

        Route::delete('/admin/blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])
            ->name('admin.blog-posts.destroy');
    });

    // Inquiry Routes
    Route::middleware('permission:inquiries')->group(function () {
        Route::get('/admin/inquiries', [InquiryController::class, 'index'])
            ->name('admin.inquiries.index');

        Route::get('/admin/inquiries/{inquiry}', [InquiryController::class, 'show'])
            ->name('admin.inquiries.show');
    });

    // Customer Routes
    Route::middleware('permission:customers')->group(function () {
        Route::get('/admin/customers', [CustomerController::class, 'index'])
            ->name('admin.customers.index');

        Route::post('/admin/customers', [CustomerController::class, 'store'])
            ->name('admin.customers.store');

        Route::get('/admin/customers/{customer}/report', [CustomerController::class, 'printReport'])
            ->name('admin.customers.report');

        Route::get('/admin/customers/{customer}', [CustomerController::class, 'show'])
            ->name('admin.customers.show');

        Route::put('/admin/customers/{customer}', [CustomerController::class, 'update'])
            ->name('admin.customers.update');
    });

    // Invoice Routes
    Route::middleware('permission:invoices')->group(function () {
        Route::get('/admin/invoices', [InvoiceController::class, 'index'])
            ->name('admin.invoices.index');

        Route::get('/admin/invoices/create', [InvoiceController::class, 'create'])
            ->name('admin.invoices.create');

        Route::post('/admin/invoices', [InvoiceController::class, 'store'])
            ->name('admin.invoices.store');

        Route::get('/admin/invoices/{invoice}/export', [InvoiceController::class, 'export'])
            ->name('admin.invoices.export');

        // Invoice Payment Routes
        Route::post('/admin/invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])
            ->name('admin.invoices.payments.store');

        Route::get('/admin/invoices/{invoice}/payments/{payment}/document', [InvoicePaymentController::class, 'download'])
            ->name('admin.invoices.payments.document');

        // Invoice Profit Routes
        Route::middleware('role:super_admin')->group(function () {
            Route::put('/admin/invoices/{invoice}/profit', [InvoiceProfitController::class, 'update'])
                ->name('admin.invoices.profit.update');
        });

        // Invoice Commission Routes
        Route::put('/admin/invoices/{invoice}/commissions', [InvoiceCommissionController::class, 'update'])
            ->name('admin.invoices.commissions.update');

        Route::get('/admin/invoices/{invoice}', [InvoiceController::class, 'show'])
            ->name('admin.invoices.show');

        Route::post('/admin/invoices/{invoice}/void', [InvoiceController::class, 'void'])
            ->name('admin.invoices.void');

        Route::post('/admin/invoices/{invoice}/convert', [InvoiceController::class, 'convert'])
            ->name('admin.invoices.convert');
    });

    // Activity Logs Routes
    Route::middleware('permission:activity_logs')->group(function () {
        Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])
            ->name('admin.activity-logs.index');
    });

    // Financial Reports
    Route::middleware('permission:financial_reports')->group(function () {
        Route::get('/admin/reports/financial', [FinancialReportController::class, 'index'])
            ->name('admin.reports.financial');

        Route::post('/admin/reports/financial/incomes', [FinancialReportController::class, 'storeIncome'])
            ->name('admin.reports.financial.incomes.store');

        Route::post('/admin/reports/financial/expenses', [FinancialReportController::class, 'storeExpense'])
            ->name('admin.reports.financial.expenses.store');
    });
});

// Public Inquiry Submission
Route::post('/inquiries', [InquiryController::class, 'store'])
    ->name('inquiries.store');

require __DIR__.'/auth.php';