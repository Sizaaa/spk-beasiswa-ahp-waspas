<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class PengajuanController extends Controller
{
    // Kolom pada tabel pengajuan_beasiswa
    private const KOLOM = [
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

    // Dokumen pengajuan
    private const DOKUMEN = [
        'kk' => 'Kartu Keluarga',
        'penghasilan' => 'Bukti Penghasilan / SKTM',
        'rapor' => 'Rapor Terakhir',
        'sertifikat' => 'Sertifikat Prestasi',
    ];

    // Memastikan hanya User yang dapat mengakses
    private function wajibUser(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'user',
            403
        );
    }

    // Menggunakan folder berkas lama
    // C:\laragon\var\spk_beasiswa_berkas
    private function folderAkun(int $idAkun): string
    {
        return dirname(base_path(), 2)
            . DIRECTORY_SEPARATOR . 'var'
            . DIRECTORY_SEPARATOR . 'spk_beasiswa_berkas'
            . DIRECTORY_SEPARATOR . 'akun_' . $idAkun;
    }

    // Mencari dokumen yang sudah tersimpan
    private function lokasiDokumen(
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
    // MENAMPILKAN FORMULIR USER
    // =========================================

    public function index(Request $request): View
    {
        $this->wajibUser($request);

        $akun = $request->user();

        // Mengambil pengajuan milik akun sendiri
        $pengajuan = DB::table('pengajuan_beasiswa')
            ->where('akun_id', $akun->id)
            ->first();

        $status = $pengajuan?->status
            ?? 'belum_mengisi';

        // Status yang mengizinkan perubahan data
        $bolehEdit = in_array(
            $status,
            [
                'belum_mengisi',
                'draf',
                'perlu_perbaikan',
            ],
            true
        );

        // Mengisi formulir dengan data lama
        $form = [];

        foreach (self::KOLOM as $kolom) {
            $form[$kolom] = $pengajuan?->{$kolom} ?? '';
        }

        if ($form['nama_lengkap'] === '') {
            $form['nama_lengkap'] =
                $akun->nama_lengkap ?: $akun->username;
        }

        // Memeriksa dokumen tersimpan
        $dokumen = [];

        foreach (self::DOKUMEN as $jenis => $nama) {

            $dokumen[$jenis] = [
                'nama' => $nama,
                'wajib' => $jenis !== 'sertifikat',
                'ada' => $pengajuan !== null
                    && $this->lokasiDokumen(
                        (int) $akun->id,
                        $jenis
                    ) !== null,
            ];
        }

        return view('user.pengajuan', compact(
            'pengajuan',
            'status',
            'bolehEdit',
            'form',
            'dokumen'
        ));
    }

    // =========================================
    // SIMPAN DRAF / KIRIM PENDAFTARAN
    // =========================================

    public function simpan(
        Request $request
    ): RedirectResponse {

        $this->wajibUser($request);

        $idAkun = (int) $request->user()->id;

        // Memeriksa pengajuan yang sudah ada
        $pengajuan = DB::table('pengajuan_beasiswa')
            ->where('akun_id', $idAkun)
            ->first();

        // Pengajuan yang sudah diproses tidak
        // bisa diubah sembarangan
        if (
            $pengajuan &&
            !in_array(
                $pengajuan->status,
                ['draf', 'perlu_perbaikan'],
                true
            )
        ) {
            return redirect()
                ->route('user.pengajuan')
                ->withErrors([
                    'aksi' =>
                        'Pengajuan sudah dikirim atau diproses Admin sehingga tidak dapat diubah.',
                ]);
        }

        $kirim = $request->input('aksi') === 'kirim';

        // Draf boleh belum lengkap,
        // sedangkan pengiriman wajib lengkap
        $wajib = $kirim ? 'required' : 'nullable';

        // Mengizinkan tanda koma pada nilai rapor
        if (
            is_string(
                $request->input('nilai_akademik')
            )
        ) {
            $request->merge([
                'nilai_akademik' => str_replace(
                    ',',
                    '.',
                    $request->input('nilai_akademik')
                ),
            ]);
        }

        // NISN tidak boleh digunakan dua pendaftar
        $aturanNisn = Rule::unique(
            'pengajuan_beasiswa',
            'nisn'
        );

        if ($pengajuan) {
            $aturanNisn->ignore($pengajuan->id);
        }

        // =====================================
        // VALIDASI SERVER LARAVEL
        // =====================================

        $data = $request->validate(
            [
                'aksi' => [
                    'required',
                    Rule::in(['draf', 'kirim']),
                ],

                'nama_lengkap' => [
                    $wajib,
                    'string',
                    'max:100',
                ],

                'nisn' => [
                    $wajib,
                    'digits:10',
                    $aturanNisn,
                ],

                'asal_sekolah' => [
                    $wajib,
                    'string',
                    'max:150',
                ],

                'kelas' => [
                    $wajib,
                    'string',
                    'max:20',
                ],

                'nomor_hp' => [
                    $wajib,
                    'regex:/\A[0-9+(). \-]{8,20}\z/',
                ],

                'alamat_lengkap' => [
                    $wajib,
                    'string',
                    'max:3000',
                ],

                'pekerjaan_orang_tua' => [
                    $wajib,
                    'string',
                    'max:100',
                ],

                'penghasilan_orang_tua' => [
                    $wajib,
                    'integer',
                    'min:1',
                    'max:1000000000000',
                ],

                'jumlah_tanggungan' => [
                    $wajib,
                    'integer',
                    'min:0',
                    'max:50',
                ],

                'kondisi_tempat_tinggal' => [
                    $wajib,
                    'integer',
                    'between:1,5',
                ],

                'nilai_akademik' => [
                    $wajib,
                    'numeric',
                    'between:0,100',
                    'regex:/\A\d{1,3}(\.\d{1,2})?\z/',
                ],

                'prestasi_non_akademik' => [
                    $wajib,
                    'integer',
                    'between:1,5',
                ],

                // Berkas maksimal 2 MB
                'kk' => [
                    'nullable',
                    'file',
                    'mimetypes:application/pdf,image/jpeg,image/png',
                    'max:2048',
                ],

                'penghasilan' => [
                    'nullable',
                    'file',
                    'mimetypes:application/pdf,image/jpeg,image/png',
                    'max:2048',
                ],

                'rapor' => [
                    'nullable',
                    'file',
                    'mimetypes:application/pdf,image/jpeg,image/png',
                    'max:2048',
                ],

                'sertifikat' => [
                    'nullable',
                    'file',
                    'mimetypes:application/pdf,image/jpeg,image/png',
                    'max:2048',
                ],

                'setuju' => [
                    $kirim ? 'accepted' : 'nullable',
                ],
            ],
            [
                'aksi.in' =>
                    'Tindakan tidak dikenal.',

                'required' =>
                    ':attribute wajib diisi sebelum dikirim.',

                'nisn.digits' =>
                    'NISN harus berisi tepat 10 angka.',

                'nisn.unique' =>
                    'NISN sudah digunakan oleh pengajuan lain.',

                'nomor_hp.regex' =>
                    'Nomor HP tidak sesuai format.',

                'nilai_akademik.regex' =>
                    'Nilai akademik maksimal dua angka di belakang koma.',

                'integer' =>
                    ':attribute harus berupa angka bulat.',

                'numeric' =>
                    ':attribute harus berupa angka.',

                'between' =>
                    ':attribute berada di luar rentang yang diperbolehkan.',

                'mimetypes' =>
                    'Berkas harus berupa PDF, JPG, atau PNG.',

                'file' =>
                    'Berkas yang dipilih tidak valid.',

                'max' =>
                    ':attribute melebihi batas ukuran atau panjang.',

                'setuju.accepted' =>
                    'Centang pernyataan kebenaran data sebelum mengirim.',
            ],
            [
                'nama_lengkap' => 'Nama lengkap',
                'nisn' => 'NISN',
                'asal_sekolah' => 'Asal sekolah',
                'kelas' => 'Kelas',
                'nomor_hp' => 'Nomor HP',
                'alamat_lengkap' => 'Alamat lengkap',
                'pekerjaan_orang_tua' =>
                    'Pekerjaan orang tua',
                'penghasilan_orang_tua' =>
                    'Penghasilan orang tua',
                'jumlah_tanggungan' =>
                    'Jumlah tanggungan',
                'kondisi_tempat_tinggal' =>
                    'Kondisi tempat tinggal',
                'nilai_akademik' => 'Nilai akademik',
                'prestasi_non_akademik' =>
                    'Prestasi non-akademik',
            ]
        );

        // =====================================
        // MEMERIKSA TIGA DOKUMEN WAJIB
        // =====================================

        if ($kirim) {

            foreach (
                ['kk', 'penghasilan', 'rapor'] as $jenis
            ) {

                if (
                    !$request->hasFile($jenis) &&
                    $this->lokasiDokumen(
                        $idAkun,
                        $jenis
                    ) === null
                ) {

                    throw ValidationException::withMessages([
                        $jenis =>
                            self::DOKUMEN[$jenis]
                            . ' wajib diunggah sebelum pengajuan dikirim.',
                    ]);
                }
            }
        }

        // Mengambil kolom yang boleh disimpan
        $isi = [];

        foreach (self::KOLOM as $kolom) {
            $isi[$kolom] = $data[$kolom] ?? null;
        }

        // =====================================
        // MENYIMPAN DATA DAN BERKAS
        // =====================================

        try {

            DB::transaction(function () use (
                $request,
                $idAkun,
                $kirim,
                $isi
            ) {

                // Memeriksa ulang status dari database
                $sekarang = DB::table('pengajuan_beasiswa')
                    ->where('akun_id', $idAkun)
                    ->lockForUpdate()
                    ->first();

                if (
                    $sekarang &&
                    !in_array(
                        $sekarang->status,
                        ['draf', 'perlu_perbaikan'],
                        true
                    )
                ) {

                    throw new \RuntimeException(
                        'Status pengajuan telah berubah.'
                    );
                }

                // =================================
                // MENYIMPAN FILE DOKUMEN
                // =================================

                $jenisBerkas = array_keys(self::DOKUMEN);

                $adaUnggahan = false;

                foreach ($jenisBerkas as $jenis) {

                    if ($request->hasFile($jenis)) {
                        $adaUnggahan = true;
                        break;
                    }
                }

                if ($adaUnggahan) {

                    $folder = $this->folderAkun($idAkun);

                    if (
                        !is_dir($folder) &&
                        !mkdir($folder, 0750, true) &&
                        !is_dir($folder)
                    ) {

                        throw new \RuntimeException(
                            'Folder dokumen tidak bisa dibuat.'
                        );
                    }

                    foreach ($jenisBerkas as $jenis) {

                        if (!$request->hasFile($jenis)) {
                            continue;
                        }

                        $file = $request->file($jenis);

                        // Ekstensi ditentukan berdasarkan
                        // tipe file, bukan nama dari User
                        $ext = match ($file->getMimeType()) {

                            'application/pdf' => 'pdf',
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',

                            default => throw new \RuntimeException(
                                'Format dokumen tidak didukung.'
                            ),
                        };

                        $namaFile = $jenis . '.' . $ext;

                        $file->move(
                            $folder,
                            $namaFile
                        );

                        // Hapus versi ekstensi lama jika
                        // pengguna mengganti dokumen
                        foreach (
                            ['pdf', 'jpg', 'png'] as $lama
                        ) {

                            $lokasiLama = $folder
                                . DIRECTORY_SEPARATOR
                                . $jenis . '.' . $lama;

                            if (
                                $lama !== $ext &&
                                is_file($lokasiLama)
                            ) {

                                unlink($lokasiLama);
                            }
                        }
                    }
                }

                // =================================
                // STATUS DAN WAKTU
                // =================================

                $isi['updated_at'] = now();

                $isi['status'] = $kirim
                    ? 'diajukan'
                    : (
                        $sekarang?->status === 'perlu_perbaikan'
                            ? 'perlu_perbaikan'
                            : 'draf'
                    );

                if ($kirim) {
                    $isi['diajukan_pada'] = now();
                }

                // =================================
                // INSERT ATAU UPDATE
                // =================================

                if ($sekarang) {

                    // Memperbarui pengajuan yang ada
                    DB::table('pengajuan_beasiswa')
                        ->where('akun_id', $idAkun)
                        ->update($isi);

                } else {

                    // Membuat draf pertama
                    $isi['akun_id'] = $idAkun;
                    $isi['created_at'] = now();

                    DB::table('pengajuan_beasiswa')
                        ->insert($isi);
                }
            });

        } catch (Throwable $e) {

            // Kesalahan disimpan dalam log Laravel
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'aksi' =>
                        'Pengajuan gagal disimpan. Periksa kembali data dan coba lagi. Berkas sebelumnya tidak perlu dihapus.',
                ]);
        }

        // =====================================
        // NOTIFIKASI BERHASIL
        // =====================================

        return redirect()
            ->route('user.pengajuan')
            ->with(
                'sukses',
                $kirim
                    ? 'Pengajuan berhasil dikirim dan menunggu verifikasi Admin.'
                    : 'Draf berhasil disimpan.'
            );
    }
}
