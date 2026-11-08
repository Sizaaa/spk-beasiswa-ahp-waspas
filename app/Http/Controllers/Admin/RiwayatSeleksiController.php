<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LingkupSeleksi;
use App\Services\SpkCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;
use JsonException;

class RiwayatSeleksiController extends Controller
{
    // =========================================
    // MENAMPILKAN DAFTAR RIWAYAT
    // =========================================

    public function index(Request $request): View
    {
        $this->wajibAdmin($request);

        $riwayat = DB::table('riwayat_seleksi')
            ->select(
                'id',
                'created_at',
                'admin_nama',
                'lingkup',
                'mode_bobot',
                'jumlah_alternatif'
            )
            ->orderByDesc('id')
            ->paginate(12);

        return view(
            'admin.riwayat-seleksi',
            compact('riwayat')
        );
    }

    // =========================================
    // MENYIMPAN HASIL PERHITUNGAN
    // =========================================

    public function simpan(
        Request $request,
        LingkupSeleksi $lingkupSeleksi,
        SpkCalculator $kalkulator
    ): RedirectResponse {

        $this->wajibAdmin($request);

        // -------------------------------------
        // LINGKUP YANG SEDANG AKTIF
        // -------------------------------------

        $modeLingkup = $request
            ->session()
            ->get(
                'spk_lingkup',
                LingkupSeleksi::ARTIKEL
            );

        if (
            !is_string($modeLingkup) ||
            !isset(
                LingkupSeleksi::PILIHAN[$modeLingkup]
            )
        ) {
            $modeLingkup = LingkupSeleksi::ARTIKEL;
        }

        // -------------------------------------
        // BOBOT AHP YANG SEDANG AKTIF
        // -------------------------------------

        $modeBobot = 'artikel';

        $bobot = SpkCalculator::BOBOT_REFERENSI;

        $crSimulasi = null;

        $ahp = $request
            ->session()
            ->get('spk_ahp');

        if (
            $request->session()->get(
                'spk_bobot_mode'
            ) === 'simulasi'
            &&
            $this->simulasiLayak($ahp)
        ) {

            $modeBobot = 'simulasi';

            $bobot = array_map(
                'floatval',
                array_values($ahp['bobot'])
            );

            $crSimulasi = (float) $ahp['cr'];
        }

        // -------------------------------------
        // HITUNG ULANG WASPAS SAAT DISIMPAN
        // -------------------------------------

        try {

            $lingkup = $lingkupSeleksi->ambil(
                $modeLingkup
            );

            $alternatif = $lingkup['alternatif'];

            if (
                $modeLingkup === LingkupSeleksi::ARTIKEL
                &&
                count($alternatif) !== 5
            ) {

                throw new InvalidArgumentException(
                    'Data penelitian A1–A5 belum lengkap.'
                );
            }

            if (count($alternatif) === 0) {

                throw new InvalidArgumentException(
                    'Belum ada alternatif yang '
                    . 'dapat disimpan sebagai riwayat.'
                );
            }

            $hasil = $kalkulator->waspas(
                $alternatif,
                $bobot
            );

        } catch (InvalidArgumentException $e) {

            return back()->withErrors([
                'riwayat' => $e->getMessage(),
            ]);
        }

        // -------------------------------------
        // SALIN DATA ASLI C1-C5
        // -------------------------------------

        $dataAsli = array_map(
            static function (object $item): array {

                return [
                    'kode' => (string) $item->kode,

                    'nama' => (string) $item->nama,

                    'penghasilan_orang_tua' =>
                        (float) $item->penghasilan_orang_tua,

                    'jumlah_tanggungan' =>
                        (int) $item->jumlah_tanggungan,

                    'nilai_akademik' =>
                        (float) $item->nilai_akademik,

                    'kondisi_tempat_tinggal' =>
                        (int) $item->kondisi_tempat_tinggal,

                    'prestasi_non_akademik' =>
                        (int) $item->prestasi_non_akademik,
                ];
            },
            $alternatif
        );

        // -------------------------------------
        // INFORMASI ADMIN DAN WAKTU
        // -------------------------------------

        $akun = $request->user();

        $namaAdmin = trim(
            (string) (
                $akun->nama_lengkap
                ?: $akun->username
            )
        );

        $dibuatPada = now()->format(
            'Y-m-d H:i:s'
        );

        // -------------------------------------
        // MEMBANGUN SNAPSHOT
        // -------------------------------------

        $snapshot = [
            'versi' => 1,

            'dibuat_pada' => $dibuatPada,

            'admin' => [
                'id' => (int) $akun->id,
                'nama' => $namaAdmin,
            ],

            'lingkup' => [
                'kode' => $modeLingkup,

                'nama' => $lingkup['judul'],

                'catatan' => $lingkup['catatan'],
            ],

            'bobot' => [
                'mode' => $modeBobot,

                'nilai' => $bobot,

                'cr_simulasi' => $crSimulasi,

                'catatan' =>
                    $modeBobot === 'artikel'

                        ? 'Bobot yang tercantum di artikel. '
                          . 'CR 0,045 belum dapat diverifikasi '
                          . 'tanpa matriks asli.'

                        : 'Bobot hasil simulasi AHP '
                          . 'konsisten dalam sesi Admin.',
            ],

            'lambda' => SpkCalculator::LAMBDA,

            'data_asli' => $dataAsli,

            'hasil' => $hasil,
        ];

        // -------------------------------------
        // UBAH SNAPSHOT MENJADI JSON
        // -------------------------------------

        try {

            $json = json_encode(
                $snapshot,

                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_PRESERVE_ZERO_FRACTION
                | JSON_THROW_ON_ERROR
            );

        } catch (JsonException $e) {

            return back()->withErrors([
                'riwayat' =>
                    'Snapshot tidak bisa dibuat: '
                    . $e->getMessage(),
            ]);
        }

        // -------------------------------------
        // SIMPAN KE TABEL RIWAYAT
        // -------------------------------------

        $id = DB::table(
            'riwayat_seleksi'
        )->insertGetId([
            'admin_id' => (int) $akun->id,

            'admin_nama' => mb_substr(
                $namaAdmin,
                0,
                100
            ),

            'lingkup' => $modeLingkup,

            'mode_bobot' => $modeBobot,

            'jumlah_alternatif' =>
                count($dataAsli),

            'snapshot_json' => $json,

            'checksum_sha256' =>
                hash('sha256', $json),

            'created_at' => $dibuatPada,
        ]);

        return redirect()
            ->route(
                'admin.riwayat.show',
                $id
            )
            ->with(
                'sukses',
                'Riwayat perhitungan berhasil disimpan.'
            );
    }

    // =========================================
    // MENAMPILKAN DETAIL ARSIP
    // =========================================

    public function show(
        Request $request,
        int $id
    ): View {

        $this->wajibAdmin($request);

        $riwayat = DB::table(
            'riwayat_seleksi'
        )
            ->where('id', $id)
            ->first();

        abort_unless(
            $riwayat !== null,
            404
        );

        // Data dibaca dari snapshot,
        // tidak mengambil ulang data terbaru.
        $snapshot = json_decode(
            (string) $riwayat->snapshot_json,
            true
        );

        // Periksa apakah isi JSON berubah
        // tanpa perubahan pada checksum.
        $utuh = is_array($snapshot)
            && hash_equals(
                (string) $riwayat->checksum_sha256,

                hash(
                    'sha256',
                    (string) $riwayat->snapshot_json
                )
            );

        if (!$utuh) {
            $snapshot = null;
        }

        return view(
            'admin.riwayat-detail',
            compact(
                'riwayat',
                'snapshot',
                'utuh'
            )
        );
    }

    // =========================================
    // PEMERIKSAAN BOBOT SIMULASI
    // =========================================

    private function simulasiLayak(
        mixed $ahp
    ): bool {

        if (
            !is_array($ahp)
            ||
            ($ahp['konsisten'] ?? null) !== true
            ||
            !isset(
                $ahp['bobot'],
                $ahp['cr']
            )
            ||
            !is_array($ahp['bobot'])
            ||
            count($ahp['bobot']) !== 5
            ||
            !is_numeric($ahp['cr'])
            ||
            !is_finite((float) $ahp['cr'])
            ||
            (float) $ahp['cr'] < 0
            ||
            (float) $ahp['cr'] > 0.10
        ) {
            return false;
        }

        $total = 0.0;

        foreach ($ahp['bobot'] as $nilai) {

            if (
                !is_numeric($nilai)
                ||
                !is_finite((float) $nilai)
                ||
                (float) $nilai <= 0
            ) {
                return false;
            }

            $total += (float) $nilai;
        }

        return abs($total - 1.0) <= 1e-6;
    }

    // =========================================
    // KEAMANAN ROLE
    // =========================================

    private function wajibAdmin(
        Request $request
    ): void {

        abort_unless(
            $request->user()?->role === 'admin',
            403
        );
    }
}
