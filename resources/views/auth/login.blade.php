
@extends('layouts.utama')

@section('title', 'Login - Beasiswa Cerdas')

@section('content')

<!-- ==========================================
     NAVBAR LOGIN
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
            href="{{ route('beranda') }}"
            class="inline-flex items-center gap-2
                   rounded-xl border
                   border-emerald-100
                   px-4 py-2 text-sm
                   font-semibold text-[#116759]
                   transition hover:bg-emerald-50"
        >
            <span aria-hidden="true">←</span>
            Kembali ke Beranda
        </a>

    </div>
</header>

<!-- ==========================================
     KONTEN LOGIN
========================================== -->

<main
    class="relative flex w-full flex-1
           items-center overflow-hidden
           px-5 py-10 sm:py-14"
>

    <!-- DEKORASI LATAR -->

    <div
        class="pointer-events-none absolute
               -left-24 top-10 h-72 w-72
               rounded-full bg-emerald-100/60
               blur-3xl"
        aria-hidden="true"
    ></div>

    <div
        class="pointer-events-none absolute
               -right-20 bottom-0 h-80 w-80
               rounded-full bg-teal-100/70
               blur-3xl"
        aria-hidden="true"
    ></div>

    <div
        class="relative mx-auto grid
               w-full max-w-5xl
               overflow-hidden rounded-3xl
               border border-emerald-100
               bg-white shadow-xl
               shadow-emerald-950/5
               lg:grid-cols-[.9fr_1.1fr]"
    >

        <!-- ==================================
             INFORMASI SISTEM
             SEBELAH KIRI PADA DESKTOP
        =================================== -->

        <aside
            class="relative flex flex-col
                   justify-between gap-8
                   bg-[#103F36] p-7
                   text-white
                   sm:p-10 lg:p-12"
        >

            <div
                class="pointer-events-none absolute
                       -right-16 -top-16
                       h-56 w-56 rounded-full
                       border border-white/10"
                aria-hidden="true"
            ></div>

            <div class="relative">

                <div
                    class="inline-flex items-center
                           gap-2 rounded-full
                           border border-emerald-200/20
                           bg-white/10 px-4 py-2
                           text-xs font-bold
                           text-emerald-100"
                >
                    <span aria-hidden="true">✦</span>
                    PORTAL BEASISWA CERDAS
                </div>

                <h1
                    class="mt-7 max-w-sm
                           text-3xl font-extrabold
                           leading-tight tracking-tight
                           sm:text-4xl"
                >
                    Selamat datang kembali.
                </h1>

                <p
                    class="mt-5 max-w-sm
                           text-sm leading-8
                           text-emerald-100"
                >
                    Masuk untuk mengelola pengajuan,
                    memantau proses seleksi,
                    serta melihat keputusan akhir
                    beasiswa berdasarkan sistem
                    AHP dan WASPAS.
                </p>

            </div>

            <div class="relative space-y-4">

                <div
                    class="flex items-start gap-3
                           rounded-2xl border
                           border-white/10
                           bg-white/10 p-4"
                >
                    <span
                        class="flex h-10 w-10
                               shrink-0 items-center
                               justify-center
                               rounded-xl bg-white/15"
                        aria-hidden="true"
                    >
                        ✓
                    </span>

                    <div>
                        <p class="text-sm font-bold">
                            Pengajuan Terpantau
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-6 text-emerald-100"
                        >
                            Informasi pengajuan dan
                            pemeriksaan administrasi
                            dalam satu portal.
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-start gap-3
                           rounded-2xl border
                           border-white/10
                           bg-white/10 p-4"
                >
                    <span
                        class="flex h-10 w-10
                               shrink-0 items-center
                               justify-center
                               rounded-xl bg-white/15"
                        aria-hidden="true"
                    >
                        ★
                    </span>

                    <div>
                        <p class="text-sm font-bold">
                            Hasil Seleksi Transparan
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-6 text-emerald-100"
                        >
                            Keputusan akhir ditampilkan
                            setelah diumumkan oleh Admin.
                        </p>
                    </div>
                </div>

            </div>

            <p
                class="relative text-xs
                       leading-6 text-emerald-200"
            >
                Beasiswa Cerdas · Kelompok 4
                <span class="mx-1">·</span>
                Universitas Pamulang
            </p>

        </aside>

        <!-- ==================================
             FORM LOGIN
             SEBELAH KANAN
        =================================== -->

        <section
            class="flex flex-col
                   justify-center p-7
                   sm:p-10 lg:p-12"
            aria-labelledby="judul-login"
        >

            <!-- IDENTITAS FORM -->

            <div>

                <div
                    class="flex h-14 w-14
                           items-center justify-center
                           rounded-2xl
                           bg-emerald-100
                           text-xl font-extrabold
                           text-[#105447]"
                    aria-hidden="true"
                >
                    BC
                </div>

                <h2
                    id="judul-login"
                    class="mt-6 text-2xl
                           font-extrabold tracking-tight
                           text-[#103F36]
                           sm:text-3xl"
                >
                    Masuk ke Akun
                </h2>

                <p
                    class="mt-3 text-sm
                           leading-7 text-slate-500"
                >
                    Masukkan username atau email
                    dan kata sandi yang terdaftar.
                </p>

            </div>

            <!-- PESAN SUKSES -->

            @if (session('sukses'))

                <div
                    role="status"
                    class="mt-6 rounded-xl
                           border border-emerald-200
                           bg-emerald-50 p-4
                           text-sm leading-6
                           text-emerald-800"
                >
                    {{ session('sukses') }}
                </div>

            @endif

            <!-- AKUN SUDAH LOGIN -->

            @auth

                <div
                    class="mt-6 rounded-xl
                           border border-blue-200
                           bg-blue-50 p-4
                           text-sm leading-7
                           text-blue-900"
                >
                    Kamu sedang login sebagai

                    <strong>
                        {{ auth()->user()->nama_lengkap
                            ?: auth()->user()->username }}
                    </strong>.

                    <p class="mt-1">
                        Kamu dapat memasukkan
                        akun lain untuk berganti akun.
                    </p>
                </div>

            @endauth

            <!-- PESAN ERROR -->

            @if ($errors->any())

                <div
                    role="alert"
                    class="mt-6 rounded-xl
                           border border-red-200
                           bg-red-50 p-4
                           text-sm leading-6
                           text-red-800"
                >
                    {{ $errors->first() }}
                </div>

            @endif

            <!-- ==================================
                 FORM LOGIN LAMA DIPERTAHANKAN
            =================================== -->

            <form
                method="POST"
                action="{{ route('login.proses') }}"
                class="mt-7 space-y-5"
            >
                @csrf

                <!-- USERNAME / EMAIL -->

                <div>

                    <label
                        for="login"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Username atau Email
                    </label>

                    <input
                        type="text"
                        id="login"
                        name="login"
                        value="{{ old('login') }}"
                        maxlength="254"
                        autocomplete="username"
                        placeholder="Masukkan username atau email"
                        required
                        autofocus
                        aria-invalid="{{ $errors->has('login') ? 'true' : 'false' }}"
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

                </div>

                <!-- PASSWORD -->

                <div>

                    <div
                        class="mb-2 flex flex-wrap
                               items-center
                               justify-between gap-2"
                    >

                        <label
                            for="password"
                            class="text-sm font-bold
                                   text-slate-700"
                        >
                            Kata Sandi
                        </label>

                        <!-- FITUR BARU -->

                        <a
                            href="{{ route('password.forgot') }}"
                            class="text-xs font-bold
                                   text-[#116759]
                                   hover:underline"
                        >
                            Lupa Kata Sandi?
                        </a>

                    </div>

                    <div class="relative">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            required
                            class="w-full rounded-xl
                                   border border-slate-300
                                   bg-white px-4 py-3.5
                                   pr-24 text-sm
                                   text-slate-800
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100"
                        >

                        <button
                            type="button"
                            id="toggle-password"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false"
                            class="absolute right-2
                                   top-1/2 -translate-y-1/2
                                   rounded-lg px-3 py-2
                                   text-xs font-bold
                                   text-[#116759]
                                   transition
                                   hover:bg-emerald-50"
                        >
                            Lihat
                        </button>

                    </div>

                </div>

                <!-- TOMBOL MASUK -->

                <button
                    type="submit"
                    class="flex w-full
                           items-center
                           justify-center
                           rounded-xl
                           bg-[#116759]
                           px-5 py-3.5
                           text-sm font-bold
                           text-white shadow-sm
                           transition
                           hover:bg-[#0E5247]
                           focus-visible:outline
                           focus-visible:outline-4
                           focus-visible:outline-emerald-200"
                >
                    Masuk ke Beasiswa Cerdas
                </button>

            </form>

            <!-- REGISTRASI -->

            <div
                class="mt-8 border-t
                       border-slate-100 pt-6"
            >

                <p
                    class="text-center text-sm
                           text-slate-500"
                >
                    Belum mempunyai akun?
                </p>

                <a
                    href="{{ route('register') }}"
                    class="mt-3 flex w-full
                           items-center justify-center
                           rounded-xl border
                           border-emerald-300
                           bg-white px-5 py-3
                           text-sm font-bold
                           text-[#116759]
                           transition
                           hover:bg-emerald-50"
                >
                    Daftar Akun User
                </a>

            </div>

            <!-- LOGOUT JIKA SUDAH LOGIN -->

            @auth

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="mt-5 text-center"
                >
                    @csrf

                    <button
                        type="submit"
                        class="text-sm font-semibold
                               text-red-700 underline"
                    >
                        Logout akun saat ini
                    </button>
                </form>

            @endauth

        </section>

    </div>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const password = document.getElementById('password');
    const toggle = document.getElementById('toggle-password');

    if (!password || !toggle) {
        return;
    }

    toggle.addEventListener('click', function () {
        const tampil = password.type === 'password';

        password.type = tampil ? 'text' : 'password';
        toggle.textContent = tampil ? 'Sembunyikan' : 'Lihat';

        toggle.setAttribute(
            'aria-pressed',
            tampil ? 'true' : 'false'
        );

        toggle.setAttribute(
            'aria-label',
            tampil
                ? 'Sembunyikan kata sandi'
                : 'Tampilkan kata sandi'
        );
    });
});
</script>
@endpush
