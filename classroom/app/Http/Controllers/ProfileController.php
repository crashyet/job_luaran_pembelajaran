<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'institution' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'avatar_preset' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'institution' => $request->institution,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ];

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $name);
            $data['avatar'] = '/uploads/avatars/' . $name;
        } elseif ($request->filled('avatar_preset')) {
            $data['avatar'] = $request->avatar_preset;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }
}
