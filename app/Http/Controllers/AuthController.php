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

        $role = $request->input('role', 'warga');
        $validatedRole = in_array($role, ['warga', 'rt', 'rw'], true) ? $role : 'warga';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validatedRole,
        ]);


        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $selectedRole = $request->input('role');

        if (Auth::attempt(array_merge($credentials, ['role' => $selectedRole]))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function mobileLogin(Request $request)
    {
        if ($request->getMethod() === 'GET' || $request->isMethod('GET')) {
            return response()->json([
                'message' => 'Gunakan method POST untuk login mobile.',
                'example' => [
                    'email' => 'warga@gmail.com',
                    'password' => '12345678',
                    'device_name' => 'Android App',
                ],
                'hint' => 'Kirim request POST ke endpoint ini dari aplikasi mobile.',
            ], 405);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Izinkan login mobile untuk warga, rt, dan rw
        if (!in_array($user->role, ['warga', 'rt', 'rw'], true)) {
            return response()->json([
                'message' => 'Akun ini tidak dapat digunakan untuk login mobile.',
            ], 403);
        }


        $token = $user->createToken($validated['device_name'] ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nik' => $user->nik,
                'alamat' => $user->alamat,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
