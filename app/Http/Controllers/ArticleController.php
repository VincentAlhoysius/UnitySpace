<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of educational articles.
     */
    public function index(Request $request)
    {
        $query = Article::with('user');

        // Category Filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

        $categories = [
            'toleransi' => 'Toleransi',
            'keberagaman' => 'Keberagaman',
            'anti diskriminasi' => 'Anti Diskriminasi',
            'SDGs' => 'SDGs & Inklusi',
            'hak asasi manusia' => 'Hak Asasi Manusia'
        ];

        return view('articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        return view('articles.create');
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:toleransi,keberagaman,anti diskriminasi,SDGs,hak asasi manusia',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Article::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    /**
     * Display the specified article.
     */
    public function show($id)
    {
        $article = Article::with('user')->findOrFail($id);
        
        // Find related articles (same category, excluding current)
        $relatedArticles = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        if (Auth::id() !== $article->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        if (Auth::id() !== $article->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:toleransi,keberagaman,anti diskriminasi,SDGs,hak asasi manusia',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $article->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
        ]);

        return redirect()->route('articles.show', $article->id)->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified article.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if (Auth::id() !== $article->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
