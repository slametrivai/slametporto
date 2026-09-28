<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Certification;
use App\Models\Project;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function show(): View
    {
        $careers = Career::orderBy('sort_order', 'asc')->get();
        $certifications = Certification::all();
        $featuredProjects = Project::where('is_featured', true)->orderBy('sort_order', 'asc')->get();

        return view('resume', compact('careers', 'certifications', 'featuredProjects'));
    }
}
