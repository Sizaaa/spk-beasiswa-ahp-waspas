<?php

namespace App\Services;

use InvalidArgumentException;

class AnalisisSensitivitas
{
    public function __construct(
        private readonly SpkCalculator $kalkulator
    ) {}

    public function hitung(
        array $alternatif,
        array $bobot
    ): array {

        // Menggunakan mesin WASPAS yang sama.
        $hasil = $this->kalkulator->waspas(
            $alternatif,
            $bobot
        );

        $dasar = $hasil['peringkat'];

        if ($dasar === []) {
            return [
                'jumlah' => 0,
                'baris' => [],
                'stabil' => null,
            ];
        }

        // Pastikan kode alternatif unik.
        $kodeDitemukan = [];

        foreach ($dasar as $item) {

            $kode = (string) $item['kode'];

            if (
                $kode === '' ||
                isset($kodeDitemukan[$kode])
            ) {
                throw new InvalidArgumentException(
                    'Kode alternatif kosong atau duplikat. '
                    . 'Periksa Data Calon Penerima.'
                );
            }

            $kodeDitemukan[$kode] = true;
        }

        // Tiga variasi sesuai Tabel 9 artikel.
        $variasi = [
            '0' => 0.0,
            '05' => 0.5,
            '1' => 1.0,
        ];

        $tabel = [];

        foreach ($variasi as $kunci => $lambda) {

            $nilai = [];

            foreach ($dasar as $item) {

                $item['qi'] =
                    $lambda * $item['s']
                    + (1.0 - $lambda) * $item['p'];

                $nilai[] = $item;
            }

            // Urutkan Qi tertinggi terlebih dahulu.
            usort(
                $nilai,
                static function (
                    array $a,
                    array $b
                ): int {

                    $beda =
                        $b['qi'] <=> $a['qi'];

                    return $beda !== 0
                        ? $beda
                        : strnatcasecmp(
                            (string) $a['kode'],
                            (string) $b['kode']
                        );
                }
            );

            foreach ($nilai as $i => $item) {

                $tabel[$kunci][$item['kode']] = [
                    'nilai' => $item['qi'],
                    'peringkat' => $i + 1,
                ];
            }
        }

        // Membandingkan peringkat tiap alternatif.
        $baris = [];
        $semuaStabil = true;

        foreach ($dasar as $item) {

            $kode = (string) $item['kode'];

            $nilai = [];

            foreach (array_keys($variasi) as $kunci) {

                $nilai[$kunci] =
                    $tabel[$kunci][$kode];
            }

            $stabil =
                $nilai['0']['peringkat']
                    === $nilai['05']['peringkat']

                &&

                $nilai['05']['peringkat']
                    === $nilai['1']['peringkat'];

            $semuaStabil =
                $semuaStabil && $stabil;

            $baris[] = [
                'kode' => $kode,
                'nama' => $item['nama'],
                'nilai' => $nilai,
                'stabil' => $stabil,
            ];
        }

        return [
            'jumlah' => count($baris),
            'baris' => $baris,
            'stabil' => $semuaStabil,
        ];
    }
}
