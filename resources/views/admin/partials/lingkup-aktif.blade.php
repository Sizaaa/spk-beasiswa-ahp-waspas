
@php
    $infoLingkup = $lingkupInfo ?? [
        'mode' => 'artikel',
        'judul' => 'Penelitian A1–A5',
        'alternatif' => [],
        'catatan' => [],
    ];

    $namaLingkup = $infoLingkup['judul']
        ?? 'Penelitian A1–A5';

    $jumlahAlternatif = count(
        $infoLingkup['alternatif'] ?? []
    );

    $catatanLingkup = $infoLingkup['catatan']
        ?? [];
@endphp

<div class="bc-notice no-print mb-5">

    <div class="flex flex-wrap
                items-center justify-between gap-4">

        <!-- INFORMASI LINGKUP -->
        <div class="min-w-0 flex-1">

            <p class="font-extrabold text-[#103F36]">

                Kelompok Data yang Sedang Dinilai:

                {{ $namaLingkup }}

            </p>

            <p class="mt-1 text-sm text-slate-600">

                Jumlah alternatif:

                <strong>
                    {{ $jumlahAlternatif }}
                </strong>

            </p>

            <p class="mt-1 text-xs
                      leading-6 text-slate-500">

                WASPAS, Hasil Seleksi, dan Laporan
                mengikuti lingkup yang dipilih Admin.

            </p>

        </div>

        <!-- TOMBOL NAVIGASI -->
        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('admin.lingkup.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Ubah Lingkup
            </a>

            <a
                href="{{ route('admin.riwayat.index') }}"
                class="bc-btn bc-btn--primary"
            >
                Riwayat Perhitungan
            </a>

        </div>

    </div>

    <!-- CATATAN LINGKUP -->
    @if (!empty($catatanLingkup))

        <div class="mt-3 space-y-2">

            @foreach ($catatanLingkup as $catatan)

                <p class="text-sm
                          leading-6 text-amber-800">

                    {{ $catatan }}

                </p>

            @endforeach

        </div>

    @endif

</div>
