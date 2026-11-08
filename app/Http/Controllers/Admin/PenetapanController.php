<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PenetapanBeasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenetapanController extends Controller
{
    public function draf(
        Request $request,
        PenetapanBeasiswa $layanan
    ): RedirectResponse {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        $data = $request->validate([
            'arsip_id' => ['required', 'integer', 'min:1'],
            'kuota' => ['required', 'integer', 'min:1'],
            'jumlah_cadangan' => ['required', 'integer', 'min:0'],
        ]);

        $layanan->simpanDraf(
            (int) $data['arsip_id'],
            (int) $data['kuota'],
            (int) $data['jumlah_cadangan'],
            (int) $request->user()->id
        );

        return redirect()
            ->route(
                'admin.hasil.index',
                ['arsip' => $data['arsip_id']]
            )
            ->with(
                'sukses',
                'Draf penetapan berhasil disimpan. Hasil belum diumumkan kepada pendaftar.'
            );
    }

    public function umumkan(
        Request $request,
        PenetapanBeasiswa $layanan
    ): RedirectResponse {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        $data = $request->validate([
            'arsip_id' => ['required', 'integer', 'min:1'],
        ]);

        $layanan->umumkan(
            (int) $data['arsip_id']
        );

        return redirect()
            ->route(
                'admin.hasil.index',
                ['arsip' => $data['arsip_id']]
            )
            ->with(
                'sukses',
                'Hasil seleksi resmi diumumkan. Setiap pendaftar dapat melihat keputusan pada Status Pengajuan.'
            );
    }
}
