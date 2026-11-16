@extends('layouts.admin')

@section('title', 'Perhitungan WASPAS - Beasiswa Cerdas')
@section('judul-admin', 'Perhitungan WASPAS')
@section('deskripsi-admin', 'Normalisasi matriks, komputasi model WSM & WPM, dan perangkingan nilai preferensi Qi.')

@section('admin-content')

@php
    $daftarKriteria = array_values($kriteria);
    $dataAsli = collect($alternatif)->keyBy('kode');
@endphp

<main class="bc-admin-main space-y-7">

    <!-- LINGKUP AKTIF -->
    @include('admin.partials.lingkup-aktif')

    <!-- ==================================================
         1. PARAMETER KOMPUTASI WASPAS
    ================================================== -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-extrabold text-[#103F36]">1</span>
                    <span>Parameter Konfigurasi Model WASPAS</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    {{ count($alternatif) }} alternatif dievaluasi · Lingkup: <strong>{{ $lingkupInfo['judul'] }}</strong> · Parameter Kombinasi λ = {{ number_format($lambda, 1, ',', '.') }}
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-800">
                    Bobot: {{ $informasi['mode'] === 'artikel' ? 'Rujukan Artikel' : 'Hasil Simulasi' }}
                </span>
                <a href="{{ route('admin.ahp.index') }}"
                   class="rounded-xl border border-[#CFDED8] bg-white px-3.5 py-1.5 text-xs font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                    Ubah Bobot AHP →
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- 5 Bobot Kriteria Grid -->
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64746D] block mb-3">
                    Bobot Normalisasi Vektor Kriteria (Wj):
                </span>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
                    @foreach ($kriteria as $kode => $k)
                        <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-3.5 text-center">
                            <span class="text-[10px] font-extrabold text-[#116759] uppercase block">
                                {{ $kode }} ({{ ucfirst($k['jenis']) }})
                            </span>
                            <span class="text-lg font-extrabold text-[#103F36] mt-0.5 block tabular-nums">
                                {{ number_format($informasi['bobot'][$loop->index], 4, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-[#64746D] truncate block mt-0.5" title="{{ $k['nama'] }}">
                                {{ $k['nama'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rumus Singkat Formula Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs bg-[#F7FAF8] p-4 rounded-xl border border-[#E3EBE6]">
                <div class="space-y-0.5">
                    <span class="font-bold text-[#103F36] block">1. Normalisasi Matriks</span>
                    <span class="text-[11px] text-[#64746D]">Cost: min / x &bull; Benefit: x / max</span>
                </div>
                <div class="space-y-0.5">
                    <span class="font-bold text-[#103F36] block">2. Model WSM (S)</span>
                    <span class="text-[11px] text-[#64746D]">Penjumlahan: &Sigma;(Wj &times; Rij)</span>
                </div>
                <div class="space-y-0.5">
                    <span class="font-bold text-[#103F36] block">3. Model WPM (P)</span>
                    <span class="text-[11px] text-[#64746D]">Perkalian: &Pi;(Rij ^ Wj)</span>
                </div>
                <div class="space-y-0.5">
                    <span class="font-bold text-[#103F36] block">4. Skor Akhir (Qi)</span>
                    <span class="text-[11px] text-[#64746D] font-mono text-emerald-800 font-bold">&lambda; &times; S + (1 - &lambda;) &times; P</span>
                </div>
            </div>
        </div>
    </section>

    <!-- PENANGANAN ERROR / KOSONG -->
    @if ($pesan)
        <div class="rounded-2xl border border-red-200 bg-red-50/80 p-5 text-xs text-red-900" role="alert">
            <strong class="font-bold text-sm block">Perhitungan Terkendala:</strong>
            <p class="mt-1">{{ $pesan }}</p>
        </div>
    @elseif ($hasil['jumlah'] === 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-6 text-xs text-amber-950 text-center">
            <span class="text-2xl block mb-2">⚠️</span>
            <p class="font-bold text-sm">Belum ada alternatif yang memenuhi syarat pada lingkup ini.</p>
            <p class="text-amber-900/80 mt-1">Periksa menu Pengaturan Lingkup Seleksi atau menu Pengajuan Siap Seleksi.</p>
        </div>
    @else

        <!-- ==================================================
             2. DATA ASLI ALTERNATIF (DECISION MATRIX)
        ================================================== -->
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-[#103F36]">2</span>
                        <span>Matriks Keputusan Awal (Data Asli Alternatif)</span>
                    </h2>
                    <p class="text-xs text-[#64746D] mt-0.5">
                        Nilai input parameter kriteria C1–C5 sebelum dilakukan normalisasi skala.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Nama Alternatif</th>
                            <th class="px-4 py-3.5 text-amber-900 bg-amber-50/40">C1 (Cost)</th>
                            <th class="px-4 py-3.5">C2 (Benefit)</th>
                            <th class="px-4 py-3.5">C3 (Benefit)</th>
                            <th class="px-4 py-3.5">C4 (Benefit)</th>
                            <th class="px-4 py-3.5">C5 (Benefit)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF3F0]">
                        @foreach ($alternatif as $item)
                            <tr class="hover:bg-[#F9FCFA]">
                                <td class="px-5 py-3 font-extrabold text-[#103F36]">{{ $item->kode }}</td>
                                <td class="px-5 py-3 font-medium text-[#172B25]">{{ $item->nama }}</td>
                                <td class="px-4 py-3 tabular-nums font-medium text-[#172B25] bg-amber-50/20">
                                    Rp {{ number_format((float) $item->penghasilan_orang_tua, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 tabular-nums text-[#64746D]">{{ $item->jumlah_tanggungan }} org</td>
                                <td class="px-4 py-3 tabular-nums text-[#64746D]">{{ $item->nilai_akademik }}</td>
                                <td class="px-4 py-3 tabular-nums text-[#64746D]">{{ $item->kondisi_tempat_tinggal }} / 5</td>
                                <td class="px-4 py-3 tabular-nums text-[#64746D]">{{ $item->prestasi_non_akademik }} / 5</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ==================================================
             3. ACUAN PEMBATAS NORMALISASI
        ================================================== -->
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-[#103F36]">3</span>
                    <span>Nilai Ekstrem Acuan Normalisasi (Min / Max)</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Kriteria Cost menggunakan nilai minimum; Kriteria Benefit menggunakan nilai maksimum.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                            <th class="px-6 py-3.5">Kriteria</th>
                            <th class="px-4 py-3.5">Kategori</th>
                            <th class="px-4 py-3.5">Nilai Ekstrem (Batas)</th>
                            <th class="px-6 py-3.5">Rumus Normalisasi Rij</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF3F0]">
                        @foreach ($kriteria as $kode => $detail)
                            @php $i = $loop->index; @endphp
                            <tr class="hover:bg-[#F9FCFA]">
                                <td class="px-6 py-3 font-bold text-[#103F36]">{{ $kode }} - {{ $detail['nama'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-extrabold {{ $detail['jenis'] === 'cost' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ strtoupper($detail['jenis']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-[#172B25] tabular-nums">
                                    {{ number_format($hasil['batas'][$i], $i === 0 ? 0 : 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 font-mono text-[#64746D]">
                                    {{ $detail['jenis'] === 'cost' ? 'min(x) / x_ij' : 'x_ij / max(x)' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ==================================================
             4. MATRIKS NORMALISASI & SKOR AKHIR WASPAS (QI)
        ================================================== -->
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <div>
                    <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#20A67A] text-xs font-extrabold text-white">4</span>
                        <span>Matriks Normalisasi & Perangkingan Preferensi (Qi)</span>
                    </h2>
                    <p class="text-xs text-[#64746D] mt-0.5">
                        Urutan peringkat dari nilai Qi tertinggi (prioritas utama beasiswa).
                    </p>
                </div>

                <a href="{{ route('admin.hasil.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white shadow-md shadow-[#103F36]/20 hover:bg-[#116759] transition">
                    <span>Lihat Halaman Penetapan Hasil</span>
                    <span>→</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                            <th class="px-5 py-3.5 text-center">Rank</th>
                            <th class="px-4 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Nama Alternatif</th>
                            <th class="px-3 py-3.5">r1 (Cost)</th>
                            <th class="px-3 py-3.5">r2</th>
                            <th class="px-3 py-3.5">r3</th>
                            <th class="px-3 py-3.5">r4</th>
                            <th class="px-3 py-3.5">r5</th>
                            <th class="px-4 py-3.5 text-[#116759]">WSM (S)</th>
                            <th class="px-4 py-3.5 text-[#116759]">WPM (P)</th>
                            <th class="px-5 py-3.5 text-right bg-emerald-50 text-[#103F36]">Skor Qi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF3F0]">
                        @foreach ($hasil['peringkat'] as $baris)
                            @php
                                $isTop3 = $baris['peringkat'] <= 3;
                            @endphp
                            <tr class="hover:bg-[#F9FCFA] {{ $isTop3 ? 'bg-emerald-50/20' : '' }}">
                                <td class="px-5 py-3 text-center">
                                    @if ($baris['peringkat'] === 1)
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-400 text-slate-900 font-extrabold text-xs shadow-xs">1</span>
                                    @elseif ($baris['peringkat'] === 2)
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-200 text-slate-800 font-extrabold text-xs">2</span>
                                    @elseif ($baris['peringkat'] === 3)
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-700 text-white font-extrabold text-xs">3</span>
                                    @else
                                        <span class="font-bold text-slate-500 tabular-nums">#{{ $baris['peringkat'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-extrabold text-[#103F36]">{{ $baris['kode'] }}</td>
                                <td class="px-5 py-3 font-semibold text-[#172B25]">{{ $baris['nama'] }}</td>

                                @foreach ($baris['normalisasi'] as $r)
                                    <td class="px-3 py-3 tabular-nums text-[#64746D]">
                                        {{ number_format($r, 4, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="px-4 py-3 tabular-nums font-semibold text-[#116759]">
                                    {{ number_format($baris['s'], 5, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 tabular-nums font-semibold text-[#116759]">
                                    {{ number_format($baris['p'], 5, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right tabular-nums font-mono font-extrabold text-[#103F36] bg-emerald-50/40 text-sm">
                                    {{ number_format($baris['q'], 6, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ==================================================
             5. RINCIAN PERHITUNGAN PER ALTERNATIF (ACCORDION)
        ================================================== -->
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-[#103F36]">5</span>
                    <span>Audit Trail Detail Perhitungan Tiap Alternatif</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Klik pada baris nama alternatif untuk menelusuri kontribusi nilai normalisasi, bobot, dan perpangkatan per kriteria.
                </p>
            </div>

            <div class="p-6 space-y-3">
                @foreach ($hasil['peringkat'] as $baris)
                    @php $asli = $dataAsli->get($baris['kode']); @endphp
                    <details class="rounded-xl border border-[#E3EBE6] bg-[#FAFCFB] p-4 transition group">
                        <summary class="cursor-pointer list-none font-bold text-[#103F36] text-xs flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="rounded-lg bg-emerald-100 px-2 py-0.5 text-[11px] font-extrabold text-[#103F36]">
                                    #{{ $baris['peringkat'] }}
                                </span>
                                <span class="text-sm font-extrabold text-[#172B25]">{{ $baris['kode'] }} — {{ $baris['nama'] }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-[#64746D]">Qi = <strong class="text-[#103F36] font-mono">{{ number_format($baris['q'], 6, ',', '.') }}</strong></span>
                                <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                            </div>
                        </summary>

                        <div class="mt-4 overflow-x-auto rounded-lg border border-[#E3EBE6] bg-white">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[10px] font-bold uppercase tracking-wider text-[#64746D]">
                                        <th class="px-3.5 py-2.5">Kriteria</th>
                                        <th class="px-3.5 py-2.5">Nilai Asli (x)</th>
                                        <th class="px-3.5 py-2.5">Batas Min/Max</th>
                                        <th class="px-3.5 py-2.5">Normalisasi (r)</th>
                                        <th class="px-3.5 py-2.5">Bobot (w)</th>
                                        <th class="px-3.5 py-2.5">w &times; r (WSM)</th>
                                        <th class="px-3.5 py-2.5">r ^ w (WPM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EEF3F0]">
                                    @foreach ($daftarKriteria as $i => $detail)
                                        @php
                                            $r = $baris['normalisasi'][$i];
                                            $w = $informasi['bobot'][$i];
                                            $x = $asli?->{$detail['kolom']};
                                        @endphp
                                        <tr>
                                            <td class="px-3.5 py-2 font-bold text-[#103F36]">C{{ $i + 1 }} - {{ $detail['nama'] }}</td>
                                            <td class="px-3.5 py-2 tabular-nums">{{ number_format((float) $x, $i === 0 ? 0 : 2, ',', '.') }}</td>
                                            <td class="px-3.5 py-2 tabular-nums">{{ number_format($hasil['batas'][$i], $i === 0 ? 0 : 2, ',', '.') }}</td>
                                            <td class="px-3.5 py-2 tabular-nums font-mono">{{ number_format($r, 5, ',', '.') }}</td>
                                            <td class="px-3.5 py-2 tabular-nums font-mono">{{ number_format($w, 4, ',', '.') }}</td>
                                            <td class="px-3.5 py-2 tabular-nums font-mono">{{ number_format($w * $r, 5, ',', '.') }}</td>
                                            <td class="px-3.5 py-2 tabular-nums font-mono">{{ number_format(pow($r, $w), 5, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs text-[#64746D] pt-1">
                            <span>S (WSM) = <strong class="text-[#103F36]">{{ number_format($baris['s'], 6, ',', '.') }}</strong></span>
                            <span>P (WPM) = <strong class="text-[#103F36]">{{ number_format($baris['p'], 6, ',', '.') }}</strong></span>
                            <span>Skor Akhir Qi = <strong class="text-emerald-800 text-sm font-mono">{{ number_format($baris['q'], 6, ',', '.') }}</strong></span>
                        </div>
                    </details>
                @endforeach
            </div>
        </section>

    @endif

</main>

@endsection
