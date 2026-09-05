<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileAuthController extends Controller
{
    public function mobileLogin(Request $request)
    {
        if ($request->isMethod('GET')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan gunakan metode POST untuk login mobile.',
                'business_flow' => 'Warga login terlebih dahulu untuk mengakses pengajuan surat dan riwayat permohonan.',
                'example' => [
                    'email' => 'warga@gmail.com',
                    'password' => '12345678',
                    'device_name' => 'Android App',
                ],
                'hint' => 'Kirim request POST ke endpoint ini dari aplikasi mobile atau browser.',
            ], 405);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $validated['email'],
        ]);

        if (! $user || ! Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'business_flow' => 'Pastikan akun warga sudah terdaftar dan password yang dimasukkan benar.',
            ], 401);
        }

        // Izinkan login mobile untuk warga, rt, dan rw
        if (! in_array($user->role, ['warga', 'rt', 'rw'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak dapat digunakan untuk login mobile.',
                'business_flow' => 'Login mobile hanya tersedia untuk akun warga, RT, dan RW.',
            ], 403);
        }

        $token = $user->createToken($validated['device_name'] ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil. Silakan lanjutkan ke proses pengajuan surat.',
            'business_flow' => 'Setelah login, warga bisa mengajukan surat, melihat status, dan memantau riwayat pengajuan.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nik' => $user->nik,
                'alamat' => $user->alamat,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logout berhasil.'], 200);
    }
}

