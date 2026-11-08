
@extends('layouts.admin')

@section(
    'title',
    'Laporan Seleksi - Beasiswa Cerdas'
)

@section('judul-admin', 'Laporan Seleksi')

@section(
    'deskripsi-admin',
    'Rekap bobot, lingkup, dan pemeringkatan AHP–WASPAS.'
)

@push('styles')

<style>
    @media print {

        aside,
        header,
        .no-print,
        footer {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .bc-admin-main {
            width: 100%;
            max-width: none;
            padding: 0;
        }

        .bc-panel {
            border: 0;
            box-shadow: none;
        }

        .bc-panel-header,
        .bc-panel-body {
            padding: 10px 0;
        }

        .bc-table {
            min-width: 0 !important;
            font-size: 10px;
        }

        .bc-table td,
        .bc-table th {
            padding: 7px !important;
        }

        .bc-table-scroll {
            overflow: visible;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    }
</style>

@endpush

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- LINGKUP AKTIF -->
    @include('admin.partials.lingkup-aktif')

    <!-- TOMBOL CETAK -->
    <div
        class="no-print flex flex-wrap
               items-center justify-between gap-3"
    >

        <p class="text-sm text-slate-600">

            Klik Cetak, lalu pilih

            <strong>
                Save as PDF / Simpan sebagai PDF
            </strong>

            di browser jika ingin
            menyimpan laporan.

        </p>

        <button
            type="button"
            onclick="window.print()"
            class="bc-btn bc-btn--primary"
        >
            Cetak / Simpan PDF
        </button>

    </div>

    <!-- =====================================
         LAPORAN UTAMA
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <p class="bc-eyebrow">

                    Kelompok 4
                    · Universitas Pamulang

                </p>

                <h2 class="bc-panel-title">

                    Laporan Pemeringkatan
                    Beasiswa Cerdas

                </h2>

                <p class="bc-panel-desc">

                    Sistem Pendukung Keputusan
                    Kelayakan Penerima Beasiswa
                    Kurang Mampu Menggunakan
                    Metode AHP dan WASPAS

                </p>

            </div>

        </div>

        <div class="bc-panel-body">

            <!-- TANGGAL -->
            <p class="text-sm">

                <strong>Tanggal cetak:</strong>

                {{ now()->format('d-m-Y H:i') }}

            </p>

            <!-- LINGKUP -->
            <p class="mt-2 text-sm">

                <strong>
                    Lingkup perhitungan:
                </strong>

                {{ $lingkupInfo['judul'] }}

            </p>

            <!-- JUMLAH ALTERNATIF -->
            <p class="mt-2 text-sm">

                <strong>
                    Jumlah alternatif:
                </strong>

                {{ count($alternatif) }}

            </p>

            <!-- SUMBER BOBOT -->
            <p class="mt-2 text-sm">

                <strong>Sumber bobot:</strong>

                {{ $informasi['sumber'] }}

            </p>

            <!-- LAMBDA -->
            <p class="mt-2 text-sm">

                <strong>λ WASPAS:</strong>

                {{ number_format(
                    $lambda,
                    1,
                    ',',
                    '.'
                ) }}

            </p>

            <!-- BOBOT C1-C5 -->
            <p class="mt-2 text-sm">

                <strong>Bobot C1–C5:</strong>

                @foreach (
                    $informasi['bobot'] as $i => $w
                )

                    C{{ $i + 1 }} =

                    {{ number_format(
                        $w,
                        5,
                        ',',
                        '.'
                    ) }}

                    {{ $loop->last ? '' : '; ' }}

                @endforeach

            </p>

            <!-- KETERANGAN AHP -->
            @if (
                $informasi['mode'] === 'simulasi'
            )

                <p class="mt-2 text-sm">

                    <strong>
                        CR simulasi AHP:
                    </strong>

                    {{ number_format(
                        $informasi['ahp']['cr'],
                        5,
                        ',',
                        '.'
                    ) }}

                    (konsisten).

                </p>

            @else

                <p
                    class="mt-2 text-sm
                           text-amber-800"
                >

                    <strong>Catatan AHP:</strong>

                    Artikel melaporkan Nilai Konsistensi (CR) = 0,045,
                    tetapi matriks aslinya tidak
                    dicantumkan.

                    CR tersebut tidak diklaim
                    sebagai hasil perhitungan
                    ulang website.

                </p>

            @endif

            <!-- CATATAN LINGKUP -->
            @foreach (
                $lingkupInfo['catatan'] as $catatan
            )

                <p
                    class="mt-2 text-sm
                           text-amber-800"
                >

                    <strong>
                        Catatan Kelompok Data Seleksi:
                    </strong>

                    {{ $catatan }}

                </p>

            @endforeach

            <!-- =================================
                 HASIL WASPAS
            ================================== -->

            @if ($pesan)

                <p
                    role="alert"
                    class="mt-6 rounded-xl
                           bg-red-50 p-4
                           text-sm text-red-700"
                >

                    Perhitungan tidak tersedia:

                    {{ $pesan }}

                </p>

            @elseif ($hasil['jumlah'] === 0)

                <p class="mt-6 text-sm">

                    Belum ada alternatif
                    yang memenuhi syarat
                    dalam lingkup ini.

                </p>

            @else

                <h3
                    class="mb-3 mt-6
                           font-extrabold
                           text-[#103F36]"
                >

                    Hasil Pemeringkatan WASPAS

                </h3>

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:650px"
                    >

                        <thead>

                            <tr>

                                <th>Peringkat</th>
                                <th>Kode</th>
                                <th>Nama Calon Penerima</th>
                                <th>WSM (S)</th>
                                <th>WPM (P)</th>
                                <th>Qi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $hasil['peringkat']
                                as $baris
                            )

                                <tr>

                                    <td>

                                        {{ $baris['peringkat'] }}

                                    </td>

                                    <td>

                                        {{ $baris['kode'] }}

                                    </td>

                                    <td>

                                        {{ $baris['nama'] }}

                                    </td>

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

                                    <td class="font-bold">

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

            @endif

            <!-- =================================
                 KETERANGAN LAPORAN
            ================================== -->

            @if (
                $lingkupInfo['mode'] === 'artikel'
            )

                <p
                    class="mt-5 text-xs
                           leading-6 text-slate-600"
                >

                    Laporan ini menggunakan
                    data simulasi penelitian A1–A5
                    dan bukan keputusan penerimaan
                    beasiswa nyata.

                </p>

            @elseif (
                $lingkupInfo['mode']
                === 'pendaftar'
            )

                <p
                    class="mt-5 text-xs
                           leading-6 text-slate-600"
                >

                    Laporan ini berisi rekomendasi
                    peringkat pengajuan User
                    terverifikasi yang sudah
                    dimasukkan ke alternatif.

                    Penetapan penerima tetap
                    menjadi tanggung jawab
                    pengelola.

                </p>

            @else

                <p
                    class="mt-5 text-xs
                           leading-6 text-amber-800"
                >

                    Laporan demonstrasi
                    menggabungkan data penelitian,
                    data manual, dan data pengajuan.

                    Hasilnya tidak boleh dipakai
                    sebagai keputusan penerimaan
                    beasiswa nyata.

                </p>

            @endif

            <!-- IDENTITAS KELOMPOK -->
            <p
                class="mt-5 text-xs text-slate-600"
            >

                Dibuat oleh Kelompok 4 —

                Bagas Dwi Prasojo

                · Muhammad Azis

                · Muhammad Asyhadullah

            </p>

        </div>

    </section>

</main>

@endsection
