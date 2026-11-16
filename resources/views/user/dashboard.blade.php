@extends('layouts.utama')

@section('title', 'Dashboard Mahasiswa - Beasiswa Cerdas')

@section('content')

@php
    $namaUser = $akun->nama_lengkap ?: $akun->username;
@endphp

<!-- DASHBOARD USER BEASISWA CERDAS (PREMIUM SAAS) -->
<main class="mx-auto max-w-6xl px-5 py-8 space-y-8">

    <!-- WELCOME HERO BANNER -->
    <section class="relative overflow-hidden rounded-3xl border border-white/10 p-7 sm:p-9 text-white shadow-xl shadow-emerald-950/10"
             style="background: linear-gradient(135deg, #0A2620 0%, #103F36 60%, #135A4B 100%);">
        <!-- Glow pattern decor -->
        <div class="pointer-events-none absolute -right-12 -top-12 h-64 w-64 rounded-full bg-[#20A67A]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute right-1/3 -bottom-12 h-48 w-48 rounded-full bg-emerald-400/10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#20A67A] animate-pulse"></span>
                    <span>Portal Pendaftar Beasiswa</span>
                    @if ($pengajuan && $pengajuan->nisn)
                        <span class="text-white/40">·</span>
                        <span class="font-mono">NISN: {{ $pengajuan->nisn }}</span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Halo, {{ $namaUser }}! 👋
                </h1>

                <p class="text-xs sm:text-sm text-emerald-100/85 leading-relaxed">
                    Selamat datang di sistem seleksi beasiswa. Pantau berkas pendaftaran, hasil verifikasi panitia, serta pengumuman resmi penerima beasiswa berbasis metode AHP–WASPAS di sini.
                </p>

                <!-- Fast CTA -->
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="{{ route('user.pengajuan') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#20A67A] px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-[#20A67A]/30 hover:bg-[#1B8E68] transition-all hover:scale-[1.02]">
                        <span>▤</span>
                        <span>{{ $pengajuan ? 'Lihat / Edit Pengajuan' : 'Isi Formulir Beasiswa' }}</span>
                    </a>
                    <a href="{{ route('user.status') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-md transition-all">
                        <span>◷</span>
                        <span>Status Lengkap</span>
                    </a>
                </div>
            </div>

            <!-- STATUS ADMINISTRASI CARD (HERO EMBEDDED) -->
            <div class="w-full lg:w-80 shrink-0 rounded-2xl border border-white/20 bg-white/10 backdrop-blur-md p-5 text-white shadow-lg">
                <div class="flex items-center justify-between gap-2 border-b border-white/15 pb-3">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-200">
                        Status Administrasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Live
                    </span>
                </div>

                <div class="mt-3">
                    <h2 class="text-lg font-extrabold text-white">
                        {{ $infoStatus['judul'] }}
                    </h2>
                    <p class="mt-1 text-xs text-emerald-100/80 leading-relaxed">
                        {{ $infoStatus['keterangan'] }}
                    </p>
                </div>

                @if ($sudahDiumumkan)
                    <div class="mt-4 rounded-xl bg-white/15 border border-white/20 p-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-200">
                            Hasil Akhir Resmi
                        </p>
                        <p class="mt-1 text-base font-extrabold text-white flex items-center gap-2">
                            <span>★</span>
                            <span>{{ $infoKeputusan['judul'] }}</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- RINGKASAN PENGAJUAN (3 CARDS) -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5" aria-label="Ringkasan pengajuan">
        <!-- Card 1: Status Administrasi -->
        <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#64746D]">
                    Status Administrasi
                </span>
                <span class="text-base">☑</span>
            </div>
            <div class="mt-4">
                <span class="bc-badge bc-badge--{{ $status }} text-xs font-bold px-3 py-1.5 rounded-full inline-block">
                    {{ $infoStatus['judul'] }}
                </span>
            </div>
            <p class="mt-4 text-xs text-[#64746D] leading-relaxed border-t border-[#EEF3F0] pt-3">
                Sesuai pemeriksaan data dan dokumen yang tersimpan pada sistem.
            </p>
        </div>

        <!-- Card 2: Dokumen Terunggah -->
        <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#64746D]">
                    Dokumen Wajib Tersimpan
                </span>
                <span class="text-base">📁</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#103F36] tabular-nums">
                    {{ $jumlahTerunggah }} / {{ $jumlahWajib }}
                </span>
                <span class="text-xs font-semibold text-[#84968F]">berkas</span>
            </div>
            <p class="mt-4 text-xs text-[#64746D] leading-relaxed border-t border-[#EEF3F0] pt-3">
                @if ($jumlahTerunggah >= $jumlahWajib)
                    <span class="text-emerald-700 font-bold">✓ Lengkap:</span> Seluruh berkas wajib telah berhasil diunggah.
                @else
                    <span class="text-amber-700 font-bold">! Perhatian:</span> Mohon lengkapi seluruh dokumen wajib yang diminta.
                @endif
            </p>
        </div>

        <!-- Card 3: Tanggal Pengiriman -->
        <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#64746D]">
                    Waktu Pengiriman
                </span>
                <span class="text-base">⏱</span>
            </div>
            <div class="mt-4">
                <p class="text-lg font-bold text-[#103F36]">
                    @if ($pengajuan && $pengajuan->diajukan_pada)
                        {{ date('d M Y, H:i', strtotime($pengajuan->diajukan_pada)) }} WIB
                    @else
                        <span class="text-slate-400">Belum Dikirim (Draf)</span>
                    @endif
                </p>
            </div>
            <p class="mt-4 text-xs text-[#64746D] leading-relaxed border-t border-[#EEF3F0] pt-3">
                Waktu resmi pencatatan pengajuan pada peladen sistem.
            </p>
        </div>
    </section>

    <!-- GRID DUA KOLOM: TAHAPAN SELEKSI & PENGAJUAN SAYA -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">

        <!-- KOLOM KIRI: TAHAPAN SELEKSI (STEPPER) (7 COL) -->
        <section class="lg:col-span-7 rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>🪜</span>
                    <span>Tahapan Seleksi Beasiswa</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Proses seleksi berjalan transparan dari pengisian data hingga penetapan penerima.
                </p>
            </div>

            <div class="p-6">
                <div class="relative space-y-6 before:absolute before:left-5 before:top-3 before:bottom-3 before:w-0.5 before:bg-[#E3EBE6]">
                    @foreach ($tahapan as $nomor => $tahap)
                        @php
                            $selesaiTahap = (bool) ($tahap['selesai'] ?? false);
                            $tahapHasil = (bool) ($tahap['hasil'] ?? false);

                            $warnaLingkaran = $selesaiTahap
                                ? 'bg-[#103F36] text-white ring-4 ring-emerald-100'
                                : 'bg-slate-100 text-slate-500 border border-slate-200';

                            $warnaStatus = $selesaiTahap
                                ? 'text-emerald-700 bg-emerald-50 border-emerald-200'
                                : 'text-slate-500 bg-slate-50 border-slate-200';

                            if ($tahapHasil) {
                                if ($keputusanFinal === 'diterima') {
                                    $warnaLingkaran = 'bg-emerald-600 text-white ring-4 ring-emerald-100';
                                    $warnaStatus = 'text-emerald-800 bg-emerald-50 border-emerald-200';
                                } elseif ($keputusanFinal === 'cadangan') {
                                    $warnaLingkaran = 'bg-amber-500 text-white ring-4 ring-amber-100';
                                    $warnaStatus = 'text-amber-800 bg-amber-50 border-amber-200';
                                } elseif ($keputusanFinal === 'tidak_diterima') {
                                    $warnaLingkaran = 'bg-rose-600 text-white ring-4 ring-rose-100';
                                    $warnaStatus = 'text-rose-800 bg-rose-50 border-rose-200';
                                }
                            }
                        @endphp

                        <div class="relative flex items-start gap-4 z-10">
                            <!-- Indicator circle -->
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-all {{ $warnaLingkaran }}">
                                @if ($selesaiTahap)
                                    ✓
                                @else
                                    {{ $nomor + 1 }}
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="min-w-0 flex-1 pt-1.5">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="text-sm font-bold text-[#172B25]">
                                        {{ $tahap['nama'] }}
                                    </h3>
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $warnaStatus }}">
                                        {{ $tahap['status'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (!$sudahDiumumkan && $status === 'terverifikasi')
                    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50/70 p-4">
                        <div class="flex items-start gap-3">
                            <span class="text-base text-emerald-800 mt-0.5">ℹ</span>
                            <div>
                                <p class="text-xs font-bold text-emerald-900">
                                    Administrasi Lolos Verifikasi
                                </p>
                                <p class="mt-1 text-xs text-emerald-800/90 leading-relaxed">
                                    Data dan dokumen kamu telah memenuhi syarat administrasi dan telah siap untuk diikutsertakan dalam perankingan metode WASPAS. Silakan pantau pengumuman resmi dari panitia.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- KOLOM KANAN: DETAIL PENGAJUAN & CATATAN ADMIN (5 COL) -->
        <section class="lg:col-span-5 rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden flex flex-col">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>👤</span>
                    <span>Informasi Akun Pendaftar</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Data identitas yang terdaftar pada sistem.
                </p>
            </div>

            <div class="p-6 space-y-5">
                <div class="divide-y divide-[#EEF3F0] rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] overflow-hidden text-xs">
                    <div class="p-3.5 flex justify-between gap-3">
                        <span class="text-[#64746D] font-medium">Username:</span>
                        <span class="font-bold text-[#172B25]">{{ $akun->username }}</span>
                    </div>
                    <div class="p-3.5 flex justify-between gap-3">
                        <span class="text-[#64746D] font-medium">Email:</span>
                        <span class="font-bold text-[#172B25] truncate">{{ $akun->email ?: 'Belum diisi' }}</span>
                    </div>
                    @if ($pengajuan)
                        <div class="p-3.5 flex justify-between gap-3">
                            <span class="text-[#64746D] font-medium">Asal Sekolah:</span>
                            <span class="font-bold text-[#172B25] text-right">{{ $pengajuan->asal_sekolah ?: '-' }}</span>
                        </div>
                    @endif
                </div>

                <!-- CATATAN DARI ADMIN -->
                @if ($pengajuan && $pengajuan->catatan_admin)
                    <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4">
                        <p class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                            <span>💬</span>
                            <span>Catatan Pemeriksaan Admin:</span>
                        </p>
                        <p class="mt-2 text-xs text-amber-900/90 leading-relaxed whitespace-pre-wrap">
                            {{ $pengajuan->catatan_admin }}
                        </p>
                    </div>
                @endif

                <!-- KEPUTUSAN AKHIR CARD JIKA SUDAH DIUMUMKAN -->
                @if ($sudahDiumumkan)
                    <div class="rounded-xl border p-4.5 {{ $infoKeputusan['warnaPanel'] }}">
                        <p class="text-[10px] font-bold uppercase tracking-wider">
                            Keputusan Akhir Resmi
                        </p>
                        <h3 class="mt-1.5 text-lg font-extrabold flex items-center gap-2">
                            <span>★</span>
                            <span>{{ $infoKeputusan['judul'] }}</span>
                        </h3>
                        <p class="mt-2 text-xs leading-relaxed opacity-90">
                            {{ $infoKeputusan['keterangan'] }}
                        </p>
                        <div class="mt-4 border-t border-current/20 pt-3">
                            <a href="{{ route('user.status') }}"
                               class="inline-flex items-center justify-center rounded-xl bg-white/80 hover:bg-white text-xs font-bold px-3.5 py-2 transition shadow-xs">
                                <span>Buka Surat Keputusan</span>
                                <span class="ml-1">→</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </div>

    <!-- BERKAS PENGAJUAN LIST -->
    <section id="berkas" class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>📑</span>
                    <span>Kelengkapan Berkas Persyaratan</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Daftar dokumen digital yang telah Anda unggah ke peladen beasiswa.
                </p>
            </div>
            <a href="{{ route('user.pengajuan') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#116759] hover:text-[#103F36] transition">
                <span>Perbarui Berkas</span>
                <span>→</span>
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($dokumen as $jenis => $item)
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-4 hover:border-[#CFDED8] transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-base font-bold border border-[#E3EBE6] shadow-xs">
                                @if ($item['ada'])
                                    📄
                                @else
                                    ✕
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#172B25] truncate">
                                    {{ $item['nama'] }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-semibold {{ $item['ada'] ? 'text-emerald-700' : 'text-slate-500' }}">
                                        {{ $item['ada'] ? '✓ Tersimpan di peladen' : 'Belum diunggah' }}
                                    </span>
                                    <span class="text-slate-300">·</span>
                                    <span class="text-[10px] font-medium text-[#64746D]">
                                        {{ $item['wajib'] ? 'Wajib' : 'Opsional' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($item['ada'])
                            <a href="{{ route('user.berkas', ['jenis' => $jenis]) }}"
                               class="shrink-0 inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-50 transition shadow-xs">
                                <span>Unduh</span>
                                <span>↓</span>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <p class="mt-4 text-[11px] text-[#64746D] leading-relaxed">
                * Dokumen yang diunggah hanya dapat diakses melalui akun Anda yang terautentikasi dan aman dari akses publik.
            </p>
        </div>
    </section>

</main>

@endsection
