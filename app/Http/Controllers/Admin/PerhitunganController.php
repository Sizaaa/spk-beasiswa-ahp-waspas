<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LingkupSeleksi;
use App\Services\SpkCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use InvalidArgumentException;

class PerhitunganController extends Controller
{
    public function __construct(
        private readonly SpkCalculator $hitung
    ) {}

    // ============================================
    // PEMERIKSAAN AKSES ADMIN
    // ============================================

    private function wajibAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );
    }

    // ============================================
    // MATRIKS AWAL SIMULASI AHP
    // ============================================

    private function pasanganAwal(): array
    {
        // Matriks ini merupakan rekonstruksi
        // dari bobot artikel.
        //
        // BUKAN matriks asli yang menghasilkan
        // CR = 0,045 dalam artikel.

        $bobot = SpkCalculator::BOBOT_REFERENSI;

        $pasangan = [];

        for ($i = 0; $i < 5; $i++) {

            for ($j = $i + 1; $j < 5; $j++) {

                $pasangan["{$i}_{$j}"] = round(
                    $bobot[$i] / $bobot[$j],
                    12
                );
            }
        }

        return $pasangan;
    }

    // ============================================
    // MEMERIKSA HASIL SIMULASI AHP
    // ============================================

    private function hasilAhpLayak(mixed $ahp): bool
    {
        if (
            !is_array($ahp) ||
            ($ahp['konsisten'] ?? false) !== true ||
            !isset($ahp['bobot'], $ahp['cr']) ||
            !is_array($ahp['bobot']) ||
            count($ahp['bobot']) !== 5 ||
            !is_numeric($ahp['cr'])
        ) {
            return false;
        }

        $cr = (float) $ahp['cr'];

        if (
            !is_finite($cr) ||
            $cr < 0 ||
            $cr > 0.10
        ) {
            return false;
        }

        $jumlah = 0.0;

        foreach ($ahp['bobot'] as $nilai) {

            if (!is_numeric($nilai)) {
                return false;
            }

            $angka = (float) $nilai;

            if (
                !is_finite($angka) ||
                $angka <= 0
            ) {
                return false;
            }

            $jumlah += $angka;
        }

        return abs($jumlah - 1.0) <= 1e-6;
    }

    // ============================================
    // BOBOT AKTIF UNTUK WASPAS
    // ============================================

    private function informasiBobot(
        Request $request
    ): array {

        $ahp = $request
            ->session()
            ->get('spk_ahp');

        $mode = $request
            ->session()
            ->get(
                'spk_bobot_mode',
                'artikel'
            );

        // Simulasi hanya digunakan jika
        // dipilih Admin secara eksplisit
        // dan hasilnya konsisten.

        if (
            $mode === 'simulasi' &&
            $this->hasilAhpLayak($ahp)
        ) {

            return [
                'bobot' => array_map(
                    'floatval',
                    array_values($ahp['bobot'])
                ),

                'sumber' =>
                    'Simulasi AHP Admin (CR = '
                    . number_format(
                        (float) $ahp['cr'],
                        5,
                        ',',
                        '.'
                    )
                    . '; bukan matriks asli artikel)',

                'mode' => 'simulasi',

                'ahp' => $ahp,
            ];
        }

        // Default memakai bobot artikel.

        return [
            'bobot' =>
                SpkCalculator::BOBOT_REFERENSI,

            'sumber' =>
                'Bobot artikel '
                . '(C1=0,400; C2=0,250; '
                . 'C3=0,150; C4=0,120; '
                . 'C5=0,080)',

            'mode' => 'artikel',

            'ahp' => null,
        ];
    }

    // ============================================
    // HALAMAN AHP
    // ============================================

    public function ahp(Request $request): View
    {
        $this->wajibAdmin($request);

        $tersimpan = $request
            ->session()
            ->get('spk_ahp');

        $pasangan = is_array($tersimpan)
            && isset($tersimpan['pasangan'])
            && is_array($tersimpan['pasangan'])

                ? $tersimpan['pasangan']

                : $this->pasanganAwal();

        return view(
            'admin.ahp',
            [
                'kriteria' =>
                    SpkCalculator::KRITERIA,

                'pasangan' => $pasangan,

                'hasil' =>
                    is_array($tersimpan)
                        ? $tersimpan
                        : null,

                'bobotAktif' =>
                    $this->informasiBobot($request),

                'simulasiLayak' =>
                    $this->hasilAhpLayak(
                        $tersimpan
                    ),
            ]
        );
    }

    // ============================================
    // MENGHITUNG AHP
    // ============================================

    public function hitungAhp(
        Request $request
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $aturan = [];

        for ($i = 0; $i < 5; $i++) {

            for ($j = $i + 1; $j < 5; $j++) {

                $aturan["pasangan.{$i}_{$j}"] = [
                    'required',
                    'numeric',
                    'gt:0',
                    'max:9',
                ];
            }
        }

        $data = $request->validate($aturan);

        try {

            $hasil = $this->hitung->ahp(
                $data['pasangan']
            );

        } catch (InvalidArgumentException $e) {

            throw ValidationException::withMessages([
                'pasangan' => $e->getMessage(),
            ]);
        }

        // Simpan hasil AHP dalam sesi Admin.

        $request->session()->put(
            'spk_ahp',
            $hasil + [
                'pasangan' => $data['pasangan'],
            ]
        );

        // Menghitung simulasi TIDAK langsung
        // mengubah bobot WASPAS.

        $request->session()->put(
            'spk_bobot_mode',
            'artikel'
        );

        return redirect()
            ->route('admin.ahp.index')
            ->with(
                'sukses',

                $hasil['konsisten']

                    ? 'Simulasi konsisten. '
                      . 'Bobot artikel tetap aktif '
                      . 'sampai Anda memilih '
                      . 'Gunakan Bobot Simulasi.'

                    : 'Simulasi belum konsisten '
                      . '(CR > 0,10). '
                      . 'Bobot artikel tetap aktif.'
            );
    }

    // ============================================
    // MEMILIH BOBOT ARTIKEL / SIMULASI
    // ============================================

    public function pilihBobot(
        Request $request
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $data = $request->validate([
            'mode' => [
                'required',

                Rule::in([
                    'artikel',
                    'simulasi',
                ]),
            ],
        ]);

        if (
            $data['mode'] === 'simulasi' &&
            !$this->hasilAhpLayak(
                $request->session()->get('spk_ahp')
            )
        ) {

            throw ValidationException::withMessages([
                'mode' =>
                    'Simulasi harus dihitung '
                    . 'dan konsisten (CR ≤ 0,10) '
                    . 'sebelum digunakan.',
            ]);
        }

        $request->session()->put(
            'spk_bobot_mode',
            $data['mode']
        );

        return redirect()
            ->route('admin.ahp.index')
            ->with(
                'sukses',

                $data['mode'] === 'artikel'

                    ? 'Bobot artikel kini aktif '
                      . 'untuk WASPAS, Hasil Seleksi, '
                      . 'dan Laporan.'

                    : 'Bobot simulasi kini aktif '
                      . 'untuk WASPAS, Hasil Seleksi, '
                      . 'dan Laporan.'
            );
    }

    // ============================================
    // RESET SIMULASI AHP
    // ============================================

    public function resetAhp(
        Request $request
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $request->session()->forget([
            'spk_ahp',
            'spk_bobot_mode',
        ]);

        return redirect()
            ->route('admin.ahp.index')
            ->with(
                'sukses',
                'Hasil simulasi sesi dihapus. '
                . 'Bobot artikel kembali aktif.'
            );
    }

    // ============================================
    // DATA WASPAS BERDASARKAN LINGKUP
    // ============================================

    private function dataWaspas(
        Request $request
    ): array {

        // Menentukan bobot aktif.
        $informasi = $this->informasiBobot(
            $request
        );

        // Menentukan lingkup aktif.
        $mode = $request
            ->session()
            ->get(
                'spk_lingkup',
                LingkupSeleksi::ARTIKEL
            );

        // Pastikan pilihan lingkup valid.
        $mode = is_string($mode)
            && isset(
                LingkupSeleksi::PILIHAN[$mode]
            )

                ? $mode

                : LingkupSeleksi::ARTIKEL;

        // Data awal jika terjadi kesalahan.
        $lingkupInfo = [
            'mode' => $mode,

            'judul' =>
                LingkupSeleksi::PILIHAN[$mode],

            'alternatif' => [],

            'catatan' => [],
        ];

        $alternatif = [];

        $hasil = [
            'peringkat' => [],
            'batas' => [],
            'jumlah' => 0,
        ];

        $pesan = null;

        try {

            // Mengambil alternatif berdasarkan
            // mode yang dipilih Admin.

            $lingkupInfo = app(
                LingkupSeleksi::class
            )->ambil($mode);

            $alternatif =
                $lingkupInfo['alternatif'];

            // Mode penelitian wajib memiliki
            // lima data A1–A5.

            if (
                $mode === LingkupSeleksi::ARTIKEL
                &&
                count($alternatif) !== 5
            ) {

                throw new InvalidArgumentException(
                    'Data A1–A5 harus lengkap '
                    . 'dan unik untuk mode Penelitian.'
                );
            }

            // Menggunakan mesin WASPAS
            // yang sama untuk ketiga lingkup.

            $hasil = $this->hitung->waspas(
                $alternatif,
                $informasi['bobot']
            );

        } catch (InvalidArgumentException $e) {

            $pesan = $e->getMessage();
        }

        // Seluruh halaman menggunakan data
        // dan hasil perhitungan yang sama.

        return [
            'informasi' => $informasi,

            'alternatif' => $alternatif,

            'hasil' => $hasil,

            'pesan' => $pesan,

            'kriteria' =>
                SpkCalculator::KRITERIA,

            'lambda' =>
                SpkCalculator::LAMBDA,

            'lingkupInfo' => $lingkupInfo,
        ];
    }

    // ============================================
    // HALAMAN WASPAS
    // ============================================

    public function waspas(
        Request $request
    ): View {

        $this->wajibAdmin($request);

        return view(
            'admin.waspas',
            $this->dataWaspas($request)
        );
    }

    // ============================================
    // HALAMAN HASIL SELEKSI
    // ============================================

    public function hasil(
        Request $request
    ): View {

        $this->wajibAdmin($request);

        return view(
            'admin.hasil',
            $this->dataWaspas($request)
        );
    }

    // ============================================
    // HALAMAN LAPORAN
    // ============================================

    public function laporan(
        Request $request
    ): View {

        $this->wajibAdmin($request);

        return view(
            'admin.laporan',
            $this->dataWaspas($request)
        );
    }
}
