<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Total views today
        $viewsToday = PostEvent::viewed()->today()->count();

        // Total views this week
        $viewsThisWeek = PostEvent::viewed()->lastDays(7)->count();

        // Total views this month
        $viewsThisMonth = PostEvent::viewed()->lastDays(30)->count();

        // Most viewed posts (last 7 days)
        $topPosts = Post::withCount([
            'events as views_count' => fn($q) => $q->viewed()->lastDays(7)
        ])
        ->having('views_count', '>', 0)
        ->orderByDesc('views_count')
        ->take(10)
        ->get();

        // Average engagement metrics (last 7 days)
        $avgEngagement = PostEvent::engaged()->lastDays(7)
        ->selectRaw('
        AVG(JSON_EXTRACT(data, "$.time_spent")) as avg_time,
        AVG(JSON_EXTRACT(data, "$.scroll_depth")) as avg_scroll
        ')
        ->first();

        // Views by hour (today) - for chart
        $viewsByHour = PostEvent::viewed()->today()
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Fill in missing hours with 0
        $viewsByHoursComplete = [];
        for ($i=0; $i < 24; $i++) {
            $viewsByHoursComplete[$i] = $viewsByHour[$i] ?? 0;
        }

        // Views by day (last 7 days) - for chart
        $viewsByDay = PostEvent::viewed()->lastDays(7)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in missing days with 0 (last 7 days)
        $viewsByDaysComplete = [];
        for ($i=6; $i>=0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $viewsByDaysComplete[$date] = $viewsByDay[$date] ?? 0;
        }



        // Top referrers
        $topReferrers = PostEvent::viewed()->lastDays(7)
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Recent activity (last 20 events)
        $recentActivity = PostEvent::with(['post', 'user'])
            ->latest('created_at')
            ->take(20)
            ->get();

            return view('admin.analytics', compact(
                'viewsToday',
                'viewsThisWeek',
                'viewsThisMonth',
                'topPosts',
                'avgEngagement',
                'viewsByHoursComplete',
                'viewsByDaysComplete',
                'topReferrers',
                'recentActivity'
            ));
    }
}
