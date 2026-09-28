<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $selectedType = $request->query('type', 'all');

        if ($request->ajax()) {
            $query = Category::with(['seo'])->withCount(['posts', 'projects', 'clients']);

            if ($selectedType && $selectedType !== 'all') {
                $query->where('type', $selectedType);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return '<span class="block font-medium text-gray-800">'.e($row->name).'</span>
                            <span class="mt-1 block text-theme-xs">'.e($row->slug).'</span>';
                })
                ->editColumn('type', function ($row) {
                    return match ($row->type) {
                        'project' => '<span class="admin-badge admin-badge-success">Studi Kasus</span>',
                        'client' => '<span class="admin-badge admin-badge-warning">Klien</span>',
                        default => '<span class="admin-badge admin-badge-brand">Artikel Blog</span>',
                    };
                })
                ->editColumn('description', function ($row) {
                    return $row->description
                        ? '<span class="line-clamp-2 block max-w-sm whitespace-normal">'.e($row->description).'</span>'
                        : 'Tidak ada deskripsi';
                })
                ->addColumn('items_count', function ($row) {
                    $count = match ($row->type) {
                        'project' => $row->projects_count,
                        'client' => $row->clients_count,
                        default => $row->posts_count,
                    };
                    $label = match ($row->type) {
                        'project' => 'Proyek',
                        'client' => 'Klien',
                        default => 'Artikel',
                    };
                    return $count.' '.$label;
                })
                ->addColumn('seo_status', function ($row) {
                    $hasCustomSeo = $row->seo && ($row->seo->title || $row->seo->description);

                    return $hasCustomSeo
                        ? '<span class="admin-badge admin-badge-brand">SEO kustom</span>'
                        : '<span class="admin-badge admin-badge-gray">SEO otomatis</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->name,
                        'edit' => route('admin.categories.edit', $row->id),
                    ])->render();
                })
                ->rawColumns(['name', 'type', 'description', 'items_count', 'seo_status', 'action'])
                ->make(true);
        }

        $postCount = Category::where('type', 'post')->count();
        $projectCount = Category::where('type', 'project')->count();
        $clientCount = Category::where('type', 'client')->count();

        return view('admin.categories.index', compact('selectedType', 'postCount', 'projectCount', 'clientCount'));
    }

    public function create(Request $request): View
    {
        $defaultType = $request->query('type', 'post');
        if (!in_array($defaultType, ['post', 'project', 'client'])) {
            $defaultType = 'post';
        }

        return view('admin.categories.create', compact('defaultType'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'type' => 'required|in:post,project,client',
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_robots' => 'nullable|string|max:50',
            'seo_canonical_url' => 'nullable|url|max:255',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->filled('seo_title') || $request->filled('seo_description') || $request->filled('seo_canonical_url')) {
            $category->seo()->updateOrCreate([], [
                'title' => $request->input('seo_title'),
                'description' => $request->input('seo_description'),
                'author' => setting('site_author', 'Slamet Rivai'),
                'robots' => $request->input('seo_robots', 'index, follow'),
                'canonical_url' => $request->input('seo_canonical_url'),
            ]);
        }

        $typeLabel = match($category->type) {
            'project' => 'proyek',
            'client' => 'klien',
            default => 'artikel',
        };

        return redirect()->route('admin.categories.index', ['type' => $category->type])
            ->with('success', 'Kategori ' . $typeLabel . ' berhasil ditambahkan!');
    }

    public function edit(Category $category): View
    {
        $category->load('seo');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug,' . $category->id,
            'type' => 'required|in:post,project,client',
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_robots' => 'nullable|string|max:50',
            'seo_canonical_url' => 'nullable|url|max:255',
        ]);

        $oldName = $category->name;
        $newName = $validated['name'];

        $category->update([
            'name' => $newName,
            'slug' => Str::slug($validated['slug']),
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
        ]);

        // If project category name changed, cascade update to projects.category to prevent orphans
        if ($category->type === 'project' && $oldName !== $newName) {
            Project::where('category', $oldName)->update(['category' => $newName]);
        }

        // If client category name changed, cascade update to clients.industry to prevent orphans
        if ($category->type === 'client' && $oldName !== $newName) {
            Client::where('industry', $oldName)->update(['industry' => $newName]);
        }

        if ($request->filled('seo_title') || $request->filled('seo_description') || $request->filled('seo_canonical_url') || ($request->filled('seo_robots') && $request->input('seo_robots') !== 'index, follow')) {
            $category->seo()->updateOrCreate([], [
                'title' => $request->input('seo_title'),
                'description' => $request->input('seo_description'),
                'author' => setting('site_author', 'Slamet Rivai'),
                'robots' => $request->input('seo_robots', 'index, follow'),
                'canonical_url' => $request->input('seo_canonical_url'),
            ]);
        } else {
            $category->seo()->update([
                'title' => null,
                'description' => null,
                'robots' => null,
                'canonical_url' => null,
            ]);
        }

        return redirect()->route('admin.categories.index', ['type' => $category->type])
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category): JsonResponse|RedirectResponse
    {
        $type = $category->type;

        // Check if category has dependent items
        if ($type === 'post' && $category->posts()->exists()) {
            $message = 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel aktif.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($type === 'project' && Project::where('category', $category->name)->exists()) {
            $message = 'Kategori tidak dapat dihapus karena masih digunakan oleh studi kasus proyek aktif.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($type === 'client' && Client::where('industry', $category->name)->exists()) {
            $message = 'Kategori tidak dapat dihapus karena masih digunakan oleh data klien aktif.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        $category->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus!']);
        }

        return redirect()->route('admin.categories.index', ['type' => $type])->with('success', 'Kategori berhasil dihapus!');
    }
}
