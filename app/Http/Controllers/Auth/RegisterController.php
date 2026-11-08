<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // =========================================
    // MENAMPILKAN HALAMAN REGISTRASI
    // =========================================

    public function create(): View
    {
        return view('auth.register');
    }

    // =========================================
    // MENYIMPAN AKUN USER BARU
    // =========================================

    public function store(Request $request): RedirectResponse
    {
        // Menghilangkan spasi di awal dan akhir
        // pada nama, username, dan email
        $rapikan = [];

        foreach (['nama_lengkap', 'username', 'email'] as $kolom) {

            $isi = $request->input($kolom);

            if (is_string($isi)) {
                $rapikan[$kolom] = trim($isi);
            }
        }

        // Email diseragamkan menjadi huruf kecil
        if (isset($rapikan['email'])) {
            $rapikan['email'] = mb_strtolower(
                $rapikan['email']
            );
        }

        $request->merge($rapikan);

        // =====================================
        // VALIDASI DATA PENDAFTARAN
        // =====================================

        $data = $request->validate(
            [
                'nama_lengkap' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                ],

                'username' => [
                    'required',
                    'string',
                    'regex:/\A[a-zA-Z0-9_]{3,50}\z/',
                    Rule::unique('akun', 'username'),
                ],

                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:254',
                    'ends_with:@gmail.com',
                    Rule::unique('akun', 'email'),
                ],

                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    Password::min(10)
                        ->max(72)
                        ->mixedCase()
                        ->numbers(),
                ],

                'password_confirmation' => [
                    'required',
                    'string',
                ],
            ],
            [
                'nama_lengkap.required' =>
                    'Nama lengkap wajib diisi.',

                'nama_lengkap.min' =>
                    'Nama lengkap minimal 3 karakter.',

                'username.required' =>
                    'Username wajib diisi.',

                'username.regex' =>
                    'Username harus 3–50 karakter, hanya huruf, angka, atau garis bawah (_).',

                'username.unique' =>
                    'Username sudah digunakan.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.ends_with' =>
                    'Gunakan alamat @gmail.com.',

                'email.unique' =>
                    'Email sudah terdaftar.',

                'password.required' =>
                    'Password wajib diisi.',

                'password.confirmed' =>
                    'Konfirmasi password belum sama.',

                'password_confirmation.required' =>
                    'Ulangi password untuk konfirmasi.',
            ]
        );

        // =====================================
        // MENYIMPAN KE DATABASE MYSQL
        // =====================================

        // Role ditentukan oleh server.
        // Pendaftar tidak bisa memilih role Admin.

        DB::table('akun')->insert([
            'nama_lengkap' => $data['nama_lengkap'],
            'username' => $data['username'],
            'email' => $data['email'],

            // Password asli diubah menjadi hash
            'password_hash' => Hash::make(
                $data['password']
            ),

            'role' => 'user',
        ]);

        // =====================================
        // REGISTRASI BERHASIL
        // =====================================

        return redirect()
            ->route('login')
            ->with(
                'sukses',
                'Registrasi berhasil. Silakan login dengan akun baru.'
            );
    }
}
