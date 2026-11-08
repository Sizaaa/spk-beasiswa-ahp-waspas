<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // =========================================
    // DASHBOARD KHUSUS ADMIN
    // =========================================

    public function index(Request $request): View
    {
        // Memastikan akun yang login adalah Admin
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        // Mengambil informasi Admin
        $akun = $request->user();

        // =====================================
        // STATISTIK PENGAJUAN USER
        // =====================================

        // Draf tidak dihitung sebagai pengajuan masuk
        $totalPengajuan = DB::table('pengajuan_beasiswa')
            ->where('status', '<>', 'draf')
            ->count();

        $menunggu = DB::table('pengajuan_beasiswa')
            ->where('status', 'diajukan')
            ->count();

        $terverifikasi = DB::table('pengajuan_beasiswa')
            ->where('status', 'terverifikasi')
            ->count();

        $perluPerbaikan = DB::table('pengajuan_beasiswa')
            ->where('status', 'perlu_perbaikan')
            ->count();

        $ditolak = DB::table('pengajuan_beasiswa')
            ->where('status', 'ditolak')
            ->count();

        // =====================================
        // STATISTIK DATA ALTERNATIF WASPAS
        // =====================================

        $totalAlternatif = DB::table('calon_penerima')
            ->count();

        // Alternatif contoh dari jurnal
        $dataJurnal = DB::table('calon_penerima')
            ->whereIn('kode', [
                'A1',
                'A2',
                'A3',
                'A4',
                'A5',
            ])
            ->count();

        $dataTambahan = $totalAlternatif - $dataJurnal;

        // Target data tugas
        $targetData = 30;

        $sisaData = max(
            0,
            $targetData - $totalAlternatif
        );

        $persentase = min(
            100,
            ($totalAlternatif / $targetData) * 100
        );

        // =====================================
        // PENGAJUAN TERBARU
        // =====================================

        // Mengambil beberapa pengajuan yang sudah
        // dikirim, bukan draf yang belum selesai.

        $pengajuanTerbaru = DB::table('pengajuan_beasiswa as p')
            ->join(
                'akun as a',
                'a.id',
                '=',
                'p.akun_id'
            )
            ->where('a.role', 'user')
            ->whereIn('p.status', [
                'diajukan',
                'perlu_perbaikan',
                'terverifikasi',
                'ditolak',
            ])
            ->select(
                'p.id',
                'p.nama_lengkap',
                'p.asal_sekolah',
                'p.status',
                'p.diajukan_pada',
                'a.username'
            )
            ->orderByDesc('p.diajukan_pada')
            ->orderByDesc('p.id')
            ->limit(6)
            ->get();

        // =====================================
        // INFORMASI STATUS
        // =====================================

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
        // MENAMPILKAN DASHBOARD ADMIN
        // =====================================

        return view('admin.dashboard', compact(
            'akun',
            'totalPengajuan',
            'menunggu',
            'terverifikasi',
            'perluPerbaikan',
            'ditolak',
            'totalAlternatif',
            'dataJurnal',
            'dataTambahan',
            'targetData',
            'sisaData',
            'persentase',
            'pengajuanTerbaru',
            'labelStatus',
            'warnaStatus'
        ));
    }
}
