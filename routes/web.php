<?php

use Illuminate\Support\Facades\Route;
use App\Models\SiteContent;
use App\Models\Product;
use App\Models\BlogPost;

use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InvoiceController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\InquiryController;

// Public Website Routes
Route::get('/', function () {
    $content = SiteContent::first();

    $products = Product::query()
        ->where('status', 'published')
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->take(3)
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

// Public Document Verification
Route::get('/document/verify/{token}', [InvoiceController::class, 'verify'])
    ->name('invoices.verify');

// Admin Panel Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin Management Routes
    Route::get('/admin/managers', function () {
        return view('admin.managers.managers');
    })->name('admin.managers.index');

    // Admin Manager Profile Route
    Route::get('/admin/managers/{manager}', function ($manager) {
        return view('admin.managers.manager-profile', [
            'managerId' => $manager,
        ]);
    })->name('admin.managers.show');

    // Site Content Routes
    Route::get('/admin/content', [SiteContentController::class, 'edit'])
        ->name('admin.site-content.edit');

    Route::put('/admin/content', [SiteContentController::class, 'update'])
        ->name('admin.site-content.update');

    // Product Routes
    Route::get('/admin/products', [ProductController::class, 'index'])
        ->name('admin.products.index');

    Route::post('/admin/products', [ProductController::class, 'store'])
        ->name('admin.products.store');

    Route::put('/admin/products/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');

    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    // Blog Post Routes
    Route::get('/admin/blog-posts', [BlogPostController::class, 'index'])
        ->name('admin.blog-posts.index');

    Route::post('/admin/blog-posts', [BlogPostController::class, 'store'])
        ->name('admin.blog-posts.store');

    Route::put('/admin/blog-posts/{blogPost}', [BlogPostController::class, 'update'])
        ->name('admin.blog-posts.update');

    Route::delete('/admin/blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])
        ->name('admin.blog-posts.destroy');

    // Inquiry Routes
    Route::get('/admin/inquiries', [InquiryController::class, 'index'])
        ->name('admin.inquiries.index');

    Route::get('/admin/inquiries/{inquiry}', [InquiryController::class, 'show'])
        ->name('admin.inquiries.show');

    // Customer Routes
    Route::get('/admin/customers', [CustomerController::class, 'index'])
        ->name('admin.customers.index');

    Route::post('/admin/customers', [CustomerController::class, 'store'])
        ->name('admin.customers.store');

    Route::get('/admin/customers/{customer}', [CustomerController::class, 'show'])
        ->name('admin.customers.show');

    Route::put('/admin/customers/{customer}', [CustomerController::class, 'update'])
        ->name('admin.customers.update');

    // Invoice Routes
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

    // Financial Reports
    Route::get('/admin/reports/financial', function () {
        return view('admin.reports.financial');
    })->name('admin.reports.financial');

});

// Public Inquiry Submission
Route::post('/inquiries', [InquiryController::class, 'store'])
    ->name('inquiries.store');

require __DIR__.'/auth.php';