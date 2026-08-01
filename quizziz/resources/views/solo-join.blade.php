<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabung Solo Play: {{ $quiz->title }} - Quizizz</title>
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
<body class="deep-bg text-slate-100 font-sans min-h-screen flex flex-col justify-between">

    <!-- Navbar -->
    <nav class="w-full glass border-b border-white/10 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors p-2 rounded-xl hover:bg-white/5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="h-6 w-px bg-white/10 mx-1"></div>
            <div>
                <h1 class="text-base font-bold tracking-wider text-white line-clamp-1">Quizizz Interactive</h1>
                <p class="text-[9px] text-pink-400 font-extrabold uppercase tracking-widest mt-0.5">Mode Mandiri (Solo Play)</p>
            </div>
        </div>
    </nav>

    <!-- Main Registration Box -->
    <main class="max-w-md w-full mx-auto px-6 py-12 flex-1 flex flex-col justify-center">
        <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden space-y-6">
            <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-pink-500/15 blur-2xl"></div>
            
            <div class="text-center space-y-2">
                <span class="inline-block text-[9px] uppercase font-extrabold bg-pink-500/20 border border-pink-500/30 text-pink-400 px-3 py-1 rounded-full tracking-widest mb-1">
                    {{ $quiz->questions->count() }} Pertanyaan
                </span>
                <h2 class="text-2xl font-black text-white leading-snug tracking-wide">{{ $quiz->title }}</h2>
                <p class="text-slate-400 text-xs leading-relaxed">{{ $quiz->description ?? 'Mainkan kuis ini secara mandiri kapan saja.' }}</p>
            </div>

            <div class="h-px bg-white/10"></div>

            <form action="{{ route('quiz.solo.join', $quiz->code) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap Anda</label>
                    <input type="text" name="name" required placeholder="Contoh: Rian Hidayat"
                           class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm font-semibold text-white transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Kelas / Unit</label>
                        <input type="text" name="class" required placeholder="Contoh: XI RPL 2"
                               class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm font-semibold text-white transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nomor Absen</label>
                        <input type="text" name="absent_no" required placeholder="Contoh: 18"
                               class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm font-semibold text-white transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/20 pt-4">
                    Mulai Kuis Mandiri
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-[10px] text-slate-500 font-medium">
        &copy; 2026 Quizizz Clone. Dibuat untuk Luaran Pembelajaran Mandiri.
    </footer>

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
