<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalonPenerimaSeeder extends Seeder
{
    private const ARTIKEL = [
        ['A1', 'A1', 1500000, 3, 85.00, 3, 2],
        ['A2', 'A2', 2500000, 2, 90.00, 2, 4],
        ['A3', 'A3', 1000000, 5, 80.00, 4, 1],
        ['A4', 'A4', 3000000, 4, 88.00, 2, 3],
        ['A5', 'A5', 1800000, 3, 92.00, 3, 5],
    ];

    public function run(): void
    {
        $waktu = now();

        foreach (self::ARTIKEL as [$kode, $nama, $penghasilan, $tanggungan, $akademik, $kondisi, $prestasi]) {
            DB::table('calon_penerima')->updateOrInsert(
                ['kode' => $kode],
                [
                    'nama' => $nama,
                    'penghasilan_orang_tua' => $penghasilan,
                    'jumlah_tanggungan' => $tanggungan,
                    'nilai_akademik' => $akademik,
                    'kondisi_tempat_tinggal' => $kondisi,
                    'prestasi_non_akademik' => $prestasi,
                    'created_at' => $waktu,
                    'updated_at' => $waktu,
                ]
            );
        }
    }
}
