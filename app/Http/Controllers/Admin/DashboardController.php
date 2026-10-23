<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const SPARK_WEEKS = 10;

    private const CHART_WEEKS = 12;

    public function index(): View
    {
        $monthAgo = now()->subDays(30);
        $chartStart = now()->startOfWeek()->subWeeks(self::CHART_WEEKS - 1);

        // Weekly buckets share one query per model; sparklines reuse the tail of the chart window.
        $projectDates = Project::where('created_at', '>=', $chartStart)->pluck('created_at');
        $postDates = Post::where('status', 'published')->where('published_at', '>=', $chartStart)->pluck('published_at');
        $inquiryDates = Inquiry::where('created_at', '>=', $chartStart)->pluck('created_at');
        $clientDates = Client::where('created_at', '>=', $chartStart)->pluck('created_at');

        $projectWeeks = $this->weeklyCounts($projectDates, $chartStart);
        $postWeeks = $this->weeklyCounts($postDates, $chartStart);

        $metrics = [
            [
                'label' => 'Studi kasus', 'icon' => 'briefcase', 'route' => 'admin.projects.index',
                'value' => Project::count(),
                'delta' => $projectDates->filter(fn ($d) => $d >= $monthAgo)->count(),
                'spark' => array_slice($projectWeeks, -self::SPARK_WEEKS),
            ],
            [
                'label' => 'Artikel terbit', 'icon' => 'pen-line', 'route' => 'admin.posts.index',
                'value' => Post::where('status', 'published')->count(),
                'delta' => $postDates->filter(fn ($d) => $d >= $monthAgo)->count(),
                'spark' => array_slice($postWeeks, -self::SPARK_WEEKS),
            ],
            [
                'label' => 'Pesan masuk', 'icon' => 'inbox', 'route' => 'admin.inquiries.index',
                'value' => Inquiry::count(),
                'delta' => $inquiryDates->filter(fn ($d) => $d >= $monthAgo)->count(),
                'spark' => array_slice($this->weeklyCounts($inquiryDates, $chartStart), -self::SPARK_WEEKS),
            ],
            [
                'label' => 'Klien tampil', 'icon' => 'building-2', 'route' => 'admin.clients.index',
                'value' => Client::where('is_active', true)->count(),
                'delta' => $clientDates->filter(fn ($d) => $d >= $monthAgo)->count(),
                'spark' => array_slice($this->weeklyCounts($clientDates, $chartStart), -self::SPARK_WEEKS),
            ],
        ];

        $chart = collect(range(0, self::CHART_WEEKS - 1))->map(fn ($i) => [
            'week' => $chartStart->copy()->addWeeks($i),
            'projects' => $projectWeeks[$i],
            'posts' => $postWeeks[$i],
        ]);

        $newInquiries = Inquiry::where('status', 'new');
        $unanswered = [
            'count' => (clone $newInquiries)->count(),
            'oldest' => (clone $newInquiries)->oldest()->value('created_at'),
            'items' => (clone $newInquiries)->latest()->take(3)->get(),
        ];

        $recentContent = Project::latest('updated_at')->take(6)->get()
            ->map(fn (Project $p) => [
                'type' => 'project',
                'title' => $p->title,
                'status' => 'published',
                'featured' => $p->is_featured,
                'views' => null,
                'updated_at' => $p->updated_at,
                'edit_url' => route('admin.projects.edit', $p),
            ])
            ->concat(Post::latest('updated_at')->take(6)->get()->map(fn (Post $p) => [
                'type' => 'post',
                'title' => $p->title,
                'status' => $p->status === 'published' && $p->published_at?->isFuture() ? 'scheduled' : $p->status,
                'featured' => false,
                'views' => $p->views_count,
                'updated_at' => $p->updated_at,
                'edit_url' => route('admin.posts.edit', $p),
            ]))
            ->sortByDesc('updated_at')
            ->take(8)
            ->values();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'chart' => $chart,
            'unanswered' => $unanswered,
            'recentContent' => $recentContent,
            'lastUpdated' => $recentContent->first()['updated_at'] ?? null,
        ]);
    }

    /**
     * Count dates into consecutive weeks starting at $start (index 0 = oldest week).
     *
     * @return array<int, int>
     */
    private function weeklyCounts(Collection $dates, Carbon $start): array
    {
        $counts = array_fill(0, self::CHART_WEEKS, 0);

        foreach ($dates as $date) {
            $index = intdiv((int) $start->diffInDays(Carbon::parse($date)), 7);
            if ($index >= 0 && $index < self::CHART_WEEKS) {
                $counts[$index]++;
            }
        }

        return $counts;
    }
}
