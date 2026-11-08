<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SpkCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

class ValidasiPenelitianController extends Controller
{
    // Acuan: Kelompok 4_SIM.pdf
    // Tabel 6 dan Tabel 8, halaman 13–14.
    // Tidak digunakan untuk mengubah data MySQL.

    private const BOBOT_ARTIKEL = [
        0.400,
        0.250,
        0.150,
        0.120,
        0.080,
    ];

    private const DATA_ARTIKEL = [
        'A1' => [
            'penghasilan_orang_tua' => 1500000,
            'jumlah_tanggungan' => 3,
            'nilai_akademik' => 85,
            'kondisi_tempat_tinggal' => 3,
            'prestasi_non_akademik' => 2,
            'wsm' => 0.6773,
            'wpm' => 0.6639,
            'qi' => 0.6706,
            'peringkat' => 3,
        ],

        'A2' => [
            'penghasilan_orang_tua' => 2500000,
            'jumlah_tanggungan' => 2,
            'nilai_akademik' => 90,
            'kondisi_tempat_tinggal' => 2,
            'prestasi_non_akademik' => 4,
            'wsm' => 0.5307,
            'wpm' => 0.4966,
            'qi' => 0.5137,
            'peringkat' => 5,
        ],

        'A3' => [
            'penghasilan_orang_tua' => 1000000,
            'jumlah_tanggungan' => 5,
            'nilai_akademik' => 80,
            'kondisi_tempat_tinggal' => 4,
            'prestasi_non_akademik' => 1,
            'wsm' => 0.9164,
            'wpm' => 0.8609,
            'qi' => 0.8887,
            'peringkat' => 1,
        ],

        'A4' => [
            'penghasilan_orang_tua' => 3000000,
            'jumlah_tanggungan' => 4,
            'nilai_akademik' => 88,
            'kondisi_tempat_tinggal' => 2,
            'prestasi_non_akademik' => 3,
            'wsm' => 0.5848,
            'wpm' => 0.5348,
            'qi' => 0.5598,
            'peringkat' => 4,
        ],

        'A5' => [
            'penghasilan_orang_tua' => 1800000,
            'jumlah_tanggungan' => 3,
            'nilai_akademik' => 92,
            'kondisi_tempat_tinggal' => 3,
            'prestasi_non_akademik' => 5,
            'wsm' => 0.6922,
            'wpm' => 0.6721,
            'qi' => 0.6822,
            'peringkat' => 2,
        ],
    ];

    private const KOLOM = [
        'penghasilan_orang_tua'
            => 'C1 · Penghasilan orang tua',

        'jumlah_tanggungan'
            => 'C2 · Jumlah tanggungan',

        'nilai_akademik'
            => 'C3 · Nilai akademik',

        'kondisi_tempat_tinggal'
            => 'C4 · Kondisi tempat tinggal',

        'prestasi_non_akademik'
            => 'C5 · Prestasi non-akademik',
    ];

    public function index(
        Request $request,
        SpkCalculator $kalkulator
    ): View {

        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        // ====================================
        // 1. MENYIAPKAN DATA DARI ARTIKEL
        // ====================================

        $dataSimulasi = [];

        foreach (
            self::DATA_ARTIKEL as $kode => $nilai
        ) {

            $dataSimulasi[] = (object) array_merge(
                [
                    'id' => count($dataSimulasi) + 1,
                    'kode' => $kode,
                    'nama' => 'Alternatif '
                        . substr($kode, 1),
                ],

                array_intersect_key(
                    $nilai,
                    self::KOLOM
                )
            );
        }

        // ====================================
        // 2. HITUNG ULANG WASPAS ARTIKEL
        // ====================================

        $kesalahanArtikel = null;
        $hitungMenurutKode = [];

        try {

            $hasilArtikel = $kalkulator->waspas(
                $dataSimulasi,
                self::BOBOT_ARTIKEL
            );

            foreach (
                $hasilArtikel['peringkat'] as $hasil
            ) {

                $hitungMenurutKode[$hasil['kode']]
                    = $hasil;
            }

        } catch (InvalidArgumentException $e) {

            $kesalahanArtikel = $e->getMessage();
        }

        // ====================================
        // 3. BANDINGKAN DENGAN TABEL 8
        // ====================================

        $tabelArtikel = [];
        $jumlahSesuai = 0;

        foreach (
            self::DATA_ARTIKEL as $kode => $acuan
        ) {

            $hitung = $hitungMenurutKode[$kode]
                ?? null;

            $sesuai = $hitung !== null

                && round($hitung['s'], 4)
                    === $acuan['wsm']

                && round($hitung['p'], 4)
                    === $acuan['wpm']

                && round($hitung['q'], 4)
                    === $acuan['qi']

                && $hitung['peringkat']
                    === $acuan['peringkat'];

            if ($sesuai) {
                $jumlahSesuai++;
            }

            $tabelArtikel[] = compact(
                'kode',
                'acuan',
                'hitung',
                'sesuai'
            );
        }

        // ====================================
        // 4. AMBIL DATA A1–A5 DARI MYSQL
        // ====================================

        $jumlahDataMySql = DB::table(
            'calon_penerima'
        )->count();

        $kodeArtikel = array_keys(
            self::DATA_ARTIKEL
        );

        $dataDb = DB::table('calon_penerima')
            ->whereIn('kode', $kodeArtikel)
            ->select(
                'id',
                'kode',
                'nama',
                ...array_keys(self::KOLOM)
            )
            ->get()
            ->groupBy('kode');

        // ====================================
        // 5. PERIKSA NILAI DATABASE
        // ====================================

        $pemeriksaanDb = [];
        $jumlahDataDbSesuai = 0;
        $dataDbUntukHitung = [];

        foreach (
            self::DATA_ARTIKEL as $kode => $acuan
        ) {

            $dataDenganKode = $dataDb->get(
                $kode,
                collect()
            );

            $row = $dataDenganKode->count() === 1
                ? $dataDenganKode->first()
                : null;

            $beda = [];

            if ($dataDenganKode->count() > 1) {

                $beda[] =
                    'Kode alternatif muncul lebih dari satu kali di MySQL.';

            } elseif ($row === null) {

                $beda[] =
                    'Kode alternatif tidak ditemukan di MySQL.';

            } else {

                foreach (
                    self::KOLOM as $kolom => $label
                ) {

                    if (
                        !is_numeric($row->{$kolom})
                        ||
                        abs(
                            (float) $row->{$kolom}
                            -
                            (float) $acuan[$kolom]
                        ) > 0.000001
                    ) {

                        $beda[] = $label;
                    }
                }

                $dataDbUntukHitung[] = $row;
            }

            $sesuai = $beda === [];

            if ($sesuai) {
                $jumlahDataDbSesuai++;
            }

            $pemeriksaanDb[] = compact(
                'kode',
                'row',
                'beda',
                'sesuai',
                'acuan'
            );
        }

        // ====================================
        // 6. PERINGKAT KHUSUS MYSQL A1–A5
        // ====================================

        $peringkatDb = [];
        $kesalahanDb = null;

        if (count($dataDbUntukHitung) === 5) {

            try {

                $hasilDb = $kalkulator->waspas(
                    $dataDbUntukHitung,
                    self::BOBOT_ARTIKEL
                );

                foreach (
                    $hasilDb['peringkat'] as $hasil
                ) {

                    $peringkatDb[$hasil['kode']]
                        = $hasil;
                }

            } catch (InvalidArgumentException $e) {

                $kesalahanDb = $e->getMessage();
            }
        }

        // ====================================
        // 7. TAMPILKAN HASIL
        // ====================================

        return view(
            'admin.validasi-penelitian',
            [
                'tabelArtikel'
                    => $tabelArtikel,

                'pemeriksaanDb'
                    => $pemeriksaanDb,

                'peringkatDb'
                    => $peringkatDb,

                'jumlahSesuai'
                    => $jumlahSesuai,

                'jumlahDataDbSesuai'
                    => $jumlahDataDbSesuai,

                'jumlahDataMySql'
                    => $jumlahDataMySql,

                'kesalahanArtikel'
                    => $kesalahanArtikel,

                'kesalahanDb'
                    => $kesalahanDb,

                'bobotArtikel'
                    => self::BOBOT_ARTIKEL,
            ]
        );
    }
}
