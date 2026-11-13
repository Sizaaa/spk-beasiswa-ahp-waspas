<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CalonPenerimaController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\ValidasiPenelitianController;
use App\Http\Controllers\Admin\SeleksiPengajuanController;
use App\Http\Controllers\Admin\SensitivitasController;
use App\Http\Controllers\Admin\LingkupSeleksiController;
use App\Http\Controllers\Admin\RiwayatSeleksiController;
use App\Http\Controllers\Admin\PenetapanController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PengajuanController;
use App\Http\Controllers\User\HalamanController;

use App\Http\Middleware\PastikanPeran;
use Illuminate\Support\Facades\Route;

// ============================================
// HALAMAN PUBLIK DAN AUTENTIKASI
// ============================================

Route::view('/', 'beranda')
    ->name('beranda');

Route::get('/login', [
    LoginController::class,
    'create'
])->name('login');

Route::post('/login', [
    LoginController::class,
    'store'
])->name('login.proses');

Route::get('/register', [
    RegisterController::class,
    'create'
])->name('register');

Route::post('/register', [
    RegisterController::class,
    'store'
])->name('register.proses');

Route::post('/logout', [
    LoginController::class,
    'destroy'
])
    ->middleware('auth')
    ->name('logout');

// ============================================
// SELURUH FUNGSI ADMIN
// ============================================

Route::middleware([
    'auth',
    PastikanPeran::class . ':admin'
])->group(function (): void {

    // DASHBOARD ADMIN
    Route::get('/admin/dashboard', [
        AdminDashboardController::class,
        'index'
    ])->name('admin.dashboard');

    // VERIFIKASI
    Route::get('/admin/verifikasi', [
        VerifikasiController::class,
        'index'
    ])->name('admin.verifikasi.index');

    Route::get(
        '/admin/verifikasi/berkas/{id}/{jenis}',
        [
            VerifikasiController::class,
            'unduh'
        ]
    )
        ->whereNumber('id')
        ->name('admin.verifikasi.unduh');

    Route::post(
        '/admin/verifikasi/keputusan/{id}',
        [
            VerifikasiController::class,
            'keputusan'
        ]
    )
        ->whereNumber('id')
        ->name('admin.verifikasi.keputusan');

    // DATA CALON PENERIMA
    Route::get('/admin/calon-penerima', [
        CalonPenerimaController::class,
        'index'
    ])->name('admin.calon-penerima.index');

    Route::post('/admin/calon-penerima', [
        CalonPenerimaController::class,
        'store'
    ])->name('admin.calon-penerima.store');

    Route::put(
        '/admin/calon-penerima/{id}',
        [
            CalonPenerimaController::class,
            'update'
        ]
    )
        ->whereNumber('id')
        ->name('admin.calon-penerima.update');

    Route::delete(
        '/admin/calon-penerima/{id}',
        [
            CalonPenerimaController::class,
            'destroy'
        ]
    )
        ->whereNumber('id')
        ->name('admin.calon-penerima.destroy');

    // PENGAJUAN SIAP SELEKSI
    Route::get('/admin/seleksi-pengajuan', [
        SeleksiPengajuanController::class,
        'index'
    ])->name('admin.seleksi-pengajuan.index');

    Route::post(
        '/admin/seleksi-pengajuan/{id}/masukkan',
        [
            SeleksiPengajuanController::class,
            'simpan'
        ]
    )
        ->whereNumber('id')
        ->name('admin.seleksi-pengajuan.simpan');

    // PERHITUNGAN AHP
    Route::get('/admin/ahp', [
        PerhitunganController::class,
        'ahp'
    ])->name('admin.ahp.index');

    Route::post('/admin/ahp/hitung', [
        PerhitunganController::class,
        'hitungAhp'
    ])->name('admin.ahp.hitung');

    Route::post('/admin/ahp/reset', [
        PerhitunganController::class,
        'resetAhp'
    ])->name('admin.ahp.reset');

    Route::post('/admin/ahp/pilih-bobot', [
        PerhitunganController::class,
        'pilihBobot'
    ])->name('admin.ahp.pilih');

    // LINGKUP SELEKSI
    Route::get('/admin/lingkup-seleksi', [
        LingkupSeleksiController::class,
        'index'
    ])->name('admin.lingkup.index');

    Route::post('/admin/lingkup-seleksi', [
        LingkupSeleksiController::class,
        'simpan'
    ])->name('admin.lingkup.simpan');

    // PERHITUNGAN WASPAS
    Route::get('/admin/waspas', [
        PerhitunganController::class,
        'waspas'
    ])->name('admin.waspas.index');

    // HASIL SELEKSI
    Route::get('/admin/hasil-seleksi', [
        PerhitunganController::class,
        'hasil'
    ])->name('admin.hasil.index');

    // ============================================
    // FITUR BARU: PENETAPAN DAN PENGUMUMAN
    // Tetap menjadi bagian dari Hasil Seleksi.
    // ============================================

    Route::post(
        '/admin/hasil-seleksi/penetapan/draf',
        [
            PenetapanController::class,
            'draf'
        ]
    )->name('admin.hasil.penetapan.draf');

    Route::post(
        '/admin/hasil-seleksi/penetapan/umumkan',
        [
            PenetapanController::class,
            'umumkan'
        ]
    )->name('admin.hasil.penetapan.umumkan');

    // LAPORAN
    Route::get('/admin/laporan', [
        PerhitunganController::class,
        'laporan'
    ])->name('admin.laporan.index');

    // ANALISIS SENSITIVITAS
    Route::get('/admin/sensitivitas', [
        SensitivitasController::class,
        'index'
    ])->name('admin.sensitivitas.index');

    // VALIDASI PENELITIAN
    Route::get('/admin/validasi-penelitian', [
        ValidasiPenelitianController::class,
        'index'
    ])->name('admin.validasi-penelitian.index');

    // RIWAYAT PERHITUNGAN
    Route::get('/admin/riwayat-seleksi', [
        RiwayatSeleksiController::class,
        'index'
    ])->name('admin.riwayat.index');

    Route::post('/admin/riwayat-seleksi', [
        RiwayatSeleksiController::class,
        'simpan'
    ])->name('admin.riwayat.simpan');

    Route::get(
        '/admin/riwayat-seleksi/{id}',
        [
            RiwayatSeleksiController::class,
            'show'
        ]
    )
        ->whereNumber('id')
        ->name('admin.riwayat.show');
});

// ============================================
// SELURUH FUNGSI USER
// ============================================

Route::middleware([
    'auth',
    PastikanPeran::class . ':user'
])->group(function (): void {

    // DASHBOARD USER
    Route::get('/user/dashboard', [
        UserDashboardController::class,
        'index'
    ])->name('user.dashboard');

    // PENGAJUAN
    Route::get('/user/pengajuan', [
        PengajuanController::class,
        'index'
    ])->name('user.pengajuan');

    Route::post('/user/pengajuan', [
        PengajuanController::class,
        'simpan'
    ])->name('user.pengajuan.simpan');

    // DOWNLOAD BERKAS
    Route::get('/user/berkas/{jenis}', [
        UserDashboardController::class,
        'unduh'
    ])->name('user.berkas');

    // STATUS PENGAJUAN
    Route::get('/user/status', [
        HalamanController::class,
        'status'
    ])->name('user.status');

    // PROFIL
    Route::get('/user/profil', [
        HalamanController::class,
        'profil'
    ])->name('user.profil');

    // PANDUAN
    Route::get('/user/panduan', [
        HalamanController::class,
        'panduan'
    ])->name('user.panduan');
});
