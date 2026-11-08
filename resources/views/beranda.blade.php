
@extends('layouts.utama')

@section('title', 'Beranda - Beasiswa Cerdas')

@section('content')

@php
    $menuBeranda = [
        ['id' => 'beranda', 'nama' => 'Beranda'],
        ['id' => 'tentang', 'nama' => 'Tentang Sistem'],
        ['id' => 'kriteria', 'nama' => 'Kriteria'],
        ['id' => 'metode-ahp', 'nama' => 'Metode AHP'],
        ['id' => 'metode-waspas', 'nama' => 'Metode WASPAS'],
        ['id' => 'alur', 'nama' => 'Alur Seleksi'],
        ['id' => 'dokumen', 'nama' => 'Persyaratan'],
    ];

    $roleLogin = auth()->check()
        ? auth()->user()->role
        : null;

    $routeDashboard = $roleLogin === 'admin'
        ? 'admin.dashboard'
        : 'user.dashboard';
@endphp

<style>
    html {
        scroll-behavior: smooth;
    }

    .bc-section {
        scroll-margin-top: 95px;
    }

    .bc-nav-link[data-active="true"] {
        color: #047857;
        background: #ecfdf5;
    }
</style>

<!-- =====================================
     NAVBAR BERANDA
===================================== -->

<header class="sticky top-0 z-40
               border-b border-emerald-100
               bg-white/95 shadow-sm backdrop-blur">

    <div class="mx-auto flex max-w-[1440px]
                items-center justify-between
                gap-4 px-5 py-4">

        <!-- LOGO -->
        <a href="#beranda"
           class="shrink-0 text-lg font-extrabold
                  text-[#105447] sm:text-xl">

            ✦ Beasiswa Cerdas

        </a>

        <!-- MENU DESKTOP -->
        <nav
            aria-label="Navigasi publik"
            class="hidden items-center gap-1
                   xl:flex"
        >

            @foreach ($menuBeranda as $menu)

                <a
                    href="#{{ $menu['id'] }}"
                    data-nav-section="{{ $menu['id'] }}"
                    class="bc-nav-link rounded-lg
                           px-2.5 py-2 text-xs
                           font-semibold text-slate-600
                           transition hover:bg-emerald-50
                           hover:text-emerald-700"
                >
                    {{ $menu['nama'] }}
                </a>

            @endforeach

        </nav>

        <!-- AKSI DESKTOP -->
        <div class="hidden shrink-0 items-center
                    gap-2 xl:flex">

            @guest

                <a
                    href="{{ route('login') }}"
                    class="rounded-xl border
                           border-emerald-700
                           px-4 py-2 text-sm
                           font-bold text-emerald-800
                           hover:bg-emerald-50"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="rounded-xl bg-[#116759]
                           px-4 py-2 text-sm
                           font-bold text-white
                           hover:bg-[#0e5145]"
                >
                    Daftar
                </a>

            @else

                <a
                    href="{{ route($routeDashboard) }}"
                    class="rounded-xl bg-[#116759]
                           px-4 py-2 text-sm
                           font-bold text-white"
                >
                    Dashboard
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl border
                               border-slate-200
                               px-3 py-2 text-sm
                               font-semibold text-slate-600
                               hover:bg-slate-50"
                    >
                        Logout
                    </button>
                </form>

            @endguest

        </div>

        <!-- TOMBOL HAMBURGER MOBILE -->
        <button
            id="bcMenuToggle"
            type="button"
            aria-label="Buka menu navigasi"
            aria-controls="bcMenuMobile"
            aria-expanded="false"
            class="flex h-11 w-11 items-center
                   justify-center rounded-xl
                   border border-emerald-200
                   text-2xl text-[#105447]
                   xl:hidden"
        >
            <span id="bcMenuIcon" aria-hidden="true">
                ☰
            </span>
        </button>

    </div>

    <!-- =================================
         MENU MOBILE
    ================================== -->

    <nav
        id="bcMenuMobile"
        aria-label="Navigasi mobile"
        class="hidden border-t border-emerald-100
               bg-white px-5 pb-5 pt-3 xl:hidden"
    >

        <div class="mx-auto max-w-2xl space-y-1">

            @foreach ($menuBeranda as $menu)

                <a
                    href="#{{ $menu['id'] }}"
                    data-nav-section="{{ $menu['id'] }}"
                    class="bc-nav-link block rounded-xl
                           px-4 py-3 text-sm
                           font-semibold text-slate-700
                           hover:bg-emerald-50"
                >
                    {{ $menu['nama'] }}
                </a>

            @endforeach

            <div class="my-3 border-t
                        border-slate-100"></div>

            @guest

                <div class="grid grid-cols-2 gap-3">

                    <a
                        href="{{ route('login') }}"
                        class="rounded-xl border
                               border-emerald-600
                               px-4 py-3 text-center
                               text-sm font-bold
                               text-emerald-800"
                    >
                        Login
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-[#116759]
                               px-4 py-3 text-center
                               text-sm font-bold text-white"
                    >
                        Daftar
                    </a>

                </div>

            @else

                <p class="px-4 py-2 text-xs text-slate-500">
                    Login sebagai:
                    <strong>
                        {{ auth()->user()->nama_lengkap
                            ?: auth()->user()->username }}
                    </strong>
                </p>

                <a
                    href="{{ route($routeDashboard) }}"
                    class="block rounded-xl bg-[#116759]
                           px-4 py-3 text-center
                           text-sm font-bold text-white"
                >
                    Buka Dashboard
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="mt-2"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-xl border
                               border-slate-200
                               px-4 py-3 text-sm
                               font-semibold"
                    >
                        Logout
                    </button>
                </form>

            @endguest

        </div>

    </nav>

</header>

<main>

    <!-- =====================================
         HERO / BERANDA
    ===================================== -->

    <section
        id="beranda"
        class="bc-section bg-[#103F36] text-white"
    >

        <div class="mx-auto grid max-w-6xl
                    items-center gap-12 px-5 py-20
                    lg:grid-cols-2">

            <div>

                <p class="mb-4 text-sm font-bold
                          tracking-wide text-emerald-300">
                    SISTEM PENDUKUNG KEPUTUSAN BEASISWA
                </p>

                <h1 class="text-4xl font-extrabold
                           leading-tight md:text-5xl">

                    Seleksi Lebih Terukur,
                    Kesempatan Lebih Adil.

                </h1>

                <p class="mt-6 text-base leading-8
                          text-emerald-100">

                    Beasiswa Cerdas membantu proses
                    penilaian kelayakan calon penerima
                    beasiswa kurang mampu.

                    Metode AHP digunakan untuk
                    menentukan bobot kriteria,
                    sedangkan WASPAS digunakan
                    untuk pemeringkatan alternatif.

                </p>

                <div class="mt-8 flex flex-wrap gap-3">

                    <a
                        href="#kriteria"
                        class="rounded-xl bg-amber-300
                               px-6 py-3 font-bold
                               text-[#103F36]
                               hover:bg-amber-200"
                    >
                        Lihat Kriteria
                    </a>

                    <a
                        href="#alur"
                        class="rounded-xl border
                               border-white/50 px-6 py-3
                               font-semibold text-white
                               hover:bg-white/10"
                    >
                        Pelajari Alur
                    </a>

                </div>

            </div>

            <!-- KARTU METODE -->
            <div class="rounded-3xl border
                        border-white/20 bg-white/10
                        p-7">

                <h2 class="text-xl font-bold">
                    Metode Penilaian
                </h2>

                <div class="mt-6 space-y-4">

                    <a
                        href="#metode-ahp"
                        class="block rounded-2xl
                               bg-white/10 p-5
                               hover:bg-white/15"
                    >

                        <p class="text-lg font-bold
                                  text-emerald-300">
                            AHP
                        </p>

                        <p class="mt-2 text-sm leading-7">
                            Analytical Hierarchy Process
                            untuk pembobotan kriteria.
                        </p>

                    </a>

                    <a
                        href="#metode-waspas"
                        class="block rounded-2xl
                               bg-white/10 p-5
                               hover:bg-white/15"
                    >

                        <p class="text-lg font-bold
                                  text-emerald-300">
                            WASPAS
                        </p>

                        <p class="mt-2 text-sm leading-7">
                            Weighted Aggregated Sum Product
                            Assessment untuk menghitung
                            preferensi dan peringkat.
                        </p>

                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- =====================================
         TENTANG SISTEM
    ===================================== -->

    <section
        id="tentang"
        class="bc-section mx-auto max-w-6xl
               px-5 py-16"
    >

        <p class="text-sm font-bold
                  text-emerald-700">
            TENTANG SISTEM
        </p>

        <h2 class="mt-3 text-3xl font-extrabold
                   text-[#103F36]">
            Apa Itu Beasiswa Cerdas?
        </h2>

        <p class="mt-6 max-w-4xl text-slate-600
                  leading-8">

            Beasiswa Cerdas merupakan aplikasi
            Sistem Pendukung Keputusan untuk
            membantu menentukan kelayakan
            calon penerima beasiswa kurang mampu.

            Sistem menggabungkan metode AHP
            dan WASPAS untuk mendukung proses
            pengambilan keputusan yang lebih terukur.

        </p>

        <p class="mt-4 max-w-4xl text-slate-600
                  leading-8">

            Selain menyediakan informasi metode,
            aplikasi memiliki fasilitas pendaftaran,
            pengajuan, unggah dokumen, dan
            verifikasi administrasi oleh Admin.

            Hasil pemeringkatan nantinya digunakan
            sebagai bahan pertimbangan, bukan
            penetapan penerima secara otomatis.

        </p>

    </section>

    <!-- =====================================
         KRITERIA PENILAIAN
    ===================================== -->

    <section
        id="kriteria"
        class="bc-section bg-white py-16"
    >

        <div class="mx-auto max-w-6xl px-5">

            <p class="text-sm font-bold
                      text-emerald-700">
                KRITERIA PENILAIAN
            </p>

            <h2 class="mt-3 text-3xl
                       font-extrabold text-[#103F36]">
                Lima Kriteria Beasiswa
            </h2>

            <p class="mt-4 text-slate-600">
                Kriteria yang menjadi dasar
                penilaian kelayakan.
            </p>

            @php
                $kriteria = [
                    [
                        'kode' => 'C1',
                        'jenis' => 'Cost',
                        'nama' => 'Penghasilan Orang Tua',
                        'deskripsi' => 'Jumlah penghasilan orang tua atau wali per bulan.',
                    ],
                    [
                        'kode' => 'C2',
                        'jenis' => 'Benefit',
                        'nama' => 'Jumlah Tanggungan',
                        'deskripsi' => 'Jumlah anggota keluarga yang menjadi tanggungan.',
                    ],
                    [
                        'kode' => 'C3',
                        'jenis' => 'Benefit',
                        'nama' => 'Nilai Akademik',
                        'deskripsi' => 'Nilai rapor pada skala 0 sampai 100.',
                    ],
                    [
                        'kode' => 'C4',
                        'jenis' => 'Benefit',
                        'nama' => 'Kondisi Tempat Tinggal',
                        'deskripsi' => 'Penilaian kondisi tempat tinggal menggunakan skor 1 sampai 5.',
                    ],
                    [
                        'kode' => 'C5',
                        'jenis' => 'Benefit',
                        'nama' => 'Prestasi Non-Akademik',
                        'deskripsi' => 'Penilaian prestasi non-akademik menggunakan skor 1 sampai 5.',
                    ],
                ];
            @endphp

            <div class="mt-8 grid gap-5
                        sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($kriteria as $item)

                    <article
                        class="rounded-2xl border
                               border-slate-200 p-6
                               transition hover:border-emerald-400
                               hover:shadow-md"
                    >

                        <span class="text-sm font-bold
                                     text-emerald-700">

                            {{ $item['kode'] }}
                            ·
                            {{ $item['jenis'] }}

                        </span>

                        <h3 class="mt-3 text-lg font-bold
                                   text-[#103F36]">
                            {{ $item['nama'] }}
                        </h3>

                        <p class="mt-3 text-sm leading-7
                                  text-slate-600">
                            {{ $item['deskripsi'] }}
                        </p>

                    </article>

                @endforeach

            </div>

        </div>

    </section>

    <!-- =====================================
         PENJELASAN AHP
    ===================================== -->

    <section
        id="metode-ahp"
        class="bc-section mx-auto max-w-6xl
               px-5 py-16"
    >

        <p class="text-sm font-bold
                  text-emerald-700">
            METODE PENELITIAN
        </p>

        <h2 class="mt-3 text-3xl
                   font-extrabold text-[#103F36]">
            Analytical Hierarchy Process (AHP)
        </h2>

        <div class="mt-6 grid gap-8 lg:grid-cols-2">

            <div>

                <p class="leading-8 text-slate-600">

                    AHP merupakan metode
                    pengambilan keputusan multikriteria
                    yang menggunakan perbandingan
                    berpasangan untuk menentukan
                    tingkat kepentingan setiap kriteria.

                </p>

                <p class="mt-4 leading-8 text-slate-600">

                    Bobot yang dihasilkan AHP
                    menjadi dasar pembobotan
                    dalam perhitungan WASPAS.

                </p>

            </div>

            <div class="rounded-2xl border
                        border-emerald-100
                        bg-emerald-50 p-6">

                <h3 class="font-bold text-[#103F36]">
                    Peran AHP dalam Sistem
                </h3>

                <p class="mt-3 text-sm leading-7
                          text-slate-700">

                    Mengukur tingkat kepentingan
                    kriteria dan menghasilkan bobot
                    yang digunakan dalam pemeringkatan.

                </p>

                <p class="mt-4 text-xs leading-6
                          text-emerald-800">

                    Halaman ini menjelaskan metode.
                    Modul perhitungan interaktif
                    AHP akan tersedia setelah
                    implementasinya selesai.

                </p>

            </div>

        </div>

    </section>

    <!-- =====================================
         PENJELASAN WASPAS
    ===================================== -->

    <section
        id="metode-waspas"
        class="bc-section bg-[#F0F8F4] py-16"
    >

        <div class="mx-auto max-w-6xl px-5">

            <p class="text-sm font-bold
                      text-emerald-700">
                METODE PENELITIAN
            </p>

            <h2 class="mt-3 text-3xl
                       font-extrabold text-[#103F36]">
                Weighted Aggregated Sum Product
                Assessment (WASPAS)
            </h2>

            <p class="mt-6 max-w-4xl
                      leading-8 text-slate-600">

                WASPAS merupakan metode
                pengambilan keputusan multikriteria
                yang menggabungkan pendekatan
                penjumlahan terbobot dan
                perkalian terbobot.

                Metode ini digunakan untuk
                memperoleh nilai preferensi
                setiap alternatif.

            </p>

            <div class="mt-8 grid gap-5 md:grid-cols-3">

                <div class="rounded-2xl bg-white p-6
                            shadow-sm">

                    <p class="font-bold text-emerald-700">
                        01 · Normalisasi
                    </p>

                    <p class="mt-3 text-sm leading-7
                              text-slate-600">
                        Nilai alternatif disesuaikan
                        berdasarkan jenis Cost
                        atau Benefit.
                    </p>

                </div>

                <div class="rounded-2xl bg-white p-6
                            shadow-sm">

                    <p class="font-bold text-emerald-700">
                        02 · Nilai Preferensi
                    </p>

                    <p class="mt-3 text-sm leading-7
                              text-slate-600">
                        Nilai normalisasi dihitung
                        menggunakan bobot setiap kriteria.
                    </p>

                </div>

                <div class="rounded-2xl bg-white p-6
                            shadow-sm">

                    <p class="font-bold text-emerald-700">
                        03 · Pemeringkatan
                    </p>

                    <p class="mt-3 text-sm leading-7
                              text-slate-600">
                        Alternatif diurutkan berdasarkan
                        nilai preferensi yang dihasilkan.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- =====================================
         ALUR SELEKSI
    ===================================== -->

    <section
        id="alur"
        class="bc-section mx-auto max-w-6xl
               px-5 py-16"
    >

        <p class="text-sm font-bold
                  text-emerald-700">
            ALUR SELEKSI
        </p>

        <h2 class="mt-3 text-3xl
                   font-extrabold text-[#103F36]">
            Bagaimana Proses Seleksinya?
        </h2>

        @php
            $alur = [
                [
                    'nomor' => '01',
                    'judul' => 'Buat Akun',
                    'teks' => 'Calon pendaftar membuat akun User.',
                ],
                [
                    'nomor' => '02',
                    'judul' => 'Isi Pengajuan',
                    'teks' => 'User mengisi data dan mengunggah dokumen.',
                ],
                [
                    'nomor' => '03',
                    'judul' => 'Verifikasi Admin',
                    'teks' => 'Admin memeriksa kelengkapan dan kesesuaian data.',
                ],
                [
                    'nomor' => '04',
                    'judul' => 'Penilaian',
                    'teks' => 'Alternatif dapat diproses menggunakan AHP dan WASPAS.',
                ],
            ];
        @endphp

        <div class="mt-8 grid gap-5
                    sm:grid-cols-2 lg:grid-cols-4">

            @foreach ($alur as $item)

                <div class="rounded-2xl border
                            border-slate-200 bg-white p-6">

                    <p class="text-2xl font-extrabold
                              text-emerald-700">
                        {{ $item['nomor'] }}
                    </p>

                    <h3 class="mt-3 font-bold
                               text-[#103F36]">
                        {{ $item['judul'] }}
                    </h3>

                    <p class="mt-3 text-sm
                              leading-7 text-slate-600">
                        {{ $item['teks'] }}
                    </p>

                </div>

            @endforeach

        </div>

    </section>

    <!-- =====================================
         PERSYARATAN DOKUMEN
    ===================================== -->

    <section
        id="dokumen"
        class="bc-section bg-[#E8F5F0] py-16"
    >

        <div class="mx-auto max-w-6xl px-5">

            <p class="text-sm font-bold
                      text-emerald-700">
                PERSYARATAN DOKUMEN
            </p>

            <h2 class="mt-3 text-3xl
                       font-extrabold text-[#103F36]">
                Dokumen yang Perlu Disiapkan
            </h2>

            <div class="mt-7 rounded-2xl
                        border border-emerald-100
                        bg-white p-7">

                <div class="grid gap-5
                            sm:grid-cols-2">

                    <p class="font-medium">
                        ✓ Kartu Keluarga
                    </p>

                    <p class="font-medium">
                        ✓ Bukti Penghasilan / SKTM
                    </p>

                    <p class="font-medium">
                        ✓ Rapor Terakhir
                    </p>

                    <p class="font-medium">
                        ✓ Sertifikat Prestasi (opsional)
                    </p>

                </div>

                <p class="mt-6 text-sm
                          leading-7 text-slate-600">

                    Dokumen menggunakan format
                    PDF, JPG, atau PNG, dengan
                    ukuran maksimal 2 MB per berkas.

                </p>

                <div class="mt-6">

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="inline-block rounded-xl
                                   bg-[#116759] px-6 py-3
                                   text-sm font-bold text-white"
                        >
                            Daftar Sekarang
                        </a>

                    @else

                        <a
                            href="{{ route($routeDashboard) }}"
                            class="inline-block rounded-xl
                                   bg-[#116759] px-6 py-3
                                   text-sm font-bold text-white"
                        >
                            Buka Dashboard
                        </a>

                    @endguest

                </div>

            </div>

        </div>

    </section>

</main>

<!-- =====================================
     JAVASCRIPT NAVIGASI
===================================== -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tombol = document.getElementById('bcMenuToggle');
    const menuMobile = document.getElementById('bcMenuMobile');
    const ikon = document.getElementById('bcMenuIcon');

    function aturMenu(terbuka) {

        menuMobile.classList.toggle('hidden', !terbuka);

        tombol.setAttribute(
            'aria-expanded',
            String(terbuka)
        );

        tombol.setAttribute(
            'aria-label',
            terbuka
                ? 'Tutup menu navigasi'
                : 'Buka menu navigasi'
        );

        ikon.textContent = terbuka ? '✕' : '☰';
    }

    tombol.addEventListener('click', function () {

        const sedangTerbuka =
            !menuMobile.classList.contains('hidden');

        aturMenu(!sedangTerbuka);
    });

    // Menutup menu setelah memilih bagian
    menuMobile.querySelectorAll('a').forEach(function (link) {

        link.addEventListener('click', function () {
            aturMenu(false);
        });
    });

    // Menutup menu dengan Escape
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            aturMenu(false);
        }
    });

    // Ketika layar diperlebar
    window.addEventListener('resize', function () {

        if (window.innerWidth >= 1280) {
            aturMenu(false);
        }
    });

    // =====================================
    // INDIKATOR MENU AKTIF
    // =====================================

    const tautan = document.querySelectorAll(
        '[data-nav-section]'
    );

    function aktifkanMenu(id) {

        tautan.forEach(function (link) {

            const aktif =
                link.dataset.navSection === id;

            link.dataset.active = aktif ? 'true' : 'false';

            if (aktif) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    }

    // Saat pertama membuka halaman
    const awal = window.location.hash
        ? window.location.hash.substring(1)
        : 'beranda';

    aktifkanMenu(awal);

    // Saat pengguna memilih link bagian
    window.addEventListener('hashchange', function () {

        const id = window.location.hash
            ? window.location.hash.substring(1)
            : 'beranda';

        aktifkanMenu(id);
    });

    // Menyesuaikan menu aktif ketika scroll
    if ('IntersectionObserver' in window) {

        const bagian = document.querySelectorAll(
            'main section[id]'
        );

        const observer = new IntersectionObserver(
            function (entries) {

                const terlihat = entries
                    .filter(function (entry) {
                        return entry.isIntersecting;
                    })
                    .sort(function (a, b) {
                        return b.intersectionRatio
                            - a.intersectionRatio;
                    });

                if (terlihat.length > 0) {
                    aktifkanMenu(terlihat[0].target.id);
                }
            },
            {
                rootMargin: '-100px 0px -55% 0px',
                threshold: 0
            }
        );

        bagian.forEach(function (section) {
            observer.observe(section);
        });
    }

});
</script>

@endsection
