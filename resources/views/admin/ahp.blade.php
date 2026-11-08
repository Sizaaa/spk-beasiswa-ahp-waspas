
@extends('layouts.admin')

@section('title', 'Perhitungan AHP - Beasiswa Cerdas')
@section('judul-admin', 'Perhitungan AHP')
@section('deskripsi-admin', 'Bobot artikel dan pengujian matriks perbandingan berpasangan.')

@section('admin-content')

@php
    $kodeKriteria = array_keys($kriteria);

    $namaKriteria = array_column(
        array_values($kriteria),
        'nama'
    );

    $modeSimulasiAktif =
        $bobotAktif['mode'] === 'simulasi';
@endphp

<main class="bc-admin-main space-y-6">

    <!-- PESAN BERHASIL -->
    @if (session('sukses'))

        <div
            class="rounded-xl border border-emerald-200
                   bg-emerald-50 p-4
                   text-sm text-emerald-800"
            role="status"
        >
            {{ session('sukses') }}
        </div>

    @endif

    <!-- PESAN ERROR -->
    @if ($errors->any())

        <div
            class="rounded-xl border border-red-200
                   bg-red-50 p-4 text-sm text-red-800"
            role="alert"
        >

            <p class="font-bold">
                Periksa masukan berikut:
            </p>

            <ul class="mt-2 list-inside
                       list-disc space-y-1">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <!-- =====================================
         1. BOBOT ARTIKEL
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    1. Bobot yang Tercantum
                    pada Artikel
                </h2>

                <p class="bc-panel-desc">

                    Acuan penelitian:
                    Penentuan Bobot Kriteria
                    dengan AHP, halaman 13.

                </p>

            </div>

            <span
                class="bc-status
                       {{ $modeSimulasiAktif
                          ? 'bc-status--perlu_perbaikan'
                          : 'bc-status--terverifikasi' }}"
            >

                {{ $modeSimulasiAktif
                    ? 'Mode simulasi aktif'
                    : 'Mode artikel aktif' }}

            </span>

        </div>

        <div class="bc-panel-body">

            <!-- BOBOT ARTIKEL -->
            <div class="grid grid-cols-2
                        gap-3 sm:grid-cols-5">

                @foreach ($kodeKriteria as $i => $kode)

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">

                            {{ $kode }}

                            · {{ $namaKriteria[$i] }}

                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                \App\Services\SpkCalculator::BOBOT_REFERENSI[$i],
                                3,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                @endforeach

            </div>

            <!-- CR ARTIKEL -->
            <div class="bc-notice mt-5">

                <strong>
                    Perbedaan yang penting:
                </strong>

                Artikel menyebutkan Nilai Konsistensi (CR) = 0,045.

                Angka tersebut adalah
                <strong>
                    CR yang dilaporkan
                    penulis artikel
                </strong>,

                bukan hasil yang dihitung
                ulang di halaman ini.

                Matriks perbandingan berpasangan
                asli tidak ditampilkan dalam PDF,
                sehingga nilai konsistensi artikel (CR) belum dapat
                direproduksi secara independen.

            </div>

            <div class="mt-5 flex flex-wrap
                        items-center gap-3">

                <!-- PILIH BOBOT ARTIKEL -->
                {{-- BC-AHP-STATUS-MODE-V2 --}}

@php
    // Ambil status bobot yang benar-benar
    // sedang digunakan oleh halaman AHP.

    $bcModeBobot = $modeAktif
        ?? ($informasi['mode'] ?? ($bobotAktif['mode'] ?? null));

    // Mendukung versi controller yang lebih lama.
    if (!in_array($bcModeBobot, ['artikel', 'simulasi'], true)) {
        $bcModeBobot = is_array($bobotAktif['ahp'] ?? null)
            ? 'simulasi'
            : 'artikel';
    }
@endphp

@if ($bcModeBobot === 'artikel')

    <div
        role="status"
        style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 17px 19px;
            background: #f0faf5;
            border: 1px solid #b9ddcd;
            border-radius: 13px;
            margin-top: 15px;
        "
    >

        <div style="flex: 1 1 300px; min-width: 0;">

            <strong
                style="
                    display: block;
                    font-size: 14px;
                    font-weight: 800;
                    color: #0c4b3a;
                "
            >
                Bobot Referensi Artikel Sedang Digunakan
            </strong>

            <p
                style="
                    margin: 6px 0 0;
                    font-size: 13px;
                    line-height: 1.7;
                    color: #4e7063;
                "
            >
                Bobot C1–C5 dari artikel sudah digunakan
                pada perhitungan WASPAS, hasil seleksi,
                dan laporan. Tidak perlu diaktifkan ulang.
            </p>

        </div>

        <a
            href="{{ route('admin.waspas.index') }}"
            class="bc-btn bc-btn--outline"
        >
            Lihat Perhitungan WASPAS
        </a>

    </div>

@else

    <div
        role="status"
        style="
            padding: 16px 19px;
            background: #f5f9fb;
            border: 1px solid #dce7ec;
            border-radius: 13px;
            margin-top: 15px;
            margin-bottom: 12px;
        "
    >

        <strong
            style="
                display: block;
                font-size: 14px;
                font-weight: 800;
                color: #143f38;
            "
        >
            Bobot Hasil AHP Baru Sedang Digunakan
        </strong>

        <p
            style="
                margin: 6px 0 0;
                font-size: 13px;
                line-height: 1.7;
                color: #58707b;
            "
        >
            Sistem menggunakan bobot hasil
            perbandingan kriteria yang dihitung Admin.
            Untuk kembali menggunakan bobot penelitian,
            pilih tombol di bawah.
        </p>

    </div>

<form
                    method="POST"
                    action="{{ route('admin.ahp.pilih') }}"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="mode"
                        value="artikel"
                    >

                    <button
                        type="submit"
                        class="bc-btn bc-btn--primary"
                        @disabled(!$modeSimulasiAktif)
                    >
                        Gunakan Bobot Referensi Artikel
                    </button>

                </form>

@endif

                <!-- VALIDASI ARTIKEL -->
                @if (
                    \Illuminate\Support\Facades\Route::has(
                        'admin.validasi-penelitian.index'
                    )
                )

                    <a
                        href="{{ route(
                            'admin.validasi-penelitian.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >

                        Validasi A1–A5 dengan Artikel

                    </a>

                @endif

            </div>

        </div>

    </section>

    <!-- =====================================
         2. BOBOT AKTIF
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    2. Bobot Aktif untuk WASPAS,
                    Hasil Seleksi, dan Laporan

                </h2>

                <p class="bc-panel-desc">

                    {{ $bobotAktif['sumber'] }}

                </p>

            </div>

        </div>

        <div class="bc-panel-body">

            <div class="grid grid-cols-2
                        gap-3 sm:grid-cols-5">

                @foreach ($kodeKriteria as $i => $kode)

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">
                            {{ $kode }}
                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                (float) $bobotAktif['bobot'][$i],
                                5,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                @endforeach

            </div>

            <p class="bc-muted-note mt-4">

                Mode artikel aktif secara default.

                Menghitung matriks simulasi
                tidak mengubah perhitungan WASPAS
                sebelum Admin memilih
                untuk menggunakannya.

                Mode simulasi hanya berlaku
                pada sesi login saat ini.

            </p>

        </div>

    </section>

    <!-- =====================================
         3. FORM SIMULASI AHP
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    3. Simulasi Matriks
                    Perbandingan AHP

                </h2>

                <p class="bc-panel-desc">

                    Bandingkan tingkat kepentingan
                    lima kriteria.

                    Nilai kebalikan matriks
                    dihitung otomatis oleh sistem.

                </p>

            </div>

        </div>

        <div class="bc-panel-body">

            <div class="bc-notice mb-5">

                Nilai awal merupakan

                <strong>
                    rekonstruksi matematis
                    dari bobot artikel
                </strong>

                untuk tujuan latihan.

                Ini bukan matriks asli yang
                menghasilkan CR 0,045
                pada artikel.

                Nilai 1 berarti sama penting.

                Nilai 3 berarti kriteria
                di kiri tiga kali lebih penting.

                Untuk keadaan sebaliknya,
                gunakan angka seperti 0,333333
                (sekitar 1/3).

            </div>

            <!-- FORM PERBANDINGAN -->
            <form
                method="POST"
                action="{{ route('admin.ahp.hitung') }}"
            >

                @csrf

                <div class="grid gap-3 md:grid-cols-2">

                    @for ($i = 0; $i < 5; $i++)

                        @for ($j = $i + 1; $j < 5; $j++)

                            @php
                                $kunci = $i . '_' . $j;
                            @endphp

                            <label
                                class="block rounded-xl
                                       border border-slate-200
                                       p-4 text-sm"
                            >

                                <span
                                    class="mb-1 block font-bold
                                           text-[#103F36]"
                                >

                                    {{ $kodeKriteria[$i] }}

                                    dibanding

                                    {{ $kodeKriteria[$j] }}

                                </span>

                                <span
                                    class="mb-2 block text-xs
                                           text-slate-500"
                                >

                                    {{ $namaKriteria[$i] }}

                                    /

                                    {{ $namaKriteria[$j] }}

                                </span>

                                <input
                                    type="number"

                                    name="pasangan[{{ $kunci }}]"

                                    value="{{ old(
                                        'pasangan.' . $kunci,
                                        $pasangan[$kunci] ?? ''
                                    ) }}"

                                    min="0.111111111111"

                                    max="9"

                                    step="any"

                                    inputmode="decimal"

                                    required

                                    class="bc-form-control w-full"
                                >

                            </label>

                        @endfor

                    @endfor

                </div>

                <p class="bc-muted-note mt-4">

                    Gunakan titik sebagai
                    pemisah desimal.

                    Batas perbandingan AHP
                    adalah 1/9 hingga 9.

                    Hasil dianggap konsisten
                    apabila CR ≤ 0,10.

                </p>

                <div class="mt-5 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="bc-btn bc-btn--primary"
                    >

                        Hitung Bobot AHP Baru

                    </button>

                    <a
                        href="{{ route('admin.waspas.index') }}"
                        class="bc-btn bc-btn--outline"
                    >

                        Buka WASPAS →

                    </a>

                </div>

            </form>

        </div>

    </section>

    <!-- =====================================
         4. HASIL SIMULASI
    ===================================== -->

    @if (
        $hasil &&
        isset(
            $hasil['matriks'],
            $hasil['bobot'],
            $hasil['cr']
        )
    )

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">

                        4. Hasil Simulasi
                        AHP Terakhir

                    </h2>

                    <p class="bc-panel-desc">

                        Eigenvektor, λ maks,
                        Consistency Index (CI),
                        dan Consistency Ratio (CR).

                    </p>

                </div>

                <span
                    class="bc-status
                           {{ $simulasiLayak
                              ? 'bc-status--terverifikasi'
                              : 'bc-status--ditolak' }}"
                >

                    {{ $simulasiLayak
                        ? 'Konsisten'
                        : 'Belum konsisten' }}

                </span>

            </div>

            <div class="bc-panel-body">

                <!-- MATRIKS -->
                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:690px"
                    >

                        <thead>

                            <tr>

                                <th>Kriteria</th>

                                @foreach ($kodeKriteria as $kode)

                                    <th>{{ $kode }}</th>

                                @endforeach

                                <th>Bobot Simulasi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $kodeKriteria as $i => $kode
                            )

                                <tr>

                                    <td class="font-bold">

                                        {{ $kode }}

                                    </td>

                                    @foreach (
                                        $kodeKriteria
                                        as $j => $kodeKolom
                                    )

                                        <td>

                                            {{ number_format(
                                                (float) $hasil['matriks'][$i][$j],
                                                5,
                                                ',',
                                                '.'
                                            ) }}

                                        </td>

                                    @endforeach

                                    <td class="font-bold">

                                        {{ number_format(
                                            (float) $hasil['bobot'][$i],
                                            6,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <!-- METRIK KONSISTENSI -->
                <div class="mt-5 grid gap-3 sm:grid-cols-3">

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">
                            λ maks
                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                (float) $hasil['lambda_max'],
                                5,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">
                            CI Simulasi
                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                (float) $hasil['ci'],
                                5,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">
                            CR Simulasi
                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                (float) $hasil['cr'],
                                5,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                </div>

                <!-- KETERANGAN -->
                <div class="bc-notice mt-4">

                    @if ($simulasiLayak)

                        Matriks simulasi konsisten.

                        Bobot simulasi dapat digunakan
                        untuk WASPAS, tetapi

                        <strong>
                            tidak otomatis aktif.
                        </strong>

                    @else

                        Matriks simulasi belum
                        memenuhi syarat konsistensi.

                        Perbaiki perbandingan sebelum
                        digunakan untuk WASPAS.

                    @endif

                    CR di sini adalah hasil
                    perhitungan matriks simulasi,

                    <strong>
                        bukan Nilai Konsistensi (CR) = 0,045
                        dari artikel.
                    </strong>

                </div>

                <!-- TOMBOL PENERAPAN -->
                <div class="mt-5 flex flex-wrap gap-3">

                    @if ($simulasiLayak)

                        <form
                            method="POST"
                            action="{{ route('admin.ahp.pilih') }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="mode"
                                value="simulasi"
                            >

                            <button
                                type="submit"
                                class="bc-btn bc-btn--primary"
                                @disabled($modeSimulasiAktif)
                            >

                                Gunakan Bobot Simulasi
                                untuk WASPAS

                            </button>

                        </form>

                    @endif

                    <!-- RESET SIMULASI -->
                    <form
                        method="POST"
                        action="{{ route('admin.ahp.reset') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="bc-btn bc-btn--outline"

                            onclick="return confirm('Hapus hasil simulasi dan kembali ke bobot artikel?')"
                        >

                            Reset Simulasi

                        </button>

                    </form>

                </div>

            </div>

        </section>

    @endif

</main>

@endsection
