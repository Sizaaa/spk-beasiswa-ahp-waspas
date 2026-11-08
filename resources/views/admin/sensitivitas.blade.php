
@extends('layouts.admin')

@section(
    'title',
    'Analisis Sensitivitas WASPAS - Beasiswa Cerdas'
)

@section('judul-admin', 'Analisis Sensitivitas')

@section(
    'deskripsi-admin',
    'Menguji kestabilan peringkat WASPAS pada λ = 0, 0,5, dan 1.'
)

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <!-- =====================================
         PENJELASAN ANALISIS
    ===================================== -->

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Uji Sensitivitas Parameter λ
                </h2>

                <p class="bc-panel-desc">

                    λ = 0 menggunakan WPM;

                    λ = 0,5 menggabungkan
                    WSM dan WPM;

                    λ = 1 menggunakan WSM.

                </p>

            </div>

            <a
                href="{{ route('admin.hasil.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Kembali ke Hasil Seleksi
            </a>

        </div>

        <div class="bc-panel-body space-y-4">

            <!-- PILIH LINGKUP -->
            <form
                method="GET"
                action="{{ route(
                    'admin.sensitivitas.index'
                ) }}"
                class="flex flex-wrap items-end gap-3"
            >

                <div>

                    <label
                        for="lingkup"
                        class="mb-2 block text-sm
                               font-semibold text-slate-700"
                    >
                        Lingkup Perhitungan
                    </label>

                    <select
                        id="lingkup"
                        name="lingkup"
                        class="bc-form-control"
                    >

                        <option
                            value="artikel"
                            @selected(
                                $lingkup === 'artikel'
                            )
                        >
                            Hanya A1–A5 (acuan artikel)
                        </option>

                        <option
                            value="semua"
                            @selected(
                                $lingkup === 'semua'
                            )
                        >
                            Seluruh alternatif,
                            termasuk PG-ID
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="bc-btn bc-btn--primary"
                >
                    Tampilkan Analisis
                </button>

            </form>

            <!-- SUMBER PERHITUNGAN -->
            <div class="bc-notice">

                <strong>
                    Sumber Bobot:
                </strong>

                {{ $sumber }}

                <span class="mt-1 block">

                    Jumlah data:

                    {{ count($alternatif) }}
                    alternatif.

                </span>

                <span class="mt-1 block">

                    Data dibaca ulang dari penyimpanan data.

                    Analisis ini tidak mengubah
                    bobot, data, maupun status
                    penerimaan.

                </span>

            </div>

            @if ($lingkup === 'artikel')

                <p class="text-xs leading-6
                          text-slate-600">

                    Angka dihitung dari A1–A5
                    yang tersimpan dalam sistem.

                    Agar hasilnya sama dengan
                    Tabel 9 artikel, nilai C1–C5
                    harus Sesuai dengan Artikel (Tabel 6).

                    Periksa melalui

                    <a
                        href="{{ route(
                            'admin.validasi-penelitian.index'
                        ) }}"
                        class="font-bold text-emerald-700
                               underline"
                    >
                        Validasi Penelitian
                    </a>.

                </p>

            @endif

        </div>

    </section>

    <!-- =====================================
         HASIL ATAU PESAN KESALAHAN
    ===================================== -->

    @if ($pesan)

        <div
            role="alert"
            class="rounded-xl border border-red-200
                   bg-red-50 p-4 text-sm text-red-800"
        >

            Perhitungan tidak dapat dijalankan:

            {{ $pesan }}

        </div>

    @elseif ($hasil['jumlah'] === 0)

        <div class="bc-notice">
            Belum ada alternatif dalam
            lingkup yang dipilih.
        </div>

    @else

        <!-- =================================
             TABEL SENSITIVITAS
        ================================== -->

        <section class="bc-panel">

            <div class="bc-panel-header">

                <div>

                    <h2 class="bc-panel-title">
                        Perbandingan Qi dan Peringkat
                    </h2>

                    <p class="bc-panel-desc">

                        Dari nilai S dan P yang sama,
                        Qi dihitung ulang untuk
                        tiga nilai λ.

                    </p>

                </div>

                <span
                    class="bc-status
                           {{ $hasil['stabil']
                              ? 'bc-status--terverifikasi'
                              : 'bc-status--perlu_perbaikan' }}"
                >

                    {{ $hasil['stabil']
                        ? 'Seluruh peringkat stabil'
                        : 'Ada perubahan peringkat' }}

                </span>

            </div>

            <div class="bc-panel-body">

                <div
                    class="bc-table-scroll"
                    role="region"
                    aria-label="Hasil analisis sensitivitas WASPAS"
                    tabindex="0"
                >

                    <table
                        class="bc-table"
                        style="min-width:940px"
                    >

                        <thead>

                            <tr>

                                <th>Kode</th>

                                <th>Nama Calon Penerima</th>

                                <th>Qi λ=0</th>

                                <th>Peringkat λ=0</th>

                                <th>Qi λ=0,5</th>

                                <th>Peringkat λ=0,5</th>

                                <th>Qi λ=1</th>

                                <th>Peringkat λ=1</th>

                                <th>Stabilitas</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach (
                                $hasil['baris'] as $baris
                            )

                                <tr>

                                    <td class="font-bold">

                                        {{ $baris['kode'] }}

                                    </td>

                                    <td>

                                        {{ $baris['nama'] }}

                                    </td>

                                    @foreach (
                                        ['0', '05', '1'] as $lambda
                                    )

                                        <!-- NILAI QI -->
                                        <td>

                                            {{ number_format(
                                                $baris['nilai'][$lambda]['nilai'],
                                                4,
                                                ',',
                                                '.'
                                            ) }}

                                        </td>

                                        <!-- PERINGKAT -->
                                        <td>

                                            #{{ $baris['nilai'][$lambda]['peringkat'] }}

                                        </td>

                                    @endforeach

                                    <!-- STABILITAS -->
                                    <td>

                                        <span
                                            class="bc-status
                                                   {{ $baris['stabil']
                                                      ? 'bc-status--terverifikasi'
                                                      : 'bc-status--perlu_perbaikan' }}"
                                        >

                                            {{ $baris['stabil']
                                                ? 'Stabil'
                                                : 'Berubah' }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <!-- KESIMPULAN -->
                <div class="bc-notice mt-5">

                    <strong>
                        Kesimpulan untuk lingkup ini:
                    </strong>

                    @if ($hasil['stabil'])

                        Peringkat seluruh alternatif
                        tetap sama ketika λ berubah
                        dari 0 menjadi 0,5 dan 1.

                    @else

                        Ada alternatif yang berpindah
                        peringkat ketika λ berubah.

                        Ini tidak otomatis berarti
                        rumus salah. Perubahan bisa
                        disebabkan perbedaan nilai S
                        dan P antarsiswa.

                    @endif

                    Nilai Akhir (Qi) bukan persentase
                    kemungkinan menerima beasiswa.

                </div>

            </div>

        </section>

    @endif

</main>

@endsection
