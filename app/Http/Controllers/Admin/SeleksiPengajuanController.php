<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SeleksiPengajuanController extends Controller
{
    private function wajibAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );
    }

    private function kode(int $id): string
    {
        return 'PG-' . $id;
    }

    // Memeriksa apakah kode yang sudah ada
    // benar-benar memuat nilai pengajuan yang sama.
    private function dataCocok(
        object $pengajuan,
        object $calon
    ): bool {

        if (
            trim((string) $pengajuan->nama_lengkap)
            !== trim((string) $calon->nama)
        ) {
            return false;
        }

        $kolomKriteria = [
            'penghasilan_orang_tua',
            'jumlah_tanggungan',
            'nilai_akademik',
            'kondisi_tempat_tinggal',
            'prestasi_non_akademik',
        ];

        foreach ($kolomKriteria as $kolom) {

            if (
                !is_numeric($pengajuan->{$kolom}) ||
                !is_numeric($calon->{$kolom})
            ) {
                return false;
            }

            if (
                abs(
                    (float) $pengajuan->{$kolom}
                    - (float) $calon->{$kolom}
                ) > 0.00001
            ) {
                return false;
            }
        }

        return true;
    }

    private function angkaBulat(
        mixed $nilai,
        int $min,
        int $max,
        string $label
    ): int {

        if (
            !is_scalar($nilai) ||
            !preg_match(
                '/^\d+$/D',
                (string) $nilai
            )
        ) {
            throw ValidationException::withMessages([
                'seleksi' =>
                    "{$label} harus berupa bilangan bulat.",
            ]);
        }

        $angka = (int) $nilai;

        if ($angka < $min || $angka > $max) {

            throw ValidationException::withMessages([
                'seleksi' =>
                    "{$label} berada di luar rentang yang diizinkan.",
            ]);
        }

        return $angka;
    }

    // ==========================================
    // DAFTAR PENGAJUAN SIAP SELEKSI
    // ==========================================

    public function index(Request $request): View
    {
        $this->wajibAdmin($request);

        // Hanya pengajuan User
        // dengan status Terverifikasi.
        $daftar = DB::table('pengajuan_beasiswa as p')
            ->join(
                'akun as a',
                'a.id',
                '=',
                'p.akun_id'
            )
            ->where('a.role', 'user')
            ->where('p.status', 'terverifikasi')
            ->select(
                'p.id',
                'p.nama_lengkap',
                'p.nisn',
                'p.asal_sekolah',
                'p.penghasilan_orang_tua',
                'p.jumlah_tanggungan',
                'p.nilai_akademik',
                'p.kondisi_tempat_tinggal',
                'p.prestasi_non_akademik'
            )
            ->orderByDesc('p.id')
            ->paginate(10);

        // Mengambil kode alternatif
        // untuk halaman yang sedang ditampilkan.
        $kodeHalaman = $daftar->getCollection()
            ->map(
                fn (object $item): string =>
                    $this->kode((int) $item->id)
            )
            ->all();

        $calonMenurutKode = $kodeHalaman === []
            ? collect()
            : DB::table('calon_penerima')
                ->whereIn('kode', $kodeHalaman)
                ->get()
                ->keyBy('kode');

        foreach ($daftar as $item) {

            $item->kode_seleksi = $this->kode(
                (int) $item->id
            );

            $calon = $calonMenurutKode->get(
                $item->kode_seleksi
            );

            if ($calon === null) {

                $item->status_seleksi = 'belum';

            } elseif (
                $this->dataCocok($item, $calon)
            ) {

                $item->status_seleksi = 'sudah';

            } else {

                $item->status_seleksi = 'konflik';
            }
        }

        return view(
            'admin.seleksi-pengajuan',
            compact('daftar')
        );
    }

    // ==========================================
    // MASUKKAN KE DATA ALTERNATIF
    // ==========================================

    public function simpan(
        Request $request,
        int $id
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $ditambahkan = DB::transaction(
            function () use ($id): bool {

                // Kunci baris pengajuan supaya
                // dua permintaan bersamaan
                // tidak menghasilkan duplikasi.
                $pengajuan = DB::table(
                    'pengajuan_beasiswa'
                )
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->first();

                abort_unless(
                    $pengajuan !== null,
                    404
                );

                // Wajib terverifikasi dan
                // benar-benar milik akun User.
                if (
                    $pengajuan->status !== 'terverifikasi'
                    ||
                    !DB::table('akun')
                        ->where(
                            'id',
                            $pengajuan->akun_id
                        )
                        ->where('role', 'user')
                        ->exists()
                ) {

                    throw ValidationException::withMessages([
                        'seleksi' =>
                            'Hanya pengajuan User berstatus Terverifikasi yang boleh dimasukkan ke seleksi.',
                    ]);
                }

                // Kode stabil berdasarkan ID pengajuan
                $kode = $this->kode(
                    (int) $pengajuan->id
                );

                if (strlen($kode) > 20) {

                    throw ValidationException::withMessages([
                        'seleksi' =>
                            'Kode alternatif melebihi batas panjang tabel.',
                    ]);
                }

                // Cegah duplikasi
                $ada = DB::table('calon_penerima')
                    ->where('kode', $kode)
                    ->first();

                if ($ada !== null) {

                    if (
                        !$this->dataCocok(
                            $pengajuan,
                            $ada
                        )
                    ) {

                        throw ValidationException::withMessages([
                            'seleksi' =>
                                "Kode {$kode} sudah digunakan oleh data berbeda. Tidak ada data yang ditimpa.",
                        ]);
                    }

                    // Sudah pernah dimasukkan
                    // dan datanya sama.
                    return false;
                }

                // ==================================
                // VALIDASI DATA SUMBER
                // ==================================

                $nama = trim(
                    (string) $pengajuan->nama_lengkap
                );

                if (
                    $nama === '' ||
                    mb_strlen($nama) > 100
                ) {

                    throw ValidationException::withMessages([
                        'seleksi' =>
                            'Nama lengkap pengajuan tidak valid.',
                    ]);
                }

                $penghasilan = $this->angkaBulat(
                    $pengajuan->penghasilan_orang_tua,
                    1,
                    1000000000000,
                    'Penghasilan orang tua'
                );

                $tanggungan = $this->angkaBulat(
                    $pengajuan->jumlah_tanggungan,
                    0,
                    50,
                    'Jumlah tanggungan'
                );

                if (
                    !is_numeric(
                        $pengajuan->nilai_akademik
                    )
                ) {

                    throw ValidationException::withMessages([
                        'seleksi' =>
                            'Nilai akademik tidak valid.',
                    ]);
                }

                $akademik = (float)
                    $pengajuan->nilai_akademik;

                if (
                    !is_finite($akademik) ||
                    $akademik < 0 ||
                    $akademik > 100 ||
                    abs(
                        $akademik * 100
                        - round($akademik * 100)
                    ) > 0.000001
                ) {

                    throw ValidationException::withMessages([
                        'seleksi' =>
                            'Nilai akademik harus 0–100 dengan maksimal dua angka desimal.',
                    ]);
                }

                $kondisi = $this->angkaBulat(
                    $pengajuan->kondisi_tempat_tinggal,
                    1,
                    5,
                    'Kondisi tempat tinggal'
                );

                $prestasi = $this->angkaBulat(
                    $pengajuan->prestasi_non_akademik,
                    1,
                    5,
                    'Prestasi non-akademik'
                );

                // ==================================
                // INSERT KE CALON_PENERIMA
                // ==================================

                DB::table('calon_penerima')->insert([
                    'kode' => $kode,

                    'nama' => $nama,

                    'penghasilan_orang_tua' =>
                        $penghasilan,

                    'jumlah_tanggungan' =>
                        $tanggungan,

                    'nilai_akademik' =>
                        $akademik,

                    'kondisi_tempat_tinggal' =>
                        $kondisi,

                    'prestasi_non_akademik' =>
                        $prestasi,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return true;
            },
            3
        );

        return redirect()
            ->route(
                'admin.seleksi-pengajuan.index'
            )
            ->with(
                'sukses',

                $ditambahkan

                    ? "Pengajuan #{$id} berhasil masuk ke alternatif seleksi dengan kode PG-{$id}."

                    : "Pengajuan #{$id} sudah ada sebagai alternatif PG-{$id}; tidak dibuat duplikat."
            );
    }
}
