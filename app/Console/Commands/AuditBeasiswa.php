<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AuditBeasiswa extends Command
{
    protected $signature = 'beasiswa:audit';

    protected $description =
        'Audit baca-saja tabel, alternatif, '
        . 'dan pengajuan Beasiswa Cerdas.';

    public function handle(): int
    {
        $this->info(
            'AUDIT BEASISWA CERDAS — '
            . 'tidak mengubah database.'
        );

        // Tabel dan kolom yang diperlukan.
        $perlu = [
            'akun' => [
                'id',
                'role',
            ],

            'pengajuan_beasiswa' => [
                'id',
                'akun_id',
                'status',
                'nama_lengkap',
                'penghasilan_orang_tua',
                'jumlah_tanggungan',
                'nilai_akademik',
                'kondisi_tempat_tinggal',
                'prestasi_non_akademik',
            ],

            'calon_penerima' => [
                'id',
                'kode',
                'nama',
                'penghasilan_orang_tua',
                'jumlah_tanggungan',
                'nilai_akademik',
                'kondisi_tempat_tinggal',
                'prestasi_non_akademik',
            ],
        ];

        try {

            // =================================
            // MEMERIKSA STRUKTUR TABEL
            // =================================

            foreach ($perlu as $tabel => $kolom) {

                if (!Schema::hasTable($tabel)) {

                    $this->error(
                        "Tabel {$tabel} tidak ditemukan."
                    );

                    return self::FAILURE;
                }

                foreach ($kolom as $namaKolom) {

                    if (
                        !Schema::hasColumn(
                            $tabel,
                            $namaKolom
                        )
                    ) {

                        $this->error(
                            "Kolom {$tabel}.{$namaKolom} "
                            . 'tidak ditemukan.'
                        );

                        return self::FAILURE;
                    }
                }

                $this->line(
                    "OK: struktur {$tabel}"
                );
            }

            // =================================
            // MEMERIKSA KODE ALTERNATIF
            // =================================

            $data = DB::table('calon_penerima')
                ->select('kode')
                ->get();

            $semuaKode = $data
                ->pluck('kode')
                ->map(
                    fn ($kode) =>
                        strtoupper((string) $kode)
                )
                ->all();

            $this->line(
                'Total alternatif: '
                . count($semuaKode)
            );

            $peringatan = 0;

            // Pastikan A1-A5 ada tepat sekali.
            foreach (
                ['A1', 'A2', 'A3', 'A4', 'A5']
                as $kode
            ) {

                $jumlah = count(
                    array_filter(
                        $semuaKode,
                        fn ($item) => $item === $kode
                    )
                );

                if ($jumlah !== 1) {

                    $this->warn(
                        "Data penelitian {$kode} "
                        . 'hilang atau duplikat.'
                    );

                    $peringatan++;
                }
            }

            // Deteksi kode duplikat.
            $duplikat = array_count_values(
                $semuaKode
            );

            foreach (
                $duplikat as $kode => $jumlah
            ) {

                if ($jumlah > 1) {

                    $this->warn(
                        "Kode {$kode} digunakan "
                        . "{$jumlah} kali."
                    );

                    $peringatan++;
                }
            }

            // =================================
            // RINGKASAN STATUS PENGAJUAN
            // =================================

            $pengajuan = DB::table(
                'pengajuan_beasiswa'
            )
                ->select('status')
                ->selectRaw(
                    'COUNT(*) AS jumlah'
                )
                ->groupBy('status')
                ->orderBy('status')
                ->get();

            foreach ($pengajuan as $item) {

                $this->line(
                    "Pengajuan {$item->status}: "
                    . $item->jumlah
                );
            }

            // =================================
            // MEMERIKSA HUBUNGAN PG-ID
            // =================================

            $kodePg = array_filter(
                $semuaKode,
                fn ($kode) =>
                    str_starts_with($kode, 'PG-')
            );

            $ids = [];

            foreach ($kodePg as $kode) {

                $id = substr($kode, 3);

                if (
                    !ctype_digit($id) ||
                    (int) $id < 1
                ) {

                    $this->warn(
                        "Kode pengajuan tidak valid: {$kode}"
                    );

                    $peringatan++;
                    continue;
                }

                $ids[$kode] = (int) $id;
            }

            if ($ids !== []) {

                $pengajuanPg = DB::table(
                    'pengajuan_beasiswa'
                )
                    ->whereIn(
                        'id',
                        array_values($ids)
                    )
                    ->get()
                    ->keyBy('id');

                foreach (
                    $ids as $kode => $id
                ) {

                    $asal = $pengajuanPg->get(
                        $id
                    );

                    if (
                        !$asal ||
                        $asal->status !== 'terverifikasi'
                    ) {

                        $this->warn(
                            "{$kode}: pengajuan asal "
                            . 'tidak ada atau tidak lagi '
                            . 'Terverifikasi.'
                        );

                        $peringatan++;
                    }
                }
            }

            $this->info(
                "Audit selesai. "
                . "Peringatan: {$peringatan}."
            );

            return $peringatan === 0
                ? self::SUCCESS
                : self::FAILURE;

        } catch (Throwable $e) {

            $this->error(
                'Audit tidak dapat berjalan: '
                . $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
