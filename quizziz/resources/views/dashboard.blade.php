<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizizz Clone - Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="deep-bg text-slate-100 font-sans min-h-screen pb-24" x-data="{ openCreate: false }">
    <!-- Navbar -->
    <nav class="sticky top-0 z-40 w-full glass border-b border-white/10 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-pink-500 to-purple-600 p-2.5 rounded-2xl shadow-lg shadow-purple-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096L3 15.09m6 5.91l4.904-6.096M21 3h-6m6 0v6m0-6L14 9M3 3h6m-6 0v6m0-6l7 7" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-wider text-white">QUIZIZZ</h1>
                <p class="text-[10px] text-pink-400 font-extrabold uppercase tracking-widest">Interactive Clone</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Alert Banner -->
            @if(session('success'))
                <div class="hidden md:flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-4 py-2 rounded-xl text-sm font-semibold">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="hidden md:flex items-center gap-2 bg-rose-500/10 text-rose-400 border border-rose-500/20 px-4 py-2 rounded-xl text-sm font-semibold">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($activeUser->role === 'teacher')
                <button @click="openCreate = true" class="flex items-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 transform active:scale-95 shadow-lg shadow-purple-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat Kuis</span>
                </button>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10">
        <!-- Toast Alerts for Mobile -->
        @if(session('success') || session('error'))
            <div class="md:hidden mb-6 p-4 rounded-xl text-sm font-semibold border {{ session('success') ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Side: Join Game Pin Form (Student) & User Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Session Profile -->
                <div class="glass p-5 rounded-2xl">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pink-500 to-purple-600 text-white flex items-center justify-center font-black uppercase text-base shadow-lg shadow-purple-500/25">
                            {{ substr($activeUser->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white text-base leading-snug">{{ $activeUser->name }}</h3>
                            <span class="text-[10px] bg-white/10 border border-white/20 px-2 py-0.5 rounded text-pink-400 font-extrabold uppercase tracking-widest mt-1 inline-block">
                                {{ $activeUser->role === 'teacher' ? 'Host/Guru' : 'Peserta/Siswa' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Join Form (Student) -->
                @if($activeUser->role === 'student')
                    <div class="glass p-6 rounded-3xl shadow-xl relative overflow-hidden border border-purple-500/20">
                        <div class="absolute -right-10 -bottom-10 w-28 h-28 rounded-full bg-pink-500/10 blur-xl"></div>
                        <h3 class="text-lg font-black text-white uppercase tracking-wider mb-2">Ikuti Kuis Live</h3>
                        <p class="text-slate-400 text-xs leading-relaxed mb-6">Masukkan Pin Kuis 6 digit yang dibagikan oleh guru Anda untuk memulai bermain secara langsung.</p>
                        
                        <form action="{{ route('game.join') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <input type="text" name="code" required placeholder="PIN KUIS (Contoh: 123456)" class="w-full bg-slate-900/80 px-4 py-4 rounded-2xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-center font-black tracking-widest text-xl text-white placeholder-slate-600">
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-md shadow-purple-500/20">
                                Mulai Game Kuis
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Right Side: Quizzes Dashboard (Teacher List of Quizzes) -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-3xl font-black text-white tracking-wider uppercase">Daftar Kuis</h2>
                    <p class="text-slate-400 text-sm mt-1">Host/Guru dapat meluncurkan kuis live multiplayer dari sini.</p>
                </div>

                @if($quizzes->isEmpty())
                    <div class="glass rounded-3xl p-16 text-center shadow-lg border border-white/5 max-w-xl mx-auto">
                        <div class="w-16 h-16 bg-purple-500/10 border border-purple-500/25 text-purple-400 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096L3 15.09m6 5.91l4.904-6.096M21 3h-6m6 0v6m0-6L14 9M3 3h6m-6 0v6m0-6l7 7" /></svg>
                        </div>
                        <h4 class="text-lg font-extrabold text-white">Belum Ada Kuis Terbuat</h4>
                        <p class="text-slate-400 text-xs mt-1 leading-relaxed">Gunakan tombol "Buat Kuis" di pojok kanan atas untuk merancang kuis pertama Anda.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ activeQuizId: null }">
                        @foreach($quizzes as $quiz)
                            <div class="glass hover:border-pink-500/40 rounded-2xl overflow-hidden shadow-lg transition-all duration-300 flex flex-col justify-between border border-white/10 relative group">
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <span class="text-[9px] uppercase font-extrabold bg-pink-500/20 border border-pink-500/30 text-pink-400 px-2 py-0.5 rounded-full tracking-widest">
                                            {{ $quiz->questions->count() }} Pertanyaan
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-white leading-snug tracking-wide line-clamp-1">{{ $quiz->title }}</h3>
                                    <p class="text-slate-400 text-xs mt-2 leading-relaxed line-clamp-2 h-8">{{ $quiz->description ?? 'Tidak ada deskripsi.' }}</p>
                                    <p class="text-[10px] text-slate-500 mt-4">Dibuat oleh: {{ $quiz->creator->name }}</p>
                                </div>

                                <!-- Actions panel -->
                                <div class="px-6 pb-6 pt-3 border-t border-white/5 space-y-3 bg-white/5">
                                    @if($activeUser->role === 'teacher' && $quiz->creator_id === $activeUser->id)
                                        <!-- Add Question Form trigger -->
                                        <button @click="activeQuizId = (activeQuizId === {{ $quiz->id }} ? null : {{ $quiz->id }})" class="w-full text-center py-2 border border-purple-500/30 hover:border-purple-500/60 hover:bg-purple-500/5 text-purple-400 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all">
                                            <span x-text="activeQuizId === {{ $quiz->id }} ? 'Tutup Form' : 'Tambah Pertanyaan'"></span>
                                        </button>
                                        
                                        <!-- Launch Host Button -->
                                        <form action="{{ route('quiz.host', $quiz->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-3.5 rounded-xl text-[10px] uppercase tracking-widest transition-all transform active:scale-95 shadow-md shadow-purple-500/20">
                                                Launch Live Game (Host)
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-[10px] text-slate-500 italic text-center py-2">Hanya host pembuat kuis yang dapat memulai game live ini.</p>
                                    @endif
                                </div>

                                <!-- Expandable Add Question Form -->
                                <div x-show="activeQuizId === {{ $quiz->id }}" x-cloak class="absolute inset-0 bg-slate-950/95 z-20 p-5 overflow-y-auto rounded-2xl border border-pink-500/50">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-xs font-black text-pink-400 uppercase tracking-widest">Tambah Pertanyaan</h4>
                                        <button @click="activeQuizId = null" class="text-slate-400 hover:text-slate-200 text-xs font-bold">X</button>
                                    </div>
                                    <form action="{{ route('quiz.question', $quiz->id) }}" method="POST" class="space-y-3 text-left">
                                        @csrf
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pertanyaan</label>
                                            <input type="text" name="text" required placeholder="Contoh: Berapa hasil 5 + 5?" class="w-full bg-slate-900 px-3 py-2 border border-white/10 rounded-lg text-xs text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilihan Jawaban</label>
                                            <input type="text" name="options[]" required placeholder="Pilihan A" class="w-full bg-slate-900 px-3 py-1.5 border border-white/10 rounded-lg text-xs text-white">
                                            <input type="text" name="options[]" required placeholder="Pilihan B" class="w-full bg-slate-900 px-3 py-1.5 border border-white/10 rounded-lg text-xs text-white">
                                            <input type="text" name="options[]" required placeholder="Pilihan C" class="w-full bg-slate-900 px-3 py-1.5 border border-white/10 rounded-lg text-xs text-white">
                                            <input type="text" name="options[]" required placeholder="Pilihan D" class="w-full bg-slate-900 px-3 py-1.5 border border-white/10 rounded-lg text-xs text-white">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jawaban Benar</label>
                                            <select name="correct_answer" class="w-full bg-slate-900 px-3 py-2 border border-white/10 rounded-lg text-xs text-white">
                                                <option value="0">Pilihan A</option>
                                                <option value="1">Pilihan B</option>
                                                <option value="2">Pilihan C</option>
                                                <option value="3">Pilihan D</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Limit Waktu (detik)</label>
                                                <input type="number" name="time_limit" value="30" min="5" max="120" class="w-full bg-slate-900 px-3 py-2 border border-white/10 rounded-lg text-xs text-white">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Poin Maksimal</label>
                                                <input type="number" name="points" value="100" min="50" max="500" class="w-full bg-slate-900 px-3 py-2 border border-white/10 rounded-lg text-xs text-white">
                                            </div>
                                        </div>
                                        <div class="flex gap-2 pt-2">
                                            <button type="button" @click="activeQuizId = null" class="w-1/2 py-2 border border-white/10 text-xs rounded-lg font-bold">Batal</button>
                                            <button type="submit" class="w-1/2 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-xs font-bold">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Floating User Switcher Simulator Panel (Dark Theme Quizizz Styling) -->
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
            <p class="text-[11px] text-slate-400 leading-relaxed">Ganti pengguna simulasi di bawah untuk menguji alur live kuis secara langsung (buat kuis dan host sebagai Guru, ikuti kuis sebagai Siswa).</p>
            
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
    <!-- Modal Buat Kuis (Teacher) -->
    <div x-show="openCreate" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="openCreate = false" class="glass rounded-3xl w-full max-w-md p-6 shadow-2xl transform transition-all text-slate-100">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-black text-white uppercase tracking-wider">Buat Kuis Baru</h3>
                <button @click="openCreate = false" class="text-slate-400 hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('quiz.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nama Kuis (Wajib)</label>
                    <input type="text" name="title" required placeholder="Contoh: Kuis Fisika Listrik Dinamis" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm font-semibold text-white">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" placeholder="Contoh: Uji pemahaman materi hukum ohm dan rangkaian hambatan..." class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm text-white"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openCreate = false" class="w-1/2 px-4 py-3 border border-white/10 hover:bg-white/5 rounded-xl font-bold text-sm transition-all">Batal</button>
                    <button type="submit" class="w-1/2 px-4 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-purple-500/25">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
