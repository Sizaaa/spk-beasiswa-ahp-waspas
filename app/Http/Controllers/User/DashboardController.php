<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    // LOKASI PENYIMPANAN DOKUMEN
    // =========================================

    // Menggunakan folder yang sama dengan
    // website PHP Native sebelumnya.

    private function folderAkun(int $idAkun): string
    {
        return dirname(base_path(), 2)
            . DIRECTORY_SEPARATOR . 'var'
            . DIRECTORY_SEPARATOR . 'spk_beasiswa_berkas'
            . DIRECTORY_SEPARATOR . 'akun_' . $idAkun;
    }

    // =========================================
    // MENCARI DOKUMEN TERSIMPAN
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
    // MENAMPILKAN DASHBOARD USER
    // =========================================

    public function index(Request $request): View
    {
        $this->periksaRole($request);

        // Mengambil akun yang sedang login
        $akun = $request->user();

        // Mengambil pengajuan milik akun sendiri
        $pengajuan = DB::table('pengajuan_beasiswa')
            ->where('akun_id', $akun->id)
            ->first();

        $status = $pengajuan?->status
            ?? 'belum_mengisi';

        // =====================================
        // KETERANGAN STATUS
        // =====================================

        $daftarStatus = [

            'belum_mengisi' => [
                'judul' => 'Belum mengajukan',
                'keterangan' =>
                    'Kamu belum mempunyai pengajuan beasiswa.',
                'warna' => 'bg-slate-100 text-slate-700',
            ],

            'draf' => [
                'judul' => 'Draf tersimpan',
                'keterangan' =>
                    'Pengajuan belum dikirim untuk diperiksa Admin.',
                'warna' => 'bg-amber-100 text-amber-800',
            ],

            'diajukan' => [
                'judul' => 'Diajukan',
                'keterangan' =>
                    'Pengajuan sudah dikirim dan menunggu verifikasi Admin.',
                'warna' => 'bg-blue-100 text-blue-800',
            ],

            'perlu_perbaikan' => [
                'judul' => 'Perlu perbaikan',
                'keterangan' =>
                    'Admin meminta perbaikan pada pengajuanmu.',
                'warna' => 'bg-amber-100 text-amber-800',
            ],

            'terverifikasi' => [
                'judul' => 'Terverifikasi',
                'keterangan' =>
                    'Pengajuan lolos pemeriksaan administrasi, bukan berarti sudah diterima.',
                'warna' => 'bg-emerald-100 text-emerald-800',
            ],

            'ditolak' => [
                'judul' => 'Ditolak pada verifikasi',
                'keterangan' =>
                    'Pengajuan tidak lolos pemeriksaan administrasi.',
                'warna' => 'bg-red-100 text-red-800',
            ],
        ];

        $infoStatus = $daftarStatus[$status] ?? [
            'judul' => 'Status tidak dikenali',
            'keterangan' => 'Hubungi administrator.',
            'warna' => 'bg-slate-100 text-slate-700',
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
        // TAHAPAN SELEKSI
        // =====================================

        $statusForm = match ($status) {

            'belum_mengisi' => 'Belum dimulai',

            'draf' => 'Draf tersimpan',

            'perlu_perbaikan' => 'Perlu diperbaiki',

            default => 'Sudah dikirim',
        };

        $statusVerifikasi = match ($status) {

            'diajukan' => 'Menunggu Admin',

            'perlu_perbaikan' => 'Perlu perbaikan',

            'terverifikasi' => 'Terverifikasi',

            'ditolak' => 'Tidak lolos verifikasi',

            default => 'Belum dimulai',
        };

        $tahapan = [

            [
                'nama' => 'Akun dibuat',
                'status' => 'Selesai',
            ],

            [
                'nama' => 'Formulir dan berkas',
                'status' => $statusForm,
            ],

            [
                'nama' => 'Verifikasi Admin',
                'status' => $statusVerifikasi,
            ],

            [
                'nama' => 'Perhitungan AHP–WASPAS',
                'status' => 'Belum ditampilkan',
            ],

            [
                'nama' => 'Pengumuman penerima',
                'status' => 'Belum diumumkan',
            ],
        ];

        // =====================================
        // MENGIRIM DATA KE HALAMAN DASHBOARD
        // =====================================

        return view('user.dashboard', compact(
            'akun',
            'pengajuan',
            'status',
            'infoStatus',
            'dokumen',
            'jumlahWajib',
            'jumlahTerunggah',
            'tahapan'
        ));
    }

    // =========================================
    // DOWNLOAD DOKUMEN USER
    // VERSI PERBAIKAN
    // =========================================

    public function unduh(
        Request $request,
        string $jenis
    ): StreamedResponse {

        // Hanya User yang boleh mengunduh
        $this->periksaRole($request);

        // Jenis dokumen yang diizinkan
        $jenisDiizinkan = [
            'kk',
            'penghasilan',
            'rapor',
            'sertifikat',
        ];

        if (!in_array($jenis, $jenisDiizinkan, true)) {
            abort(404);
        }

        // Mengambil ID akun yang sedang login
        $idAkun = (int) $request->user()->id;

        // Memastikan pengajuan milik User tersedia
        $punyaPengajuan = DB::table('pengajuan_beasiswa')
            ->where('akun_id', $idAkun)
            ->exists();

        abort_unless($punyaPengajuan, 404);

        // Mengambil lokasi dokumen asli
        $lokasi = $this->cariDokumen(
            $idAkun,
            $jenis
        );

        if ($lokasi === null) {
            abort(404, 'Berkas belum tersimpan.');
        }

        // Mengambil ekstensi dokumen
        $ext = strtolower(
            pathinfo(
                $lokasi,
                PATHINFO_EXTENSION
            )
        );

        // =====================================
        // MEMERIKSA FORMAT FILE
        // =====================================

        $mimeDiizinkan = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        if (!isset($mimeDiizinkan[$ext])) {
            abort(404, 'Format berkas tidak didukung.');
        }

        // Memeriksa isi file sebenarnya
        // agar HTML atau error PHP tidak
        // dikirim sebagai gambar JPG.

        $mimeAsli = (
            new \finfo(FILEINFO_MIME_TYPE)
        )->file($lokasi);

        if ($mimeAsli !== $mimeDiizinkan[$ext]) {

            abort(
                422,
                'Isi berkas di penyimpanan tidak sesuai formatnya. Jangan hapus berkas asli.'
            );
        }

        // =====================================
        // MEMERIKSA FILE GAMBAR
        // =====================================

        // Untuk JPG dan PNG, pastikan file
        // masih dikenali sebagai gambar.

        if (in_array($ext, ['jpg', 'png'], true)) {

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
        // MENGAMBIL UKURAN FILE ASLI
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
        // PERBAIKAN UTAMA:
        // MENGIRIM FILE SECARA BERSIH
        // =====================================

        // Menggunakan streamDownload agar
        // file dikirim langsung sebagai data,
        // tanpa menampilkan halaman Blade.

        return response()->streamDownload(

            function () use ($lokasi): void {

                // Menghapus keluaran tambahan
                // sebelum mengirim isi dokumen.

                while (ob_get_level() > 0) {

                    if (!@ob_end_clean()) {

                        @ob_clean();

                        break;
                    }
                }

                // Mengirim isi file asli
                readfile($lokasi);
            },

            // Nama file hasil download
            $jenis . '.' . $ext,

            // Header download
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
