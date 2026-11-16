@extends('layouts.admin')

@section('title', 'Analisis Sensitivitas WASPAS - Beasiswa Cerdas')
@section('judul-admin', 'Analisis Sensitivitas')
@section('deskripsi-admin', 'Uji kestabilan peringkat alternatif terhadap variasi parameter lambda (λ = 0, 0,5, dan 1).')

@section('admin-content')

<main class="bc-admin-main space-y-7">

    <!-- HEADER / PARAMETER CONTROL PANEL -->
    <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
            <div>
                <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                    <span>📈</span>
                    <span>Uji Sensitivitas Nilai Parameter Lambda (&lambda;)</span>
                </h2>
                <p class="text-xs text-[#64746D] mt-0.5">
                    Evaluasi ketahanan sistem terhadap perubahan bobot kombinasi model WSM dan WPM.
                </p>
            </div>

            <a href="{{ route('admin.hasil.index') }}"
               class="rounded-xl border border-[#CFDED8] bg-white px-3.5 py-1.5 text-xs font-bold text-[#103F36] hover:bg-[#F5F8F6] transition">
                ← Kembali ke Hasil Seleksi
            </a>
        </div>

        <div class="p-6 space-y-6">
            <!-- Form Pilih Lingkup -->
            <form method="GET" action="{{ route('admin.sensitivitas.index') }}" class="flex flex-wrap items-end gap-3 bg-[#F7FAF8] p-4 rounded-xl border border-[#E3EBE6]">
                <div class="min-w-[220px] text-xs">
                    <label for="lingkup" class="font-bold text-[#103F36] block mb-1">
                        Pilih Lingkup Analisis:
                    </label>
                    <select id="lingkup" name="lingkup" class="w-full rounded-xl border border-[#CFDED8] bg-white px-3.5 py-2 text-xs font-semibold text-[#172B25] focus:border-[#20A67A] focus:outline-none">
                        <option value="artikel" @selected($lingkup === 'artikel')>
                            Hanya Alternatif A1–A5 (Rujukan Artikel)
                        </option>
                        <option value="semua" @selected($lingkup === 'semua')>
                            Seluruh Alternatif Aktif (Termasuk Pendaftar PG-ID)
                        </option>
                    </select>
                </div>

                <button type="submit" class="rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-[#116759] transition">
                    Terapkan Lingkup
                </button>
            </form>

            <!-- Explanation 3 Box Lambda -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="rounded-xl border border-[#E3EBE6] bg-[#FAFCFB] p-4 space-y-1">
                    <span class="font-extrabold text-[#103F36] block">&lambda; = 0 (Pure WPM)</span>
                    <p class="text-[11px] text-[#64746D] leading-relaxed">
                        Hanya menggunakan model perkalian bobot (Weighted Product).
                    </p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 space-y-1">
                    <span class="font-extrabold text-emerald-950 block">&lambda; = 0,5 (Standard WASPAS)</span>
                    <p class="text-[11px] text-emerald-900/80 leading-relaxed">
                        Keseimbangan 50% penjumlahan WSM dan 50% perkalian WPM.
                    </p>
                </div>
                <div class="rounded-xl border border-[#E3EBE6] bg-[#FAFCFB] p-4 space-y-1">
                    <span class="font-extrabold text-[#103F36] block">&lambda; = 1 (Pure WSM)</span>
                    <p class="text-[11px] text-[#64746D] leading-relaxed">
                        Hanya menggunakan model penjumlahan terbobot (Weighted Sum).
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700 leading-relaxed flex items-center justify-between">
                <span>Sumber Bobot Terhitung: <strong>{{ $sumber }}</strong> &bull; Total Alternatif: <strong>{{ count($alternatif) }}</strong></span>
                <span class="text-[11px] text-slate-500">Tidak mengubah basis data tersimpan</span>
            </div>
        </div>
    </section>

    <!-- HASIL ANALISIS SENSITIVITAS -->
    @if ($pesan)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-xs text-red-900" role="alert">
            {{ $pesan }}
        </div>
    @elseif ($hasil['jumlah'] === 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-xs text-amber-950 text-center">
            Belum ada alternatif pada lingkup yang dipilih.
        </div>
    @else
        <section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB]">
                <div>
                    <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2">
                        <span>📊</span>
                        <span>Perbandingan Nilai Qi & Peringkat pada Variasi &lambda;</span>
                    </h2>
                    <p class="text-xs text-[#64746D] mt-0.5">
                        Mengetahui apakah terjadi pergeseran ranking (rank reversal) saat parameter diubah.
                    </p>
                </div>

                <span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $hasil['stabil'] ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                    {{ $hasil['stabil'] ? '✓ Peringkat Konsisten & Stabil' : '⚠️ Terjadi Perubahan Urutan Peringkat' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F7FAF8] border-b border-[#E3EBE6] text-[10px] font-bold uppercase tracking-wider text-[#64746D]">
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-6 py-3.5">Nama Alternatif</th>
                            <th class="px-3.5 py-3.5 text-center bg-slate-50/50">Qi (&lambda;=0)</th>
                            <th class="px-3.5 py-3.5 text-center bg-slate-50/50">Rank (&lambda;=0)</th>
                            <th class="px-3.5 py-3.5 text-center bg-emerald-50/40 text-[#103F36]">Qi (&lambda;=0.5)</th>
                            <th class="px-3.5 py-3.5 text-center bg-emerald-50/40 text-[#103F36]">Rank (&lambda;=0.5)</th>
                            <th class="px-3.5 py-3.5 text-center bg-slate-50/50">Qi (&lambda;=1)</th>
                            <th class="px-3.5 py-3.5 text-center bg-slate-50/50">Rank (&lambda;=1)</th>
                            <th class="px-5 py-3.5 text-right">Stabilitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEF3F0]">
                        @foreach ($hasil['baris'] as $baris)
                            <tr class="hover:bg-[#F9FCFA]">
                                <td class="px-5 py-3 font-bold font-mono text-[#103F36]">{{ $baris['kode'] }}</td>
                                <td class="px-6 py-3 font-semibold text-[#172B25]">{{ $baris['nama'] }}</td>

                                @foreach (['0', '05', '1'] as $idx => $lmb)
                                    @php $isStandard = $lmb === '05'; @endphp
                                    <td class="px-3.5 py-3 text-center tabular-nums font-mono {{ $isStandard ? 'font-bold text-[#103F36] bg-emerald-50/20' : 'text-slate-600' }}">
                                        {{ number_format($baris['nilai'][$lmb]['nilai'], 4, ',', '.') }}
                                    </td>
                                    <td class="px-3.5 py-3 text-center {{ $isStandard ? 'bg-emerald-50/20' : '' }}">
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold {{ $baris['nilai'][$lmb]['peringkat'] === 1 ? 'bg-amber-400 text-slate-900' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $baris['nilai'][$lmb]['peringkat'] }}
                                        </span>
                                    </td>
                                @endforeach

                                <td class="px-5 py-3 text-right">
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-extrabold {{ $baris['stabil'] ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $baris['stabil'] ? 'Stabil' : 'Berubah' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-[#EEF3F0] bg-[#FAFCFB] text-xs text-[#64746D] leading-relaxed">
                <strong>Interpretasi Akademik:</strong>
                @if ($hasil['stabil'])
                    Urutan prioritas alternatif terbukti robust (kebal terhadap variasi model WSM maupun WPM), membuktikan bahwa perangkingan WASPAS sangat valid.
                @else
                    Terdapat pergeseran minor pada ranking akibat perbedaan sensitivitas nilai WSM dan WPM, yang merupakan karakteristik wajar dari multi-kriteria evaluasi.
                @endif
            </div>
        </section>
    @endif

</main>

@endsection
