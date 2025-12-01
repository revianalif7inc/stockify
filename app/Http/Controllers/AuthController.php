<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login.
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Perbaikan path view: Mengarah ke resources/views/auth/login.blade.php
        return view('auth.login');
    }

    /**
     * Memproses permintaan login pengguna.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek apakah email terdaftar
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            // Email tidak ditemukan.
            $errorMessage = 'Email yang Anda masukkan belum terdaftar. Silakan <a href="' . route('register') . '" class="font-bold underline text-red-700 dark:text-red-500">daftar di sini</a>.';
            return back()->withErrors(['email' => $errorMessage])->onlyInput('email');
        }

        // 3. Coba autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect ke dashboard yang sesuai dengan role
            $role = Auth::user()->role;
            switch ($role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard'); 
                case 'manager':
                    return redirect()->intended('/manager/dashboard');
                case 'staff':
                    return redirect()->intended('/staff/dashboard');
                default:
                    return redirect()->intended('/dashboard'); // Fallback
            }
        }

        // 4. Jika autentikasi gagal (password salah)
        return back()->withErrors([
            'email' => 'Kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }


    /**
     * Menampilkan halaman formulir registrasi.
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Perbaikan path view: Mengarah ke resources/views/auth/register.blade.php
        return view('auth.register');
    }

    /**
     * Memproses permintaan register pengguna.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:admin,manager,staff', 
        ]);

        // Membuat user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'staff', 
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Akun Anda berhasil didaftarkan! Silakan login.');
    }

    /**
     * Memproses permintaan logout.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}       