<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Process login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Process registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:teacher,student',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di Quizizz, ' . $user->name . '!');
    }

    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth Callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Link google_id and avatar if not set
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($user);
                request()->session()->regenerate();

                return redirect()->route('dashboard')
                    ->with('success', 'Berhasil masuk dengan akun Google: ' . $user->name);
            } else {
                // Save pending Google user data in session and prompt role selection
                session([
                    'google_user' => [
                        'google_id' => $googleUser->getId(),
                        'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                        'email' => $googleUser->getEmail(),
                        'avatar' => $googleUser->getAvatar(),
                    ]
                ]);

                return redirect()->route('select.role');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal melakukan otentikasi Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Show role selection form for new Google users.
     */
    public function showSelectRole()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (!session()->has('google_user')) {
            return redirect()->route('login');
        }

        $googleUser = session('google_user');
        return view('auth.select-role', compact('googleUser'));
    }

    /**
     * Save selected role and complete Google user registration.
     */
    public function saveRole(Request $request)
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'role' => ['required', 'in:teacher,student'],
        ]);

        $googleData = session('google_user');

        $user = User::create([
            'name' => $googleData['name'],
            'email' => $googleData['email'],
            'google_id' => $googleData['google_id'],
            'avatar' => $googleData['avatar'],
            'role' => $request->role,
            'password' => Hash::make(\Illuminate\Support\Str::random(16)),
        ]);

        session()->forget('google_user');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun Google Anda berhasil didaftarkan sebagai ' . ($user->role === 'teacher' ? 'Guru' : 'Siswa') . '! Selamat datang di Quizizz, ' . $user->name . '!');
    }

    /**
     * Process logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
