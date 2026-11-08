
@extends('layouts.admin')

@section('title', 'Data Calon Penerima - Beasiswa Cerdas')

@section('judul-admin', 'Data Calon Penerima')

@section(
    'deskripsi-admin',
    'Kelola alternatif penelitian, manual, dan pengajuan terverifikasi.'
)

@section('admin-content')

@php
    $formTerbuka = $edit !== null || $errors->any();

    $isPengajuan = static fn (string $kode): bool =>
        preg_match(
            '/^PG-[1-9][0-9]*$/D',
            strtoupper($kode)
        ) === 1;
@endphp

<main class="bc-admin-main space-y-6">
    <!-- Ringkasan jumlah seluruh alternatif -->
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3
                rounded-2xl border border-slate-200
                bg-white px-5 py-4 shadow-sm">

        <span class="text-sm font-semibold text-[#103F36]">
            Jumlah seluruh alternatif
        </span>

        <strong class="text-xl font-extrabold text-[#103F36]">
            {{ number_format($totalData, 0, ',', '.') }}
        </strong>

    </div>


    <!-- PESAN BERHASIL -->
    @if (session('sukses'))

        <div
            role="status"
            class="rounded-xl border border-emerald-200
                   bg-emerald-50 p-4 text-sm text-emerald-800"
        >
            {{ session('sukses') }}
        </div>

    @endif

    <!-- ERROR VALIDASI -->
    @if ($errors->any())

        <div
            role="alert"
            class="rounded-xl border border-red-200
                   bg-red-50 p-4 text-sm text-red-700"
        >

            <strong>Periksa formulir:</strong>

            @foreach ($errors->all() as $error)
                <p class="mt-1">{{ $error }}</p>
            @endforeach

        </div>

    @endif

    <!-- RINGKASAN -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Ringkasan Alternatif
                </h2>

                <p class="bc-panel-desc">

                    {{ $totalData }} Total Calon yang Dinilai

                    · {{ $jumlahHasil }} hasil pencarian.

                </p>

            </div>

            <a
                href="{{ route(
                    'admin.seleksi-pengajuan.index'
                ) }}"
                class="bc-btn bc-btn--primary"
            >
                Pengajuan Siap Seleksi
            </a>

        </div>

        <div class="bc-panel-body">

            <div class="bc-notice">

                <strong>A1–A5:</strong>
                data penelitian yang dikunci.

                <strong>PG-ID:</strong>
                alternatif dari User terverifikasi
                yang juga dikunci dari edit/hapus manual.

                Calon Penerima Lainnya dapat dikelola
                menggunakan formulir di bawah.

            </div>

        </div>

    </section>

    <!-- FORMULIR TAMBAH / EDIT -->
    <details
        class="bc-panel"
        @if ($formTerbuka) open @endif
    >

        <summary
            class="bc-panel-header cursor-pointer"
            style="list-style:none"
        >

            <div>

                <h2 class="bc-panel-title">

                    {{ $edit
                        ? 'Edit Alternatif ' . $edit->kode
                        : 'Tambah Alternatif Manual' }}

                </h2>

                <p class="bc-panel-desc">
                    Klik untuk membuka atau menutup
                    formulir C1–C5.
                </p>

            </div>

            <span class="bc-btn bc-btn--outline">

                {{ $edit
                    ? 'Mode Edit'
                    : 'Buka Formulir' }}

            </span>

        </summary>

        <div class="bc-panel-body">

            <form
                method="POST"
                action="{{ $edit
                    ? route(
                        'admin.calon-penerima.update',
                        $edit->id
                    )
                    : route(
                        'admin.calon-penerima.store'
                    ) }}"
            >

                @csrf

                @if ($edit)
                    @method('PUT')
                @endif

                <div class="grid gap-4
                            md:grid-cols-2 lg:grid-cols-3">

                    <!-- KODE -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        Kode Calon Penerima

                        <input
                            name="kode"
                            type="text"
                            maxlength="20"
                            required
                            class="bc-form-control mt-2 w-full"
                            placeholder="Contoh: A7"
                            value="{{ old(
                                'kode',
                                $edit->kode ?? ''
                            ) }}"
                        >

                        <span class="bc-muted-note mt-1 block">
                            Awalan PG-ID hanya untuk
                            pengajuan User.
                        </span>

                    </label>

                    <!-- NAMA -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700
                               lg:col-span-2"
                    >

                        Nama Calon Penerima

                        <input
                            name="nama"
                            type="text"
                            maxlength="100"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'nama',
                                $edit->nama ?? ''
                            ) }}"
                        >

                    </label>

                    <!-- C1 -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        C1 · Penghasilan Orang Tua (Rp)

                        <input
                            name="penghasilan_orang_tua"
                            type="number"
                            min="1"
                            step="1"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'penghasilan_orang_tua',
                                $edit->penghasilan_orang_tua ?? ''
                            ) }}"
                        >

                    </label>

                    <!-- C2 -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        C2 · Jumlah Tanggungan

                        <input
                            name="jumlah_tanggungan"
                            type="number"
                            min="0"
                            max="65535"
                            step="1"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'jumlah_tanggungan',
                                $edit->jumlah_tanggungan ?? ''
                            ) }}"
                        >

                    </label>

                    <!-- C3 -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        C3 · Nilai Akademik

                        <input
                            name="nilai_akademik"
                            type="number"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'nilai_akademik',
                                $edit->nilai_akademik ?? ''
                            ) }}"
                        >

                    </label>

                    <!-- C4 -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        C4 · Kondisi Tempat Tinggal (1–5)

                        <input
                            name="kondisi_tempat_tinggal"
                            type="number"
                            min="1"
                            max="5"
                            step="1"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'kondisi_tempat_tinggal',
                                $edit->kondisi_tempat_tinggal ?? ''
                            ) }}"
                        >

                    </label>

                    <!-- C5 -->
                    <label
                        class="block text-sm font-semibold
                               text-slate-700"
                    >

                        C5 · Prestasi Non-Akademik (1–5)

                        <input
                            name="prestasi_non_akademik"
                            type="number"
                            min="1"
                            max="5"
                            step="1"
                            required
                            class="bc-form-control mt-2 w-full"
                            value="{{ old(
                                'prestasi_non_akademik',
                                $edit->prestasi_non_akademik ?? ''
                            ) }}"
                        >

                    </label>

                </div>

                <div class="mt-5 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="bc-btn bc-btn--primary"
                    >

                        {{ $edit
                            ? 'Simpan Perubahan'
                            : 'Simpan Alternatif' }}

                    </button>

                    @if ($edit)

                        <a
                            href="{{ route(
                                'admin.calon-penerima.index'
                            ) }}"
                            class="bc-btn bc-btn--outline"
                        >
                            Batal Edit
                        </a>

                    @endif

                </div>

            </form>

        </div>

    </details>

    <!-- DAFTAR ALTERNATIF -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Daftar Calon Penerima
                </h2>

                <p class="bc-panel-desc">

                    A1–A5 untuk penelitian;
                    PG-ID dari pengajuan terverifikasi.

                </p>

            </div>

            <!-- PENCARIAN -->
            <form
                method="GET"
                action="{{ route(
                    'admin.calon-penerima.index'
                ) }}"
                class="flex flex-wrap gap-2"
            >

                <label
                    for="cariAlternatif"
                    class="sr-only"
                >
                    Cari kode atau nama
                </label>

                <input
                    id="cariAlternatif"
                    name="cari"
                    type="search"
                    value="{{ $cari }}"
                    placeholder="Kode atau nama..."
                    class="bc-form-control"
                >

                <button
                    type="submit"
                    class="bc-btn bc-btn--primary"
                >
                    Cari
                </button>

                @if ($cari !== '')

                    <a
                        href="{{ route(
                            'admin.calon-penerima.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Reset
                    </a>

                @endif

            </form>

        </div>

        <div class="bc-panel-body">

            <div
                class="bc-table-scroll"
                role="region"
                aria-label="Data calon penerima"
                tabindex="0"
            >

                <table
                    class="bc-table"
                    style="min-width:1040px"
                >

                    <thead>

                        <tr>

                            <th>No.</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>C1 · Penghasilan</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                            <th>Tindakan</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse (
                            $calonPenerima as $alternatif
                        )

                            @php
                                $dataAwal = in_array(
                                    strtoupper(
                                        $alternatif->kode
                                    ),
                                    [
                                        'A1',
                                        'A2',
                                        'A3',
                                        'A4',
                                        'A5',
                                    ],
                                    true
                                );

                                $dariPengajuan = $isPengajuan(
                                    (string) $alternatif->kode
                                );

                                $terkunci =
                                    $dataAwal || $dariPengajuan;
                            @endphp

                            <tr>

                                <td>

                                    {{ $calonPenerima->firstItem()
                                        + $loop->index }}

                                </td>

                                <!-- KODE -->
                                <td>

                                    <p class="font-bold">
                                        {{ $alternatif->kode }}
                                    </p>

                                    @if ($dataAwal)

                                        <p class="bc-cell-caption">
                                            Penelitian
                                        </p>

                                    @endif

                                    @if ($dariPengajuan)

                                        <p class="bc-cell-caption">
                                            Pengajuan User
                                        </p>

                                    @endif

                                </td>

                                <!-- NAMA -->
                                <td class="bc-cell-title">
                                    {{ $alternatif->nama }}
                                </td>

                                <!-- C1 -->
                                <td>

                                    Rp {{ number_format(
                                        (float) $alternatif->penghasilan_orang_tua,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <!-- C2 -->
                                <td>
                                    {{ $alternatif->jumlah_tanggungan }}
                                </td>

                                <!-- C3 -->
                                <td>

                                    {{ number_format(
                                        (float) $alternatif->nilai_akademik,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <!-- C4 -->
                                <td>
                                    {{ $alternatif->kondisi_tempat_tinggal }}
                                </td>

                                <!-- C5 -->
                                <td>
                                    {{ $alternatif->prestasi_non_akademik }}
                                </td>

                                <!-- TINDAKAN -->
                                <td>

                                    @if ($terkunci)

                                        <span
                                            class="text-xs
                                                   font-semibold
                                                   text-slate-500"
                                        >
                                            Dikunci
                                        </span>

                                    @else

                                        <div class="flex
                                                    items-center gap-2">

                                            <a
                                                href="{{ route(
                                                    'admin.calon-penerima.index',
                                                    ['edit' => $alternatif->id]
                                                    +
                                                    (
                                                        $cari !== ''
                                                        ? ['cari' => $cari]
                                                        : []
                                                    )
                                                ) }}"
                                                class="bc-btn bc-btn--outline"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"

                                                action="{{ route(
                                                    'admin.calon-penerima.destroy',
                                                    $alternatif->id
                                                ) }}"

                                                onsubmit="return confirm('Yakin menghapus alternatif ini?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="bc-btn bc-btn--red"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="py-10
                                           text-center
                                           text-slate-500"
                                >

                                    Belum ada alternatif
                                    yang cocok.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->
            @if ($calonPenerima->hasPages())

                <div class="mt-5">

                    {{ $calonPenerima->links() }}

                </div>

            @endif

        </div>

    </section>

    <p class="bc-muted-note">

        Memasukkan pengajuan ke seleksi
        hanya menambah alternatif perhitungan WASPAS.

        Tindakan ini bukan penetapan siswa
        sebagai penerima beasiswa.

    </p>

</main>

@endsection
