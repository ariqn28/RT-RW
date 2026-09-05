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
            'role' => 'required|in:warga,rt,rw',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nik' => null,
            'alamat' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|in:warga,rt,rw,admin',
        ]);

        // ✅ FIX: Coba otentikasi dengan email + password
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ FIX: Validasi bahwa role form === role database
            if ($user->role !== $credentials['role']) {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'role' => 'Role tidak sesuai. User Anda adalah: ' . strtoupper($user->role),
                ])->onlyInput('email');
            }

            // Arahkan ke dashboard sesuai role aktual dari database
            return match ($user->role) {
                'rt'    => redirect()->route('dashboard.rt'),
                'rw'    => redirect()->route('dashboard.rw'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('warga.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
