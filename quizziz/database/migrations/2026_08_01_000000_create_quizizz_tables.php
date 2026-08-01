<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Quizzes
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('banner_theme')->default('purple'); // e.g. purple, indigo, pink, violet
            $table->timestamps();
        });

        // 2. Questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('text');
            $table->json('options'); // JSON array of strings: ["Option A", "Option B", ...]
            $table->integer('correct_answer'); // Index of correct option (0-3)
            $table->integer('time_limit')->default(30); // Seconds
            $table->integer('points')->default(100);
            $table->timestamps();
        });

        // 3. Game Sessions (Live rooms)
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->string('code')->unique(); // 6-digit pin code
            $table->enum('status', ['waiting', 'active', 'finished'])->default('waiting');
            $table->foreignId('current_question_id')->nullable()->constrained('questions')->onDelete('set null');
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('question_active_since')->nullable(); // Track when current question started
            $table->timestamps();
        });

        // 4. Game Players (Joined students)
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained('game_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('streak')->default(0);
            $table->timestamps();
            
            $table->unique(['game_session_id', 'user_id']);
        });

        // 5. Player Answers
        Schema::create('player_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_player_id')->constrained('game_players')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('selected_option'); // Index chosen (0-3)
            $table->boolean('is_correct');
            $table->integer('score_earned');
            $table->integer('time_taken'); // In seconds
            $table->timestamps();
            
            $table->unique(['game_player_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_answers');
        Schema::dropIfExists('game_players');
        Schema::dropIfExists('game_sessions');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
