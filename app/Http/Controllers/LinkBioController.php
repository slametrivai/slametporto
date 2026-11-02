<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class LinkBioController extends Controller
{
    public function show(): View
    {
        $projects = Project::with('images')->orderByDesc('is_featured')->orderBy('sort_order')->get();

        return view('linkbio', [
            'featured' => $projects->first(),
            'moreProjects' => $projects->slice(1, 2)->values(),
            'latestPost' => Post::published()->latest('published_at')->first(),
        ]);
    }
}
