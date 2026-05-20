<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Forum;
use App\Models\Article;
use App\Models\Report;
use App\Models\QuizResult;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isModerator()) {
            return $this->moderatorDashboard();
        } else {
            return $this->mahasiswaDashboard();
        }
    }

    /**
     * Admin dashboard analytics and overview.
     */
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalForums = Forum::count();
        $totalReports = Report::count();

        $recentReports = Report::orderBy('created_at', 'desc')->take(5)->get();
        $recentEvents = Event::orderBy('date', 'asc')->where('date', '>=', Carbon::today()->toDateString())->take(5)->get();

        // Chart data: Forum categories distribution
        $forumStats = Forum::select('category', \DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Chart data: Reports status
        $reportStats = Report::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $leaderboard = User::orderBy('points', 'desc')->take(5)->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalEvents',
            'totalForums',
            'totalReports',
            'recentReports',
            'recentEvents',
            'forumStats',
            'reportStats',
            'leaderboard'
        ));
    }

    /**
     * Moderator dashboard overview.
     */
    private function moderatorDashboard()
    {
        $pendingReports = Report::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $reviewedReports = Report::where('status', 'reviewed')->orderBy('created_at', 'desc')->get();
        
        $reportedForums = Forum::where('reports_count', '>', 0)->orderBy('reports_count', 'desc')->get();

        $totalReports = Report::count();
        $resolvedReportsCount = Report::where('status', 'resolved')->count();

        return view('dashboard.moderator', compact(
            'pendingReports',
            'reviewedReports',
            'reportedForums',
            'totalReports',
            'resolvedReportsCount'
        ));
    }

    /**
     * Mahasiswa dashboard overview.
     */
    private function mahasiswaDashboard()
    {
        $user = Auth::user();

        $recentEvents = Event::orderBy('date', 'asc')
            ->where('date', '>=', Carbon::today()->toDateString())
            ->take(3)
            ->get();

        $recentForums = Forum::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $recentArticles = Article::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $userResults = QuizResult::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $allBadges = Badge::all();
        $userBadgeIds = $user->badges->pluck('id')->toArray();

        // Participations
        $registeredEventsCount = $user->registrations()->where('status', 'registered')->count();
        $attendedEventsCount = $user->registrations()->where('status', 'attended')->count();
        $forumPostsCount = $user->forums()->count();
        $commentsCount = $user->forumComments()->count();

        // Leaderboard
        $leaderboard = User::orderBy('points', 'desc')->take(5)->get();

        return view('dashboard.mahasiswa', compact(
            'recentEvents',
            'recentForums',
            'recentArticles',
            'userResults',
            'allBadges',
            'userBadgeIds',
            'registeredEventsCount',
            'attendedEventsCount',
            'forumPostsCount',
            'commentsCount',
            'leaderboard'
        ));
    }
}
