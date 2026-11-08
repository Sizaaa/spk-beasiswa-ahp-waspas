
@extends('layouts.utama')

@section(
    'title',
    'Lupa Kata Sandi - Beasiswa Cerdas'
)

@section('content')

<!-- ==========================================
     NAVBAR PEMULIHAN AKUN
========================================== -->

<header
    class="border-b border-emerald-100
           bg-white"
>
    <div
        class="mx-auto flex max-w-6xl
               flex-wrap items-center
               justify-between gap-4
               px-5 py-5"
    >

        <a
            href="{{ route('beranda') }}"
            class="text-xl font-extrabold
                   tracking-tight text-[#105447]"
        >
            ✦ Beasiswa Cerdas
        </a>

        <a
            href="{{ route('login') }}"
            class="inline-flex items-center gap-2
                   rounded-xl border
                   border-emerald-100
                   px-4 py-2
                   text-sm font-semibold
                   text-[#116759]
                   transition hover:bg-emerald-50"
        >
            <span aria-hidden="true">←</span>
            Kembali ke Login
        </a>

    </div>
</header>

<!-- ==========================================
     HALAMAN PEMULIHAN KATA SANDI
========================================== -->

<main
    class="relative flex w-full flex-1
           items-center justify-center
           overflow-hidden px-5 py-10
           sm:py-14"
>

    <!-- LATAR DEKORATIF -->

    <div
        class="pointer-events-none absolute
               -left-20 top-8 h-72 w-72
               rounded-full bg-emerald-100/60
               blur-3xl"
        aria-hidden="true"
    ></div>

    <div
        class="pointer-events-none absolute
               -right-20 bottom-0 h-72 w-72
               rounded-full bg-teal-100/70
               blur-3xl"
        aria-hidden="true"
    ></div>

    <div
        class="relative w-full max-w-xl
               rounded-3xl border
               border-emerald-100
               bg-white p-7 shadow-xl
               shadow-emerald-950/5
               sm:p-10"
    >

        <!-- JUDUL -->

        <div class="text-center">

            <div
                class="mx-auto flex h-16 w-16
                       items-center justify-center
                       rounded-2xl bg-emerald-100
                       text-2xl font-extrabold
                       text-[#105447]"
                aria-hidden="true"
            >
                ✉
            </div>

            <p
                class="mt-5 text-xs font-bold
                       uppercase tracking-widest
                       text-emerald-700"
            >
                PEMULIHAN AKUN USER
            </p>

            <h1
                class="mt-2 text-2xl
                       font-extrabold tracking-tight
                       text-[#103F36]
                       sm:text-3xl"
            >
                Lupa Kata Sandi?
            </h1>

            <p
                class="mx-auto mt-3 max-w-md
                       text-sm leading-7
                       text-slate-500"
            >
                Pulihkan akses akun Beasiswa Cerdas
                menggunakan kode verifikasi
                yang dikirim ke alamat Gmail
                yang terdaftar.
            </p>

        </div>

        <!-- ==================================
             INDIKATOR TAHAP
        =================================== -->

        <div
            class="mt-7 grid grid-cols-2
                   gap-3"
        >

            <div
                class="rounded-xl border p-3
                       {{ $emailTerkirim
                           ? 'border-emerald-200 bg-emerald-50'
                           : 'border-emerald-300 bg-emerald-50' }}"
            >

                <p
                    class="text-xs font-bold
                           text-emerald-800"
                >
                    01
                </p>

                <p
                    class="mt-1 text-sm font-bold
                           text-[#103F36]"
                >
                    Masukkan Gmail
                </p>

            </div>

            <div
                class="rounded-xl border p-3
                       {{ $emailTerkirim
                           ? 'border-emerald-300 bg-emerald-50'
                           : 'border-slate-200 bg-slate-50' }}"
            >

                <p
                    class="text-xs font-bold
                           {{ $emailTerkirim
                               ? 'text-emerald-800'
                               : 'text-slate-400' }}"
                >
                    02
                </p>

                <p
                    class="mt-1 text-sm font-bold
                           {{ $emailTerkirim
                               ? 'text-[#103F36]'
                               : 'text-slate-500' }}"
                >
                    Verifikasi & Reset
                </p>

            </div>

        </div>

        <!-- ==================================
             PESAN SUKSES
        =================================== -->

        @if (session('sukses'))

            <div
                role="status"
                class="mt-6 rounded-xl
                       border border-emerald-200
                       bg-emerald-50 p-4
                       text-sm leading-7
                       text-emerald-900"
            >
                {{ session('sukses') }}
            </div>

        @endif

        <!-- ==================================
             PESAN ERROR
        =================================== -->

        @if ($errors->any())

            <div
                role="alert"
                class="mt-6 rounded-xl
                       border border-red-200
                       bg-red-50 p-4
                       text-sm leading-7
                       text-red-800"
            >

                <p class="font-bold">
                    Periksa kembali formulir:
                </p>

                @foreach ($errors->all() as $error)
                    <p class="mt-1">
                        {{ $error }}
                    </p>
                @endforeach

            </div>

        @endif

        <!-- ==================================
             TAHAP 1: MASUKKAN GMAIL
        =================================== -->

        <section class="mt-7">

            <div class="flex items-center gap-3">

                <span
                    class="flex h-9 w-9
                           shrink-0 items-center
                           justify-center
                           rounded-full
                           bg-[#116759]
                           text-sm font-bold
                           text-white"
                >
                    1
                </span>

                <div>

                    <h2
                        class="text-base font-extrabold
                               text-[#103F36]"
                    >
                        Alamat Gmail Terdaftar
                    </h2>

                    <p
                        class="mt-1 text-xs
                               leading-6 text-slate-500"
                    >
                        Gunakan alamat Gmail yang
                        dipakai saat membuat akun.
                    </p>

                </div>

            </div>

            <!-- FORM KIRIM KODE -->

            <form
                method="POST"
                action="{{ route('password.send') }}"
                class="mt-5 space-y-4"
            >
                @csrf

                <div>

                    <label
                        for="email"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Alamat Gmail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $emailTerkirim ?? '') }}"
                        maxlength="254"
                        autocomplete="email"
                        placeholder="nama@gmail.com"
                        required
                        class="w-full rounded-xl
                               border border-slate-300
                               bg-white px-4 py-3.5
                               text-sm text-slate-800
                               outline-none transition
                               placeholder:text-slate-400
                               focus:border-[#116759]
                               focus:ring-4
                               focus:ring-emerald-100"
                    >

                    <p
                        class="mt-2 text-xs
                               leading-6 text-slate-500"
                    >
                        Pastikan alamat email sama
                        dengan yang tersimpan
                        di akun Beasiswa Cerdas.
                    </p>

                </div>

                <button
                    type="submit"
                    class="flex w-full items-center
                           justify-center rounded-xl
                           bg-[#116759]
                           px-5 py-3.5
                           text-sm font-bold
                           text-white transition
                           hover:bg-[#0E5247]"
                >

                    {{ $emailTerkirim
                        ? 'Kirim Ulang Kode / Ganti Gmail'
                        : 'Kirim Kode Verifikasi' }}

                </button>

            </form>

        </section>

        <!-- ==================================
             TAHAP 2: KODE & PASSWORD BARU
        =================================== -->

        @if ($emailTerkirim)

            <section
                class="mt-8 border-t
                       border-slate-100 pt-7"
            >

                <div class="flex items-center gap-3">

                    <span
                        class="flex h-9 w-9
                               shrink-0 items-center
                               justify-center
                               rounded-full bg-[#116759]
                               text-sm font-bold
                               text-white"
                    >
                        2
                    </span>

                    <div>

                        <h2
                            class="text-base font-extrabold
                                   text-[#103F36]"
                        >
                            Verifikasi Kode
                        </h2>

                        <p
                            class="mt-1 text-xs
                                   leading-6
                                   text-slate-500"
                        >
                            Masukkan kode 6 digit
                            dan kata sandi baru.
                        </p>

                    </div>

                </div>

                <div
                    class="mt-5 rounded-xl
                           border border-slate-200
                           bg-slate-50 p-4"
                >

                    <p
                        class="text-xs font-semibold
                               text-slate-500"
                    >
                        Gmail yang digunakan
                    </p>

                    <p
                        class="mt-1 break-all
                               text-sm font-bold
                               text-[#103F36]"
                    >
                        {{ $emailTerkirim }}
                    </p>

                </div>

                <form
                    method="POST"
                    action="{{ route('password.reset') }}"
                    class="mt-6 space-y-5"
                >
                    @csrf

                    <!-- KODE OTP -->

                    <div>

                        <label
                            for="kode"
                            class="mb-2 block
                                   text-sm font-bold
                                   text-slate-700"
                        >
                            Kode Verifikasi 6 Digit
                        </label>

                        <input
                            type="text"
                            id="kode"
                            name="kode"
                            maxlength="6"
                            minlength="6"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            autocomplete="one-time-code"
                            placeholder="000000"
                            value="{{ old('kode') }}"
                            required
                            class="w-full rounded-xl
                                   border border-slate-300
                                   bg-white px-4 py-3.5
                                   text-center text-xl
                                   font-extrabold
                                   tracking-widest
                                   text-[#103F36]
                                   outline-none transition
                                   placeholder:text-slate-300
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100"
                        >

                        <p
                            class="mt-2 text-xs
                                   leading-6 text-slate-500"
                        >
                            Kode berlaku selama
                            10 menit dan maksimal
                            5 kali percobaan.
                        </p>

                    </div>

                    <!-- PASSWORD BARU -->

                    <div>

                        <label
                            for="password-baru"
                            class="mb-2 block
                                   text-sm font-bold
                                   text-slate-700"
                        >
                            Kata Sandi Baru
                        </label>

                        <input
                            type="password"
                            id="password-baru"
                            name="password"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Masukkan kata sandi baru"
                            required
                            class="w-full rounded-xl
                                   border border-slate-300
                                   bg-white px-4 py-3.5
                                   text-sm text-slate-800
                                   outline-none transition
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100"
                        >

                        <p
                            class="mt-2 text-xs
                                   leading-6 text-slate-500"
                        >
                            Minimal 10 karakter,
                            gunakan huruf besar,
                            huruf kecil, dan angka.
                        </p>

                    </div>

                    <!-- KONFIRMASI PASSWORD -->

                    <div>

                        <label
                            for="konfirmasi-password"
                            class="mb-2 block
                                   text-sm font-bold
                                   text-slate-700"
                        >
                            Konfirmasi Kata Sandi Baru
                        </label>

                        <input
                            type="password"
                            id="konfirmasi-password"
                            name="password_confirmation"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            required
                            class="w-full rounded-xl
                                   border border-slate-300
                                   bg-white px-4 py-3.5
                                   text-sm text-slate-800
                                   outline-none transition
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100"
                        >

                    </div>

                    <!-- LIHAT PASSWORD -->

                    <label
                        class="flex cursor-pointer
                               items-center gap-3
                               text-sm text-slate-600"
                    >

                        <input
                            type="checkbox"
                            id="lihat-password-reset"
                            class="h-4 w-4
                                   accent-emerald-700"
                        >

                        Tampilkan kata sandi baru
                        dan konfirmasi
                    </label>

                    <!-- SIMPAN PASSWORD -->

                    <button
                        type="submit"
                        class="flex w-full
                               items-center justify-center
                               rounded-xl bg-[#116759]
                               px-5 py-3.5
                               text-sm font-bold
                               text-white transition
                               hover:bg-[#0E5247]"
                    >
                        Verifikasi & Simpan Kata Sandi
                    </button>

                </form>

            </section>

        @else

            <!-- PENJELASAN SEBELUM MEMINTA KODE -->

            <div
                class="mt-7 rounded-xl
                       border border-emerald-100
                       bg-emerald-50 p-4"
            >

                <p
                    class="text-sm font-bold
                           text-[#103F36]"
                >
                    Keamanan Akun
                </p>

                <p
                    class="mt-2 text-xs leading-7
                           text-slate-600"
                >
                    Kata sandi tidak akan diubah
                    sebelum kode verifikasi
                    yang benar dimasukkan.

                    Jangan membagikan kode
                    verifikasi kepada siapa pun.
                </p>

            </div>

        @endif

        <!-- KEMBALI KE LOGIN -->

        <div
            class="mt-8 border-t
                   border-slate-100 pt-6
                   text-center"
        >

            <p class="text-sm text-slate-500">
                Sudah ingat kata sandi?
            </p>

            <a
                href="{{ route('login') }}"
                class="mt-2 inline-flex
                       text-sm font-bold
                       text-[#116759]
                       hover:underline"
            >
                Kembali ke Halaman Login
            </a>

        </div>

    </div>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const centang = document.getElementById(
        'lihat-password-reset'
    );

    const password = document.getElementById(
        'password-baru'
    );

    const konfirmasi = document.getElementById(
        'konfirmasi-password'
    );

    if (!centang || !password || !konfirmasi) {
        return;
    }

    centang.addEventListener('change', function () {
        const jenis = this.checked
            ? 'text'
            : 'password';

        password.type = jenis;
        konfirmasi.type = jenis;
    });
});
</script>
@endpush
