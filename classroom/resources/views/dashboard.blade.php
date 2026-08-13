<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom Clone - Dashboard</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS (Vite or CDN Fallback to ensure styling works instantly) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .dark-glass {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen pb-24" x-data="{ openCreate: false, openJoin: false }">
    <!-- Navbar -->
    <nav class="sticky top-0 z-40 w-full glass border-b border-slate-200 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="bg-indigo-600 text-white p-2 sm:p-2.5 rounded-xl shadow-lg shadow-indigo-100 flex items-center justify-center">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.231-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84a50.58 50.58 0 00-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M12 21v-3.75m.75-13.06c.084.628.755 1.127 1.459 1.127a1.459 1.459 0 000-2.917c-.704 0-1.375.5-1.459 1.128z" />
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-bold tracking-tight text-slate-900 leading-tight">Classroom</h1>
                <p class="text-[10px] sm:text-xs text-indigo-600 font-medium hidden xs:block">Google Classroom Clone</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5 sm:gap-3">
            <!-- Alert Banner -->
            @if(session('success'))
                <div class="hidden md:flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl text-sm font-medium border border-emerald-200/50">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="hidden md:flex items-center gap-2 bg-rose-50 text-rose-700 px-4 py-2 rounded-xl text-sm font-medium border border-rose-200/50">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($activeUser->role === 'teacher')
                <!-- Create Class Button -->
                <button @click="openCreate = true" class="flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm transition-all duration-300 transform active:scale-95 shadow-md shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat <span class="hidden sm:inline">Kelas</span></span>
                </button>
            @else
                <!-- Join Class Button -->
                <button @click="openJoin = true" class="flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm transition-all duration-300 transform active:scale-95 shadow-md shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Gabung <span class="hidden sm:inline">Kelas</span></span>
                </button>
            @endif

            <!-- Profile Button -->
            <a href="{{ route('profile.show') }}" class="flex items-center gap-1.5 sm:gap-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm transition-all duration-300 transform active:scale-95 border border-slate-200/60">
                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="hidden xs:inline">Profil</span>
            </a>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 sm:gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm transition-all duration-300 transform active:scale-95 border border-slate-200/60" title="Keluar">
                    <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10">
        <!-- Toast Session Alerts for Mobile -->
        @if(session('success') || session('error'))
            <div class="md:hidden mb-6 p-4 rounded-xl text-sm font-medium border {{ session('success') ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row items-start justify-between gap-6 mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelas Anda</h2>
                <p class="text-slate-500 mt-1">Kelola dan ikuti kelas pembelajaran aktif Anda di sini.</p>
            </div>
            
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl px-5 py-3 flex items-center gap-3.5">
                <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></div>
                <div class="text-sm">
                    <span class="text-slate-500">Masuk sebagai:</span>
                    <span class="font-bold text-slate-800 ml-1">{{ $activeUser->name }}</span>
                    <span class="ml-2 px-2 py-0.5 rounded-md text-xs font-semibold uppercase tracking-wider {{ $activeUser->role === 'teacher' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200/50' : 'bg-sky-100 text-sky-700 border border-sky-200/50' }}">
                        {{ $activeUser->role === 'teacher' ? 'Pengajar' : 'Siswa' }}
                    </span>
                </div>
            </div>
        </div>

        @if($classes->isEmpty())
            <!-- Empty State -->
            <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center shadow-sm max-w-xl mx-auto mt-8">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Belum Ada Kelas</h3>
                <p class="text-slate-500 mt-2 mb-8">
                    {{ $activeUser->role === 'teacher' ? 'Anda belum membuat kelas satupun. Mulailah dengan membuat kelas baru menggunakan tombol di atas.' : 'Anda belum bergabung dengan kelas satupun. Silakan masukkan kode kelas dari pengajar Anda.' }}
                </p>
                @if($activeUser->role === 'teacher')
                    <button @click="$dispatch('open-create')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all">
                        Buat Kelas Pertama Anda
                    </button>
                @endif
            </div>
        @else
            <!-- Classes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $class)
                    <div class="group bg-white border border-slate-200 hover:border-indigo-400 hover:shadow-xl rounded-2xl overflow-hidden transition-all duration-300 flex flex-col justify-between shadow-sm relative">
                        <!-- Card Banner -->
                        @php
                            $themeColors = [
                                'indigo' => 'from-indigo-600 to-indigo-800 text-indigo-100',
                                'emerald' => 'from-emerald-600 to-emerald-800 text-emerald-100',
                                'purple' => 'from-purple-600 to-purple-800 text-purple-100',
                                'rose' => 'from-rose-600 to-rose-800 text-rose-100',
                                'blue' => 'from-blue-600 to-blue-800 text-blue-100',
                            ];
                            $themeColor = $themeColors[$class->banner_theme] ?? $themeColors['indigo'];
                        @endphp
                        
                        <div class="bg-gradient-to-br {{ $themeColor }} p-5 relative overflow-hidden flex flex-col justify-between h-36">
                            <!-- Background Abstract Circles -->
                            <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-white/10 blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                            <div class="absolute -left-6 -bottom-6 w-24 h-24 rounded-full bg-white/5 blur-xl"></div>
                            
                            <div class="relative z-10 flex items-start justify-between">
                                <a href="{{ route('classroom.show', $class->id) }}" class="hover:underline">
                                    <h3 class="text-xl font-bold text-white tracking-tight line-clamp-1 leading-snug">{{ $class->name }}</h3>
                                </a>
                                <span class="bg-white/20 border border-white/30 text-white text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full">
                                    {{ $class->code }}
                                </span>
                            </div>
                            <div class="relative z-10 mt-auto">
                                @if($class->section)
                                    <p class="text-sm font-medium text-white/80 line-clamp-1">{{ $class->section }}</p>
                                @endif
                                <p class="text-xs text-white/70 line-clamp-1">Pengajar: {{ $class->teacher->name }}</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex flex-col justify-between flex-grow min-h-[140px]">
                            <div class="text-slate-600 text-sm space-y-2">
                                @if($class->subject)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18" /></svg>
                                        <span class="line-clamp-1">Mata Pelajaran: {{ $class->subject }}</span>
                                    </div>
                                @endif
                                @if($class->room)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 13.5a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        <span class="line-clamp-1">Ruang: {{ $class->room }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                    <span>{{ $class->students->count() }} Siswa bergabung</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-400">Kode: {{ $class->code }}</span>
                                <a href="{{ route('classroom.show', $class->id) }}" class="flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors group-hover:translate-x-1 duration-200">
                                    <span>Buka Kelas</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>


    <!-- Modal Buat Kelas (Teacher) -->
    <div x-show="openCreate" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openCreate = false" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl border border-slate-100 transform transition-all">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900">Buat Kelas Baru</h3>
                <button @click="openCreate = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('classroom.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Kelas (Wajib)</label>
                    <input type="text" name="name" required placeholder="Contoh: Matematika Kelas X" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bagian / Seksi</label>
                    <input type="text" name="section" placeholder="Contoh: Semester Ganjil" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: Aljabar & Geometri" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ruang</label>
                    <input type="text" name="room" placeholder="Contoh: Kelas 302" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openCreate = false" class="w-1/2 px-4 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="w-1/2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition-all shadow-md shadow-indigo-200">Buat Kelas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Gabung Kelas (Student) -->
    <div x-show="openJoin" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openJoin = false" class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl border border-slate-100 transform transition-all">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900">Gabung Kelas</h3>
                <button @click="openJoin = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('classroom.join') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kode Kelas</label>
                    <input type="text" name="code" required maxlength="6" placeholder="Contoh: GRAP11" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-center font-bold tracking-widest text-lg">
                    <p class="text-xs text-slate-400 mt-2 text-center">Mintalah 6 digit kode kelas dari pengajar Anda.</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openJoin = false" class="w-1/2 px-4 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="w-1/2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition-all shadow-md shadow-indigo-200">Gabung</button>
                </div>
            </form>
        </div>
    </div>
    @include('partials.institutional-logos')
    <!-- Service Worker Registration for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Classroom PWA SW Registered'))
                    .catch(err => console.error('Classroom PWA SW Failed:', err));
            });
        }
    </script>
</body>
</html>
