
@extends('layouts.utama')

@section('title', 'Status Pengajuan - Beasiswa Cerdas')

@section('content')

<main class="mx-auto max-w-5xl px-5 py-10">

    <p class="text-xs font-bold uppercase
              tracking-wide text-emerald-700">
        Portal Pendaftar
    </p>

    <h1 class="mt-2 text-3xl font-extrabold
               text-[#103F36]">
        Status Pengajuan
    </h1>

    <p class="mt-3 text-sm leading-7 text-slate-600">
        Pantau perkembangan pengajuan dan informasi
        pemeriksaan administrasi.
    </p>

    <section class="mt-7 rounded-2xl border
                    border-slate-100 bg-white
                    p-6 shadow-sm">

        <p class="text-sm font-semibold text-slate-500">
            Status Saat Ini
        </p>

        <span class="mt-3 inline-block rounded-full
                     px-4 py-2 text-sm font-bold
                     {{ $info['warna'] }}">

            {{ $info['nama'] }}

        </span>

        <p class="mt-4 text-sm leading-7 text-slate-600">
            {{ $info['penjelasan'] }}
        </p>

        @if ($pengajuan)

            <div class="mt-6 grid gap-4
                        border-t border-slate-100
                        pt-5 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-semibold
                              text-slate-500">
                        Nama Pendaftar
                    </p>

                    <p class="mt-1 break-words
                              text-sm font-bold">
                        {{ $pengajuan->nama_lengkap
                            ?: auth()->user()->nama_lengkap }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold
                              text-slate-500">
                        Tanggal Pengiriman
                    </p>

                    <p class="mt-1 text-sm font-bold">

                        {{ $pengajuan->diajukan_pada
                            ? date(
                                'd-m-Y H:i',
                                strtotime($pengajuan->diajukan_pada)
                              )
                            : 'Belum dikirim' }}

                    </p>
                </div>

            </div>

        @endif

    </section>

    <!-- CATATAN ADMIN -->
    @if ($pengajuan && $pengajuan->catatan_admin)

        <section class="mt-6 rounded-2xl
                        border border-amber-200
                        bg-amber-50 p-6">

            <h2 class="text-lg font-bold text-amber-900">
                Catatan dari Admin
            </h2>

            <p class="mt-3 whitespace-pre-wrap
                      break-words text-sm leading-7
                      text-amber-900">
                {{ $pengajuan->catatan_admin }}
            </p>

        </section>

    @endif

    <!-- LANGKAH SELANJUTNYA -->
    <section class="mt-6 rounded-2xl
                    border border-emerald-100
                    bg-emerald-50 p-6">

        <h2 class="font-bold text-[#103F36]">
            Langkah Selanjutnya
        </h2>

        <p class="mt-2 text-sm leading-7 text-slate-700">

            @if (in_array(
                $status,
                ['belum_mengisi', 'draf'],
                true
            ))

                Lengkapi formulir dan dokumen,
                lalu klik Kirim Pendaftaran
                ketika data sudah benar.

            @elseif ($status === 'perlu_perbaikan')

                Buka formulir untuk memperbaiki
                data atau mengganti dokumen
                sesuai catatan Admin,
                lalu kirim kembali.

            @elseif ($status === 'diajukan')

                Tunggu pemeriksaan Admin.
                Formulir tidak dapat diubah
                selama pengajuan sedang diperiksa.

            @elseif ($status === 'terverifikasi')

                Pengajuan sudah lolos pemeriksaan
                administrasi. Tunggu proses seleksi
                dan pengumuman resmi.

            @elseif ($status === 'ditolak')

                Pengajuan sudah ditolak pada
                pemeriksaan administrasi.
                Perubahan status memerlukan
                prosedur dari pengelola.

            @else

                Hubungi administrator untuk
                mengetahui status pengajuan.

            @endif

        </p>

    </section>

    <div class="mt-7 flex flex-wrap gap-3">

        <a
            href="{{ route('user.dashboard') }}"
            class="rounded-xl border
                   border-emerald-700 px-5 py-3
                   text-sm font-bold text-emerald-800"
        >
            Kembali ke Dashboard
        </a>

        @if (in_array(
            $status,
            ['belum_mengisi', 'draf', 'perlu_perbaikan'],
            true
        ))

            <a
                href="{{ route('user.pengajuan') }}"
                class="rounded-xl bg-[#116759]
                       px-5 py-3 text-sm
                       font-bold text-white"
            >
                Buka Formulir
            </a>

        @endif

    </div>

    <p class="mt-6 text-xs text-slate-500">
        Status Terverifikasi bukan keputusan
        akhir penerima beasiswa.
    </p>

</main>

@endsection
