
@extends('layouts.admin')

@section('title', 'Pengajuan Siap Seleksi - Beasiswa Cerdas')

@section('judul-admin', 'Pengajuan Siap Seleksi')

@section(
    'deskripsi-admin',
    'Masukkan pengajuan terverifikasi sebagai alternatif WASPAS.'
)

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- PENJELASAN FITUR -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Hubungkan User Terverifikasi ke WASPAS
                </h2>

                <p class="bc-panel-desc">
                    {{ $daftar->total() }}
                    pengajuan User berstatus Terverifikasi.

                    Data penelitian A1–A5
                    tetap dilindungi.
                </p>

            </div>

            <a
                href="{{ route(
                    'admin.verifikasi.index',
                    ['status' => 'terverifikasi']
                ) }}"
                class="bc-btn bc-btn--outline"
            >
                Lihat Verifikasi
            </a>

        </div>

        <div class="bc-panel-body">

            <div class="bc-notice">

                <strong>Cara kerja:</strong>

                Klik <strong>Masukkan ke Seleksi</strong>
                pada pengajuan yang sudah diperiksa.

                Sistem menyalin C1–C5
                menjadi alternatif dengan kode tetap
                <strong>PG-ID</strong>.

                Contoh: pengajuan #2 menjadi PG-2.

                Status pengajuan User tidak berubah.

                Kode PG-... dilindungi dari edit
                dan hapus melalui CRUD manual.

            </div>

            <p class="bc-muted-note mt-3">

                C4 mengikuti makna skor artikel:
                semakin besar berarti kondisi
                tempat tinggal semakin
                membutuhkan bantuan.

                Masuk ke seleksi bukan berarti
                siswa langsung menerima beasiswa.

            </p>

        </div>

    </section>

    <!-- PESAN BERHASIL -->
    @if (session('sukses'))

        <div
            role="status"
            class="rounded-xl border
                   border-emerald-200
                   bg-emerald-50 p-4
                   text-sm text-emerald-800"
        >
            {{ session('sukses') }}
        </div>

    @endif

    <!-- PESAN KESALAHAN -->
    @if ($errors->any())

        <div
            role="alert"
            class="rounded-xl border
                   border-red-200
                   bg-red-50 p-4
                   text-sm text-red-800"
        >

            <strong>
                Proses belum berhasil:
            </strong>

            @foreach ($errors->all() as $error)

                <p class="mt-1">
                    {{ $error }}
                </p>

            @endforeach

        </div>

    @endif

    <!-- DAFTAR PENGAJUAN -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Daftar Pengajuan Terverifikasi
                </h2>

                <p class="bc-panel-desc">

                    Data berasal dari tabel
                    pengajuan_beasiswa,
                    bukan dari alternatif penelitian.

                </p>

            </div>

            <a
                href="{{ route(
                    'admin.calon-penerima.index'
                ) }}"
                class="bc-btn bc-btn--outline"
            >
                Lihat Data Alternatif
            </a>

        </div>

        <div class="bc-panel-body">

            <div
                class="bc-table-scroll"
                role="region"
                aria-label="Daftar pengajuan siap seleksi"
                tabindex="0"
            >

                <table
                    class="bc-table"
                    style="min-width:870px"
                >

                    <thead>

                        <tr>
                            <th>Pendaftar</th>
                            <th>NISN</th>
                            <th>Kode Calon Penerima</th>
                            <th>C1 · Penghasilan</th>
                            <th>C2 · Tanggungan</th>
                            <th>C3 · Akademik</th>
                            <th>C4 · Rumah</th>
                            <th>C5 · Prestasi</th>
                            <th>Tindakan</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($daftar as $item)

                            <tr>

                                <td>

                                    <p class="bc-cell-title">
                                        {{ $item->nama_lengkap }}
                                    </p>

                                    <p class="bc-cell-caption">

                                        Pengajuan #{{ $item->id }}

                                        · {{ $item->asal_sekolah ?: '-' }}

                                    </p>

                                </td>

                                <td>
                                    {{ $item->nisn }}
                                </td>

                                <td class="font-semibold">
                                    {{ $item->kode_seleksi }}
                                </td>

                                <td>

                                    Rp {{ number_format(
                                        (float) $item->penghasilan_orang_tua,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td>
                                    {{ $item->jumlah_tanggungan }}
                                </td>

                                <td>
                                    {{ $item->nilai_akademik }}
                                </td>

                                <td>
                                    {{ $item->kondisi_tempat_tinggal }}
                                </td>

                                <td>
                                    {{ $item->prestasi_non_akademik }}
                                </td>

                                <td>

                                    @if ($item->status_seleksi === 'sudah')

                                        <span
                                            class="bc-status
                                                   bc-status--terverifikasi"
                                        >
                                            Sudah masuk
                                        </span>

                                    @elseif ($item->status_seleksi === 'konflik')

                                        <span
                                            class="bc-status
                                                   bc-status--ditolak"
                                        >
                                            Konflik kode
                                        </span>

                                        <p class="bc-cell-caption">

                                            Periksa PG-{{ $item->id }}
                                            di Data Alternatif.

                                        </p>

                                    @else

                                        <form
                                            method="POST"

                                            action="{{ route(
                                                'admin.seleksi-pengajuan.simpan',
                                                ['id' => $item->id]
                                            ) }}"

                                            onsubmit="return confirm('Masukkan pengajuan ini ke alternatif WASPAS?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="bc-btn
                                                       bc-btn--primary"
                                            >
                                                Masukkan ke Seleksi
                                            </button>

                                        </form>

                                    @endif

                                    <a
                                        href="{{ route(
                                            'admin.verifikasi.index',
                                            ['detail' => $item->id]
                                        ) }}"

                                        class="mt-2 block text-xs
                                               font-semibold
                                               text-emerald-700
                                               underline"
                                    >
                                        Periksa detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="py-10 text-center
                                           text-slate-500"
                                >

                                    Belum ada pengajuan User
                                    berstatus Terverifikasi.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($daftar->hasPages())

                <div class="mt-5">
                    {{ $daftar->links() }}
                </div>

            @endif

        </div>

    </section>

    <!-- MENU LANJUTAN -->
    <div class="flex flex-wrap gap-3">

        <a
            href="{{ route('admin.calon-penerima.index') }}"
            class="bc-btn bc-btn--outline"
        >
            Data Calon Penerima
        </a>

        <a
            href="{{ route('admin.waspas.index') }}"
            class="bc-btn bc-btn--primary"
        >
            Lihat Perhitungan WASPAS
        </a>

    </div>

</main>

@endsection
