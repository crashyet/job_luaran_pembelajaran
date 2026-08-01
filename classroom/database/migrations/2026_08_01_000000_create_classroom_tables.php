<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Classes table
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable();
            $table->string('subject')->nullable();
            $table->string('room')->nullable();
            $table->string('code')->unique();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('banner_theme')->default('indigo'); // e.g. blue, indigo, emerald, purple, rose
            $table->timestamps();
        });

        // 2. Class-Student Pivot table
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Posts table (Announcements and Assignments)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable(); // Required for assignments
            $table->text('content');
            $table->enum('type', ['announcement', 'assignment'])->default('announcement');
            $table->integer('points')->nullable(); // e.g. 100
            $table->dateTime('due_date')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestamps();
        });

        // 4. Comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // 5. Submissions table
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->text('content'); // Link or text submission
            $table->integer('grade')->nullable();
            $table->enum('status', ['turned_in', 'graded'])->default('turned_in');
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('classes');
    }
};
