<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Certification;
use App\Models\Client;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $careers = Career::orderBy('sort_order', 'asc')->get();
        $projects = Project::orderBy('sort_order', 'asc')->get();
        $clients = Client::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $certifications = Certification::all();
        $latestPosts = Post::published()->with('category')->latest('published_at')->take(3)->get();
        
        $projectCategories = $projects->pluck('category')->unique()->values();

        return view('home', compact(
            'careers',
            'projects',
            'clients',
            'certifications',
            'latestPosts',
            'projectCategories'
        ));
    }
}
