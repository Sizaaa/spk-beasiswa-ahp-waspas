
@extends('layouts.admin')

@section('title', 'Riwayat Perhitungan - Beasiswa Cerdas')

@section('judul-admin', 'Riwayat Perhitungan')

@section(
    'deskripsi-admin',
    'Arsip hasil WASPAS beserta data, bobot, dan waktu perhitungan.'
)

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- SIMPAN HASIL AKTIF -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>
                <h2 class="bc-panel-title">
                    Simpan Hasil Perhitungan Saat Ini
                </h2>

                <p class="bc-panel-desc">
                    Menyalin kondisi data dan hasil WASPAS
                    ke arsip yang tidak berubah ketika
                    data aktif diperbarui.
                </p>
            </div>

            <a
                class="bc-btn bc-btn--outline"
                href="{{ route('admin.hasil.index') }}"
            >
                Lihat Hasil Aktif
            </a>

        </div>

        <div class="bc-panel-body space-y-4">

            @if ($errors->any())

                <div
                    role="alert"
                    class="rounded-xl bg-red-50 p-4
                           text-sm text-red-700"
                >
                    @foreach ($errors->all() as $pesan)
                        <p>{{ $pesan }}</p>
                    @endforeach
                </div>

            @endif

            @if (session('sukses'))

                <p
                    role="status"
                    class="rounded-xl bg-emerald-50 p-4
                           text-sm text-emerald-700"
                >
                    {{ session('sukses') }}
                </p>

            @endif

            <!-- SUMBER HASIL YANG DISIMPAN -->
            <div class="bc-notice">

                <strong>Lingkup saat ini:</strong>

                {{
                    \App\Services\LingkupSeleksi::PILIHAN[
                        session('spk_lingkup', 'artikel')
                    ] ?? 'Penelitian A1–A5'
                }}.

                <strong>Sumber Bobot Penilaian:</strong>

                {{
                    session('spk_bobot_mode') === 'simulasi'
                        ? 'Simulasi AHP (jika konsisten)'
                        : 'Artikel'
                }}.

                <p class="mt-2 text-sm">
                    Arsip dibuat hanya ketika Admin
                    menekan Simpan.

                    Riwayat tidak menetapkan siapa
                    penerima beasiswa.
                </p>

            </div>

            <!-- TOMBOL SIMPAN -->
            <form
                method="POST"
                action="{{ route('admin.riwayat.simpan') }}"
                onsubmit="return confirm('Simpan salinan data dan hasil perhitungan saat ini?');"
            >

                @csrf

                <button
                    type="submit"
                    class="bc-btn bc-btn--primary"
                >
                    Simpan Hasil Saat Ini
                </button>

            </form>

        </div>

    </section>

    <!-- DAFTAR RIWAYAT -->
    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>
                <h2 class="bc-panel-title">
                    Daftar Riwayat Tersimpan
                </h2>

                <p class="bc-panel-desc">
                    Setiap baris merupakan hasil
                    yang pernah disimpan oleh Admin.
                </p>
            </div>

        </div>

        <div class="bc-panel-body">

            <div
                class="bc-table-scroll"
                role="region"
                aria-label="Daftar riwayat seleksi"
                tabindex="0"
            >

                <table
                    class="bc-table"
                    style="min-width:770px"
                >

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Waktu</th>
                            <th>Disimpan Oleh</th>
                            <th>Lingkup</th>
                            <th>Bobot</th>
                            <th>Alternatif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($riwayat as $item)

                            <tr>

                                <td class="font-bold">
                                    #{{ $item->id }}
                                </td>

                                <td>
                                    {{
                                        \Illuminate\Support\Carbon::parse(
                                            $item->created_at
                                        )->format('d/m/Y H:i')
                                    }}
                                </td>

                                <td>
                                    {{ $item->admin_nama }}
                                </td>

                                <td>
                                    {{
                                        \App\Services\LingkupSeleksi::PILIHAN[
                                            $item->lingkup
                                        ] ?? $item->lingkup
                                    }}
                                </td>

                                <td>
                                    {{
                                        $item->mode_bobot === 'simulasi'
                                            ? 'Simulasi AHP'
                                            : 'Artikel'
                                    }}
                                </td>

                                <td>
                                    {{ $item->jumlah_alternatif }}
                                </td>

                                <td>
                                    <a
                                        class="bc-btn bc-btn--outline"
                                        href="{{ route(
                                            'admin.riwayat.show',
                                            $item->id
                                        ) }}"
                                    >
                                        Lihat Arsip
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="py-8 text-center
                                           text-slate-500"
                                >
                                    Belum ada riwayat
                                    yang disimpan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($riwayat->hasPages())

                <div class="mt-5">
                    {{ $riwayat->links() }}
                </div>

            @endif

        </div>

    </section>

</main>

@endsection
