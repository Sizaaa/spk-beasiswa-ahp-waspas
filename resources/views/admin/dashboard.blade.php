@extends('layouts.admin')

@section('title', 'Dashboard Admin - Beasiswa Cerdas')
@section('judul-admin', 'Dashboard Seleksi')
@section('deskripsi-admin', 'Ringkasan pengajuan dan data alternatif beasiswa.')

@section('admin-content')

@php
    $namaAdmin = $akun->nama_lengkap ?: $akun->username;

    $kartu = [
        [
            'judul' => 'Total Pengajuan',
            'nilai' => $totalPengajuan,
            'catatan' => 'Total pengajuan masuk dari User',
            'jenis' => 'teal',
            'ikon' => '▤',
            'link' => route('admin.verifikasi.index'),
            'badge' => 'Semua',
        ],
        [
            'judul' => 'Menunggu Verifikasi',
            'nilai' => $menunggu,
            'catatan' => 'Perlu ditinjau segera oleh Admin',
            'jenis' => 'blue',
            'ikon' => '◷',
            'link' => route('admin.verifikasi.index'),
            'badge' => 'Pending',
        ],
        [
            'judul' => 'Lolos Terverifikasi',
            'nilai' => $terverifikasi,
            'catatan' => 'Berkas lengkap & memenuhi syarat',
            'jenis' => 'green',
            'ikon' => '✓',
            'link' => route('admin.seleksi-pengajuan.index'),
            'badge' => 'Siap Seleksi',
        ],
        [
            'judul' => 'Perlu Perbaikan',
            'nilai' => $perluPerbaikan,
            'catatan' => 'Menunggu revisi berkas oleh User',
            'jenis' => 'amber',
            'ikon' => '!',
            'link' => route('admin.verifikasi.index'),
            'badge' => 'Revisi',
        ],
    ];
@endphp

<main class="bc-admin-main space-y-7">

    <!-- HERO SECTION WELCOME BANNER -->
    <section class="relative overflow-hidden rounded-2xl border border-white/10 p-7 sm:p-9 text-white shadow-xl shadow-emerald-950/10"
             style="background: linear-gradient(135deg, #0A2620 0%, #103F36 60%, #135A4B 100%);">
        <!-- Glow pattern decor -->
        <div class="pointer-events-none absolute -right-12 -top-12 h-64 w-64 rounded-full bg-[#20A67A]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute right-1/4 -bottom-12 h-48 w-48 rounded-full bg-emerald-400/10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#20A67A] animate-pulse"></span>
                    <span>Tahun Akademik Aktif · SPK Terintegrasi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Selamat Datang, {{ $namaAdmin }}! 👋
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/85 leading-relaxed">
                    Sistem Pendukung Keputusan Seleksi Beasiswa siap digunakan. Pantau verifikasi administrasi berkas pendaftar dan jalankan simulasi perangkingan dengan kombinasi metode <strong>AHP</strong> dan <strong>WASPAS</strong>.
                </p>
            </div>

            <!-- Fast Action CTA Buttons -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0">
                <a href="{{ route('admin.verifikasi.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#20A67A] px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-[#20A67A]/30 hover:bg-[#1B8E68] transition-all hover:scale-[1.02]">
                    <span>☑</span>
                    <span>Verifikasi Pengajuan</span>
                </a>
                <a href="{{ route('admin.waspas.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-md transition-all">
                    <span>∑</span>
                    <span>Buka WASPAS</span>
                </a>
            </div>
        </div>
    </section>

    <!-- STATISTIK KARTU UTAMA -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" aria-label="Statistik pengajuan">
        @foreach ($kartu as $item)
            <div class="group relative overflow-hidden rounded-2xl border border-[#E3EBE6] bg-white p-5 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-start justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#64746D]">
                        {{ $item['judul'] }}
                    </span>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-bold text-sm
                        {{ $item['jenis'] === 'teal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                        {{ $item['jenis'] === 'blue' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                        {{ $item['jenis'] === 'green' ? 'bg-green-50 text-green-700 border border-green-100' : '' }}
                        {{ $item['jenis'] === 'amber' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                    ">
                        {{ $item['ikon'] }}
                    </span>
                </div>

                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-[#103F36] tabular-nums tracking-tight">
                        {{ number_format((int) $item['nilai'], 0, ',', '.') }}
                    </span>
                    <span class="text-[11px] font-semibold text-[#84968F]">pendaftar</span>
                </div>

                <div class="mt-3 flex items-center justify-between border-t border-[#EEF3F0] pt-3 text-[11px]">
                    <span class="text-[#64746D] truncate max-w-[170px]">{{ $item['catatan'] }}</span>
                    <span class="rounded-full px-2 py-0.5 font-bold text-[10px]
                        {{ $item['jenis'] === 'teal' ? 'bg-emerald-100/70 text-emerald-800' : '' }}
                        {{ $item['jenis'] === 'blue' ? 'bg-blue-100/70 text-blue-800' : '' }}
                        {{ $item['jenis'] === 'green' ? 'bg-green-100/70 text-green-800' : '' }}
                        {{ $item['jenis'] === 'amber' ? 'bg-amber-100/70 text-amber-800' : '' }}
                    ">
                        {{ $item['badge'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </section>

    <!-- GRID DUA KOLOM: PENGAJUAN TERBARU & TARGET ALTERNATIF -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

        <!-- KOLOM KIRI: PENGAJUAN TERBARU (8 COL) -->
        <section class="lg:col-span-8 rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <div>
                    <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                        <span>▤</span>
                        <span>Pengajuan Terbaru</span>
                    </h2>
                    <p class="text-xs text-[#64746D] mt-0.5">
                        Daftar berkas pendaftaran yang baru dikirimkan oleh siswa/mahasiswa.
                    </p>
                </div>

                <a href="{{ route('admin.verifikasi.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#116759] hover:text-[#103F36] transition">
                    <span>Lihat Semua</span>
                    <span>→</span>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#EEF3F0] bg-[#F7FAF8] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                            <th class="px-6 py-3.5">Pendaftar</th>
                            <th class="px-4 py-3.5">Asal Sekolah</th>
                            <th class="px-4 py-3.5">Waktu Kirim</th>
                            <th class="px-6 py-3.5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF3F0]">
                        @forelse ($pengajuanTerbaru as $item)
                            @php
                                $namaTampil = $item->nama_lengkap ?: $item->username;
                                $initial = strtoupper(substr($namaTampil, 0, 2));
                            @endphp
                            <tr class="hover:bg-[#F9FCFA] transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-extrabold text-[#103F36] border border-emerald-200">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#172B25] leading-tight">
                                                {{ $namaTampil }}
                                            </p>
                                            <p class="text-[11px] text-[#84968F] font-mono mt-0.5">
                                                ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-[#64746D]">
                                    {{ $item->asal_sekolah ?: '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-[#64746D] tabular-nums whitespace-nowrap">
                                    {{ $item->diajukan_pada ? date('d M Y, H:i', strtotime($item->diajukan_pada)) : '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <span class="bc-badge bc-badge--{{ $item->status }}">
                                        {{ $labelStatus[$item->status] ?? $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-3xl mb-2">📭</span>
                                        <p class="font-bold text-slate-700">Belum ada pengajuan masuk</p>
                                        <p class="text-xs text-slate-500 mt-1">Pengajuan yang dikirim pendaftar akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- KOLOM KANAN: STATUS DATA ALTERNATIF WASPAS (4 COL) -->
        <section class="lg:col-span-4 rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>♙</span>
                    <span>Kesiapan Data Alternatif</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Data calon penerima untuk komputasi WASPAS.
                </p>
            </div>

            <div class="p-6 space-y-6 flex-1">
                <!-- Mini Stats 3 Box -->
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-3">
                        <p class="text-[10px] font-bold text-[#64746D] uppercase">Total</p>
                        <p class="text-xl font-extrabold text-[#103F36] mt-1 tabular-nums">{{ $totalAlternatif }}</p>
                        <p class="text-[9px] text-emerald-700 font-semibold mt-0.5">Calon</p>
                    </div>
                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-3">
                        <p class="text-[10px] font-bold text-[#64746D] uppercase">A1–A5</p>
                        <p class="text-xl font-extrabold text-blue-700 mt-1 tabular-nums">{{ $dataJurnal }}</p>
                        <p class="text-[9px] text-blue-600 font-semibold mt-0.5">Penelitian</p>
                    </div>
                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-3">
                        <p class="text-[10px] font-bold text-[#64746D] uppercase">Lainnya</p>
                        <p class="text-xl font-extrabold text-[#116759] mt-1 tabular-nums">{{ $dataTambahan }}</p>
                        <p class="text-[9px] text-[#116759] font-semibold mt-0.5">Pendaftar</p>
                    </div>
                </div>

                <!-- Progress Target Bar -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#172B25]">Target Minimal Alternatif</span>
                        <span class="font-extrabold text-[#103F36] tabular-nums">
                            {{ $totalAlternatif }} / {{ $targetData }} ({{ round($persentase) }}%)
                        </span>
                    </div>

                    <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#116759] to-[#20A67A] transition-all duration-500"
                             style="width: {{ min(100, $persentase) }}%"></div>
                    </div>

                    <p class="text-[11px] text-[#64746D] leading-relaxed pt-1">
                        @if ($sisaData > 0)
                            <span class="text-amber-700 font-semibold">Perhatian:</span> Masih diperlukan {{ $sisaData }} calon penerima lagi untuk mencapai target {{ $targetData }} data.
                        @else
                            <span class="text-emerald-700 font-semibold">✓ Target Terpenuhi:</span> Seluruh kuota alternatif siap dihitung dengan metode WASPAS.
                        @endif
                    </p>
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <a href="{{ route('admin.calon-penerima.index') }}"
                       class="flex items-center justify-center gap-2 w-full rounded-xl border border-[#116759] text-[#116759] hover:bg-[#116759] hover:text-white px-4 py-2.5 text-xs font-bold transition-all">
                        <span>Kelola Alternatif WASPAS</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </section>

    </div>

    <!-- PANDUAN DAN AKSES CEPAT MODUL SPK -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- CARD 1: VERIFIKASI -->
        <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800 text-lg font-bold border border-emerald-100">
                    ☑
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#103F36]">Verifikasi Dokumen Pendaftaran</h3>
                    <p class="text-xs text-[#64746D] leading-relaxed">
                        Tinjau berkas scan nilai rapor, surat keterangan penghasilan orang tua, sertifikat prestasi, dan tanggungan keluarga untuk memutuskan status verifikasi.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('admin.verifikasi.index') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-[#103F36] hover:text-[#20A67A] transition">
                            <span>Buka Antrean Verifikasi</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: PERHITUNGAN SPK -->
        <div class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-800 text-lg font-bold border border-blue-100">
                    ◎
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#103F36]">Metode AHP & WASPAS</h3>
                    <p class="text-xs text-[#64746D] leading-relaxed">
                        Metode AHP digunakan untuk mencari bobot prioritas kriteria dengan uji konsistensi (CR < 0.1), lalu metode WASPAS mengkombinasikan model WSM dan WPM untuk peringkat optimal.
                    </p>
                    <div class="pt-2 flex items-center gap-4 text-xs font-bold">
                        <a href="{{ route('admin.ahp.index') }}" class="text-[#103F36] hover:text-[#20A67A] transition">
                            Perhitungan AHP →
                        </a>
                        <a href="{{ route('admin.waspas.index') }}" class="text-[#103F36] hover:text-[#20A67A] transition">
                            Perhitungan WASPAS →
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </section>

</main>

@endsection
