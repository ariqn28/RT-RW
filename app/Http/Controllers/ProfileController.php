<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update profile
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nik' => $validated['nik'] ?? $user->nik,
            'alamat' => $validated['alamat'] ?? $user->alamat,
            // Saran: Simpan pengaturan Wi-Fi di tabel user jika spesifik per user
            // 'wifi_ssid' => $request->input('wifi_ssid'),
            // 'wifi_password' => $request->filled('wifi_password') ? encrypt($request->input('wifi_password')) : $user->wifi_password,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
 
        $user->save();

        return redirect()->route('profile.edit')
                         ->with('success', 'Profil berhasil diupdate.');
    }
}
