<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Event;
use App\Models\Forum;
use App\Models\Article;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page / Home Route
Route::get('/', function () {
    $totalUsers = User::count();
    $totalEvents = Event::count();
    $totalDiscussions = Forum::count();

    $featuredEvents = Event::where('date', '>=', Carbon::today()->toDateString())
        ->orderBy('date', 'asc')
        ->take(3)
        ->get();

    $latestArticles = Article::orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    return view('welcome', compact('totalUsers', 'totalEvents', 'totalDiscussions', 'featuredEvents', 'latestArticles'));
})->name('home');

// Dashboard Route (Role-based logic handled inside DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Authentication Protected Routes
Route::middleware('auth')->group(function () {
    
    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Forum Submissions & Actions
    Route::post('/forums', [ForumController::class, 'store'])->name('forums.store');
    Route::put('/forums/{id}', [ForumController::class, 'update'])->name('forums.update');
    Route::delete('/forums/{id}', [ForumController::class, 'destroy'])->name('forums.destroy');
    Route::post('/forums/{id}/comments', [ForumController::class, 'storeComment'])->name('forums.comments.store');
    Route::delete('/comments/{id}', [ForumController::class, 'destroyComment'])->name('forums.comments.destroy');
    Route::post('/forums/{id}/like', [ForumController::class, 'toggleLike'])->name('forums.like');
    Route::post('/forums/{id}/report', [ForumController::class, 'reportForum'])->name('forums.report');

    // Event Bookings & Attendance (Admin / Moderator)
    Route::post('/events/{id}/join', [EventController::class, 'join'])->name('events.join');
    Route::post('/events/{id}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::post('/events/registrations/{id}/attendance', [EventController::class, 'markAttendance'])
        ->name('events.attendance')
        ->middleware('role:admin,moderator');

    // Event Management (Admin / Moderator)
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('role:admin,moderator');
    Route::post('/events', [EventController::class, 'store'])->name('events.store')->middleware('role:admin,moderator');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware('role:admin,moderator');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update')->middleware('role:admin,moderator');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy')->middleware('role:admin,moderator');

    // Article Management (Admin / Moderator)
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('role:admin,moderator');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store')->middleware('role:admin,moderator');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('role:admin,moderator');
    Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update')->middleware('role:admin,moderator');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('role:admin,moderator');

    // Tolerance Quiz
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result/{id}', [QuizController::class, 'showResult'])->name('quiz.result');

    // Anonymity Reports Management (Admin / Moderator)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:admin,moderator');
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])
        ->name('reports.status')
        ->middleware('role:admin,moderator');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Public / Guest Accessible Feature Routes
Route::get('/forums', [ForumController::class, 'index'])->name('forums.index');
Route::get('/forums/{id}', [ForumController::class, 'show'])->name('forums.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/leaderboard', [BadgeController::class, 'index'])->name('badges.index');

// Reports Submission (Open to guest for fully anonymous submissions, or users)
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

require __DIR__.'/auth.php';
