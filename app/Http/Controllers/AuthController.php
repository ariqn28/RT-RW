<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Untuk registrasi publik, peran harus selalu 'warga' untuk keamanan.
        // Pembuatan user RT/RW/Admin sebaiknya hanya melalui panel admin.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'warga',
        ]);


        Auth::login($user);

        // Warga langsung masuk ke dashboard warga setelah registrasi.
        return redirect()->route('warga.dashboard');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Coba otentikasi tanpa memeriksa peran terlebih dahulu
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route($this->dashboardRoute(Auth::user()->role));
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    private function dashboardRoute(string $role): string
    {
        return match ($role) {
            'warga' => 'warga.dashboard',
            'rt' => 'dashboard.rt',
            'rw' => 'dashboard.rw',
            'admin' => 'dashboard',
            default => 'login',
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
