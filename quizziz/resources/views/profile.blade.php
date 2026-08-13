<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Quizizz Interactive</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ec4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <!-- Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .deep-bg {
            background: radial-gradient(circle at 50% 0%, #2e1065 0%, #0f172a 70%, #020617 100%);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="deep-bg text-slate-100 font-sans min-h-screen flex flex-col justify-between" x-data="{ selectedPreset: '{{ $user->avatar }}' }">

    <!-- Decorative Glows -->
    <div class="fixed top-10 left-10 w-96 h-96 rounded-full bg-pink-600/15 blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 right-10 w-96 h-96 rounded-full bg-purple-600/15 blur-3xl pointer-events-none"></div>

    <div>
        <!-- Navbar -->
        <nav class="sticky top-0 z-40 w-full glass-card border-b border-white/10 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors p-1.5 sm:p-2 rounded-xl hover:bg-white/5 flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali <span class="hidden xs:inline">ke Dashboard</span></span>
                </a>
            </div>

            <!-- Install PWA Button -->
            <button id="pwaInstallBtn" class="hidden items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg shadow-purple-500/20 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Install <span class="hidden sm:inline">Aplikasi</span></span>
            </button>
        </nav>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-3 sm:px-4 py-6 sm:py-8">

            <!-- Alert Notifikasi -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-4 sm:px-5 py-3.5 sm:py-4 rounded-2xl flex items-center gap-3 backdrop-blur-md">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-rose-500/20 border border-rose-500/30 text-rose-300 px-4 sm:px-5 py-3.5 sm:py-4 rounded-2xl backdrop-blur-md">
                    <div class="flex items-center gap-2 font-bold text-xs sm:text-sm mb-1 text-rose-200">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Terdapat kesalahan pengisian:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Profile Card -->
            <div class="glass-card rounded-3xl p-5 sm:p-6 md:p-8 shadow-2xl mb-6 sm:mb-8 relative overflow-hidden flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 sm:gap-6 border-pink-500/20">
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-2xl border-2 border-pink-500/40 shadow-2xl overflow-hidden bg-slate-900 flex items-center justify-center text-2xl sm:text-3xl font-black text-pink-400 flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                </div>

                <div class="flex-1 w-full">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-2">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight text-white">{{ $user->name }}</h2>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm mb-3">{{ $user->email }}</p>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-2 sm:gap-4 text-[11px] sm:text-xs text-slate-300 font-medium">
                        <span class="flex items-center gap-1.5 bg-white/5 px-2.5 sm:px-3 py-1.5 rounded-xl border border-white/10">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $user->institution ?? 'Belum Diisi' }}
                        </span>
                        <span class="flex items-center gap-1.5 bg-white/5 px-2.5 sm:px-3 py-1.5 rounded-xl border border-white/10">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $user->phone ?? 'Belum Diisi' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Forms Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <!-- Left: Form Informasi Profil -->
                <div class="lg:col-span-2 glass-card rounded-3xl p-5 sm:p-6 md:p-8 shadow-xl">
                    <h3 class="text-base sm:text-lg font-bold text-white mb-6 flex items-center gap-2 border-b border-white/10 pb-4">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Lengkapi Informasi Profil</span>
                    </h3>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
                        @csrf

                        <!-- Photo / Avatar Picker -->
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">Foto / Avatar Profil</label>
                            
                            <div class="grid grid-cols-5 gap-1.5 sm:gap-3 mb-4">
                                @php
                                    $presets = [
                                        'https://api.dicebear.com/7.x/bottts/svg?seed=Felix',
                                        'https://api.dicebear.com/7.x/bottts/svg?seed=Aneka',
                                        'https://api.dicebear.com/7.x/bottts/svg?seed=Budi',
                                        'https://api.dicebear.com/7.x/bottts/svg?seed=Siti',
                                        'https://api.dicebear.com/7.x/avataaars/svg?seed=Teacher',
                                    ];
                                @endphp
                                @foreach($presets as $idx => $preset)
                                    <label class="cursor-pointer flex justify-center">
                                        <input type="radio" name="avatar_preset" value="{{ $preset }}" class="peer hidden" @click="selectedPreset = '{{ $preset }}'">
                                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl border-2 border-white/10 peer-checked:border-pink-500 peer-checked:ring-4 peer-checked:ring-pink-500/20 p-1 transition-all hover:scale-105 bg-slate-900 flex items-center justify-center">
                                            <img src="{{ $preset }}" alt="Preset {{ $idx+1 }}" class="w-full h-full object-cover">
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <label for="avatar" class="block text-xs text-slate-400 font-medium mb-1">Upload Foto Sendiri (JPG, PNG, WebP max 2MB):</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-pink-500/20 file:text-pink-300 hover:file:bg-pink-500/30 cursor-pointer">
                            </div>
                        </div>

                        <!-- Nama Lengkap & Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3 text-sm text-white outline-none transition-all">
                            </div>
                            <div>
                                <label for="email" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Alamat Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3 text-sm text-white outline-none transition-all">
                            </div>
                        </div>

                        <!-- Instansi -->
                        <div>
                            <label for="institution" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Instansi / Sekolah / Kampus</label>
                            <input type="text" id="institution" name="institution" value="{{ old('institution', $user->institution) }}" placeholder="Contoh: Universitas Majalengka" class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3 text-sm text-white outline-none transition-all">
                        </div>

                        <!-- No HP / Telepon -->
                        <div>
                            <label for="phone" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nomor Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3 text-sm text-white outline-none transition-all">
                        </div>

                        <!-- Bio / Catatan -->
                        <div>
                            <label for="bio" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Bio / Catatan Singkat</label>
                            <textarea id="bio" name="bio" rows="3" placeholder="Tuliskan gambaran singkat tentang Anda..." class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-3 text-sm text-white outline-none transition-all">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-extrabold py-4 rounded-2xl text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg shadow-purple-500/25">
                            Simpan Perubahan Profil
                        </button>
                    </form>
                </div>

                <!-- Right: Keamanan & Install PWA Card -->
                <div class="space-y-6">
                    
                    <!-- Form Ganti Password -->
                    <div class="glass-card rounded-3xl p-6 shadow-xl">
                        <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2 border-b border-white/10 pb-3">
                            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Ubah Password</span>
                        </h3>

                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="current_password" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" required class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-2.5 text-sm text-white outline-none transition-all">
                            </div>

                            <div>
                                <label for="password" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Password Baru</label>
                                <input type="password" id="password" name="password" required placeholder="Min. 6 karakter" class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-2.5 text-sm text-white outline-none transition-all">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-slate-900/70 border border-white/10 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-2xl px-4 py-2.5 text-sm text-white outline-none transition-all">
                            </div>

                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition-all shadow-md active:scale-95 border border-white/10">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <!-- PWA Mobile Info Box -->
                    <div class="glass-card rounded-3xl p-6 border-pink-500/30 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-gradient-to-tr from-pink-500 to-purple-600 text-white p-2.5 rounded-2xl shadow-lg shadow-purple-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="font-black text-sm text-white">Aplikasi Mobile (PWA)</h4>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed mb-4">
                            Install Quizizz Interactive di Layar Utama HP Anda untuk akses cepat dan pengalaman aplikasi native!
                        </p>
                        <button id="pwaCardInstallBtn" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white text-xs font-extrabold uppercase tracking-widest py-3 rounded-2xl transition-all shadow-lg shadow-purple-500/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Install ke Layar HP</span>
                        </button>
                    </div>

                </div>

            </div>

        </main>
    </div>

    <!-- Institutional Logos Footer at Bottom -->
    @include('partials.institutional-logos')

    <!-- Service Worker & PWA Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered:', reg))
                    .catch(err => console.error('PWA SW failed:', err));
            });
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const topBtn = document.getElementById('pwaInstallBtn');
            const cardBtn = document.getElementById('pwaCardInstallBtn');
            if (topBtn) topBtn.classList.remove('hidden');
            if (cardBtn) cardBtn.style.display = 'flex';
        });

        const triggerPwaInstall = () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted PWA installation');
                    }
                    deferredPrompt = null;
                });
            } else {
                alert('Untuk meng-install aplikasi:\n\n- Android (Chrome): Ketuk titik tiga di pojok kanan atas > "Tambahkan ke Layar Utama" / "Install Aplikasi".\n- iOS (Safari): Ketuk tombol Share > "Tambahkan ke Layar Utama".');
            }
        };

        document.getElementById('pwaInstallBtn')?.addEventListener('click', triggerPwaInstall);
        document.getElementById('pwaCardInstallBtn')?.addEventListener('click', triggerPwaInstall);
    </script>
</body>
</html>
