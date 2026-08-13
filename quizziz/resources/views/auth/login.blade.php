<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Quizizz Interactive</title>
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
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .deep-bg {
            background: radial-gradient(circle at top right, #3b0764, #1e1b4b, #0f172a);
        }
    </style>
</head>
<body class="deep-bg text-slate-100 font-sans min-h-screen flex flex-col justify-between p-4 md:p-6 lg:p-8">

    <!-- Decorative Background Elements -->
    <div class="fixed top-10 left-10 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 right-10 w-96 h-96 rounded-full bg-pink-500/15 blur-3xl pointer-events-none"></div>

    <!-- Main Container -->
    <div class="w-full max-w-5xl mx-auto flex-1 flex flex-col justify-center my-6 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left Hero Banner Card -->
            <div class="lg:col-span-5 glass p-6 sm:p-8 rounded-3xl border border-white/10 flex flex-col justify-between shadow-2xl relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-40 h-40 bg-pink-500/20 rounded-full blur-2xl"></div>
                
                <div>
                    <!-- Brand Title -->
                    <div class="flex items-center gap-3 sm:gap-3.5 mb-6 sm:mb-8">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-tr from-pink-500 to-purple-600 rounded-2xl shadow-xl shadow-purple-500/30 flex items-center justify-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096L3 15.09m6 5.91l4.904-6.096M21 3h-6m6 0v6m0-6L14 9M3 3h6m-6 0v6m0-6l7 7" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black tracking-wider text-white uppercase">QUIZIZZ</h1>
                            <p class="text-[9px] sm:text-[10px] text-pink-400 font-extrabold uppercase tracking-widest">Platform Kuis Interaktif</p>
                        </div>
                    </div>

                    <h2 class="text-lg sm:text-xl font-extrabold text-white leading-snug mb-2.5 sm:mb-3">
                        Solusi Kuis Interaktif & Latihan Mandiri
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium mb-5 sm:mb-6">
                        Akses kuis seru, tantangan solo, dan evaluasi hasil belajar secara interaktif dalam satu platform modern.
                    </p>
                </div>

                <!-- Partner logos badge inside hero banner -->
                <div class="pt-4 sm:pt-6 border-t border-white/15">
                    <span class="text-[10px] font-black uppercase tracking-widest text-pink-400 block mb-2.5 sm:mb-3">Mitra Penyelenggara:</span>
                    <div class="grid grid-cols-2 gap-2 sm:gap-2.5">
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                            <img src="{{ asset('logo_unma.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="Universitas Majalengka">
                        </div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                            <img src="{{ asset('diktisaintek.webp') }}" class="h-7 sm:h-9 w-auto object-contain" title="Diktisaintek">
                        </div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                            <img src="{{ asset('diktisaintek_berdampak.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="Diktisaintek Berdampak">
                        </div>
                        <div class="bg-white/95 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                            <img src="{{ asset('bima.png') }}" class="h-7 sm:h-9 w-auto object-contain" title="BIMA Kemdikbudristek">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Login Form Card -->
            <div class="lg:col-span-7 glass p-8 md:p-10 rounded-3xl shadow-2xl relative overflow-hidden border border-white/10 flex flex-col justify-center">

                <div class="mb-6">
                    <h2 class="text-2xl font-black text-white">Selamat Datang Kembali!</h2>
                    <p class="text-slate-400 text-xs mt-1 leading-relaxed">Masukkan akun Anda untuk melanjutkan bermain atau mengelola kuis.</p>
                </div>

                <!-- Session / Error Alerts -->
                @if(session('success'))
                    <div class="mb-5 p-4 rounded-2xl bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 text-rose-300 border border-rose-500/20 text-xs font-semibold space-y-1">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3.5 text-sm text-white placeholder-slate-500 outline-none transition-all">
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Password</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3.5 text-sm text-white placeholder-slate-500 outline-none transition-all">
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-400 hover:text-slate-200">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded bg-slate-900 border-white/20 text-pink-500 focus:ring-0 focus:ring-offset-0">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/25 mt-2">
                        Masuk Sekarang
                    </button>
                </form>

                <!-- Google OAuth Button -->
                <div class="mt-4">
                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-white/10"></div>
                        <span class="flex-shrink mx-3 text-[10px] uppercase font-bold text-slate-500 tracking-widest">atau</span>
                        <div class="flex-grow border-t border-white/10"></div>
                    </div>
                    <a href="{{ route('auth.google') }}" class="w-full mt-1 bg-white hover:bg-slate-100 text-slate-900 font-bold py-3.5 px-4 rounded-2xl text-xs flex items-center justify-center gap-3 transition-all transform active:scale-95 shadow-md">
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
                <div class="mt-6 text-center text-xs text-slate-400">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-pink-400 hover:text-pink-300 underline ml-1">
                        Daftar di sini
                    </a>
                </div>

            </div>

        </div>

    </div>

    <!-- Institutional Logos Footer at Bottom -->
    @include('partials.institutional-logos')

</body>
</html>
