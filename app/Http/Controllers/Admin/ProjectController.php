<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    // PHP's max_file_uploads defaults to 20, and the cover image counts toward it.
    private const GALLERY_MAX_PER_UPLOAD = 12;

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Project::select([
                'id', 'title', 'slug', 'category', 'role', 'is_featured', 'sort_order', 'cover_image'
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('cover_image', function ($row) {
                    return $row->cover_image_url
                        ? '<img src="'.e($row->cover_image_url).'" alt="" class="h-11 w-16 rounded-lg border border-gray-200 object-cover">'
                        : 'Tidak ada';
                })
                ->editColumn('title', function ($row) {
                    return '<span class="block font-medium text-gray-800">'.e($row->title).'</span>
                            <span class="mt-1 block text-theme-xs">'.e($row->slug).'</span>';
                })
                ->editColumn('category', function ($row) {
                    return '<span class="admin-badge admin-badge-brand">'.e($row->category).'</span>';
                })
                ->editColumn('is_featured', function ($row) {
                    return $row->is_featured
                        ? '<span class="admin-badge admin-badge-warning">Unggulan</span>'
                        : 'Biasa';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->title,
                        'view' => route('projects.show', $row->slug),
                        'edit' => route('admin.projects.edit', $row->id),
                    ])->render();
                })
                ->rawColumns(['cover_image', 'title', 'category', 'is_featured', 'action'])
                ->make(true);
        }

        return view('admin.projects.index');
    }

    public function create(): View
    {
        $categories = Category::where('type', 'project')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Automation & OCR', 'CRM & Sales', 'WhatsApp API', 'HR Systems', 'Digital Experience'];
        }
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $project = Project::create($this->contentFields($request, $validated) + [
            'slug' => $slug,
            'cover_image' => $request->file('cover_image')?->store('projects', 'public'),
        ]);

        $this->storeGalleryImages($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Studi kasus proyek berhasil ditambahkan!');
    }

    public function edit(Project $project): View
    {
        $categories = Category::where('type', 'project')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Automation & OCR', 'CRM & Sales', 'WhatsApp API', 'HR Systems', 'Digital Experience'];
        }
        if (!in_array($project->category, $categories) && !empty($project->category)) {
            array_unshift($categories, $project->category);
        }
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate($this->rules() + [
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'image_titles' => 'nullable|array',
            'image_titles.*' => 'nullable|string|max:150',
            'image_descriptions' => 'nullable|array',
            'image_descriptions.*' => 'nullable|string|max:500',
        ]);

        $data = $this->contentFields($request, $validated);

        if ($request->hasFile('cover_image')) {
            if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('projects', 'public');
        }

        $project->update($data);

        foreach ($project->images as $image) {
            if (in_array($image->id, $validated['remove_images'] ?? [])) {
                $this->deleteGalleryImage($image);
                continue;
            }

            $image->update([
                'title' => trim($validated['image_titles'][$image->id] ?? '') ?: null,
                'description' => trim($validated['image_descriptions'][$image->id] ?? '') ?: null,
            ]);
        }

        $this->storeGalleryImages($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Studi kasus proyek berhasil diperbarui!');
    }

    public function destroy(Project $project): JsonResponse|RedirectResponse
    {
        if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
            Storage::disk('public')->delete($project->cover_image);
        }

        $project->images->each(fn (ProjectImage $image) => $this->deleteGalleryImage($image));

        $project->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proyek berhasil dihapus!']);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus!');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'role' => 'required|string|max:150',
            'summary' => 'nullable|string|max:500',
            'challenge' => 'required|string',
            'solution' => 'required|string',
            'result' => 'nullable|string',
            'volume' => 'nullable|string|max:150',
            'components' => 'nullable|string|max:500',
            'impact_metrics' => 'nullable|array',
            'impact_labels' => 'nullable|array',
            'impact_details' => 'nullable|array',
            'workflow_steps' => 'nullable|array',
            'step_descriptions' => 'nullable|array',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'gallery_images' => 'nullable|array|max:' . self::GALLERY_MAX_PER_UPLOAD,
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }

    /** Text, metrics and pipeline fields shared by store and update. */
    private function contentFields(Request $request, array $validated): array
    {
        $impactHighlights = [];
        foreach ($validated['impact_metrics'] ?? [] as $index => $metric) {
            if (trim((string) $metric) !== '') {
                $impactHighlights[] = [
                    'metric' => trim($metric),
                    'label' => trim($validated['impact_labels'][$index] ?? ''),
                    'detail' => trim($validated['impact_details'][$index] ?? ''),
                ];
            }
        }

        $workflowSteps = [];
        foreach ($validated['workflow_steps'] ?? [] as $index => $step) {
            if (trim((string) $step) !== '') {
                $workflowSteps[] = [
                    'name' => trim($step),
                    'description' => trim($validated['step_descriptions'][$index] ?? ''),
                ];
            }
        }

        $components = array_values(array_filter(array_map('trim', explode(',', $validated['components'] ?? ''))));

        return [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'role' => $validated['role'],
            'summary' => $validated['summary'] ?? null,
            'challenge' => $validated['challenge'],
            'solution' => $validated['solution'],
            'result' => $validated['result'] ?? null,
            'volume' => $validated['volume'] ?? null,
            'components' => $components ?: null,
            'impact_highlights' => $impactHighlights,
            'workflow_steps' => $workflowSteps,
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
    }

    private function storeGalleryImages(Request $request, Project $project): void
    {
        $sortOrder = (int) $project->images()->max('sort_order');

        foreach ($request->file('gallery_images', []) as $file) {
            $project->images()->create([
                'path' => $file->store('projects/gallery', 'public'),
                'sort_order' => ++$sortOrder,
            ]);
        }
    }

    private function deleteGalleryImage(ProjectImage $image): void
    {
        if (!str_starts_with($image->path, 'http')) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();
    }
}
