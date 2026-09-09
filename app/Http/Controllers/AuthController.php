<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'role' => 'required|in:warga,admin,rt,rw',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => strtolower(trim($validated['role'])),
        ]);

        Auth::login($user);

        return redirect()->route($this->dashboardRoute($user->role));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Hapus session/login akun sebelumnya
        Auth::logout();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pastikan role selalu konsisten
            $role = strtolower(trim((string) $user->role));

            return redirect()->route($this->dashboardRoute($role));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Menentukan dashboard berdasarkan role user.
     */
    private function dashboardRoute(string $role): string
    {
        return match (strtolower(trim($role))) {
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
