<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileAuthController extends Controller
{
    public function mobileLogin(Request $request)
    {
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
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Izinkan login mobile untuk warga, rt, dan rw
        if (! in_array($user->role, ['warga', 'rt', 'rw'], true)) {
            return response()->json(['message' => 'Akun ini tidak dapat digunakan untuk login mobile.'], 403);
        }

        $token = $user->createToken($validated['device_name'] ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
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

