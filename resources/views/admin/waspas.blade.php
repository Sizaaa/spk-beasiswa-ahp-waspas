
@extends('layouts.admin')

@section(
    'title',
    'Perhitungan WASPAS - Beasiswa Cerdas'
)

@section('judul-admin', 'Perhitungan WASPAS')

@section(
    'deskripsi-admin',
    'Data asli, normalisasi Cost/Benefit, WSM, WPM, dan Qi.'
)

@section('admin-content')

@php
    $daftarKriteria = array_values($kriteria);

    $dataAsli = collect($alternatif)
        ->keyBy('kode');
@endphp

<main class="bc-admin-main space-y-6">

    <!-- LINGKUP AKTIF -->
    @include('admin.partials.lingkup-aktif')

    <!-- =====================================
         1. PARAMETER PERHITUNGAN
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Parameter Perhitungan WASPAS
                </h2>

                <p class="bc-panel-desc">

                    {{ count($alternatif) }}
                    alternatif pada lingkup

                    {{ $lingkupInfo['judul'] }}

                    · λ =

                    {{ number_format(
                        $lambda,
                        1,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>

            <a
                href="{{ route('admin.ahp.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Atur Bobot AHP
            </a>

        </div>

        <div class="bc-panel-body">

            <div class="bc-notice">

                <strong>Sumber bobot:</strong>

                {{ $informasi['sumber'] }}.

                @if ($informasi['mode'] === 'artikel')

                    <span class="block mt-1">

                        Bobot sesuai artikel,
                        tetapi nilai konsistensi artikel (CR) 0,045
                        belum bisa dihitung ulang
                        tanpa matriks AHP aslinya.

                    </span>

                @endif

            </div>

            <!-- BOBOT C1-C5 -->
            <div
                class="mt-4 grid grid-cols-2
                       gap-3 sm:grid-cols-5"
            >

                @foreach ($kriteria as $kode => $k)

                    <div class="bc-mini-stat">

                        <p class="bc-mini-stat-label">

                            {{ $kode }}

                            · {{ ucfirst($k['jenis']) }}

                        </p>

                        <p class="bc-mini-stat-value">

                            {{ number_format(
                                $informasi['bobot'][$loop->index],
                                4,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                @endforeach

            </div>

            <!-- RUMUS -->
            <p class="bc-muted-note mt-4">

                Cost: r = minimum / nilai.

                Benefit: r = nilai / maksimum.

                WSM (S) = Σ(w × r).

                WPM (P) = Π(r ^ w).

                Qi = 0,5 × S + 0,5 × P.

            </p>

        </div>

    </section>

    <!-- =====================================
         PENANGANAN ERROR / DATA KOSONG
    ===================================== -->

    @if ($pesan)

        <div
            role="alert"
            class="rounded-xl border
                   border-red-200 bg-red-50
                   p-5 text-sm text-red-800"
        >

            <strong>
                Perhitungan tidak dapat dilakukan:
            </strong>

            {{ $pesan }}

        </div>

    @elseif ($hasil['jumlah'] === 0)

        <div class="bc-notice">

            Belum ada alternatif yang memenuhi
            syarat pada lingkup ini.

            Periksa pengaturan lingkup dan
            menu Pengajuan Siap Seleksi.

        </div>

    @else

        <!-- =================================
             2. DATA ASLI
        ================================== -->

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">
                        1. Data Asli Alternatif
                    </h2>

                    <p class="bc-panel-desc">

                        Nilai C1–C5 langsung dari
                        data yang dipilih,
                        belum dinormalisasi.

                    </p>

                </div>

            </div>

            <div class="bc-panel-body">

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:850px"
                    >

                        <thead>

                            <tr>

                                <th>Kode</th>
                                <th>Nama</th>
                                <th>C1 · Rp (Cost)</th>
                                <th>C2 · Tanggungan</th>
                                <th>C3 · Akademik</th>
                                <th>C4 · Rumah</th>
                                <th>C5 · Prestasi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($alternatif as $item)

                                <tr>

                                    <td class="font-bold">

                                        {{ $item->kode }}

                                    </td>

                                    <td>

                                        {{ $item->nama }}

                                    </td>

                                    <td>

                                        {{ number_format(
                                            (float) $item->penghasilan_orang_tua,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                    <td>

                                        {{ $item->jumlah_tanggungan }}

                                    </td>

                                    <td>

                                        {{ $item->nilai_akademik }}

                                    </td>

                                    <td>

                                        {{ $item->kondisi_tempat_tinggal }}

                                    </td>

                                    <td>

                                        {{ $item->prestasi_non_akademik }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- =================================
             3. ACUAN NORMALISASI
        ================================== -->

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">
                        2. Acuan Normalisasi
                    </h2>

                    <p class="bc-panel-desc">

                        Minimum C1 dan maksimum
                        C2–C5 dihitung dari
                        lingkup aktif.

                    </p>

                </div>

            </div>

            <div class="bc-panel-body">

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:650px"
                    >

                        <thead>

                            <tr>

                                <th>Kriteria</th>
                                <th>Jenis</th>
                                <th>Nilai Acuan</th>
                                <th>Rumus</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $kriteria as $kode => $detail
                            )

                                @php
                                    $i = $loop->index;
                                @endphp

                                <tr>

                                    <td class="font-bold">

                                        {{ $kode }}

                                        · {{ $detail['nama'] }}

                                    </td>

                                    <td>

                                        {{ ucfirst(
                                            $detail['jenis']
                                        ) }}

                                    </td>

                                    <td>

                                        {{ number_format(
                                            $hasil['batas'][$i],
                                            $i === 0 ? 0 : 2,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                    <td>

                                        {{ $detail['jenis'] === 'cost'
                                            ? 'min(x) / x'
                                            : 'x / max(x)' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- =================================
             4. HASIL NORMALISASI
        ================================== -->

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">

                        3. Normalisasi dan
                        Hasil Preferensi

                    </h2>

                    <p class="bc-panel-desc">

                        Qi diurutkan dari nilai
                        terbesar ke terkecil.

                    </p>

                </div>

                <a
                    href="{{ route(
                        'admin.hasil.index'
                    ) }}"
                    class="bc-btn bc-btn--primary"
                >
                    Lihat Hasil Seleksi
                </a>

            </div>

            <div class="bc-panel-body">

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:1020px"
                    >

                        <thead>

                            <tr>

                                <th>Rank</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>r1</th>
                                <th>r2</th>
                                <th>r3</th>
                                <th>r4</th>
                                <th>r5</th>
                                <th>WSM (S)</th>
                                <th>WPM (P)</th>
                                <th>Qi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $hasil['peringkat'] as $baris
                            )

                                <tr>

                                    <td>

                                        #{{ $baris['peringkat'] }}

                                    </td>

                                    <td class="font-bold">

                                        {{ $baris['kode'] }}

                                    </td>

                                    <td>

                                        {{ $baris['nama'] }}

                                    </td>

                                    @foreach (
                                        $baris['normalisasi'] as $r
                                    )

                                        <td>

                                            {{ number_format(
                                                $r,
                                                5,
                                                ',',
                                                '.'
                                            ) }}

                                        </td>

                                    @endforeach

                                    <td>

                                        {{ number_format(
                                            $baris['s'],
                                            6,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                    <td>

                                        {{ number_format(
                                            $baris['p'],
                                            6,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                    <td class="font-extrabold
                                               text-emerald-800">

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

            </div>

        </section>

        <!-- =================================
             5. RINCIAN PER ALTERNATIF
        ================================== -->

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">

                        4. Rincian Perhitungan
                        Setiap Alternatif

                    </h2>

                    <p class="bc-panel-desc">

                        Buka kode untuk melihat
                        nilai asal, normalisasi,
                        kontribusi WSM, dan WPM.

                    </p>

                </div>

            </div>

            <div class="bc-panel-body space-y-3">

                @foreach (
                    $hasil['peringkat'] as $baris
                )

                    @php
                        $asli = $dataAsli->get(
                            $baris['kode']
                        );
                    @endphp

                    <details
                        class="rounded-xl border
                               border-slate-200 p-4"
                    >

                        <summary
                            class="cursor-pointer
                                   font-bold text-[#103F36]"
                        >

                            {{ $baris['kode'] }}

                            — {{ $baris['nama'] }}

                            · Peringkat

                            #{{ $baris['peringkat'] }}

                            · Qi

                            {{ number_format(
                                $baris['q'],
                                6,
                                ',',
                                '.'
                            ) }}

                        </summary>

                        <div class="bc-table-scroll mt-4">

                            <table
                                class="bc-table"
                                style="min-width:850px"
                            >

                                <thead>

                                    <tr>

                                        <th>Kriteria</th>
                                        <th>Nilai Asli (x)</th>
                                        <th>Min/Max</th>
                                        <th>Normalisasi (r)</th>
                                        <th>Bobot (w)</th>
                                        <th>w × r</th>
                                        <th>r ^ w</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach (
                                        $daftarKriteria
                                        as $i => $detail
                                    )

                                        @php
                                            $r = $baris[
                                                'normalisasi'
                                            ][$i];

                                            $w = $informasi[
                                                'bobot'
                                            ][$i];

                                            $x = $asli?->{$detail[
                                                'kolom'
                                            ]};
                                        @endphp

                                        <tr>

                                            <td class="font-bold">

                                                C{{ $i + 1 }}

                                                · {{ $detail['nama'] }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    (float) $x,
                                                    $i === 0 ? 0 : 2,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    $hasil['batas'][$i],
                                                    $i === 0 ? 0 : 2,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    $r,
                                                    6,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    $w,
                                                    5,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    $w * $r,
                                                    6,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    pow($r, $w),
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

                        <p
                            class="mt-3 text-sm
                                   text-slate-700"
                        >

                            S =

                            {{ number_format(
                                $baris['s'],
                                6,
                                ',',
                                '.'
                            ) }}

                            · P =

                            {{ number_format(
                                $baris['p'],
                                6,
                                ',',
                                '.'
                            ) }}

                            · <strong>

                                Qi =

                                {{ number_format(
                                    $baris['q'],
                                    6,
                                    ',',
                                    '.'
                                ) }}

                            </strong>

                        </p>

                    </details>

                @endforeach

                <p class="bc-muted-note">

                    Peringkat adalah rekomendasi
                    metode, bukan keputusan otomatis
                    penerimaan beasiswa.

                    Data penelitian A1–A5
                    bersifat simulasi.

                </p>

            </div>

        </section>

    @endif

</main>

@endsection
