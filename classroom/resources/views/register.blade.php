<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom - Daftar Akun Baru</title>
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

    <div class="w-full max-w-4xl flex flex-col md:flex-row glass rounded-3xl shadow-2xl overflow-hidden border border-slate-200/50" x-data="{ selectedRole: '{{ old('role', 'student') }}' }">
        <!-- Left Side: Welcome and Brand Info -->
        <div class="md:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-8 md:p-12 flex flex-col justify-between relative overflow-hidden text-white">
            <!-- Pattern -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-48 h-48 rounded-full bg-white/5 blur-xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-white/10 p-2.5 rounded-xl border border-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.231-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84a50.58 50.58 0 00-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M12 21v-3.75m.75-13.06c.084.628.755 1.127 1.459 1.127a1.459 1.459 0 000-2.917c-.704 0-1.375.5-1.459 1.128z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white leading-tight">Classroom</h1>
                        <p class="text-[10px] text-indigo-200 font-semibold tracking-wider uppercase">Google Classroom Clone</p>
                    </div>
                </div>

                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight leading-tight mb-4">
                    Bergabung Sekarang & Mulai Pembelajaran
                </h2>
                <p class="text-indigo-100 text-sm leading-relaxed mb-6 font-medium">
                    Daftar akun sebagai Pengajar untuk mengelola kelas dan tugas, atau sebagai Siswa untuk mendaftar ke kelas dan mengumpulkan tugas secara praktis.
                </p>
            </div>

            <div class="relative z-10 text-xs text-indigo-200 font-medium">
                © {{ date('Y') }} Classroom Platform. All rights reserved.
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="md:w-1/2 p-8 md:p-10 bg-white/80 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Buat Akun Baru</h3>
            <p class="text-slate-500 text-xs mb-6 font-medium">Isi formulir di bawah ini untuk membuat akun Classroom Anda.</p>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Peran Akun</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label @click="selectedRole = 'student'" class="p-3 rounded-xl border transition-all cursor-pointer text-center relative flex flex-col items-center justify-center gap-1.5" :class="selectedRole === 'student' ? 'bg-indigo-50 border-indigo-600 text-indigo-900 shadow-sm ring-1 ring-indigo-600' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                            <input type="radio" name="role" value="student" class="hidden" x-model="selectedRole">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold">Siswa</p>
                                <p class="text-[9px] text-slate-400 font-medium">Gabung & kerjakan tugas</p>
                            </div>
                        </label>

                        <label @click="selectedRole = 'teacher'" class="p-3 rounded-xl border transition-all cursor-pointer text-center relative flex flex-col items-center justify-center gap-1.5" :class="selectedRole === 'teacher' ? 'bg-indigo-50 border-indigo-600 text-indigo-900 shadow-sm ring-1 ring-indigo-600' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                            <input type="radio" name="role" value="teacher" class="hidden" x-model="selectedRole">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold">Pengajar / Guru</p>
                                <p class="text-[9px] text-slate-400 font-medium">Buat & kelola kelas</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-xs transition-all font-semibold text-slate-800">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" required placeholder="nama@email.com" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-xs transition-all font-semibold text-slate-800">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-xs transition-all font-semibold text-slate-800">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-xs transition-all font-semibold text-slate-800">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl font-bold text-xs transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 mt-2">
                    <span>Daftar Akun Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-5 text-center text-xs text-slate-500 font-medium">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 underline ml-1">
                    Masuk di sini
                </a>
            </div>
        </div>
    </div>
</body>
</html>
