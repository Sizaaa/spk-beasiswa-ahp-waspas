<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class AdminAkunSeeder extends Seeder
{
    /**
     * Membuat akun Admin yang sama pada
     * database lokal anggota kelompok.
     *
     * Kredensial dimasukkan melalui terminal,
     * bukan disimpan dalam source code.
     */
    public function run(): void
    {
        if (!Schema::hasTable('akun')) {
            throw new RuntimeException(
                'Tabel akun belum tersedia. '
                . 'Siapkan struktur database '
                . 'Beasiswa Cerdas terlebih dahulu.'
            );
        }

        $this->command->info(
            '=== Pengaturan Admin Beasiswa Cerdas ==='
        );

        $this->command->line(
            'Masukkan identitas akun Admin '
            . 'yang sudah digunakan oleh ketua kelompok.'
        );

        $username = trim((string)
            $this->command->ask('Username Admin')
        );

        $email = mb_strtolower(trim((string)
            $this->command->ask('Gmail Admin')
        ));

        $nama = trim((string)
            $this->command->ask('Nama lengkap Admin')
        );

        // Password tidak ditampilkan saat diketik.
        $password = (string)
            $this->command->secret('Password Admin');

        $konfirmasi = (string)
            $this->command->secret('Ulangi password Admin');

        if (
            $username === ''
            || $email === ''
            || $nama === ''
            || $password === ''
        ) {
            throw new RuntimeException(
                'Semua identitas dan password '
                . 'Admin wajib diisi.'
            );
        }

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            throw new RuntimeException(
                'Format email Admin tidak valid.'
            );
        }

        if (!hash_equals($password, $konfirmasi)) {
            throw new RuntimeException(
                'Konfirmasi password tidak sama.'
            );
        }

        DB::transaction(function () use (
            $username,
            $email,
            $nama,
            $password
        ): void {

            $akunUsername = DB::table('akun')
                ->where('username', $username)
                ->first([
                    'id',
                    'role',
                    'email',
                ]);

            $akunEmail = DB::table('akun')
                ->where('email', $email)
                ->first([
                    'id',
                    'username',
                ]);

            // Jangan mengambil alih akun User
            // yang kebetulan memiliki username sama.
            if (
                $akunUsername !== null
                && $akunUsername->role !== 'admin'
            ) {
                throw new RuntimeException(
                    'Username sudah digunakan '
                    . 'oleh akun non-Admin.'
                );
            }

            // Jangan memakai email milik akun lain.
            if (
                $akunEmail !== null
                && (
                    $akunUsername === null
                    || (int) $akunEmail->id
                        !== (int) $akunUsername->id
                )
            ) {
                throw new RuntimeException(
                    'Email sudah digunakan '
                    . 'oleh akun lain.'
                );
            }

            $dataAdmin = [
                'nama_lengkap' => $nama,
                'username' => $username,
                'email' => $email,
                'password_hash' =>
                    Hash::make($password),
                'role' => 'admin',
            ];

            if ($akunUsername !== null) {

                // Jika Admin sudah ada pada laptop
                // tersebut, samakan kredensialnya.
                DB::table('akun')
                    ->where(
                        'id',
                        (int) $akunUsername->id
                    )
                    ->update($dataAdmin);

            } else {

                // Jika belum ada, buat akun Admin.
                DB::table('akun')->insert(
                    $dataAdmin
                );
            }
        });

        $this->command->info(
            'Akun Admin berhasil disiapkan.'
        );

        $this->command->info(
            'Username: ' . $username
        );

        $this->command->info(
            'Silakan login melalui halaman '
            . 'Login Beasiswa Cerdas.'
        );
    }
}
