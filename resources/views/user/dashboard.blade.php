
@extends('layouts.utama')

@section('title', 'Dashboard User - Beasiswa Cerdas')

@section('content')

@php
    // Nama akun yang sedang login
    $namaUser = $akun->nama_lengkap
        ?: $akun->username;
@endphp

<!-- =====================================
     NAVBAR USER
===================================== -->

<header class="bg-white border-b border-emerald-100">

    <div class="max-w-6xl mx-auto px-5 py-5
                flex flex-wrap items-center
                justify-between gap-4">

        <!-- LOGO -->
        <a
            href="{{ route('user.dashboard') }}"
            class="text-xl font-extrabold text-[#105447]"
        >
            ✦ Beasiswa Cerdas

            <span class="block text-xs font-normal
                         text-slate-500">
                Portal User
            </span>
        </a>

        <!-- MENU -->
        <nav class="flex flex-wrap gap-5
                    items-center text-sm font-semibold">

            <a
                href="{{ route('user.dashboard') }}"
                class="text-emerald-700"
            >
                Dashboard
            </a>

            <a
                href="#berkas"
                class="text-slate-600 hover:text-emerald-700"
            >
                Status Berkas
            </a>

            <a
                href="#pengumuman"
                class="text-slate-600 hover:text-emerald-700"
            >
                Pengumuman
            </a>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-xl bg-red-50
                           px-4 py-2 text-red-700"
                >
                    Logout
                </button>

            </form>

        </nav>

    </div>

</header>

<!-- =====================================
     KONTEN DASHBOARD
===================================== -->

<main class="max-w-6xl mx-auto px-5 py-9">

    <!-- SAMBUTAN -->
    <section class="bg-[#103F36]
                    rounded-3xl p-8 text-white">

        <div class="flex flex-wrap justify-between
                    items-center gap-6">

            <div>

                <p class="text-sm text-emerald-200">
                    Selamat datang,
                </p>

                <h1 class="text-3xl font-extrabold mt-2">
                    {{ $namaUser }}
                </h1>

                <p class="text-sm text-emerald-100 mt-4">
                    Pantau status pengajuan beasiswa
                    milik akunmu di sini.
                </p>

                @if ($pengajuan && $pengajuan->nisn)

                    <p class="text-xs text-emerald-200 mt-3">
                        NISN: {{ $pengajuan->nisn }}
                    </p>

                @endif

            </div>

            <!-- STATUS TERBARU -->
            <div class="bg-white/10 border border-white/20
                        rounded-2xl p-5 w-full sm:w-72">

                <p class="text-xs font-semibold
                          text-emerald-200">
                    STATUS PENGAJUAN
                </p>

                <h2 class="text-xl font-extrabold mt-2">
                    {{ $infoStatus['judul'] }}
                </h2>

                <p class="text-sm text-emerald-100
                          leading-6 mt-3">
                    {{ $infoStatus['keterangan'] }}
                </p>

            </div>

        </div>

    </section>

    <!-- =====================================
         RINGKASAN DATA
    ===================================== -->

    <section class="grid md:grid-cols-3
                    gap-5 mt-7">

        <!-- STATUS -->
        <div class="bg-white border border-slate-100
                    rounded-2xl p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Status Saat Ini
            </p>

            <span
                class="inline-block rounded-full
                       px-4 py-2 text-sm font-bold mt-4
                       {{ $infoStatus['warna'] }}"
            >
                {{ $infoStatus['judul'] }}
            </span>

            <p class="text-xs text-slate-500 mt-4">
                Status sesuai data pengajuan di MySQL.
            </p>

        </div>

        <!-- JUMLAH DOKUMEN -->
        <div class="bg-white border border-slate-100
                    rounded-2xl p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Dokumen Wajib Tersimpan
            </p>

            <h3 class="text-3xl font-extrabold
                       text-[#103F36] mt-4">
                {{ $jumlahTerunggah }}
                /
                {{ $jumlahWajib }}
            </h3>

            <p class="text-xs text-slate-500 mt-4">
                Berkas tersimpan belum tentu
                lolos pemeriksaan Admin.
            </p>

        </div>

        <!-- TANGGAL -->
        <div class="bg-white border border-slate-100
                    rounded-2xl p-6 shadow-sm">

            <p class="text-sm text-slate-500">
                Tanggal Pengiriman
            </p>

            <h3 class="text-lg font-bold
                       text-[#103F36] mt-4">

                @if ($pengajuan && $pengajuan->diajukan_pada)

                    {{ date(
                        'd-m-Y H:i',
                        strtotime($pengajuan->diajukan_pada)
                    ) }}

                @else

                    Belum dikirim

                @endif

            </h3>

            <p class="text-xs text-slate-500 mt-4">
                Dicatat saat pendaftaran dikirim.
            </p>

        </div>

    </section>

    <!-- =====================================
         TAHAPAN DAN INFORMASI PENGAJUAN
    ===================================== -->

    <section class="grid lg:grid-cols-2
                    gap-6 mt-8">

        <!-- TAHAPAN SELEKSI -->
        <div class="bg-white border border-slate-100
                    rounded-2xl p-7 shadow-sm">

            <h2 class="text-xl font-extrabold
                       text-[#103F36]">
                Tahapan Seleksi
            </h2>

            <p class="text-sm text-slate-500 mt-2">
                Tahapan berdasarkan status
                pengajuan yang tersimpan.
            </p>

            <div class="space-y-6 mt-7">

                @foreach ($tahapan as $nomor => $tahap)

                    <div class="flex items-start gap-4">

                        <div class="w-10 h-10 shrink-0
                                    rounded-full flex
                                    items-center justify-center
                                    font-bold
                                    {{ $nomor === 0
                                       ? 'bg-[#116759] text-white'
                                       : 'bg-slate-100 text-slate-600' }}">

                            {{ $nomor === 0
                                ? '✓'
                                : $nomor + 1 }}

                        </div>

                        <div>

                            <p class="font-semibold text-slate-800">
                                {{ $tahap['nama'] }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $tahap['status'] }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        <!-- PENGAJUAN SAYA -->
        <div class="bg-white border border-slate-100
                    rounded-2xl p-7 shadow-sm">

            <h2 class="text-xl font-extrabold
                       text-[#103F36]">
                Pengajuan Saya
            </h2>

            <p class="text-sm leading-7
                      text-slate-600 mt-4">
                {{ $infoStatus['keterangan'] }}
            </p>

            <!-- INFORMASI AKUN -->
            <div class="bg-slate-50 rounded-xl
                        p-5 mt-6 space-y-3 text-sm">

                <div>
                    <p class="text-xs text-slate-500">
                        Username
                    </p>

                    <p class="font-bold mt-1">
                        {{ $akun->username }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500">
                        Email
                    </p>

                    <p class="font-bold mt-1 break-words">
                        {{ $akun->email ?: 'Belum tersedia' }}
                    </p>
                </div>

                @if ($pengajuan)

                    <div>
                        <p class="text-xs text-slate-500">
                            Asal Sekolah
                        </p>

                        <p class="font-bold mt-1">
                            {{ $pengajuan->asal_sekolah ?: '-' }}
                        </p>
                    </div>

                @endif

            </div>

            <!-- CATATAN ADMIN -->
            @if ($pengajuan && $pengajuan->catatan_admin)

                <div class="bg-amber-50 border
                            border-amber-200
                            rounded-xl p-5 mt-6">

                    <p class="font-bold text-amber-900">
                        Catatan Admin
                    </p>

                    <p class="text-sm text-amber-900
                              leading-7 mt-3 whitespace-pre-wrap">
                        {{ $pengajuan->catatan_admin }}
                    </p>

                </div>

            @elseif ($status === 'diajukan')

                <p class="text-xs text-slate-500 mt-6">
                    Belum ada catatan pemeriksaan
                    dari Admin.
                </p>

            @endif

            <!-- FORMULIR BELUM DIPINDAHKAN -->
            <div class="bg-amber-50 border
                        border-amber-200
                        rounded-xl p-5 mt-6">

                <p class="font-bold text-amber-900">
                    Formulir Laravel
                </p>

                <p class="text-sm text-amber-900
                          leading-7 mt-2">

                    Fitur mengisi dan memperbaiki
                    formulir masih dalam proses
                    migrasi ke Laravel.

                    Data pengajuan yang sudah
                    tersimpan tetap aman dan
                    dapat dilihat di dashboard ini.

                </p>

            </div>

        </div>

    </section>

    <!-- =====================================
         DAFTAR DOKUMEN
    ===================================== -->

    <section id="berkas"
             class="bg-white border border-slate-100
                    rounded-2xl p-7 shadow-sm mt-8">

        <div class="flex flex-wrap justify-between
                    gap-3 items-center">

            <h2 class="text-xl font-extrabold
                       text-[#103F36]">
                Berkas Pengajuan
            </h2>

            <p class="text-xs text-slate-500">
                Status penyimpanan dokumen
            </p>

        </div>

        <div class="grid md:grid-cols-2 gap-4 mt-6">

            @foreach ($dokumen as $jenis => $item)

                <div class="bg-slate-50 rounded-xl p-5
                            flex flex-wrap justify-between
                            items-center gap-4">

                    <div>

                        <p class="text-sm font-bold">
                            {{ $item['nama'] }}
                        </p>

                        @if ($item['ada'])

                            <p class="text-xs text-emerald-700 mt-2">
                                ✓ Tersimpan
                            </p>

                        @else

                            <p class="text-xs text-slate-500 mt-2">
                                Belum ada berkas
                            </p>

                        @endif

                        <p class="text-xs text-slate-500 mt-1">

                            {{ $item['wajib']
                                ? 'Dokumen wajib'
                                : 'Dokumen opsional' }}

                        </p>

                    </div>

                    <!-- UNDUH MELALUI LARAVEL -->
                    @if ($item['ada'])

                        <a
                            href="{{ route('user.berkas', ['jenis' => $jenis]) }}"
                            class="text-sm font-bold
                                   text-emerald-700 underline"
                        >
                            Unduh
                        </a>

                    @endif

                </div>

            @endforeach

        </div>

        <p class="text-xs text-slate-500 mt-5
                  leading-6">

            Dokumen yang tersedia dapat diunduh
            menggunakan sesi Laravel milikmu.
            Dokumen pengguna lain tidak dapat
            diakses melalui halaman ini.

        </p>

    </section>

    <!-- =====================================
         PENGUMUMAN
    ===================================== -->

    <section id="pengumuman"
             class="bg-[#E8F5F0] border
                    border-emerald-100 rounded-2xl
                    p-7 mt-8">

        <h2 class="text-xl font-extrabold
                   text-[#103F36]">
            Pengumuman Hasil Seleksi
        </h2>

        <p class="text-sm text-slate-600
                  leading-7 mt-4">

            Hasil akhir seleksi beasiswa
            belum diumumkan melalui portal ini.

            Status

            <strong>{{ $infoStatus['judul'] }}</strong>

            bukan keputusan akhir penerimaan.

            Proses AHP–WASPAS dan penetapan penerima
            masih memerlukan pengolahan
            dan keputusan seleksi oleh Admin.

        </p>

    </section>

</main>

@endsection
