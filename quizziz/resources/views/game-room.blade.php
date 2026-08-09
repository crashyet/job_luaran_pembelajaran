<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Room: {{ $session->code }} - Quizizz</title>
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
          gameStatus: '{{ $session->status }}',
          questionId: '{{ $session->current_question_id ?? 0 }}',
          answerCount: {{ $answerCount }},
          remainingSeconds: 30,
          hasAnswered: {{ $hasAnswered ? 'true' : 'false' }},
          isCorrect: {{ $lastAnswerCorrect ? 'true' : 'false' }},
          scoreEarned: {{ $scoreEarned }},
          players: [],
          init() {
              this.pollStatus();
              setInterval(() => this.pollStatus(), 1500);
          },
          pollStatus() {
              fetch('{{ route('game.status', $session->code) }}')
                  .then(res => res.json())
                  .then(data => {
                      // If state changed, trigger reload
                      if (data.status !== this.gameStatus || data.current_question_id != this.questionId) {
                          window.location.reload();
                      }
                      this.answerCount = data.answer_count;
                      this.remainingSeconds = data.remaining_seconds;
                      this.players = data.players;
                  });
          },
          submitAnswer(optionIdx) {
              if (this.hasAnswered) return;
              
              fetch('{{ route('game.answer', $session->code) }}', {
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
                <h1 class="text-base font-bold tracking-wider text-white line-clamp-1">{{ $session->quiz->title }}</h1>
                <p class="text-[9px] text-pink-400 font-extrabold uppercase tracking-widest mt-0.5">PIN KUIS: {{ $session->code }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-purple-500/20 border border-purple-500/35 text-purple-300 rounded-lg text-xs font-bold uppercase tracking-wider">
                {{ $isHost ? 'HOST' : 'PLAYER' }}
            </span>
        </div>
    </nav>

    <!-- Main Game Area -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        
        <!-- ==================== LOBBY STATE (WAITING) ==================== -->
        @if($session->status === 'waiting')
            <div class="text-center space-y-8">
                <!-- Pin Display -->
                <div class="glass p-8 rounded-3xl max-w-lg mx-auto shadow-2xl border border-white/15 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-purple-600/20 blur-2xl"></div>
                    <p class="text-slate-400 text-xs uppercase font-extrabold tracking-widest mb-2">Gabung di quizziz dengan PIN:</p>
                    <h2 class="text-5xl md:text-6xl font-black text-white tracking-widest animate-pulse">{{ $session->code }}</h2>
                    <p class="text-pink-400 text-[10px] font-extrabold uppercase tracking-wider mt-4">Menunggu peserta bergabung...</p>
                </div>

                <!-- Host Controls -->
                @if($isHost)
                    <div class="max-w-xs mx-auto">
                        <form action="{{ route('game.start', $session->code) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/20">
                                Mulai Game Kuis
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900/60 rounded-full border border-white/5 text-xs text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-pink-500 animate-ping"></span>
                            <span>Menunggu guru memulai permainan...</span>
                        </div>
                    </div>
                @endif

                <!-- Players Grid -->
                <div class="space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 text-left">Daftar Peserta (<span x-text="players.length">0</span>)</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($session->players as $p)
                            <div class="glass p-4 rounded-2xl flex items-center gap-3 border border-white/5 shadow-md">
                                <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($p->user->name, 0, 2) }}
                                </div>
                                <span class="font-bold text-white text-xs truncate">{{ $p->user->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- ==================== ACTIVE GAME STATE (PLAYING) ==================== -->
        @if($session->status === 'active' && $session->currentQuestion)
            <div class="space-y-6">
                <!-- QUESTION HEADER CARD -->
                <div class="glass p-6 md:p-10 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden text-center">
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 rounded-full bg-pink-600/10 blur-xl"></div>
                    
                    <!-- Progress Countdown Bar -->
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-full transition-all duration-1000 ease-linear"
                             :style="'width: ' + ((remainingSeconds / {{ $session->currentQuestion->time_limit }}) * 100) + '%'"></div>
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold text-slate-400 mb-6">
                        <span>Timer: <span class="text-pink-400 font-extrabold text-sm ml-1" x-text="remainingSeconds">30</span>s</span>
                        <span class="bg-white/10 border border-white/20 text-white px-2.5 py-1 rounded-lg">
                            {{ $session->currentQuestion->points }} Poin
                        </span>
                    </div>

                    <h3 class="text-xl md:text-2xl font-black text-white leading-relaxed tracking-wide">
                        {{ $session->currentQuestion->text }}
                    </h3>
                </div>

                <!-- HOST VIEW IN PLAYING STATE -->
                @if($isHost)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <div class="md:col-span-2 glass p-6 rounded-3xl border border-white/5 shadow-lg space-y-4">
                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 border-b border-white/10 pb-3">Status Pengiriman Jawaban</h4>
                            
                            <div class="flex items-center gap-6 py-4">
                                <div class="text-center">
                                    <p class="text-3xl font-black text-white" x-text="answerCount">0</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider mt-1">Jawaban Masuk</p>
                                </div>
                                <div class="h-10 w-px bg-white/15"></div>
                                <div class="text-center">
                                    <p class="text-3xl font-black text-white">{{ $session->players->count() }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider mt-1">Total Peserta</p>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-1 space-y-4">
                            <!-- Host Control Options -->
                            <form action="{{ route('game.next', $session->code) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-md shadow-purple-500/20">
                                    Pertanyaan Berikutnya / Selesai
                                </button>
                            </form>

                            <form action="{{ route('game.end', $session->code) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full border border-rose-500/30 hover:bg-rose-500/10 text-rose-400 font-extrabold py-3 rounded-2xl text-[10px] uppercase tracking-widest transition-all">
                                    Akhiri Game Kuis
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- PLAYER VIEW IN PLAYING STATE -->
                @if(!$isHost)
                    <!-- Choice Buttons Grid (Only if not answered) -->
                    <div x-show="!hasAnswered" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($session->currentQuestion->options as $idx => $opt)
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
                    <div x-show="hasAnswered" x-cloak class="glass p-8 rounded-3xl border text-center transition-all shadow-xl"
                         :class="isCorrect ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-rose-500/10 border-rose-500/30'">
                        
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border"
                             :class="isCorrect ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/35' : 'bg-rose-500/20 text-rose-400 border-rose-500/35'">
                            <span class="text-3xl font-black" x-text="isCorrect ? '✓' : '✗'"></span>
                        </div>

                        <h4 class="text-2xl font-black tracking-wide uppercase" :class="isCorrect ? 'text-emerald-400' : 'text-rose-400'"
                            x-text="isCorrect ? 'Benar!' : 'Salah!'"></h4>
                        
                        <p class="text-slate-400 text-xs mt-2" x-text="isCorrect ? 'Karya hebat! Jawaban Anda terkirim cepat.' : 'Jangan menyerah! Coba lagi pada kuis selanjutnya.'"></p>
                        
                        <div x-show="isCorrect" class="mt-5 inline-flex items-center gap-2 bg-emerald-500/20 border border-emerald-500/20 px-4 py-2 rounded-xl text-emerald-300 font-bold text-sm">
                            <span>+<span x-text="scoreEarned">0</span> Poin</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- ==================== FINISHED STATE (LEADERBOARD PODIUM) ==================== -->
        @if($session->status === 'finished')
            <div class="space-y-10 text-center">
                <!-- Podiums Block -->
                <div class="glass p-6 md:p-10 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-pink-500/15 blur-2xl"></div>
                    <div class="absolute -left-20 -bottom-20 w-48 h-48 rounded-full bg-purple-500/15 blur-2xl"></div>
                    
                    <h2 class="text-3xl font-black text-white tracking-widest uppercase mb-10">Podium Pemenang</h2>

                    <div class="flex items-end justify-center gap-4 md:gap-8 min-h-[220px]">
                        @php
                            $winners = $session->players->take(3);
                            $podiumMap = [
                                1 => ['height' => 'h-36', 'color' => 'bg-gradient-to-t from-pink-600 to-purple-600', 'badge' => '🥇 1st', 'order' => 'order-2'],
                                0 => ['height' => 'h-28', 'color' => 'bg-gradient-to-t from-blue-600 to-indigo-600', 'badge' => '🥈 2nd', 'order' => 'order-1'],
                                2 => ['height' => 'h-24', 'color' => 'bg-gradient-to-t from-amber-600 to-orange-600', 'badge' => '🥉 3rd', 'order' => 'order-3'],
                            ];
                        @endphp

                        @foreach([0, 1, 2] as $placeIdx)
                            @if(isset($winners[$placeIdx]))
                                @php
                                    $player = $winners[$placeIdx];
                                    $mapIdx = ($placeIdx == 0) ? 1 : (($placeIdx == 1) ? 0 : 2); // Map 1st to center, 2nd to left, 3rd to right
                                    $podium = $podiumMap[$mapIdx];
                                @endphp
                                
                                <div class="flex flex-col items-center {{ $podium['order'] }} flex-1 max-w-[130px]">
                                    <span class="font-bold text-white text-xs truncate w-full text-center">{{ $player->user->name }}</span>
                                    <span class="text-[10px] text-pink-400 font-extrabold mt-1">{{ $player->score }} Pts</span>
                                    <div class="w-full {{ $podium['height'] }} {{ $podium['color'] }} rounded-t-xl mt-3 flex items-center justify-center shadow-lg border border-white/10">
                                        <span class="font-black text-white text-sm md:text-base uppercase tracking-wider">{{ $podium['badge'] }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Detailed Leaderboard Table -->
                <div class="glass rounded-3xl border border-white/5 overflow-hidden shadow-lg text-left">
                    <div class="p-5 border-b border-white/5 bg-white/5">
                        <h3 class="text-sm font-black text-white uppercase tracking-wider">Tabel Peringkat Lengkap</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-white/5">
                                    <th class="p-4 w-16 text-center">Peringkat</th>
                                    <th class="p-4">Nama Peserta</th>
                                    <th class="p-4 w-32 text-center">Skor Kuis</th>
                                    <th class="p-4 w-32 text-center">Streak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-xs">
                                @foreach($session->players as $idx => $p)
                                    <tr class="hover:bg-white/5 transition-colors font-bold text-slate-200">
                                        <td class="p-4 text-center text-slate-400 font-extrabold">#{{ $idx + 1 }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 text-white flex items-center justify-center font-bold text-[10px]">
                                                    {{ substr($p->user->name, 0, 2) }}
                                                </div>
                                                <span class="text-white">{{ $p->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center text-pink-400 text-sm font-black">{{ $p->score }}</td>
                                        <td class="p-4 text-center text-amber-400 text-sm font-black">🔥 {{ $p->streak }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </main>


</body>
</html>
