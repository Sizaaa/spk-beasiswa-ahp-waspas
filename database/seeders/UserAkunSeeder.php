<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class UserAkunSeeder extends Seeder
{
    /**
     * Menyiapkan akun User yang sama
     * pada database lokal anggota kelompok.
     *
     * Tidak menghapus atau mengubah
     * data pengajuan beasiswa.
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
            '=== AKUN USER BEASISWA CERDAS ==='
        );

        $this->command->line(
            'Gunakan identitas akun User '
            . 'yang sudah dibuat sebelumnya.'
        );

        $nama = trim((string)
            $this->command->ask(
                'Nama lengkap User'
            )
        );

        $username = trim((string)
            $this->command->ask(
                'Username User'
            )
        );

        $email = mb_strtolower(trim((string)
            $this->command->ask(
                'Gmail User'
            )
        ));

        $password = (string)
            $this->command->secret(
                'Password User'
            );

        $konfirmasi = (string)
            $this->command->secret(
                'Ulangi password User'
            );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI IDENTITAS
        |--------------------------------------------------------------------------
        */

        if (
            mb_strlen($nama) < 3
            || mb_strlen($nama) > 100
        ) {
            throw new RuntimeException(
                'Nama lengkap harus 3–100 karakter.'
            );
        }

        if (
            !preg_match(
                '/\A[a-zA-Z0-9_]{3,50}\z/',
                $username
            )
        ) {
            throw new RuntimeException(
                'Username harus 3–50 karakter '
                . 'berupa huruf, angka, '
                . 'atau garis bawah.'
            );
        }

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
            || !str_ends_with(
                $email,
                '@gmail.com'
            )
        ) {
            throw new RuntimeException(
                'Gunakan alamat @gmail.com '
                . 'yang valid.'
            );
        }

        if (
            mb_strlen($password) < 10
            || mb_strlen($password) > 72
            || !preg_match('/[A-Z]/', $password)
            || !preg_match('/[a-z]/', $password)
            || !preg_match('/[0-9]/', $password)
        ) {
            throw new RuntimeException(
                'Password harus 10–72 karakter, '
                . 'mengandung huruf besar, '
                . 'huruf kecil, dan angka.'
            );
        }

        if (
            !hash_equals(
                $password,
                $konfirmasi
            )
        ) {
            throw new RuntimeException(
                'Konfirmasi password tidak cocok.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN AKUN YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $akun = DB::table('akun')
            ->where('username', $username)
            ->first();

        $akunEmail = DB::table('akun')
            ->where('email', $email)
            ->first();

        if ($akun !== null) {

            if ($akun->role !== 'user') {
                throw new RuntimeException(
                    'Username ini digunakan '
                    . 'oleh akun yang bukan User.'
                );
            }

            if (
                mb_strtolower(
                    (string) $akun->email
                ) !== $email
            ) {
                throw new RuntimeException(
                    'Username sudah digunakan '
                    . 'dengan Gmail berbeda.'
                );
            }
        }

        if (
            $akunEmail !== null
            && (
                $akun === null
                || (int) $akunEmail->id
                    !== (int) $akun->id
            )
        ) {
            throw new RuntimeException(
                'Gmail sudah digunakan '
                . 'oleh akun lain.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AKUN SUDAH ADA
        |--------------------------------------------------------------------------
        |
        | Jangan membuat akun ganda.
        | Jangan mengubah pengajuan yang
        | sudah terhubung ke akun tersebut.
        |
        */

        if ($akun !== null) {

            if (
                Hash::check(
                    $password,
                    (string) $akun->password_hash
                )
            ) {
                $this->command->info(
                    'Akun User sudah tersedia '
                    . 'dengan password yang sama.'
                );

                return;
            }

            $setuju = $this->command->confirm(
                'Akun sudah ada, tetapi password '
                . 'berbeda. Perbarui password '
                . 'akun lokal ini?',
                false
            );

            if (!$setuju) {
                $this->command->warn(
                    'Tidak ada data yang diubah.'
                );

                return;
            }

            DB::table('akun')
                ->where('id', (int) $akun->id)
                ->where('role', 'user')
                ->update([
                    'password_hash' =>
                        Hash::make($password),
                ]);

            $this->command->info(
                'Password akun User lokal '
                . 'berhasil diperbarui.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MEMBUAT AKUN USER BARU
        |--------------------------------------------------------------------------
        */

        DB::table('akun')->insert([
            'nama_lengkap' => $nama,
            'username' => $username,
            'email' => $email,
            'password_hash' =>
                Hash::make($password),
            'role' => 'user',
        ]);

        $this->command->info(
            'Akun User berhasil dibuat.'
        );

        $this->command->line(
            'Username: ' . $username
        );

        $this->command->line(
            'Role: user'
        );

        $this->command->info(
            'Silakan login menggunakan '
            . 'password yang sudah dimasukkan.'
        );
    }
}
