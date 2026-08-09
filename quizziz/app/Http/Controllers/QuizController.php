<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\GameSession;
use App\Models\GamePlayer;
use App\Models\PlayerAnswer;
use App\Models\SoloAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // Helper to get active user
    private function getActiveUser()
    {
        return Auth::user();
    }

    // Quizizz Dashboard
    public function index()
    {
        $activeUser = $this->getActiveUser();

        // Fill missing codes for existing quizzes
        $quizzesWithoutCode = Quiz::whereNull('code')->get();
        foreach ($quizzesWithoutCode as $q) {
            $code = 'QZ-' . Str::upper(Str::random(8));
            while (Quiz::where('code', $code)->exists()) {
                $code = 'QZ-' . Str::upper(Str::random(8));
            }
            $q->update(['code' => $code]);
        }

        $quizzes = Quiz::with('questions')->get();

        return view('dashboard', compact('activeUser', 'quizzes'));
    }

    // Create Quiz
    public function createQuiz(Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'teacher') {
            return back()->with('error', 'Hanya pengajar yang dapat membuat kuis!');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $code = 'QZ-' . Str::upper(Str::random(8));
        while (Quiz::where('code', $code)->exists()) {
            $code = 'QZ-' . Str::upper(Str::random(8));
        }

        Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => $activeUser->id,
            'banner_theme' => collect(['purple', 'indigo', 'pink', 'violet'])->random(),
            'code' => $code,
        ]);

        return back()->with('success', 'Kuis berhasil dibuat! Silakan tambahkan pertanyaan.');
    }

    // Add Question to Quiz
    public function addQuestion(Quiz $quiz, Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($quiz->creator_id !== $activeUser->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke kuis ini!');
        }

        $request->validate([
            'text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
            'time_limit' => 'required|integer|min:5|max:120',
            'points' => 'required|integer|min:50|max:500',
            'level' => 'required|integer|min:1',
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'level' => $request->level,
            'text' => $request->text,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
            'time_limit' => $request->time_limit,
            'points' => $request->points,
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan ke kuis!');
    }

    // Host a Live Game Session
    public function hostGame(Quiz $quiz)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'teacher') {
            return back()->with('error', 'Hanya pengajar yang dapat menjadi host kuis!');
        }

        if ($quiz->questions()->count() === 0) {
            return back()->with('error', 'Kuis tidak memiliki pertanyaan! Tambahkan pertanyaan terlebih dahulu.');
        }

        // Generate game pin (6 digits)
        $code = rand(100000, 999999);
        while (GameSession::where('code', $code)->exists()) {
            $code = rand(100000, 999999);
        }

        $session = GameSession::create([
            'quiz_id' => $quiz->id,
            'code' => $code,
            'status' => 'waiting',
            'host_id' => $activeUser->id,
        ]);

        return redirect()->route('game.session', $session->code);
    }

    // Player Join Game
    public function joinGame(Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'student') {
            return back()->with('error', 'Hanya siswa yang dapat bergabung kuis!');
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $session = GameSession::where('code', $request->code)->first();

        if (!$session) {
            return back()->with('error', 'Pin Kuis tidak valid!');
        }

        if ($session->status === 'finished') {
            return back()->with('error', 'Game kuis ini telah selesai!');
        }

        // Register or fetch player
        $player = GamePlayer::firstOrCreate([
            'game_session_id' => $session->id,
            'user_id' => $activeUser->id,
        ]);

        return redirect()->route('game.session', $session->code);
    }

    // Game Session Lobby / Live Screen
    public function sessionView($code)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->with(['quiz', 'players.user', 'currentQuestion'])->firstOrFail();

        $isHost = $session->host_id === $activeUser->id;
        
        $myPlayerInfo = null;
        if (!$isHost) {
            $myPlayerInfo = GamePlayer::where('game_session_id', $session->id)
                ->where('user_id', $activeUser->id)
                ->first();
            
            if (!$myPlayerInfo) {
                return redirect()->route('dashboard')->with('error', 'Anda belum bergabung ke game kuis ini!');
            }
        }

        // Determine active question answer counts
        $answerCount = 0;
        $hasAnswered = false;
        $lastAnswerCorrect = false;
        $scoreEarned = 0;

        if ($session->status === 'active' && $session->current_question_id) {
            $answerCount = PlayerAnswer::where('question_id', $session->current_question_id)
                ->whereIn('game_player_id', $session->players->pluck('id'))
                ->count();
            
            if (!$isHost && $myPlayerInfo) {
                $ans = PlayerAnswer::where('question_id', $session->current_question_id)
                    ->where('game_player_id', $myPlayerInfo->id)
                    ->first();
                if ($ans) {
                    $hasAnswered = true;
                    $lastAnswerCorrect = $ans->is_correct;
                    $scoreEarned = $ans->score_earned;
                }
            }
        }

        return view('game-room', compact(
            'session',
            'activeUser',
            'isHost',
            'myPlayerInfo',
            'answerCount',
            'hasAnswered',
            'lastAnswerCorrect',
            'scoreEarned'
        ));
    }

    // Polling Status JSON API (Alpine calls this every 1-2 seconds to make it real-time!)
    public function sessionStatus($code)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->with(['quiz', 'players.user'])->firstOrFail();
        $isHost = $session->host_id === $activeUser->id;

        $playerAnswersCount = 0;
        if ($session->status === 'active' && $session->current_question_id) {
            $playerAnswersCount = PlayerAnswer::where('question_id', $session->current_question_id)
                ->whereIn('game_player_id', $session->players->pluck('id'))
                ->count();
        }

        $myPlayerInfo = null;
        $hasAnswered = false;
        $isCorrect = false;
        $scoreEarned = 0;

        if (!$isHost) {
            $myPlayerInfo = GamePlayer::where('game_session_id', $session->id)
                ->where('user_id', $activeUser->id)
                ->first();
            
            if ($myPlayerInfo && $session->current_question_id) {
                $ans = PlayerAnswer::where('question_id', $session->current_question_id)
                    ->where('game_player_id', $myPlayerInfo->id)
                    ->first();
                if ($ans) {
                    $hasAnswered = true;
                    $isCorrect = (bool) $ans->is_correct;
                    $scoreEarned = $ans->score_earned;
                }
            }
        }

        // Remaining seconds calculation
        $remainingSeconds = 0;
        if ($session->status === 'active' && $session->currentQuestion && $session->question_active_since) {
            $elapsed = now()->diffInSeconds($session->question_active_since);
            $limit = $session->currentQuestion->time_limit;
            $remainingSeconds = max(0, $limit - $elapsed);
        }

        return response()->json([
            'status' => $session->status,
            'current_question_id' => $session->current_question_id,
            'players' => $session->players->map(function($p) {
                return [
                    'name' => $p->user->name,
                    'score' => $p->score,
                    'streak' => $p->streak,
                ];
            }),
            'answer_count' => $playerAnswersCount,
            'remaining_seconds' => $remainingSeconds,
            'has_answered' => $hasAnswered,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
        ]);
    }

    // Start Host Game
    public function startGame($code)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->firstOrFail();

        if ($session->host_id !== $activeUser->id) {
            return back()->with('error', 'Hanya host yang dapat memulai kuis!');
        }

        $firstQuestion = $session->quiz->questions()->orderBy('id')->first();

        $session->update([
            'status' => 'active',
            'current_question_id' => $firstQuestion->id,
            'question_active_since' => now(),
        ]);

        return back()->with('success', 'Game telah dimulai!');
    }

    // Submit Answer (Player Side)
    public function submitAnswer($code, Request $request)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->firstOrFail();
        
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option' => 'required|integer|min:0|max:3',
        ]);

        $player = GamePlayer::where('game_session_id', $session->id)
            ->where('user_id', $activeUser->id)
            ->firstOrFail();

        $question = Question::findOrFail($request->question_id);

        // Check if already answered
        $existing = PlayerAnswer::where('game_player_id', $player->id)
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Anda sudah menjawab pertanyaan ini!'], 400);
        }

        // Calculate time taken & points
        $elapsedSeconds = now()->diffInSeconds($session->question_active_since);
        $timeTaken = min($question->time_limit, $elapsedSeconds);

        $isCorrect = ($request->selected_option == $question->correct_answer);
        $scoreEarned = 0;

        if ($isCorrect) {
            // Speed scoring bonus (faster = more points up to question points)
            $timeRatio = ($question->time_limit - $timeTaken) / $question->time_limit;
            $speedBonus = round($question->points * 0.4 * $timeRatio);
            $scoreEarned = round($question->points * 0.6) + $speedBonus;
            
            $player->increment('score', $scoreEarned);
            $player->increment('streak');
        } else {
            $player->update(['streak' => 0]);
        }

        PlayerAnswer::create([
            'game_player_id' => $player->id,
            'question_id' => $question->id,
            'selected_option' => $request->selected_option,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
            'time_taken' => $timeTaken,
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
            'new_score' => $player->score,
        ]);
    }

    // Host next question
    public function nextQuestion($code)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->firstOrFail();

        if ($session->host_id !== $activeUser->id) {
            return back()->with('error', 'Hanya host yang dapat memajukan pertanyaan!');
        }

        // Find next question
        $nextQuestion = $session->quiz->questions()
            ->where('id', '>', $session->current_question_id)
            ->orderBy('id')
            ->first();

        if ($nextQuestion) {
            $session->update([
                'current_question_id' => $nextQuestion->id,
                'question_active_since' => now(),
            ]);
            return back()->with('success', 'Pertanyaan berikutnya aktif!');
        } else {
            // Finish game
            $session->update([
                'status' => 'finished',
                'current_question_id' => null,
                'question_active_since' => null,
            ]);
            return back()->with('success', 'Game kuis selesai!');
        }
    }

    // Host end game manually
    public function endGame($code)
    {
        $activeUser = $this->getActiveUser();
        $session = GameSession::where('code', $code)->firstOrFail();

        if ($session->host_id !== $activeUser->id) {
            return back()->with('error', 'Hanya host yang dapat mengakhiri kuis!');
        }

        $session->update([
            'status' => 'finished',
            'current_question_id' => null,
            'question_active_since' => null,
        ]);

        return back()->with('success', 'Game kuis dihentikan dan selesai!');
    }

    // CSV Template Download
    public function downloadCSVTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_soal_quizziz.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'pertanyaan', 
                'opsi_a', 
                'opsi_b', 
                'opsi_c', 
                'opsi_d', 
                'jawaban_benar', 
                'limit_waktu', 
                'poin',
                'tingkat'
            ]);

            // Sample rows
            fputcsv($file, [
                'Berapakah hasil dari 5 x 5?',
                '25',
                '15',
                '35',
                '45',
                'A',
                '30',
                '100',
                '1'
            ]);

            fputcsv($file, [
                'Manakah yang merupakan bahasa pemrograman web backend?',
                'HTML',
                'CSS',
                'PHP',
                'Photoshop',
                'C',
                '20',
                '100',
                '2'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Import Questions from CSV
    public function importQuestions(Quiz $quiz, Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($quiz->creator_id !== $activeUser->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke kuis ini!');
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        $questionsAdded = 0;

        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read header
            $header = fgetcsv($handle, 1000, ',');
            
            // Expect columns in order:
            // 0: text, 1: option A, 2: option B, 3: option C, 4: option D, 5: correct_answer, 6: time_limit, 7: points, 8: level
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 6) {
                    continue; // Skip invalid row
                }

                $text = trim($row[0]);
                $options = [
                    trim($row[1]),
                    trim($row[2]),
                    trim($row[3]),
                    trim($row[4])
                ];
                
                // Map correct_answer
                $rawCorrect = strtoupper(trim($row[5]));
                $correctMapping = [
                    'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3,
                    '0' => 0, '1' => 1, '2' => 2, '3' => 3
                ];
                $correctAnswer = $correctMapping[$rawCorrect] ?? 0;

                $timeLimit = isset($row[6]) && is_numeric($row[6]) ? (int) $row[6] : 30;
                $points = isset($row[7]) && is_numeric($row[7]) ? (int) $row[7] : 100;
                $level = isset($row[8]) && is_numeric($row[8]) ? (int) $row[8] : 1;

                if (!empty($text)) {
                    Question::create([
                        'quiz_id' => $quiz->id,
                        'level' => $level,
                        'text' => $text,
                        'options' => $options,
                        'correct_answer' => $correctAnswer,
                        'time_limit' => $timeLimit,
                        'points' => $points,
                    ]);
                    $questionsAdded++;
                }
            }
            fclose($handle);
        }

        return back()->with('success', "Berhasil mengimpor {$questionsAdded} pertanyaan dari file CSV!");
    }

    // Export Questions to CSV
    public function exportQuestions(Quiz $quiz)
    {
        $activeUser = $this->getActiveUser();
        if ($quiz->creator_id !== $activeUser->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke kuis ini!');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export_soal_' . Str::slug($quiz->title) . '.csv"',
        ];

        $callback = function() use ($quiz) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'pertanyaan', 
                'opsi_a', 
                'opsi_b', 
                'opsi_c', 
                'opsi_d', 
                'jawaban_benar', 
                'limit_waktu', 
                'poin',
                'tingkat'
            ]);

            $questions = $quiz->questions()->orderBy('level')->orderBy('id')->get();

            foreach ($questions as $question) {
                $correctMapping = [0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D'];
                $correctAnswerLetter = $correctMapping[$question->correct_answer] ?? 'A';

                fputcsv($file, [
                    $question->text,
                    $question->options[0] ?? '',
                    $question->options[1] ?? '',
                    $question->options[2] ?? '',
                    $question->options[3] ?? '',
                    $correctAnswerLetter,
                    $question->time_limit,
                    $question->points,
                    $question->level
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Start Solo Play
    public function startSoloPlay($quiz_code)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        if ($quiz->questions()->count() === 0) {
            return redirect()->route('dashboard')->with('error', 'Kuis tidak memiliki pertanyaan! Tambahkan pertanyaan terlebih dahulu.');
        }

        $activeUser = $this->getActiveUser();

        return view('solo-join', compact('quiz', 'activeUser'));
    }

    // Join Solo Play (Save user details)
    public function joinSoloPlay($quiz_code, Request $request)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'absent_no' => 'required|string|max:255',
        ]);

        $sessionKey = "solo_game_{$quiz->code}";
        session([
            $sessionKey => [
                'quiz_id' => $quiz->id,
                'name' => $request->name,
                'class' => $request->class,
                'absent_no' => $request->absent_no,
                'current_level' => 1,
                'level_state' => 'primary',
                'consecutive_correct' => 0,
                'score' => 0,
                'streak' => 0,
                'answers' => [],
                'started_at' => now()->toIso8601String(),
                'question_started_at' => now()->toIso8601String(),
            ]
        ]);

        return redirect()->route('quiz.solo.question', $quiz->code);
    }

    // Solo Question View
    public function soloQuestionView($quiz_code)
    {
        $activeUser = $this->getActiveUser();
        
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $sessionKey = "solo_game_{$quiz->code}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('quiz.solo', $quiz->code)->with('error', 'Sesi permainan mandiri tidak ditemukan!');
        }

        $currentLevel = $soloSession['current_level'] ?? 1;
        $levelState = $soloSession['level_state'] ?? 'primary';
        $consecutiveCorrect = $soloSession['consecutive_correct'] ?? 0;
        
        $maxLevel = $quiz->questions()->max('level') ?? 1;

        if ($currentLevel > $maxLevel) {
            return redirect()->route('quiz.solo.result', $quiz->code);
        }

        $question = null;
        if ($levelState === 'primary') {
            // Primary question for the current level (first question of this level by ID)
            $question = $quiz->questions()
                ->where('level', $currentLevel)
                ->orderBy('id')
                ->first();
        } else {
            // Remedial question: must be at same level, but not already answered in this attempt
            $answeredIds = array_keys($soloSession['answers'] ?? []);
            $question = $quiz->questions()
                ->where('level', $currentLevel)
                ->whereNotIn('id', $answeredIds)
                ->inRandomOrder()
                ->first();
        }

        if (!$question) {
            // If we run out of questions at this level, finish the quiz
            return redirect()->route('quiz.solo.result', $quiz->code);
        }

        // Update question_started_at so timer calculations work correctly
        $soloSession['question_started_at'] = now()->toIso8601String();
        $soloSession['current_question_id'] = $question->id;
        session([$sessionKey => $soloSession]);

        $currentIndex = count($soloSession['answers'] ?? []);
        $totalQuestions = $maxLevel; // Display levels as progress
        $currentScore = $soloSession['score'];
        
        $hasAnswered = isset($soloSession['answers'][$question->id]);
        $lastAnswerCorrect = false;
        $scoreEarned = 0;
        if ($hasAnswered) {
            $ans = $soloSession['answers'][$question->id];
            $lastAnswerCorrect = $ans['is_correct'];
            $scoreEarned = $ans['score_earned'];
        }

        return view('solo-room', compact(
            'quiz',
            'question',
            'currentIndex',
            'totalQuestions',
            'currentScore',
            'hasAnswered',
            'lastAnswerCorrect',
            'scoreEarned',
            'activeUser',
            'currentLevel',
            'maxLevel',
            'levelState',
            'consecutiveCorrect'
        ));
    }

    // Submit Solo Answer
    public function submitSoloAnswer($quiz_code, Request $request)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $sessionKey = "solo_game_{$quiz->code}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return response()->json(['error' => 'Sesi tidak ditemukan!'], 404);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option' => 'required|integer|min:0|max:3',
        ]);

        $questionId = $request->question_id;
        $question = Question::findOrFail($questionId);

        if (isset($soloSession['answers'][$questionId])) {
            return response()->json(['error' => 'Anda sudah menjawab pertanyaan ini!'], 400);
        }

        $startedAt = \Carbon\Carbon::parse($soloSession['question_started_at']);
        $elapsedSeconds = abs(now()->diffInSeconds($startedAt));
        $timeTaken = min($question->time_limit, (int) round($elapsedSeconds));

        $isCorrect = ($request->selected_option == $question->correct_answer);
        $scoreEarned = 0;

        if ($isCorrect) {
            $timeRatio = ($question->time_limit - $timeTaken) / $question->time_limit;
            $speedBonus = round($question->points * 0.4 * $timeRatio);
            $scoreEarned = round($question->points * 0.6) + $speedBonus;
            
            $soloSession['score'] += $scoreEarned;
            $soloSession['streak'] += 1;
        } else {
            $soloSession['streak'] = 0;
        }

        $soloSession['answers'][$questionId] = [
            'selected_option' => $request->selected_option,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
            'time_taken' => $timeTaken,
        ];

        // Adaptive Progression Logic
        $currentLevel = $soloSession['current_level'] ?? 1;
        $levelState = $soloSession['level_state'] ?? 'primary';
        $consecutiveCorrect = $soloSession['consecutive_correct'] ?? 0;

        if ($levelState === 'primary') {
            if ($isCorrect) {
                // Correct on primary question -> Advance to next level immediately
                $soloSession['current_level'] += 1;
                $soloSession['level_state'] = 'primary';
                $soloSession['consecutive_correct'] = 0;
            } else {
                // Incorrect on primary question -> Go to remedial at same level
                $soloSession['level_state'] = 'remedial';
                $soloSession['consecutive_correct'] = 0;
            }
        } else {
            // Remedial state: need 2 consecutive correct answers to advance
            if ($isCorrect) {
                $consecutiveCorrect += 1;
                $soloSession['consecutive_correct'] = $consecutiveCorrect;
                if ($consecutiveCorrect >= 2) {
                    // Passed remedial -> Advance to next level
                    $soloSession['current_level'] += 1;
                    $soloSession['level_state'] = 'primary';
                    $soloSession['consecutive_correct'] = 0;
                }
            } else {
                // Wrong again -> Reset remedial streak to 0, remain at same level
                $soloSession['consecutive_correct'] = 0;
            }
        }

        session([$sessionKey => $soloSession]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
            'new_score' => $soloSession['score'],
        ]);
    }

    // Move to Next Question
    public function nextSoloQuestion($quiz_code, Request $request)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $sessionKey = "solo_game_{$quiz->code}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('dashboard')->with('error', 'Sesi tidak ditemukan!');
        }

        $currentLevel = $soloSession['current_level'] ?? 1;
        $maxLevel = $quiz->questions()->max('level') ?? 1;

        if ($currentLevel > $maxLevel) {
            return redirect()->route('quiz.solo.result', $quiz->code);
        }

        // Verify if a next question is available to be served
        $levelState = $soloSession['level_state'] ?? 'primary';
        $question = null;
        if ($levelState === 'primary') {
            $question = $quiz->questions()
                ->where('level', $currentLevel)
                ->orderBy('id')
                ->first();
        } else {
            $answeredIds = array_keys($soloSession['answers'] ?? []);
            $question = $quiz->questions()
                ->where('level', $currentLevel)
                ->whereNotIn('id', $answeredIds)
                ->first();
        }

        if (!$question) {
            return redirect()->route('quiz.solo.result', $quiz->code);
        }

        return redirect()->route('quiz.solo.question', $quiz->code);
    }

    // Solo Play Results View
    public function soloResultView($quiz_code)
    {
        $activeUser = $this->getActiveUser();

        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $sessionKey = "solo_game_{$quiz->code}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('dashboard')->with('error', 'Sesi tidak ditemukan!');
        }

        $totalQuestions = count($soloSession['answers'] ?? []);
        $finalScore = $soloSession['score'];
        
        $correctCount = 0;
        $formattedAnswers = [];

        foreach ($soloSession['answers'] as $qId => $ans) {
            if ($ans['is_correct']) {
                $correctCount++;
            }
            
            $question = Question::find($qId);
            $formattedAnswers[] = [
                'question' => $question ? $question->text : 'Pertanyaan dihapus',
                'selected_option' => $ans['selected_option'],
                'correct_option' => $question ? $question->correct_answer : 0,
                'options' => $question ? $question->options : [],
                'is_correct' => $ans['is_correct'],
                'score_earned' => $ans['score_earned'],
                'time_taken' => $ans['time_taken'],
            ];
        }

        // Save Attempt to Database
        SoloAttempt::create([
            'quiz_id' => $quiz->id,
            'name' => $soloSession['name'],
            'class' => $soloSession['class'],
            'absent_no' => $soloSession['absent_no'],
            'score' => $finalScore,
            'correct_answers' => $correctCount,
            'total_questions' => $totalQuestions,
            'answers' => $formattedAnswers,
        ]);

        session()->forget($sessionKey);

        return view('solo-result', compact('quiz', 'totalQuestions', 'finalScore', 'correctCount', 'activeUser'));
    }

    // Fetch reports for solo attempts (For teacher)
    public function getSoloReports($quiz_code)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $activeUser = $this->getActiveUser();

        if ($quiz->creator_id !== $activeUser->id) {
            return response()->json(['error' => 'Akses ditolak!'], 403);
        }

        $reports = $quiz->soloAttempts()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'quiz_title' => $quiz->title,
            'reports' => $reports
        ]);
    }

    // Export reports for solo attempts to CSV (For teacher)
    public function exportSoloReports($quiz_code)
    {
        $quiz = Quiz::where('code', $quiz_code)->firstOrFail();
        $activeUser = $this->getActiveUser();

        if ($quiz->creator_id !== $activeUser->id) {
            abort(403, 'Akses ditolak!');
        }

        $reports = $quiz->soloAttempts()->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="laporan_nilai_' . Str::slug($quiz->title, '_') . '.csv"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding of special characters
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($file, [
                'No',
                'Nama Siswa',
                'Kelas',
                'No. Absen',
                'Jumlah Benar',
                'Total Soal',
                'Persentase (%)',
                'Skor Akhir',
                'Tanggal Mengerjakan'
            ]);

            foreach ($reports as $index => $row) {
                $percentage = $row->total_questions > 0 
                    ? round(($row->correct_answers / $row->total_questions) * 100, 2) 
                    : 0;

                fputcsv($file, [
                    $index + 1,
                    $row->name,
                    $row->class,
                    $row->absent_no,
                    $row->correct_answers,
                    $row->total_questions,
                    $percentage . '%',
                    $row->score,
                    $row->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

