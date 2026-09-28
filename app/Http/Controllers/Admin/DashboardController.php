<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Certification;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'active_projects' => Project::count(),
            'total_clients' => Client::where('is_active', true)->count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'new_inquiries' => Inquiry::where('status', 'new')->count(),
            'total_careers' => Career::count(),
            'total_certifications' => Certification::count(),
        ];

        $recentInquiries = Inquiry::latest()->take(5)->get();
        $recentProjects = Project::latest()->take(5)->get();
        $recentPosts = Post::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentInquiries', 'recentProjects', 'recentPosts'));
    }
}
