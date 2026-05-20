<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    /**
     * Display the gamification leaderboard and user badges.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch top users by points
        $leaderboard = User::orderBy('points', 'desc')->take(10)->get();

        // Fetch all badges
        $badges = Badge::all();
        
        // Fetch user earned badge IDs
        $earnedBadgeIds = $user ? $user->badges->pluck('id')->toArray() : [];

        return view('badges.index', compact('leaderboard', 'badges', 'earnedBadgeIds'));
    }
}
