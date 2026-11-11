<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class LupaPasswordController extends Controller
{
    private const MASA_BERLAKU_MENIT = 10;
    private const MAKSIMAL_KESALAHAN_KODE = 5;
    private const JEDA_KIRIM_DETIK = 60;

    /*
    |--------------------------------------------------------------------------
    | UTILITAS
    |--------------------------------------------------------------------------
    */

    private function emailSesi(Request $request): string
    {
        $email = $request->session()->get(
            'reset_password_email',
            ''
        );

        return is_string($email) ? $email : '';
    }

    private function kunciEmail(string $email): string
    {
        return 'reset-email:' . hash(
            'sha256',
            mb_strtolower($email)
        );
    }

    private function kunciIp(Request $request): string
    {
        return 'reset-ip:' . hash(
            'sha256',
            (string) $request->ip()
        );
    }

    private function kunciVerifikasi(
        Request $request,
        string $email
    ): string {
        return 'reset-verifikasi:' . hash(
            'sha256',
            mb_strtolower($email)
                . '|'
                . (string) $request->ip()
        );
    }

    private function buktiVerifikasi(
        Request $request
    ): ?array {
        $bukti = $request->session()->get(
            'reset_password_verified'
        );

        if (!is_array($bukti)) {
            return null;
        }

        if (
            !isset(
                $bukti['akun_id'],
                $bukti['kode_hash'],
                $bukti['expires_at']
            )
        ) {
            return null;
        }

        if (
            !is_numeric($bukti['akun_id'])
            || !is_string($bukti['kode_hash'])
            || !is_numeric($bukti['expires_at'])
            || (int) $bukti['expires_at'] <= now()->timestamp
        ) {
            return null;
        }

        return $bukti;
    }

    private function pesanKodeTidakValid(): string
    {
        return 'Kode salah, sudah kedaluwarsa, atau '
            . 'tidak berlaku lagi. Silakan meminta '
            . 'kode baru jika diperlukan.';
    }

    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HALAMAN
    |--------------------------------------------------------------------------
    |
    | Tahap 1: email
    | Tahap 2: kode
    | Tahap 3: password
    |
    | Tahap 3 hanya tersedia setelah verifikasi
    | kode berhasil di sisi server.
    |
    */

    public function form(Request $request): View
    {
        $email = $this->emailSesi($request);
        $tahap = 'email';

        if ($email !== '') {
            $tahap = 'kode';

            $bukti = $this->buktiVerifikasi($request);

            if ($bukti !== null) {
                $akun = DB::table('akun')
                    ->where('email', $email)
                    ->where('role', 'user')
                    ->first(['id']);

                if (
                    $akun !== null
                    && (int) $akun->id
                        === (int) $bukti['akun_id']
                ) {
                    $tahap = 'password';
                } else {
                    $request->session()->forget(
                        'reset_password_verified'
                    );
                }
            } else {
                $request->session()->forget(
                    'reset_password_verified'
                );
            }
        }

        return view('auth.lupa-password', [
            'emailTerkirim' => $email,
            'tahap' => $tahap,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TAHAP 1: KIRIM KODE KE GMAIL TERDAFTAR
    |--------------------------------------------------------------------------
    */

    public function kirimKode(
        Request $request
    ): RedirectResponse {
        $email = mb_strtolower(
            trim((string) $request->input('email', ''))
        );

        $request->merge([
            'email' => $email,
        ]);

        $data = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:254',
                    'ends_with:@gmail.com',
                ],
            ],
            [
                'email.required' =>
                    'Alamat Gmail wajib diisi.',
                'email.email' =>
                    'Format alamat Gmail tidak valid.',
                'email.ends_with' =>
                    'Gunakan Gmail yang terdaftar.',
            ]
        );

        $email = $data['email'];

        $kunciEmail = $this->kunciEmail($email);
        $kunciIp = $this->kunciIp($request);

        if (
            RateLimiter::tooManyAttempts(
                $kunciEmail,
                1
            )
        ) {
            return back()
                ->withErrors([
                    'email' =>
                        'Tunggu sekitar 1 menit sebelum '
                        . 'meminta kode baru.',
                ])
                ->withInput(['email' => $email]);
        }

        if (
            RateLimiter::tooManyAttempts(
                $kunciIp,
                5
            )
        ) {
            return back()->withErrors([
                'email' =>
                    'Terlalu banyak permintaan. '
                    . 'Silakan coba lagi nanti.',
            ]);
        }

        RateLimiter::hit(
            $kunciEmail,
            self::JEDA_KIRIM_DETIK
        );

        RateLimiter::hit(
            $kunciIp,
            3600
        );

        // Setiap pengiriman kode baru
        // membatalkan bukti verifikasi lama.
        $request->session()->forget(
            'reset_password_verified'
        );

        $request->session()->put(
            'reset_password_email',
            $email
        );

        // Hanya akun User biasa yang boleh
        // menggunakan pemulihan password publik.
        $akun = DB::table('akun')
            ->where('email', $email)
            ->where('role', 'user')
            ->first(['id', 'email']);

        if ($akun !== null) {
            $kode = (string) random_int(
                100000,
                999999
            );

            $hashKode = Hash::make($kode);
            $sekarang = now();

            DB::table('reset_kode_akun')
                ->updateOrInsert(
                    [
                        'akun_id' => (int) $akun->id,
                    ],
                    [
                        'kode_hash' => $hashKode,
                        'percobaan' => 0,
                        'kedaluwarsa_pada' =>
                            $sekarang->copy()
                                ->addMinutes(
                                    self::MASA_BERLAKU_MENIT
                                ),
                        'updated_at' => $sekarang,
                    ]
                );

            try {
                $isiPesan =
                    "Halo,\n\n"
                    . "Kami menerima permintaan "
                    . "pemulihan akun Beasiswa Cerdas.\n\n"
                    . "Kode verifikasi kamu:\n\n"
                    . $kode
                    . "\n\nKode ini berlaku "
                    . self::MASA_BERLAKU_MENIT
                    . " menit dan hanya bisa dipakai "
                    . "untuk satu proses reset password.\n\n"
                    . "Jangan berikan kode kepada "
                    . "siapa pun.\n\n"
                    . "Jika kamu tidak meminta "
                    . "pemulihan akun, abaikan "
                    . "email ini.\n\n"
                    . "Beasiswa Cerdas";

                Mail::raw(
                    $isiPesan,
                    static function (
                        Message $pesan
                    ) use ($email): void {
                        $pesan->to($email);

                        $pesan->subject(
                            'Kode Verifikasi Lupa Kata Sandi '
                            . '- Beasiswa Cerdas'
                        );
                    }
                );
            } catch (Throwable $exception) {
                DB::table('reset_kode_akun')
                    ->where(
                        'akun_id',
                        (int) $akun->id
                    )
                    ->where(
                        'kode_hash',
                        $hashKode
                    )
                    ->delete();

                // Jangan masukkan OTP
                // atau password ke log error.
                Log::warning(
                    'Gagal mengirim email reset password.',
                    [
                        'jenis_error' =>
                            get_class($exception),
                    ]
                );
            }
        }

        // Respons tidak membocorkan
        // apakah Gmail terdaftar atau tidak.
        return redirect()
            ->route('password.forgot')
            ->with(
                'sukses',
                'Jika Gmail terdaftar, kode verifikasi '
                . 'telah diproses. Periksa inbox email '
                . 'atau log pengujian sesuai '
                . 'konfigurasi aplikasi.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TAHAP 2: VERIFIKASI KODE TERLEBIH DAHULU
    |--------------------------------------------------------------------------
    |
    | Password baru belum diminta di tahap ini.
    | Kode diperiksa dari hash di MySQL.
    |
    */

    public function verifikasiKode(
        Request $request
    ): RedirectResponse {
        $email = $this->emailSesi($request);

        if ($email === '') {
            return redirect()
                ->route('password.forgot')
                ->withErrors([
                    'email' =>
                        'Masukkan Gmail dan minta kode '
                        . 'terlebih dahulu.',
                ]);
        }

        $data = $request->validate(
            [
                'kode' => [
                    'required',
                    'digits:6',
                ],
            ],
            [
                'kode.required' =>
                    'Kode verifikasi wajib diisi.',
                'kode.digits' =>
                    'Kode harus terdiri dari 6 digit.',
            ]
        );

        $kunciPercobaan = $this->kunciVerifikasi(
            $request,
            $email
        );

        if (
            RateLimiter::tooManyAttempts(
                $kunciPercobaan,
                10
            )
        ) {
            return back()->withErrors([
                'kode' =>
                    'Terlalu banyak percobaan. '
                    . 'Coba lagi nanti.',
            ]);
        }

        RateLimiter::hit(
            $kunciPercobaan,
            600
        );

        $akun = DB::table('akun')
            ->where('email', $email)
            ->where('role', 'user')
            ->first(['id']);

        if ($akun === null) {
            return back()->withErrors([
                'kode' => $this->pesanKodeTidakValid(),
            ]);
        }

        // Mengunci baris OTP agar percobaan
        // salah dihitung secara konsisten.
        $hasil = DB::transaction(
            function () use ($akun, $data): ?array {
                $baris = DB::table('reset_kode_akun')
                    ->where(
                        'akun_id',
                        (int) $akun->id
                    )
                    ->lockForUpdate()
                    ->first();

                if ($baris === null) {
                    return null;
                }

                $kedaluwarsa = Carbon::parse(
                    $baris->kedaluwarsa_pada
                );

                if (
                    now()->greaterThanOrEqualTo(
                        $kedaluwarsa
                    )
                ) {
                    DB::table('reset_kode_akun')
                        ->where('id', $baris->id)
                        ->delete();

                    return null;
                }

                if (
                    (int) $baris->percobaan >=
                    self::MAKSIMAL_KESALAHAN_KODE
                ) {
                    DB::table('reset_kode_akun')
                        ->where('id', $baris->id)
                        ->delete();

                    return null;
                }

                if (
                    !Hash::check(
                        $data['kode'],
                        $baris->kode_hash
                    )
                ) {
                    $jumlah =
                        (int) $baris->percobaan + 1;

                    if (
                        $jumlah >=
                        self::MAKSIMAL_KESALAHAN_KODE
                    ) {
                        DB::table('reset_kode_akun')
                            ->where('id', $baris->id)
                            ->delete();
                    } else {
                        DB::table('reset_kode_akun')
                            ->where('id', $baris->id)
                            ->update([
                                'percobaan' => $jumlah,
                                'updated_at' => now(),
                            ]);
                    }

                    return null;
                }

                // Bukti verifikasi terikat
                // ke akun dan kode OTP saat ini.
                return [
                    'akun_id' => (int) $akun->id,
                    'kode_hash' =>
                        (string) $baris->kode_hash,
                    'expires_at' =>
                        $kedaluwarsa->timestamp,
                ];
            }
        );

        if ($hasil === null) {
            return back()->withErrors([
                'kode' => $this->pesanKodeTidakValid(),
            ]);
        }

        // Regenerasi ID sesi setelah
        // kode terbukti benar.
        $request->session()->regenerate();

        $request->session()->put(
            'reset_password_verified',
            $hasil
        );

        RateLimiter::clear($kunciPercobaan);

        return redirect()
            ->route('password.forgot')
            ->with(
                'sukses',
                'Kode verifikasi berhasil. '
                . 'Sekarang buat kata sandi baru.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TAHAP 3: SIMPAN PASSWORD BARU
    |--------------------------------------------------------------------------
    |
    | Tidak menerima kode OTP dari form ini.
    | Menggunakan bukti verifikasi yang
    | tersimpan dalam sesi Laravel.
    |
    */

    public function resetPassword(
        Request $request
    ): RedirectResponse {
        $email = $this->emailSesi($request);
        $bukti = $this->buktiVerifikasi($request);

        if ($email === '' || $bukti === null) {
            $request->session()->forget(
                'reset_password_verified'
            );

            return redirect()
                ->route('password.forgot')
                ->withErrors([
                    'kode' =>
                        'Verifikasi kode terlebih dahulu. '
                        . 'Jika masa berlaku habis, '
                        . 'minta kode baru.',
                ]);
        }

        $data = $request->validate(
            [
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
                'password.required' =>
                    'Kata sandi baru wajib diisi.',
                'password.confirmed' =>
                    'Konfirmasi kata sandi tidak cocok.',
                'password_confirmation.required' =>
                    'Ulangi kata sandi baru.',
            ]
        );

        $akun = DB::table('akun')
            ->where('email', $email)
            ->where('role', 'user')
            ->first(['id']);

        if (
            $akun === null
            || (int) $akun->id !==
                (int) $bukti['akun_id']
        ) {
            $request->session()->forget(
                'reset_password_verified'
            );

            return redirect()
                ->route('password.forgot')
                ->withErrors([
                    'kode' =>
                        'Sesi verifikasi tidak valid. '
                        . 'Silakan meminta kode baru.',
                ]);
        }

        $hasil = DB::transaction(
            function () use (
                $akun,
                $bukti,
                $data
            ): bool {
                $baris = DB::table('reset_kode_akun')
                    ->where(
                        'akun_id',
                        (int) $akun->id
                    )
                    ->lockForUpdate()
                    ->first();

                if ($baris === null) {
                    return false;
                }

                // Bukti verifikasi harus
                // berasal dari OTP yang sama.
                if (
                    !hash_equals(
                        (string) $baris->kode_hash,
                        (string) $bukti['kode_hash']
                    )
                ) {
                    return false;
                }

                if (
                    now()->greaterThanOrEqualTo(
                        Carbon::parse(
                            $baris->kedaluwarsa_pada
                        )
                    )
                ) {
                    DB::table('reset_kode_akun')
                        ->where('id', $baris->id)
                        ->delete();

                    return false;
                }

                if (
                    (int) $baris->percobaan >=
                    self::MAKSIMAL_KESALAHAN_KODE
                ) {
                    return false;
                }

                $berubah = DB::table('akun')
                    ->where(
                        'id',
                        (int) $akun->id
                    )
                    ->where('role', 'user')
                    ->update([
                        'password_hash' =>
                            Hash::make(
                                $data['password']
                            ),
                    ]);

                if ($berubah !== 1) {
                    return false;
                }

                // OTP hanya berlaku sekali.
                DB::table('reset_kode_akun')
                    ->where('id', $baris->id)
                    ->delete();

                return true;
            }
        );

        if (!$hasil) {
            $request->session()->forget(
                'reset_password_verified'
            );

            return redirect()
                ->route('password.forgot')
                ->withErrors([
                    'kode' =>
                        'Verifikasi telah kedaluwarsa '
                        . 'atau kode sudah tidak berlaku. '
                        . 'Silakan meminta kode baru.',
                ]);
        }

        // Bersihkan proses reset.
        RateLimiter::clear(
            $this->kunciVerifikasi(
                $request,
                $email
            )
        );

        $request->session()->forget([
            'reset_password_email',
            'reset_password_verified',
        ]);

        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'sukses',
                'Kata sandi berhasil diperbarui. '
                . 'Silakan login menggunakan '
                . 'kata sandi baru.'
            );
    }
}
