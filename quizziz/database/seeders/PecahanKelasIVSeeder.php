<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class PecahanKelasIVSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first() ?? User::first();
        if (!$teacher) {
            $this->command->error('Tidak ditemukan user guru!');
            return;
        }

        $quiz = Quiz::updateOrCreate(
            ['code' => 'QZ-PECAHAN4'],
            [
                'title' => 'Operasi Hitung Pecahan - Kelas IV SD (Kuis Adaptif)',
                'description' => 'Kuis matematika adaptif 10 level untuk menguji dan memperkuat pemahaman operasi hitung pecahan secara bertahap.',
                'creator_id' => $teacher->id,
                'banner_theme' => 'blue',
            ]
        );

        // Hapus soal lama dari kuis ini jika sudah ada
        $quiz->questions()->delete();

        $csvPath = __DIR__ . '/master_bank_soal_pecahan_kelas_4_sd.csv';
        if (!file_exists($csvPath)) {
            $csvPath = base_path('../master_bank_soal_pecahan_kelas_4_sd.csv');
        }

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di: {$csvPath}");
            return;
        }

        if (($handle = fopen($csvPath, 'r')) !== false) {
            $header = fgetcsv($handle, 2000, ',');
            $headerMap = array_flip($header);

            $count = 0;
            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                if (count($row) < 13) {
                    continue;
                }

                $no = isset($headerMap['No']) ? (int) $row[$headerMap['No']] : ($count + 1);
                $pertanyaan = trim($row[$headerMap['Pertanyaan'] ?? 6]);
                $optA = trim($row[$headerMap['Pilihan A'] ?? 7]);
                $optB = trim($row[$headerMap['Pilihan B'] ?? 8]);
                $optC = trim($row[$headerMap['Pilihan C'] ?? 9]);
                $optD = trim($row[$headerMap['Pilihan D'] ?? 10]);

                // Hitung level (1-10): setiap 5 soal = 1 level
                $level = (int) floor(($no - 1) / 5) + 1;
                if ($level > 10) $level = 10;
                if ($level < 1) $level = 1;

                $rawIndex = isset($headerMap['Indeks Jawaban']) ? trim($row[$headerMap['Indeks Jawaban']]) : '1';
                $correctAnswer = is_numeric($rawIndex) ? max(0, min(3, (int) $rawIndex - 1)) : 0;

                $timeLimit = isset($headerMap['Waktu (detik)']) && is_numeric($row[$headerMap['Waktu (detik)']]) 
                    ? (int) $row[$headerMap['Waktu (detik)']] 
                    : 60;

                Question::create([
                    'quiz_id' => $quiz->id,
                    'level' => $level,
                    'text' => $pertanyaan,
                    'options' => [$optA, $optB, $optC, $optD],
                    'correct_answer' => $correctAnswer,
                    'time_limit' => $timeLimit,
                    'points' => 100,
                ]);

                $count++;
            }
            fclose($handle);

            $this->command->info("Berhasil mengimpor {$count} soal ke kuis '{$quiz->title}' (Kode: {$quiz->code}) dengan 10 level adaptif.");
        }
    }
}
