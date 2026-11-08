
@extends('layouts.admin')

@section('title', 'Dashboard Admin - Beasiswa Cerdas')
@section('judul-admin', 'Dashboard Seleksi')
@section('deskripsi-admin', 'Ringkasan pengajuan dan data alternatif beasiswa.')

@section('admin-content')

@php
    $namaAdmin = $akun->nama_lengkap ?: $akun->username;

    $kartu = [
        [
            'judul' => 'Total Pengajuan Masuk',
            'nilai' => $totalPengajuan,
            'catatan' => 'Tidak termasuk draf User',
            'jenis' => 'teal',
            'ikon' => '▤',
        ],
        [
            'judul' => 'Menunggu Verifikasi',
            'nilai' => $menunggu,
            'catatan' => 'Perlu diperiksa Admin',
            'jenis' => 'blue',
            'ikon' => '◷',
        ],
        [
            'judul' => 'Terverifikasi',
            'nilai' => $terverifikasi,
            'catatan' => 'Lolos pemeriksaan administrasi',
            'jenis' => 'green',
            'ikon' => '✓',
        ],
        [
            'judul' => 'Perlu Perbaikan',
            'nilai' => $perluPerbaikan,
            'catatan' => 'Menunggu perbaikan User',
            'jenis' => 'amber',
            'ikon' => '!',
        ],
    ];
@endphp

<main class="bc-admin-main">

    <!-- SAMBUTAN -->
    <section class="bc-page-heading">

        <p class="bc-eyebrow">
            Ringkasan sistem
        </p>

        <h2 class="bc-page-title">
            Selamat Datang, {{ $namaAdmin }}!
        </h2>

        <p class="bc-page-description">
            Pantau pendaftaran, pemeriksaan administrasi,
            dan data calon penerima yang akan dinilai dalam satu halaman.
        </p>

    </section>

    <!-- STATISTIK -->
    <section class="bc-stats" aria-label="Statistik pengajuan">

        @foreach ($kartu as $item)

            <article class="bc-stat bc-stat--{{ $item['jenis'] }}">

                <div class="bc-stat-top">

                    <h3 class="bc-stat-label">
                        {{ $item['judul'] }}
                    </h3>

                    <span class="bc-stat-icon" aria-hidden="true">
                        {{ $item['ikon'] }}
                    </span>

                </div>

                <p class="bc-stat-value">
                    {{ number_format((int) $item['nilai'], 0, ',', '.') }}
                </p>

                <p class="bc-stat-foot">
                    {{ $item['catatan'] }}
                </p>

            </article>

        @endforeach

    </section>

    <!-- PENGAJUAN TERBARU -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Pengajuan Terbaru
                </h2>

                <p class="bc-panel-desc">
                    Pengajuan yang sudah dikirim oleh User
                    dan tersimpan pada penyimpanan data.
                </p>

            </div>

            <a
                href="{{ route('admin.verifikasi.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Lihat Semua Pengajuan
                <span aria-hidden="true">→</span>
            </a>

        </div>

        <div class="bc-panel-body">

            <div class="bc-table-scroll">

                <table class="bc-table">

                    <thead>
                        <tr>
                            <th scope="col">Pendaftar</th>
                            <th scope="col">Asal Sekolah</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($pengajuanTerbaru as $item)

                            <tr>

                                <td>

                                    <p class="bc-cell-title">
                                        {{ $item->nama_lengkap ?: $item->username }}
                                    </p>

                                    <p class="bc-cell-caption">
                                        ID Pengajuan: {{ $item->id }}
                                    </p>

                                </td>

                                <td>
                                    {{ $item->asal_sekolah ?: '-' }}
                                </td>

                                <td>
                                    {{ $item->diajukan_pada
                                        ? date('d-m-Y H:i', strtotime($item->diajukan_pada))
                                        : '-' }}
                                </td>

                                <td>

                                    <span class="bc-status bc-status--{{ $item->status }}">
                                        {{ $labelStatus[$item->status] ?? $item->status }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center text-slate-500">
                                    Belum ada pengajuan yang dikirim oleh User.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

    <!-- DATA ALTERNATIF WASPAS -->
    <section class="bc-panel mt-6">

        <div class="bc-panel-header">

            <div>
                <h2 class="bc-panel-title">
                    Data Calon Penerima untuk Penilaian
                </h2>

                <p class="bc-panel-desc">
                    Ringkasan data calon penerima yang tersedia
                    untuk simulasi seleksi.
                </p>
            </div>

            <a
                href="{{ route('admin.calon-penerima.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Kelola Alternatif
                <span aria-hidden="true">→</span>
            </a>

        </div>

        <div class="bc-panel-body">

            <div class="bc-mini-grid">

                <div class="bc-mini-stat">
                    <p class="bc-mini-stat-label">
                        Total Calon yang Dinilai
                    </p>

                    <p class="bc-mini-stat-value">
                        {{ $totalAlternatif }}
                    </p>
                </div>

                <div class="bc-mini-stat">
                    <p class="bc-mini-stat-label">
                        Alternatif Berkode A1–A5
                    </p>

                    <p class="bc-mini-stat-value">
                        {{ $dataJurnal }}
                    </p>
                </div>

                <div class="bc-mini-stat">
                    <p class="bc-mini-stat-label">
                        Calon Penerima Lainnya
                    </p>

                    <p class="bc-mini-stat-value">
                        {{ $dataTambahan }}
                    </p>
                </div>

            </div>

            <!-- PROGRES ALTERNATIF -->
            <div class="mt-6 flex flex-wrap justify-between gap-2 text-sm">

                <p class="font-semibold text-slate-700">
                    Target Jumlah Calon yang Dinilai
                </p>

                <p class="font-bold text-[#103F36]">
                    {{ $totalAlternatif }} / {{ $targetData }}
                </p>

            </div>

            <div
                class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100"
                role="progressbar"
                aria-label="Progres target alternatif"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuenow="{{ round($persentase) }}"
            >
                <div
                    class="h-full rounded-full bg-[#116759]"
                    style="width: {{ $persentase }}%"
                ></div>
            </div>

            <p class="bc-muted-note mt-3">

                @if ($sisaData > 0)

                    Masih diperlukan {{ $sisaData }}
                    alternatif untuk mencapai target
                    {{ $targetData }} data.

                @else

                    Target minimal {{ $targetData }}
                    alternatif sudah terpenuhi.

                @endif

                Pengajuan User tidak otomatis menjadi
                Data Calon Penerima untuk Penilaian.

            </p>

        </div>

    </section>

    <!-- INFORMASI MODUL -->
    <section class="mt-6 grid gap-5 lg:grid-cols-2">

        <!-- VERIFIKASI -->
        <article class="bc-panel">

            <div class="bc-panel-header">

                <div>
                    <h2 class="bc-panel-title">
                        Verifikasi Administrasi
                    </h2>

                    <p class="bc-panel-desc">
                        Periksa pengajuan dan dokumen pendukung.
                    </p>
                </div>

            </div>

            <div class="bc-panel-body">

                <p class="text-sm leading-7 text-slate-600">
                    Admin dapat memeriksa pengajuan,
                    mengunduh dokumen, serta memilih keputusan
                    Terverifikasi, Perlu Perbaikan, atau Ditolak.
                </p>

                <a
                    href="{{ route('admin.verifikasi.index') }}"
                    class="bc-btn bc-btn--primary mt-4"
                >
                    Buka Verifikasi
                    <span aria-hidden="true">→</span>
                </a>

            </div>

        </article>

        <!-- AHP-WASPAS -->
        <article class="bc-panel">

            <div class="bc-panel-header">

                <div>
                    <h2 class="bc-panel-title">
                        Perhitungan AHP–WASPAS
                    </h2>

                    <p class="bc-panel-desc">
                        Modul perhitungan sedang disiapkan.
                    </p>
                </div>

            </div>

            <div class="bc-panel-body">

                <p class="text-sm leading-7 text-slate-600">
                    AHP menentukan bobot kriteria dan WASPAS
                    menghasilkan pemeringkatan alternatif.

                    Menu perhitungan belum diaktifkan
                    sampai implementasinya tersedia.
                </p>

                <p class="bc-muted-note mt-4">
                    Status Terverifikasi hanya berarti lolos
                    pemeriksaan administrasi, bukan keputusan
                    akhir penerimaan beasiswa.
                </p>

            </div>

        </article>

    </section>

</main>

@endsection
