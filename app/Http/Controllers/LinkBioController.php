<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class LinkBioController extends Controller
{
    public function show(): View
    {
        $projects = Project::orderBy('sort_order')->get();
        $featured = $projects->firstWhere('is_featured', true) ?? $projects->first();
        $nextProject = $featured
            ? ($projects->where('id', '!=', $featured->id)->firstWhere('is_featured', true) ?? $projects->firstWhere('id', '!=', $featured->id))
            : null;

        return view('linkbio', [
            'featured' => $featured,
            'nextProject' => $nextProject,
            'latestPost' => Post::published()->latest('published_at')->first(),
        ]);
    }
}
