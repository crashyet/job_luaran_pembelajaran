<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $classroom->name }} - Classroom</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen pb-24" x-data="{ openCreateAss: false }">
    <!-- Navbar -->
    <nav class="sticky top-0 z-40 w-full glass border-b border-slate-200 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-indigo-600 transition-colors p-2 rounded-xl hover:bg-indigo-50/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="h-6 w-px bg-slate-200 mx-1"></div>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-slate-900 leading-none">{{ $classroom->name }}</h1>
                <span class="text-xs font-semibold text-slate-400 mt-1 inline-block">{{ $classroom->section ?? 'Kelas Umum' }}</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="hidden md:flex items-center gap-1 bg-slate-100 p-1.5 rounded-xl border border-slate-200/50">
            <a href="?tab=stream" class="px-4 py-2 text-xs font-extrabold uppercase tracking-wider rounded-lg transition-all {{ $tab === 'stream' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                Forum
            </a>
            <a href="?tab=classwork" class="px-4 py-2 text-xs font-extrabold uppercase tracking-wider rounded-lg transition-all {{ $tab === 'classwork' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                Tugas Kelas
            </a>
            <a href="?tab=people" class="px-4 py-2 text-xs font-extrabold uppercase tracking-wider rounded-lg transition-all {{ $tab === 'people' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                Anggota
            </a>
            @if($classroom->teacher_id === $activeUser->id)
                <a href="?tab=grades" class="px-4 py-2 text-xs font-extrabold uppercase tracking-wider rounded-lg transition-all {{ $tab === 'grades' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                    Nilai
                </a>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                {{ $activeUser->role === 'teacher' ? 'Pengajar' : 'Siswa' }}
            </div>
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-300 transform active:scale-95 border border-slate-200/60">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Mobile Tabs (visible only on small screens) -->
    <div class="md:hidden flex border-b border-slate-200 bg-white sticky top-[69px] z-30">
        <a href="?tab=stream" class="w-1/3 py-3 text-center text-xs font-extrabold uppercase tracking-wider border-b-2 {{ $tab === 'stream' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500' }}">
            Forum
        </a>
        <a href="?tab=classwork" class="w-1/3 py-3 text-center text-xs font-extrabold uppercase tracking-wider border-b-2 {{ $tab === 'classwork' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500' }}">
            Tugas
        </a>
        <a href="?tab=people" class="w-1/3 py-3 text-center text-xs font-extrabold uppercase tracking-wider border-b-2 {{ $tab === 'people' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500' }}">
            Anggota
        </a>
        @if($classroom->teacher_id === $activeUser->id)
            <a href="?tab=grades" class="w-1/4 py-3 text-center text-xs font-extrabold uppercase tracking-wider border-b-2 {{ $tab === 'grades' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500' }}">
                Nilai
            </a>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 py-8">
        <!-- Banner Theme Colors mapping -->
        @php
            $themeColors = [
                'indigo' => 'from-indigo-600 to-indigo-800 text-indigo-100',
                'emerald' => 'from-emerald-600 to-emerald-800 text-emerald-100',
                'purple' => 'from-purple-600 to-purple-800 text-purple-100',
                'rose' => 'from-rose-600 to-rose-800 text-rose-100',
                'blue' => 'from-blue-600 to-blue-800 text-blue-100',
            ];
            $themeColor = $themeColors[$classroom->banner_theme] ?? $themeColors['indigo'];
        @endphp

        <!-- 1. HEADER BANNER -->
        <div class="bg-gradient-to-br {{ $themeColor }} p-8 md:p-12 rounded-3xl shadow-lg relative overflow-hidden mb-8">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>
            
            <div class="relative z-10 flex flex-col justify-between min-h-[120px]">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight">{{ $classroom->name }}</h2>
                    @if($classroom->section)
                        <p class="text-lg text-white/80 font-medium mt-1.5">{{ $classroom->section }}</p>
                    @endif
                </div>
                <div class="mt-8 flex flex-wrap gap-4 items-center justify-between border-t border-white/20 pt-6">
                    <div class="text-sm font-semibold text-white/95">
                        <span class="text-white/60 font-medium">Mata Pelajaran:</span> {{ $classroom->subject ?? '-' }}
                        <span class="mx-3 text-white/30">|</span>
                        <span class="text-white/60 font-medium">Ruang:</span> {{ $classroom->room ?? '-' }}
                    </div>
                    <div class="bg-white/15 border border-white/25 px-4 py-2 rounded-xl flex items-center gap-2.5 text-white">
                        <span class="text-xs font-semibold text-white/70">Kode Kelas:</span>
                        <span class="text-base font-bold tracking-widest">{{ $classroom->code }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-sm font-medium">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ==================== STREAM TAB ==================== -->
        @if($tab === 'stream')
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar Code & Due Dates -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-3">Mendatang</h4>
                        @php
                            $upcoming = $assignments->filter(function($a) {
                                return !$a->due_date || $a->due_date->isFuture();
                            })->take(3);
                        @endphp
                        
                        @if($upcoming->isEmpty())
                            <p class="text-xs text-slate-500">Hore, tidak ada tugas yang perlu dikumpulkan segera!</p>
                        @else
                            <div class="space-y-3">
                                @foreach($upcoming as $up)
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-700 leading-normal line-clamp-1">{{ $up->title }}</p>
                                        <p class="text-slate-400 mt-0.5">Tenggat: {{ $up->due_date ? $up->due_date->format('d M, H:i') : 'Tidak ada' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <a href="?tab=classwork" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors mt-4">Lihat semua</a>
                    </div>
                </div>

                <!-- Announcement / Feed Section -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Announcement Composer -->
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm" x-data="{ editing: false }">
                        <div x-show="!editing" @click="editing = true" class="flex items-center gap-3.5 cursor-pointer hover:bg-slate-50 p-2.5 rounded-xl transition-all">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center font-bold text-indigo-600 text-sm uppercase">
                                {{ substr($activeUser->name, 0, 2) }}
                            </div>
                            <span class="text-slate-500 text-sm font-medium">Bagikan sesuatu dengan kelas Anda...</span>
                        </div>

                        <div x-show="editing" x-cloak>
                            <form action="{{ route('classroom.post', $classroom->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="announcement">
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-2">Umumkan Sesuatu</label>
                                    <textarea name="content" required rows="3" placeholder="Tulis pengumuman atau instruksi untuk kelas Anda..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm"></textarea>
                                </div>

                                <div class="mb-4" x-data="{ fileName: '' }">
                                    <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-2">Lampiran File (Opsional)</label>
                                    <div class="flex items-center gap-3">
                                        <label class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 px-4 py-2.5 rounded-xl cursor-pointer transition-all text-xs font-bold shadow-sm">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A3 3 0 1011.293 13.7m3.535-4.536l-3.535 3.535m0 0a3 3 0 11-4.243-4.243l3.535-3.536m3.536 3.536L9.75 14.636a5.002 5.002 0 01-7.072 0 5.002 5.002 0 010-7.072l6.239-6.239a7.5 7.5 0 0110.606 10.606l-6.24 6.24" />
                                            </svg>
                                            <span>Pilih File</span>
                                            <input type="file" name="attachment" class="hidden" @change="fileName = $event.target.files[0]?.name || ''">
                                        </label>
                                        <span class="text-xs text-slate-500 font-semibold truncate max-w-[200px]" x-text="fileName || 'Belum ada file terpilih'"></span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Format: PDF, Word, Excel, Gambar, Zip, dll. Maksimal 10MB.</p>
                                </div>

                                <div class="flex items-center justify-end gap-2.5">
                                    <button type="button" @click="editing = false" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-50 transition-all">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-all shadow-md shadow-indigo-100">Posting</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Post Feed -->
                    @if($posts->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                            <p class="text-slate-500 text-sm">Forum kelas masih kosong. Buat pengumuman pertama Anda!</p>
                        </div>
                    @else
                        @foreach($posts as $post)
                            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                                <!-- Post Header -->
                                <div class="p-5 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm uppercase {{ $post->type === 'assignment' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-100 text-slate-700' }}">
                                        @if($post->type === 'assignment')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @else
                                            {{ substr($post->author->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <h4 class="font-bold text-slate-800 text-sm">{{ $post->author->name }}</h4>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($post->type === 'assignment')
                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="text-xs font-bold text-indigo-600 hover:underline">TUGAS BARU: {{ $post->title }}</span>
                                                <span class="text-[10px] bg-indigo-50 border border-indigo-100 text-indigo-600 font-semibold px-2 py-0.5 rounded-md">
                                                    {{ $post->points }} Poin
                                                </span>
                                            </div>
                                        @endif
                                        <div class="text-slate-700 text-sm mt-3 leading-relaxed whitespace-pre-line">{{ $post->content }}</div>

                                        @if($post->attachment_path)
                                            <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex items-center justify-between gap-4 max-w-md group hover:bg-slate-100/50 transition-colors">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-slate-800 truncate" title="{{ $post->attachment_name }}">{{ $post->attachment_name }}</p>
                                                        <p class="text-[10px] text-slate-400 font-semibold uppercase">Lampiran File</p>
                                                    </div>
                                                </div>
                                                <a href="{{ asset($post->attachment_path) }}" target="_blank" download class="flex-shrink-0 bg-white border border-slate-200 hover:border-indigo-500 hover:text-indigo-600 p-2 rounded-xl transition-all shadow-sm flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($post->type === 'assignment' && $post->due_date)
                                            <div class="mt-3 flex items-center gap-1.5 text-xs text-rose-600 font-semibold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span>Tenggat: {{ $post->due_date->format('d M Y, H:i') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Comments List -->
                                <div class="bg-slate-50 border-t border-slate-100 px-5 py-4 space-y-4">
                                    <div class="flex items-center gap-2 border-b border-slate-200/50 pb-2 mb-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Komentar Kelas ({{ $post->comments->count() }})</span>
                                    </div>
                                    
                                    @foreach($post->comments as $comment)
                                        <div class="flex items-start gap-3.5 text-xs">
                                            <div class="w-8 h-8 rounded-lg bg-slate-200/80 text-slate-700 font-bold uppercase flex items-center justify-center flex-shrink-0">
                                                {{ substr($comment->author->name, 0, 2) }}
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-slate-800">{{ $comment->author->name }}</span>
                                                    <span class="text-[9px] text-slate-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-slate-600 mt-1 leading-normal whitespace-pre-line">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Add Comment Form -->
                                    <form action="{{ route('post.comment', $post->id) }}" method="POST" class="flex gap-3 items-center mt-3 pt-3 border-t border-slate-200/30">
                                        @csrf
                                        <input type="text" name="content" required placeholder="Tulis komentar kelas..." class="flex-grow bg-white px-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                                        <button type="submit" class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif

        <!-- ==================== CLASSWORK TAB ==================== -->
        @if($tab === 'classwork')
            <div class="space-y-6">
                <!-- Teacher Action Buttons -->
                @if($classroom->teacher_id === $activeUser->id)
                    <div class="flex justify-between items-center bg-white border border-slate-200 p-4 rounded-2xl shadow-sm">
                        <h3 class="font-bold text-slate-800 text-sm">Kelola Tugas Pembelajaran</h3>
                        <button @click="openCreateAss = true" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all transform active:scale-95 shadow-md shadow-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span>Buat Tugas</span>
                        </button>
                    </div>
                @endif

                <!-- Assignments List -->
                @if($assignments->isEmpty())
                    <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200/50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800">Belum Ada Tugas</h4>
                        <p class="text-slate-500 text-sm mt-1">Belum ada tugas yang diberikan di kelas ini.</p>
                    </div>
                @else
                    <div class="space-y-4" x-data="{ activeAssId: null }">
                        @foreach($assignments as $ass)
                            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
                                <!-- Collapsed Header -->
                                <div @click="activeAssId = (activeAssId === {{ $ass->id }} ? null : {{ $ass->id }})" class="p-5 flex items-center justify-between cursor-pointer hover:bg-slate-50 select-none">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600">{{ $ass->title }}</h4>
                                            <p class="text-[10px] text-slate-400 mt-1 font-semibold">Dibuat {{ $ass->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <div class="text-right hidden sm:block">
                                            @if($ass->due_date)
                                                <p class="text-xs text-rose-600 font-bold">Tenggat: {{ $ass->due_date->format('d M Y, H:i') }}</p>
                                            @else
                                                <p class="text-xs text-slate-400 font-bold">Tidak ada tenggat</p>
                                            @endif
                                        </div>
                                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" :class="activeAssId === {{ $ass->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                    </div>
                                </div>

                                <!-- Expanded Details Body -->
                                <div x-show="activeAssId === {{ $ass->id }}" x-cloak class="border-t border-slate-100 bg-slate-50/50 p-6 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="md:col-span-2 space-y-4">
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] bg-slate-200 border border-slate-300 text-slate-700 font-bold px-2 py-0.5 rounded">
                                                    Maksimal Poin: {{ $ass->points }} Poin
                                                </span>
                                            </div>
                                            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $ass->content }}</p>
                                        </div>
                                        
                                        <!-- Submit / Submission Widget (Student Side) -->
                                        @if($activeUser->role === 'student')
                                            <div class="md:col-span-1 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-4">
                                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                    <h5 class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Tugas Anda</h5>
                                                    
                                                    @php
                                                        $sub = $mySubmissions[$ass->id] ?? null;
                                                    @endphp

                                                    @if($sub)
                                                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-md {{ $sub->status === 'graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/50' : 'bg-amber-50 text-amber-700 border border-amber-200/50' }}">
                                                            {{ $sub->status === 'graded' ? 'Dinilai' : 'Diserahkan' }}
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200/50">
                                                            Belum Dikumpulkan
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($sub)
                                                    <div class="space-y-3">
                                                        <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl">
                                                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Jawaban Anda:</p>
                                                            <p class="text-xs text-slate-700 whitespace-pre-line">{{ $sub->content }}</p>
                                                        </div>
                                                        @if($sub->status === 'graded')
                                                            <div class="bg-indigo-50 border border-indigo-100 p-3.5 rounded-xl">
                                                                <p class="text-xs font-bold text-slate-800">Nilai: <span class="text-lg font-black text-indigo-700 ml-1">{{ $sub->grade }}</span> / {{ $ass->points }}</p>
                                                                @if($sub->feedback)
                                                                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-400 mt-2">Komentar Pengajar:</p>
                                                                    <p class="text-xs text-indigo-800 italic mt-0.5">"{{ $sub->feedback }}"</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Submit Form -->
                                                @if(!$sub || $sub->status !== 'graded')
                                                    <form action="{{ route('assignment.submit', $ass->id) }}" method="POST" class="space-y-3">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Tulis Jawaban atau Link Tugas</label>
                                                            <textarea name="content" required rows="3" placeholder="Masukkan link Figma, Google Drive, atau ketik deskripsi jawaban Anda..." class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500"></textarea>
                                                        </div>
                                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all transform active:scale-95 shadow-md shadow-indigo-100">
                                                            {{ $sub ? 'Kirim Ulang Tugas' : 'Serahkan Tugas' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Submissions List (Teacher Side) -->
                                        @if($classroom->teacher_id === $activeUser->id)
                                            <div class="md:col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden" x-data="{ gradingSubId: null }">
                                                <div class="p-4 bg-slate-50 border-b border-slate-100">
                                                    <h5 class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Pengumpulan Siswa</h5>
                                                </div>
                                                
                                                @php
                                                    $subs = $allSubmissions[$ass->id] ?? collect();
                                                @endphp

                                                @if($subs->isEmpty())
                                                    <div class="p-6 text-center">
                                                        <p class="text-xs text-slate-400 font-semibold">Belum ada siswa yang mengumpulkan tugas ini.</p>
                                                    </div>
                                                @else
                                                    <div class="divide-y divide-slate-100">
                                                        @foreach($subs as $item)
                                                            <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                                <div class="space-y-1.5 flex-grow">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-bold text-slate-800 text-xs">{{ $item->student->name }}</span>
                                                                        <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded {{ $item->status === 'graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                                                            {{ $item->status === 'graded' ? 'Dinilai' : 'Menunggu Penilaian' }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 whitespace-pre-line">{{ $item->content }}</p>
                                                                    @if($item->grade)
                                                                        <p class="text-xs text-indigo-700 font-bold">Nilai: {{ $item->grade }} / {{ $ass->points }}</p>
                                                                    @endif
                                                                    @if($item->feedback)
                                                                        <p class="text-xs text-slate-500 italic">Masukan: "{{ $item->feedback }}"</p>
                                                                    @endif
                                                                </div>

                                                                <div class="flex-shrink-0">
                                                                    <button @click="gradingSubId = (gradingSubId === {{ $item->id }} ? null : {{ $item->id }})" class="bg-indigo-50 border border-indigo-100 hover:bg-indigo-100/50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl transition-all">
                                                                        Nilai / Masukan
                                                                    </button>
                                                                </div>

                                                                <!-- Grading Expandable Form -->
                                                                <div x-show="gradingSubId === {{ $item->id }}" x-cloak class="w-full md:w-auto md:basis-full md:order-last mt-3 bg-indigo-50/30 border border-indigo-100/50 p-4 rounded-xl space-y-3">
                                                                    <form action="{{ route('submission.grade', $item->id) }}" method="POST" class="space-y-3">
                                                                        @csrf
                                                                        <div class="flex gap-4">
                                                                            <div class="w-24">
                                                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Nilai (0-100)</label>
                                                                                <input type="number" name="grade" required min="0" max="100" value="{{ $item->grade ?? '' }}" class="w-full px-3 py-1.5 text-xs bg-white rounded-lg border border-slate-200">
                                                                            </div>
                                                                            <div class="flex-grow">
                                                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Masukan Pengajar (Feedback)</label>
                                                                                <input type="text" name="feedback" value="{{ $item->feedback ?? '' }}" placeholder="Contoh: Kerja bagus, tingkatkan layouting..." class="w-full px-3 py-1.5 text-xs bg-white rounded-lg border border-slate-200">
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex justify-end gap-2">
                                                                            <button type="button" @click="gradingSubId = null" class="px-3 py-1.5 border border-slate-200 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-slate-100 transition-all">Batal</button>
                                                                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded-lg transition-all shadow-sm">Simpan Nilai</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- ==================== PEOPLE TAB ==================== -->
        @if($tab === 'people')
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-10">
                <!-- Teachers Section -->
                <div>
                    <h3 class="text-2xl font-bold tracking-tight text-indigo-700 border-b border-slate-100 pb-3 mb-5">Pengajar</h3>
                    <div class="flex items-center gap-4 p-2.5 hover:bg-slate-50/50 rounded-xl transition-all">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold uppercase flex items-center justify-center border border-indigo-200/30">
                            {{ substr($classroom->teacher->name, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm leading-none">{{ $classroom->teacher->name }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $classroom->teacher->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Students Section -->
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-2xl font-bold tracking-tight text-indigo-700">Teman Sekelas</h3>
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">{{ $classroom->students->count() }} Siswa</span>
                    </div>

                    @if($classroom->students->isEmpty())
                        <p class="text-slate-500 text-sm py-4">Belum ada siswa yang bergabung di kelas ini.</p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach($classroom->students as $student)
                                <div class="flex items-center gap-4 py-3.5 hover:bg-slate-50/50 rounded-xl transition-all">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 font-bold uppercase flex items-center justify-center border border-slate-200/50">
                                        {{ substr($student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-none">{{ $student->name }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $student->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- ==================== GRADES TAB ==================== -->
        @if($tab === 'grades')
            @if($classroom->teacher_id !== $activeUser->id)
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-6 rounded-2xl text-center">
                    <p class="font-bold">Akses Ditolak</p>
                    <p class="text-sm mt-1">Hanya pengajar kelas yang dapat mengakses buku nilai.</p>
                </div>
            @else
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-900">Buku Nilai Kelas</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola ringkasan tugas dan nilai siswa Anda.</p>
                    </div>

                    @if($classroom->students->isEmpty() || $assignments->isEmpty())
                        <div class="p-12 text-center text-slate-500 text-sm">
                            Buku nilai masih kosong. Pastikan sudah ada siswa yang bergabung dan ada tugas yang diterbitkan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/80">
                                        <th class="p-5 font-extrabold min-w-[200px]">Nama Siswa</th>
                                        @foreach($assignments as $ass)
                                            <th class="p-5 font-extrabold min-w-[150px]">
                                                <p class="line-clamp-1 text-slate-700">{{ $ass->title }}</p>
                                                <p class="text-[9px] text-slate-400 mt-0.5 font-semibold">Tenggat: {{ $ass->due_date ? $ass->due_date->format('d M') : '-' }}</p>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    @foreach($classroom->students as $student)
                                        <tr class="hover:bg-slate-50/50 transition-all font-semibold">
                                            <!-- Student Name -->
                                            <td class="p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold uppercase flex items-center justify-center border border-slate-200/50 text-[10px]">
                                                        {{ substr($student->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-800 leading-none">{{ $student->name }}</p>
                                                        <p class="text-[10px] text-slate-400 font-medium mt-1">{{ $student->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Assignments Scores -->
                                            @foreach($assignments as $ass)
                                                @php
                                                    $sub = $allSubmissions[$ass->id]?->firstWhere('student_id', $student->id);
                                                @endphp
                                                <td class="p-5">
                                                    @if($sub)
                                                        @if($sub->status === 'graded')
                                                            <div class="space-y-1">
                                                                <span class="text-base font-black text-indigo-600">{{ $sub->grade }}</span>
                                                                <span class="text-[10px] text-slate-400">/ {{ $ass->points }}</span>
                                                            </div>
                                                        @else
                                                            <a href="?tab=classwork" class="inline-block px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold rounded-lg hover:bg-amber-100 transition-colors uppercase tracking-wider">
                                                                Butuh Nilai
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wider">Kosong</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </main>


    <!-- Modal Create Assignment -->
    <div x-show="openCreateAss" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openCreateAss = false" class="bg-white rounded-3xl w-full max-w-xl p-6 shadow-2xl border border-slate-100 transform transition-all">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900">Buat Tugas Baru</h3>
                <button @click="openCreateAss = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('classroom.post', $classroom->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="assignment">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Judul Tugas</label>
                    <input type="text" name="title" required placeholder="Contoh: Tugas 2 - Desain Tipografi" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Instruksi Tugas</label>
                    <textarea name="content" required rows="4" placeholder="Tulis instruksi lengkap tugas dan materi penunjang..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Poin Maksimal</label>
                        <input type="number" name="points" value="100" min="0" max="100" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tenggat Waktu</label>
                        <input type="datetime-local" name="due_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="openCreateAss = false" class="w-1/2 px-4 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="w-1/2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition-all shadow-md shadow-indigo-200">Buat Tugas</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
