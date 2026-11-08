
@extends('layouts.utama')

@section('title', 'Login - Beasiswa Cerdas')

{{-- =========================================
     PENGATURAN KHUSUS HALAMAN LOGIN

     Footer global hanya disembunyikan
     pada halaman Login.

     Tombol ikon mata dibuat sederhana,
     tanpa tampilan tombol berwarna.
========================================== --}}

@push('styles')
<style>
    body > footer {
        display: none !important;
    }

    .bc-login-eye-button {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);

        display: inline-flex;
        align-items: center;
        justify-content: center;

        width: 36px;
        height: 36px;

        padding: 0 !important;
        margin: 0;

        border: none !important;
        border-radius: 9px;

        background: transparent !important;
        box-shadow: none !important;

        color: #64748b !important;
        cursor: pointer;

        transition:
            color 0.2s ease,
            background-color 0.2s ease;
    }

    .bc-login-eye-button:hover {
        color: #116759 !important;
        background: #ecfdf5 !important;
    }

    .bc-login-eye-button:focus-visible {
        outline: 2px solid #116759;
        outline-offset: 2px;
    }

    .bc-login-eye-button svg {
        width: 21px;
        height: 21px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .bc-login-eye-button .bc-eye-hidden {
        display: none;
    }

    .bc-login-forgot {
        display: flex;
        justify-content: flex-end;
        margin-top: 11px;
    }

    .bc-login-forgot a {
        color: #116759;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .bc-login-forgot a:hover {
        color: #0e5247;
        text-decoration: underline;
    }

    .bc-login-forgot a:focus-visible {
        outline: 2px solid #116759;
        outline-offset: 4px;
        border-radius: 3px;
    }
</style>
@endpush

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
                   px-4 py-2
                   text-sm font-semibold
                   text-[#116759]
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

    <!-- KARTU UTAMA LOGIN -->

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
             KOLOM KIRI
        =================================== -->

        <aside
            class="relative flex flex-col
                   justify-between gap-8
                   bg-[#103F36] p-7
                   text-white
                   sm:p-10 lg:p-12"
        >

            <!-- DEKORASI -->

            <div
                class="pointer-events-none absolute
                       -right-16 -top-16
                       h-56 w-56 rounded-full
                       border border-white/10"
                aria-hidden="true"
            ></div>

            <!-- SAMBUTAN -->

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

            <!-- INFORMASI FITUR -->

            <div class="relative space-y-4">

                <!-- PENGAJUAN TERPANTAU -->

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

                <!-- HASIL SELEKSI -->

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

            <!-- IDENTITAS KELOMPOK -->

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
             KOLOM KANAN
        =================================== -->

        <section
            class="flex flex-col
                   justify-center p-7
                   sm:p-10 lg:p-12"
            aria-labelledby="judul-login"
        >

            <!-- JUDUL FORM -->

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

            <!-- ==================================
                 PESAN SUKSES
            =================================== -->

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

            <!-- ==================================
                 INFORMASI AKUN SUDAH LOGIN
            =================================== -->

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

            <!-- ==================================
                 PESAN ERROR LOGIN
            =================================== -->

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
                 FORM LOGIN
            =================================== -->

            <form
                method="POST"
                action="{{ route('login.proses') }}"
                class="mt-7 space-y-5"
            >

                @csrf

                <!-- USERNAME ATAU EMAIL -->

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

                <!-- ==================================
                     KATA SANDI
                =================================== -->

                <div>

                    <label
                        for="password"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Kata Sandi
                    </label>

                    <!-- INPUT PASSWORD + IKON MATA -->

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
                                   pr-16 text-sm
                                   text-slate-800
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100"
                        >

                        <!--
                            IKON MATA SAJA

                            Saat password tersembunyi:
                            ikon mata biasa.

                            Saat password terlihat:
                            ikon mata dicoret.
                        -->

                        <button
                            type="button"
                            id="toggle-password"
                            class="bc-login-eye-button"
                            aria-label="Tampilkan kata sandi"
                            aria-controls="password"
                            aria-pressed="false"
                            title="Tampilkan kata sandi"
                        >

                            <!-- IKON MATA TERBUKA -->

                            <svg
                                id="icon-eye-open"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="3"
                                />
                            </svg>

                            <!-- IKON MATA DICORET -->

                            <svg
                                id="icon-eye-closed"
                                class="bc-eye-hidden"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.8 10.8 0 0 1-3.173 4.073"
                                />

                                <path
                                    d="M14.084 14.158a3 3 0 0 1-4.242-4.242"
                                />

                                <path
                                    d="M17.479 17.499A10.75 10.75 0 0 1 2.062 12.35a1 1 0 0 1 0-.7 10.7 10.7 0 0 1 4.446-5.143"
                                />

                                <path d="m2 2 20 20" />
                            </svg>

                        </button>

                    </div>

                    <!-- ==================================
                         LUPA KATA SANDI
                         DIPINDAHKAN KE BAWAH INPUT
                    =================================== -->

                    <div class="bc-login-forgot">

                        <a
                            href="{{ route('password.forgot') }}"
                        >
                            Lupa Kata Sandi?
                        </a>

                    </div>

                </div>

                <!-- TOMBOL LOGIN -->

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

            <!-- ==================================
                 REGISTRASI USER
            =================================== -->

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

            <!-- ==================================
                 LOGOUT JIKA SUDAH LOGIN
            =================================== -->

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

<!-- ==========================================
     JAVASCRIPT IKON MATA
========================================== -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const password = document.getElementById(
        'password'
    );

    const toggle = document.getElementById(
        'toggle-password'
    );

    const iconEyeOpen = document.getElementById(
        'icon-eye-open'
    );

    const iconEyeClosed = document.getElementById(
        'icon-eye-closed'
    );

    if (
        !password ||
        !toggle ||
        !iconEyeOpen ||
        !iconEyeClosed
    ) {
        return;
    }

    toggle.addEventListener('click', function () {

        const tampil = password.type === 'password';

        // Mengubah jenis input.
        password.type = tampil
            ? 'text'
            : 'password';

        // Saat password terlihat,
        // gunakan ikon mata dicoret.
        if (tampil) {

            iconEyeOpen.classList.add(
                'bc-eye-hidden'
            );

            iconEyeClosed.classList.remove(
                'bc-eye-hidden'
            );

        } else {

            iconEyeOpen.classList.remove(
                'bc-eye-hidden'
            );

            iconEyeClosed.classList.add(
                'bc-eye-hidden'
            );
        }

        // Aksesibilitas tombol.
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

        toggle.setAttribute(
            'title',
            tampil
                ? 'Sembunyikan kata sandi'
                : 'Tampilkan kata sandi'
        );

    });

});
</script>
@endpush
