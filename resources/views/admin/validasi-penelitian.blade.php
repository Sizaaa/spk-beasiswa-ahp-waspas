
@extends('layouts.admin')

@section('title', 'Validasi Artikel - Beasiswa Cerdas')

@section('judul-admin', 'Validasi Hasil Penelitian')

@section(
    'deskripsi-admin',
    'Reproduksi angka Tabel 6 dan 8 dari artikel Kelompok 4.'
)

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- =====================================
         INFORMASI ARTIKEL
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    Acuan Resmi: Artikel Kelompok 4

                </h2>

                <p class="bc-panel-desc">

                    Data simulasi Tabel 6
                    (halaman 13),

                    hasil Tabel 8
                    (halaman 14).

                </p>

            </div>

            <a
                href="{{ route('admin.hasil.index') }}"
                class="bc-btn bc-btn--outline"
            >
                ← Hasil Seleksi
            </a>

        </div>

        <div class="bc-panel-body space-y-4">

            <p class="text-sm leading-7 text-slate-700">

                Website menghitung ulang

                <strong>
                    lima alternatif A1–A5 dari artikel
                </strong>

                menggunakan rumus WASPAS
                yang sama dengan halaman Hasil Seleksi.

                Data referensi ini tidak mengambil
                atau mengubah Data Tersimpan.

            </p>

            <div class="bc-notice">

                <strong>Bobot resmi artikel:</strong>

                @foreach ($bobotArtikel as $i => $bobot)

                    C{{ $i + 1 }} =

                    {{ number_format(
                        $bobot,
                        3,
                        ',',
                        '.'
                    ) }}{{ $loop->last ? '.' : '; ' }}

                @endforeach

                <span class="mt-1 block">

                    λ = 0,5 · C1 Cost ·
                    C2–C5 Benefit.

                </span>

            </div>

            <!-- KETERBATASAN AHP -->
            <div class="rounded-xl border
                        border-amber-200 bg-amber-50
                        p-4 text-sm leading-7
                        text-amber-900">

                <strong>Catatan AHP:</strong>

                Artikel menyatakan Nilai Konsistensi (CR) = 0,045,
                tetapi tidak menyertakan matriks
                perbandingan berpasangan lengkap.

                Nilai CR tersebut belum dapat
                direproduksi secara mandiri
                dari isi PDF.

                Pengujian halaman ini memvalidasi
                bagian <strong>WASPAS</strong>
                berdasarkan bobot yang tertulis.

            </div>

        </div>

    </section>

    <!-- =====================================
         1. PERBANDINGAN HASIL PERHITUNGAN
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    1. Uji Ulang Angka WASPAS
                    pada Artikel

                </h2>

                <p class="bc-panel-desc">

                    WSM, WPM, dan Qi dibandingkan
                    hingga 4 desimal seperti Tabel 8.

                </p>

            </div>

            <strong
                class="text-sm
                       {{ $jumlahSesuai === 5
                          ? 'text-emerald-700'
                          : 'text-amber-700' }}"
            >

                {{ $jumlahSesuai }} / 5 sesuai

            </strong>

        </div>

        <div class="bc-panel-body">

            @if ($kesalahanArtikel)

                <div
                    role="alert"
                    class="mb-4 rounded-xl
                           bg-red-50 p-4
                           text-sm text-red-800"
                >

                    Perhitungan artikel gagal:

                    {{ $kesalahanArtikel }}

                </div>

            @endif

            <div
                class="bc-table-scroll"
                role="region"
                aria-label="Perbandingan WASPAS artikel"
                tabindex="0"
            >

                <table
                    class="bc-table"
                    style="min-width:950px"
                >

                    <thead>

                        <tr>

                            <th>Kode</th>

                            <th>WSM Artikel</th>

                            <th>WSM Hitung</th>

                            <th>WPM Artikel</th>

                            <th>WPM Hitung</th>

                            <th>Qi Artikel</th>

                            <th>Qi Hitung</th>

                            <th>
                                Peringkat Artikel / Hitung
                            </th>

                            <th>Validasi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($tabelArtikel as $item)

                            <tr>

                                <!-- KODE -->
                                <td class="font-bold">

                                    {{ $item['kode'] }}

                                </td>

                                <!-- WSM ARTIKEL -->
                                <td>

                                    {{ number_format(
                                        $item['acuan']['wsm'],
                                        4,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <!-- WSM HITUNG -->
                                <td>

                                    {{ $item['hitung']
                                        ? number_format(
                                            $item['hitung']['s'],
                                            4,
                                            ',',
                                            '.'
                                          )
                                        : '-' }}

                                </td>

                                <!-- WPM ARTIKEL -->
                                <td>

                                    {{ number_format(
                                        $item['acuan']['wpm'],
                                        4,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <!-- WPM HITUNG -->
                                <td>

                                    {{ $item['hitung']
                                        ? number_format(
                                            $item['hitung']['p'],
                                            4,
                                            ',',
                                            '.'
                                          )
                                        : '-' }}

                                </td>

                                <!-- QI ARTIKEL -->
                                <td>

                                    {{ number_format(
                                        $item['acuan']['qi'],
                                        4,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <!-- QI HITUNG -->
                                <td class="font-bold">

                                    {{ $item['hitung']
                                        ? number_format(
                                            $item['hitung']['q'],
                                            4,
                                            ',',
                                            '.'
                                          )
                                        : '-' }}

                                </td>

                                <!-- PERINGKAT -->
                                <td>

                                    {{ $item['acuan']['peringkat'] }}

                                    /

                                    {{ $item['hitung']['peringkat']
                                        ?? '-' }}

                                </td>

                                <!-- STATUS VALIDASI -->
                                <td>

                                    <span
                                        class="bc-status
                                               {{ $item['sesuai']
                                                  ? 'bc-status--terverifikasi'
                                                  : 'bc-status--ditolak' }}"
                                    >

                                        {{ $item['sesuai']
                                            ? 'Sesuai'
                                            : 'Berbeda' }}

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <p class="bc-muted-note mt-4">

                Kecocokan di sini berarti
                rumus WASPAS dapat mereproduksi
                angka artikel.

                Ini bukan verifikasi keaslian
                data penelitian atau matriks AHP.

            </p>

        </div>

    </section>

    <!-- =====================================
         2. PERIKSA DATA MYSQL
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    2. Apakah Data A1–A5
                    dalam sistem Sesuai dengan Artikel (Tabel 6)?

                </h2>

                <p class="bc-panel-desc">

                    Pemeriksaan langsung terhadap
                    data tersimpan, tanpa pembaruan.

                    Jumlah seluruh calon yang tercatat:

                    {{ $jumlahDataMySql }}.

                </p>

            </div>

            <strong
                class="text-sm
                       {{ $jumlahDataDbSesuai === 5
                          ? 'text-emerald-700'
                          : 'text-amber-700' }}"
            >

                {{ $jumlahDataDbSesuai }} / 5
                data cocok

            </strong>

        </div>

        <div class="bc-panel-body">

            <div
                class="bc-table-scroll"
                role="region"
                aria-label="Pemeriksaan Data Tersimpan dan artikel"
                tabindex="0"
            >

                <table
                    class="bc-table"
                    style="min-width:840px"
                >

                    <thead>

                        <tr>

                            <th>Kode</th>

                            <th>C1 · Penghasilan Orang Tua (Rp)</th>

                            <th>C2</th>

                            <th>C3</th>

                            <th>C4</th>

                            <th>C5</th>

                            <th>Keterangan</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($pemeriksaanDb as $item)

                            <tr>

                                <td class="font-bold">
                                    {{ $item['kode'] }}
                                </td>

                                <td>

                                    {{ $item['row']
                                        ? number_format(
                                            (float) $item['row']->penghasilan_orang_tua,
                                            0,
                                            ',',
                                            '.'
                                          )
                                        : '-' }}

                                </td>

                                <td>

                                    {{ $item['row']->jumlah_tanggungan
                                        ?? '-' }}

                                </td>

                                <td>

                                    {{ $item['row']->nilai_akademik
                                        ?? '-' }}

                                </td>

                                <td>

                                    {{ $item['row']->kondisi_tempat_tinggal
                                        ?? '-' }}

                                </td>

                                <td>

                                    {{ $item['row']->prestasi_non_akademik
                                        ?? '-' }}

                                </td>

                                <!-- STATUS DATA MYSQL -->
                                <td>

                                    <span
                                        class="bc-status
                                               {{ $item['sesuai']
                                                  ? 'bc-status--terverifikasi'
                                                  : 'bc-status--perlu_perbaikan' }}"
                                    >

                                        {{ $item['sesuai']
                                            ? 'Sesuai Tabel 6'
                                            : 'Perlu diperiksa' }}

                                    </span>

                                    @if (!$item['sesuai'])

                                        <p class="mt-2 text-xs
                                                  leading-6
                                                  text-amber-800">

                                            {{ implode(
                                                ', ',
                                                $item['beda']
                                            ) }}

                                        </p>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            @if ($jumlahDataMySql > 5)

                <div class="bc-notice mt-4">

                    Ada {{ $jumlahDataMySql - 5 }}
                    alternatif tambahan dibandingkan
                    simulasi lima data artikel.

                    Peringkat pada menu WASPAS umum
                    dapat berbeda karena normalisasi
                    menggunakan seluruh alternatif
                    yang tersedia.

                </div>

            @endif

            <p class="bc-muted-note mt-4">

                Jangan mengubah A1–A5 hanya agar
                hasil terlihat cocok.

                Bila terdapat perbedaan,
                periksa sumber data terlebih dahulu.

            </p>

        </div>

    </section>

    <!-- =====================================
         3. PERINGKAT KHUSUS MYSQL A1-A5
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">

                    3. Peringkat penyimpanan data A1–A5
                    dengan Bobot Artikel

                </h2>

                <p class="bc-panel-desc">

                    Kalkulasi ini hanya membandingkan
                    A1–A5, bukan seluruh alternatif.

                </p>

            </div>

        </div>

        <div class="bc-panel-body">

            @if ($kesalahanDb)

                <p
                    role="alert"
                    class="rounded-xl
                           bg-red-50 p-4
                           text-sm text-red-800"
                >

                    Perhitungan Data Tersimpan gagal:

                    {{ $kesalahanDb }}

                </p>

            @elseif (count($peringkatDb) !== 5)

                <p class="bc-notice">

                    Perhitungan Data Tersimpan belum bisa
                    dilakukan karena A1–A5
                    belum lengkap atau tidak unik.

                </p>

            @else

                <div class="bc-table-scroll">

                    <table class="bc-table">

                        <thead>

                            <tr>

                                <th>Kode</th>

                                <th>Qi Artikel</th>

                                <th>
                                    Qi dari penyimpanan data A1–A5
                                </th>

                                <th>
                                    Peringkat Artikel
                                </th>

                                <th>
                                    Peringkat penyimpanan data
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($tabelArtikel as $item)

                                @php
                                    $db = $peringkatDb[
                                        $item['kode']
                                    ] ?? null;
                                @endphp

                                <tr>

                                    <td class="font-bold">

                                        {{ $item['kode'] }}

                                    </td>

                                    <td>

                                        {{ number_format(
                                            $item['acuan']['qi'],
                                            4,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>

                                    <td>

                                        {{ $db
                                            ? number_format(
                                                $db['q'],
                                                4,
                                                ',',
                                                '.'
                                              )
                                            : '-' }}

                                    </td>

                                    <td>

                                        {{ $item['acuan']['peringkat'] }}

                                    </td>

                                    <td>

                                        {{ $db['peringkat']
                                            ?? '-' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </section>

    <p class="bc-muted-note">

        Referensi: Kelompok 4_SIM.pdf,
        halaman 13–14.

        Angka penelitian merupakan data simulasi.

        Fitur ini tidak mengubah Data Tersimpan,
        bobot sesi AHP,
        atau status pengajuan User.

    </p>

</main>

@endsection
