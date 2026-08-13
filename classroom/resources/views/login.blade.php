<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom Clone - Masuk</title>
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
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .grid-bg {
            background-image: radial-gradient(rgba(79, 70, 229, 0.07) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex items-center justify-center p-4 relative grid-bg overflow-x-hidden">
    <!-- Decorative glow elements -->
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-400/20 blur-3xl -z-10 animate-pulse"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-purple-400/20 blur-3xl -z-10 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-4xl flex flex-col md:flex-row glass rounded-3xl shadow-2xl overflow-hidden border border-slate-200/50">
        <!-- Left Side: Welcome and Brand Info -->
        <div class="md:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-6 sm:p-8 md:p-12 flex flex-col justify-between relative overflow-hidden text-white">
            <!-- Pattern -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-48 h-48 rounded-full bg-white/5 blur-xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6 sm:mb-8">
                    <div class="bg-white/10 p-2.5 rounded-xl border border-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.231-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84a50.58 50.58 0 00-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M12 21v-3.75m.75-13.06c.084.628.755 1.127 1.459 1.127a1.459 1.459 0 000-2.917c-.704 0-1.375.5-1.459 1.128z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white leading-tight">Classroom</h1>
                        <p class="text-[10px] text-indigo-200 font-semibold tracking-wider uppercase">Google Classroom Clone</p>
                    </div>
                </div>

                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight leading-tight mb-3 sm:mb-4">
                    Belajar & Berbagi di Satu Tempat Aman
                </h2>
                <p class="text-indigo-100 text-xs sm:text-sm leading-relaxed mb-5 sm:mb-6 font-medium">
                    Masuk untuk mengelola kelas, membagikan pengumuman, mengirim tugas, dan memantau perkembangan nilai Anda secara real-time.
                </p>

                <!-- Partner logos badge on card -->
                <div class="pt-4 sm:pt-5 border-t border-white/20">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-200 mb-2.5">Mitra Penyelenggara:</p>
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-2.5">
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl hover:scale-105 transition-transform shadow-sm flex items-center justify-center"><img src="{{ asset('logo_unma.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="Universitas Majalengka"></div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl hover:scale-105 transition-transform shadow-sm flex items-center justify-center"><img src="{{ asset('diktisaintek.webp') }}" class="h-7 sm:h-9 w-auto object-contain" title="Diktisaintek"></div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl hover:scale-105 transition-transform shadow-sm flex items-center justify-center"><img src="{{ asset('diktisaintek_berdampak.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="Diktisaintek Berdampak"></div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl hover:scale-105 transition-transform shadow-sm flex items-center justify-center"><img src="{{ asset('bima.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="BIMA Kemdikbudristek"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-8 md:p-12 bg-white/80 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight mb-2">Selamat Datang</h3>
            <p class="text-slate-500 text-sm mb-8 font-medium">Masukkan email dan password terdaftar Anda untuk masuk ke sistem.</p>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-rose-50 text-rose-700 border-rose-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" name="email" id="email" required placeholder="nama@email.com" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm transition-all font-semibold text-slate-800">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm transition-all font-semibold text-slate-800">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" checked class="w-4.5 h-4.5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500/20">
                        <span class="text-xs text-slate-500 group-hover:text-slate-700 transition-colors font-semibold select-none">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 mt-2">
                    <span>Masuk Aplikasi</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <!-- Google OAuth Button -->
            <div class="mt-4">
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[10px] uppercase font-bold text-slate-400 tracking-widest">atau</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>
                <a href="{{ route('auth.google') }}" class="w-full mt-1 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z"/>
                        <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.8z"/>
                        <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15s.7 5.3 1.9 7.7l3.7-2.9c-.8-1.5-1.2-3.3-1.2-5z"/>
                        <path fill="#34A853" d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.3-6.4-5.2L1.9 16C3.7 19.7 7.5 23 12 23z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>
            </div>

            <!-- Register Link -->
            <div class="mt-6 text-center text-xs text-slate-500 font-medium">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 underline ml-1">
                    Daftar di sini
                </a>
            </div>
        </div>
    </div>
</body>
</html>
