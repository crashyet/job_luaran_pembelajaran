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
<body class="deep-bg text-slate-100 font-sans min-h-screen pb-24" x-data="{ openCreate: false, openAddQuestion: false, activeQuizId: null, activeQuizTitle: '', openReports: false, reportsList: [], reportsQuizTitle: '', reportsQuizCode: '', showToast: false, toastMessage: '', triggerToast(msg) { this.toastMessage = msg; this.showToast = true; setTimeout(() => { this.showToast = false; }, 3000); }, fetchReports(code, title) { this.reportsQuizTitle = title; this.reportsQuizCode = code; this.reportsList = []; this.openReports = true; fetch('/quiz/' + code + '/reports').then(res => res.json()).then(data => { this.reportsList = data.reports; }); } }">

    <!-- Toast Feedback Notification -->
    <div x-show="showToast" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-12 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-250 transform" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-12 opacity-0" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-xs uppercase tracking-widest px-6 py-3.5 rounded-2xl shadow-xl shadow-teal-900/30 border border-teal-400/20 flex items-center gap-3" x-cloak>
        <svg class="w-4 h-4 text-emerald-100 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
        <span x-text="toastMessage"></span>
    </div>
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

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 px-4 py-2.5 rounded-xl font-extrabold text-xs uppercase tracking-widest transition-all transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($quizzes as $quiz)
                            <div class="glass hover:border-pink-500/40 rounded-2xl overflow-hidden shadow-lg transition-all duration-300 flex flex-col justify-between border border-white/10 relative group">
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <span class="text-[9px] uppercase font-extrabold bg-pink-500/20 border border-pink-500/30 text-pink-400 px-2 py-0.5 rounded-full tracking-widest">
                                            {{ $quiz->questions->count() }} Pertanyaan
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-white leading-snug tracking-wide line-clamp-1">{{ $quiz->title }}</h3>
                                    <p class="text-slate-400 text-xs mt-2 leading-relaxed line-clamp-2 min-h-[2.5rem]">{{ $quiz->description ?? 'Tidak ada deskripsi.' }}</p>
                                    <p class="text-[10px] text-slate-500 mt-4">Dibuat oleh: {{ $quiz->creator->name }}</p>
                                </div>

                                <!-- Actions panel -->
                                <div class="px-6 pb-6 pt-3 border-t border-white/5 space-y-3 bg-white/5 text-left">
                                    @if($activeUser->role === 'teacher' && $quiz->creator_id === $activeUser->id)
                                        <!-- Add Question Form trigger -->
                                        <button @click="activeQuizId = {{ $quiz->id }}; activeQuizTitle = '{{ addslashes($quiz->title) }}'; openAddQuestion = true" class="w-full text-center py-3 border border-purple-500/30 hover:border-purple-500/60 hover:bg-purple-500/5 text-purple-400 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all">
                                            <span>Tambah Pertanyaan</span>
                                        </button>
                                        
                                        <!-- Launch Host Button -->
                                        <form action="{{ route('quiz.host', $quiz->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-3.5 rounded-xl text-[10px] uppercase tracking-widest transition-all transform active:scale-95 shadow-md shadow-purple-500/20">
                                                Launch Live Game (Host)
                                            </button>
                                        </form>

                                        <!-- Solo Play Report Button -->
                                        <button @click="fetchReports('{{ $quiz->code }}', '{{ addslashes($quiz->title) }}')" class="w-full text-center py-3 border border-pink-500/30 hover:border-pink-500/60 hover:bg-pink-500/5 text-pink-400 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all">
                                            <span>Laporan Hasil Solo</span>
                                        </button>
                                    @endif

                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Solo Play Button -->
                                        <a href="{{ route('quiz.solo', $quiz->code ?? '') }}" class="text-center bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500/35 text-white font-extrabold py-3.5 rounded-xl text-[9px] uppercase tracking-wider transition-all transform active:scale-95 flex items-center justify-center">
                                            Main Solo
                                        </a>

                                        <!-- Copy Link Button -->
                                        <button @click="navigator.clipboard.writeText('{{ route('quiz.solo', $quiz->code ?? '') }}'); triggerToast('Link Kuis berhasil disalin!')" class="text-center border border-white/10 hover:bg-white/5 text-slate-300 font-extrabold py-3.5 rounded-xl text-[9px] uppercase tracking-wider transition-all transform active:scale-95 flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M3.75 18h11.25A2.25 2.25 0 0017.25 15.75V9A2.25 2.25 0 0015.75 6.75H3.75A2.25 2.25 0 001.5 9v6.75A2.25 2.25 0 003.75 18z" />
                                            </svg>
                                            <span>Salin Link</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>


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

    <!-- Modal Tambah Pertanyaan / Import (Teacher) -->
    <div x-show="openAddQuestion" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak x-data="{ tab: 'manual' }">
        <div @click.away="openAddQuestion = false" class="glass rounded-3xl w-full max-w-2xl p-6 shadow-2xl transform transition-all text-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5 border-b border-white/10 pb-4">
                <div>
                    <h3 class="text-lg font-black text-white uppercase tracking-wider">Kelola Soal Kuis</h3>
                    <p class="text-[10px] text-pink-400 font-extrabold uppercase tracking-widest mt-1">Kuis: <span x-text="activeQuizTitle"></span></p>
                </div>
                <button @click="openAddQuestion = false" class="text-slate-400 hover:text-slate-200 font-bold text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Tab Buttons -->
            <div class="flex border-b border-white/5 mb-6">
                <button @click="tab = 'manual'" :class="tab === 'manual' ? 'border-pink-500 text-pink-400' : 'border-transparent text-slate-400 hover:text-slate-200'" class="flex-1 py-3 text-xs font-black uppercase tracking-wider border-b-2 transition-all">Tambah Manual</button>
                <button @click="tab = 'import'" :class="tab === 'import' ? 'border-pink-500 text-pink-400' : 'border-transparent text-slate-400 hover:text-slate-200'" class="flex-1 py-3 text-xs font-black uppercase tracking-wider border-b-2 transition-all">Import dari CSV</button>
                <button @click="tab = 'export'" :class="tab === 'export' ? 'border-pink-500 text-pink-400' : 'border-transparent text-slate-400 hover:text-slate-200'" class="flex-1 py-3 text-xs font-black uppercase tracking-wider border-b-2 transition-all">Export Soal</button>
            </div>

            <!-- Tab 1: Manual Form -->
            <div x-show="tab === 'manual'">
                <form :action="'/quiz/' + activeQuizId + '/question'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Pertanyaan</label>
                        <input type="text" name="text" required placeholder="Contoh: Berapa hasil 15 x 3?" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-sm font-semibold text-white">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Pilihan Jawaban</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[8px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Pilihan A</label>
                                <input type="text" name="options[]" required placeholder="Jawaban A" class="w-full bg-slate-900/60 px-4 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-[8px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Pilihan B</label>
                                <input type="text" name="options[]" required placeholder="Jawaban B" class="w-full bg-slate-900/60 px-4 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-[8px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Pilihan C</label>
                                <input type="text" name="options[]" required placeholder="Jawaban C" class="w-full bg-slate-900/60 px-4 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-[8px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Pilihan D</label>
                                <input type="text" name="options[]" required placeholder="Jawaban D" class="w-full bg-slate-900/60 px-4 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Jawaban Benar</label>
                            <select name="correct_answer" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                                <option value="0">Pilihan A</option>
                                <option value="1">Pilihan B</option>
                                <option value="2">Pilihan C</option>
                                <option value="3">Pilihan D</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Limit Waktu (Detik)</label>
                            <input type="number" name="time_limit" value="30" min="5" max="120" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Poin Maksimal</label>
                            <input type="number" name="points" value="100" min="50" max="500" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Tingkat / Soal Ke-</label>
                            <input type="number" name="level" value="1" min="1" max="100" class="w-full bg-slate-900/60 px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 text-xs text-white">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-white/5">
                        <button type="button" @click="openAddQuestion = false" class="w-1/2 px-4 py-3 border border-white/10 hover:bg-white/5 rounded-xl font-bold text-sm transition-all">Batal</button>
                        <button type="submit" class="w-1/2 px-4 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-purple-500/25">Simpan Pertanyaan</button>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Import CSV Form -->
            <div x-show="tab === 'import'" class="space-y-4">
                <div class="bg-purple-950/40 border border-purple-500/20 p-4 rounded-2xl text-xs space-y-2 leading-relaxed text-left">
                    <p class="font-bold text-pink-400">💡 Petunjuk Format CSV:</p>
                    <ul class="list-disc pl-4 space-y-1 text-slate-300">
                        <li>File harus berekstensi <strong>.csv</strong></li>
                        <li>Format kolom: <code>pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, limit_waktu, poin</code></li>
                        <li>Kolom <code>jawaban_benar</code> diisi dengan huruf <strong>A, B, C, D</strong> atau angka <strong>0, 1, 2, 3</strong>.</li>
                    </ul>
                    <div class="pt-2">
                        <a href="{{ route('quiz.template.csv') }}" class="inline-flex items-center gap-1.5 text-pink-400 hover:text-pink-300 font-extrabold underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            <span>Unduh Template CSV Contoh</span>
                        </a>
                    </div>
                </div>

                <form :action="'/quiz/' + activeQuizId + '/import'" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Pilih File CSV</label>
                        <div class="relative group border-2 border-dashed border-white/10 hover:border-pink-500/40 rounded-2xl p-6 text-center cursor-pointer transition-colors bg-slate-900/30">
                            <input type="file" name="file" required accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-2">
                                <svg class="w-8 h-8 text-slate-400 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-xs font-bold text-slate-300">Pilih file CSV dari komputer Anda</p>
                                <p class="text-[10px] text-slate-500">Maksimum ukuran file: 2MB (Format .csv)</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-white/5">
                        <button type="button" @click="openAddQuestion = false" class="w-1/2 px-4 py-3 border border-white/10 hover:bg-white/5 rounded-xl font-bold text-sm transition-all">Batal</button>
                        <button type="submit" class="w-1/2 px-4 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-purple-500/25">Mulai Import</button>
                    </div>
                </form>
            </div>

            <!-- Tab 3: Export Questions -->
            <div x-show="tab === 'export'" class="space-y-4">
                <div class="bg-purple-950/40 border border-purple-500/20 p-6 rounded-2xl text-center space-y-4">
                    <div class="w-12 h-12 bg-pink-500/10 text-pink-400 rounded-full flex items-center justify-center mx-auto border border-pink-500/25 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-sm font-bold text-white">Export Semua Pertanyaan ke CSV</h4>
                        <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">Ekspor seluruh daftar soal dari kuis ini ke dalam file CSV. Anda dapat mengedit file tersebut lalu mengimpornya kembali di kuis lain.</p>
                    </div>
                    <div class="pt-2">
                        <form :action="'/quiz/' + activeQuizId + '/export'" method="GET">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white rounded-xl font-extrabold text-[10px] uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/25">
                                Unduh File Soal (.csv)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Solo Play Reports Modal -->
    <div x-show="openReports" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-transition>
        <div @click.away="openReports = false" class="bg-[#1e1b4b]/95 border border-white/10 rounded-3xl w-full max-w-4xl p-6 md:p-8 max-h-[85vh] overflow-y-auto shadow-2xl relative">
            <!-- Close Button -->
            <button @click="openReports = false" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors bg-white/5 hover:bg-white/10 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-white/10 pb-4 sm:pr-12">
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-white tracking-wide">LAPORAN HASIL SOLO PLAY</h2>
                    <p class="text-xs text-pink-400 font-extrabold uppercase tracking-widest mt-1" x-text="reportsQuizTitle"></p>
                </div>
                <div x-show="reportsList.length > 0" x-cloak>
                    <a :href="'/quiz/' + reportsQuizCode + '/reports/export'" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold px-4 py-2.5 rounded-xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-md shadow-teal-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>

            <!-- Loader / Empty State / Table -->
            <div class="space-y-4">
                <!-- If Loading or Empty -->
                <div x-show="reportsList.length === 0" class="text-center py-12 text-slate-400 space-y-3" x-cloak>
                    <svg class="w-12 h-12 mx-auto text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm font-bold">Belum ada siswa yang mengerjakan kuis ini lewat mode mandiri.</p>
                    <p class="text-xs text-slate-500">Salin link solo play kuis ini dan bagikan ke siswa Anda untuk mengumpulkan hasil pengerjaan.</p>
                </div>

                <!-- Table Content -->
                <div x-show="reportsList.length > 0" class="overflow-x-auto rounded-2xl border border-white/5 bg-slate-900/40" x-cloak>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/5 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <th class="p-4">No</th>
                                <th class="p-4">Nama Siswa</th>
                                <th class="p-4">Kelas</th>
                                <th class="p-4">No. Absen</th>
                                <th class="p-4 text-center">Benar</th>
                                <th class="p-4 text-center">Skor</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4 text-center">Detail Jawaban</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-xs text-slate-300">
                            <template x-for="(attempt, idx) in reportsList" :key="attempt.id">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 font-bold text-slate-500" x-text="idx + 1"></td>
                                    <td class="p-4 font-extrabold text-white" x-text="attempt.name"></td>
                                    <td class="p-4" x-text="attempt.class"></td>
                                    <td class="p-4 font-mono" x-text="attempt.absent_no"></td>
                                    <td class="p-4 text-center font-bold text-emerald-400">
                                        <span x-text="attempt.correct_answers"></span> / <span x-text="attempt.total_questions"></span>
                                    </td>
                                    <td class="p-4 text-center font-black text-pink-400" x-text="attempt.score"></td>
                                    <td class="p-4 text-[10px] text-slate-500" x-text="new Date(attempt.created_at).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'})"></td>
                                    <td class="p-4 text-center">
                                        <!-- Detail Answers Sub-list trigger -->
                                        <div x-data="{ showDetails: false }" class="relative inline-block text-left">
                                            <button @click="showDetails = !showDetails" class="px-3 py-1.5 bg-purple-500/20 border border-purple-500/35 hover:bg-purple-500/35 text-purple-300 rounded-lg text-[9px] uppercase font-bold tracking-wider transition-all">
                                                Detail
                                            </button>
                                            
                                            <!-- Detailed Answers Modal/Dropdown overlay -->
                                            <div x-show="showDetails" @click.away="showDetails = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
                                                <div class="bg-[#241b5c] border border-white/10 rounded-2xl w-full max-w-xl p-6 text-left space-y-4 max-h-[80vh] overflow-y-auto shadow-2xl">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <h3 class="text-sm font-black text-white" x-text="'Log Jawaban: ' + attempt.name"></h3>
                                                            <p class="text-[10px] text-slate-400" x-text="attempt.class + ' • Absen ' + attempt.absent_no"></p>
                                                        </div>
                                                        <button @click="showDetails = false" class="text-slate-400 hover:text-white transition-colors bg-white/5 hover:bg-white/10 p-1.5 rounded-full">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="divide-y divide-white/5 space-y-3">
                                                        <template x-for="(ans, aIdx) in attempt.answers" :key="aIdx">
                                                            <div class="pt-3 first:pt-0 space-y-1.5">
                                                                <div class="flex items-start justify-between gap-3">
                                                                    <p class="text-xs font-bold text-white leading-relaxed" x-text="(aIdx + 1) + '. ' + ans.question"></p>
                                                                    <span class="px-2 py-0.5 rounded text-[8px] font-extrabold uppercase tracking-widest flex-shrink-0"
                                                                          :class="ans.is_correct ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30'">
                                                                        <span x-text="ans.is_correct ? 'Benar' : 'Salah'"></span>
                                                                    </span>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-2 text-[10px]">
                                                                    <div class="bg-white/5 p-2 rounded-lg">
                                                                        <p class="text-slate-500 font-extrabold uppercase tracking-wider">Jawaban Siswa</p>
                                                                        <p class="font-bold text-white mt-0.5" x-text="ans.options[ans.selected_option] !== undefined ? ans.options[ans.selected_option] : 'Timeout / Tidak Dijawab'"></p>
                                                                    </div>
                                                                    <div class="bg-emerald-500/5 p-2 rounded-lg border border-emerald-500/10">
                                                                        <p class="text-emerald-500/70 font-extrabold uppercase tracking-wider">Kunci Jawaban</p>
                                                                        <p class="font-bold text-emerald-300 mt-0.5" x-text="ans.options[ans.correct_option]"></p>
                                                                    </div>
                                                                </div>
                                                                <p class="text-[9px] text-slate-500 leading-none" x-text="'Waktu pengerjaan: ' + ans.time_taken + 's • Poin didapatkan: ' + ans.score_earned"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
