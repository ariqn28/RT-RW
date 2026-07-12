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
            'wifi_ssid' => ['nullable', 'string', 'max:255'],
            'wifi_password' => ['nullable', 'string', 'max:255'],
        ]);

        // Update profile
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nik' => $validated['nik'] ?? $user->nik,
            'alamat' => $validated['alamat'] ?? $user->alamat,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $wifiUpdates = [];
        if ($request->has('wifi_ssid')) {
            $wifiUpdates['WIFI_SSID'] = (string) $request->input('wifi_ssid');
        }

        if ($request->filled('wifi_password')) {
            $wifiUpdates['WIFI_PASSWORD'] = (string) $request->input('wifi_password');
        }

        if (!empty($wifiUpdates)) {
            $this->updateEnvironmentVariables($wifiUpdates);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        }

        return redirect()->route('profile.edit')
                         ->with('success', 'Profil berhasil diupdate. Pengaturan Wi‑Fi telah disimpan dan konfigurasi aplikasi telah disegarkan.');
    }

    private function updateEnvironmentVariables(array $updates): void
    {
        $path = app()->environmentFilePath();
        $contents = file_exists($path) ? file_get_contents($path) : '';

        foreach ($updates as $key => $value) {
            $escapedValue = $this->formatEnvValue((string) $value);
            $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

            if (preg_match($pattern, $contents)) {
                $contents = preg_replace($pattern, $key . '=' . $escapedValue, $contents);
            } else {
                if ($contents !== '' && substr($contents, -1) !== PHP_EOL) {
                    $contents .= PHP_EOL;
                }

                $contents .= $key . '=' . $escapedValue . PHP_EOL;
            }

            putenv($key . '=' . (string) $value);
            $_ENV[$key] = (string) $value;
        }

        file_put_contents($path, $contents);
    }

    private function formatEnvValue(string $value): string
    {
        $value = str_replace(["\r", "\n"], '', $value);

        if ($value === '') {
            return '';
        }

        if (str_contains($value, ' ') || str_contains($value, '#') || str_contains($value, '"') || str_contains($value, "'") || str_contains($value, '\\')) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
        }

        return $value;
    }
}

