<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\PenetapanBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    // =========================================
    // MEMERIKSA ROLE USER
    // =========================================

    private function periksaRole(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'user',
            403
        );
    }

    // =========================================
    // LOKASI DOKUMEN
    // =========================================

    private function folderAkun(int $idAkun): string
    {
        return dirname(base_path(), 2)
            . DIRECTORY_SEPARATOR . 'var'
            . DIRECTORY_SEPARATOR . 'spk_beasiswa_berkas'
            . DIRECTORY_SEPARATOR . 'akun_' . $idAkun;
    }

    // =========================================
    // MENCARI DOKUMEN
    // =========================================

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
    // MEMBACA KEPUTUSAN AKHIR
    // =========================================
    //
    // Layanan PenetapanBeasiswa hanya boleh
    // mengembalikan keputusan yang sudah
    // diumumkan secara resmi.
    //
    // Metode ini menormalkan nama keputusan
    // untuk ditampilkan pada Dashboard.
    //
    // Tidak boleh membuat keputusan baru
    // hanya berdasarkan status Terverifikasi.
    // =========================================

    private function keputusanPublik(mixed $hasil): ?string
    {
        if ($hasil === null) {
            return null;
        }

        $nilaiYangDiperiksa = [];

        if (is_string($hasil)) {
            $nilaiYangDiperiksa[] = $hasil;
        }

        foreach ([
            'keputusan',
            'status_keputusan',
            'keputusan_akhir',
            'hasil_akhir',
            'status_akhir',
            'hasil',
            'status',
            'peserta.keputusan',
            'peserta.status',
            'detail.keputusan',
        ] as $kunci) {

            $nilaiYangDiperiksa[] = data_get(
                $hasil,
                $kunci
            );
        }

        foreach ($nilaiYangDiperiksa as $nilai) {

            if (!is_string($nilai)) {
                continue;
            }

            $kode = mb_strtolower(
                trim($nilai)
            );

            $kode = str_replace(
                [' ', '-'],
                '_',
                $kode
            );

            if (in_array(
                $kode,
                [
                    'diterima',
                    'cadangan',
                    'tidak_diterima',
                ],
                true
            )) {
                return $kode;
            }
        }

        return null;
    }

    // =========================================
    // MENAMPILKAN DASHBOARD USER
    // =========================================

    public function index(
        Request $request,
        PenetapanBeasiswa $penetapanBeasiswa
    ): View {

        $this->periksaRole($request);

        // =====================================
        // AKUN DAN PENGAJUAN SENDIRI
        // =====================================

        $akun = $request->user();

        $pengajuan = DB::table(
            'pengajuan_beasiswa'
        )
            ->where('akun_id', $akun->id)
            ->first();

        $status = $pengajuan?->status
            ?? 'belum_mengisi';

        // =====================================
        // STATUS ADMINISTRASI
        // =====================================

        $daftarStatus = [

            'belum_mengisi' => [
                'judul' => 'Belum mengajukan',
                'keterangan' =>
                    'Kamu belum mempunyai pengajuan beasiswa.',
                'warna' =>
                    'bg-slate-100 text-slate-700',
            ],

            'draf' => [
                'judul' => 'Draf tersimpan',
                'keterangan' =>
                    'Pengajuan belum dikirim untuk diperiksa Admin.',
                'warna' =>
                    'bg-amber-100 text-amber-800',
            ],

            'diajukan' => [
                'judul' => 'Diajukan',
                'keterangan' =>
                    'Pengajuan sudah dikirim dan menunggu verifikasi Admin.',
                'warna' =>
                    'bg-blue-100 text-blue-800',
            ],

            'perlu_perbaikan' => [
                'judul' => 'Perlu perbaikan',
                'keterangan' =>
                    'Admin meminta perbaikan pada pengajuanmu.',
                'warna' =>
                    'bg-amber-100 text-amber-800',
            ],

            'terverifikasi' => [
                'judul' => 'Terverifikasi',
                'keterangan' =>
                    'Pengajuan lolos pemeriksaan administrasi. Keputusan penerimaan beasiswa ditampilkan secara terpisah.',
                'warna' =>
                    'bg-emerald-100 text-emerald-800',
            ],

            'ditolak' => [
                'judul' => 'Ditolak pada verifikasi',
                'keterangan' =>
                    'Pengajuan tidak lolos pemeriksaan administrasi.',
                'warna' =>
                    'bg-red-100 text-red-800',
            ],
        ];

        $infoStatus = $daftarStatus[$status] ?? [
            'judul' => 'Status tidak dikenali',
            'keterangan' =>
                'Hubungi administrator untuk informasi pengajuan.',
            'warna' =>
                'bg-slate-100 text-slate-700',
        ];

        // =====================================
        // MEMBACA HASIL AKHIR DARI ADMIN
        // =====================================
        //
        // Hanya mengambil pengajuan milik akun
        // yang sedang login.
        //
        // Tidak membaca draf sebagai hasil akhir.
        // PenetapanBeasiswa::hasilUser()
        // harus menjadi sumber keputusan resmi.
        // =====================================

        $hasilAkhir = null;

        if (
            $pengajuan !== null &&
            Schema::hasTable('penetapan_seleksi') &&
            Schema::hasTable('penetapan_peserta')
        ) {
            $hasilAkhir = $penetapanBeasiswa->hasilUser(
                (int) $pengajuan->id
            );
        }

        $keputusanFinal = $this->keputusanPublik(
            $hasilAkhir
        );

        $sudahDiumumkan = $keputusanFinal !== null;

        // =====================================
        // INFORMASI KEPUTUSAN AKHIR
        // =====================================

        $daftarKeputusan = [

            'diterima' => [
                'judul' => 'Diterima',
                'keterangan' =>
                    'Selamat! Kamu ditetapkan sebagai penerima beasiswa berdasarkan hasil seleksi yang telah diumumkan oleh Admin.',
                'warna' =>
                    'bg-emerald-100 text-emerald-800',
                'warnaPanel' =>
                    'border-emerald-200 bg-emerald-50 text-emerald-900',
            ],

            'cadangan' => [
                'judul' => 'Cadangan',
                'keterangan' =>
                    'Kamu masuk daftar cadangan berdasarkan hasil seleksi yang telah diumumkan. Status ini bukan penerimaan utama.',
                'warna' =>
                    'bg-amber-100 text-amber-800',
                'warnaPanel' =>
                    'border-amber-200 bg-amber-50 text-amber-900',
            ],

            'tidak_diterima' => [
                'judul' => 'Tidak Diterima',
                'keterangan' =>
                    'Berdasarkan hasil seleksi akhir yang telah diumumkan, kamu belum ditetapkan sebagai penerima beasiswa.',
                'warna' =>
                    'bg-rose-100 text-rose-800',
                'warnaPanel' =>
                    'border-rose-200 bg-rose-50 text-rose-900',
            ],
        ];

        $infoKeputusan = $sudahDiumumkan
            ? $daftarKeputusan[$keputusanFinal]
            : [
                'judul' => 'Menunggu Pengumuman',
                'keterangan' =>
                    'Belum ada keputusan akhir yang diumumkan untuk pengajuanmu. Status Terverifikasi belum berarti Diterima.',
                'warna' =>
                    'bg-slate-100 text-slate-700',
                'warnaPanel' =>
                    'border-slate-200 bg-slate-50 text-slate-700',
            ];

        // =====================================
        // MEMERIKSA DOKUMEN
        // =====================================

        $dokumen = [

            'kk' => [
                'nama' => 'Kartu Keluarga',
                'wajib' => true,
            ],

            'penghasilan' => [
                'nama' => 'Bukti Penghasilan / SKTM',
                'wajib' => true,
            ],

            'rapor' => [
                'nama' => 'Rapor Terakhir',
                'wajib' => true,
            ],

            'sertifikat' => [
                'nama' => 'Sertifikat Prestasi',
                'wajib' => false,
            ],
        ];

        $jumlahWajib = 0;
        $jumlahTerunggah = 0;

        foreach ($dokumen as $jenis => &$item) {

            $item['ada'] = $pengajuan !== null
                && $this->cariDokumen(
                    (int) $akun->id,
                    $jenis
                ) !== null;

            if ($item['wajib']) {

                $jumlahWajib++;

                if ($item['ada']) {
                    $jumlahTerunggah++;
                }
            }
        }

        unset($item);

        // =====================================
        // TAHAP 2: FORMULIR DAN BERKAS
        // =====================================

        $formulirSelesai =
            in_array(
                $status,
                [
                    'diajukan',
                    'terverifikasi',
                    'ditolak',
                ],
                true
            ) ||
            $sudahDiumumkan;

        $statusForm = match ($status) {

            'belum_mengisi' => 'Belum dimulai',

            'draf' => 'Draf tersimpan',

            'perlu_perbaikan' => 'Perlu diperbaiki',

            default => 'Sudah dikirim',
        };

        // =====================================
        // TAHAP 3: VERIFIKASI ADMIN
        // =====================================

        $verifikasiSelesai =
            $status === 'terverifikasi'
            ||
            $sudahDiumumkan;

        $statusVerifikasi = match ($status) {

            'diajukan' => 'Menunggu Admin',

            'perlu_perbaikan' => 'Perlu perbaikan',

            'terverifikasi' => 'Terverifikasi',

            'ditolak' => 'Tidak lolos verifikasi',

            default => 'Belum dimulai',
        };

        // Keputusan yang sudah resmi diumumkan
        // menunjukkan peserta sebelumnya
        // sudah memenuhi tahapan seleksi.

        if (
            $sudahDiumumkan &&
            $statusVerifikasi !== 'Terverifikasi'
        ) {
            $statusVerifikasi =
                'Selesai pada proses seleksi';
        }

        // =====================================
        // TAHAP 4: PERHITUNGAN AHP-WASPAS
        // =====================================
        //
        // Jika keputusan sudah diumumkan,
        // berarti peserta telah melewati proses
        // penilaian WASPAS yang diarsipkan.
        //
        // Jika belum diumumkan, jangan
        // mengklaim proses sudah selesai.
        // =====================================

        if ($sudahDiumumkan) {

            $statusWaspas =
                'Selesai diproses dan ditetapkan';

        } elseif ($status === 'terverifikasi') {

            $statusWaspas =
                'Menunggu hasil seleksi resmi';

        } else {

            $statusWaspas =
                'Belum selesai';
        }

        // =====================================
        // TAHAP 5: PENGUMUMAN PENERIMA
        // =====================================

        $statusPengumuman = $sudahDiumumkan
            ? $infoKeputusan['judul']
            : 'Belum diumumkan';

        // =====================================
        // DATA TAHAPAN SELEKSI
        // =====================================
        //
        // "selesai" digunakan oleh Blade
        // untuk menampilkan centang hijau.
        //
        // "hasil" menandai tahap keputusan
        // akhir yang sudah dipublikasikan.
        // =====================================

        $tahapan = [

            [
                'nama' => 'Akun dibuat',
                'status' => 'Selesai',
                'selesai' => true,
                'hasil' => false,
            ],

            [
                'nama' => 'Formulir dan berkas',
                'status' => $statusForm,
                'selesai' => $formulirSelesai,
                'hasil' => false,
            ],

            [
                'nama' => 'Verifikasi Admin',
                'status' => $statusVerifikasi,
                'selesai' => $verifikasiSelesai,
                'hasil' => false,
            ],

            [
                'nama' => 'Perhitungan AHP–WASPAS',
                'status' => $statusWaspas,
                'selesai' => $sudahDiumumkan,
                'hasil' => false,
            ],

            [
                'nama' => 'Pengumuman penerima',
                'status' => $statusPengumuman,
                'selesai' => $sudahDiumumkan,
                'hasil' => $sudahDiumumkan,
            ],
        ];

        // =====================================
        // KIRIM DATA KE DASHBOARD USER
        // =====================================

        return view(
            'user.dashboard',
            compact(
                'akun',
                'pengajuan',
                'status',
                'infoStatus',
                'dokumen',
                'jumlahWajib',
                'jumlahTerunggah',
                'tahapan',
                'hasilAkhir',
                'keputusanFinal',
                'sudahDiumumkan',
                'infoKeputusan'
            )
        );
    }

    // =========================================
    // DOWNLOAD DOKUMEN USER
    // =========================================

    public function unduh(
        Request $request,
        string $jenis
    ): StreamedResponse {

        $this->periksaRole($request);

        $jenisDiizinkan = [
            'kk',
            'penghasilan',
            'rapor',
            'sertifikat',
        ];

        abort_unless(
            in_array(
                $jenis,
                $jenisDiizinkan,
                true
            ),
            404
        );

        $idAkun = (int) $request->user()->id;

        $punyaPengajuan = DB::table(
            'pengajuan_beasiswa'
        )
            ->where('akun_id', $idAkun)
            ->exists();

        abort_unless(
            $punyaPengajuan,
            404
        );

        $lokasi = $this->cariDokumen(
            $idAkun,
            $jenis
        );

        if ($lokasi === null) {
            abort(
                404,
                'Berkas belum tersimpan.'
            );
        }

        $ext = strtolower(
            pathinfo(
                $lokasi,
                PATHINFO_EXTENSION
            )
        );

        $mimeDiizinkan = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        abort_unless(
            isset($mimeDiizinkan[$ext]),
            404,
            'Format berkas tidak didukung.'
        );

        // =====================================
        // VALIDASI MIME ASLI
        // =====================================

        $mimeAsli = (
            new \finfo(FILEINFO_MIME_TYPE)
        )->file($lokasi);

        if (
            $mimeAsli !==
            $mimeDiizinkan[$ext]
        ) {
            abort(
                422,
                'Isi berkas tidak sesuai formatnya. Jangan hapus berkas asli.'
            );
        }

        // =====================================
        // VALIDASI FILE GAMBAR
        // =====================================

        if (in_array(
            $ext,
            ['jpg', 'png'],
            true
        )) {

            $informasiGambar = @getimagesize(
                $lokasi
            );

            if ($informasiGambar === false) {

                abort(
                    422,
                    'File gambar di penyimpanan tidak valid. Periksa berkas asli.'
                );
            }
        }

        // =====================================
        // MENGAMBIL UKURAN BERKAS
        // =====================================

        clearstatcache(true, $lokasi);

        $ukuran = filesize($lokasi);

        if (
            $ukuran === false ||
            $ukuran < 1
        ) {
            abort(
                404,
                'Ukuran berkas tidak valid.'
            );
        }

        // =====================================
        // DOWNLOAD AMAN MILIK USER
        // =====================================

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

            $jenis . '.' . $ext,

            [
                'Content-Type' =>
                    $mimeDiizinkan[$ext],

                'Content-Length' =>
                    (string) $ukuran,

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'X-Content-Type-Options' =>
                    'nosniff',
            ]
        );
    }
}
