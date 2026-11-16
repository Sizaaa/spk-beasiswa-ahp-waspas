@extends('layouts.admin')

@section('title', 'Perhitungan AHP - Beasiswa Cerdas')
@section('judul-admin', 'Perhitungan AHP')
@section('deskripsi-admin', 'Pembobotan kriteria dan pengujian konsistensi matriks perbandingan berpasangan.')

@section('admin-content')

@php
    $kodeKriteria = array_keys($kriteria);
    $namaKriteria = array_column(array_values($kriteria), 'nama');
    $modeSimulasiAktif = $bobotAktif['mode'] === 'simulasi';

    $bcModeBobot = $modeAktif ?? ($informasi['mode'] ?? ($bobotAktif['mode'] ?? null));
    if (!in_array($bcModeBobot, ['artikel', 'simulasi'], true)) {
        $bcModeBobot = is_array($bobotAktif['ahp'] ?? null) ? 'simulasi' : 'artikel';
    }
@endphp

<main class="bc-admin-main space-y-7">

    <!-- ALERT SUKSES / ERROR -->
    @if (session('sukses'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4.5 text-xs font-semibold text-emerald-900 shadow-xs" role="status">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-200/80 text-emerald-900 font-bold">✓</span>
            <div class="flex-1">{{ session('sukses') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50/80 p-5 text-xs text-red-900 shadow-xs" role="alert">
            <p class="font-bold flex items-center gap-2 text-sm text-red-950">
                <span>⚠️</span>
                <span>Terjadi kesalahan pada data masukan:</span>
            </p>
            <ul class="mt-2 list-inside list-disc space-y-1 font-medium pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ==================================================
         1. BOBOT YANG TERCANTUM PADA ARTIKEL
    ================================================== -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-extrabold text-[#103F36]">1</span>
                    <span>Bobot Acuan Artikel Penelitian (Jurnal AHP)</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Data rujukan primer: Penentuan Bobot Kriteria dengan AHP, hal. 13 (CR = 0,045).
                </p>
            </div>

            <span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $modeSimulasiAktif ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-900' }}">
                {{ $modeSimulasiAktif ? 'Mode Simulasi Aktif' : '✓ Mode Artikel Sedang Digunakan' }}
            </span>
        </div>

        <div class="p-6 space-y-6">
            <!-- 5 Kriteria Mini Stat Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
                @foreach ($kodeKriteria as $i => $kode)
                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-4 text-center hover:border-emerald-300 transition">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#116759] block">
                            {{ $kode }}
                        </span>
                        <span class="text-xl font-extrabold text-[#103F36] mt-1 block tabular-nums">
                            {{ number_format(\App\Services\SpkCalculator::BOBOT_REFERENSI[$i], 3, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-[#64746D] truncate block mt-1" title="{{ $namaKriteria[$i] }}">
                            {{ $namaKriteria[$i] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Catatan Metodologi -->
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 text-xs text-emerald-950 space-y-1 leading-relaxed">
                <p class="font-bold flex items-center gap-1.5">
                    <span>💡</span>
                    <span>Informasi Verifikasi Rujukan:</span>
                </p>
                <p class="text-emerald-900/80">
                    Nilai CR = 0,045 adalah hasil yang dilaporkan dalam jurnal publikasi rujukan. Matriks perbandingan mentah tidak dicantumkan lengkap dalam artikel asli sehingga bobot di atas dijadikan standar acuan baku penelitian.
                </p>
            </div>

            <!-- Status Banner & Switcher -->
            @if ($bcModeBobot === 'artikel')
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-emerald-200 bg-[#F0FAF5] p-4.5">
                    <div class="space-y-1">
                        <h4 class="text-xs font-extrabold text-[#0C4B3A]">
                            ✓ Bobot Referensi Artikel Sedang Digunakan di Sistem
                        </h4>
                        <p class="text-xs text-[#4E7063]">
                            Bobot C1–C5 dari artikel saat ini menjadi pengali pada normalisasi WASPAS, perankingan, dan cetak laporan.
                        </p>
                    </div>

                    <a href="{{ route('admin.waspas.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-[#116759] bg-white px-4 py-2 text-xs font-bold text-[#116759] hover:bg-[#116759] hover:text-white transition">
                        <span>Lihat Perhitungan WASPAS</span>
                        <span>→</span>
                    </a>
                </div>
            @else
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-amber-200 bg-[#FFFDF5] p-4.5">
                    <div class="space-y-1">
                        <h4 class="text-xs font-extrabold text-amber-950">
                            ⚙ Mode Simulasi Baru Sedang Aktif
                        </h4>
                        <p class="text-xs text-amber-900/80">
                            Sistem sedang menggunakan bobot yang dihasilkan dari form simulasi perbandingan di bawah.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.ahp.pilih') }}">
                        @csrf
                        <input type="hidden" name="mode" value="artikel">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white hover:bg-[#116759] transition shadow-xs">
                            <span>Kembali ke Bobot Artikel</span>
                            <span>↺</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </section>

    <!-- ==================================================
         2. BOBOT AKTIF UNTUK KOMPUTASI
    ================================================== -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB] flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-100 text-xs font-extrabold text-[#116759]">2</span>
                    <span>Bobot Aktif Komputasi WASPAS & Seleksi</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Sumber data aktif: <strong>{{ $bobotAktif['sumber'] }}</strong>
                </p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
                @foreach ($kodeKriteria as $i => $kode)
                    <div class="rounded-xl border border-emerald-100 bg-[#F4F9F7] p-4 text-center">
                        <span class="text-[10px] font-extrabold text-[#116759] uppercase block">{{ $kode }}</span>
                        <span class="text-xl font-extrabold text-[#103F36] mt-1 block tabular-nums">
                            {{ number_format((float) $bobotAktif['bobot'][$i], 5, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-[#64746D] truncate block mt-0.5">
                            {{ round(((float)$bobotAktif['bobot'][$i]) * 100, 1) }}% kontribusi
                        </span>
                    </div>
                @endforeach
            </div>

            <p class="text-[11px] text-[#84968F] mt-4 leading-relaxed">
                * Pengaturan bobot ini disimpan pada session dan memengaruhi modul WASPAS, Hasil Seleksi, Analisis Sensitivitas, dan Laporan.
            </p>
        </div>
    </section>

    <!-- ==================================================
         3. FORM SIMULASI MATRIKS PERBANDINGAN BERPASANGAN
    ================================================== -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-xs font-extrabold text-blue-900">3</span>
                <span>Simulasi Matriks Perbandingan Berpasangan (Pairwise Comparison)</span>
            </h2>
            <p class="text-xs text-[#64746D] mt-0.5">
                Masukkan nilai skala Saaty (1–9 atau pecahan seperti 0.333) untuk menguji konsistensi matriks perbandingan kriteria baru.
            </p>
        </div>

        <div class="p-6 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-[#F8FAFC] p-4 text-xs text-slate-700 leading-relaxed">
                <strong>Pedoman Skala Saaty:</strong> 1 = Sama penting, 3 = Sedikit lebih penting, 5 = Lebih penting, 7 = Sangat lebih penting, 9 = Mutlak lebih penting. Nilai kebalikan (1/x) dihitung otomatis oleh sistem.
            </div>

            <form method="POST" action="{{ route('admin.ahp.hitung') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @for ($i = 0; $i < 5; $i++)
                        @for ($j = $i + 1; $j < 5; $j++)
                            @php $kunci = $i . '_' . $j; @endphp
                            <div class="rounded-xl border border-[#E3EBE6] bg-[#FAFCFB] p-4 hover:border-emerald-300 transition space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-[#103F36]">
                                        {{ $kodeKriteria[$i] }} vs {{ $kodeKriteria[$j] }}
                                    </span>
                                    <span class="text-[11px] text-[#64746D]">
                                        {{ $namaKriteria[$i] }} / {{ $namaKriteria[$j] }}
                                    </span>
                                </div>

                                <input
                                    type="number"
                                    name="pasangan[{{ $kunci }}]"
                                    value="{{ old('pasangan.' . $kunci, $pasangan[$kunci] ?? '') }}"
                                    min="0.111111111111"
                                    max="9"
                                    step="any"
                                    inputmode="decimal"
                                    required
                                    class="w-full rounded-xl border border-[#CFDED8] bg-white px-3.5 py-2.5 text-xs font-semibold text-[#172B25] focus:border-[#20A67A] focus:outline-none focus:ring-2 focus:ring-[#20A67A]/20 transition"
                                >
                            </div>
                        @endfor
                    @endfor
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#103F36] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#103F36]/20 hover:bg-[#116759] transition">
                        <span>Hitung Bobot AHP Baru</span>
                        <span>⚡</span>
                    </button>

                    <a href="{{ route('admin.waspas.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-[#CFDED8] bg-white px-4 py-2.5 text-xs font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                        <span>Buka Modul WASPAS</span>
                        <span>→</span>
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- ==================================================
         4. HASIL SIMULASI & UJI KONSISTENSI
    ================================================== -->
    @if ($hasil && isset($hasil['matriks'], $hasil['bobot'], $hasil['cr']))
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <div>
                    <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-extrabold text-[#103F36]">4</span>
                        <span>Hasil Perhitungan Matriks & Uji Konsistensi</span>
                    </h2>
                    <p class="text-xs text-[#64746D] mt-0.5">
                        Nilai Eigenvektor, Nilai Maksimum (λmax), Consistency Index (CI), dan Consistency Ratio (CR).
                    </p>
                </div>

                <span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $simulasiLayak ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">
                    {{ $simulasiLayak ? '✓ Matriks Konsisten (CR ≤ 0.10)' : '✕ Tidak Konsisten (CR > 0.10)' }}
                </span>
            </div>

            <div class="p-6 space-y-6">
                <!-- Matriks Table -->
                <div class="overflow-x-auto rounded-xl border border-[#E3EBE6]">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[11px] font-bold uppercase tracking-wider text-[#64746D]">
                                <th class="px-4 py-3">Kriteria</th>
                                @foreach ($kodeKriteria as $kode)
                                    <th class="px-4 py-3">{{ $kode }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-right bg-emerald-50/60 text-[#103F36]">Bobot Simulasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EEF3F0]">
                            @foreach ($kodeKriteria as $i => $kode)
                                <tr class="hover:bg-[#F9FCFA]">
                                    <td class="px-4 py-3 font-bold text-[#103F36] bg-[#FAFCFB]">
                                        {{ $kode }} - {{ $namaKriteria[$i] }}
                                    </td>
                                    @foreach ($kodeKriteria as $j => $kodeKolom)
                                        <td class="px-4 py-3 text-[#64746D] tabular-nums">
                                            {{ number_format((float) $hasil['matriks'][$i][$j], 4, ',', '.') }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-right font-extrabold text-[#103F36] bg-emerald-50/40 tabular-nums">
                                        {{ number_format((float) $hasil['bobot'][$i], 6, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 3 Consistency Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-4 text-center">
                        <span class="text-[10px] font-bold text-[#64746D] uppercase">λ maks (Eigenvalue)</span>
                        <span class="text-2xl font-extrabold text-[#103F36] mt-1 block tabular-nums">
                            {{ number_format((float) $hasil['lambda_max'], 5, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400 mt-1 block">Ideal mendekati 5.000</span>
                    </div>

                    <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-4 text-center">
                        <span class="text-[10px] font-bold text-[#64746D] uppercase">CI (Consistency Index)</span>
                        <span class="text-2xl font-extrabold text-[#103F36] mt-1 block tabular-nums">
                            {{ number_format((float) $hasil['ci'], 5, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400 mt-1 block">(λmax - n) / (n - 1)</span>
                    </div>

                    <div class="rounded-xl border {{ $simulasiLayak ? 'border-emerald-200 bg-emerald-50/60' : 'border-rose-200 bg-rose-50/60' }} p-4 text-center">
                        <span class="text-[10px] font-bold {{ $simulasiLayak ? 'text-emerald-800' : 'text-rose-800' }} uppercase">CR (Consistency Ratio)</span>
                        <span class="text-2xl font-extrabold {{ $simulasiLayak ? 'text-emerald-900' : 'text-rose-900' }} mt-1 block tabular-nums">
                            {{ number_format((float) $hasil['cr'], 5, ',', '.') }}
                        </span>
                        <span class="text-[10px] font-semibold {{ $simulasiLayak ? 'text-emerald-700' : 'text-rose-700' }} mt-1 block">
                            {{ $simulasiLayak ? 'CR ≤ 0.10 (Konsisten)' : 'CR > 0.10 (Inkonsisten)' }}
                        </span>
                    </div>
                </div>

                <!-- Action Button for Applying Weights -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    @if ($simulasiLayak)
                        <form method="POST" action="{{ route('admin.ahp.pilih') }}">
                            @csrf
                            <input type="hidden" name="mode" value="simulasi">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#20A67A] px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-[#20A67A]/25 hover:bg-[#1B8E68] transition"
                                    @disabled($modeSimulasiAktif)>
                                <span>Gunakan Bobot Simulasi Ini untuk WASPAS</span>
                                <span>✓</span>
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.ahp.reset') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-xl border border-red-200 bg-red-50/60 px-4 py-2.5 text-xs font-bold text-red-700 hover:bg-red-100 transition"
                                onclick="return confirm('Hapus hasil simulasi dan kembali ke bobot acuan artikel?')">
                            Reset Simulasi
                        </button>
                    </form>
                </div>
            </div>
        </section>
    @endif

</main>

@endsection
