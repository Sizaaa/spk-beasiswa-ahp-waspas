@extends('layouts.utama')

@section('title', 'Beasiswa Cerdas - Sistem Pendukung Keputusan AHP & WASPAS')

@section('content')

@php
    $menuBeranda = [
        ['id' => 'beranda', 'nama' => 'Beranda'],
        ['id' => 'tentang', 'nama' => 'Tentang Sistem'],
        ['id' => 'kriteria', 'nama' => 'Kriteria Penilaian'],
        ['id' => 'metode', 'nama' => 'Metode AHP & WASPAS'],
        ['id' => 'alur', 'nama' => 'Alur Pendaftaran'],
        ['id' => 'dokumen', 'nama' => 'Persyaratan Berkas'],
    ];

    $roleLogin = auth()->check() ? auth()->user()->role : null;
    $routeDashboard = $roleLogin === 'admin' ? 'admin.dashboard' : 'user.dashboard';

    $kriteria = [
        [
            'kode' => 'C1',
            'jenis' => 'Cost',
            'nama' => 'Penghasilan Orang Tua',
            'deskripsi' => 'Total pendapatan kumulatif ayah dan ibu/wali per bulan. Semakin rendah penghasilan, prioritas bantuan semakin tinggi.',
            'ikon' => '💵',
            'badge' => 'Biaya (Cost)',
        ],
        [
            'kode' => 'C2',
            'jenis' => 'Benefit',
            'nama' => 'Jumlah Tanggungan',
            'deskripsi' => 'Jumlah anggota keluarga yang masih menjadi tanggungan hidup orang tua dalam satu Kartu Keluarga.',
            'ikon' => '👨‍👩‍👧‍👦',
            'badge' => 'Manfaat (Benefit)',
        ],
        [
            'kode' => 'C3',
            'jenis' => 'Benefit',
            'nama' => 'Nilai Rata-rata Rapor',
            'deskripsi' => 'Prestasi capaian akademik siswa pada semester terakhir (skala 0–100) sebagai indikator komitmen belajar.',
            'ikon' => '📊',
            'badge' => 'Manfaat (Benefit)',
        ],
        [
            'kode' => 'C4',
            'jenis' => 'Benefit',
            'nama' => 'Kondisi Rumah / Tinggal',
            'deskripsi' => 'Asesmen kelayakan tempat tinggal berdasarkan kepemilikan, dinding, lantai, dan fasilitas dasar (skala 1–5).',
            'ikon' => '🏠',
            'badge' => 'Manfaat (Benefit)',
        ],
        [
            'kode' => 'C5',
            'jenis' => 'Benefit',
            'nama' => 'Prestasi Non-Akademik',
            'deskripsi' => 'Penghargaan bidang olahraga, seni, sains, atau keagamaan tingkat sekolah, kota, provinsi, hingga nasional (skala 1–5).',
            'ikon' => '🏆',
            'badge' => 'Manfaat (Benefit)',
        ],
    ];

    $alur = [
        [
            'nomor' => '01',
            'judul' => 'Registrasi Akun Siswa',
            'teks' => 'Calon pendaftar membuat akun mandiri dengan memasukkan username, email, dan kata sandi.',
            'ikon' => '👤',
        ],
        [
            'nomor' => '02',
            'judul' => 'Pengisian Formulir & Berkas',
            'teks' => 'Melengkapi data diri, nilai rapor, penghasilan orang tua, dan mengunggah berkas scan pendukung.',
            'ikon' => '📝',
        ],
        [
            'nomor' => '03',
            'judul' => 'Verifikasi Berkas oleh Panitia',
            'teks' => 'Tim administrator memeriksa keabsahan dokumen. Berkas yang lolos dinyatakan Siap Seleksi.',
            'ikon' => '🔍',
        ],
        [
            'nomor' => '04',
            'judul' => 'Komputasi SPK AHP & WASPAS',
            'teks' => 'Sistem menghitung bobot kriteria berpasangan dan perangkingan multi-kriteria untuk penetapan penerima.',
            'ikon' => '⚡',
        ],
    ];
@endphp

<style>
    html {
        scroll-behavior: smooth;
    }
    .bc-section {
        scroll-margin-top: 80px;
    }
    .bc-nav-link[data-active="true"] {
        color: #103F36;
        font-weight: 700;
        background: #E8F7F2;
    }
</style>

<!-- NAVBAR BERANDA SAAS (HEIGHT ~72px, GLASSMORPHISM) -->
<header class="sticky top-0 z-40 border-b border-[#E3EBE6] bg-white/90 backdrop-blur-md transition-all">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-3.5">

        <!-- LOGO & SYSTEM BADGE -->
        <a href="#beranda" class="flex items-center gap-3 shrink-0 group">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#103F36] to-[#20A67A] text-white shadow-md shadow-[#20A67A]/20">
                <span class="text-base font-extrabold">✦</span>
            </div>
            <div>
                <span class="text-base font-extrabold tracking-tight text-[#103F36] block leading-tight">
                    Beasiswa Cerdas
                </span>
                <span class="text-[10px] font-semibold text-[#116759] uppercase tracking-wider block">
                    SPK AHP–WASPAS
                </span>
            </div>
        </a>

        <!-- MENU DESKTOP -->
        <nav aria-label="Navigasi publik" class="hidden items-center gap-1 xl:flex">
            @foreach ($menuBeranda as $menu)
                <a
                    href="#{{ $menu['id'] }}"
                    data-nav-section="{{ $menu['id'] }}"
                    class="bc-nav-link rounded-xl px-3.5 py-2 text-xs font-semibold text-[#64746D] transition hover:bg-[#F5F8F6] hover:text-[#172B25]"
                >
                    {{ $menu['nama'] }}
                </a>
            @endforeach
        </nav>

        <!-- CTA AKSI DESKTOP -->
        <div class="hidden shrink-0 items-center gap-3 xl:flex">
            @guest
                <a
                    href="{{ route('login') }}"
                    class="rounded-xl border border-[#E3EBE6] bg-white px-4 py-2 text-xs font-bold text-[#103F36] transition hover:bg-[#F5F8F6] hover:border-slate-300"
                >
                    Masuk Portal
                </a>
                <a
                    href="{{ route('register') }}"
                    class="rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white shadow-md shadow-[#103F36]/20 transition hover:bg-[#116759]"
                >
                    Daftar Sekarang →
                </a>
            @else
                <a
                    href="{{ route($routeDashboard) }}"
                    class="rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white shadow-md shadow-[#103F36]/20 transition hover:bg-[#116759] flex items-center gap-2"
                >
                    <span>Buka Dashboard</span>
                    <span>→</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-xl border border-red-200 bg-red-50/70 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100"
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
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#E3EBE6] text-[#103F36] hover:bg-[#F5F8F6] xl:hidden"
        >
            <span id="bcMenuIcon" class="text-xl" aria-hidden="true">☰</span>
        </button>

    </div>

    <!-- MENU MOBILE DROPDOWN -->
    <nav
        id="bcMenuMobile"
        aria-label="Navigasi mobile"
        class="hidden border-t border-[#E3EBE6] bg-white px-6 pb-6 pt-3 xl:hidden shadow-xl"
    >
        <div class="mx-auto max-w-xl space-y-1">
            @foreach ($menuBeranda as $menu)
                <a
                    href="#{{ $menu['id'] }}"
                    data-nav-section="{{ $menu['id'] }}"
                    class="bc-nav-link block rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-[#F5F8F6]"
                >
                    {{ $menu['nama'] }}
                </a>
            @endforeach

            <div class="my-3 border-t border-[#E3EBE6]"></div>

            @guest
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <a
                        href="{{ route('login') }}"
                        class="rounded-xl border border-[#E3EBE6] py-2.5 text-center text-xs font-bold text-[#103F36] hover:bg-[#F5F8F6]"
                    >
                        Masuk
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-[#103F36] py-2.5 text-center text-xs font-bold text-white shadow-sm"
                    >
                        Daftar
                    </a>
                </div>
            @else
                <div class="space-y-2 pt-1">
                    <a
                        href="{{ route($routeDashboard) }}"
                        class="block rounded-xl bg-[#103F36] py-2.5 text-center text-xs font-bold text-white"
                    >
                        Buka Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 bg-red-50 py-2 text-center text-xs font-bold text-red-700"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </nav>
</header>

<main class="space-y-0">

    <!-- ==================================================
         1. HERO SECTION (HIGH IMPACT SAAS PRESENTATION)
    ================================================== -->
    <section id="beranda" class="bc-section relative overflow-hidden text-white py-20 lg:py-28"
             style="background: linear-gradient(135deg, #0A2620 0%, #103F36 60%, #135A4B 100%);">
        <!-- Glow pattern decor -->
        <div class="pointer-events-none absolute -left-20 -top-20 h-96 w-96 rounded-full bg-[#20A67A]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute right-0 top-1/4 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Left Content (7 Col) -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold text-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-[#20A67A] animate-pulse"></span>
                    <span>Sistem Pendukung Keputusan Seleksi Beasiswa</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.15] text-white">
                    Seleksi Lebih Terukur, <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-200 to-[#20A67A]">
                        Keputusan Lebih Adil & Transparan.
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Sistem evaluasi beasiswa berbasis integrasi metode <strong>Analytical Hierarchy Process (AHP)</strong> untuk pembobotan kriteria dan <strong>WASPAS</strong> untuk perangkingan multi-kriteria pendaftar secara obyektif.
                </p>

                <!-- Dual CTA Buttons -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#20A67A] px-6 py-3.5 text-xs sm:text-sm font-extrabold text-white shadow-xl shadow-[#20A67A]/30 hover:bg-[#1B8E68] transition-all hover:scale-[1.02]"
                    >
                        <span>Daftar Beasiswa Sekarang</span>
                        <span>→</span>
                    </a>

                    <a
                        href="#metode"
                        class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 px-6 py-3.5 text-xs sm:text-sm font-bold text-white backdrop-blur-md transition-all"
                    >
                        <span>Pelajari Algoritma SPK</span>
                        <span>↓</span>
                    </a>
                </div>

                <!-- Trust Micro-Badges -->
                <div class="pt-6 border-t border-white/15 grid grid-cols-3 gap-4 text-center lg:text-left">
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-white">5 Kriteria</p>
                        <p class="text-[11px] text-emerald-200/80">Evaluasi Komprehensif</p>
                    </div>
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-white">AHP + WASPAS</p>
                        <p class="text-[11px] text-emerald-200/80">Dua Metode Terintegrasi</p>
                    </div>
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-white">CR &lt; 0.10</p>
                        <p class="text-[11px] text-emerald-200/80">Konsistensi Teruji</p>
                    </div>
                </div>
            </div>

            <!-- Hero Right Visual Card (5 Col) -->
            <div class="lg:col-span-5">
                <div class="relative mx-auto max-w-md rounded-3xl border border-white/20 bg-white/10 backdrop-blur-xl p-6 sm:p-7 shadow-2xl text-white space-y-5">
                    <div class="flex items-center justify-between border-b border-white/15 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="flex h-3 w-3 rounded-full bg-emerald-400"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-200">
                                Simulasi Perangkingan SPK
                            </span>
                        </div>
                        <span class="text-[10px] font-mono bg-white/15 px-2 py-0.5 rounded-full">λ = 0.5</span>
                    </div>

                    <!-- Mini Ranking Podium Preview -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white/15 border border-white/25 p-3">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-400 text-slate-900 font-extrabold text-xs shadow-xs">1</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Pendaftar Prioritas 1</p>
                                    <p class="text-[10px] text-emerald-200">Keluarga Prasejahtera & Berprestasi</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-300 font-mono">0.8942</span>
                        </div>

                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white/10 border border-white/15 p-3">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-200 text-slate-800 font-extrabold text-xs">2</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Pendaftar Prioritas 2</p>
                                    <p class="text-[10px] text-emerald-200">Nilai Rapor 92.5 + SKTM</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-300 font-mono">0.8415</span>
                        </div>

                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white/10 border border-white/15 p-3">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-700 text-white font-extrabold text-xs">3</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Pendaftar Prioritas 3</p>
                                    <p class="text-[10px] text-emerald-200">Prestasi Non-Akademik Kota</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-emerald-300 font-mono">0.7890</span>
                        </div>
                    </div>

                    <div class="rounded-xl bg-black/20 p-3.5 text-[11px] text-emerald-100/80 leading-relaxed border border-white/10">
                        ⚡ Perhitungan otomatis memadukan nilai WSM (Weighted Sum) dan WPM (Weighted Product) secara presisi.
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ==================================================
         2. SECTION TENTANG SISTEM
    ================================================== -->
    <section id="tentang" class="bc-section py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                    Tentang Aplikasi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#103F36] tracking-tight">
                    Mengapa Beasiswa Cerdas Diciptakan?
                </h2>
                <p class="text-sm text-[#64746D] leading-relaxed">
                    Menjawab tantangan penyeleksian beasiswa manual yang rentan terhadap subyektivitas, inkonsistensi bobot, dan keterbatasan dalam memproses banyak pendaftar sekaligus.
                </p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-7">
                <!-- Feature 1 -->
                <div class="rounded-2xl border border-[#E3EBE6] bg-[#FAFCFB] p-7 space-y-4 hover:shadow-md transition">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-[#103F36] text-xl font-bold">
                        ⚖️
                    </div>
                    <h3 class="text-base font-bold text-[#103F36]">Pembobotan Teruji AHP</h3>
                    <p class="text-xs text-[#64746D] leading-relaxed">
                        Kriteria dinilai menggunakan matriks perbandingan berpasangan dengan verifikasi rasio konsistensi (CR &le; 0.10) agar pembobotan terbebas dari kontradiksi opini panitia.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="rounded-2xl border border-[#E3EBE6] bg-[#FAFCFB] p-7 space-y-4 hover:shadow-md transition">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-100 text-[#116759] text-xl font-bold">
                        ⚡
                    </div>
                    <h3 class="text-base font-bold text-[#103F36]">Akurasi Tinggi WASPAS</h3>
                    <p class="text-xs text-[#64746D] leading-relaxed">
                        Menggabungkan kelebihan model WSM (penjumlahan) dan WPM (perkalian) dengan koefisien lambda untuk menghasilkan nilai preferensi Qi yang stabil dan reliabel.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="rounded-2xl border border-[#E3EBE6] bg-[#FAFCFB] p-7 space-y-4 hover:shadow-md transition">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-900 text-xl font-bold">
                        📂
                    </div>
                    <h3 class="text-base font-bold text-[#103F36]">Pemberkasan Digital Aman</h3>
                    <p class="text-xs text-[#64746D] leading-relaxed">
                        Pendaftar mengunggah scan rapor, KK, dan bukti penghasilan langsung ke sistem. Admin memverifikasi dokumen secara transparan dengan audit trail status.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================================================
         3. SECTION KRITERIA PENILAIAN (C1 - C5)
    ================================================== -->
    <section id="kriteria" class="bc-section py-20 bg-[#F5F8F6]">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                    Kriteria Evaluasi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#103F36] tracking-tight">
                    5 Kriteria Penentu Kelayakan Beasiswa
                </h2>
                <p class="text-sm text-[#64746D] leading-relaxed">
                    Setiap kriteria memiliki sifat (Cost / Benefit) yang diperhitungkan secara matematis pada tahap normalisasi WASPAS.
                </p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($kriteria as $item)
                    <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 space-y-4 shadow-sm hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F5F8F6] text-xl">
                                {{ $item['ikon'] }}
                            </span>
                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-extrabold {{ $item['jenis'] === 'Cost' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $item['kode'] }} · {{ $item['badge'] }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-[#103F36]">
                                {{ $item['nama'] }}
                            </h3>
                            <p class="mt-2 text-xs text-[#64746D] leading-relaxed">
                                {{ $item['deskripsi'] }}
                            </p>
                        </div>
                    </div>
                @endforeach

                <!-- Card Info Ringkasan -->
                <div class="rounded-2xl border border-[#116759]/20 bg-gradient-to-br from-[#103F36] to-[#116759] p-6 text-white space-y-3 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300">Konsep Perhitungan</span>
                        <h3 class="text-base font-bold mt-1 text-white">Sifat Cost vs Benefit</h3>
                        <p class="mt-2 text-xs text-emerald-100/85 leading-relaxed">
                            <strong>Cost:</strong> Nilai semakin kecil semakin diprioritaskan (cth: penghasilan orang tua). <br>
                            <strong>Benefit:</strong> Nilai semakin besar semakin diprioritaskan (cth: rapor, prestasi, tanggungan).
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-200 hover:text-white transition">
                            <span>Mulai Seleksi Sekarang</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================================================
         4. SECTION METODE AHP & WASPAS
    ================================================== -->
    <section id="metode" class="bc-section py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6 space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                    Metodologi Ilmiah
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#103F36] tracking-tight">
                    Sinergi Metode AHP & WASPAS
                </h2>
                <p class="text-sm text-[#64746D] leading-relaxed">
                    Penggabungan dua metode terkemuka untuk menyelesaikan masalah Multi-Criteria Decision Making (MCDM) secara komprehensif.
                </p>
            </div>

            <!-- Two Method Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                <!-- AHP Box -->
                <div class="rounded-2xl border border-[#E3EBE6] bg-[#FAFCFB] p-8 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                            <span>Tahap 1</span>
                            <span>·</span>
                            <span>Pembobotan Kriteria</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#103F36]">Analytical Hierarchy Process (AHP)</h3>
                        <p class="text-xs text-[#64746D] leading-relaxed">
                            AHP dikembangkan oleh Thomas L. Saaty untuk memecahkan persoalan kompleks ke dalam hierarki kriteria dan alternatif. Pada sistem ini, AHP berperan menghasilkan vektor bobot prioritas kriteria (Wj) melalui perbandingan matriks berpasangan berukuran 5x5.
                        </p>
                        <div class="space-y-2 pt-2 text-xs">
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Matriks perbandingan skala 1 sampai 9
                            </div>
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Uji Nilai Eigen Maksimum (λmax) & Indeks Konsistensi (CI)
                            </div>
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Validasi Rasio Konsistensi (CR &lt; 0.10)
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-[#E3EBE6] pt-4 text-[11px] text-[#84968F]">
                        Output: Bobot Kriteria yang valid dan konsisten.
                    </div>
                </div>

                <!-- WASPAS Box -->
                <div class="rounded-2xl border border-[#E3EBE6] bg-[#FAFCFB] p-8 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold">
                            <span>Tahap 2</span>
                            <span>·</span>
                            <span>Perangkingan Alternatif</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#103F36]">WASPAS Method</h3>
                        <p class="text-xs text-[#64746D] leading-relaxed">
                            Weighted Aggregated Sum Product Assessment mengoptimalkan akurasi perangkingan dengan memadukan Weighted Sum Model (WSM) dan Weighted Product Model (WPM). Hasil kombinasi menghasilkan nilai preferensi Qi yang menentukan peringkat kelayakan penerima beasiswa.
                        </p>
                        <div class="space-y-2 pt-2 text-xs">
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Normalisasi matriks keputusan linier (Cost & Benefit)
                            </div>
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Perhitungan nilai WSM (penjumlahan terbobot)
                            </div>
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Perhitungan nilai WPM (perkalian terbobot berpangkat)
                            </div>
                            <div class="flex items-center gap-2 text-[#172B25] font-semibold">
                                <span class="text-emerald-700">✓</span> Agregasi skor akhir Qi dengan nilai lambda parameter
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-[#E3EBE6] pt-4 text-[11px] text-[#84968F]">
                        Output: Urutan peringkat alternatif calon penerima (Rank 1 .. n).
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================================================
         5. SECTION ALUR PENDAFTARAN (STEP-BY-STEP)
    ================================================== -->
    <section id="alur" class="bc-section py-20 bg-[#F5F8F6]">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                    Alur Seleksi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#103F36] tracking-tight">
                    4 Langkah Mudah Mendapatkan Beasiswa
                </h2>
                <p class="text-sm text-[#64746D] leading-relaxed">
                    Tahapan pendaftaran dari awal hingga keputusan penetapan beasiswa diumumkan.
                </p>
            </div>

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($alur as $item)
                    <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 space-y-4 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-extrabold text-[#20A67A] tabular-nums font-mono">
                                {{ $item['nomor'] }}
                            </span>
                            <span class="text-xl">{{ $item['ikon'] }}</span>
                        </div>
                        <h3 class="text-base font-bold text-[#103F36]">
                            {{ $item['judul'] }}
                        </h3>
                        <p class="text-xs text-[#64746D] leading-relaxed">
                            {{ $item['teks'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================================================
         6. SECTION PERSYARATAN DOKUMEN & CTA FINAL
    ================================================== -->
    <section id="dokumen" class="bc-section py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6">
            <div class="rounded-3xl border border-[#E3EBE6] bg-[#FAFCFB] p-8 sm:p-12 overflow-hidden relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

                    <div class="lg:col-span-7 space-y-5">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                            Persyaratan Berkas
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#103F36] tracking-tight leading-tight">
                            Persiapkan Dokumen Digital Anda Sebelum Mendaftar
                        </h2>
                        <p class="text-xs sm:text-sm text-[#64746D] leading-relaxed">
                            Seluruh dokumen diunggah dalam format digital (PDF, JPG, atau PNG) dengan ukuran maksimal 2 MB per berkas.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                            <div class="flex items-center gap-3 rounded-xl border border-[#E3EBE6] bg-white p-3.5 text-xs font-semibold text-[#172B25]">
                                <span class="text-emerald-700">✓</span> Scan Kartu Keluarga (KK) Asli
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-[#E3EBE6] bg-white p-3.5 text-xs font-semibold text-[#172B25]">
                                <span class="text-emerald-700">✓</span> Surat Ket. Penghasilan / SKTM
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-[#E3EBE6] bg-white p-3.5 text-xs font-semibold text-[#172B25]">
                                <span class="text-emerald-700">✓</span> Rapor Semester Terakhir
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-[#E3EBE6] bg-white p-3.5 text-xs font-semibold text-[#172B25]">
                                <span class="text-emerald-700">✓</span> Sertifikat Prestasi (Opsional)
                            </div>
                        </div>
                    </div>

                    <!-- CTA Box Right -->
                    <div class="lg:col-span-5 text-center lg:text-right space-y-4">
                        <div class="rounded-2xl bg-[#103F36] p-7 text-white space-y-4 shadow-xl">
                            <h3 class="text-lg font-bold text-white">Siap Mengajukan Beasiswa?</h3>
                            <p class="text-xs text-emerald-100/80 leading-relaxed">
                                Buat akun sekarang dan isi formulir pengajuan Anda dalam hitungan menit.
                            </p>
                            <div class="pt-2">
                                @guest
                                    <a
                                        href="{{ route('register') }}"
                                        class="block w-full rounded-xl bg-[#20A67A] py-3 text-xs font-extrabold text-white shadow-lg shadow-[#20A67A]/30 hover:bg-[#1B8E68] transition"
                                    >
                                        Daftar Akun Pendaftar →
                                    </a>
                                @else
                                    <a
                                        href="{{ route($routeDashboard) }}"
                                        class="block w-full rounded-xl bg-[#20A67A] py-3 text-xs font-extrabold text-white shadow-lg shadow-[#20A67A]/30 hover:bg-[#1B8E68] transition"
                                    >
                                        Buka Dashboard Pengajuan →
                                    </a>
                                @endguest
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</main>

<!-- JAVASCRIPT NAVIGASI INTERAKTIF & SCROLLSPY -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tombol = document.getElementById('bcMenuToggle');
    const menuMobile = document.getElementById('bcMenuMobile');
    const ikon = document.getElementById('bcMenuIcon');

    function aturMenu(terbuka) {
        if (!menuMobile || !tombol) return;
        menuMobile.classList.toggle('hidden', !terbuka);
        tombol.setAttribute('aria-expanded', String(terbuka));
        tombol.setAttribute('aria-label', terbuka ? 'Tutup menu' : 'Buka menu');
        if (ikon) ikon.textContent = terbuka ? '✕' : '☰';
    }

    if (tombol) {
        tombol.addEventListener('click', function () {
            const sedangTerbuka = !menuMobile.classList.contains('hidden');
            aturMenu(!sedangTerbuka);
        });
    }

    if (menuMobile) {
        menuMobile.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                aturMenu(false);
            });
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') aturMenu(false);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1280) aturMenu(false);
    });

    // SCROLLSPY ACTIVE MENU
    const tautan = document.querySelectorAll('[data-nav-section]');
    function aktifkanMenu(id) {
        tautan.forEach(function (link) {
            const aktif = link.dataset.navSection === id;
            link.dataset.active = aktif ? 'true' : 'false';
            if (aktif) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    }

    const awal = window.location.hash ? window.location.hash.substring(1) : 'beranda';
    aktifkanMenu(awal);

    window.addEventListener('hashchange', function () {
        const id = window.location.hash ? window.location.hash.substring(1) : 'beranda';
        aktifkanMenu(id);
    });

    if ('IntersectionObserver' in window) {
        const sections = document.querySelectorAll('main section[id]');
        const observer = new IntersectionObserver(function (entries) {
            const terlihat = entries.filter(e => e.isIntersecting).sort((a, b) => b.intersectionRatio - a.intersectionRatio);
            if (terlihat.length > 0) {
                aktifkanMenu(terlihat[0].target.id);
            }
        }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

        sections.forEach(s => observer.observe(s));
    }
});
</script>

@endsection
