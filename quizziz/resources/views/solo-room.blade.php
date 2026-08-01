<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Play: {{ $quiz->title }} - Quizizz</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        quizPurple: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            600: '#8854C0',
                            800: '#4c1d95',
                            900: '#2e1065',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .deep-bg {
            background: radial-gradient(circle at top right, #3b0764, #1e1b4b, #0f172a);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="deep-bg text-slate-100 font-sans min-h-screen pb-24"
      x-data="{
          questionId: {{ $question->id }},
          remainingSeconds: {{ $question->time_limit }},
          hasAnswered: {{ $hasAnswered ? 'true' : 'false' }},
          isCorrect: {{ $lastAnswerCorrect ? 'true' : 'false' }},
          scoreEarned: {{ $scoreEarned }},
          currentScore: {{ $currentScore }},
          timerInterval: null,
          init() {
              if (!this.hasAnswered) {
                  this.startTimer();
              }
          },
          startTimer() {
              this.timerInterval = setInterval(() => {
                  if (this.remainingSeconds > 0) {
                      this.remainingSeconds--;
                  } else {
                      clearInterval(this.timerInterval);
                      this.submitAnswer(-1); // Timeout submission
                  }
              }, 1000);
          },
          submitAnswer(optionIdx) {
              if (this.hasAnswered) return;
              clearInterval(this.timerInterval);
              
              fetch('{{ route('quiz.solo.answer', $quiz->code) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({
                      question_id: this.questionId,
                      selected_option: optionIdx
                  })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.success) {
                      this.hasAnswered = true;
                      this.isCorrect = data.is_correct;
                      this.scoreEarned = data.score_earned;
                      this.currentScore = data.new_score;
                  } else if (data.error) {
                      alert(data.error);
                  }
              });
          }
      }">

    <!-- Navbar -->
    <nav class="sticky top-0 z-40 w-full glass border-b border-white/10 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors p-2 rounded-xl hover:bg-white/5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <div>
                <h1 class="text-base font-bold tracking-wider text-white line-clamp-1">{{ $quiz->title }}</h1>
                <p class="text-[9px] text-pink-400 font-extrabold uppercase tracking-widest mt-0.5">Mode Mandiri (Solo Play)</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 bg-slate-900/60 border border-white/10 text-white rounded-lg text-xs font-bold">
                Skor: <span class="text-pink-400 font-black ml-1" x-text="currentScore">0</span>
            </span>
            <span class="px-3 py-1.5 bg-purple-500/20 border border-purple-500/35 text-purple-300 rounded-lg text-xs font-bold uppercase tracking-wider">
                Soal {{ $currentIndex + 1 }} / {{ $totalQuestions }}
            </span>
        </div>
    </nav>

    <!-- Main Game Area -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <div class="space-y-6">
            <!-- QUESTION HEADER CARD -->
            <div class="glass p-6 md:p-10 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden text-center">
                <div class="absolute -left-10 -bottom-10 w-32 h-32 rounded-full bg-pink-600/10 blur-xl"></div>
                
                <!-- Progress Countdown Bar -->
                <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-full transition-all duration-1000 ease-linear"
                         :style="'width: ' + ((remainingSeconds / {{ $question->time_limit }}) * 100) + '%'"></div>
                </div>

                <div class="flex items-center justify-between text-xs font-semibold text-slate-400 mb-6">
                    <span>Sisa Waktu: <span class="text-pink-400 font-extrabold text-sm ml-1" x-text="remainingSeconds">30</span>s</span>
                    <span class="bg-white/10 border border-white/20 text-white px-2.5 py-1 rounded-lg">
                        {{ $question->points }} Poin Maksimal
                    </span>
                </div>

                <h3 class="text-xl md:text-2xl font-black text-white leading-relaxed tracking-wide">
                    {{ $question->text }}
                </h3>
            </div>

            <!-- Choice Buttons Grid (Only if not answered) -->
            <div x-show="!hasAnswered" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($question->options as $idx => $opt)
                    @php
                        $colors = [
                            0 => 'from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 shadow-rose-900/10',
                            1 => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-blue-900/10',
                            2 => 'from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 shadow-amber-900/10',
                            3 => 'from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 shadow-emerald-900/10',
                        ];
                        $shapes = [
                            0 => '▲', // Triangle
                            1 => '◆', // Diamond
                            2 => '●', // Circle
                            3 => '■', // Square
                        ];
                        $color = $colors[$idx] ?? $colors[0];
                        $shape = $shapes[$idx] ?? $shapes[0];
                    @endphp
                    
                    <button @click="submitAnswer({{ $idx }})" class="bg-gradient-to-br {{ $color }} text-white p-6 rounded-2xl text-left font-bold transition-all transform hover:-translate-y-1 active:scale-95 shadow-lg flex items-center gap-4 min-h-[90px] border border-white/10 group">
                        <span class="text-2xl font-black opacity-80 group-hover:scale-110 transition-transform">{{ $shape }}</span>
                        <span class="text-sm tracking-wide">{{ $opt }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Answer Feedback Card (If answered) -->
            <div x-show="hasAnswered" x-cloak class="space-y-6">
                <div class="glass p-8 rounded-3xl border text-center transition-all shadow-xl"
                     :class="isCorrect ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-rose-500/10 border-rose-500/30'">
                    
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border"
                         :class="isCorrect ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/35' : 'bg-rose-500/20 text-rose-400 border-rose-500/35'">
                        <span class="text-3xl font-black" x-text="isCorrect ? '✓' : '✗'"></span>
                    </div>

                    <h4 class="text-2xl font-black tracking-wide uppercase" :class="isCorrect ? 'text-emerald-400' : 'text-rose-400'"
                        x-text="isCorrect ? 'Jawaban Benar!' : 'Jawaban Salah!'"></h4>
                    
                    <p class="text-slate-400 text-xs mt-2" x-text="isCorrect ? 'Karya hebat! Anda mendapatkan poin bonus kecepatan.' : 'Jangan menyerah! Belajar lagi dan terus mencoba.'"></p>
                    
                    <div x-show="isCorrect" class="mt-5 inline-flex items-center gap-2 bg-emerald-500/20 border border-emerald-500/20 px-4 py-2 rounded-xl text-emerald-300 font-bold text-sm">
                        <span>+<span x-text="scoreEarned">0</span> Poin</span>
                    </div>
                </div>

                <div class="max-w-xs mx-auto">
                    <form action="{{ route('quiz.solo.next', $quiz->code) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/20">
                            {{ ($currentIndex + 1) === $totalQuestions ? 'Lihat Hasil Akhir' : 'Pertanyaan Berikutnya' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating User Switcher Simulator Panel -->
    <div class="fixed bottom-6 right-6 z-50 glass shadow-2xl rounded-2xl p-4 max-w-sm text-slate-200 border border-white/10" x-data="{ openSim: false }">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="flex h-3.5 w-3.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-pink-500"></span>
                </span>
                <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400">User Simulator</span>
            </div>
            <button @click="openSim = !openSim" class="text-xs font-bold bg-pink-600 hover:bg-pink-500 text-white px-3 py-1.5 rounded-lg transition-all shadow-md shadow-pink-500/20">
                <span x-text="openSim ? 'Sembunyikan' : 'Ganti User'"></span>
            </button>
        </div>

        <div x-show="openSim" class="mt-4 pt-4 border-t border-white/10 space-y-3" x-cloak>
            <p class="text-[11px] text-slate-400 leading-relaxed">Ganti pengguna simulasi di bawah untuk menguji kuis dari sudut pandang siswa atau guru lain.</p>
            
            <form action="{{ route('simulate.user') }}" method="POST" class="space-y-2">
                @csrf
                <div class="space-y-1.5">
                    @foreach($allUsers as $user)
                        <label class="flex items-center justify-between p-2.5 rounded-xl border transition-all cursor-pointer text-sm font-semibold {{ $activeUser->id == $user->id ? 'bg-pink-500/20 border-pink-500 text-pink-300' : 'bg-slate-900/40 border-white/5 text-slate-300 hover:bg-slate-900/60' }}">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="user_id" value="{{ $user->id }}" {{ $activeUser->id == $user->id ? 'checked' : '' }} class="hidden" onchange="this.form.submit()">
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-xs leading-none font-bold text-white">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-[9px] uppercase font-extrabold tracking-wider px-2 py-0.5 rounded-md {{ $user->role === 'teacher' ? 'bg-pink-950/80 text-pink-400 border border-pink-800/50' : 'bg-indigo-950/80 text-indigo-400 border border-indigo-800/50' }}">
                                {{ $user->role === 'teacher' ? 'Teacher' : 'Student' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</body>
</html>
