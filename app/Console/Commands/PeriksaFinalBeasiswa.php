<?php

namespace App\Console\Commands;

use App\Services\SpkCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Throwable;

class PeriksaFinalBeasiswa extends Command
{
    protected $signature = 'beasiswa:final-check';

    protected $description =
        'Audit final Beasiswa Cerdas tanpa mengubah database atau dokumen.';

    private int $lulus = 0;
    private int $peringatan = 0;
    private int $gagal = 0;

    // Tabel 6 dan Tabel 8 dari artikel Kelompok 4.
    private const ARTIKEL = [
        'A1' => [1500000, 3, 85, 3, 2, 0.6773, 0.6639, 0.6706, 3],
        'A2' => [2500000, 2, 90, 2, 4, 0.5307, 0.4966, 0.5137, 5],
        'A3' => [1000000, 5, 80, 4, 1, 0.9164, 0.8609, 0.8887, 1],
        'A4' => [3000000, 4, 88, 2, 3, 0.5848, 0.5348, 0.5598, 4],
        'A5' => [1800000, 3, 92, 3, 5, 0.6922, 0.6721, 0.6822, 2],
    ];

    private const KRITERIA_DB = [
        'penghasilan_orang_tua',
        'jumlah_tanggungan',
        'nilai_akademik',
        'kondisi_tempat_tinggal',
        'prestasi_non_akademik',
    ];

    public function handle(): int
    {
        $this->info(
            'BEASISWA CERDAS - PEMERIKSAAN FINAL'
        );

        $this->line(
            'Mode baca-saja: tidak mengubah database atau dokumen.'
        );

        $this->periksaFile();
        $this->periksaRuteDanView();
        $this->periksaPerhitunganArtikel();
        $this->periksaDatabase();
        $this->periksaKonfigurasi();

        $this->newLine();

        $this->table(
            ['Lolos', 'Peringatan', 'Masalah'],
            [[
                $this->lulus,
                $this->peringatan,
                $this->gagal,
            ]]
        );

        if (
            $this->gagal > 0 ||
            $this->peringatan > 0
        ) {
            $this->warn(
                'BELUM FINAL: periksa temuan di atas.'
            );

            return self::FAILURE;
        }

        $this->info(
            'AUDIT OTOMATIS LOLOS. Lanjutkan pengujian browser.'
        );

        return self::SUCCESS;
    }

    private function lulus(string $pesan): void
    {
        $this->lulus++;

        $this->line(
            '[OK] ' . $pesan
        );
    }

    private function peringatan(string $pesan): void
    {
        $this->peringatan++;

        $this->warn(
            '[PERIKSA] ' . $pesan
        );
    }

    private function gagal(string $pesan): void
    {
        $this->gagal++;

        $this->error(
            '[GAGAL] ' . $pesan
        );
    }

    // ============================================
    // 1. FILE INTI
    // ============================================

    private function periksaFile(): void
    {
        $this->newLine();

        $this->comment('1. Memeriksa file inti');

        $wajib = [
            'app/Http/Middleware/PastikanPeran.php',

            'app/Services/SpkCalculator.php',
            'app/Services/AnalisisSensitivitas.php',
            'app/Services/LingkupSeleksi.php',

            'app/Http/Controllers/Admin/PerhitunganController.php',
            'app/Http/Controllers/Admin/VerifikasiController.php',
            'app/Http/Controllers/Admin/SeleksiPengajuanController.php',
            'app/Http/Controllers/Admin/LingkupSeleksiController.php',
            'app/Http/Controllers/Admin/ValidasiPenelitianController.php',
            'app/Http/Controllers/Admin/SensitivitasController.php',
            'app/Http/Controllers/Admin/RiwayatSeleksiController.php',

            'app/Http/Controllers/User/PengajuanController.php',
            'app/Http/Controllers/User/HalamanController.php',

            'resources/views/layouts/admin.blade.php',
            'resources/views/layouts/utama.blade.php',

            'resources/views/admin/partials/lingkup-aktif.blade.php',

            'resources/views/admin/riwayat-seleksi.blade.php',
            'resources/views/admin/riwayat-detail.blade.php',
        ];

        $hilang = [];

        foreach ($wajib as $path) {

            if (!is_file(base_path($path))) {
                $hilang[] = $path;
            }
        }

        if ($hilang === []) {

            $this->lulus(
                'Seluruh ' .
                count($wajib) .
                ' file inti tersedia.'
            );

        } else {

            foreach ($hilang as $path) {

                $this->gagal(
                    'File tidak ditemukan: ' . $path
                );
            }
        }
    }

    // ============================================
    // 2. ROUTE DAN VIEW
    // ============================================

    private function periksaRuteDanView(): void
    {
        $this->newLine();

        $this->comment('2. Memeriksa route dan view');

        $rute = [
            'beranda',
            'login',
            'register',
            'logout',

            'user.dashboard',
            'user.pengajuan',
            'user.pengajuan.simpan',
            'user.status',
            'user.profil',
            'user.panduan',

            'admin.dashboard',
            'admin.verifikasi.index',
            'admin.calon-penerima.index',

            'admin.seleksi-pengajuan.index',
            'admin.seleksi-pengajuan.simpan',

            'admin.ahp.index',
            'admin.ahp.hitung',
            'admin.ahp.pilih',

            'admin.waspas.index',
            'admin.hasil.index',
            'admin.laporan.index',

            'admin.lingkup.index',
            'admin.lingkup.simpan',

            'admin.sensitivitas.index',
            'admin.validasi-penelitian.index',

            'admin.riwayat.index',
            'admin.riwayat.simpan',
            'admin.riwayat.show',
        ];

        $jumlahRute = 0;

        foreach ($rute as $nama) {

            if (Route::has($nama)) {

                $jumlahRute++;

            } else {

                $this->gagal(
                    'Route tidak ditemukan: ' . $nama
                );
            }
        }

        if ($jumlahRute === count($rute)) {

            $this->lulus(
                'Semua ' .
                count($rute) .
                ' route penting tersedia.'
            );
        }

        $daftarView = [
            'beranda',

            'admin.dashboard',
            'admin.verifikasi',
            'admin.calon-penerima',

            'admin.ahp',
            'admin.waspas',
            'admin.hasil',
            'admin.laporan',

            'admin.lingkup-seleksi',
            'admin.sensitivitas',
            'admin.validasi-penelitian',
            'admin.seleksi-pengajuan',

            'admin.riwayat-seleksi',
            'admin.riwayat-detail',
            'admin.partials.lingkup-aktif',

            'user.dashboard',
            'user.pengajuan',
        ];

        $jumlahView = 0;

        foreach ($daftarView as $nama) {

            if (View::exists($nama)) {

                $jumlahView++;

            } else {

                $this->gagal(
                    'View tidak ditemukan: ' . $nama
                );
            }
        }

        if ($jumlahView === count($daftarView)) {

            $this->lulus(
                'Semua ' .
                count($daftarView) .
                ' view penting tersedia.'
            );
        }
    }

    // ============================================
    // 3. VALIDASI ANGKA PENELITIAN
    // ============================================

    private function periksaPerhitunganArtikel(): void
    {
        $this->newLine();

        $this->comment(
            '3. Memeriksa hasil perhitungan artikel'
        );

        try {

            $masukan = [];

            foreach (
                self::ARTIKEL as $kode => $nilai
            ) {

                $masukan[] = (object) [
                    'id' =>
                        count($masukan) + 1,

                    'kode' => $kode,

                    'nama' => $kode,

                    'penghasilan_orang_tua' =>
                        $nilai[0],

                    'jumlah_tanggungan' =>
                        $nilai[1],

                    'nilai_akademik' =>
                        $nilai[2],

                    'kondisi_tempat_tinggal' =>
                        $nilai[3],

                    'prestasi_non_akademik' =>
                        $nilai[4],
                ];
            }

            $hasil = (
                new SpkCalculator()
            )->waspas(
                $masukan,
                SpkCalculator::BOBOT_REFERENSI
            );

            $tidakCocok = [];

            foreach (
                $hasil['peringkat'] as $item
            ) {

                $kode = $item['kode'];

                $acuan =
                    self::ARTIKEL[$kode] ?? null;

                if (
                    $acuan === null

                    ||
                    abs(
                        $item['s'] - $acuan[5]
                    ) > 0.00005

                    ||
                    abs(
                        $item['p'] - $acuan[6]
                    ) > 0.00005

                    ||
                    abs(
                        $item['q'] - $acuan[7]
                    ) > 0.00005

                    ||
                    $item['peringkat']
                        !== $acuan[8]
                ) {

                    $tidakCocok[] = $kode;
                }
            }

            if (
                $tidakCocok === [] &&
                count($hasil['peringkat']) === 5
            ) {

                $this->lulus(
                    'WSM, WPM, Qi, dan ranking A1-A5 sesuai artikel.'
                );

            } else {

                $this->gagal(
                    'Hasil WASPAS berbeda: ' .
                    implode(', ', $tidakCocok)
                );
            }

            // Menguji tiga nilai lambda.
            $sensitivitasCocok = true;

            foreach (
                $hasil['peringkat'] as $item
            ) {

                $acuan = self::ARTIKEL[
                    $item['kode']
                ];

                foreach (
                    [0.0, 0.5, 1.0] as $lambda
                ) {

                    $qi =
                        $lambda * $item['s']

                        +

                        (1 - $lambda)
                        * $item['p'];

                    $nilaiArtikel =
                        $lambda === 0.0
                            ? $acuan[6]
                            : (
                                $lambda === 0.5
                                    ? $acuan[7]
                                    : $acuan[5]
                            );

                    if (
                        abs(
                            $qi - $nilaiArtikel
                        ) > 0.00005
                    ) {

                        $sensitivitasCocok = false;
                    }
                }
            }

            if ($sensitivitasCocok) {

                $this->lulus(
                    'Sensitivitas lambda 0; 0,5; 1 sesuai artikel.'
                );

            } else {

                $this->gagal(
                    'Analisis sensitivitas berbeda dari artikel.'
                );
            }

        } catch (Throwable $e) {

            $this->gagal(
                'Perhitungan gagal: ' .
                $e->getMessage()
            );
        }
    }

    // ============================================
    // 4. INTEGRITAS DATABASE
    // ============================================

    private function periksaDatabase(): void
    {
        $this->newLine();

        $this->comment(
            '4. Memeriksa integritas database'
        );

        try {

            DB::connection()->getPdo();

        } catch (Throwable $e) {

            $this->gagal(
                'Koneksi MySQL gagal: ' .
                $e->getMessage()
            );

            return;
        }

        $this->lulus(
            'Koneksi database berhasil.'
        );

        $struktur = [
            'akun' => [
                'id',
                'role',
                'username',
            ],

            'pengajuan_beasiswa' => array_merge(
                [
                    'id',
                    'akun_id',
                    'nama_lengkap',
                    'status',
                ],
                self::KRITERIA_DB
            ),

            'calon_penerima' => array_merge(
                [
                    'id',
                    'kode',
                    'nama',
                ],
                self::KRITERIA_DB
            ),

            'riwayat_seleksi' => [
                'id',
                'admin_id',
                'lingkup',
                'mode_bobot',
                'snapshot_json',
                'checksum_sha256',
                'created_at',
            ],
        ];

        $lengkap = true;

        foreach (
            $struktur as $tabel => $kolom
        ) {

            if (!Schema::hasTable($tabel)) {

                $this->gagal(
                    'Tabel tidak ditemukan: ' .
                    $tabel
                );

                $lengkap = false;

                continue;
            }

            foreach ($kolom as $nama) {

                if (
                    !Schema::hasColumn(
                        $tabel,
                        $nama
                    )
                ) {

                    $this->gagal(
                        'Kolom tidak ditemukan: ' .
                        $tabel . '.' . $nama
                    );

                    $lengkap = false;
                }
            }
        }

        if (!$lengkap) {

            $this->peringatan(
                'Periksa struktur database dan migration riwayat.'
            );

            return;
        }

        $this->lulus(
            'Struktur empat tabel utama tersedia.'
        );

        try {

            $alternatif = DB::table(
                'calon_penerima'
            )->get();

            $pengajuan = DB::table(
                'pengajuan_beasiswa'
            )
                ->get()
                ->keyBy('id');

            $akunUser = DB::table('akun')
                ->where('role', 'user')
                ->pluck('id')
                ->map(
                    static fn ($id): int =>
                        (int) $id
                )
                ->all();

            $akunUser = array_fill_keys(
                $akunUser,
                true
            );

            // =================================
            // CEK KODE ALTERNATIF UNIK
            // =================================

            $ditemukan = [];
            $duplikat = [];

            foreach ($alternatif as $item) {

                $kode = strtoupper(
                    trim((string) $item->kode)
                );

                if (
                    $kode === '' ||
                    isset($ditemukan[$kode])
                ) {

                    $duplikat[] =
                        $kode === ''
                            ? '(kosong)'
                            : $kode;
                }

                $ditemukan[$kode] = $item;
            }

            if ($duplikat !== []) {

                $this->gagal(
                    'Kode alternatif duplikat: ' .
                    implode(
                        ', ',
                        array_unique($duplikat)
                    )
                );

            } else {

                $this->lulus(
                    'Tidak ditemukan kode alternatif duplikat.'
                );
            }

            // =================================
            // CEK A1-A5 TERHADAP ARTIKEL
            // =================================

            $bedaArtikel = [];

            foreach (
                self::ARTIKEL as $kode => $acuan
            ) {

                $row =
                    $ditemukan[$kode] ?? null;

                if ($row === null) {

                    $bedaArtikel[] =
                        $kode . ' tidak ada';

                    continue;
                }

                foreach (
                    self::KRITERIA_DB as $i => $kolom
                ) {

                    if (
                        !is_numeric(
                            $row->{$kolom}
                        )

                        ||

                        abs(
                            (float) $row->{$kolom}
                            - $acuan[$i]
                        ) > 0.000001
                    ) {

                        $bedaArtikel[] =
                            $kode . ' ' . $kolom;
                    }
                }
            }

            if ($bedaArtikel === []) {

                $this->lulus(
                    'Data MySQL A1-A5 cocok dengan Tabel 6.'
                );

            } else {

                $this->peringatan(
                    'Data A1-A5 berbeda: ' .
                    implode(
                        '; ',
                        $bedaArtikel
                    )
                );
            }

            // =================================
            // CEK RELASI ALTERNATIF PG-ID
            // =================================

            $jumlahPg = 0;
            $pgBermasalah = [];

            foreach ($alternatif as $calon) {

                $kode = strtoupper(
                    trim((string) $calon->kode)
                );

                if (
                    !str_starts_with(
                        $kode,
                        'PG-'
                    )
                ) {
                    continue;
                }

                $jumlahPg++;

                if (
                    !preg_match(
                        '/^PG-([1-9][0-9]*)$/D',
                        $kode,
                        $cocok
                    )
                ) {

                    $pgBermasalah[] =
                        $kode . ': format kode';

                    continue;
                }

                $asal = $pengajuan->get(
                    (int) $cocok[1]
                );

                if (
                    $asal === null

                    ||
                    $asal->status
                        !== 'terverifikasi'

                    ||
                    !isset(
                        $akunUser[
                            (int) $asal->akun_id
                        ]
                    )
                ) {

                    $pgBermasalah[] =
                        $kode .
                        ': pengajuan asal/status';

                    continue;
                }

                if (
                    trim(
                        (string) $asal->nama_lengkap
                    )

                    !==

                    trim(
                        (string) $calon->nama
                    )
                ) {

                    $pgBermasalah[] =
                        $kode . ': nama berbeda';

                    continue;
                }

                foreach (
                    self::KRITERIA_DB as $kolom
                ) {

                    if (
                        !is_numeric(
                            $asal->{$kolom}
                        )

                        ||
                        !is_numeric(
                            $calon->{$kolom}
                        )

                        ||
                        abs(
                            (float) $asal->{$kolom}
                            -
                            (float) $calon->{$kolom}
                        ) > 0.00001
                    ) {

                        $pgBermasalah[] =
                            $kode . ': ' .
                            $kolom . ' berbeda';

                        break;
                    }
                }
            }

            if ($pgBermasalah === []) {

                $this->lulus(
                    $jumlahPg .
                    ' alternatif PG-ID memiliki sumber valid.'
                );

            } else {

                $this->peringatan(
                    'PG-ID perlu diperiksa: ' .
                    implode(
                        '; ',
                        $pgBermasalah
                    )
                );
            }

            // =================================
            // CEK CHECKSUM ARSIP RIWAYAT
            // =================================

            $jumlahArsip = 0;
            $arsipRusak = [];

            $arsip = DB::table(
                'riwayat_seleksi'
            )
                ->select(
                    'id',
                    'snapshot_json',
                    'checksum_sha256'
                )
                ->cursor();

            foreach ($arsip as $item) {

                $jumlahArsip++;

                $json = (string)
                    $item->snapshot_json;

                $checksumCocok = hash_equals(
                    (string)
                    $item->checksum_sha256,

                    hash(
                        'sha256',
                        $json
                    )
                );

                $jsonValid = is_array(
                    json_decode(
                        $json,
                        true
                    )
                );

                if (
                    !$checksumCocok ||
                    !$jsonValid
                ) {

                    $arsipRusak[] =
                        '#' . $item->id;
                }
            }

            if ($arsipRusak === []) {

                $this->lulus(
                    $jumlahArsip .
                    ' arsip memiliki checksum valid.'
                );

            } else {

                $this->gagal(
                    'Arsip bermasalah: ' .
                    implode(
                        ', ',
                        $arsipRusak
                    )
                );
            }

        } catch (Throwable $e) {

            $this->gagal(
                'Pemeriksaan data gagal: ' .
                $e->getMessage()
            );
        }
    }

    // ============================================
    // 5. KONFIGURASI
    // ============================================

    private function periksaKonfigurasi(): void
    {
        $this->newLine();

        $this->comment(
            '5. Memeriksa konfigurasi keamanan'
        );

        if (
            app()->environment('production')
            &&
            config('app.debug')
        ) {

            $this->gagal(
                'APP_DEBUG tidak boleh true pada production.'
            );

        } else {

            $this->lulus(
                'Konfigurasi debug production tidak bermasalah.'
            );
        }
    }
}
