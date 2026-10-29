<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\SiteContent;
use App\Support\ImageConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'hero_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'hero_slogan' => ['nullable', 'string', 'max:2000'],

            'industry_title' => ['nullable', 'string', 'max:255'],
            'industry_text' => ['nullable', 'string', 'max:10000'],

            'difference_title' => ['nullable', 'string', 'max:255'],
            'difference_text' => ['nullable', 'string', 'max:10000'],

            'about_title' => ['nullable', 'string', 'max:255'],
            'about_text' => ['nullable', 'string', 'max:10000'],

            'activity_areas' => ['nullable', 'array', 'max:30'],
            'activity_areas.*' => ['nullable', 'string', 'max:255'],

            'phones' => ['nullable', 'array', 'max:30'],
            'phones.*' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\s()-]*$/',
            ],

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
            'logo.image' => 'فایل لوگو باید یک تصویر معتبر باشد.',
            'logo.mimes' => 'فرمت لوگو باید JPG، PNG یا WebP باشد.',
            'logo.max' => 'حجم لوگو نباید بیشتر از ۵ مگابایت باشد.',

            'hero_image.image' => 'فایل تصویر هدر باید یک تصویر معتبر باشد.',
            'hero_image.mimes' => 'فرمت تصویر هدر باید JPG، PNG یا WebP باشد.',
            'hero_image.max' => 'حجم تصویر هدر نباید بیشتر از ۵ مگابایت باشد.',

            'activity_areas.array' => 'حوزه‌های فعالیت ارسالی معتبر نیستند.',
            'activity_areas.max' => 'حداکثر ۳۰ حوزه فعالیت می‌توانید ثبت کنید.',
            'activity_areas.*.string' => 'هر حوزه فعالیت باید متن معتبر باشد.',
            'activity_areas.*.max' => 'طول هر حوزه فعالیت نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'phones.array' => 'شماره‌های تلفن ارسالی معتبر نیستند.',
            'phones.max' => 'حداکثر ۳۰ شماره تلفن می‌توانید ثبت کنید.',
            'phones.*.string' => 'هر شماره تلفن باید متن معتبر باشد.',
            'phones.*.max' => 'طول هر شماره تلفن نباید بیشتر از ۳۰ کاراکتر باشد.',
            'phones.*.regex' => 'شماره تلفن فقط می‌تواند شامل اعداد، فاصله و علامت‌های +، پرانتز و خط تیره باشد.',

            'email.email' => 'لطفاً یک آدرس ایمیل معتبر وارد کنید.',
            'instagram.regex' => 'آیدی اینستاگرام فقط می‌تواند شامل حروف انگلیسی، عدد، نقطه و زیرخط باشد.',
            'whatsapp.regex' => 'شماره واتساپ را فقط با اعداد و در صورت نیاز علامت + وارد کنید.',
        ]);

        $content = SiteContent::first() ?? new SiteContent();

        foreach (['logo', 'hero_image'] as $imageField) {
            if (! $request->hasFile($imageField)) {
                continue;
            }

            $newImagePath = ImageConverter::convertToWebp(
                $request->file($imageField),
                'site'
            );

            $oldImagePath = $content->{$imageField};

            $validated[$imageField] = $newImagePath;

            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        $validated['instagram'] = trim(
            $validated['instagram'] ?? ''
        );

        $validated['whatsapp'] = preg_replace(
            '/[\s()+-]/',
            '',
            trim($validated['whatsapp'] ?? '')
        );

        $validated['activity_areas'] = array_values(array_filter(
            $validated['activity_areas'] ?? [],
            fn ($value) => trim($value) !== ''
        ));

        $validated['activity_areas'] = array_map(
            fn ($value) => trim($value),
            $validated['activity_areas']
        );

        $validated['phones'] = array_values(array_filter(
            array_map(
                fn ($value) => preg_replace(
                    '/\s+/',
                    '',
                    trim($value)
                ),
                $validated['phones'] ?? []
            ),
            fn ($value) => $value !== ''
        ));

        $content->fill($validated);
        $content->save();

        return redirect()
            ->route('admin.site-content.edit')
            ->with('active_tab', 'main')
            ->with('success', 'محتوای اصلی سایت بروزرسانی شد.');
    }
}