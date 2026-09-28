<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectPublicController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');
        $search = $request->query('q');

        $query = Project::query()->orderBy('sort_order', 'asc');

        if ($selectedCategory && $selectedCategory !== 'All') {
            $query->where('category', $selectedCategory);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('challenge', 'like', "%{$search}%")
                  ->orWhere('solution', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(9)->withQueryString();

        // Get distinct categories with count
        $allProjects = Project::all();
        $categoriesWithCount = $allProjects->groupBy('category')->map(fn($group) => $group->count());

        $featuredProject = Project::where('is_featured', true)->first() ?? Project::first();

        return view('projects.index', compact(
            'projects',
            'categoriesWithCount',
            'selectedCategory',
            'search',
            'featuredProject'
        ));
    }

    public function show(string $slug): View
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        // Related or other case studies for navigation
        $otherProjects = Project::where('id', '!=', $project->id)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'otherProjects'));
    }
}
