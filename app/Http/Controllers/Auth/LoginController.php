<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan formulir login.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Jika user sudah login, arahkan ke dashboard
        if (Auth::check()) {
            // Jika sudah login, langsung arahkan ke dashboard berdasarkan role
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return view('auth.login');
    }

    /**
     * Tangani permintaan login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'password.required' => 'Kolom Password wajib diisi.',
        ]);

        // 2. Coba proses autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();

            // Ambil user yang baru login
            $user = Auth::user();

            // Jika akun dinonaktifkan, logout dan tolak login
            if (property_exists($user, 'is_active') && !$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi administrator.'])->onlyInput('email');
            }

            // 3. Arahkan user ke halaman berdasarkan role (LOGIKA UTAMA)
            $defaultDashboard = route('dashboard');

            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'manager':
                    return redirect()->intended('/manager/dashboard');
                case 'staff':
                    return redirect()->intended('/staff/dashboard');
                default:
                    // Default fallback jika role tidak dikenal
                    return redirect()->intended($defaultDashboard);
            }
        }

        // 4. Jika gagal, kembalikan user ke formulir login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tangani permintaan logout.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidasi session user
        $request->session()->invalidate();

        // Regenerasi token CSRF
        $request->session()->regenerateToken();

        // Arahkan ke halaman login
        return redirect('/login');
    }
}