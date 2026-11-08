
@extends('layouts.admin')

@section(
    'title',
    'Hasil Seleksi - Beasiswa Cerdas'
)

@section('judul-admin', 'Hasil Seleksi')

@section(
    'deskripsi-admin',
    'Pemeringkatan alternatif berdasarkan nilai preferensi WASPAS.'
)

@section('admin-content')

@php
    $modeAktif = $informasi['mode']
        ?? 'artikel';

    $daftarKriteria = array_values(
        \App\Services\SpkCalculator::KRITERIA
    );

    $bobotArtikel =
        \App\Services\SpkCalculator::BOBOT_REFERENSI;

    $ahpSesi = session('spk_ahp');

    $bobotSimulasi = null;

    $crSimulasi = null;

    if (
        is_array($ahpSesi) &&
        isset(
            $ahpSesi['bobot'],
            $ahpSesi['cr']
        ) &&
        is_array($ahpSesi['bobot']) &&
        count($ahpSesi['bobot']) === 5 &&
        count(
            array_filter(
                $ahpSesi['bobot'],
                'is_numeric'
            )
        ) === 5 &&
        is_numeric($ahpSesi['cr'])
    ) {

        $bobotSimulasi = array_values(
            $ahpSesi['bobot']
        );

        $crSimulasi = (float)
            $ahpSesi['cr'];
    }
@endphp

<main class="bc-admin-main space-y-6">

    <!-- LINGKUP AKTIF -->
    @include('admin.partials.lingkup-aktif')

    <!-- =====================================
         DUA MODE BOBOT
    ===================================== -->

    <section
        class="grid gap-5 lg:grid-cols-2"
        aria-label="Perbandingan sumber bobot"
    >

        <!-- BOBOT ARTIKEL -->
        <article class="bc-panel">

            <div class="bc-panel-body h-full">

                <div class="flex flex-wrap
                            items-start justify-between
                            gap-3"
                >

                    <div>

                        <h2 class="bc-panel-title">
                            Bobot Artikel
                        </h2>

                        <p class="bc-panel-desc">

                            Bobot yang tercantum
                            dalam artikel penelitian.

                        </p>

                    </div>

                    <span
                        class="bc-status
                               {{ $modeAktif === 'artikel'
                                  ? 'bc-status--terverifikasi'
                                  : 'bc-status--perlu_perbaikan' }}"
                    >

                        {{ $modeAktif === 'artikel'
                            ? 'Aktif'
                            : 'Tidak aktif' }}

                    </span>

                </div>

                <dl class="mt-5 divide-y
                           divide-slate-100"
                >

                    @foreach (
                        $daftarKriteria as $i => $item
                    )

                        <div
                            class="flex items-center
                                   justify-between
                                   gap-4 py-2.5"
                        >

                            <dt
                                class="min-w-0 text-sm
                                       text-slate-600"
                            >

                                <strong
                                    class="mr-2
                                           text-[#103F36]"
                                >
                                    C{{ $i + 1 }}
                                </strong>

                                {{ $item['nama'] }}

                            </dt>

                            <dd
                                class="shrink-0 text-sm
                                       font-extrabold
                                       text-[#103F36]"
                            >

                                {{ number_format(
                                    $bobotArtikel[$i],
                                    3,
                                    ',',
                                    '.'
                                ) }}

                            </dd>

                        </div>

                    @endforeach

                </dl>

                <p class="bc-muted-note mt-3">

                    CR 0,045 dilaporkan di artikel,
                    tetapi matriks AHP aslinya
                    tidak dicantumkan untuk
                    dihitung ulang.

                </p>

            </div>

        </article>

        <!-- BOBOT SIMULASI -->
        <article class="bc-panel">

            <div class="bc-panel-body h-full">

                <div class="flex flex-wrap
                            items-start justify-between
                            gap-3"
                >

                    <div>

                        <h2 class="bc-panel-title">
                            Bobot Simulasi AHP
                        </h2>

                        <p class="bc-panel-desc">

                            Hasil AHP yang dihitung
                            Admin dalam sesi ini.

                        </p>

                    </div>

                    <span
                        class="bc-status
                               {{ $modeAktif === 'simulasi'
                                  ? 'bc-status--terverifikasi'
                                  : 'bc-status--perlu_perbaikan' }}"
                    >

                        {{ $modeAktif === 'simulasi'
                            ? 'Aktif'
                            : 'Tidak aktif' }}

                    </span>

                </div>

                @if ($bobotSimulasi !== null)

                    <dl
                        class="mt-5 divide-y
                               divide-slate-100"
                    >

                        @foreach (
                            $daftarKriteria
                            as $i => $item
                        )

                            <div
                                class="flex items-center
                                       justify-between
                                       gap-4 py-2.5"
                            >

                                <dt
                                    class="min-w-0 text-sm
                                           text-slate-600"
                                >

                                    <strong
                                        class="mr-2
                                               text-[#103F36]"
                                    >
                                        C{{ $i + 1 }}
                                    </strong>

                                    {{ $item['nama'] }}

                                </dt>

                                <dd
                                    class="shrink-0
                                           text-sm
                                           font-extrabold
                                           text-[#103F36]"
                                >

                                    {{ number_format(
                                        (float) $bobotSimulasi[$i],
                                        3,
                                        ',',
                                        '.'
                                    ) }}

                                </dd>

                            </div>

                        @endforeach

                    </dl>

                    <p class="bc-muted-note mt-3">

                        CR simulasi =

                        {{ number_format(
                            $crSimulasi,
                            5,
                            ',',
                            '.'
                        ) }}

                        ·

                        {{ $crSimulasi <= 0.10
                            ? 'Konsisten'
                            : 'Belum konsisten' }}.

                    </p>

                @else

                    <p
                        class="mt-5 rounded-xl
                               bg-slate-50 p-5
                               text-sm text-slate-600"
                    >

                        Belum ada hasil simulasi AHP
                        pada sesi Admin ini.

                    </p>

                @endif

            </div>

        </article>

    </section>

    <!-- =====================================
         INFORMASI BOBOT AKTIF
    ===================================== -->

    <section
        class="bc-notice flex flex-wrap
               items-start justify-between gap-3"
    >

        <div class="min-w-0 flex-1">

            <p class="font-bold text-[#103F36]">

                Bobot Penilaian yang Digunakan:

                {{ $modeAktif === 'simulasi'
                    ? 'Simulasi AHP'
                    : 'Artikel Penelitian' }}

            </p>

            <p class="mt-1 break-words">

                {{ $informasi['sumber'] }}

            </p>

        </div>

        <span
            class="shrink-0 rounded-lg
                   bg-white px-3 py-2
                   text-xs font-bold"
        >

            λ =

            {{ number_format(
                $lambda,
                1,
                ',',
                '.'
            ) }}

        </span>

    </section>

    <!-- PESAN -->
    @if (session('sukses'))

        <p
            role="status"
            class="rounded-xl bg-emerald-50
                   p-4 text-sm text-emerald-800"
        >

            {{ session('sukses') }}

        </p>

    @endif

    <!-- =====================================
         HASIL SELEKSI
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div class="w-full min-w-0">

                <h2 class="bc-panel-title">
                    Peringkat Calon Penerima
                </h2>

                <p class="bc-panel-desc">

                    {{ $hasil['jumlah'] }}
                    alternatif dihitung
                    dalam lingkup

                    {{ $lingkupInfo['judul'] }}.

                    Nilai Akhir (Qi) tertinggi memperoleh
                    peringkat pertama.

                </p>

                <!-- MENU AKSI -->
                <div
                    class="mt-4 flex flex-wrap
                           items-center gap-2"
                >

                    <a
                        href="{{ route(
                            'admin.seleksi-pengajuan.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Pengajuan Siap Seleksi
                    </a>

                    <a
                        href="{{ route(
                            'admin.waspas.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Detail Perhitungan
                    </a>

                    <a
                        href="{{ route(
                            'admin.sensitivitas.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Analisis Sensitivitas
                    </a>

                    <a
                        href="{{ route(
                            'admin.validasi-penelitian.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Bandingkan dengan Artikel
                    </a>

                    <a
                        href="{{ route(
                            'admin.laporan.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Laporan / Cetak
                    </a>

                </div>

            </div>

        </div>

        <div class="bc-panel-body">

            @if ($pesan)

                <p
                    role="alert"
                    class="rounded-xl bg-red-50
                           p-4 text-sm text-red-700"
                >
                    {{ $pesan }}
                </p>

            @elseif ($hasil['jumlah'] === 0)

                <p class="bc-notice">

                    Belum ada alternatif
                    yang memenuhi syarat
                    dalam lingkup ini.

                </p>

            @else

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:620px"
                    >

                        <thead>

                            <tr>

                                <th>Peringkat</th>
                                <th>Kode</th>
                                <th>Nama Calon Penerima</th>
                                <th>Nilai Akhir (Qi)</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $hasil['peringkat']
                                as $baris
                            )

                                <tr>

                                    <td
                                        class="font-bold
                                               {{ $loop->first
                                                  ? 'text-emerald-700'
                                                  : '' }}"
                                    >

                                        #{{ $baris['peringkat'] }}

                                    </td>

                                    <td class="font-semibold">

                                        {{ $baris['kode'] }}

                                    </td>

                                    <td>

                                        {{ $baris['nama'] }}

                                    </td>

                                    <td
                                        class="font-extrabold
                                               text-[#103F36]"
                                    >

                                        {{ number_format(
                                            $baris['q'],
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

                <!-- KETERANGAN HASIL -->
                <div class="bc-notice mt-5">

                    Peringkat adalah rekomendasi
                    pendukung keputusan,
                    bukan keputusan penerimaan
                    otomatis.

                    @if (
                        $lingkupInfo['mode']
                        === 'artikel'
                    )

                        Data A1–A5 adalah
                        simulasi penelitian.

                    @elseif (
                        $lingkupInfo['mode']
                        === 'pendaftar'
                    )

                        Hanya pengajuan terverifikasi
                        yang valid dan sudah masuk
                        sebagai PG-ID yang
                        diperhitungkan.

                    @else

                        Data gabungan hanya untuk
                        demonstrasi, bukan seleksi
                        penerima beasiswa nyata.

                    @endif

                </div>

            @endif

        </div>

    </section>

</main>

@endsection
