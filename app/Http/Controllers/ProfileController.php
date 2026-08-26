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
            'wifi_ssid' => 'nullable|string|max:255',
            'wifi_password' => 'nullable|string|max:255',
        ]);

        // Simpan pengaturan Wi-Fi ke file .env (dipakai konfigurasi PWA/mobile)
        if ($request->filled('wifi_ssid') || $request->filled('wifi_password')) {
            $envPath = base_path('.env');
            $env = file_exists($envPath) ? file_get_contents($envPath) : '';

            $env = $this->setEnvValue($env, 'WIFI_SSID', $request->input('wifi_ssid'));
            $env = $this->setEnvValue($env, 'WIFI_PASSWORD', $request->input('wifi_password'));

            file_put_contents($envPath, $env);
        }

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

    /**
     * Set atau replace sebuah key di konten file .env.
     */
    private function setEnvValue(string $env, string $key, ?string $value): string
    {
        $line = $key . '=' . $value;

        // Key sudah ada -> replace
        if (preg_match('/^' . preg_quote($key, '/') . '=.*$/m', $env)) {
            return preg_replace('/^' . preg_quote($key, '/') . '=.*$/m', $line, $env);
        }

        // Key belum ada -> tambahkan di akhir file
        return rtrim($env, "\r\n") . "\r\n" . $line . "\r\n";
    }
}
