<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as LaravelView;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // =====================================
        // MODAL KONFIRMASI GLOBAL
        // =====================================
        // Tetap digunakan di layout Admin
        // dan User seperti sebelumnya.

        View::composer(
            [
                'layouts.admin',
                'layouts.utama',
            ],
            static function (LaravelView $layout): void {

                $factory = $layout->getFactory();

                $factory->startPush('scripts');

                try {

                    echo view(
                        'partials.konfirmasi-global'
                    )->render();

                } finally {

                    $factory->stopPush();
                }
            }
        );

        // =====================================
        // NOTIFIKASI VERIFIKASI ADMIN
        // =====================================
        // Dihitung dari database setiap kali
        // layout Admin ditampilkan.
        //
        // Berlaku untuk Dashboard, Verifikasi,
        // AHP, WASPAS, Hasil Seleksi, Laporan,
        // dan halaman Admin lainnya.

        View::composer(
            'layouts.admin',
            static function (LaravelView $layout): void {

                if (auth()->user()?->role !== 'admin') {
                    return;
                }

                $jumlahMenunggu = DB::table(
                    'pengajuan_beasiswa as p'
                )
                    ->join(
                        'akun as a',
                        'a.id',
                        '=',
                        'p.akun_id'
                    )
                    ->where('a.role', 'user')
                    ->where('p.status', 'diajukan')
                    ->count();

                // Variabel ini sudah digunakan
                // oleh layout Admin yang lama.
                // Jadi HTML dan CSS sidebar
                // tidak perlu diubah.

                $layout->with(
                    'menunggu',
                    (int) $jumlahMenunggu
                );
            }
        );
    }
}
