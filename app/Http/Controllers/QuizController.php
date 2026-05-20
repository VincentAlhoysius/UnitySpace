<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display the self-assessment quiz form.
     */
    public function index()
    {
        $quiz = Quiz::with('questions.answers')->first();

        if (!$quiz) {
            return redirect()->route('dashboard')->with('error', 'Quiz tidak ditemukan. Silakan jalankan seeder.');
        }

        $user = Auth::user();
        $history = QuizResult::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('quiz.index', compact('quiz', 'history'));
    }

    /**
     * Store and grade the quiz results.
     */
    public function submit(Request $request)
    {
        $quiz = Quiz::first();
        if (!$quiz) {
            abort(404, 'Quiz tidak ditemukan.');
        }

        $request->validate([
            'answers' => 'required|array|size:10',
            'answers.*' => 'required|integer|exists:quiz_answers,id',
        ]);

        $user = Auth::user();
        $totalScore = 0;
        $answersSummary = [];

        foreach ($request->answers as $questionId => $answerId) {
            $question = QuizQuestion::findOrFail($questionId);
            $answer = QuizAnswer::findOrFail($answerId);

            $totalScore += $answer->points_value;

            $answersSummary[] = [
                'question_text' => $question->question_text,
                'dimension' => $question->dimension,
                'selected_option' => $answer->option_text,
                'points' => $answer->points_value,
            ];
        }

        // Determine Level
        // Min possible score: 10, Max: 50
        if ($totalScore >= 40) {
            $category = 'tinggi';
        } elseif ($totalScore >= 25) {
            $category = 'sedang';
        } else {
            $category = 'rendah';
        }

        // Create Quiz Result
        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $totalScore,
            'category' => $category,
            'answers_summary' => $answersSummary,
        ]);

        // Award gamification points: +30 points
        $user->increment('points', 30);

        // Check badges
        $this->checkAndAwardBadges($user, $category);

        return redirect()->route('quiz.result', $result->id)->with('success', 'Self-assessment selesai! Anda mendapatkan +30 poin.');
    }

    /**
     * Show result details page with Chart.js visualization.
     */
    public function showResult($id)
    {
        $result = QuizResult::findOrFail($id);

        if (Auth::id() !== $result->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Interpretations
        $interpretation = '';
        if ($result->category === 'tinggi') {
            $interpretation = 'Luar biasa! Anda menunjukkan komitmen tinggi terhadap toleransi, empati, dan inklusivitas beragama di kampus. Anda menghargai perbedaan sebagai kekuatan sosial dan siap menjadi pelopor perdamaian.';
        } elseif ($result->category === 'sedang') {
            $interpretation = 'Bagus! Anda memiliki sikap toleransi yang cukup baik. Namun, ada beberapa aspek stereotip atau keraguan sosial yang perlu Anda sadari dan perbaiki demi mewujudkan sikap inklusif yang seutuhnya.';
        } else {
            $interpretation = 'Perhatian. Skor Anda mengindikasikan tingkat toleransi atau keterbukaan lintas iman yang masih kurang. Hal ini mungkin dipengaruhi oleh kurangnya interaksi sosial lintas agama atau adanya prasangka tertentu. Mari lebih aktif mengikuti kegiatan dialog dan kemanusiaan!';
        }

        // Aggregate scores by dimension for Chart.js
        $dimensionScores = [];
        foreach ($result->answers_summary as $ans) {
            $dim = $ans['dimension'] ?? 'Lainnya';
            if (!isset($dimensionScores[$dim])) {
                $dimensionScores[$dim] = 0;
            }
            $dimensionScores[$dim] += $ans['points'];
        }

        return view('quiz.result', compact('result', 'interpretation', 'dimensionScores'));
    }

    /**
     * Helper to award badges dynamically based on quiz results.
     */
    private function checkAndAwardBadges($user, $quizCategory)
    {
        // Badge 3: Peace Ambassador (requires "Tinggi" result or points >= 200)
        $ambassadorBadge = \App\Models\Badge::where('badge_type', 'ambassador')->first();
        if ($ambassadorBadge && !$user->badges->contains($ambassadorBadge->id)) {
            if ($quizCategory === 'tinggi' || $user->points >= $ambassadorBadge->point_requirement) {
                $user->badges()->attach($ambassadorBadge->id);
                $user->notify(new \App\Notifications\BadgeEarnedNotification($ambassadorBadge));
            }
        }
    }
}
