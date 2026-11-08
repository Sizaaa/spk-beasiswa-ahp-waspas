<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanPeran
{
    /**
     * Membatasi akses halaman berdasarkan role.
     *
     * Contoh:
     * - admin hanya bisa membuka halaman Admin
     * - user hanya bisa membuka halaman User
     */
    public function handle(
        Request $request,
        Closure $next,
        string $peran
    ): Response {

        // Pastikan pengguna sudah login
        // dan mempunyai role yang sesuai.
        if (
            !$request->user() ||
            $request->user()->role !== $peran
        ) {

            abort(
                403,
                'Anda tidak memiliki izin untuk mengakses halaman ini.'
            );
        }

        return $next($request);
    }
}
