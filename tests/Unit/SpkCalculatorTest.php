<?php

namespace Tests\Unit;

use App\Services\SpkCalculator;
use PHPUnit\Framework\TestCase;

class SpkCalculatorTest extends TestCase
{
    // =========================================
    // TEST AHP
    // =========================================

    public function test_rekonstruksi_konsisten_memulihkan_bobot_referensi(): void
    {
        $hitung = new SpkCalculator();

        $bobot = SpkCalculator::BOBOT_REFERENSI;

        $pasangan = [];

        for ($i = 0; $i < 5; $i++) {

            for ($j = $i + 1; $j < 5; $j++) {

                $pasangan["{$i}_{$j}"] =
                    $bobot[$i] / $bobot[$j];
            }
        }

        $hasil = $hitung->ahp($pasangan);

        self::assertTrue(
            $hasil['konsisten']
        );

        self::assertEqualsWithDelta(
            0.0,
            $hasil['cr'],
            1e-9
        );

        for ($i = 0; $i < 5; $i++) {

            self::assertEqualsWithDelta(
                $bobot[$i],
                $hasil['bobot'][$i],
                1e-9
            );
        }
    }

    // =========================================
    // TEST WASPAS
    // =========================================

    public function test_waspas_peringkat_dan_nilai_normalisasi(): void
    {
        $data = [
            (object) [
                'id' => 1,
                'kode' => 'A1',
                'nama' => 'A',
                'penghasilan_orang_tua' => 1000000,
                'jumlah_tanggungan' => 4,
                'nilai_akademik' => 80,
                'kondisi_tempat_tinggal' => 3,
                'prestasi_non_akademik' => 2,
            ],

            (object) [
                'id' => 2,
                'kode' => 'A2',
                'nama' => 'B',
                'penghasilan_orang_tua' => 2000000,
                'jumlah_tanggungan' => 2,
                'nilai_akademik' => 70,
                'kondisi_tempat_tinggal' => 2,
                'prestasi_non_akademik' => 1,
            ],
        ];

        $hasil = (new SpkCalculator())->waspas(
            $data,
            SpkCalculator::BOBOT_REFERENSI
        );

        // Alternatif A1 seharusnya lebih baik
        self::assertSame(
            'A1',
            $hasil['peringkat'][0]['kode']
        );

        // C1 A2 = 1.000.000 / 2.000.000 = 0,5
        self::assertEqualsWithDelta(
            0.5,
            $hasil['peringkat'][1]['normalisasi'][0],
            1e-12
        );

        // Semua nilai A1 adalah nilai terbaik
        self::assertEqualsWithDelta(
            1.0,
            $hasil['peringkat'][0]['q'],
            1e-12
        );
    }
}
