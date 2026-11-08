<?php

namespace App\Services;

use InvalidArgumentException;

class SpkCalculator
{
    public const KRITERIA = [
        'C1' => [
            'nama' => 'Penghasilan Orang Tua',
            'kolom' => 'penghasilan_orang_tua',
            'jenis' => 'cost',
        ],
        'C2' => [
            'nama' => 'Jumlah Tanggungan',
            'kolom' => 'jumlah_tanggungan',
            'jenis' => 'benefit',
        ],
        'C3' => [
            'nama' => 'Nilai Akademik',
            'kolom' => 'nilai_akademik',
            'jenis' => 'benefit',
        ],
        'C4' => [
            'nama' => 'Kondisi Tempat Tinggal',
            'kolom' => 'kondisi_tempat_tinggal',
            'jenis' => 'benefit',
        ],
        'C5' => [
            'nama' => 'Prestasi Non-Akademik',
            'kolom' => 'prestasi_non_akademik',
            'jenis' => 'benefit',
        ],
    ];

    // Bobot referensi proyek, bukan hasil verifikasi artikel asli.
    public const BOBOT_REFERENSI = [
        0.40,
        0.25,
        0.15,
        0.12,
        0.08,
    ];

    public const LAMBDA = 0.5;

    // =========================================
    // PERHITUNGAN AHP
    // =========================================

    public function ahp(array $pasangan): array
    {
        $n = count(self::KRITERIA);

        $matriks = array_fill(
            0,
            $n,
            array_fill(0, $n, 1.0)
        );

        // Membentuk matriks resiprokal
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {

                $key = $i . '_' . $j;

                if (
                    !isset($pasangan[$key]) ||
                    !is_numeric($pasangan[$key])
                ) {
                    throw new InvalidArgumentException(
                        'Seluruh perbandingan wajib diisi.'
                    );
                }

                $nilai = (float) $pasangan[$key];

                if (
                    !is_finite($nilai) ||
                    $nilai < (1.0 / 9.0) - 1e-12 ||
                    $nilai > 9.0
                ) {
                    throw new InvalidArgumentException(
                        'Nilai perbandingan harus antara 1/9 dan 9.'
                    );
                }

                $matriks[$i][$j] = $nilai;
                $matriks[$j][$i] = 1.0 / $nilai;
            }
        }

        // Menghitung eigenvektor utama
        // menggunakan power iteration
        $bobot = array_fill(0, $n, 1.0 / $n);

        for ($iterasi = 0; $iterasi < 10000; $iterasi++) {

            $berikutnya = [];

            for ($i = 0; $i < $n; $i++) {

                $berikutnya[$i] = 0.0;

                for ($j = 0; $j < $n; $j++) {

                    $berikutnya[$i] +=
                        $matriks[$i][$j] * $bobot[$j];
                }
            }

            $total = array_sum($berikutnya);

            foreach ($berikutnya as &$nilai) {
                $nilai /= $total;
            }

            unset($nilai);

            $selisih = max(array_map(
                static fn (int $i): float =>
                    abs($berikutnya[$i] - $bobot[$i]),
                range(0, $n - 1)
            ));

            $bobot = $berikutnya;

            if ($selisih < 1e-13) {
                break;
            }
        }

        // Menghitung lambda maksimum
        $lambdaMax = 0.0;

        for ($i = 0; $i < $n; $i++) {

            $aw = 0.0;

            for ($j = 0; $j < $n; $j++) {
                $aw += $matriks[$i][$j] * $bobot[$j];
            }

            $lambdaMax += $aw / $bobot[$i];
        }

        $lambdaMax /= $n;

        // Consistency Index
        $ci = max(
            0.0,
            ($lambdaMax - $n) / ($n - 1)
        );

        // Random Index untuk 5 kriteria = 1.12
        $cr = $ci / 1.12;

        return [
            'matriks' => $matriks,
            'bobot' => $bobot,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'cr' => $cr,
            'konsisten' => $cr <= 0.10,
        ];
    }

    // =========================================
    // PERHITUNGAN WASPAS
    // =========================================

    public function waspas(
        array $alternatif,
        array $bobot
    ): array {

        if (
            count($bobot) !== 5 ||
            abs(array_sum($bobot) - 1.0) > 1e-6
        ) {
            throw new InvalidArgumentException(
                'Bobot harus lima nilai yang berjumlah 1.'
            );
        }

        foreach ($bobot as $nilaiBobot) {

            if (
                !is_numeric($nilaiBobot) ||
                $nilaiBobot <= 0
            ) {
                throw new InvalidArgumentException(
                    'Semua bobot harus positif.'
                );
            }
        }

        if ($alternatif === []) {

            return [
                'peringkat' => [],
                'batas' => [],
                'jumlah' => 0,
            ];
        }

        $kolom = array_column(
            array_values(self::KRITERIA),
            'kolom'
        );

        $batas = [];

        // =====================================
        // MENENTUKAN MINIMUM DAN MAKSIMUM
        // =====================================

        foreach ($kolom as $k => $namaKolom) {

            $angka = [];

            foreach ($alternatif as $item) {

                if (
                    !isset($item->{$namaKolom}) ||
                    !is_numeric($item->{$namaKolom})
                ) {
                    throw new InvalidArgumentException(
                        "Nilai {$namaKolom} pada salah satu alternatif tidak valid."
                    );
                }

                $nilai = (float) $item->{$namaKolom};

                if (
                    !is_finite($nilai) ||
                    $nilai < 0
                ) {
                    throw new InvalidArgumentException(
                        "Nilai {$namaKolom} tidak boleh negatif."
                    );
                }

                $angka[] = $nilai;
            }

            // C1 Cost: gunakan minimum
            // C2-C5 Benefit: gunakan maksimum
            $batas[$k] = $k === 0
                ? min($angka)
                : max($angka);

            if ($batas[$k] <= 0) {

                throw new InvalidArgumentException(
                    'Normalisasi tidak dapat dilakukan: '
                    . array_keys(self::KRITERIA)[$k]
                    . ' mempunyai batas normalisasi 0. '
                    . 'Periksa data alternatif.'
                );
            }
        }

        // =====================================
        // NORMALISASI DAN NILAI PREFERENSI
        // =====================================

        $peringkat = [];

        foreach ($alternatif as $item) {

            $normalisasi = [];

            $s = 0.0;
            $p = 1.0;

            foreach ($kolom as $k => $namaKolom) {

                $x = (float) $item->{$namaKolom};

                if ($k === 0 && $x <= 0) {

                    throw new InvalidArgumentException(
                        'Penghasilan orang tua harus lebih besar dari 0 untuk normalisasi Cost.'
                    );
                }

                // Normalisasi Cost atau Benefit
                $r = $k === 0
                    ? $batas[$k] / $x
                    : $x / $batas[$k];

                $normalisasi[] = $r;

                // Weighted Sum Model
                $s += $bobot[$k] * $r;

                // Weighted Product Model
                $p *= pow($r, $bobot[$k]);
            }

            // Nilai preferensi gabungan
            $q = self::LAMBDA * $s
                + (1 - self::LAMBDA) * $p;

            $peringkat[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'normalisasi' => $normalisasi,
                's' => $s,
                'p' => $p,
                'q' => $q,
            ];
        }

        // =====================================
        // MENGURUTKAN HASIL
        // =====================================

        usort(
            $peringkat,
            static function (array $a, array $b): int {

                $nilai = $b['q'] <=> $a['q'];

                return $nilai !== 0
                    ? $nilai
                    : strnatcasecmp(
                        (string) $a['kode'],
                        (string) $b['kode']
                    );
            }
        );

        foreach ($peringkat as $i => &$item) {
            $item['peringkat'] = $i + 1;
        }

        unset($item);

        return [
            'peringkat' => $peringkat,
            'batas' => $batas,
            'jumlah' => count($peringkat),
        ];
    }
}
