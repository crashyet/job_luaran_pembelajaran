<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizizz - Pilih Peran Akun</title>
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
<body class="deep-bg text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">
    <!-- Decorative background elements -->
    <div class="fixed top-10 left-10 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 right-10 w-96 h-96 rounded-full bg-pink-500/15 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg my-8 relative z-10" x-data="{ selectedRole: 'student' }">
        
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-pink-500 to-purple-600 rounded-3xl shadow-xl shadow-purple-500/30 mb-4 transform hover:scale-105 transition-transform duration-300">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21m0 0l-.813-5.096L3 15.09m6 5.91l4.904-6.096M21 3h-6m6 0v6m0-6L14 9M3 3h6m-6 0v6m0-6l7 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-black tracking-wider text-white uppercase">QUIZIZZ</h1>
            <p class="text-xs text-pink-400 font-extrabold uppercase tracking-widest mt-1">Pilih Peran Akun Anda</p>
        </div>

        <div class="glass p-8 rounded-3xl shadow-2xl relative overflow-hidden border border-white/10 text-center">
            
            <!-- Google User Profile Card -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 mb-6 flex items-center gap-3 text-left">
                @if(isset($googleUser['avatar']) && $googleUser['avatar'])
                    <img src="{{ $googleUser['avatar'] }}" alt="{{ $googleUser['name'] }}" class="w-11 h-11 rounded-full border-2 border-pink-500 shadow-md">
                @else
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 text-white flex items-center justify-center font-bold uppercase text-xs shadow-md">
                        {{ substr($googleUser['name'], 0, 2) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ $googleUser['name'] }}</p>
                    <p class="text-[11px] text-slate-400 truncate font-medium">{{ $googleUser['email'] }}</p>
                </div>
                <span class="text-[9px] uppercase font-extrabold tracking-widest bg-pink-500/20 text-pink-300 border border-pink-500/30 px-2.5 py-1 rounded-lg">
                    Google OAuth
                </span>
            </div>

            <p class="text-slate-300 text-xs mb-6 font-medium leading-relaxed">
                Pilih bagaimana Anda ingin menggunakan Quizizz untuk melanjutkan ke dashboard:
            </p>

            @if($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-500/10 text-rose-300 border border-rose-500/20 text-xs font-semibold space-y-1 text-left">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('select.role.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Role Selection Cards -->
                <div class="grid grid-cols-2 gap-3 text-left">
                    <label @click="selectedRole = 'student'" class="p-4 rounded-2xl border transition-all cursor-pointer text-center relative overflow-hidden flex flex-col items-center justify-center gap-2" :class="selectedRole === 'student' ? 'bg-pink-500/20 border-pink-500 text-white shadow-lg shadow-pink-500/10' : 'bg-slate-900/50 border-white/10 text-slate-400 hover:bg-slate-900/80'">
                        <input type="radio" name="role" value="student" class="hidden" x-model="selectedRole">
                        <div class="w-12 h-12 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold">Siswa / Peserta</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Ikuti kuis & latihan</p>
                        </div>
                    </label>

                    <label @click="selectedRole = 'teacher'" class="p-4 rounded-2xl border transition-all cursor-pointer text-center relative overflow-hidden flex flex-col items-center justify-center gap-2" :class="selectedRole === 'teacher' ? 'bg-purple-500/20 border-purple-500 text-white shadow-lg shadow-purple-500/10' : 'bg-slate-900/50 border-white/10 text-slate-400 hover:bg-slate-900/80'">
                        <input type="radio" name="role" value="teacher" class="hidden" x-model="selectedRole">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold">Guru / Host</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Buat & kelola kuis</p>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/25 mt-2">
                    Lanjutkan ke Quizizz
                </button>
            </form>
        </div>
    </div>
</body>
</html>
