<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectPublicController extends Controller
{
    public const SORTS = ['featured' => 'Featured first', 'newest' => 'Newest first'];

    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $search = $request->query('q');
        $sort = array_key_exists($request->query('sort'), self::SORTS) ? $request->query('sort') : 'featured';
        $filtering = ($selectedCategory && $selectedCategory !== 'All') || $search;

        $allProjects = Project::all(['id', 'category', 'is_featured']);
        $categoriesWithCount = $allProjects->groupBy('category')->map(fn ($group) => $group->count());
        $totals = [
            'case studies' => $allProjects->count(),
            'domains' => $categoriesWithCount->count(),
            'featured' => $allProjects->where('is_featured', true)->count(),
        ];

        $featuredProject = $filtering ? null : (Project::with('images')->where('is_featured', true)->orderBy('sort_order')->first() ?? Project::with('images')->orderBy('sort_order')->first());

        $query = Project::with('images')->when($featuredProject, fn ($q) => $q->whereKeyNot($featuredProject->id));

        if ($selectedCategory && $selectedCategory !== 'All') {
            $query->where('category', $selectedCategory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('challenge', 'like', "%{$search}%")
                  ->orWhere('solution', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $sort === 'newest'
            ? $query->latest()
            : $query->orderByDesc('is_featured')->orderBy('sort_order');

        $projects = $query->paginate(9)->withQueryString();

        return view('projects.index', compact(
            'projects',
            'categoriesWithCount',
            'selectedCategory',
            'search',
            'sort',
            'totals',
            'featuredProject'
        ));
    }

    public function show(string $slug): View
    {
        $project = Project::with('images')->where('slug', $slug)->firstOrFail();

        // Related or other case studies for navigation
        $otherProjects = Project::where('id', '!=', $project->id)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'otherProjects'));
    }
}
