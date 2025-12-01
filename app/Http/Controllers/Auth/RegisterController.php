<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Tampilkan formulir registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Jika user sudah login, arahkan ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Tangani permintaan registrasi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Role di sini disembunyikan di form dan di-set default staff
            'role' => ['required', 'string', Rule::in(['admin', 'manager', 'staff'])],
        ], [
            'name.required' => 'Kolom Nama wajib diisi.',
            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kolom Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi Password tidak cocok.',
            'role.required' => 'Role wajib diset.',
        ]);

        // 2. Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Default role untuk registrasi melalui form ini adalah 'staff'
            'role' => $request->role, 
        ]);

        // 3. Langsung loginkan user
        Auth::login($user);

        // 4. Arahkan user ke dashboard
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di Stockify!');
    }
}