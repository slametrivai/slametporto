<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'role' => 'required|string|max:150',
            'challenge' => 'required|string',
            'solution' => 'required|string',
            'impact_metrics' => 'nullable|array',
            'impact_labels' => 'nullable|array',
            'workflow_steps' => 'nullable|array',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('projects', 'public');
        }

        // Process impact highlights array of objects
        $impactHighlights = [];
        if (!empty($validated['impact_metrics']) && is_array($validated['impact_metrics'])) {
            foreach ($validated['impact_metrics'] as $index => $metric) {
                if (!empty(trim($metric))) {
                    $impactHighlights[] = [
                        'metric' => trim($metric),
                        'label' => trim($validated['impact_labels'][$index] ?? ''),
                    ];
                }
            }
        }

        // Process workflow steps
        $workflowSteps = [];
        if (!empty($validated['workflow_steps']) && is_array($validated['workflow_steps'])) {
            foreach ($validated['workflow_steps'] as $step) {
                if (!empty(trim($step))) {
                    $workflowSteps[] = trim($step);
                }
            }
        }

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Project::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'role' => $validated['role'],
            'challenge' => $validated['challenge'],
            'solution' => $validated['solution'],
            'impact_highlights' => $impactHighlights,
            'workflow_steps' => $workflowSteps,
            'cover_image' => $coverImagePath,
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'role' => 'required|string|max:150',
            'challenge' => 'required|string',
            'solution' => 'required|string',
            'impact_metrics' => 'nullable|array',
            'impact_labels' => 'nullable|array',
            'workflow_steps' => 'nullable|array',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Process impact highlights
        $impactHighlights = [];
        if (!empty($validated['impact_metrics']) && is_array($validated['impact_metrics'])) {
            foreach ($validated['impact_metrics'] as $index => $metric) {
                if (!empty(trim($metric))) {
                    $impactHighlights[] = [
                        'metric' => trim($metric),
                        'label' => trim($validated['impact_labels'][$index] ?? ''),
                    ];
                }
            }
        }

        // Process workflow steps
        $workflowSteps = [];
        if (!empty($validated['workflow_steps']) && is_array($validated['workflow_steps'])) {
            foreach ($validated['workflow_steps'] as $step) {
                if (!empty(trim($step))) {
                    $workflowSteps[] = trim($step);
                }
            }
        }

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'role' => $validated['role'],
            'challenge' => $validated['challenge'],
            'solution' => $validated['solution'],
            'impact_highlights' => $impactHighlights,
            'workflow_steps' => $workflowSteps,
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('cover_image')) {
            if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Studi kasus proyek berhasil diperbarui!');
    }

    public function destroy(Project $project): JsonResponse|RedirectResponse
    {
        if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
            Storage::disk('public')->delete($project->cover_image);
        }

        $project->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proyek berhasil dihapus!']);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus!');
    }
}
