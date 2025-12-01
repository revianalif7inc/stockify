<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Periksa apakah peran pengguna sesuai dengan peran yang diizinkan (case-insensitive)
        if (strtolower($user->role) == strtolower($role)) {
            return $next($request);
        }

        // Jika peran tidak sesuai, arahkan ke halaman utama atau tampilkan error
        // Di sini kita arahkan ke rute default ('/dashboard')
        // Anda bisa mengubahnya menjadi response 403 atau rute lain
        return redirect('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk melihat halaman ini.');
    }
}