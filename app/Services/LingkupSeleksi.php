<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LingkupSeleksi
{
    public const ARTIKEL = 'artikel';
    public const PENDAFTAR = 'pendaftar';
    public const GABUNGAN = 'gabungan';

    public const PILIHAN = [
        self::ARTIKEL => 'Penelitian A1–A5',
        self::PENDAFTAR => 'Pendaftar terverifikasi',
        self::GABUNGAN => 'Gabungan untuk demonstrasi',
    ];

    private const KOLOM = [
        'penghasilan_orang_tua',
        'jumlah_tanggungan',
        'nilai_akademik',
        'kondisi_tempat_tinggal',
        'prestasi_non_akademik',
    ];

    public function ambil(string $mode): array
    {
        if (!array_key_exists($mode, self::PILIHAN)) {
            $mode = self::ARTIKEL;
        }

        $query = DB::table('calon_penerima')
            ->select('id', 'kode', 'nama', ...self::KOLOM)
            ->orderBy('kode');

        $catatan = [];

        // =====================================
        // MODE 1: DATA ARTIKEL
        // =====================================

        if ($mode === self::ARTIKEL) {

            $query->whereIn(
                'kode',
                ['A1', 'A2', 'A3', 'A4', 'A5']
            );

            $alternatif = $query->get()->all();

            if (count($alternatif) !== 5) {

                $catatan[] =
                    'A1–A5 tidak lengkap atau duplikat '
                    . 'di database. Periksa Data Calon Penerima.';
            }

        // =====================================
        // MODE 2: PENDAFTAR TERVERIFIKASI
        // =====================================

        } elseif ($mode === self::PENDAFTAR) {

            // Ambil hanya pengajuan yang saat ini
            // Terverifikasi dan milik akun User.
            $sumber = DB::table('pengajuan_beasiswa as p')
                ->join(
                    'akun as a',
                    'a.id',
                    '=',
                    'p.akun_id'
                )
                ->where('a.role', 'user')
                ->where('p.status', 'terverifikasi')
                ->select(
                    'p.id',
                    'p.nama_lengkap',
                    ...array_map(
                        static fn (string $kolom): string =>
                            'p.' . $kolom,
                        self::KOLOM
                    )
                )
                ->get();

            // Hubungkan pengajuan dengan PG-ID.
            $menurutKode = [];

            foreach ($sumber as $pengajuan) {

                $menurutKode[
                    'PG-' . $pengajuan->id
                ] = $pengajuan;
            }

            $kodeValid = array_keys($menurutKode);

            // Tanpa pengajuan terverifikasi,
            // tidak perlu menjalankan query alternatif.
            $calon = $kodeValid === []
                ? []
                : $query
                    ->whereIn('kode', $kodeValid)
                    ->get()
                    ->all();

            $alternatif = [];
            $sudahMasuk = [];
            $tidakSesuai = 0;

            foreach ($calon as $item) {

                $asal = $menurutKode[
                    $item->kode
                ] ?? null;

                if ($asal === null) {
                    continue;
                }

                // Nama harus sama dengan pengajuan asal.
                $cocok =
                    trim((string) $asal->nama_lengkap)
                    === trim((string) $item->nama);

                // Nilai C1–C5 juga harus sesuai.
                foreach (self::KOLOM as $kolom) {

                    if (
                        !is_numeric($asal->{$kolom}) ||
                        !is_numeric($item->{$kolom}) ||
                        abs(
                            (float) $asal->{$kolom}
                            - (float) $item->{$kolom}
                        ) > 0.00001
                    ) {

                        $cocok = false;
                        break;
                    }
                }

                if (!$cocok) {

                    // Jangan menghitung data
                    // yang berbeda dengan sumbernya.
                    $tidakSesuai++;

                    continue;
                }

                $sudahMasuk[
                    $item->kode
                ] = true;

                $alternatif[] = $item;
            }

            $belumMasuk = count(
                array_diff_key(
                    $menurutKode,
                    $sudahMasuk
                )
            );

            if ($belumMasuk > 0) {

                $catatan[] =
                    "{$belumMasuk} pengajuan terverifikasi "
                    . 'belum masuk seleksi atau datanya '
                    . 'tidak cocok. Periksa menu '
                    . 'Pengajuan Siap Seleksi.';
            }

            if ($tidakSesuai > 0) {

                $catatan[] =
                    "{$tidakSesuai} alternatif PG-ID "
                    . 'dilewati karena berbeda dari '
                    . 'data pengajuan asal.';
            }

        // =====================================
        // MODE 3: SEMUA DATA UNTUK DEMO
        // =====================================

        } else {

            $alternatif = $query->get()->all();

            $catatan[] =
                'Mode gabungan mencampur data '
                . 'penelitian, alternatif manual, '
                . 'dan pengajuan. Gunakan hanya '
                . 'untuk demonstrasi, bukan '
                . 'keputusan penerimaan siswa.';
        }

        // =====================================
        // CEGAH KODE ALTERNATIF DUPLIKAT
        // =====================================

        $ditemukan = [];

        foreach ($alternatif as $item) {

            $kode = strtoupper(
                trim((string) $item->kode)
            );

            if (
                $kode === '' ||
                isset($ditemukan[$kode])
            ) {

                throw new InvalidArgumentException(
                    'Ada kode alternatif kosong '
                    . 'atau duplikat. Periksa '
                    . 'Data Calon Penerima.'
                );
            }

            $ditemukan[$kode] = true;
        }

        return [
            'mode' => $mode,
            'judul' => self::PILIHAN[$mode],
            'alternatif' => $alternatif,
            'catatan' => $catatan,
        ];
    }
}
