<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create simulated users
        $teacher = User::create([
            'name' => 'Budi Utomo, S.Kom.',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        $student1 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // 2. Create Quiz
        $quiz = Quiz::create([
            'title' => 'Kuis Logika & Teknologi',
            'description' => 'Uji wawasan Anda seputar dunia logika matematika sederhana dan teknologi informasi dasar.',
            'creator_id' => $teacher->id,
            'banner_theme' => 'purple',
        ]);

        // 3. Create Questions
        Question::create([
            'quiz_id' => $quiz->id,
            'text' => 'Bahasa pemrograman apakah yang menjadi fondasi utama dalam pembuatan framework Laravel?',
            'options' => ['Python', 'PHP', 'JavaScript', 'C++'],
            'correct_answer' => 1, // PHP
            'time_limit' => 30,
            'points' => 100,
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'text' => 'Manakah di bawah ini yang merupakan planet terbesar di tata surya kita?',
            'options' => ['Bumi', 'Mars', 'Saturnus', 'Yupiter'],
            'correct_answer' => 3, // Yupiter
            'time_limit' => 20,
            'points' => 100,
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'text' => 'Berapakah hasil dari operasi perhitungan matematika sederhana: 15 + (4 x 5)?',
            'options' => ['35', '95', '45', '25'],
            'correct_answer' => 0, // 35
            'time_limit' => 30,
            'points' => 100,
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'text' => 'Protokol jaringan terenkripsi aman yang biasa digunakan untuk mentransfer data halaman web adalah...',
            'options' => ['HTTP', 'HTTPS', 'FTP', 'SMTP'],
            'correct_answer' => 1, // HTTPS
            'time_limit' => 20,
            'points' => 100,
        ]);
    }
}
