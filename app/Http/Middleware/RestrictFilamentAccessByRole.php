<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictFilamentAccessByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Asumsi menggunakan spatie/laravel-permission
            // Ganti 'siswa' dan 'guru' dengan nama peran yang sebenarnya Anda gunakan
            if ($user->hasRole('siswa') || $user->hasRole('guru')) {
                // Jika pengguna memiliki peran 'siswa' atau 'guru',
                // tolak akses ke panel admin.
                // Anda bisa mengembalikan response 403 (Forbidden)
                abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');

                // Atau, Anda bisa redirect ke halaman lain dengan pesan error
                // return redirect('/dashboard')->with('error', 'Anda tidak diizinkan mengakses area admin.');
            }
        }
        // Jika pengguna tidak memiliki peran 'siswa' atau 'guru', atau tidak login
        // (Filament akan menangani kasus tidak login dengan mengarahkan ke halaman loginnya sendiri),
        // lanjutkan request ke tujuan berikutnya.
        return $next($request);
    }
}