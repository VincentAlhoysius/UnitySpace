<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display a listing of forum threads with searching and filtering.
     */
    public function index(Request $request)
    {
        $query = Forum::with(['user', 'comments', 'likes']);

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search in Title or Content
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderBy('likes_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $forums = $query->paginate(10)->withQueryString();
        
        $categories = [
            'Toleransi',
            'Pengalaman Kampus',
            'Keberagaman',
            'Diskusi Sosial',
            'Event Kampus'
        ];

        return view('forums.index', compact('forums', 'categories'));
    }

    /**
     * Store a newly created forum thread.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:Toleransi,Pengalaman Kampus,Keberagaman,Diskusi Sosial,Event Kampus',
        ]);

        $user = Auth::user();

        $forum = Forum::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
        ]);

        // Award gamification points: +10 for creating a thread
        $user->increment('points', 10);

        // Check and award badges
        $this->checkAndAwardBadges($user);

        return redirect()->route('forums.show', $forum->id)->with('success', 'Diskusi berhasil dibuat! Anda mendapatkan +10 poin.');
    }

    /**
     * Display a specific forum thread with its comments and nested replies.
     */
    public function show($id)
    {
        $forum = Forum::with(['user', 'likes', 'rootComments.user', 'rootComments.replies.user'])->findOrFail($id);
        
        return view('forums.show', compact('forum'));
    }

    /**
     * Update an existing forum thread.
     */
    public function update(Request $request, $id)
    {
        $forum = Forum::findOrFail($id);

        // Authorization check: Only author or admin/moderator can update
        if (Auth::id() !== $forum->user_id && !Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Anda tidak diizinkan mengubah diskusi ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:Toleransi,Pengalaman Kampus,Keberagaman,Diskusi Sosial,Event Kampus',
        ]);

        $forum->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
        ]);

        return redirect()->route('forums.show', $forum->id)->with('success', 'Diskusi berhasil diperbarui.');
    }

    /**
     * Delete a forum thread.
     */
    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);

        // Authorization check
        if (Auth::id() !== $forum->user_id && !Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Anda tidak diizinkan menghapus diskusi ini.');
        }

        $forum->delete();

        return redirect()->route('forums.index')->with('success', 'Diskusi berhasil dihapus.');
    }

    /**
     * Store a comment or a reply.
     */
    public function storeComment(Request $request, $forumId)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        $forum = Forum::findOrFail($forumId);
        $user = Auth::user();

        $comment = ForumComment::create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        // Award gamification points: +5 for commenting
        $user->increment('points', 5);

        // Check badges
        $this->checkAndAwardBadges($user);

        // If it is a reply to another user, we can send a notification
        if ($request->parent_id) {
            $parentComment = ForumComment::findOrFail($request->parent_id);
            if ($parentComment->user_id !== $user->id) {
                // Send db notification
                $parentComment->user->notify(new \App\Notifications\ForumReplyNotification($forum, $user, 'reply'));
            }
        } else {
            // Reply to the thread owner
            if ($forum->user_id !== $user->id) {
                $forum->user->notify(new \App\Notifications\ForumReplyNotification($forum, $user, 'comment'));
            }
        }

        return redirect()->route('forums.show', $forum->id)->with('success', 'Komentar ditambahkan! Anda mendapatkan +5 poin.');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment($id)
    {
        $comment = ForumComment::findOrFail($id);

        if (Auth::id() !== $comment->user_id && !Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Anda tidak diizinkan menghapus komentar ini.');
        }

        $forumId = $comment->forum_id;
        $comment->delete();

        return redirect()->route('forums.show', $forumId)->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Toggle upvote/like on a forum thread.
     */
    public function toggleLike($id)
    {
        $forum = Forum::findOrFail($id);
        $userId = Auth::id();

        $like = ForumLike::where('forum_id', $forum->id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $forum->decrement('likes_count');
            $message = 'Upvote dibatalkan.';
        } else {
            ForumLike::create([
                'forum_id' => $forum->id,
                'user_id' => $userId,
            ]);
            $forum->increment('likes_count');
            $message = 'Diskusi di-upvote!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Report an offensive forum thread.
     */
    public function reportForum($id)
    {
        $forum = Forum::findOrFail($id);
        $forum->increment('reports_count');

        return redirect()->back()->with('success', 'Terima kasih atas laporan Anda. Moderator akan meninjau diskusi ini.');
    }

    /**
     * Helper to check and award badges dynamically based on points and contributions.
     */
    private function checkAndAwardBadges($user)
    {
        // Badges checks
        $forumPostsCount = $user->forums()->count();
        $commentsCount = $user->forumComments()->count();
        $totalContributions = $forumPostsCount + $commentsCount;

        // Badge 2: Harmony Contributor (requires totalContributions >= 5 or points >= 100)
        $harmonyBadge = \App\Models\Badge::where('badge_type', 'contributor')->first();
        if ($harmonyBadge && !$user->badges->contains($harmonyBadge->id)) {
            if ($totalContributions >= 5 || $user->points >= $harmonyBadge->point_requirement) {
                $user->badges()->attach($harmonyBadge->id);
                // Notify user
                $user->notify(new \App\Notifications\BadgeEarnedNotification($harmonyBadge));
            }
        }

        // Badge 3: Peace Ambassador (requires points >= 200)
        $ambassadorBadge = \App\Models\Badge::where('badge_type', 'ambassador')->first();
        if ($ambassadorBadge && !$user->badges->contains($ambassadorBadge->id)) {
            if ($user->points >= $ambassadorBadge->point_requirement) {
                $user->badges()->attach($ambassadorBadge->id);
                $user->notify(new \App\Notifications\BadgeEarnedNotification($ambassadorBadge));
            }
        }
    }
}
