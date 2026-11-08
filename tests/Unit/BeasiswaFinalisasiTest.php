<?php

namespace Tests\Unit;

use App\Services\AnalisisSensitivitas;
use App\Services\SpkCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BeasiswaFinalisasiTest extends TestCase
{
    private function dataArtikel(): array
    {
        // Tabel 6 artikel penelitian.
        $data = [
            ['A1', 1500000, 3, 85, 3, 2],
            ['A2', 2500000, 2, 90, 2, 4],
            ['A3', 1000000, 5, 80, 4, 1],
            ['A4', 3000000, 4, 88, 2, 3],
            ['A5', 1800000, 3, 92, 3, 5],
        ];

        return array_map(
            static fn (
                array $item,
                int $i
            ): object => (object) [
                'id' => $i + 1,
                'kode' => $item[0],
                'nama' => $item[0],

                'penghasilan_orang_tua' =>
                    $item[1],

                'jumlah_tanggungan' =>
                    $item[2],

                'nilai_akademik' =>
                    $item[3],

                'kondisi_tempat_tinggal' =>
                    $item[4],

                'prestasi_non_akademik' =>
                    $item[5],
            ],
            $data,
            array_keys($data)
        );
    }

    public function test_waspas_sesuai_tabel_delapan_artikel(): void
    {
        $hasil = (
            new SpkCalculator()
        )->waspas(
            $this->dataArtikel(),

            SpkCalculator::BOBOT_REFERENSI
        );

        self::assertSame(
            [
                'A3',
                'A5',
                'A1',
                'A4',
                'A2',
            ],

            array_column(
                $hasil['peringkat'],
                'kode'
            )
        );

        // Tabel 8:
        // WSM, WPM, Qi, peringkat.
        $acuan = [
            'A1' => [0.6773, 0.6639, 0.6706, 3],
            'A2' => [0.5307, 0.4966, 0.5137, 5],
            'A3' => [0.9164, 0.8609, 0.8887, 1],
            'A4' => [0.5848, 0.5348, 0.5598, 4],
            'A5' => [0.6922, 0.6721, 0.6822, 2],
        ];

        foreach (
            $hasil['peringkat'] as $item
        ) {

            [
                $s,
                $p,
                $qi,
                $peringkat
            ] = $acuan[
                $item['kode']
            ];

            self::assertEqualsWithDelta(
                $s,
                $item['s'],
                0.00005
            );

            self::assertEqualsWithDelta(
                $p,
                $item['p'],
                0.00005
            );

            self::assertEqualsWithDelta(
                $qi,
                $item['q'],
                0.00005
            );

            self::assertSame(
                $peringkat,
                $item['peringkat']
            );
        }
    }

    public function test_sensitivitas_artikel_stabil(): void
    {
        $analisis = new AnalisisSensitivitas(
            new SpkCalculator()
        );

        $hasil = $analisis->hitung(
            $this->dataArtikel(),

            SpkCalculator::BOBOT_REFERENSI
        );

        self::assertSame(
            5,
            $hasil['jumlah']
        );

        self::assertTrue(
            $hasil['stabil']
        );

        // Tabel 9 artikel:
        // Lambda 0, 0.5, 1, peringkat.
        $acuan = [
            'A1' => [0.6639, 0.6706, 0.6773, 3],
            'A2' => [0.4966, 0.5137, 0.5307, 5],
            'A3' => [0.8609, 0.8887, 0.9164, 1],
            'A4' => [0.5348, 0.5598, 0.5848, 4],
            'A5' => [0.6721, 0.6822, 0.6922, 2],
        ];

        foreach ($hasil['baris'] as $baris) {

            [
                $q0,
                $q05,
                $q1,
                $peringkat
            ] = $acuan[
                $baris['kode']
            ];

            $nilaiAcuan = [
                '0' => $q0,
                '05' => $q05,
                '1' => $q1,
            ];

            foreach (
                $nilaiAcuan as $kunci => $nilai
            ) {

                self::assertEqualsWithDelta(
                    $nilai,

                    $baris['nilai'][$kunci]['nilai'],

                    0.00005
                );

                self::assertSame(
                    $peringkat,

                    $baris['nilai'][$kunci]['peringkat']
                );
            }
        }
    }

    public function test_rekonstruksi_ahp_bukan_cr_artikel(): void
    {
        $bobot =
            SpkCalculator::BOBOT_REFERENSI;

        $pasangan = [];

        for ($i = 0; $i < 5; $i++) {

            for (
                $j = $i + 1;
                $j < 5;
                $j++
            ) {

                $pasangan["{$i}_{$j}"] =
                    $bobot[$i] / $bobot[$j];
            }
        }

        $hasil = (
            new SpkCalculator()
        )->ahp(
            $pasangan
        );

        self::assertTrue(
            $hasil['konsisten']
        );

        self::assertEqualsWithDelta(
            0.0,
            $hasil['cr'],
            1e-9
        );

        // CR rekonstruksi mendekati nol,
        // bukan CR 0,045 yang dilaporkan artikel.
        self::assertNotEqualsWithDelta(
            0.045,
            $hasil['cr'],
            0.001
        );
    }

    public function test_penghasilan_nol_ditolak(): void
    {
        $data = $this->dataArtikel();

        $data[0]->penghasilan_orang_tua = 0;

        $this->expectException(
            InvalidArgumentException::class
        );

        (
            new SpkCalculator()
        )->waspas(
            $data,
            SpkCalculator::BOBOT_REFERENSI
        );
    }

    public function test_data_kosong_ditangani(): void
    {
        $hasil = (
            new SpkCalculator()
        )->waspas(
            [],
            SpkCalculator::BOBOT_REFERENSI
        );

        self::assertSame(
            0,
            $hasil['jumlah']
        );

        self::assertSame(
            [],
            $hasil['peringkat']
        );
    }
}
