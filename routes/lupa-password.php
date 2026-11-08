
<?php

use App\Http\Controllers\Auth\LupaPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PEMULIHAN KATA SANDI USER
|--------------------------------------------------------------------------
|
| Semua route menggunakan middleware web:
| - Session
| - Cookie
| - Proteksi CSRF
|
| Tidak mengubah route login, registrasi,
| Dashboard Admin, AHP, WASPAS, atau User.
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
    '/lupa-kata-sandi/reset',
    [
        LupaPasswordController::class,
        'resetPassword',
    ]
)
    ->middleware('throttle:15,1')
    ->name('password.reset');
