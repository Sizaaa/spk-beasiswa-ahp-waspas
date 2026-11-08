<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LingkupSeleksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LingkupSeleksiController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        $aktif = $request->session()->get(
            'spk_lingkup',
            LingkupSeleksi::ARTIKEL
        );

        return view(
            'admin.lingkup-seleksi',
            [
                'aktif' => $aktif,
                'pilihan' => LingkupSeleksi::PILIHAN,
            ]
        );
    }

    public function simpan(
        Request $request
    ): RedirectResponse {

        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        $data = $request->validate([
            'lingkup' => [
                'required',
                Rule::in(
                    array_keys(
                        LingkupSeleksi::PILIHAN
                    )
                ),
            ],
        ]);

        $request->session()->put(
            'spk_lingkup',
            $data['lingkup']
        );

        return redirect()
            ->route('admin.hasil.index')
            ->with(
                'sukses',
                'Lingkup perhitungan diubah menjadi: '
                . LingkupSeleksi::PILIHAN[
                    $data['lingkup']
                ]
                . '.'
            );
    }
}
