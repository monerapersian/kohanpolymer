<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    /**
     * Display the website content management page.
     */
    public function edit(Request $request)
    {
        $content = SiteContent::first();

        $productQuery = Product::query();

        if ($request->filled('search')) {
            $productQuery->where(
                'name',
                'like',
                '%' . $request->input('search') . '%'
            );
        }

        $productSort = $request->input('sort', 'newest');

        $productQuery->orderBy(
            'created_at',
            $productSort === 'oldest' ? 'asc' : 'desc'
        );

        $products = $productQuery
            ->paginate(10, ['*'], 'products_page')
            ->withQueryString();

        $blogQuery = BlogPost::query();

        if ($request->filled('blog_search')) {
            $blogQuery->where(
                'title',
                'like',
                '%' . $request->input('blog_search') . '%'
            );
        }

        $blogSort = $request->input('blog_sort', 'newest');

        $blogQuery->orderBy(
            'created_at',
            $blogSort === 'oldest' ? 'asc' : 'desc'
        );

        $blogPosts = $blogQuery
            ->paginate(10, ['*'], 'blog_page')
            ->withQueryString();

        return view(
            'admin.site-content',
            compact('content', 'products', 'blogPosts')
        );
    }

    /**
     * Validate and save the website content.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_slogan' => ['nullable', 'string', 'max:2000'],
            'about_text' => ['nullable', 'string', 'max:10000'],
            'phone' => ['nullable', 'string', 'max:16'],
            'email' => ['nullable', 'email', 'max:150'],
            'instagram' => [
                'nullable',
                'string',
                'max:150',
                'regex:/^[A-Za-z0-9._]*$/',
            ],
            'whatsapp' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9+\s()-]*$/',
            ],
        ], [
            'email.email' => 'لطفاً یک آدرس ایمیل معتبر وارد کنید.',
            'instagram.regex' => 'آیدی اینستاگرام فقط می‌تواند شامل حروف انگلیسی، عدد، نقطه و زیرخط باشد.',
            'whatsapp.regex' => 'شماره واتساپ را فقط با اعداد و در صورت نیاز علامت + وارد کنید.',
        ]);

        if (! empty($validated['instagram'])) {
            $validated['instagram'] = trim($validated['instagram']);
        }

        if (! empty($validated['whatsapp'])) {
            $validated['whatsapp'] = preg_replace(
                '/[\s()+-]/',
                '',
                trim($validated['whatsapp'])
            );
        }

        $content = SiteContent::first();

        if ($content) {
            $content->update($validated);
        } else {
            SiteContent::create($validated);
        }

        return redirect()
            ->route('admin.site-content.edit')
            ->with('success', 'محتوای اصلی سایت بروزرسانی شد');
    }
}