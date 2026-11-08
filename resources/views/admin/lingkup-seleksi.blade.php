
@extends('layouts.admin')

@section(
    'title',
    'Lingkup Perhitungan - Beasiswa Cerdas'
)

@section(
    'judul-admin',
    'Lingkup Perhitungan'
)

@section(
    'deskripsi-admin',
    'Tentukan kelompok alternatif yang dinilai oleh AHP–WASPAS.'
)

@section('admin-content')

<main class="bc-admin-main space-y-6">

    <section class="bc-panel">

        <div class="bc-panel-header">

            <div>

                <h2 class="bc-panel-title">
                    Pilih Data untuk Perhitungan
                </h2>

                <p class="bc-panel-desc">

                    Pilihan berlaku pada WASPAS,
                    Hasil Seleksi, dan Laporan
                    dalam sesi Admin ini.

                </p>

            </div>

            <a
                href="{{ route('admin.hasil.index') }}"
                class="bc-btn bc-btn--outline"
            >
                Kembali ke Hasil
            </a>

        </div>

        <div class="bc-panel-body space-y-5">

            @if ($errors->any())

                <div
                    role="alert"
                    class="rounded-xl bg-red-50
                           p-4 text-sm text-red-800"
                >

                    {{ $errors->first() }}

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('admin.lingkup.simpan') }}"
                class="space-y-4"
            >

                @csrf

                <!-- ============================
                     PILIHAN PENELITIAN
                ============================= -->

                <label
                    class="flex cursor-pointer
                           items-start gap-3
                           rounded-xl border
                           border-slate-200 p-4"
                >

                    <input
                        type="radio"
                        name="lingkup"
                        value="artikel"
                        class="mt-1"
                        required

                        @checked(
                            old('lingkup', $aktif)
                            === 'artikel'
                        )
                    >

                    <span class="block">

                        <strong
                            class="block text-[#103F36]"
                        >
                            Penelitian A1–A5
                        </strong>

                        <span
                            class="mt-1 block text-sm
                                   leading-6 text-slate-600"
                        >

                            Hanya lima data penelitian
                            untuk mencocokkan hasil
                            dengan artikel.

                            Tidak mencampurkan A6
                            atau pengajuan siswa.

                        </span>

                    </span>

                </label>

                <!-- ============================
                     PILIHAN PENDAFTAR
                ============================= -->

                <label
                    class="flex cursor-pointer
                           items-start gap-3
                           rounded-xl border
                           border-slate-200 p-4"
                >

                    <input
                        type="radio"
                        name="lingkup"
                        value="pendaftar"
                        class="mt-1"
                        required

                        @checked(
                            old('lingkup', $aktif)
                            === 'pendaftar'
                        )
                    >

                    <span class="block">

                        <strong
                            class="block text-[#103F36]"
                        >
                            Pendaftar Terverifikasi
                        </strong>

                        <span
                            class="mt-1 block text-sm
                                   leading-6 text-slate-600"
                        >

                            Hanya alternatif PG-ID
                            yang berasal dari pengajuan
                            User berstatus Terverifikasi
                            dan datanya masih cocok
                            dengan pengajuan asal.

                            Gunakan untuk pemeringkatan
                            siswa yang benar-benar
                            mendaftar.

                        </span>

                    </span>

                </label>

                <!-- ============================
                     PILIHAN GABUNGAN
                ============================= -->

                <label
                    class="flex cursor-pointer
                           items-start gap-3
                           rounded-xl border
                           border-slate-200 p-4"
                >

                    <input
                        type="radio"
                        name="lingkup"
                        value="gabungan"
                        class="mt-1"
                        required

                        @checked(
                            old('lingkup', $aktif)
                            === 'gabungan'
                        )
                    >

                    <span class="block">

                        <strong
                            class="block text-[#103F36]"
                        >
                            Gabungan untuk Demonstrasi
                        </strong>

                        <span
                            class="mt-1 block text-sm
                                   leading-6 text-slate-600"
                        >

                            Semua baris calon_penerima,
                            termasuk A1–A5,
                            alternatif manual,
                            dan PG-ID.

                            Hanya untuk contoh
                            perhitungan, bukan
                            keputusan beasiswa nyata
                            karena data simulasi
                            dan pendaftar tercampur.

                        </span>

                    </span>

                </label>

                <div class="bc-notice">

                    <strong>Catatan:</strong>

                    Mengganti lingkup tidak menghapus
                    atau memperbarui data.

                    Namun, Nilai setelah penyesuaian (normalisasi) dan
                    peringkat dapat berubah karena
                    kelompok alternatif yang
                    dibandingkan berbeda.

                    Bobot artikel atau simulasi AHP
                    tetap mengikuti pilihan
                    yang sudah aktif.

                </div>

                <button
                    type="submit"
                    class="bc-btn bc-btn--primary"
                >

                    Simpan Lingkup & Lihat Hasil

                </button>

            </form>

        </div>

    </section>

</main>

@endsection
