<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public const SORTS = ['newest' => 'Newest first', 'oldest' => 'Oldest first', 'popular' => 'Most read'];

    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search = $request->query('q');
        $sort = array_key_exists($request->query('sort'), self::SORTS) ? $request->query('sort') : 'newest';

        $featuredPost = ($categorySlug || $search) ? null : Post::published()->with('category')->latest('published_at')->first();

        $query = Post::published()->with('category')
            ->when($featuredPost, fn ($q) => $q->whereKeyNot($featuredPost->id));

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

        match ($sort) {
            'oldest' => $query->oldest('published_at'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest('published_at'),
        };

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::forPosts()->withCount(['posts' => function ($q) {
            $q->published();
        }])->orderBy('id')->get();

        $totals = [
            'articles' => $categories->sum('posts_count'),
            'topics' => $categories->where('posts_count', '>', 0)->count(),
        ];

        return view('blog.index', compact('posts', 'categories', 'categorySlug', 'search', 'sort', 'totals', 'featuredPost'));
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

        // Markdown to HTML, with ids on the h2 headings so the sidebar can link to them.
        $toc = [];
        $htmlContent = preg_replace_callback('/<h2(\s[^>]*)?>(.*?)<\/h2>/is', function ($m) use (&$toc) {
            $text = trim(html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5));
            $id = Str::slug($text) ?: 'section-' . (count($toc) + 1);
            $toc[] = ['id' => $id, 'text' => $text];

            return '<h2 id="' . e($id) . '"' . ($m[1] ?? '') . '>' . $m[2] . '</h2>';
        }, Str::markdown($post->content));

        // Two more articles, same topic first.
        $relatedPosts = Post::published()->with('category')
            ->whereKeyNot($post->id)
            ->orderByRaw('category_id = ? desc', [$post->category_id])
            ->latest('published_at')
            ->take(2)
            ->get();

        $relatedProject = $this->relatedProject($post);

        return view('blog.show', compact('post', 'htmlContent', 'toc', 'relatedPosts', 'relatedProject'));
    }

    /** The case study sharing the most keywords with the article title and topic, if any. */
    private function relatedProject(Post $post): ?Project
    {
        $keywords = collect(preg_split('/[^a-z0-9]+/', Str::lower($post->title . ' ' . $post->category?->name)))
            ->filter(fn ($word) => strlen($word) > 3)
            ->unique();

        $best = Project::all()
            ->map(function (Project $project) use ($keywords) {
                $haystack = Str::lower($project->title . ' ' . $project->category);

                return [$project, $keywords->filter(fn ($word) => str_contains($haystack, $word))->count()];
            })
            ->filter(fn ($pair) => $pair[1] > 0)
            ->sortByDesc(fn ($pair) => $pair[1])
            ->first();

        return $best[0] ?? null;
    }
}
