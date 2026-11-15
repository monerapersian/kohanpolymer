<?php

use Illuminate\Support\Facades\Route;
use App\Models\SiteContent;
use App\Models\Product;
use App\Models\BlogPost;

use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InvoiceController;
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
        return view('admin.dashboard');
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

        Route::get('/admin/customers/{customer}', [CustomerController::class, 'show'])
            ->name('admin.customers.show');

        Route::post('/admin/customers', [CustomerController::class, 'store'])
            ->name('admin.customers.store');

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