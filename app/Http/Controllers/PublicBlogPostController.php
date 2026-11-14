<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class PublicBlogPostController extends Controller
{
    /**
     * Display published blog posts.
     */
    public function index(Request $request)
    {
        $content = SiteContent::first();
        $siteContent = $content;

        $query = BlogPost::query()
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sort = $request->input('sort', 'newest');

        $query->orderBy(
            'published_at',
            $sort === 'oldest' ? 'asc' : 'desc'
        );

        $blogPosts = $query->paginate(9)->withQueryString();

        return view('blog.index', compact(
            'blogPosts',
            'sort',
            'content',
            'siteContent'
        ));
    }

    /**
     * Display a single published blog post.
     */
    public function show(BlogPost $blogPost)
    {
        abort_unless($blogPost->status === 'published', 404);

        $content = SiteContent::first();
        $siteContent = $content;

        $relatedPosts = BlogPost::query()
            ->where('status', 'published')
            ->where('id', '!=', $blogPost->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact(
            'blogPost',
            'relatedPosts',
            'content',
            'siteContent'
        ));
    }
}