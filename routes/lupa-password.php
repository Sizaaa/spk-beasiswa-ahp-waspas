
<?php

use App\Http\Controllers\Auth\LupaPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LUPA KATA SANDI - BEASISWA CERDAS
|--------------------------------------------------------------------------
|
| Tahap 1: Meminta kode melalui Gmail
| Tahap 2: Memverifikasi kode enam digit
| Tahap 3: Menyimpan kata sandi baru
|
| Tidak mengubah route Admin atau User.
|
*/

Route::get(
    '/lupa-kata-sandi',
    [
        LupaPasswordController::class,
        'form',
    ]
)->name('password.forgot');

Route::post(
    '/lupa-kata-sandi/kirim-kode',
    [
        LupaPasswordController::class,
        'kirimKode',
    ]
)
    ->middleware('throttle:10,1')
    ->name('password.send');

Route::post(
    '/lupa-kata-sandi/verifikasi-kode',
    [
        LupaPasswordController::class,
        'verifikasiKode',
    ]
)
    ->middleware('throttle:10,1')
    ->name('password.verify');

Route::post(
    '/lupa-kata-sandi/reset',
    [
        LupaPasswordController::class,
        'resetPassword',
    ]
)
    ->middleware('throttle:15,1')
    ->name('password.reset');
