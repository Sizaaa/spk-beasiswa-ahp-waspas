
@extends('layouts.admin')

@section('title', 'Detail Riwayat Seleksi - Beasiswa Cerdas')

@section('judul-admin', 'Detail Riwayat Seleksi')

@section(
    'deskripsi-admin',
    'Rekap perhitungan pada waktu arsip dibuat; tidak mengikuti perubahan data terbaru.'
)

@push('styles')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        .no-print,
        aside,
        header,
        footer {
            display: none !important;
        }

        .bc-admin-main {
            max-width: none !important;
            padding: 0 !important;
        }

        .bc-panel {
            border: 0 !important;
            box-shadow: none !important;
        }

        .bc-table-scroll {
            overflow: visible !important;
        }

        .bc-table {
            min-width: 0 !important;
            font-size: 10px;
        }

        .bc-table td,
        .bc-table th {
            padding: 6px !important;
        }
    }
</style>
@endpush

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- NAVIGASI -->
    <div class="no-print flex flex-wrap gap-2">

        <a
            class="bc-btn bc-btn--outline"
            href="{{ route('admin.riwayat.index') }}"
        >
            ← Daftar Riwayat
        </a>

        @if ($utuh)

            <button
                type="button"
                class="bc-btn bc-btn--primary"
                onclick="window.print()"
            >
                Cetak / Simpan PDF
            </button>

        @endif

    </div>

    @if (session('sukses'))

        <p
            role="status"
            class="rounded-xl bg-emerald-50 p-4
                   text-sm text-emerald-700"
        >
            {{ session('sukses') }}
        </p>

    @endif

    <!-- PEMERIKSAAN INTEGRITAS -->
    @if (!$utuh)

        <div
            role="alert"
            class="rounded-xl border border-red-200
                   bg-red-50 p-5 text-sm text-red-800"
        >

            <strong>
                Integritas arsip tidak lolos pemeriksaan.
            </strong>

            Data atau Pemeriksaan Keutuhan Arsip tidak cocok.

            Isi arsip tidak ditampilkan agar tidak
            digunakan sebagai bukti perhitungan
            yang valid.

        </div>

    @else

        <!-- INFORMASI ARSIP -->
        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>
                    <h2 class="bc-panel-title">
                        Riwayat Perhitungan
                        #{{ $riwayat->id }}
                    </h2>

                    <p class="bc-panel-desc">
                        Disimpan pada
                        {{ $riwayat->created_at }}
                        oleh
                        {{ $riwayat->admin_nama }}
                    </p>
                </div>

                <span
                    class="bc-status
                           bc-status--terverifikasi"
                >
                    Keutuhan Arsip Sesuai
                </span>

            </div>

            <div class="bc-panel-body space-y-3">

                <p class="text-sm">
                    <strong>Kelompok Data Seleksi:</strong>
                    {{ $snapshot['lingkup']['nama'] }}
                </p>

                <p class="text-sm">
                    <strong>Sumber Bobot Penilaian:</strong>

                    {{
                        $snapshot['bobot']['mode'] === 'simulasi'
                            ? 'Simulasi AHP'
                            : 'Bobot Artikel'
                    }}
                </p>

                <p class="text-sm">
                    <strong>Bobot C1–C5:</strong>

                    @foreach (
                        $snapshot['bobot']['nilai']
                        as $i => $bobot
                    )

                        C{{ $i + 1 }}=
                        {{
                            number_format(
                                $bobot,
                                4,
                                ',',
                                '.'
                            )
                        }}

                        {{ $loop->last ? '' : '; ' }}

                    @endforeach
                </p>

                <p class="text-sm">
                    <strong>λ:</strong>

                    {{
                        number_format(
                            $snapshot['lambda'],
                            1,
                            ',',
                            '.'
                        )
                    }}
                </p>

                @if (
                    $snapshot['bobot']['mode']
                    === 'simulasi'
                )

                    <p class="text-sm">
                        <strong>CR simulasi:</strong>

                        {{
                            number_format(
                                $snapshot['bobot']['cr_simulasi'],
                                5,
                                ',',
                                '.'
                            )
                        }}
                    </p>

                @endif

                <p class="text-xs text-slate-600">
                    {{ $snapshot['bobot']['catatan'] }}
                </p>

                @foreach (
                    $snapshot['lingkup']['catatan']
                    as $catatan
                )

                    <p class="text-xs text-amber-700">
                        {{ $catatan }}
                    </p>

                @endforeach

                <p class="break-all text-xs text-slate-500">

                    <strong>Kode Pemeriksaan Arsip:</strong>

                    {{ $riwayat->checksum_sha256 }}

                </p>

                <p class="text-xs text-slate-500">

                    Pemeriksaan Keutuhan Arsip mendeteksi perubahan isi
                    Salinan Hasil Perhitungan tanpa pembaruan Pemeriksaan Keutuhan Arsip;
                    bukan pengganti audit keamanan Data Tersimpan.

                </p>

            </div>

        </section>

        <!-- DATA ASLI SAAT DISIMPAN -->
        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>
                    <h2 class="bc-panel-title">
                        Data C1–C5 Saat Riwayat Disimpan
                    </h2>

                    <p class="bc-panel-desc">
                        Nilai ini tidak diambil ulang
                        dari daftar calon penerima.
                    </p>
                </div>

            </div>

            <div class="bc-panel-body">

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:830px"
                    >

                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Calon Penerima</th>
                                <th>C1 (Rp)</th>
                                <th>C2</th>
                                <th>C3</th>
                                <th>C4</th>
                                <th>C5</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach (
                                $snapshot['data_asli']
                                as $item
                            )

                                <tr>

                                    <td class="font-bold">
                                        {{ $item['kode'] }}
                                    </td>

                                    <td>
                                        {{ $item['nama'] }}
                                    </td>

                                    <td>
                                        {{
                                            number_format(
                                                $item['penghasilan_orang_tua'],
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </td>

                                    <td>
                                        {{ $item['jumlah_tanggungan'] }}
                                    </td>

                                    <td>
                                        {{ $item['nilai_akademik'] }}
                                    </td>

                                    <td>
                                        {{ $item['kondisi_tempat_tinggal'] }}
                                    </td>

                                    <td>
                                        {{ $item['prestasi_non_akademik'] }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- HASIL WASPAS SAAT DISIMPAN -->
        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>
                    <h2 class="bc-panel-title">
                        Hasil WASPAS yang Diarsipkan
                    </h2>

                    <p class="bc-panel-desc">
                        WSM (S), WPM (P), Qi,
                        dan peringkat dari waktu penyimpanan.
                    </p>
                </div>

            </div>

            <div class="bc-panel-body">

                <div class="bc-table-scroll">

                    <table
                        class="bc-table"
                        style="min-width:780px"
                    >

                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>WSM (S)</th>
                                <th>WPM (P)</th>
                                <th>Qi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach (
                                $snapshot['hasil']['peringkat']
                                as $hasil
                            )

                                <tr>

                                    <td class="font-bold">
                                        #{{ $hasil['peringkat'] }}
                                    </td>

                                    <td>
                                        {{ $hasil['kode'] }}
                                    </td>

                                    <td>
                                        {{ $hasil['nama'] }}
                                    </td>

                                    <td>
                                        {{
                                            number_format(
                                                $hasil['s'],
                                                6,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            number_format(
                                                $hasil['p'],
                                                6,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </td>

                                    <td class="font-extrabold">
                                        {{
                                            number_format(
                                                $hasil['q'],
                                                6,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <p class="mt-4 text-xs leading-6 text-slate-600">

                    Hasil ini adalah rekomendasi
                    pendukung keputusan, bukan
                    penetapan otomatis penerima beasiswa.

                    @if (
                        $snapshot['lingkup']['kode']
                        === 'gabungan'
                    )

                        Lingkup Gabungan hanya untuk
                        demonstrasi karena data penelitian
                        dan pengajuan siswa dapat tercampur.

                    @elseif (
                        $snapshot['lingkup']['kode']
                        === 'artikel'
                    )

                        Alternatif A1–A5 adalah
                        data simulasi penelitian.

                    @endif

                </p>

                <p class="mt-4 text-xs text-slate-600">

                    Kelompok 4 · Bagas Dwi Prasojo ·
                    Muhammad Azis · Muhammad Asyhadullah

                </p>

            </div>

        </section>

    @endif

</main>

@endsection
