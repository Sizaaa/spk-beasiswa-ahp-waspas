<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HalamanController extends Controller
{
    private function wajibUser(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'user',
            403
        );
    }

    // STATUS PENGAJUAN
    public function status(Request $request): View
    {
        $this->wajibUser($request);

        $pengajuan = DB::table('pengajuan_beasiswa')
            ->where('akun_id', $request->user()->id)
            ->first();

        $daftarStatus = [
            'belum_mengisi' => [
                'nama' => 'Belum Mengajukan',
                'penjelasan' =>
                    'Kamu belum memiliki pengajuan. Mulai isi formulir untuk mendaftar.',
                'warna' => 'bg-slate-100 text-slate-700',
            ],

            'draf' => [
                'nama' => 'Draf Tersimpan',
                'penjelasan' =>
                    'Pengajuan tersimpan tetapi belum dikirim untuk diperiksa Admin.',
                'warna' => 'bg-amber-100 text-amber-800',
            ],

            'diajukan' => [
                'nama' => 'Menunggu Verifikasi',
                'penjelasan' =>
                    'Pengajuan sudah dikirim dan sedang menunggu pemeriksaan Admin.',
                'warna' => 'bg-blue-100 text-blue-800',
            ],

            'perlu_perbaikan' => [
                'nama' => 'Perlu Perbaikan',
                'penjelasan' =>
                    'Admin meminta kamu memperbaiki data atau dokumen. Baca catatan di bawah.',
                'warna' => 'bg-amber-100 text-amber-800',
            ],

            'terverifikasi' => [
                'nama' => 'Terverifikasi',
                'penjelasan' =>
                    'Pengajuan lolos pemeriksaan administrasi. Ini bukan keputusan akhir penerimaan beasiswa.',
                'warna' => 'bg-emerald-100 text-emerald-800',
            ],

            'ditolak' => [
                'nama' => 'Ditolak pada Verifikasi',
                'penjelasan' =>
                    'Pengajuan tidak lolos pemeriksaan administrasi. Lihat catatan Admin jika tersedia.',
                'warna' => 'bg-red-100 text-red-800',
            ],
        ];

        $status = $pengajuan?->status
            ?? 'belum_mengisi';

        $info = $daftarStatus[$status] ?? [
            'nama' => 'Status Tidak Dikenali',
            'penjelasan' =>
                'Hubungi administrator untuk informasi lebih lanjut.',
            'warna' => 'bg-slate-100 text-slate-700',
        ];

        return view('user.status', compact(
            'pengajuan',
            'status',
            'info'
        ));
    }

    // PROFIL USER
    public function profil(Request $request): View
    {
        $this->wajibUser($request);

        return view('user.profil', [
            'akun' => $request->user(),
        ]);
    }

    // PANDUAN USER
    public function panduan(Request $request): View
    {
        $this->wajibUser($request);

        return view('user.panduan');
    }
}
