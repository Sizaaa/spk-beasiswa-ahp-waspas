
@extends('layouts.utama')

@section('title', 'Panduan Pengajuan - Beasiswa Cerdas')

@section('content')

<main class="mx-auto max-w-5xl px-5 py-10">

    <p class="text-xs font-bold uppercase
              tracking-wide text-emerald-700">
        Portal Pendaftar
    </p>

    <h1 class="mt-2 text-3xl font-extrabold
               text-[#103F36]">
        Panduan Pengajuan Beasiswa
    </h1>

    <p class="mt-3 text-sm leading-7 text-slate-600">
        Ikuti langkah berikut untuk melengkapi
        pengajuan dan menghindari kesalahan pengisian.
    </p>

    @php
        $tahapanPanduan = [
            [
                '01',
                'Periksa akun',
                'Pastikan akun yang digunakan adalah akun User milikmu. Informasi nama, username, dan email dapat dilihat pada Profil Akun.',
            ],
            [
                '02',
                'Isi data pendaftar',
                'Lengkapi nama, NISN 10 digit, asal sekolah, kelas, nomor HP, serta alamat lengkap.',
            ],
            [
                '03',
                'Isi kriteria C1–C5',
                'Masukkan pekerjaan dan penghasilan orang tua, jumlah tanggungan, nilai akademik 0–100, kondisi tempat tinggal 1–5, dan prestasi non-akademik 1–5.',
            ],
            [
                '04',
                'Unggah dokumen',
                'Unggah Kartu Keluarga, Bukti Penghasilan/SKTM, dan Rapor Terakhir. Sertifikat Prestasi bersifat opsional. Format PDF/JPG/PNG, maksimal 2 MB per berkas.',
            ],
            [
                '05',
                'Simpan atau kirim',
                'Gunakan Simpan Draf jika belum lengkap. Setelah seluruh data benar, centang pernyataan dan klik Kirim Pendaftaran.',
            ],
            [
                '06',
                'Pantau dan perbaiki',
                'Buka Status Pengajuan. Jika Admin meminta perbaikan, baca catatannya, perbaiki data atau dokumen, lalu kirim ulang.',
            ],
        ];
    @endphp

    <section class="mt-7 grid gap-4 md:grid-cols-2">

        @foreach ($tahapanPanduan as $item)

            <article class="rounded-2xl border
                            border-slate-100 bg-white
                            p-6 shadow-sm">

                <span class="text-lg font-extrabold
                             text-emerald-700">
                    {{ $item[0] }}
                </span>

                <h2 class="mt-2 text-lg font-bold
                           text-[#103F36]">
                    {{ $item[1] }}
                </h2>

                <p class="mt-3 text-sm leading-7
                          text-slate-600">
                    {{ $item[2] }}
                </p>

            </article>

        @endforeach

    </section>

    <section class="mt-6 rounded-2xl border
                    border-amber-100
                    bg-amber-50 p-6">

        <h2 class="font-bold text-amber-900">
            Catatan Penting
        </h2>

        <p class="mt-2 text-sm leading-7
                  text-amber-900">

            Status Terverifikasi menunjukkan
            bahwa pengajuan lolos pemeriksaan
            administrasi, bukan berarti kamu
            otomatis mendapatkan beasiswa.

            Penilaian AHP–WASPAS dan penetapan
            penerima merupakan tahap terpisah.

        </p>

    </section>

    <div class="mt-7 flex flex-wrap gap-3">

        <a
            href="{{ route('user.pengajuan') }}"
            class="rounded-xl bg-[#116759]
                   px-5 py-3 text-sm
                   font-bold text-white"
        >
            Buka Formulir Pengajuan
        </a>

        <a
            href="{{ route('user.status') }}"
            class="rounded-xl border
                   border-emerald-700 px-5 py-3
                   text-sm font-bold text-emerald-800"
        >
            Lihat Status Pengajuan
        </a>

    </div>

</main>

@endsection
