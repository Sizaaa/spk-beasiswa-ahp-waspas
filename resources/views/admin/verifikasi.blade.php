
@extends('layouts.admin')

@section('title', 'Verifikasi Pengajuan - Beasiswa Cerdas')
@section('judul-admin', 'Verifikasi Pengajuan')
@section('deskripsi-admin', 'Periksa data dan dokumen pendaftar sebelum memberikan keputusan.')

@push('styles')
<style>
    /* DESAIN A - RINGKAS PROFESIONAL */

    .vra-page {
        max-width: 1380px;
        margin: 0 auto;
        padding: 26px 26px 48px;
    }

    .vra-card {
        background: #fff;
        border: 1px solid #dae9e2;
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(16,63,54,.04);
        overflow: hidden;
        transition: box-shadow .2s, border-color .2s;
    }

    .vra-card:hover {
        border-color: #b9d9cc;
        box-shadow: 0 12px 24px rgba(16,63,54,.065);
    }

    /* RINGKASAN PENDAFTAR */

    .vra-top {
        padding: 23px 26px;
        background: linear-gradient(
            110deg, #eaf8f2, #f8fcfa
        );
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .vra-avatar {
        width: 65px;
        height: 65px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #c3edda;
        color: #0b5942;
        font-weight: 800;
        font-size: 24px;
    }

    .vra-top-main {
        flex: 1 1 260px;
        min-width: 0;
    }

    .vra-eyebrow {
        color: #247d63;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
    }

    .vra-name {
        color: #103f36;
        font-size: clamp(21px, 2.2vw, 29px);
        font-weight: 800;
        line-height: 1.25;
        margin: 6px 0 8px;
        overflow-wrap: anywhere;
    }

    .vra-top-sub {
        display: flex;
        gap: 6px 20px;
        flex-wrap: wrap;
        color: #506e63;
        font-size: 13px;
    }

    .vra-top-side {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .vra-muted {
        color: #657b85;
        font-size: 12px;
        line-height: 1.6;
    }

    .vra-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 750;
    }

    .vra-status-diajukan {
        background: #fff2d4;
        color: #875719;
    }

    .vra-status-perlu_perbaikan {
        background: #fff1dd;
        color: #925813;
    }

    .vra-status-terverifikasi {
        background: #def5e9;
        color: #126048;
    }

    .vra-status-ditolak {
        background: #ffebee;
        color: #982e3c;
    }

    /* DUA KOLOM, EMPAT BAGIAN */

    .vra-columns {
        display: grid;
        grid-template-columns: repeat(
            2,
            minmax(0, 1fr)
        );
        gap: 18px;
        align-items: start;
    }

    .vra-column {
        display: flex;
        flex-direction: column;
        gap: 18px;
        min-width: 0;
    }

    .vra-head {
        padding: 17px 21px 14px;
        border-bottom: 1px solid #e7efe9;
    }

    .vra-head h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.35;
        color: #103f36;
    }

    .vra-head p {
        margin: 5px 0 0;
        color: #657b85;
        font-size: 12px;
        line-height: 1.5;
    }

    .vra-accent {
        display: block;
        width: 48px;
        height: 3px;
        margin-top: 12px;
        border-radius: 9px;
        background: linear-gradient(
            90deg,
            #07986b,
            #83d6b0
        );
    }

    .vra-body {
        padding: 8px 21px 17px;
    }

    /*
       PERBAIKAN UTAMA:
       Label dan nilai berada pada baris sama.
       Tidak memakai dl/dt/dd agar tidak
       terpengaruh CSS lama.
    */

    .vra-pair {
        display: flex !important;
        align-items: flex-start !important;
        gap: 16px !important;
        padding: 11px 0 !important;
        border-bottom: 1px solid #edf2ef !important;
        min-height: 40px;
    }

    .vra-pair:last-child {
        border-bottom: 0 !important;
    }

    .vra-key {
        display: block !important;
        width: 42% !important;
        flex: 0 0 42% !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #607588;
        font-size: 12px;
        line-height: 1.6;
        text-align: left !important;
    }

    .vra-val {
        display: block !important;
        min-width: 0 !important;
        flex: 1 1 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #183e35;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
        text-align: left !important;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    .vra-criteria .vra-key {
        flex-basis: 67% !important;
        width: 67% !important;
        color: #285d4a;
    }

    .vra-criteria .vra-val {
        text-align: right !important;
        white-space: normal;
    }

    /* DOKUMEN */

    .vra-doc {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid #edf2ef;
    }

    .vra-doc:last-child {
        border-bottom: 0;
    }

    .vra-doc-name {
        color: #1a3e33;
        font-size: 13px;
        font-weight: 700;
    }

    .vra-doc-state {
        margin-top: 3px;
        font-size: 11px;
        color: #657b85;
    }

    .vra-doc-state.is-ready {
        color: #126348;
    }

    .vra-note {
        padding: 12px 15px;
        border-radius: 11px;
        border: 1px solid #dcece4;
        background: #f4faf7;
        color: #466e5c;
        font-size: 12px;
        line-height: 1.7;
    }

    /* TOMBOL PUTIH */

    .vra-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 41px;
        padding: 9px 16px;
        background: #fff;
        border: 1px solid #a6d0bf;
        border-radius: 11px;
        color: #105c46;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4;
        text-decoration: none;
        cursor: pointer;

        transition:
            transform .18s,
            background .18s,
            box-shadow .18s;
    }

    .vra-btn:hover:not(:disabled) {
        background: #f0faf5;
        box-shadow: 0 6px 17px rgba(18,94,69,.08);
        transform: translateY(-2px);
    }

    .vra-btn:active {
        transform: translateY(0) scale(.985);
    }

    .vra-btn:focus-visible {
        outline: 3px solid #b0dfcb;
        outline-offset: 2px;
    }

    .vra-btn-danger {
        border-color: #edc1c9;
        color: #a02c40;
        background: #fffafa;
    }

    .vra-btn-warning {
        border-color: #e9cfa5;
        color: #83561b;
        background: #fffdf7;
    }

    .vra-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 11px;
        padding-top: 16px;
    }

    /* FORM VERIFIKASI */

    .vra-input {
        display: block;
        width: 100%;
        min-height: 94px;
        padding: 12px 14px;
        margin-top: 8px;
        border: 1px solid #cbded6;
        border-radius: 11px;
        color: #234137;
        font-size: 13px;
        resize: vertical;
        outline: none;
    }

    .vra-input:focus {
        border-color: #168765;
        box-shadow: 0 0 0 3px rgba(22,135,101,.11);
    }

    .vra-error {
        margin-top: 7px;
        color: #b52e43;
        font-size: 12px;
    }

    .vra-error[hidden],
    .vra-modal[hidden],
    .vra-loading[hidden] {
        display: none !important;
    }

    .vra-invalid {
        outline: 2px solid rgba(219,61,81,.4) !important;
        outline-offset: 2px;
    }

    /* MODAL DAN BLUR SELURUH WEBSITE */

    .vra-modal {
        position: fixed !important;
        inset: 0 !important;
        z-index: 2147483000 !important;
        width: 100vw !important;
        height: 100dvh !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(8,30,24,.52);
        backdrop-filter: blur(7px);
        -webkit-backdrop-filter: blur(7px);
        overflow: auto;
        box-sizing: border-box;
    }

    .vra-dialog {
        width: min(100%, 465px);
        max-height: calc(100dvh - 32px);
        overflow-y: auto;
        padding: 31px 34px;
        border-radius: 23px;
        background: white;
        box-shadow: 0 24px 70px rgba(15,45,35,.2);
        animation: vra-show .2s ease;
    }

    .vra-dialog-rule {
        width: 55px;
        height: 4px;
        border-radius: 9px;
        background: linear-gradient(
            90deg,
            #07986b,
            #77cda8
        );
        margin-bottom: 20px;
    }

    .vra-dialog h2 {
        color: #0c4a3b;
        font-size: 24px;
        font-weight: 800;
        margin: 0;
    }

    .vra-dialog p {
        color: #64747a;
        font-size: 14px;
        line-height: 1.75;
        margin: 13px 0 0;
    }

    .vra-dialog-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap-reverse;
        gap: 10px;
        margin-top: 27px;
    }

    /* SKELETON SHIMMER */

    .vra-loading {
        position: fixed !important;
        z-index: 2147483001 !important;
        bottom: 18px;
        left: 50%;
        transform: translateX(-50%);
        width: min(850px, calc(100vw - 36px));
        padding: 19px 22px;
        background: #fff;
        border: 1px solid #dce9e4;
        border-radius: 15px;
        display: flex;
        gap: 18px;
        align-items: center;
        box-shadow: 0 14px 40px rgba(9,46,36,.13);
    }

    .vra-bars {
        flex: 1;
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .vra-bar {
        height: 14px;
        border-radius: 20px;
        background: linear-gradient(
            100deg,
            #e1efe9 20%,
            #f9fffc 45%,
            #d9ece3 65%
        );
        background-size: 220% 100%;
        animation: vra-glide 1.3s ease-in-out infinite;
    }

    @keyframes vra-show {
        from {
            opacity: 0;
            transform: translateY(11px) scale(.985);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    @keyframes vra-glide {
        from {
            background-position: 110% 0;
        }

        to {
            background-position: -110% 0;
        }
    }

    /* RESPONSIVE */

    @media (max-width: 1000px) {
        .vra-columns {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .vra-page {
            padding: 18px 14px 30px;
        }

        .vra-top {
            padding: 17px;
            gap: 12px;
        }

        .vra-avatar {
            width: 52px;
            height: 52px;
            font-size: 19px;
        }

        .vra-top-side {
            align-items: flex-start;
        }

        .vra-head {
            padding: 16px;
        }

        .vra-body {
            padding: 7px 16px 16px;
        }

        .vra-pair {
            gap: 9px !important;
        }

        .vra-key {
            width: 44% !important;
            flex-basis: 44% !important;
        }

        .vra-criteria .vra-key {
            width: 65% !important;
            flex-basis: 65% !important;
        }

        .vra-loading {
            display: block;
        }

        .vra-bars {
            margin-top: 13px;
        }

        .vra-dialog {
            padding: 25px 22px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .vra-btn,
        .vra-card {
            transition: none;
        }

        .vra-btn:hover {
            transform: none;
        }

        .vra-dialog,
        .vra-bar {
            animation: none;
        }
    }

/* BCC-COMPACT-ROWS-2026 */

/*
   BARIS DATA OTOMATIS
   - Data pendek: satu baris ringkas
   - Data panjang: membungkus teks otomatis
   - Tidak ada tinggi baris tetap
*/

.vra-card .vra-pair {
    display: grid !important;

    grid-template-columns:
        minmax(110px, 38%)
        minmax(0, 1fr) !important;

    align-items: start !important;

    gap: 12px !important;

    padding: 6px 0 !important;

    margin: 0 !important;

    min-height: 0 !important;

    height: auto !important;
}

/*
   LABEL DAN NILAI

   white-space: normal menghilangkan
   spasi/baris kosong dari indentasi Blade.
*/

.vra-card .vra-key,
.vra-card .vra-val {
    display: block !important;

    width: auto !important;

    min-width: 0 !important;

    flex: initial !important;

    flex-basis: auto !important;

    margin: 0 !important;

    padding: 0 !important;

    line-height: 1.45 !important;

    white-space: normal !important;

    overflow-wrap: anywhere;
}

.vra-card .vra-key {
    font-size: 12px;
    color: #607588;
}

.vra-card .vra-val {
    font-size: 13px;
    font-weight: 700;
    color: #183e35;
}

/*
   KHUSUS C1-C5:
   Label bisa panjang dan nilai tetap
   rapi di sisi kanan.
*/

.vra-card .vra-criteria .vra-pair {
    grid-template-columns:
        minmax(0, 1fr)
        minmax(65px, auto) !important;
}

.vra-card .vra-criteria .vra-val {
    text-align: right !important;
}

/*
   LAYAR KECIL:
   Baris tetap ringkas.
   Teks panjang menyesuaikan tinggi.
*/

@media (max-width: 650px) {

    .vra-card .vra-pair {
        grid-template-columns:
            minmax(90px, 40%)
            minmax(0, 1fr) !important;

        gap: 9px !important;

        padding: 6px 0 !important;
    }

    .vra-card .vra-criteria .vra-pair {
        grid-template-columns:
            minmax(0, 1fr)
            minmax(55px, auto) !important;
    }
}

</style>
@endpush

@section('admin-content')

@php
    $statistikKartu = [
        ['Seluruh Pengajuan', (int) $statistik->total],
        ['Menunggu Verifikasi', (int) $statistik->menunggu],
        ['Terverifikasi', (int) $statistik->terverifikasi],
        ['Perlu Perbaikan', (int) $statistik->perbaikan],
    ];
@endphp

<main class="vra-page">

    @if (session('sukses'))
        <div class="vra-note mb-5" role="status">
            {{ session('sukses') }}
        </div>
    @endif

    @if ($errors->any())
        <div
            class="mb-5 rounded-xl border border-red-200
                   bg-red-50 p-4 text-sm text-red-700"
            role="alert"
        >
            <strong>Periksa kembali informasi berikut:</strong>

            @foreach ($errors->all() as $pesan)
                <p class="mt-1">{{ $pesan }}</p>
            @endforeach
        </div>
    @endif

    @if (!$detail)

        {{-- DAFTAR PENGAJUAN --}}

        @if ($filterAktif)

            <section class="vra-card mb-5 p-5">
                <p class="vra-eyebrow">
                    Filter Pengajuan
                </p>

                <h2 class="mt-1 text-xl font-bold text-[#103f36]">
                    {{ $namaFilter }}
                </h2>

                <p class="vra-muted mt-1">
                    {{ $jumlahFilter }} pengajuan ditemukan.
                </p>

                <a
                    class="vra-btn mt-3"
                    href="{{ route('admin.verifikasi.index') }}"
                >
                    Tampilkan Semua
                </a>
            </section>

        @else

            <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">

                @foreach ($statistikKartu as $item)
                    <div class="vra-card px-5 py-4">
                        <p class="vra-muted">{{ $item[0] }}</p>

                        <strong class="mt-2 block text-2xl text-[#103f36]">
                            {{ $item[1] }}
                        </strong>
                    </div>
                @endforeach

            </div>

        @endif

        <section class="vra-card">

            <div
                class="vra-head flex flex-wrap
                       items-center justify-between gap-4"
            >
                <div>
                    <h2>Daftar Pengajuan Beasiswa</h2>
                    <p>
                        Pilih pendaftar untuk memeriksa
                        data dan dokumen.
                    </p>
                </div>

                <form
                    method="GET"
                    action="{{ route('admin.verifikasi.index') }}"
                    class="flex flex-wrap items-center gap-2"
                >
                    <label
                        for="vra-filter"
                        class="text-sm font-semibold"
                    >
                        Status
                    </label>

                    <select
                        id="vra-filter"
                        name="status"
                        class="rounded-xl border border-slate-300
                               bg-white px-3 py-2 text-sm"
                    >
                        <option
                            value=""
                            @selected($filter === '')
                        >
                            Semua Status
                        </option>

                        @foreach ($labelStatus as $kode => $label)
                            <option
                                value="{{ $kode }}"
                                @selected($filter === $kode)
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        class="vra-btn"
                        type="submit"
                    >
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto px-5 pb-5">

                <table class="w-full min-w-[640px] text-left text-sm">

                    <thead class="bg-[#f4f8f6] text-[#3c5e54]">
                        <tr>
                            <th class="p-3">Pendaftar</th>
                            <th class="p-3">NISN</th>
                            <th class="p-3">Tanggal Pengajuan</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($daftar as $item)

                            <tr class="hover:bg-[#f8fcfa]">

                                <td class="p-3">
                                    <strong class="text-[#123f34]">
                                        {{
                                            $item->nama_lengkap
                                                ?: $item->username
                                        }}
                                    </strong>

                                    <p class="vra-muted">
                                        {{ $item->asal_sekolah ?: '-' }}
                                    </p>
                                </td>

                                <td class="p-3">
                                    {{ $item->nisn ?: '-' }}
                                </td>

                                <td class="p-3">
                                    {{
                                        $item->diajukan_pada
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime($item->diajukan_pada)
                                            )
                                            : '-'
                                    }}
                                </td>

                                <td class="p-3">
                                    <span
                                        class="vra-badge vra-status-{{ $item->status }}"
                                    >
                                        {{
                                            $labelStatus[$item->status]
                                                ?? $item->status
                                        }}
                                    </span>
                                </td>

                                <td class="p-3">
                                    <a
                                        class="vra-btn"
                                        href="{{ route(
                                            'admin.verifikasi.index',
                                            ['detail' => $item->id]
                                        ) }}"
                                    >
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    class="p-8 text-center text-slate-500"
                                    colspan="5"
                                >
                                    Belum ada pengajuan yang sesuai.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($daftar->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $daftar->links() }}
                </div>
            @endif

        </section>

    @else

        {{-- DETAIL PENGAJUAN: DESAIN A --}}

        <a
            href="{{ route('admin.verifikasi.index') }}"
            class="vra-btn mb-4"
        >
            Kembali ke Daftar Pengajuan
        </a>

        @php
            $namaPendaftar = $detail->nama_lengkap
                ?: $detail->username;

            $inisial = mb_strtoupper(
                mb_substr(trim($namaPendaftar), 0, 2)
            );

            $dataPribadi = [
                'Nama lengkap' => $detail->nama_lengkap,
                'NISN' => $detail->nisn,
                'Nama pengguna' => $detail->username,
                'Email' => $detail->email,
                'Sekolah' => $detail->asal_sekolah,
                'Kelas' => $detail->kelas,
                'Nomor HP' => $detail->nomor_hp,
                'Alamat' => $detail->alamat_lengkap,
            ];

            $penghasilan = $detail->penghasilan_orang_tua === null
                ? '-'
                : 'Rp ' . number_format(
                    (float) $detail->penghasilan_orang_tua,
                    0,
                    ',',
                    '.'
                );

            $tanggungan = $detail->jumlah_tanggungan === null
                ? '-'
                : $detail->jumlah_tanggungan . ' orang';

            $nilai = $detail->nilai_akademik === null
                ? '-'
                : number_format(
                    (float) $detail->nilai_akademik,
                    2,
                    ',',
                    '.'
                );

            $dataKriteria = [
                'C1 · Penghasilan orang tua' => $penghasilan,
                'C2 · Jumlah tanggungan' => $tanggungan,
                'C3 · Nilai akademik' => $nilai,
                'C4 · Kondisi tempat tinggal' =>
                    $detail->kondisi_tempat_tinggal,
                'C5 · Prestasi non-akademik' =>
                    $detail->prestasi_non_akademik,
            ];

            $dataKeluarga = [
                'Pekerjaan orang tua/wali' =>
                    $detail->pekerjaan_orang_tua,
                'Penghasilan per bulan' => $penghasilan,
                'Jumlah tanggungan' => $tanggungan,
            ];
        @endphp

        <!-- RINGKASAN PENDAFTAR -->

        <section class="vra-card mb-5">

            <div class="vra-top">

                <div class="vra-avatar" aria-hidden="true">
                    {{ $inisial ?: 'PD' }}
                </div>

                <div class="vra-top-main">

                    <p class="vra-eyebrow">
                        Detail Pengajuan Beasiswa · No. {{ $detail->id }}
                    </p>

                    <h2 class="vra-name">
                        {{ $namaPendaftar }}
                    </h2>

                    <div class="vra-top-sub">

                        <span>
                            NISN:
                            <strong>
                                {{ $detail->nisn ?: '-' }}
                            </strong>
                        </span>

                        <span>
                            Sekolah:
                            <strong>
                                {{ $detail->asal_sekolah ?: '-' }}
                            </strong>
                        </span>

                    </div>
                </div>

                <div class="vra-top-side">

                    <span
                        class="vra-badge vra-status-{{ $detail->status }}"
                    >
                        {{
                            $labelStatus[$detail->status]
                                ?? $detail->status
                        }}
                    </span>

                    <span class="vra-muted">
                        Diajukan:
                        {{
                            $detail->diajukan_pada
                                ? date(
                                    'd/m/Y, H:i',
                                    strtotime($detail->diajukan_pada)
                                )
                                : '-'
                        }}
                    </span>

                </div>

            </div>

        </section>

        <!-- EMPAT KARTU INFORMASI -->

        <div class="vra-columns mb-5">

            <!-- KOLOM KIRI -->

            <div class="vra-column">

                <!-- DATA PRIBADI -->

                <section class="vra-card">

                    <div class="vra-head">
                        <h2>Data Pribadi Pendaftar</h2>

                        <p>
                            Identitas, sekolah, dan kontak pendaftar.
                        </p>

                        <span class="vra-accent"></span>
                    </div>

                    <div class="vra-body">

                        @foreach ($dataPribadi as $label => $value)

                            <div class="vra-pair">

                                <span class="vra-key">
                                    {{ $label }}
                                </span>

                                <span class="vra-val">
                                    {{
                                        $value === null || $value === ''
                                            ? '-'
                                            : $value
                                    }}
                                </span>

                            </div>

                        @endforeach

                    </div>
                </section>

                <!-- DATA KELUARGA -->

                <section class="vra-card">

                    <div class="vra-head">
                        <h2>Data Keluarga dan Penghasilan</h2>

                        <p>
                            Kondisi ekonomi keluarga pendaftar.
                        </p>

                        <span class="vra-accent"></span>
                    </div>

                    <div class="vra-body">

                        @foreach ($dataKeluarga as $label => $value)

                            <div class="vra-pair">

                                <span class="vra-key">
                                    {{ $label }}
                                </span>

                                <span class="vra-val">
                                    {{
                                        $value === null || $value === ''
                                            ? '-'
                                            : $value
                                    }}
                                </span>

                            </div>

                        @endforeach

                    </div>
                </section>

            </div>

            <!-- KOLOM KANAN -->

            <div class="vra-column">

                <!-- KRITERIA PENILAIAN -->

                <section class="vra-card">

                    <div class="vra-head">
                        <h2>Kriteria Penilaian (C1–C5)</h2>

                        <p>
                            Data yang digunakan dalam
                            perhitungan seleksi.
                        </p>

                        <span class="vra-accent"></span>
                    </div>

                    <div class="vra-body vra-criteria">

                        @foreach ($dataKriteria as $label => $value)

                            <div class="vra-pair">

                                <span class="vra-key">
                                    {{ $label }}
                                </span>

                                <span class="vra-val">
                                    {{
                                        $value === null || $value === ''
                                            ? '-'
                                            : $value
                                    }}
                                </span>

                            </div>

                        @endforeach

                        <p class="vra-note mt-4">
                            Penghasilan lebih rendah lebih diprioritaskan.
                            Untuk C2–C5, skor yang lebih tinggi
                            memberikan prioritas lebih besar sesuai
                            ketentuan penelitian.
                        </p>

                    </div>
                </section>

                <!-- DOKUMEN -->

                <section class="vra-card">

                    <div class="vra-head">
                        <h2>Dokumen Pendukung</h2>

                        <p>
                            Periksa berkas secara langsung
                            sebelum verifikasi.
                        </p>

                        <span class="vra-accent"></span>
                    </div>

                    <div class="vra-body">

                        @foreach ($dokumen as $jenis => $file)

                            <div class="vra-doc">

                                <div>

                                    <p class="vra-doc-name">
                                        {{ $file['nama'] }}
                                    </p>

                                    <p
                                        class="vra-doc-state
                                               {{ $file['ada']
                                                   ? 'is-ready'
                                                   : '' }}"
                                    >
                                        {{
                                            $file['ada']
                                                ? 'Berkas tersedia'
                                                : 'Belum ada berkas'
                                        }}

                                        ·

                                        {{
                                            $file['wajib']
                                                ? 'Wajib'
                                                : 'Opsional'
                                        }}
                                    </p>

                                </div>

                                @if ($file['ada'])

                                    <a
                                        class="vra-btn"
                                        href="{{ route(
                                            'admin.verifikasi.unduh',
                                            [
                                                'id' => $detail->id,
                                                'jenis' => $jenis
                                            ]
                                        ) }}"
                                    >
                                        Unduh
                                    </a>

                                @else

                                    <span class="vra-muted">
                                        Tidak tersedia
                                    </span>

                                @endif

                            </div>

                        @endforeach

                        <p class="vra-note mt-3">
                            Berkas tersedia belum berarti isinya
                            sudah benar. Admin tetap perlu
                            memeriksanya.
                        </p>

                    </div>
                </section>

            </div>
        </div>

        <!-- KEPUTUSAN VERIFIKASI -->

        <section class="vra-card">

            <div class="vra-head">
                <h2>Keputusan Verifikasi Administrasi</h2>

                <p>
                    Memverifikasi berkas tidak otomatis
                    menetapkan penerima beasiswa.
                </p>

                <span class="vra-accent"></span>
            </div>

            <div class="vra-body" style="padding-top:18px">

                @if ($detail->status === 'diajukan')

                    <form
                        id="vra-form"
                        method="POST"
                        action="{{ route(
                            'admin.verifikasi.keputusan',
                            ['id' => $detail->id]
                        ) }}"
                        novalidate
                    >

                        @csrf

                        <input
                            id="vra-keputusan"
                            type="hidden"
                            name="keputusan"
                            value=""
                        >

                        <label
                            for="vra-catatan"
                            class="text-sm font-bold text-[#254238]"
                        >
                            Catatan untuk pendaftar
                        </label>

                        <textarea
                            class="vra-input"
                            id="vra-catatan"
                            name="catatan_admin"
                            rows="3"
                            maxlength="1000"
                            placeholder="Tuliskan alasan jika meminta perbaikan atau menolak pengajuan."
                        >{{ old('catatan_admin') }}</textarea>

                        <p class="vra-muted mt-2">
                            Catatan wajib minimal 10 karakter
                            untuk Minta Perbaikan atau Tolak.
                        </p>

                        @error('catatan_admin')
                            <p
                                class="vra-error"
                                data-error-field="catatan_admin"
                            >
                                {{ $message }}
                            </p>
                        @enderror

                        <p
                            class="vra-error"
                            id="vra-error-catatan"
                            hidden
                            role="alert"
                        ></p>

                        <label
                            for="vra-periksa"
                            class="mt-4 flex items-start gap-3
                                   text-sm text-[#254238]"
                        >
                            <input
                                id="vra-periksa"
                                type="checkbox"
                                name="sudah_periksa"
                                value="1"
                                class="mt-1"
                                @checked(old('sudah_periksa') == '1')
                            >

                            <span>
                                Saya sudah memeriksa data pendaftar
                                dan dokumen pendukung.
                            </span>
                        </label>

                        @error('sudah_periksa')
                            <p
                                class="vra-error"
                                data-error-field="sudah_periksa"
                            >
                                {{ $message }}
                            </p>
                        @enderror

                        <p
                            class="vra-error"
                            id="vra-error-periksa"
                            hidden
                            role="alert"
                        ></p>

                        <div class="vra-actions">

                            <button
                                type="button"
                                class="vra-btn"
                                data-vra-action="terverifikasi"
                            >
                                Verifikasi Administrasi
                            </button>

                            <button
                                type="button"
                                class="vra-btn vra-btn-warning"
                                data-vra-action="perlu_perbaikan"
                            >
                                Minta Perbaikan
                            </button>

                            <button
                                type="button"
                                class="vra-btn vra-btn-danger"
                                data-vra-action="ditolak"
                            >
                                Tolak Pengajuan
                            </button>

                        </div>

                    </form>

                    <!-- MODAL KONFIRMASI -->

                    <div
                        id="vra-modal"
                        class="vra-modal"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="vra-modal-title"
                        aria-describedby="vra-modal-description"
                        hidden
                    >
                        <div class="vra-dialog">

                            <div class="vra-dialog-rule"></div>

                            <h2 id="vra-modal-title">
                                Konfirmasi Tindakan
                            </h2>

                            <p id="vra-modal-description"></p>

                            <div class="vra-dialog-actions">

                                <button
                                    type="button"
                                    class="vra-btn"
                                    id="vra-batal"
                                >
                                    Batal
                                </button>

                                <button
                                    type="button"
                                    class="vra-btn"
                                    id="vra-lanjut"
                                >
                                    Ya, Lanjutkan
                                </button>

                            </div>
                        </div>
                    </div>

                    <!-- SKELETON SHIMMER -->

                    <div
                        id="vra-loading"
                        class="vra-loading"
                        role="status"
                        aria-live="polite"
                        aria-busy="true"
                        hidden
                    >
                        <strong class="text-sm text-[#103f36]">
                            Menyimpan keputusan...
                        </strong>

                        <div class="vra-bars" aria-hidden="true">
                            <div class="vra-bar"></div>
                            <div class="vra-bar"></div>
                            <div class="vra-bar"></div>
                        </div>
                    </div>

                @else

                    <div class="vra-note">

                        <strong>
                            Status saat ini:
                            {{
                                $labelStatus[$detail->status]
                                    ?? $detail->status
                            }}
                        </strong>

                        <p class="mt-2">
                            Keputusan administrasi sudah tersimpan
                            untuk pengajuan ini.
                        </p>

                        @if ($detail->catatan_admin)

                            <p class="mt-3 font-bold">
                                Catatan Admin:
                            </p>

                            <p class="mt-1 whitespace-pre-wrap">
                                {{ $detail->catatan_admin }}
                            </p>

                        @endif

                    </div>

                @endif

            </div>
        </section>

    @endif

    <p class="vra-muted mt-5">
        Hasil verifikasi administrasi adalah bagian dari
        proses seleksi dan bukan keputusan penerimaan
        beasiswa secara otomatis.
    </p>

</main>

@endsection

@push('scripts')
<script>
(() => {
    'use strict';

    const form = document.getElementById('vra-form');

    if (!form) return;

    const modal = document.getElementById('vra-modal');
    const loading = document.getElementById('vra-loading');
    const title = document.getElementById('vra-modal-title');
    const description = document.getElementById(
        'vra-modal-description'
    );

    const cancel = document.getElementById('vra-batal');
    const accept = document.getElementById('vra-lanjut');
    const input = document.getElementById('vra-keputusan');

    const notes = document.getElementById('vra-catatan');
    const approval = document.getElementById('vra-periksa');

    const noteError = document.getElementById(
        'vra-error-catatan'
    );

    const approvalError = document.getElementById(
        'vra-error-periksa'
    );

    // Agar blur mencakup sidebar dan header.
    document.body.append(modal, loading);

    const choices = {
        terverifikasi: {
            title: 'Konfirmasi Verifikasi',

            text:
                'Pastikan data dan dokumen sudah diperiksa. ' +
                'Pengajuan ini akan berstatus Terverifikasi, ' +
                'bukan otomatis diterima beasiswa.',

            button: 'Ya, Verifikasi Administrasi',

            tone: ''
        },

        perlu_perbaikan: {
            title: 'Konfirmasi Perbaikan',

            text:
                'Catatan akan disampaikan kepada pendaftar ' +
                'sehingga data atau dokumen dapat diperbaiki ' +
                'dan dikirim ulang.',

            button: 'Ya, Minta Perbaikan',

            tone: 'vra-btn-warning'
        },

        ditolak: {
            title: 'Konfirmasi Penolakan',

            text:
                'Pengajuan ini akan ditolak dengan alasan ' +
                'yang ditulis. Pastikan keputusan telah ' +
                'dipertimbangkan.',

            button: 'Ya, Tolak Pengajuan',

            tone: 'vra-btn-danger'
        }
    };

    let selected = null;
    let confirmed = false;
    let submitting = false;
    let previousFocus = null;
    let previousOverflow = '';

    function clear(field, output) {
        output.hidden = true;

        field.classList.remove('vra-invalid');
        field.removeAttribute('aria-invalid');

        form.querySelectorAll(
            '[data-error-field="' + field.name + '"]'
        ).forEach(el => {
            el.hidden = true;
        });
    }

    notes.addEventListener('input', () => {
        clear(notes, noteError);
    });

    approval.addEventListener('change', () => {
        clear(approval, approvalError);
    });

    function fail(field, output, text) {
        output.textContent = text;
        output.hidden = false;

        field.classList.add('vra-invalid');

        field.setAttribute(
            'aria-invalid',
            'true'
        );

        field.focus();

        field.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    function close() {
        modal.hidden = true;

        document.body.style.overflow = previousOverflow;

        selected = null;

        previousFocus?.focus();
    }

    form.querySelectorAll(
        '[data-vra-action]'
    ).forEach(button => {

        button.addEventListener('click', () => {
            if (submitting) return;

            const action = button.dataset.vraAction;
            const choice = choices[action];

            if (!choice) return;

            if (
                action === 'terverifikasi' &&
                !approval.checked
            ) {
                fail(
                    approval,
                    approvalError,

                    'Centang pernyataan setelah memeriksa ' +
                    'data dan dokumen.'
                );

                return;
            }

            if (
                action !== 'terverifikasi' &&
                notes.value.trim().length < 10
            ) {
                fail(
                    notes,
                    noteError,

                    'Tuliskan alasan minimal 10 karakter ' +
                    'sebelum melanjutkan.'
                );

                return;
            }

            selected = action;

            title.textContent = choice.title;
            description.textContent = choice.text;
            accept.textContent = choice.button;

            accept.className = 'vra-btn ' + choice.tone;

            previousFocus = document.activeElement;
            previousOverflow = document.body.style.overflow;

            document.body.style.overflow = 'hidden';

            modal.hidden = false;

            cancel.focus();
        });
    });

    cancel.addEventListener('click', close);

    modal.addEventListener('click', event => {
        if (event.target === modal) {
            close();
        }
    });

    document.addEventListener('keydown', event => {
        if (modal.hidden) return;

        if (event.key === 'Escape') {
            event.preventDefault();
            close();
        }

        if (event.key === 'Tab') {
            if (
                event.shiftKey &&
                document.activeElement === cancel
            ) {
                event.preventDefault();
                accept.focus();

            } else if (
                !event.shiftKey &&
                document.activeElement === accept
            ) {
                event.preventDefault();
                cancel.focus();
            }
        }
    });

    form.addEventListener('submit', event => {
        if (!confirmed || submitting) {
            event.preventDefault();
            return;
        }

        submitting = true;

        loading.hidden = false;

        form.setAttribute(
            'aria-busy',
            'true'
        );
    });

    accept.addEventListener('click', () => {
        if (!selected || submitting) return;

        // Nilai yang diperlukan controller Laravel.
        input.value = selected;

        confirmed = true;

        close();

        form.requestSubmit();
    });

    window.addEventListener('pageshow', event => {
        if (!event.persisted) return;

        submitting = false;
        confirmed = false;

        loading.hidden = true;
        modal.hidden = true;

        form.removeAttribute('aria-busy');

        document.body.style.overflow = previousOverflow;
    });
})();
</script>
@endpush
