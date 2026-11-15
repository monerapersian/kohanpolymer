<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\ActivityLogger;
use App\Support\ImageConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sort = $request->input('sort', 'newest');

        $query->orderBy(
            'created_at',
            $sort === 'oldest' ? 'asc' : 'desc'
        );

        $blogPosts = $query->paginate(10)->withQueryString();

        return view('admin.blog.index', compact('blogPosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'body' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageConverter::convertToWebp(
                $request->file('image'),
                'blog-posts'
            );
        }

        $validated['published_at'] =
            $validated['status'] === 'published' ? now() : null;

        $blogPost = BlogPost::create($validated);

        app(ActivityLogger::class)->log(
            action: 'blog_post_created',
            description: 'مقاله جدید «' . $blogPost->title . '» ایجاد شد.',
            subject: $blogPost,
            properties: [
                'title' => $blogPost->title,
                'status' => $blogPost->status,
                'has_image' => (bool) $blogPost->image,
            ]
        );

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'blog'])
            ->with('active_tab', 'blog')
            ->with(
                'success',
                $validated['status'] === 'published'
                    ? 'مقاله با موفقیت منتشر شد.'
                    : 'مقاله با موفقیت در پیش‌نویس ذخیره شد.'
            );
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'body' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $oldValues = [
            'title' => $blogPost->title,
            'summary' => $blogPost->summary,
            'status' => $blogPost->status,
            'has_image' => (bool) $blogPost->image,
        ];

        if ($request->hasFile('image')) {
            $newImagePath = ImageConverter::convertToWebp(
                $request->file('image'),
                'blog-posts'
            );

            if ($blogPost->image) {
                Storage::disk('public')->delete($blogPost->image);
            }

            $validated['image'] = $newImagePath;
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] =
                $blogPost->published_at ?? now();
        } else {
            $validated['published_at'] = null;
        }

        $blogPost->update($validated);

        $changes = [];

        foreach (['title', 'summary', 'status'] as $field) {
            $oldValue = $oldValues[$field];
            $newValue = $blogPost->{$field};

            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        $newHasImage = (bool) $blogPost->image;

        if ($oldValues['has_image'] !== $newHasImage || $request->hasFile('image')) {
            $changes['image'] = [
                'old' => $oldValues['has_image'],
                'new' => $newHasImage,
            ];
        }

        $description = 'مقاله «' . $blogPost->title . '» ویرایش شد.';

        if (isset($changes['status'])) {
            $description .= $blogPost->status === 'published'
                ? ' مقاله منتشر شد.'
                : ' مقاله به پیش‌نویس تغییر یافت.';
        }

        app(ActivityLogger::class)->log(
            action: 'blog_post_updated',
            description: $description,
            subject: $blogPost,
            properties: [
                'changes' => $changes,
            ]
        );

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'blog'])
            ->with('active_tab', 'blog')
            ->with('success', 'مقاله با موفقیت ویرایش شد.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $deletedTitle = $blogPost->title;
        $deletedStatus = $blogPost->status;
        $hadImage = (bool) $blogPost->image;

        $imagePath = $blogPost->image;

        $blogPost->delete();

        app(ActivityLogger::class)->log(
            action: 'blog_post_deleted',
            description: 'مقاله «' . $deletedTitle . '» حذف شد.',
            subject: $blogPost,
            properties: [
                'title' => $deletedTitle,
                'status' => $deletedStatus,
                'had_image' => $hadImage,
            ]
        );

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'blog'])
            ->with('active_tab', 'blog')
            ->with('success', 'مقاله با موفقیت حذف شد.');
    }
}