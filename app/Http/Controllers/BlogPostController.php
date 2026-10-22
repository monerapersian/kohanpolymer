<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Support\ImageConverter;

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

        BlogPost::create($validated);

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

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'blog'])
            ->with('active_tab', 'blog')
            ->with('success', 'مقاله با موفقیت ویرایش شد.');
    }

    public function destroy(BlogPost $blogPost)
    {
        if ($blogPost->image) {
            Storage::disk('public')->delete($blogPost->image);
        }

        $blogPost->delete();

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'blog'])
            ->with('active_tab', 'blog')
            ->with('success', 'مقاله با موفقیت حذف شد.');
    }
}