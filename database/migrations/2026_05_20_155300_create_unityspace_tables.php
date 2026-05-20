<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Forums Table
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('category'); // Toleransi, Pengalaman Kampus, Keberagaman, Diskusi Sosial, Event Kampus
            $table->integer('likes_count')->default(0);
            $table->integer('reports_count')->default(0);
            $table->timestamps();
        });

        // Forum Comments Table
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained('forums')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('forum_comments')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Organizer
            $table->string('title');
            $table->text('description');
            $table->string('category'); // seminar, volunteer, bakti sosial, dialog lintas agama
            $table->string('poster')->nullable();
            $table->integer('quota');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->integer('points_reward')->default(20);
            $table->timestamps();
        });

        // Event Registrations Table
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('registered'); // registered, attended, cancelled
            $table->timestamps();
        });

        // Articles Table
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Author
            $table->string('title');
            $table->longText('content');
            $table->string('category'); // toleransi, keberagaman, anti diskriminasi, SDGs, hak asasi manusia
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        // Quizzes Table
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Quiz Questions Table
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->string('dimension')->nullable(); // e.g., tolerance, empathy, etc.
            $table->timestamps();
        });

        // Quiz Answers Options Table
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->string('option_text'); // e.g. Sangat Setuju, Setuju
            $table->integer('points_value'); // e.g. 5, 4, 3, 2, 1
            $table->timestamps();
        });

        // Quiz Results Table
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('score');
            $table->string('category'); // rendah, sedang, tinggi
            $table->json('answers_summary')->nullable(); // to log the responses
            $table->timestamps();
        });

        // Reports Table
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->onDelete('set null'); // nullable for anonymous
            $table->string('report_type'); // intoleransi, diskriminasi
            $table->text('description');
            $table->string('evidence_path')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, resolved
            $table->timestamps();
        });

        // Badges Table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('point_requirement');
            $table->string('badge_type'); // volunteer, contributor, ambassador, etc.
            $table->timestamps();
        });

        // User Badges Table
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();
        });

        // Forum Upvotes / Likes (to handle like/upvote system)
        Schema::create('forum_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained('forums')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['forum_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_likes');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('quiz_results');
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('forum_comments');
        Schema::dropIfExists('forums');
    }
};
