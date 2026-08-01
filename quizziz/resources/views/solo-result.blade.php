<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Solo Play: {{ $quiz->title }} - Quizizz</title>
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
<body class="deep-bg text-slate-100 font-sans min-h-screen pb-24">

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
                <p class="text-[9px] text-pink-400 font-extrabold uppercase tracking-widest mt-0.5">Hasil Permainan Mandiri</p>
            </div>
        </div>
    </nav>

    <!-- Main Game Area -->
    <main class="max-w-xl mx-auto px-6 py-12">
        <div class="text-center space-y-8">
            
            <!-- Result Podiums Block -->
            <div class="glass p-8 md:p-10 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-pink-500/15 blur-2xl"></div>
                <div class="absolute -left-20 -bottom-20 w-48 h-48 rounded-full bg-purple-500/15 blur-2xl"></div>
                
                <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-2">Skor Akhir Anda</p>
                <h2 class="text-5xl font-black text-white tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-pink-400 to-purple-400 mb-6">{{ $finalScore }}</h2>
                
                <div class="h-px bg-white/10 my-6"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 border border-white/5 rounded-2xl p-4">
                        <p class="text-[10px] text-slate-400 uppercase font-extrabold tracking-widest">Akurasi Soal</p>
                        <p class="text-2xl font-black text-emerald-400 mt-1">
                            {{ round(($correctCount / $totalQuestions) * 100) }}%
                        </p>
                    </div>
                    <div class="bg-white/5 border border-white/5 rounded-2xl p-4">
                        <p class="text-[10px] text-slate-400 uppercase font-extrabold tracking-widest">Jawaban Benar</p>
                        <p class="text-2xl font-black text-purple-400 mt-1">
                            {{ $correctCount }} / {{ $totalQuestions }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-sm mx-auto">
                <a href="{{ route('quiz.solo', $quiz->id) }}" class="flex-1 text-center bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/20">
                    Coba Lagi
                </a>
                <a href="{{ route('dashboard') }}" class="flex-1 text-center border border-white/15 hover:bg-white/5 text-slate-300 font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all">
                    Ke Dashboard
                </a>
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
