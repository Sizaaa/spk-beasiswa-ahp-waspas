
@extends('layouts.utama')

@section('title', 'Dashboard User - Beasiswa Cerdas')

@section('content')

@php
    $namaUser = $akun->nama_lengkap
        ?: $akun->username;
@endphp

<!-- ==========================================
     DASHBOARD USER BEASISWA CERDAS
========================================== -->

<main class="mx-auto max-w-6xl px-5 py-9">

    <!-- ======================================
         SAMBUTAN USER
    ======================================= -->

    <section
        class="rounded-3xl bg-[#103F36]
               p-8 text-white"
    >

        <div
            class="flex flex-wrap items-center
                   justify-between gap-6"
        >

            <div class="min-w-0 flex-1">

                <p class="text-sm text-emerald-200">
                    Selamat datang,
                </p>

                <h1
                    class="mt-2 break-words
                           text-3xl font-extrabold"
                >
                    {{ $namaUser }}
                </h1>

                <p
                    class="mt-4 text-sm leading-7
                           text-emerald-100"
                >
                    Pantau pengajuan, proses seleksi,
                    dan keputusan akhir beasiswa
                    milik akunmu di sini.
                </p>

                @if (
                    $pengajuan &&
                    $pengajuan->nisn
                )

                    <p class="mt-3 text-xs text-emerald-200">
                        NISN: {{ $pengajuan->nisn }}
                    </p>

                @endif

            </div>

            <!-- STATUS ADMINISTRASI -->

            <div
                class="w-full rounded-2xl
                       border border-white/20
                       bg-white/10 p-5 sm:w-72"
            >

                <p
                    class="text-xs font-semibold
                           text-emerald-200"
                >
                    STATUS ADMINISTRASI
                </p>

                <h2
                    class="mt-2 text-xl font-extrabold"
                >
                    {{ $infoStatus['judul'] }}
                </h2>

                <p
                    class="mt-3 text-sm leading-6
                           text-emerald-100"
                >
                    {{ $infoStatus['keterangan'] }}
                </p>

                @if ($sudahDiumumkan)

                    <div
                        class="mt-4 rounded-xl
                               bg-white/15 p-3"
                    >

                        <p
                            class="text-xs font-semibold
                                   text-emerald-200"
                        >
                            HASIL AKHIR SELEKSI
                        </p>

                        <p
                            class="mt-1 text-lg
                                   font-extrabold"
                        >
                            {{ $infoKeputusan['judul'] }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </section>

    <!-- ======================================
         RINGKASAN PENGAJUAN
    ======================================= -->

    <section
        class="mt-7 grid gap-5 md:grid-cols-3"
    >

        <!-- STATUS ADMINISTRASI -->

        <div
            class="rounded-2xl border
                   border-slate-100 bg-white
                   p-6 shadow-sm"
        >

            <p class="text-sm text-slate-500">
                Status Saat Ini
            </p>

            <span
                class="mt-4 inline-block
                       rounded-full px-4 py-2
                       text-sm font-bold
                       {{ $infoStatus['warna'] }}"
            >
                {{ $infoStatus['judul'] }}
            </span>

            <p
                class="mt-4 text-xs leading-6
                       text-slate-500"
            >
                Status administrasi sesuai data
                pengajuan yang tersimpan.
            </p>

        </div>

        <!-- DOKUMEN WAJIB -->

        <div
            class="rounded-2xl border
                   border-slate-100 bg-white
                   p-6 shadow-sm"
        >

            <p class="text-sm text-slate-500">
                Dokumen Wajib Tersimpan
            </p>

            <h3
                class="mt-4 text-3xl font-extrabold
                       text-[#103F36]"
            >
                {{ $jumlahTerunggah }}
                /
                {{ $jumlahWajib }}
            </h3>

            <p
                class="mt-4 text-xs leading-6
                       text-slate-500"
            >
                Berkas tersimpan belum tentu
                lolos pemeriksaan Admin.
            </p>

        </div>

        <!-- TANGGAL PENGIRIMAN -->

        <div
            class="rounded-2xl border
                   border-slate-100 bg-white
                   p-6 shadow-sm"
        >

            <p class="text-sm text-slate-500">
                Tanggal Pengiriman
            </p>

            <h3
                class="mt-4 text-lg font-bold
                       text-[#103F36]"
            >

                @if (
                    $pengajuan &&
                    $pengajuan->diajukan_pada
                )

                    {{ date(
                        'd-m-Y H:i',
                        strtotime(
                            $pengajuan->diajukan_pada
                        )
                    ) }}

                @else

                    Belum dikirim

                @endif

            </h3>

            <p
                class="mt-4 text-xs leading-6
                       text-slate-500"
            >
                Dicatat saat pendaftaran dikirim.
            </p>

        </div>

    </section>

    <!-- ======================================
         TAHAPAN SELEKSI DAN PENGAJUAN SAYA
    ======================================= -->

    <!--
        PERBAIKAN TATA LETAK:

        items-start membuat kedua kartu
        mengikuti tinggi kontennya masing-masing.

        Dengan demikian, tidak ada ruang kosong
        besar akibat tinggi kartu yang dipaksakan.
    -->

    <section
        class="mt-8 grid items-start
               gap-6 lg:grid-cols-2"
    >

        <!-- ==================================
             KARTU KIRI: TAHAPAN SELEKSI
        =================================== -->

        <div
            class="self-start rounded-2xl
                   border border-slate-100
                   bg-white p-7 shadow-sm"
        >

            <h2
                class="text-xl font-extrabold
                       text-[#103F36]"
            >
                Tahapan Seleksi
            </h2>

            <p
                class="mt-2 text-sm leading-7
                       text-slate-500"
            >
                Perkembangan seleksi mengikuti
                pemeriksaan administrasi dan
                keputusan akhir yang diumumkan Admin.
            </p>

            <div class="mt-7 space-y-6">

                @foreach (
                    $tahapan as $nomor => $tahap
                )

                    @php
                        $selesaiTahap = (bool) (
                            $tahap['selesai'] ?? false
                        );

                        $tahapHasil = (bool) (
                            $tahap['hasil'] ?? false
                        );

                        $warnaLingkaran = $selesaiTahap
                            ? 'bg-[#116759] text-white'
                            : 'bg-slate-100 text-slate-600';

                        $warnaStatus = $selesaiTahap
                            ? 'text-emerald-700'
                            : 'text-slate-500';

                        if ($tahapHasil) {

                            $warnaStatus = match (
                                $keputusanFinal
                            ) {
                                'diterima' =>
                                    'text-emerald-700',

                                'cadangan' =>
                                    'text-amber-700',

                                'tidak_diterima' =>
                                    'text-rose-700',

                                default =>
                                    'text-slate-500',
                            };

                            $warnaLingkaran = match (
                                $keputusanFinal
                            ) {
                                'diterima' =>
                                    'bg-emerald-700 text-white',

                                'cadangan' =>
                                    'bg-amber-500 text-white',

                                'tidak_diterima' =>
                                    'bg-rose-600 text-white',

                                default =>
                                    'bg-slate-100 text-slate-600',
                            };
                        }
                    @endphp

                    <div
                        class="flex items-start gap-4"
                    >

                        <!-- INDIKATOR TAHAP -->

                        <div
                            class="flex h-10 w-10 shrink-0
                                   items-center justify-center
                                   rounded-full font-bold
                                   {{ $warnaLingkaran }}"
                            aria-hidden="true"
                        >
                            {{ $selesaiTahap
                                ? '✓'
                                : $nomor + 1 }}
                        </div>

                        <!-- INFORMASI TAHAP -->

                        <div class="min-w-0 flex-1">

                            <p
                                class="font-semibold
                                       text-slate-800"
                            >
                                {{ $tahap['nama'] }}
                            </p>

                            <p
                                class="mt-1 text-sm
                                       font-medium
                                       {{ $warnaStatus }}"
                            >
                                {{ $tahap['status'] }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

            <!--
                KEPUTUSAN AKHIR TIDAK DIULANG
                LAGI DI KARTU INI.

                Hasil akhir tetap terlihat
                pada langkah Pengumuman penerima.

                Rincian keputusan ditempatkan
                pada kartu Pengajuan Saya
                di sebelah kanan.
            -->

            <!-- BELUM ADA PENGUMUMAN -->

            @if (
                !$sudahDiumumkan &&
                $status === 'terverifikasi'
            )

                <div
                    class="mt-7 rounded-xl
                           border border-emerald-200
                           bg-emerald-50 p-4"
                >

                    <p
                        class="text-sm font-bold
                               text-emerald-900"
                    >
                        Administrasi Sudah Terverifikasi
                    </p>

                    <p
                        class="mt-2 text-sm leading-7
                               text-emerald-800"
                    >
                        Pengajuan sudah lolos pemeriksaan
                        administrasi.

                        Selanjutnya, tunggu pengumuman
                        keputusan akhir dari Admin.
                    </p>

                </div>

            @endif

        </div>

        <!-- ==================================
             KARTU KANAN: PENGAJUAN SAYA
        =================================== -->

        <div
            class="self-start rounded-2xl
                   border border-slate-100
                   bg-white p-7 shadow-sm"
        >

            <h2
                class="text-xl font-extrabold
                       text-[#103F36]"
            >
                Pengajuan Saya
            </h2>

            <p
                class="mt-4 text-sm leading-7
                       text-slate-600"
            >
                {{ $infoStatus['keterangan'] }}
            </p>

            <!-- INFORMASI AKUN -->

            <div
                class="mt-6 space-y-3
                       rounded-xl bg-slate-50
                       p-5 text-sm"
            >

                <div>

                    <p class="text-xs text-slate-500">
                        Username
                    </p>

                    <p class="mt-1 font-bold">
                        {{ $akun->username }}
                    </p>

                </div>

                <div>

                    <p class="text-xs text-slate-500">
                        Email
                    </p>

                    <p
                        class="mt-1 break-words font-bold"
                    >
                        {{ $akun->email
                            ?: 'Belum tersedia' }}
                    </p>

                </div>

                @if ($pengajuan)

                    <div>

                        <p class="text-xs text-slate-500">
                            Asal Sekolah
                        </p>

                        <p class="mt-1 font-bold">
                            {{ $pengajuan->asal_sekolah
                                ?: '-' }}
                        </p>

                    </div>

                @endif

            </div>

            <!-- ==================================
                 KEPUTUSAN AKHIR BEASISWA
                 CUKUP SATU KARTU DI SINI
            =================================== -->

            @if ($sudahDiumumkan)

                <div
                    class="mt-6 rounded-xl border
                           p-5
                           {{ $infoKeputusan['warnaPanel'] }}"
                >

                    <p
                        class="text-xs font-semibold
                               uppercase tracking-wide"
                    >
                        Keputusan Akhir Beasiswa
                    </p>

                    <div
                        class="mt-3 flex flex-wrap
                               items-center gap-3"
                    >

                        <div
                            class="flex h-11 w-11
                                   shrink-0 items-center
                                   justify-center rounded-full
                                   bg-white/70 text-xl
                                   font-extrabold"
                            aria-hidden="true"
                        >
                            @if ($keputusanFinal === 'diterima')
                                ✓
                            @elseif ($keputusanFinal === 'cadangan')
                                ◷
                            @else
                                !
                            @endif
                        </div>

                        <h3
                            class="text-xl font-extrabold"
                        >
                            {{ $infoKeputusan['judul'] }}
                        </h3>

                    </div>

                    <p
                        class="mt-4 text-sm leading-7"
                    >
                        {{ $infoKeputusan['keterangan'] }}
                    </p>

                    <div
                        class="mt-5 border-t
                               border-current/15 pt-4"
                    >

                        <a
                            href="{{ route('user.status') }}"
                            class="inline-flex items-center
                                   justify-center rounded-xl
                                   border border-current/40
                                   bg-white/70 px-4 py-2.5
                                   text-sm font-bold
                                   transition hover:bg-white"
                        >
                            Lihat Status Pengajuan
                        </a>

                    </div>

                </div>

            @endif

            <!-- CATATAN DARI ADMIN -->

            @if (
                $pengajuan &&
                $pengajuan->catatan_admin
            )

                <div
                    class="mt-6 rounded-xl border
                           border-amber-200
                           bg-amber-50 p-5"
                >

                    <p
                        class="font-bold text-amber-900"
                    >
                        Catatan Admin
                    </p>

                    <p
                        class="mt-3 whitespace-pre-wrap
                               text-sm leading-7
                               text-amber-900"
                    >
                        {{ $pengajuan->catatan_admin }}
                    </p>

                </div>

            @elseif ($status === 'diajukan')

                <p
                    class="mt-6 text-xs text-slate-500"
                >
                    Belum ada catatan pemeriksaan
                    dari Admin.
                </p>

            @endif

        </div>

    </section>

    <!-- ======================================
         BERKAS PENGAJUAN
         TIDAK DIUBAH
    ======================================= -->

    <section
        id="berkas"
        class="mt-8 rounded-2xl
               border border-slate-100
               bg-white p-7 shadow-sm"
    >

        <div
            class="flex flex-wrap items-center
                   justify-between gap-3"
        >

            <h2
                class="text-xl font-extrabold
                       text-[#103F36]"
            >
                Berkas Pengajuan
            </h2>

            <p class="text-xs text-slate-500">
                Status penyimpanan dokumen
            </p>

        </div>

        <div
            class="mt-6 grid gap-4 md:grid-cols-2"
        >

            @foreach (
                $dokumen as $jenis => $item
            )

                <div
                    class="flex flex-wrap items-center
                           justify-between gap-4
                           rounded-xl bg-slate-50 p-5"
                >

                    <div>

                        <p class="text-sm font-bold">
                            {{ $item['nama'] }}
                        </p>

                        @if ($item['ada'])

                            <p
                                class="mt-2 text-xs
                                       text-emerald-700"
                            >
                                ✓ Tersimpan
                            </p>

                        @else

                            <p
                                class="mt-2 text-xs
                                       text-slate-500"
                            >
                                Belum ada berkas
                            </p>

                        @endif

                        <p
                            class="mt-1 text-xs
                                   text-slate-500"
                        >
                            {{ $item['wajib']
                                ? 'Dokumen wajib'
                                : 'Dokumen opsional' }}
                        </p>

                    </div>

                    <!-- UNDUH BERKAS -->

                    @if ($item['ada'])

                        <a
                            href="{{ route(
                                'user.berkas',
                                ['jenis' => $jenis]
                            ) }}"
                            class="text-sm font-bold
                                   text-emerald-700 underline"
                        >
                            Unduh
                        </a>

                    @endif

                </div>

            @endforeach

        </div>

        <p
            class="mt-5 text-xs leading-6
                   text-slate-500"
        >
            Dokumen yang tersedia dapat diunduh
            menggunakan sesi Laravel milikmu.

            Dokumen pengguna lain tidak dapat
            diakses melalui halaman ini.
        </p>

    </section>

    <!-- ======================================
         PENGUMUMAN HASIL SELEKSI
         TIDAK DIUBAH
    ======================================= -->

    <section
        id="pengumuman"
        class="mt-8 rounded-2xl
               border border-emerald-100
               bg-[#E8F5F0] p-7"
    >

        <h2
            class="text-xl font-extrabold
                   text-[#103F36]"
        >
            Pengumuman Hasil Seleksi
        </h2>

        <!-- HASIL SUDAH DIUMUMKAN -->

        @if ($sudahDiumumkan)

            <div
                class="mt-5 rounded-xl border p-5
                       {{ $infoKeputusan['warnaPanel'] }}"
            >

                <p
                    class="text-xs font-bold
                           uppercase tracking-wide"
                >
                    Keputusan Akhir Resmi
                </p>

                <h3
                    class="mt-2 text-2xl
                           font-extrabold"
                >
                    {{ $infoKeputusan['judul'] }}
                </h3>

                <p
                    class="mt-3 text-sm leading-7"
                >
                    {{ $infoKeputusan['keterangan'] }}
                </p>

            </div>

            <p
                class="mt-4 text-sm leading-7
                       text-slate-600"
            >
                Keputusan ini berasal dari
                hasil seleksi yang telah
                diumumkan oleh Admin.

                Status administrasi

                <strong>
                    {{ $infoStatus['judul'] }}
                </strong>

                tetap terpisah dari keputusan
                akhir penerimaan beasiswa.
            </p>

            <a
                href="{{ route('user.status') }}"
                class="mt-5 inline-flex rounded-xl
                       bg-[#116759] px-5 py-3
                       text-sm font-bold text-white"
            >
                Lihat Status Pengajuan
            </a>

        <!-- HASIL BELUM DIUMUMKAN -->

        @else

            <p
                class="mt-4 text-sm leading-7
                       text-slate-600"
            >
                Hasil akhir seleksi beasiswa
                belum diumumkan untuk pengajuanmu.

                Status

                <strong>
                    {{ $infoStatus['judul'] }}
                </strong>

                bukan keputusan akhir penerimaan.

                Pengumuman akan tersedia setelah
                Admin menetapkan dan memublikasikan
                hasil seleksi.
            </p>

            <div
                class="mt-5 rounded-xl
                       border border-slate-200
                       bg-white/80 p-4"
            >

                <p
                    class="text-sm font-bold
                           text-[#103F36]"
                >
                    Status Pengumuman:
                    Menunggu Pengumuman
                </p>

                <p
                    class="mt-2 text-xs leading-6
                           text-slate-600"
                >
                    Kamu dapat membuka kembali
                    Dashboard atau halaman
                    Status Pengajuan untuk
                    melihat perkembangan terbaru.
                </p>

            </div>

        @endif

    </section>

</main>

@endsection
