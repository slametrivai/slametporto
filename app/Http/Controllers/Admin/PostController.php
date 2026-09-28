<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Post::with(['category', 'seo'])->select([
                'id', 'category_id', 'title', 'slug', 'status', 'views_count', 'published_at', 'cover_image'
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('cover_image', function ($row) {
                    return $row->cover_image_url
                        ? '<img src="'.e($row->cover_image_url).'" alt="" class="h-11 w-16 rounded-lg border border-gray-200 object-cover">'
                        : 'Tidak ada';
                })
                ->editColumn('title', function ($row) {
                    $hasCustomSeo = $row->seo && ($row->seo->title || $row->seo->description);
                    $seoBadge = $hasCustomSeo
                        ? '<span class="admin-badge admin-badge-brand">SEO kustom</span>'
                        : '<span class="admin-badge admin-badge-gray">SEO otomatis</span>';

                    return '<span class="block font-medium text-gray-800">'.e($row->title).'</span>
                            <span class="mt-1 flex flex-wrap items-center gap-2 text-theme-xs">/blog/'.e($row->slug).' '.$seoBadge.'</span>';
                })
                ->editColumn('category', function ($row) {
                    return '<span class="admin-badge admin-badge-brand">'.e($row->category?->name ?? 'Tanpa kategori').'</span>';
                })
                ->editColumn('status', function ($row) {
                    return $row->status === 'published'
                        ? '<span class="admin-badge admin-badge-success">Terbit</span>'
                        : '<span class="admin-badge admin-badge-gray">Draf</span>';
                })
                ->editColumn('published_at', function ($row) {
                    return $row->published_at ? $row->published_at->format('d M Y, H:i') : 'Belum dijadwalkan';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->title,
                        'view' => $row->status === 'published' ? route('blog.show', $row->slug) : null,
                        'edit' => route('admin.posts.edit', $row->id),
                    ])->render();
                })
                ->rawColumns(['cover_image', 'title', 'category', 'status', 'published_at', 'action'])
                ->make(true);
        }

        return view('admin.posts.index');
    }

    public function create(): View
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_robots' => 'nullable|string|max:50',
            'seo_canonical_url' => 'nullable|url|max:255',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('posts', 'public');
        }

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $publishedAt = $validated['status'] === 'published'
            ? (!empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : Carbon::now())
            : null;

        $post = Post::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'cover_image' => $coverImagePath,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        // Save Custom SEO metadata if provided
        if ($request->filled('seo_title') || $request->filled('seo_description') || $request->filled('seo_canonical_url')) {
            $post->seo()->updateOrCreate([], [
                'title' => $request->input('seo_title'),
                'description' => $request->input('seo_description'),
                'author' => setting('site_author', 'Slamet Rivai'),
                'robots' => $request->input('seo_robots', 'index, follow'),
                'canonical_url' => $request->input('seo_canonical_url'),
            ]);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artikel blog berhasil dibuat!');
    }

    public function edit(Post $post): View
    {
        $post->load('seo');
        $categories = Category::where('type', 'post')->orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_robots' => 'nullable|string|max:50',
            'seo_canonical_url' => 'nullable|url|max:255',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = !empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : Carbon::now();
        } elseif ($validated['status'] === 'published' && !empty($validated['published_at'])) {
            $data['published_at'] = Carbon::parse($validated['published_at']);
        } elseif ($validated['status'] === 'draft') {
            $data['published_at'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image && !str_starts_with($post->cover_image, 'http')) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);

        // Update or create SEO metadata
        if ($request->filled('seo_title') || $request->filled('seo_description') || $request->filled('seo_canonical_url') || ($request->filled('seo_robots') && $request->input('seo_robots') !== 'index, follow')) {
            $post->seo()->updateOrCreate([], [
                'title' => $request->input('seo_title'),
                'description' => $request->input('seo_description'),
                'author' => setting('site_author', 'Slamet Rivai'),
                'robots' => $request->input('seo_robots', 'index, follow'),
                'canonical_url' => $request->input('seo_canonical_url'),
            ]);
        } else {
            $post->seo()->update([
                'title' => null,
                'description' => null,
                'robots' => null,
                'canonical_url' => null,
            ]);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artikel blog berhasil diperbarui!');
    }

    public function destroy(Post $post): JsonResponse|RedirectResponse
    {
        if ($post->cover_image && !str_starts_with($post->cover_image, 'http')) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus!']);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
