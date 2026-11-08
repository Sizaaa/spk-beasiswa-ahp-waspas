<?php

namespace Tests\Unit;

use App\Services\AnalisisSensitivitas;
use App\Services\SpkCalculator;
use PHPUnit\Framework\TestCase;

class AnalisisSensitivitasTest extends TestCase
{
    public function test_tabel_sembilan_artikel_tetap_stabil(): void
    {
        // Data dari Tabel 6 artikel.
        $data = [
            ['A1', 1500000, 3, 85, 3, 2],
            ['A2', 2500000, 2, 90, 2, 4],
            ['A3', 1000000, 5, 80, 4, 1],
            ['A4', 3000000, 4, 88, 2, 3],
            ['A5', 1800000, 3, 92, 3, 5],
        ];

        $alternatif = [];

        foreach (
            $data as $i =>
            [$kode, $c1, $c2, $c3, $c4, $c5]
        ) {

            $alternatif[] = (object) [
                'id' => $i + 1,
                'kode' => $kode,
                'nama' => $kode,
                'penghasilan_orang_tua' => $c1,
                'jumlah_tanggungan' => $c2,
                'nilai_akademik' => $c3,
                'kondisi_tempat_tinggal' => $c4,
                'prestasi_non_akademik' => $c5,
            ];
        }

        $hasil = (
            new AnalisisSensitivitas(
                new SpkCalculator()
            )
        )->hitung(
            $alternatif,
            SpkCalculator::BOBOT_REFERENSI
        );

        self::assertSame(
            5,
            $hasil['jumlah']
        );

        self::assertTrue(
            $hasil['stabil']
        );

        // Tabel 9 artikel.
        // WPM, campuran, WSM, peringkat.
        $acuan = [
            'A1' => [
                0.6639, 0.6706, 0.6773, 3
            ],

            'A2' => [
                0.4966, 0.5137, 0.5307, 5
            ],

            'A3' => [
                0.8609, 0.8887, 0.9164, 1
            ],

            'A4' => [
                0.5348, 0.5598, 0.5848, 4
            ],

            'A5' => [
                0.6721, 0.6822, 0.6922, 2
            ],
        ];

        foreach ($hasil['baris'] as $baris) {

            [
                $wpm,
                $campuran,
                $wsm,
                $peringkat
            ] = $acuan[$baris['kode']];

            self::assertEqualsWithDelta(
                $wpm,
                $baris['nilai']['0']['nilai'],
                0.00005
            );

            self::assertEqualsWithDelta(
                $campuran,
                $baris['nilai']['05']['nilai'],
                0.00005
            );

            self::assertEqualsWithDelta(
                $wsm,
                $baris['nilai']['1']['nilai'],
                0.00005
            );

            foreach (
                ['0', '05', '1'] as $kunci
            ) {

                self::assertSame(
                    $peringkat,
                    $baris['nilai'][$kunci]['peringkat']
                );
            }
        }
    }
}
