<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CalonPenerimaController extends Controller
{
    private const KODE_DATA_AWAL = [
        'A1',
        'A2',
        'A3',
        'A4',
        'A5',
    ];

    // Kode PG-ID khusus untuk pengajuan User.
    private function kodePengajuan(string $kode): bool
    {
        return preg_match(
            '/^PG-[1-9][0-9]*$/D',
            strtoupper($kode)
        ) === 1;
    }

    private function wajibAdmin(Request $request): void
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );
    }

    private function pastikanBukanDataAwal(
        object $calon
    ): void {

        abort_if(
            in_array(
                $calon->kode,
                self::KODE_DATA_AWAL,
                true
            )
            ||
            $this->kodePengajuan(
                (string) $calon->kode
            ),
            403,
            'Data A1–A5 dan alternatif PG-... dari pengajuan User dilindungi.'
        );
    }

    private function validasi(
        Request $request,
        ?int $id = null
    ): array {

        // Rapikan kode dan nama
        foreach (['kode', 'nama'] as $kolom) {

            $nilai = $request->input($kolom);

            if (is_string($nilai)) {

                $request->merge([
                    $kolom => $kolom === 'kode'
                        ? mb_strtoupper(trim($nilai))
                        : trim($nilai),
                ]);
            }
        }

        $kodeUnik = Rule::unique(
            'calon_penerima',
            'kode'
        );

        if ($id !== null) {
            $kodeUnik->ignore($id);
        }

        return $request->validate(
            [
                'kode' => [
                    'required',
                    'string',
                    'max:20',
                    $kodeUnik,

                    // Kode PG-ID tidak dapat
                    // dibuat dari formulir manual.
                    function (
                        string $attribute,
                        mixed $value,
                        \Closure $fail
                    ): void {

                        if (
                            is_string($value) &&
                            $this->kodePengajuan($value)
                        ) {

                            $fail(
                                'Kode PG-ID dikhususkan untuk pengajuan User terverifikasi.'
                            );
                        }
                    },
                ],

                'nama' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'penghasilan_orang_tua' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'jumlah_tanggungan' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:65535',
                ],

                'nilai_akademik' => [
                    'required',
                    'numeric',
                    'decimal:0,2',
                    'min:0',
                    'max:100',
                ],

                'kondisi_tempat_tinggal' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:5',
                ],

                'prestasi_non_akademik' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:5',
                ],
            ],
            [
                'kode.required' =>
                    'Kode alternatif wajib diisi.',

                'kode.max' =>
                    'Kode alternatif maksimal 20 karakter.',

                'kode.unique' =>
                    'Kode alternatif sudah digunakan.',

                'nama.required' =>
                    'Nama calon penerima wajib diisi.',

                'nama.max' =>
                    'Nama calon penerima maksimal 100 karakter.',

                'penghasilan_orang_tua.required' =>
                    'Penghasilan orang tua wajib diisi.',

                'penghasilan_orang_tua.integer' =>
                    'Penghasilan orang tua harus berupa bilangan bulat.',

                'penghasilan_orang_tua.min' =>
                    'Penghasilan orang tua harus lebih besar dari 0.',

                'jumlah_tanggungan.required' =>
                    'Jumlah tanggungan wajib diisi.',

                'jumlah_tanggungan.min' =>
                    'Jumlah tanggungan tidak boleh kurang dari 0.',

                'jumlah_tanggungan.max' =>
                    'Jumlah tanggungan melebihi batas kolom database.',

                'nilai_akademik.required' =>
                    'Nilai akademik wajib diisi.',

                'nilai_akademik.decimal' =>
                    'Nilai akademik maksimal memiliki 2 angka desimal.',

                'nilai_akademik.min' =>
                    'Nilai akademik minimal 0.',

                'nilai_akademik.max' =>
                    'Nilai akademik maksimal 100.',

                'kondisi_tempat_tinggal.required' =>
                    'Skor kondisi tempat tinggal wajib diisi.',

                'kondisi_tempat_tinggal.min' =>
                    'Skor kondisi tempat tinggal minimal 1.',

                'kondisi_tempat_tinggal.max' =>
                    'Skor kondisi tempat tinggal maksimal 5.',

                'prestasi_non_akademik.required' =>
                    'Skor prestasi non-akademik wajib diisi.',

                'prestasi_non_akademik.min' =>
                    'Skor prestasi non-akademik minimal 1.',

                'prestasi_non_akademik.max' =>
                    'Skor prestasi non-akademik maksimal 5.',
            ]
        );
    }

    // =========================================
    // HALAMAN DAFTAR ALTERNATIF
    // =========================================

    public function index(Request $request): View
    {
        $this->wajibAdmin($request);

        $cari = $request->query('cari', '');

        if (!is_string($cari)) {
            $cari = '';
        }

        $cari = trim($cari);

        $query = DB::table('calon_penerima');

        if ($cari !== '') {

            $query->where(
                function ($subQuery) use ($cari): void {

                    $subQuery
                        ->where(
                            'kode',
                            'like',
                            '%' . $cari . '%'
                        )
                        ->orWhere(
                            'nama',
                            'like',
                            '%' . $cari . '%'
                        );
                }
            );
        }

        $jumlahHasil = (clone $query)->count();

        $totalData = DB::table(
            'calon_penerima'
        )->count();

        $calonPenerima = $query
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        $edit = null;
        $editId = $request->query('edit');

        if ($editId !== null) {

            abort_unless(
                is_string($editId) &&
                ctype_digit($editId),
                404
            );

            $edit = DB::table('calon_penerima')
                ->where('id', (int) $editId)
                ->firstOrFail();

            $this->pastikanBukanDataAwal($edit);
        }

        return view(
            'admin.calon-penerima',
            compact(
                'cari',
                'jumlahHasil',
                'totalData',
                'calonPenerima',
                'edit'
            )
        );
    }

    // =========================================
    // TAMBAH ALTERNATIF MANUAL
    // =========================================

    public function store(
        Request $request
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $data = $this->validasi($request);

        $waktu = now();

        DB::table('calon_penerima')->insert(
            $data + [
                'created_at' => $waktu,
                'updated_at' => $waktu,
            ]
        );

        return redirect()
            ->route('admin.calon-penerima.index')
            ->with(
                'sukses',
                'Data calon penerima berhasil ditambahkan.'
            );
    }

    // =========================================
    // EDIT ALTERNATIF MANUAL
    // =========================================

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $calon = DB::table('calon_penerima')
            ->where('id', $id)
            ->firstOrFail();

        $this->pastikanBukanDataAwal($calon);

        $data = $this->validasi(
            $request,
            $id
        );

        DB::table('calon_penerima')
            ->where('id', $id)
            ->update(
                $data + [
                    'updated_at' => now(),
                ]
            );

        return redirect()
            ->route('admin.calon-penerima.index')
            ->with(
                'sukses',
                'Data calon penerima berhasil diperbarui.'
            );
    }

    // =========================================
    // HAPUS ALTERNATIF MANUAL
    // =========================================

    public function destroy(
        Request $request,
        int $id
    ): RedirectResponse {

        $this->wajibAdmin($request);

        $calon = DB::table('calon_penerima')
            ->where('id', $id)
            ->firstOrFail();

        $this->pastikanBukanDataAwal($calon);

        DB::table('calon_penerima')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('admin.calon-penerima.index')
            ->with(
                'sukses',
                'Data calon penerima berhasil dihapus.'
            );
    }
}
