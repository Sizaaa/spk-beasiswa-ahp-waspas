<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
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

    // =========================================
    // HALAMAN LUPA KATA SANDI
    // =========================================

    public function form(Request $request): View
    {
        return view('auth.lupa-password', [
            'emailTerkirim' => $request->session()
                ->get('reset_password_email'),
        ]);
    }

    // =========================================
    // MEMBUAT KUNCI PEMBATAS PERCOBAAN
    // =========================================

    private function kunciEmail(string $email): string
    {
        return 'reset-email:'
            . hash('sha256', mb_strtolower($email));
    }

    private function kunciIp(Request $request): string
    {
        return 'reset-ip:'
            . hash('sha256', (string) $request->ip());
    }

    private function kunciVerifikasi(
        Request $request,
        string $email
    ): string {
        return 'reset-verifikasi:'
            . hash(
                'sha256',
                mb_strtolower($email)
                . '|'
                . (string) $request->ip()
            );
    }

    // =========================================
    // KIRIM KODE VERIFIKASI KE EMAIL
    // =========================================

    public function kirimKode(
        Request $request
    ): RedirectResponse {

        // Normalisasi email.
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
                    'Gunakan alamat @gmail.com yang terdaftar.',
            ]
        );

        $email = $data['email'];

        $kunciEmail = $this->kunciEmail($email);
        $kunciIp = $this->kunciIp($request);

        // =====================================
        // PEMBATASAN PERMINTAAN
        // =====================================

        if (
            RateLimiter::tooManyAttempts(
                $kunciEmail,
                1
            )
        ) {

            return back()
                ->withErrors([
                    'email' =>
                        'Tunggu sekitar 1 menit sebelum meminta kode baru.',
                ])
                ->withInput([
                    'email' => $email,
                ]);
        }

        if (
            RateLimiter::tooManyAttempts(
                $kunciIp,
                5
            )
        ) {

            return back()
                ->withErrors([
                    'email' =>
                        'Terlalu banyak permintaan. Coba lagi nanti.',
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

        // =====================================
        // SIMPAN EMAIL PADA SESI
        // =====================================
        //
        // Email disimpan walaupun akun tidak
        // ditemukan supaya halaman tidak
        // mengungkap keberadaan suatu akun.
        // =====================================

        $request->session()->put(
            'reset_password_email',
            $email
        );

        // =====================================
        // HANYA AKUN USER BIASA
        // =====================================
        //
        // Akun Admin tidak boleh dipulihkan
        // melalui formulir publik ini.
        // =====================================

        $akun = DB::table('akun')
            ->where('email', $email)
            ->where('role', 'user')
            ->first([
                'id',
                'email',
            ]);

        if ($akun !== null) {

            // Kode 6 digit dihasilkan secara
            // acak menggunakan random_int().
            $kode = (string) random_int(
                100000,
                999999
            );

            // Hanya hash kode yang disimpan.
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

            // =================================
            // KIRIM EMAIL
            // =================================
            //
            // Dengan MAIL_MAILER=smtp:
            //   dikirim ke server SMTP.
            //
            // Dengan MAIL_MAILER=log:
            //   masuk ke log Laravel, bukan
            //   ke inbox Gmail sungguhan.
            //
            // Untuk testing email formalitas,
            // gunakan Mailpit.
            // =================================

            try {

                $isiPesan =
                    "Halo,\n\n"
                    . "Kami menerima permintaan untuk "
                    . "mengatur ulang kata sandi akun "
                    . "Beasiswa Cerdas.\n\n"
                    . "Kode verifikasi kamu:\n\n"
                    . $kode . "\n\n"
                    . "Kode berlaku selama "
                    . self::MASA_BERLAKU_MENIT
                    . " menit dan hanya dapat digunakan "
                    . "satu kali.\n\n"
                    . "Jika kamu tidak meminta "
                    . "pengaturan ulang kata sandi, "
                    . "abaikan email ini.\n\n"
                    . "Beasiswa Cerdas";

                Mail::raw(
                    $isiPesan,
                    static function (
                        Message $pesan
                    ) use ($email): void {

                        $pesan->to($email);

                        $pesan->subject(
                            'Kode Verifikasi Lupa Kata Sandi - Beasiswa Cerdas'
                        );
                    }
                );

            } catch (Throwable $exception) {

                // Jika pengiriman gagal,
                // batalkan kode yang baru dibuat.
                // Kondisi hash mencegah kode
                // yang lebih baru ikut terhapus.
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

                // Jangan menuliskan OTP ke log.
                Log::warning(
                    'Pengiriman email reset password gagal.',
                    [
                        'jenis_error' =>
                            get_class($exception),
                    ]
                );
            }
        }

        return redirect()
            ->route('password.forgot')
            ->with(
                'sukses',
                'Jika Gmail tersebut terdaftar, kode verifikasi telah dikirim. Periksa inbox atau inbox pengujian Mailpit.'
            );
    }

    // =========================================
    // VALIDASI KODE DAN RESET PASSWORD
    // =========================================

    public function resetPassword(
        Request $request
    ): RedirectResponse {

        $email = $request->session()
            ->get('reset_password_email');

        if (
            !is_string($email) ||
            $email === ''
        ) {

            return redirect()
                ->route('password.forgot')
                ->withErrors([
                    'email' =>
                        'Masukkan Gmail terlebih dahulu untuk meminta kode verifikasi.',
                ]);
        }

        $data = $request->validate(
            [
                'kode' => [
                    'required',
                    'digits:6',
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
                'kode.required' =>
                    'Kode verifikasi wajib diisi.',

                'kode.digits' =>
                    'Kode verifikasi harus 6 digit.',

                'password.required' =>
                    'Kata sandi baru wajib diisi.',

                'password.confirmed' =>
                    'Konfirmasi kata sandi tidak cocok.',

                'password_confirmation.required' =>
                    'Ulangi kata sandi baru.',
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

            return back()
                ->withErrors([
                    'kode' =>
                        'Terlalu banyak percobaan verifikasi. Coba lagi nanti.',
                ]);
        }

        RateLimiter::hit(
            $kunciPercobaan,
            600
        );

        // Cari hanya akun User sesuai Gmail
        // yang dimasukkan pada tahap pertama.
        $akun = DB::table('akun')
            ->where('email', $email)
            ->where('role', 'user')
            ->first(['id']);

        if ($akun === null) {

            return back()
                ->withErrors([
                    'kode' =>
                        'Kode salah atau sudah kedaluwarsa. Silakan meminta kode baru.',
                ]);
        }

        // =====================================
        // VERIFIKASI SECARA TRANSAKSI
        // =====================================
        //
        // lockForUpdate mencegah dua request
        // memakai OTP yang sama bersamaan.
        // =====================================

        $hasil = DB::transaction(
            function () use (
                $akun,
                $data
            ): string {

                $baris = DB::table(
                    'reset_kode_akun'
                )
                    ->where(
                        'akun_id',
                        (int) $akun->id
                    )
                    ->lockForUpdate()
                    ->first();

                if ($baris === null) {
                    return 'tidak_valid';
                }

                // Periksa masa berlaku.
                if (
                    now()->greaterThan(
                        \Illuminate\Support\Carbon::parse(
                            $baris->kedaluwarsa_pada
                        )
                    )
                ) {

                    DB::table('reset_kode_akun')
                        ->where('id', $baris->id)
                        ->delete();

                    return 'tidak_valid';
                }

                // Sudah terlalu banyak salah.
                if (
                    (int) $baris->percobaan >=
                    self::MAKSIMAL_KESALAHAN_KODE
                ) {

                    DB::table('reset_kode_akun')
                        ->where('id', $baris->id)
                        ->delete();

                    return 'tidak_valid';
                }

                // Kode tidak sesuai.
                if (
                    !Hash::check(
                        $data['kode'],
                        $baris->kode_hash
                    )
                ) {

                    $jumlahKesalahan =
                        (int) $baris->percobaan + 1;

                    if (
                        $jumlahKesalahan >=
                        self::MAKSIMAL_KESALAHAN_KODE
                    ) {

                        DB::table('reset_kode_akun')
                            ->where('id', $baris->id)
                            ->delete();

                    } else {

                        DB::table('reset_kode_akun')
                            ->where('id', $baris->id)
                            ->update([
                                'percobaan' =>
                                    $jumlahKesalahan,
                                'updated_at' => now(),
                            ]);
                    }

                    return 'tidak_valid';
                }

                // =================================
                // KODE COCOK
                // =================================
                //
                // Password hanya berubah setelah
                // kode email berhasil diverifikasi.
                //
                // Mengikuti kolom lama password_hash.
                // =================================

                $jumlahDiubah = DB::table('akun')
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

                if ($jumlahDiubah !== 1) {
                    return 'tidak_valid';
                }

                // Hapus OTP sesudah digunakan.
                DB::table('reset_kode_akun')
                    ->where('id', $baris->id)
                    ->delete();

                return 'berhasil';
            }
        );

        if ($hasil !== 'berhasil') {

            return back()
                ->withErrors([
                    'kode' =>
                        'Kode salah, sudah digunakan, atau sudah kedaluwarsa. Silakan meminta kode baru jika diperlukan.',
                ]);
        }

        // =====================================
        // RESET BERHASIL
        // =====================================

        RateLimiter::clear($kunciPercobaan);

        $request->session()->forget(
            'reset_password_email'
        );

        // Mengakhiri sesi saat ini supaya
        // masuk kembali dengan password baru.
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'sukses',
                'Kata sandi berhasil diperbarui. Silakan masuk menggunakan kata sandi baru.'
            );
    }
}
