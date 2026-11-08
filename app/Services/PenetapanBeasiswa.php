<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PenetapanBeasiswa
{
    private const KOLOM = [
        'penghasilan_orang_tua',
        'jumlah_tanggungan',
        'nilai_akademik',
        'kondisi_tempat_tinggal',
        'prestasi_non_akademik',
    ];

    public function daftarArsip()
    {
        return DB::table('riwayat_seleksi')
            ->where('lingkup', LingkupSeleksi::PENDAFTAR)
            ->where('jumlah_alternatif', '>', 0)
            ->orderByDesc('id')
            ->limit(40)
            ->get();
    }

    // Membaca hasil dari arsip, bukan menghitung ulang WASPAS.
    public function pratinjau(int $id): array
    {
        $arsip = DB::table('riwayat_seleksi')
            ->where('id', $id)
            ->first();

        return [
            'arsip' => $arsip,
            'baris' => $this->bacaArsip($arsip, false),

            'penetapan' => DB::table('penetapan_seleksi')
                ->where('riwayat_seleksi_id', $id)
                ->first(),
        ];
    }

    // Hanya keputusan yang telah diumumkan yang terlihat User.
    public function hasilUser(int $pengajuanId): ?object
    {
        if (!Schema::hasTable('penetapan_seleksi')) {
            return null;
        }

        return DB::table('penetapan_peserta as pp')
            ->join(
                'penetapan_seleksi as ps',
                'ps.id',
                '=',
                'pp.penetapan_seleksi_id'
            )
            ->where('pp.pengajuan_id', $pengajuanId)
            ->where('ps.status', 'diumumkan')
            ->select(
                'pp.keputusan',
                'pp.peringkat',
                'pp.nilai_waspas',
                'ps.diumumkan_pada',
                'ps.riwayat_seleksi_id'
            )
            ->orderByDesc('ps.diumumkan_pada')
            ->orderByDesc('ps.id')
            ->first();
    }

    // Menentukan keputusan berdasarkan kuota.
    public function keputusan(
        int $peringkat,
        int $kuota,
        int $cadangan
    ): string {
        if ($peringkat <= $kuota) {
            return 'diterima';
        }

        if ($peringkat <= $kuota + $cadangan) {
            return 'cadangan';
        }

        return 'tidak_diterima';
    }

    // Menyimpan keputusan sementara (belum diumumkan).
    public function simpanDraf(
        int $arsipId,
        int $kuota,
        int $cadangan,
        int $adminId
    ): void {
        DB::transaction(function () use (
            $arsipId,
            $kuota,
            $cadangan,
            $adminId
        ) {
            $arsip = DB::table('riwayat_seleksi')
                ->where('id', $arsipId)
                ->lockForUpdate()
                ->first();

            $baris = $this->pesertaDikunci($arsip);
            $jumlah = count($baris);

            if (
                $kuota < 1 ||
                $kuota > $jumlah ||
                $cadangan < 0 ||
                $kuota + $cadangan > $jumlah
            ) {
                $this->gagal(
                    'Kuota dan jumlah cadangan tidak sesuai jumlah peserta.'
                );
            }

            $penetapan = DB::table('penetapan_seleksi')
                ->where('riwayat_seleksi_id', $arsipId)
                ->lockForUpdate()
                ->first();

            if ($penetapan && $penetapan->status !== 'draf') {
                $this->gagal(
                    'Hasil sudah diumumkan dan tidak dapat diubah.'
                );
            }

            $this->pastikanBelumDiumumkan($baris, $arsipId);

            if ($penetapan) {
                $id = (int) $penetapan->id;

                DB::table('penetapan_seleksi')
                    ->where('id', $id)
                    ->update([
                        'kuota' => $kuota,
                        'jumlah_cadangan' => $cadangan,
                        'admin_id' => $adminId,
                        'updated_at' => now(),
                    ]);

                DB::table('penetapan_peserta')
                    ->where('penetapan_seleksi_id', $id)
                    ->delete();

            } else {
                $id = DB::table('penetapan_seleksi')
                    ->insertGetId([
                        'riwayat_seleksi_id' => $arsipId,
                        'admin_id' => $adminId,
                        'kuota' => $kuota,
                        'jumlah_cadangan' => $cadangan,
                        'status' => 'draf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            $detail = [];

            foreach ($baris as $item) {
                $detail[] = [
                    'penetapan_seleksi_id' => $id,
                    'pengajuan_id' => $item['pengajuan_id'],
                    'peringkat' => $item['peringkat'],
                    'nilai_waspas' => round($item['q'], 10),

                    'keputusan' => $this->keputusan(
                        $item['peringkat'],
                        $kuota,
                        $cadangan
                    ),
                ];
            }

            DB::table('penetapan_peserta')->insert($detail);
        }, 3);
    }

    // Mempublikasikan keputusan yang sudah disimpan.
    public function umumkan(int $arsipId): void
    {
        DB::transaction(function () use ($arsipId) {
            $arsip = DB::table('riwayat_seleksi')
                ->where('id', $arsipId)
                ->lockForUpdate()
                ->first();

            $baris = $this->pesertaDikunci($arsip);

            $penetapan = DB::table('penetapan_seleksi')
                ->where('riwayat_seleksi_id', $arsipId)
                ->lockForUpdate()
                ->first();

            if (!$penetapan || $penetapan->status !== 'draf') {
                $this->gagal(
                    'Simpan draf penetapan terlebih dahulu.'
                );
            }

            $this->pastikanBelumDiumumkan($baris, $arsipId);

            $detail = DB::table('penetapan_peserta')
                ->where('penetapan_seleksi_id', $penetapan->id)
                ->orderBy('peringkat')
                ->get();

            if ($detail->count() !== count($baris)) {
                $this->gagal(
                    'Daftar penetapan tidak lengkap. Simpan draf kembali.'
                );
            }

            foreach ($baris as $i => $item) {
                $simpan = $detail[$i];

                $keputusanBenar = $this->keputusan(
                    $item['peringkat'],
                    (int) $penetapan->kuota,
                    (int) $penetapan->jumlah_cadangan
                );

                if (
                    (int) $simpan->pengajuan_id !==
                        $item['pengajuan_id'] ||

                    (int) $simpan->peringkat !==
                        $item['peringkat'] ||

                    abs(
                        (float) $simpan->nilai_waspas -
                        $item['q']
                    ) > 0.000000001 ||

                    $simpan->keputusan !== $keputusanBenar
                ) {
                    $this->gagal(
                        'Data penetapan tidak cocok dengan arsip. Simpan draf kembali.'
                    );
                }
            }

            DB::table('penetapan_seleksi')
                ->where('id', $penetapan->id)
                ->update([
                    'status' => 'diumumkan',
                    'diumumkan_pada' => now(),
                    'updated_at' => now(),
                ]);
        }, 3);
    }

    private function pastikanBelumDiumumkan(
        array $baris,
        int $arsipId
    ): void {
        $ids = array_column($baris, 'pengajuan_id');

        $sudahAda = DB::table('penetapan_peserta as pp')
            ->join(
                'penetapan_seleksi as ps',
                'ps.id',
                '=',
                'pp.penetapan_seleksi_id'
            )
            ->whereIn('pp.pengajuan_id', $ids)
            ->where('ps.status', 'diumumkan')
            ->where(
                'ps.riwayat_seleksi_id',
                '<>',
                $arsipId
            )
            ->exists();

        if ($sudahAda) {
            $this->gagal(
                'Ada peserta yang sudah menerima pengumuman final pada penetapan lain.'
            );
        }
    }

    // Mengunci data peserta saat proses penyimpanan.
    private function pesertaDikunci(?object $arsip): array
    {
        $mentah = $this->bacaArsip($arsip, false);

        DB::table('pengajuan_beasiswa')
            ->whereIn(
                'id',
                array_column($mentah, 'pengajuan_id')
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        return $this->bacaArsip($arsip, true);
    }

    private function bacaArsip(
        ?object $arsip,
        bool $periksaTerkini
    ): array {
        if (
            !$arsip ||
            $arsip->lingkup !== LingkupSeleksi::PENDAFTAR
        ) {
            $this->gagal(
                'Pilih arsip Pendaftar Terverifikasi.'
            );
        }

        $json = (string) $arsip->snapshot_json;

        if (!hash_equals(
            (string) $arsip->checksum_sha256,
            hash('sha256', $json)
        )) {
            $this->gagal(
                'Arsip perhitungan tidak utuh.'
            );
        }

        $snapshot = json_decode($json, true);

        $hasil = $snapshot['hasil']['peringkat'] ?? null;
        $asli = $snapshot['data_asli'] ?? null;

        if (
            ($snapshot['lingkup']['kode'] ?? '') !==
                LingkupSeleksi::PENDAFTAR ||

            !is_array($hasil) ||
            !is_array($asli) ||
            !$hasil ||

            count($hasil) !==
                (int) $arsip->jumlah_alternatif ||

            count($hasil) !== count($asli)
        ) {
            $this->gagal(
                'Isi arsip perhitungan tidak valid.'
            );
        }

        $original = [];

        foreach ($asli as $data) {
            if (
                !is_array($data) ||
                !isset($data['kode']) ||
                isset($original[$data['kode']])
            ) {
                $this->gagal(
                    'Kode peserta pada arsip tidak unik.'
                );
            }

            $original[$data['kode']] = $data;
        }

        $daftar = [];
        $terpakai = [];

        foreach ($hasil as $i => $item) {
            $kode = (string) ($item['kode'] ?? '');

            if (
                !preg_match(
                    '/^PG-([1-9][0-9]*)$/D',
                    $kode,
                    $m
                ) ||

                !isset($original[$kode]) ||
                isset($terpakai[$kode]) ||

                (int) ($item['peringkat'] ?? 0)
                    !== $i + 1 ||

                !is_numeric($item['q'] ?? null) ||
                !is_finite((float) $item['q']) ||
                (float) $item['q'] < 0 ||

                trim((string) ($item['nama'] ?? ''))
                    !== trim(
                        (string) (
                            $original[$kode]['nama'] ?? ''
                        )
                    )
            ) {
                $this->gagal(
                    'Peringkat pada arsip tidak valid.'
                );
            }

            $terpakai[$kode] = true;
            $pengajuanId = (int) $m[1];

            if ($pengajuanId <= 0) {
                $this->gagal(
                    'Identitas pengajuan pada arsip tidak valid.'
                );
            }

            if ($periksaTerkini) {
                $this->pastikanMasihLayak(
                    $pengajuanId,
                    $kode,
                    $original[$kode]
                );
            }

            $daftar[] = [
                'kode' => $kode,
                'pengajuan_id' => $pengajuanId,
                'nama' => (string) $item['nama'],
                'peringkat' => $i + 1,
                'q' => (float) $item['q'],
            ];
        }

        return $daftar;
    }

    private function pastikanMasihLayak(
        int $id,
        string $kode,
        array $asli
    ): void {
        $pengajuan = DB::table('pengajuan_beasiswa as p')
            ->join(
                'akun as a',
                'a.id',
                '=',
                'p.akun_id'
            )
            ->where('p.id', $id)
            ->where('p.status', 'terverifikasi')
            ->where('a.role', 'user')
            ->select('p.*')
            ->first();

        $calon = DB::table('calon_penerima')
            ->where('kode', $kode)
            ->first();

        if (
            !$pengajuan ||
            !$calon ||

            trim((string) $pengajuan->nama_lengkap)
                !== trim((string) $asli['nama']) ||

            trim((string) $calon->nama)
                !== trim((string) $asli['nama'])
        ) {
            $this->gagal(
                "Data {$kode} telah berubah atau tidak terverifikasi."
            );
        }

        foreach (self::KOLOM as $kolom) {
            if (
                !is_numeric($asli[$kolom] ?? null) ||

                abs(
                    (float) $pengajuan->{$kolom} -
                    (float) $asli[$kolom]
                ) > 0.00001 ||

                abs(
                    (float) $calon->{$kolom} -
                    (float) $asli[$kolom]
                ) > 0.00001
            ) {
                $this->gagal(
                    "Nilai {$kode} tidak sesuai arsip. Buat arsip perhitungan baru."
                );
            }
        }
    }

    private function gagal(string $pesan): never
    {
        throw ValidationException::withMessages([
            'penetapan' => $pesan,
        ]);
    }
}
