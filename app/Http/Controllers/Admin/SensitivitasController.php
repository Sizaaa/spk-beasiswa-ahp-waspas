<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalisisSensitivitas;
use App\Services\SpkCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

class SensitivitasController extends Controller
{
    public function index(
        Request $request,
        AnalisisSensitivitas $analisis
    ): View {

        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        // =====================================
        // MEMILIH LINGKUP ANALISIS
        // =====================================

        $lingkup = $request->query(
            'lingkup',
            'artikel'
        );

        if (
            !is_string($lingkup) ||
            !in_array(
                $lingkup,
                ['artikel', 'semua'],
                true
            )
        ) {
            $lingkup = 'artikel';
        }

        // =====================================
        // MEMBACA ALTERNATIF
        // =====================================

        $query = DB::table('calon_penerima')
            ->select(
                'id',
                'kode',
                'nama',
                'penghasilan_orang_tua',
                'jumlah_tanggungan',
                'nilai_akademik',
                'kondisi_tempat_tinggal',
                'prestasi_non_akademik'
            );

        if ($lingkup === 'artikel') {

            $query->whereIn(
                'kode',
                ['A1', 'A2', 'A3', 'A4', 'A5']
            );
        }

        $alternatif = $query
            ->orderBy('kode')
            ->get()
            ->all();

        // =====================================
        // MENENTUKAN BOBOT
        // =====================================

        $bobot =
            SpkCalculator::BOBOT_REFERENSI;

        $sumber =
            'Bobot bagian AHP artikel; '
            . 'hanya alternatif A1–A5 dari MySQL.';

        $pesan = null;

        $hasil = [
            'jumlah' => 0,
            'baris' => [],
            'stabil' => null,
        ];

        if ($lingkup === 'semua') {

            $sumber =
                'Bobot artikel untuk seluruh '
                . 'alternatif MySQL.';

            $mode = $request
                ->session()
                ->get(
                    'spk_bobot_mode',
                    'artikel'
                );

            $ahp = $request
                ->session()
                ->get('spk_ahp');

            if (
                $mode === 'simulasi' &&
                $this->simulasiLayak($ahp)
            ) {

                $bobot = array_map(
                    'floatval',
                    array_values($ahp['bobot'])
                );

                $sumber =
                    'Bobot simulasi AHP konsisten '
                    . 'yang aktif pada sesi Admin.';
            }

        } elseif (count($alternatif) !== 5) {

            $pesan =
                'A1–A5 belum lengkap di MySQL. '
                . 'Uji lima alternatif artikel '
                . 'tidak dapat dijalankan.';
        }

        // =====================================
        // MENGHITUNG SENSITIVITAS
        // =====================================

        if ($pesan === null) {

            try {

                $hasil = $analisis->hitung(
                    $alternatif,
                    $bobot
                );

            } catch (InvalidArgumentException $e) {

                $pesan = $e->getMessage();
            }
        }

        return view(
            'admin.sensitivitas',
            compact(
                'lingkup',
                'alternatif',
                'bobot',
                'sumber',
                'hasil',
                'pesan'
            )
        );
    }

    // =========================================
    // VALIDASI BOBOT SIMULASI
    // =========================================

    private function simulasiLayak(
        mixed $ahp
    ): bool {

        if (
            !is_array($ahp)

            || ($ahp['konsisten'] ?? false) !== true

            || !isset(
                $ahp['bobot'],
                $ahp['cr']
            )

            || !is_array($ahp['bobot'])

            || count($ahp['bobot']) !== 5

            || !is_numeric($ahp['cr'])

            || (float) $ahp['cr'] < 0

            || (float) $ahp['cr'] > 0.10
        ) {
            return false;
        }

        $total = 0.0;

        foreach ($ahp['bobot'] as $nilai) {

            if (
                !is_numeric($nilai)

                || !is_finite((float) $nilai)

                || (float) $nilai <= 0
            ) {
                return false;
            }

            $total += (float) $nilai;
        }

        return abs($total - 1.0) <= 1e-6;
    }
}
