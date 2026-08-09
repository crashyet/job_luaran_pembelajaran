<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom - Pilih Peran Akun</title>
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

    <div class="w-full max-w-lg glass rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-200/50 text-center relative z-10" x-data="{ selectedRole: 'student' }">
        
        <!-- App Header Icon -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 shadow-xl shadow-indigo-200 mb-6 text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.231-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84a50.58 50.58 0 00-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M12 21v-3.75m.75-13.06c.084.628.755 1.127 1.459 1.127a1.459 1.459 0 000-2.917c-.704 0-1.375.5-1.459 1.128z" />
            </svg>
        </div>

        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pilih Peran Akun Anda</h2>
        <p class="text-slate-500 text-xs mt-1 font-medium mb-6">Satu langkah lagi untuk menyelesaikan pendaftaran akun Google Anda.</p>

        <!-- Google User Profile Card -->
        <div class="bg-white/80 border border-slate-200/80 rounded-2xl p-3.5 mb-6 flex items-center gap-3 text-left">
            @if(isset($googleUser['avatar']) && $googleUser['avatar'])
                <img src="{{ $googleUser['avatar'] }}" alt="{{ $googleUser['name'] }}" class="w-10 h-10 rounded-full border-2 border-indigo-500 shadow-sm">
            @else
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold uppercase text-xs">
                    {{ substr($googleUser['name'], 0, 2) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-slate-800 truncate">{{ $googleUser['name'] }}</p>
                <p class="text-[11px] text-slate-500 truncate font-medium">{{ $googleUser['email'] }}</p>
            </div>
            <span class="text-[9px] uppercase font-extrabold tracking-wider bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                Google Auth
            </span>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('select.role.post') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Role Selection Cards -->
            <div class="grid grid-cols-2 gap-3 text-left">
                <label @click="selectedRole = 'student'" class="p-4 rounded-2xl border transition-all cursor-pointer relative flex flex-col items-center justify-center gap-2 text-center" :class="selectedRole === 'student' ? 'bg-indigo-50/90 border-indigo-600 text-indigo-900 shadow-md ring-2 ring-indigo-600/20' : 'bg-white/60 border-slate-200 text-slate-600 hover:bg-white'">
                    <input type="radio" name="role" value="student" class="hidden" x-model="selectedRole">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold">Siswa</p>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Gabung kelas & kirim tugas</p>
                    </div>
                </label>

                <label @click="selectedRole = 'teacher'" class="p-4 rounded-2xl border transition-all cursor-pointer relative flex flex-col items-center justify-center gap-2 text-center" :class="selectedRole === 'teacher' ? 'bg-indigo-50/90 border-indigo-600 text-indigo-900 shadow-md ring-2 ring-indigo-600/20' : 'bg-white/60 border-slate-200 text-slate-600 hover:bg-white'">
                    <input type="radio" name="role" value="teacher" class="hidden" x-model="selectedRole">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold">Pengajar / Guru</p>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Buat & kelola kelas</p>
                    </div>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                <span>Lanjutkan ke Classroom</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>
    </div>
</body>
</html>
