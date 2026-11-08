<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class LoginController extends Controller
{
    // =========================================
    // MENAMPILKAN HALAMAN LOGIN
    // =========================================

    public function create(): View
    {
        return view('auth.login');
    }

    // =========================================
    // MEMPROSES LOGIN
    // =========================================

    public function store(Request $request): RedirectResponse
    {
        // Memeriksa isian formulir
        $data = $request->validate(
            [
                'login' => [
                    'required',
                    'string',
                    'max:254',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'login.required' =>
                    'Username atau email wajib diisi.',

                'password.required' =>
                    'Password wajib diisi.',
            ]
        );

        $identitas = trim($data['login']);

        // Membatasi percobaan login yang gagal
        $kunci = mb_strtolower($identitas)
            . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            return back()
                ->withErrors([
                    'login' => 'Terlalu banyak percobaan. Coba lagi sebentar.',
                ])
                ->onlyInput('login');
        }

        // Jika memakai @, periksa kolom email.
        // Jika tidak, periksa kolom username.
        $kolom = str_contains($identitas, '@')
            ? 'email'
            : 'username';

        // Memeriksa akun dan password
        $berhasil = Auth::attempt([
            $kolom => $identitas,
            'password' => $data['password'],
        ]);

        if (!$berhasil) {

            RateLimiter::hit($kunci, 60);

            return back()
                ->withErrors([
                    'login' => 'Username/email atau password tidak sesuai.',
                ])
                ->onlyInput('login');
        }

        // Login berhasil
        RateLimiter::clear($kunci);

        // Membuat ulang sesi agar aman
        $request->session()->regenerate();

        // Memeriksa role akun
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'user'], true)) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Role akun tidak dikenali.',
                ]);
        }

        // Mengarahkan sesuai role
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    // =========================================
    // LOGOUT
    // =========================================

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Mengakhiri sesi login
        $request->session()->invalidate();

        // Memperbarui token keamanan
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'sukses',
                'Kamu berhasil logout.'
            );
    }
}
