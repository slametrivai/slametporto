<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search = $request->query('q');

        $query = Post::published()->with('category')->latest('published_at');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::forPosts()->withCount(['posts' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        $featuredPost = Post::published()->latest('published_at')->first();

        return view('blog.index', compact('posts', 'categories', 'categorySlug', 'search', 'featuredPost'));
    }

    public function show(string $slug): View
    {
        $query = Post::where('slug', $slug)->with('category');

        if (!Auth::check()) {
            $query->where('status', 'published');
        }

        $post = $query->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        // Convert markdown to sanitized HTML
        $htmlContent = Str::markdown($post->content);

        // Related posts in same category
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'htmlContent', 'relatedPosts'));
    }
}
