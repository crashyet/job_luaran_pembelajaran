<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\GameSession;
use App\Models\GamePlayer;
use App\Models\PlayerAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    // Helper to get active user (simulated)
    private function getActiveUser()
    {
        $userId = session('simulated_user_id');
        if (!$userId) {
            // Default to teacher
            $teacher = User::where('role', 'teacher')->first();
            if ($teacher) {
                session(['simulated_user_id' => $teacher->id]);
                return $teacher;
            }
        }
        return User::find($userId) ?? User::first();
    }

    // Switch simulated user
    public function simulateUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        session(['simulated_user_id' => $request->user_id]);
        return back()->with('success', 'Berhasil berganti pengguna simulator!');
    }

    // Quizizz Dashboard
    public function index()
    {
        $activeUser = $this->getActiveUser();
        $allUsers = User::all();
        $quizzes = Quiz::with('questions')->get();

        return view('dashboard', compact('activeUser', 'allUsers', 'quizzes'));
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

        Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => $activeUser->id,
            'banner_theme' => collect(['purple', 'indigo', 'pink', 'violet'])->random(),
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
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
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
        $allUsers = User::all();
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
            'allUsers',
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
                'poin'
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
                '100'
            ]);

            fputcsv($file, [
                'Manakah yang merupakan bahasa pemrograman web backend?',
                'HTML',
                'CSS',
                'PHP',
                'Photoshop',
                'C',
                '20',
                '100'
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
            // 0: text, 1: option A, 2: option B, 3: option C, 4: option D, 5: correct_answer, 6: time_limit, 7: points
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

                if (!empty($text)) {
                    Question::create([
                        'quiz_id' => $quiz->id,
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

    // Start Solo Play
    public function startSoloPlay(Quiz $quiz)
    {
        if ($quiz->questions()->count() === 0) {
            return back()->with('error', 'Kuis tidak memiliki pertanyaan! Tambahkan pertanyaan terlebih dahulu.');
        }

        $questions = $quiz->questions()->orderBy('id')->get();
        
        $sessionKey = "solo_game_{$quiz->id}";
        session([
            $sessionKey => [
                'quiz_id' => $quiz->id,
                'question_ids' => $questions->pluck('id')->toArray(),
                'current_index' => 0,
                'score' => 0,
                'streak' => 0,
                'answers' => [],
                'started_at' => now()->toIso8601String(),
                'question_started_at' => now()->toIso8601String(),
            ]
        ]);

        return redirect()->route('quiz.solo.question', $quiz->id);
    }

    // Solo Question View
    public function soloQuestionView(Quiz $quiz)
    {
        $activeUser = $this->getActiveUser();
        $allUsers = User::all();
        
        $sessionKey = "solo_game_{$quiz->id}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('dashboard')->with('error', 'Sesi permainan mandiri tidak ditemukan!');
        }

        $currentIndex = $soloSession['current_index'];
        $questionIds = $soloSession['question_ids'];

        if ($currentIndex >= count($questionIds)) {
            return redirect()->route('quiz.solo.result', $quiz->id);
        }

        $questionId = $questionIds[$currentIndex];
        $question = Question::findOrFail($questionId);

        // Update question_started_at so timer calculations work correctly
        $soloSession['question_started_at'] = now()->toIso8601String();
        session([$sessionKey => $soloSession]);

        $totalQuestions = count($questionIds);
        $currentScore = $soloSession['score'];
        
        $hasAnswered = isset($soloSession['answers'][$questionId]);
        $lastAnswerCorrect = false;
        $scoreEarned = 0;
        if ($hasAnswered) {
            $ans = $soloSession['answers'][$questionId];
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
            'allUsers'
        ));
    }

    // Submit Solo Answer
    public function submitSoloAnswer(Quiz $quiz, Request $request)
    {
        $sessionKey = "solo_game_{$quiz->id}";
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
        $elapsedSeconds = now()->diffInSeconds($startedAt);
        $timeTaken = min($question->time_limit, $elapsedSeconds);

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

        session([$sessionKey => $soloSession]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'score_earned' => $scoreEarned,
            'new_score' => $soloSession['score'],
        ]);
    }

    // Move to Next Question
    public function nextSoloQuestion(Quiz $quiz, Request $request)
    {
        $sessionKey = "solo_game_{$quiz->id}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('dashboard')->with('error', 'Sesi tidak ditemukan!');
        }

        $soloSession['current_index'] += 1;
        session([$sessionKey => $soloSession]);

        if ($soloSession['current_index'] >= count($soloSession['question_ids'])) {
            return redirect()->route('quiz.solo.result', $quiz->id);
        }

        return redirect()->route('quiz.solo.question', $quiz->id);
    }

    // Solo Play Results View
    public function soloResultView(Quiz $quiz)
    {
        $activeUser = $this->getActiveUser();
        $allUsers = User::all();

        $sessionKey = "solo_game_{$quiz->id}";
        $soloSession = session($sessionKey);

        if (!$soloSession) {
            return redirect()->route('dashboard')->with('error', 'Sesi tidak ditemukan!');
        }

        $totalQuestions = count($soloSession['question_ids']);
        $finalScore = $soloSession['score'];
        
        $correctCount = 0;
        foreach ($soloSession['answers'] as $ans) {
            if ($ans['is_correct']) {
                $correctCount++;
            }
        }

        session()->forget($sessionKey);

        return view('solo-result', compact('quiz', 'totalQuestions', 'finalScore', 'correctCount', 'activeUser', 'allUsers'));
    }
}
