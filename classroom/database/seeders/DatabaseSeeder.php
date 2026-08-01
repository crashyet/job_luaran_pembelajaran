<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Submission;
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

        // 2. Create Classroom
        $classroom = Classroom::create([
            'name' => 'Desain Grafis Dasar',
            'section' => 'Kelas XI-RPL 1',
            'subject' => 'Teknologi Informasi',
            'room' => 'Lab Komputer 2',
            'code' => 'GRAP11',
            'teacher_id' => $teacher->id,
            'banner_theme' => 'indigo',
        ]);

        // Associate students to class
        $classroom->students()->attach([$student1->id, $student2->id]);

        // 3. Create Announcement
        $announcement = Post::create([
            'class_id' => $classroom->id,
            'user_id' => $teacher->id,
            'title' => null,
            'content' => 'Selamat datang di kelas Desain Grafis Dasar! Silakan pelajari materi dasar tentang layouting sebelum kita mulai sesi praktek minggu depan.',
            'type' => 'announcement',
        ]);

        // Add comments to announcement
        Comment::create([
            'post_id' => $announcement->id,
            'user_id' => $student1->id,
            'content' => 'Baik Pak, apakah ada modul PDF yang bisa dibaca terlebih dahulu?',
        ]);

        Comment::create([
            'post_id' => $announcement->id,
            'user_id' => $teacher->id,
            'content' => '@Rian Ya, modul akan segera saya unggah di menu Tugas Kelas besok.',
        ]);

        // 4. Create Assignment
        $assignment = Post::create([
            'class_id' => $classroom->id,
            'user_id' => $teacher->id,
            'title' => 'Tugas 1: Membuat Wireframe Landing Page',
            'content' => 'Buatlah rancangan layout (wireframe) landing page untuk website e-commerce. Anda boleh membuatnya menggunakan kertas (lalu difoto) atau tools digital seperti Figma. Kumpulkan tautan/link atau teks deskripsi pengerjaan Anda.',
            'type' => 'assignment',
            'points' => 100,
            'due_date' => now()->addDays(7),
        ]);

        // Rian's submission (Graded)
        Submission::create([
            'post_id' => $assignment->id,
            'student_id' => $student1->id,
            'content' => 'Berikut link Figma wireframe landing page saya: figma.com/file/rian-landing-page',
            'grade' => 92,
            'status' => 'graded',
            'submitted_at' => now()->subDays(1),
            'feedback' => 'Bagus sekali Rian! Penataan layout-nya sudah seimbang dan navigasi jelas.',
        ]);

        // Siti's submission (Turned In, not graded)
        Submission::create([
            'post_id' => $assignment->id,
            'student_id' => $student2->id,
            'content' => 'Tugas wireframe sudah saya buat di kertas pak. Ini deskripsi konsepnya: Desain minimalis dengan hero section besar di atas, disusul kategori produk, lalu testimoni pelanggan.',
            'grade' => null,
            'status' => 'turned_in',
            'submitted_at' => now()->subHours(2),
            'feedback' => null,
        ]);
    }
}
