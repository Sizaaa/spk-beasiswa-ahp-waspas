<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VerifikasiController extends Controller
{
    // Nama dokumen yang digunakan
    private const NAMA_DOKUMEN = [
        'kk' => 'Kartu Keluarga',
        'penghasilan' => 'Bukti Penghasilan / SKTM',
        'rapor' => 'Rapor Terakhir',
        'sertifikat' => 'Sertifikat Prestasi',
    ];

    // =========================================
    // MEMERIKSA AKSES ADMIN
    // =========================================

    private function wajibAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );
    }

    // =========================================
    // MENGAMBIL DETAIL PENGAJUAN
    // =========================================

    private function cariPengajuan(int $id): ?object
    {
        return DB::table('pengajuan_beasiswa as p')
            ->join('akun as a', 'a.id', '=', 'p.akun_id')
            ->where('p.id', $id)
            ->where('a.role', 'user')
            ->whereIn('p.status', [
                'diajukan',
                'perlu_perbaikan',
                'terverifikasi',
                'ditolak',
            ])
            ->select(
                'p.*',
                'a.username',
                'a.email'
            )
            ->first();
    }

    // =========================================
    // FOLDER DOKUMEN
    // =========================================

    private function folderAkun(int $idAkun): string
    {
        return dirname(base_path(), 2)
            . DIRECTORY_SEPARATOR . 'var'
            . DIRECTORY_SEPARATOR . 'spk_beasiswa_berkas'
            . DIRECTORY_SEPARATOR . 'akun_' . $idAkun;
    }

    private function cariDokumen(
        int $idAkun,
        string $jenis
    ): ?string {

        $folder = $this->folderAkun($idAkun);

        foreach (['pdf', 'jpg', 'png'] as $ext) {

            $lokasi = $folder
                . DIRECTORY_SEPARATOR
                . $jenis . '.' . $ext;

            if (
                is_file($lokasi) &&
                is_readable($lokasi) &&
                filesize($lokasi) > 0
            ) {
                return $lokasi;
            }
        }

        return null;
    }

    // =========================================
    // HALAMAN VERIFIKASI DAN FILTER
    // =========================================

    public function index(Request $request): View
    {
        $this->wajibAdmin($request);

        // Status yang tersedia
        $labelStatus = [
            'diajukan' => 'Menunggu Verifikasi',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
        ];

        $warnaStatus = [
            'diajukan' => 'bg-blue-100 text-blue-800',
            'perlu_perbaikan' => 'bg-amber-100 text-amber-800',
            'terverifikasi' => 'bg-emerald-100 text-emerald-800',
            'ditolak' => 'bg-red-100 text-red-800',
        ];

        // =====================================
        // MEMBACA FILTER DARI URL
        // =====================================

        // Contoh:
        // ?status=perlu_perbaikan
        // ?status=terverifikasi

        $filter = $request->query('status', '');

        if (
            !is_string($filter) ||
            (
                $filter !== '' &&
                !array_key_exists($filter, $labelStatus)
            )
        ) {
            $filter = '';
        }

        $filterAktif = $filter !== '';

        $namaFilter = $filterAktif
            ? $labelStatus[$filter]
            : 'Semua Status';

        // =====================================
        // STATISTIK SELURUH PENGAJUAN
        // =====================================

        $statistik = DB::table('pengajuan_beasiswa as p')
            ->join(
                'akun as a',
                'a.id',
                '=',
                'p.akun_id'
            )
            ->where('a.role', 'user')
            ->whereIn('p.status', array_keys($labelStatus))
            ->selectRaw("
                COUNT(*) AS total,
                COALESCE(SUM(p.status = 'diajukan'), 0)
                    AS menunggu,
                COALESCE(SUM(p.status = 'terverifikasi'), 0)
                    AS terverifikasi,
                COALESCE(SUM(p.status = 'perlu_perbaikan'), 0)
                    AS perbaikan,
                COALESCE(SUM(p.status = 'ditolak'), 0)
                    AS ditolak
            ")
            ->first();

        // =====================================
        // DETAIL PENGAJUAN
        // =====================================

        $detailId = $request->query('detail');

        $detail = null;
        $dokumen = [];

        if ($detailId !== null) {

            abort_unless(
                is_string($detailId) &&
                preg_match(
                    '/^[1-9][0-9]*$/D',
                    $detailId
                ),
                404
            );

            $detail = $this->cariPengajuan(
                (int) $detailId
            );

            abort_unless($detail !== null, 404);

            // Memeriksa dokumen milik pendaftar
            foreach (
                self::NAMA_DOKUMEN as $jenis => $nama
            ) {

                $dokumen[$jenis] = [
                    'nama' => $nama,
                    'wajib' => $jenis !== 'sertifikat',
                    'ada' => $this->cariDokumen(
                        (int) $detail->akun_id,
                        $jenis
                    ) !== null,
                ];
            }
        }

        // =====================================
        // MENGAMBIL DAFTAR SESUAI FILTER
        // =====================================

        $daftar = null;
        $jumlahFilter = 0;

        if ($detail === null) {

            $query = DB::table('pengajuan_beasiswa as p')
                ->join(
                    'akun as a',
                    'a.id',
                    '=',
                    'p.akun_id'
                )
                ->where('a.role', 'user')
                ->whereIn(
                    'p.status',
                    array_keys($labelStatus)
                )
                ->select(
                    'p.id',
                    'p.nama_lengkap',
                    'p.nisn',
                    'p.asal_sekolah',
                    'p.diajukan_pada',
                    'p.status',
                    'a.username'
                );

            // =================================
            // PERBAIKAN FILTER
            // =================================

            // Jika filter dipilih, hanya ambil
            // data dengan status yang sama.

            if ($filterAktif) {

                $query->where(
                    'p.status',
                    '=',
                    $filter
                );
            }

            // Jika tidak ada filter,
            // tampilkan seluruh status.

            $daftar = $query
                ->orderByDesc('p.diajukan_pada')
                ->orderByDesc('p.id')
                ->paginate(10)
                ->withQueryString();

            // Jumlah data setelah difilter
            $jumlahFilter = $daftar->total();
        }

        return view('admin.verifikasi', compact(
            'detail',
            'dokumen',
            'daftar',
            'filter',
            'filterAktif',
            'namaFilter',
            'jumlahFilter',
            'labelStatus',
            'warnaStatus',
            'statistik'
        ));
    }

    // =========================================
    // DOWNLOAD DOKUMEN ADMIN
    // =========================================

    public function unduh(
        Request $request,
        int $id,
        string $jenis
    ): StreamedResponse {

        $this->wajibAdmin($request);

        abort_unless(
            isset(self::NAMA_DOKUMEN[$jenis]),
            404
        );

        $pengajuan = $this->cariPengajuan($id);

        abort_unless(
            $pengajuan !== null,
            404
        );

        $lokasi = $this->cariDokumen(
            (int) $pengajuan->akun_id,
            $jenis
        );

        abort_unless($lokasi !== null, 404);

        $ext = strtolower(
            pathinfo(
                $lokasi,
                PATHINFO_EXTENSION
            )
        );

        $tipe = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        abort_unless(isset($tipe[$ext]), 404);

        // Memeriksa format berkas asli
        $mimeAsli = (
            new \finfo(FILEINFO_MIME_TYPE)
        )->file($lokasi);

        abort_unless(
            $mimeAsli === $tipe[$ext],
            422,
            'Format berkas tidak sesuai isi file.'
        );

        if (in_array($ext, ['jpg', 'png'], true)) {

            abort_unless(
                @getimagesize($lokasi) !== false,
                422,
                'Gambar tidak dapat dibaca.'
            );
        }

        clearstatcache(true, $lokasi);

        $ukuran = filesize($lokasi);

        abort_unless(
            $ukuran !== false &&
            $ukuran > 0,
            404
        );

        // Download bersih seperti perbaikan
        // yang sudah berhasil sebelumnya.
        return response()->streamDownload(

            function () use ($lokasi): void {

                while (ob_get_level() > 0) {

                    if (!@ob_end_clean()) {
                        @ob_clean();
                        break;
                    }
                }

                readfile($lokasi);
            },

            'pengajuan_' . $id . '_'
                . $jenis . '.' . $ext,

            [
                'Content-Type' => $tipe[$ext],

                'Content-Length' => (string) $ukuran,

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'X-Content-Type-Options' =>
                    'nosniff',
            ]
        );
    }

    // =========================================
    // MENYIMPAN KEPUTUSAN ADMIN
    // =========================================

    public function keputusan(
        Request $request,
        int $id
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $data = $request->validate([
            'keputusan' => [
                'required',
                Rule::in([
                    'terverifikasi',
                    'perlu_perbaikan',
                    'ditolak',
                ]),
            ],

            'catatan_admin' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $keputusan = $data['keputusan'];

        $catatan = trim(
            $data['catatan_admin'] ?? ''
        );

        // Alasan wajib untuk perbaikan / tolak
        if (
            in_array(
                $keputusan,
                ['perlu_perbaikan', 'ditolak'],
                true
            ) &&
            mb_strlen($catatan) < 10
        ) {
            throw ValidationException::withMessages([
                'catatan_admin' =>
                    'Tulis alasan minimal 10 karakter.',
            ]);
        }

        // Verifikasi butuh konfirmasi pemeriksaan
        if (
            $keputusan === 'terverifikasi' &&
            !$request->boolean('sudah_periksa')
        ) {
            throw ValidationException::withMessages([
                'sudah_periksa' =>
                    'Centang pernyataan pemeriksaan data dan dokumen.',
            ]);
        }

        // =====================================
        // SIMPAN KEPUTUSAN KE MYSQL
        // =====================================

        DB::transaction(function () use (
            $id,
            $keputusan,
            $catatan
        ): void {

            $pengajuan = DB::table('pengajuan_beasiswa')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            abort_unless(
                $pengajuan !== null,
                404
            );

            abort_unless(
                DB::table('akun')
                    ->where('id', $pengajuan->akun_id)
                    ->where('role', 'user')
                    ->exists(),
                404
            );

            if ($pengajuan->status !== 'diajukan') {

                throw ValidationException::withMessages([
                    'keputusan' =>
                        'Hanya pengajuan berstatus Diajukan yang dapat diputuskan.',
                ]);
            }

            // =================================
            // VALIDASI SEBELUM TERVERIFIKASI
            // =================================

            if ($keputusan === 'terverifikasi') {

                $wajibIsi = [
                    'nama_lengkap',
                    'nisn',
                    'asal_sekolah',
                    'kelas',
                    'nomor_hp',
                    'alamat_lengkap',
                    'pekerjaan_orang_tua',
                    'penghasilan_orang_tua',
                    'jumlah_tanggungan',
                    'kondisi_tempat_tinggal',
                    'nilai_akademik',
                    'prestasi_non_akademik',
                ];

                foreach ($wajibIsi as $kolom) {

                    if (
                        $pengajuan->{$kolom} === null ||
                        trim(
                            (string) $pengajuan->{$kolom}
                        ) === ''
                    ) {
                        throw ValidationException::withMessages([
                            'keputusan' =>
                                'Data '
                                . str_replace('_', ' ', $kolom)
                                . ' belum lengkap.',
                        ]);
                    }
                }

                if (
                    !preg_match(
                        '/^[0-9]{10}$/D',
                        (string) $pengajuan->nisn
                    ) ||
                    (float) $pengajuan->penghasilan_orang_tua <= 0 ||
                    (int) $pengajuan->jumlah_tanggungan < 0 ||
                    (int) $pengajuan->jumlah_tanggungan > 50 ||
                    (float) $pengajuan->nilai_akademik < 0 ||
                    (float) $pengajuan->nilai_akademik > 100 ||
                    (int) $pengajuan->kondisi_tempat_tinggal < 1 ||
                    (int) $pengajuan->kondisi_tempat_tinggal > 5 ||
                    (int) $pengajuan->prestasi_non_akademik < 1 ||
                    (int) $pengajuan->prestasi_non_akademik > 5
                ) {
                    throw ValidationException::withMessages([
                        'keputusan' =>
                            'NISN atau nilai kriteria tidak sesuai aturan.',
                    ]);
                }

                foreach (
                    ['kk', 'penghasilan', 'rapor'] as $jenis
                ) {

                    if (
                        $this->cariDokumen(
                            (int) $pengajuan->akun_id,
                            $jenis
                        ) === null
                    ) {
                        throw ValidationException::withMessages([
                            'keputusan' =>
                                'Dokumen '
                                . self::NAMA_DOKUMEN[$jenis]
                                . ' belum tersedia.',
                        ]);
                    }
                }
            }

            // Mengubah status pengajuan
            DB::table('pengajuan_beasiswa')
                ->where('id', $id)
                ->update([
                    'status' => $keputusan,

                    'catatan_admin' =>
                        $catatan === ''
                            ? null
                            : $catatan,

                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route(
                'admin.verifikasi.index',
                ['detail' => $id]
            )
            ->with(
                'sukses',
                'Keputusan verifikasi berhasil disimpan.'
            );
    }
}
