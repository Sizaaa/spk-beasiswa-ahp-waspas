@extends('layouts.admin')

@section('title', 'Hasil Seleksi - Beasiswa Cerdas')
@section('judul-admin', 'Hasil & Penetapan Seleksi')
@section('deskripsi-admin', 'Pemeringkatan alternatif penerima beasiswa berbasis metode AHP & WASPAS serta penetapan kelulusan.')

@section('admin-content')

@php
    $modeAktif = $informasi['mode'] ?? 'artikel';
    $daftarKriteria = array_values(\App\Services\SpkCalculator::KRITERIA);
    $bobotArtikel = \App\Services\SpkCalculator::BOBOT_REFERENSI;
    $ahpSesi = session('spk_ahp');

    $bobotSimulasi = null;
    $crSimulasi = null;

    if (
        is_array($ahpSesi) &&
        isset($ahpSesi['bobot'], $ahpSesi['cr']) &&
        is_array($ahpSesi['bobot']) &&
        count($ahpSesi['bobot']) === 5 &&
        count(array_filter($ahpSesi['bobot'], 'is_numeric')) === 5 &&
        is_numeric($ahpSesi['cr'])
    ) {
        $bobotSimulasi = array_values($ahpSesi['bobot']);
        $crSimulasi = (float) $ahpSesi['cr'];
    }

    $top3 = collect($hasil['peringkat'] ?? [])->take(3);
@endphp

<main class="bc-admin-main space-y-7">

    <!-- LINGKUP SELEKSI AKTIF -->
    @include('admin.partials.lingkup-aktif')

    <!-- ALERT PESAN -->
    @if (session('sukses'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4.5 text-xs font-semibold text-emerald-900 shadow-xs" role="status">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-200 text-emerald-900 font-bold">✓</span>
            <div class="flex-1">{{ session('sukses') }}</div>
        </div>
    @endif

    <!-- ==================================================
         TOP 3 PODIUM HIGHLIGHT (JIKA DATA TERSEDIA)
    ================================================== -->
    @if ($top3->count() >= 3)
        <section class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#116759]">
                    Kandidat Teratas
                </span>
                <span class="text-xs font-semibold text-[#64746D]">
                    Prioritas Kelayakan Nilai Qi
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Juara 2 (Perak) -->
                @php $p2 = $top3->get(1); @endphp
                <div class="order-2 md:order-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-700 font-extrabold text-sm border border-slate-200">
                            2
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
                            Peringkat 2
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 font-mono">{{ $p2['kode'] }}</p>
                        <h3 class="text-base font-extrabold text-[#172B25] truncate mt-0.5">{{ $p2['nama'] }}</h3>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between text-xs">
                        <span class="text-[#64746D]">Nilai Akhir Qi</span>
                        <span class="font-extrabold font-mono text-[#103F36] text-base">{{ number_format($p2['q'], 6, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Juara 1 (Emas - Dominan) -->
                @php $p1 = $top3->get(0); @endphp
                <div class="order-1 md:order-2 rounded-2xl border-2 border-amber-300 bg-gradient-to-b from-amber-50/50 to-white p-6 shadow-md space-y-3.5 relative overflow-hidden flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base shadow-sm">
                            🥇
                        </span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-900 bg-amber-200/80 px-3 py-1 rounded-full shadow-xs">
                            Prioritas Utama #1
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-800 font-mono">{{ $p1['kode'] }}</p>
                        <h3 class="text-lg font-extrabold text-[#103F36] truncate mt-0.5">{{ $p1['nama'] }}</h3>
                    </div>
                    <div class="border-t border-amber-200/60 pt-3 flex items-center justify-between text-xs">
                        <span class="text-amber-950 font-bold">Skor Tertinggi Qi</span>
                        <span class="font-extrabold font-mono text-emerald-900 text-lg">{{ number_format($p1['q'], 6, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Juara 3 (Perunggu) -->
                @php $p3 = $top3->get(2); @endphp
                <div class="order-3 md:order-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100 text-amber-800 font-extrabold text-sm border border-amber-200">
                            3
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-full">
                            Peringkat 3
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 font-mono">{{ $p3['kode'] }}</p>
                        <h3 class="text-base font-extrabold text-[#172B25] truncate mt-0.5">{{ $p3['nama'] }}</h3>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between text-xs">
                        <span class="text-[#64746D]">Nilai Akhir Qi</span>
                        <span class="font-extrabold font-mono text-[#103F36] text-base">{{ number_format($p3['q'], 6, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- ==================================================
         PERBANDINGAN SUMBER BOBOT (ARTIKEL VS SIMULASI)
    ================================================== -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6" aria-label="Perbandingan sumber bobot">
        <!-- Bobot Artikel -->
        <article class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#103F36]">Bobot Artikel Penelitian</h3>
                    <p class="text-[11px] text-[#64746D]">Acuan jurnal baku (CR = 0,045)</p>
                </div>
                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-extrabold {{ $modeAktif === 'artikel' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $modeAktif === 'artikel' ? '✓ Aktif Digunakan' : 'Non-Aktif' }}
                </span>
            </div>

            <div class="divide-y divide-[#EEF3F0] text-xs">
                @foreach ($daftarKriteria as $i => $item)
                    <div class="flex items-center justify-between py-2">
                        <span class="text-[#64746D]"><strong class="text-[#103F36] mr-1">C{{ $i + 1 }}</strong> {{ $item['nama'] }}</span>
                        <span class="font-mono font-bold text-[#172B25]">{{ number_format($bobotArtikel[$i], 3, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <!-- Bobot Simulasi AHP -->
        <article class="rounded-2xl border border-[#E3EBE6] bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#103F36]">Bobot Simulasi Sesi Admin</h3>
                    <p class="text-[11px] text-[#64746D]">Hasil perbandingan pairwise dinamis</p>
                </div>
                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-extrabold {{ $modeAktif === 'simulasi' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $modeAktif === 'simulasi' ? '✓ Aktif Digunakan' : 'Non-Aktif' }}
                </span>
            </div>

            @if ($bobotSimulasi !== null)
                <div class="divide-y divide-[#EEF3F0] text-xs">
                    @foreach ($daftarKriteria as $i => $item)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-[#64746D]"><strong class="text-[#103F36] mr-1">C{{ $i + 1 }}</strong> {{ $item['nama'] }}</span>
                            <span class="font-mono font-bold text-[#172B25]">{{ number_format((float) $bobotSimulasi[$i], 3, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-[11px] text-emerald-700 font-semibold pt-1">
                    CR Simulasi = {{ number_format($crSimulasi, 4, ',', '.') }} ({{ $crSimulasi <= 0.10 ? 'Konsisten' : 'Inkonsisten' }})
                </p>
            @else
                <div class="py-8 text-center text-xs text-slate-400">
                    Belum ada komputasi simulasi matriks AHP pada sesi login ini.
                </div>
            @endif
        </article>
    </section>

    <!-- ==================================================
         TABEL UTAMA HASIL PERANGKINGAN WASPAS
    ================================================== -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>★</span>
                    <span>Daftar Pemeringkatan Skor WASPAS (Qi)</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    {{ $hasil['jumlah'] }} alternatif terhitung · Lingkup: <strong>{{ $lingkupInfo['judul'] }}</strong>
                </p>
            </div>

            <!-- Shortcut Navigation Pills -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <a href="{{ route('admin.seleksi-pengajuan.index') }}" class="rounded-lg border border-[#E3EBE6] bg-white px-3 py-1.5 font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                    Pengajuan Siap Seleksi
                </a>
                <a href="{{ route('admin.waspas.index') }}" class="rounded-lg border border-[#E3EBE6] bg-white px-3 py-1.5 font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                    Detail WASPAS
                </a>
                <a href="{{ route('admin.sensitivitas.index') }}" class="rounded-lg border border-[#E3EBE6] bg-white px-3 py-1.5 font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                    Sensitivitas
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="rounded-lg bg-[#103F36] px-3.5 py-1.5 font-bold text-white hover:bg-[#116759] transition">
                    Laporan Cetak →
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                        <th class="px-6 py-3.5 text-center">Peringkat</th>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Nama Calon Penerima</th>
                        <th class="px-6 py-3.5 text-right">Nilai Akhir (Qi)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF3F0]">
                    @forelse ($hasil['peringkat'] as $baris)
                        @php $isFirst = $baris['peringkat'] === 1; @endphp
                        <tr class="hover:bg-[#F9FCFA] {{ $isFirst ? 'bg-emerald-50/25' : '' }}">
                            <td class="px-6 py-3.5 text-center">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full font-extrabold text-xs {{ $isFirst ? 'bg-amber-400 text-slate-900 shadow-xs' : 'text-slate-600 bg-slate-100' }}">
                                    {{ $baris['peringkat'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold font-mono text-[#103F36]">
                                {{ $baris['kode'] }}
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-[#172B25]">
                                {{ $baris['nama'] }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono font-extrabold text-sm text-[#103F36] tabular-nums">
                                {{ number_format($baris['q'], 6, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data alternatif untuk lingkup ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- ==================================================
         PENETAPAN PENERIMA BEASISWA
    ================================================== -->
    @include('admin.partials.penetapan-hasil')

</main>

@endsection
