
@extends('layouts.utama')

@section('title', 'Registrasi User - Beasiswa Cerdas')

@push('styles')
<style>
    /*
    |--------------------------------------------------------------------------
    | TAMPILAN REGISTRASI
    |--------------------------------------------------------------------------
    | Mengikuti halaman Login.
    | Footer disembunyikan hanya di halaman ini.
    */

    body > footer {
        display: none !important;
    }

    .bc-register-eye {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);

        width: 38px;
        height: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: none !important;
        border-radius: 9px;
        padding: 0 !important;

        background: transparent !important;
        box-shadow: none !important;

        color: #64748b !important;
        cursor: pointer;

        transition:
            color 0.2s ease,
            background-color 0.2s ease;
    }

    .bc-register-eye:hover {
        background: #ecfdf5 !important;
        color: #116759 !important;
    }

    .bc-register-eye:focus-visible {
        outline: 2px solid #116759;
        outline-offset: 2px;
    }

    .bc-register-eye svg {
        width: 21px;
        height: 21px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .bc-register-eye .bc-eye-hidden {
        display: none;
    }
</style>
@endpush

@section('content')

<!-- ==========================================
     NAVBAR REGISTRASI
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
     KONTEN REGISTRASI
========================================== -->

<main
    class="relative w-full
           overflow-hidden
           px-5 py-10 sm:py-14"
>

    <!-- DEKORASI LATAR -->

    <div
        class="pointer-events-none absolute
               -left-24 top-10
               h-72 w-72 rounded-full
               bg-emerald-100/60 blur-3xl"
        aria-hidden="true"
    ></div>

    <div
        class="pointer-events-none absolute
               -right-20 bottom-0
               h-80 w-80 rounded-full
               bg-teal-100/70 blur-3xl"
        aria-hidden="true"
    ></div>

    <!-- KARTU REGISTRASI -->

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
             PANEL INFORMASI HIJAU
             KOLOM KIRI
        =================================== -->

        <aside
            class="relative flex flex-col
                   justify-between gap-8
                   bg-[#103F36] p-7
                   text-white
                   sm:p-10 lg:p-12"
        >

            <!-- DEKORASI PANEL -->

            <div
                class="pointer-events-none absolute
                       -right-16 -top-16
                       h-56 w-56
                       rounded-full border
                       border-white/10"
                aria-hidden="true"
            ></div>

            <!-- JUDUL INFORMASI -->

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
                    Mulai langkahmu bersama
                    Beasiswa Cerdas.
                </h1>

                <p
                    class="mt-5 max-w-sm
                           text-sm leading-8
                           text-emerald-100"
                >
                    Buat akun untuk mengajukan
                    beasiswa, melengkapi dokumen,
                    memantau tahapan seleksi,
                    serta mengetahui keputusan
                    akhir yang diumumkan Admin.
                </p>

            </div>

            <!-- INFORMASI KEUNGGULAN -->

            <div class="relative space-y-4">

                <!-- PENDAFTARAN TERARAH -->

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
                            Pengajuan Lebih Terarah
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-6 text-emerald-100"
                        >
                            Lengkapi data dan
                            dokumen melalui
                            formulir pendaftaran
                            yang tersedia.
                        </p>

                    </div>

                </div>

                <!-- PROSES TRANSPARAN -->

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
                            Proses Seleksi Transparan
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-6 text-emerald-100"
                        >
                            Pantau pemeriksaan
                            administrasi dan
                            hasil seleksi
                            setelah diumumkan.
                        </p>

                    </div>

                </div>

                <!-- KEAMANAN AKUN -->

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
                        ✦
                    </span>

                    <div>

                        <p class="text-sm font-bold">
                            Akun Pribadi
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-6 text-emerald-100"
                        >
                            Setiap User menggunakan
                            akun sendiri untuk
                            mengakses pengajuan
                            dan informasi hasilnya.
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
             FORM REGISTRASI
             KOLOM KANAN
        =================================== -->

        <section
            class="p-7 sm:p-10 lg:p-12"
            aria-labelledby="judul-register"
        >

            <!-- JUDUL FORMULIR -->

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
                    id="judul-register"
                    class="mt-6 text-2xl
                           font-extrabold
                           tracking-tight
                           text-[#103F36]
                           sm:text-3xl"
                >
                    Daftar Akun Beasiswa
                </h2>

                <p
                    class="mt-3 text-sm
                           leading-7 text-slate-500"
                >
                    Isi informasi berikut
                    untuk membuat akun User
                    Beasiswa Cerdas.
                </p>

            </div>

            <!-- INFORMASI PENDAFTARAN -->

            <div
                class="mt-6 rounded-xl
                       border border-emerald-200
                       bg-emerald-50 p-4
                       text-sm leading-7
                       text-emerald-900"
            >
                Pendaftaran ini khusus untuk
                <strong>
                    calon pendaftar beasiswa (User)
                </strong>.

                Akun Admin tidak dapat dibuat
                melalui formulir ini.
            </div>

            <!-- ==================================
                 FORMULIR REGISTRASI
            =================================== -->

            <form
                id="form-register"
                method="POST"
                action="{{ route('register.proses') }}"
                class="mt-7 space-y-5"
                novalidate
            >

                @csrf

                <!-- ==================================
                     NAMA LENGKAP
                =================================== -->

                <div>

                    <label
                        for="nama_lengkap"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        id="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        maxlength="100"
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        required
                        aria-invalid="{{ $errors->has('nama_lengkap') ? 'true' : 'false' }}"
                        aria-describedby="error-nama_lengkap"
                        class="w-full rounded-xl
                               border bg-white
                               px-4 py-3.5
                               text-sm text-slate-800
                               outline-none transition
                               placeholder:text-slate-400
                               focus:border-[#116759]
                               focus:ring-4
                               focus:ring-emerald-100
                               {{ $errors->has('nama_lengkap')
                                   ? 'border-red-500 bg-red-50'
                                   : 'border-slate-300' }}"
                    >

                    <p
                        id="error-nama_lengkap"
                        class="mt-2 text-sm
                               font-medium text-red-600
                               {{ $errors->has('nama_lengkap') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('nama_lengkap') }}
                    </p>

                </div>

                <!-- ==================================
                     USERNAME
                =================================== -->

                <div>

                    <label
                        for="username"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        value="{{ old('username') }}"
                        minlength="3"
                        maxlength="50"
                        autocomplete="username"
                        placeholder="Contoh: siswa_01"
                        required
                        aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                        aria-describedby="error-username"
                        class="w-full rounded-xl
                               border bg-white
                               px-4 py-3.5
                               text-sm text-slate-800
                               outline-none transition
                               placeholder:text-slate-400
                               focus:border-[#116759]
                               focus:ring-4
                               focus:ring-emerald-100
                               {{ $errors->has('username')
                                   ? 'border-red-500 bg-red-50'
                                   : 'border-slate-300' }}"
                    >

                    <p
                        class="mt-2 text-xs
                               leading-6 text-slate-500"
                    >
                        Gunakan 3–50 karakter:
                        huruf, angka,
                        atau garis bawah (_).
                    </p>

                    <p
                        id="error-username"
                        class="mt-2 text-sm
                               font-medium text-red-600
                               {{ $errors->has('username') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('username') }}
                    </p>

                </div>

                <!-- ==================================
                     EMAIL
                =================================== -->

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
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        maxlength="254"
                        autocomplete="email"
                        placeholder="contoh@gmail.com"
                        required
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="error-email"
                        class="w-full rounded-xl
                               border bg-white
                               px-4 py-3.5
                               text-sm text-slate-800
                               outline-none transition
                               placeholder:text-slate-400
                               focus:border-[#116759]
                               focus:ring-4
                               focus:ring-emerald-100
                               {{ $errors->has('email')
                                   ? 'border-red-500 bg-red-50'
                                   : 'border-slate-300' }}"
                    >

                    <p
                        class="mt-2 text-xs
                               leading-6 text-slate-500"
                    >
                        Gunakan alamat email
                        berakhiran @gmail.com.
                    </p>

                    <p
                        id="error-email"
                        class="mt-2 text-sm
                               font-medium text-red-600
                               {{ $errors->has('email') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('email') }}
                    </p>

                </div>

                <!-- ==================================
                     PASSWORD
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

                    <div class="relative">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Buat kata sandi"
                            required
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="error-password"
                            class="w-full rounded-xl
                                   border bg-white
                                   px-4 py-3.5 pr-14
                                   text-sm text-slate-800
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100
                                   {{ $errors->has('password')
                                       ? 'border-red-500 bg-red-50'
                                       : 'border-slate-300' }}"
                        >

                        <!-- IKON MATA -->

                        <button
                            type="button"
                            class="bc-register-eye"
                            data-target="password"
                            aria-label="Tampilkan kata sandi"
                            aria-controls="password"
                            aria-pressed="false"
                            title="Tampilkan kata sandi"
                        >

                            <svg
                                class="bc-eye-open"
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

                            <svg
                                class="bc-eye-closed bc-eye-hidden"
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

                    <p
                        class="mt-2 text-xs
                               leading-6 text-slate-500"
                    >
                        Minimal 10 karakter,
                        maksimal 72 karakter,
                        terdiri atas huruf besar,
                        huruf kecil, dan angka.
                    </p>

                    <p
                        id="error-password"
                        class="mt-2 text-sm
                               font-medium text-red-600
                               {{ $errors->has('password') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('password') }}
                    </p>

                </div>

                <!-- ==================================
                     KONFIRMASI PASSWORD
                =================================== -->

                <div>

                    <label
                        for="password_confirmation"
                        class="mb-2 block
                               text-sm font-bold
                               text-slate-700"
                    >
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi"
                            required
                            aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                            aria-describedby="error-password_confirmation"
                            class="w-full rounded-xl
                                   border bg-white
                                   px-4 py-3.5 pr-14
                                   text-sm text-slate-800
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-[#116759]
                                   focus:ring-4
                                   focus:ring-emerald-100
                                   {{ $errors->has('password_confirmation')
                                       ? 'border-red-500 bg-red-50'
                                       : 'border-slate-300' }}"
                        >

                        <!-- IKON MATA -->

                        <button
                            type="button"
                            class="bc-register-eye"
                            data-target="password_confirmation"
                            aria-label="Tampilkan konfirmasi kata sandi"
                            aria-controls="password_confirmation"
                            aria-pressed="false"
                            title="Tampilkan konfirmasi kata sandi"
                        >

                            <svg
                                class="bc-eye-open"
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

                            <svg
                                class="bc-eye-closed bc-eye-hidden"
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

                    <p
                        id="error-password_confirmation"
                        class="mt-2 text-sm
                               font-medium text-red-600
                               {{ $errors->has('password_confirmation') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('password_confirmation') }}
                    </p>

                </div>

                <!-- ==================================
                     TOMBOL DAFTAR
                =================================== -->

                <button
                    type="submit"
                    class="flex w-full
                           items-center justify-center
                           rounded-xl bg-[#116759]
                           px-5 py-3.5
                           text-sm font-bold
                           text-white shadow-sm
                           transition hover:bg-[#0E5247]"
                >
                    Daftar Akun User
                </button>

            </form>

            <!-- ==================================
                 LINK LOGIN
            =================================== -->

            <div
                class="mt-8 border-t
                       border-slate-100 pt-6"
            >

                <p
                    class="text-center text-sm
                           text-slate-500"
                >
                    Sudah mempunyai akun?
                </p>

                <a
                    href="{{ route('login') }}"
                    class="mt-3 flex w-full
                           items-center justify-center
                           rounded-xl border
                           border-emerald-300
                           bg-white px-5 py-3
                           text-sm font-bold
                           text-[#116759]
                           transition hover:bg-emerald-50"
                >
                    Masuk ke Beasiswa Cerdas
                </a>

            </div>

        </section>

    </div>

</main>

@endsection

<!-- ==========================================
     JAVASCRIPT VALIDASI FORMULIR
     FUNGSI LAMA TETAP DIPERTAHANKAN
========================================== -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const formRegister = document.getElementById(
        'form-register'
    );

    if (!formRegister) {
        return;
    }

    // =====================================
    // DAFTAR KOLOM REGISTRASI
    // =====================================

    const daftarKolom = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'password_confirmation'
    ];

    // =====================================
    // PEMERIKSAAN ISI KOLOM
    // =====================================

    function cekIsiKolom(namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        const nilai = kolom.value;
        const teks = nilai.trim();

        // NAMA LENGKAP

        if (namaKolom === 'nama_lengkap') {

            if (teks === '') {
                return 'Nama lengkap wajib diisi.';
            }

            if ([...teks].length < 3) {
                return 'Nama lengkap minimal 3 karakter.';
            }

            if ([...teks].length > 100) {
                return 'Nama lengkap maksimal 100 karakter.';
            }
        }

        // USERNAME

        if (namaKolom === 'username') {

            if (teks === '') {
                return 'Username wajib diisi.';
            }

            if (teks.length < 3) {
                return 'Username minimal 3 karakter.';
            }

            if (teks.length > 50) {
                return 'Username maksimal 50 karakter.';
            }

            if (!/^[a-zA-Z0-9_]+$/.test(teks)) {
                return 'Username hanya boleh huruf, angka, dan garis bawah (_).';
            }
        }

        // EMAIL GMAIL

        if (namaKolom === 'email') {

            if (teks === '') {
                return 'Alamat Gmail wajib diisi.';
            }

            if (teks.length > 254) {
                return 'Alamat Gmail terlalu panjang.';
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(teks)) {
                return 'Format alamat email tidak valid.';
            }

            if (!teks.toLowerCase().endsWith('@gmail.com')) {
                return 'Email harus menggunakan alamat @gmail.com.';
            }
        }

        // PASSWORD

        if (namaKolom === 'password') {

            if (nilai === '') {
                return 'Password wajib diisi.';
            }

            if ([...nilai].length < 10) {
                return 'Password minimal harus 10 karakter.';
            }

            if ([...nilai].length > 72) {
                return 'Password maksimal 72 karakter.';
            }

            if (!/[A-Z]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 huruf besar.';
            }

            if (!/[a-z]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 huruf kecil.';
            }

            if (!/[0-9]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 angka.';
            }
        }

        // KONFIRMASI PASSWORD

        if (namaKolom === 'password_confirmation') {

            const password = document.getElementById(
                'password'
            ).value;

            if (nilai === '') {
                return 'Konfirmasi password wajib diisi.';
            }

            if (nilai !== password) {
                return 'Konfirmasi password tidak sama dengan password.';
            }
        }

        return '';
    }

    // =====================================
    // TAMPILKAN ERROR DI BAWAH INPUT
    // =====================================

    function tampilkanValidasi(namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        const tempatPesan = document.getElementById(
            'error-' + namaKolom
        );

        const pesan = cekIsiKolom(namaKolom);

        if (pesan !== '') {

            tempatPesan.textContent = pesan;
            tempatPesan.classList.remove('hidden');

            kolom.classList.remove(
                'border-slate-300'
            );

            kolom.classList.add(
                'border-red-500',
                'bg-red-50'
            );

            kolom.setAttribute(
                'aria-invalid',
                'true'
            );

            return false;

        } else {

            tempatPesan.textContent = '';
            tempatPesan.classList.add('hidden');

            kolom.classList.remove(
                'border-red-500',
                'bg-red-50'
            );

            kolom.classList.add(
                'border-slate-300'
            );

            kolom.setAttribute(
                'aria-invalid',
                'false'
            );

            return true;
        }
    }

    // =====================================
    // VALIDASI KETIKA MENGETIK
    // =====================================

    daftarKolom.forEach(function (namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        kolom.addEventListener('input', function () {

            tampilkanValidasi(namaKolom);

            if (namaKolom === 'password') {

                const konfirmasi = document.getElementById(
                    'password_confirmation'
                );

                if (konfirmasi.value !== '') {

                    tampilkanValidasi(
                        'password_confirmation'
                    );
                }
            }
        });

        // VALIDASI SAAT KELUAR DARI KOLOM

        kolom.addEventListener('blur', function () {

            tampilkanValidasi(namaKolom);

        });
    });

    // =====================================
    // VALIDASI SAAT KLIK DAFTAR
    // =====================================

    formRegister.addEventListener(
        'submit',
        function (event) {

            let kolomPertamaSalah = null;

            daftarKolom.forEach(function (namaKolom) {

                const benar = tampilkanValidasi(
                    namaKolom
                );

                if (
                    !benar &&
                    kolomPertamaSalah === null
                ) {

                    kolomPertamaSalah =
                        document.getElementById(
                            namaKolom
                        );
                }
            });

            if (kolomPertamaSalah !== null) {

                event.preventDefault();

                kolomPertamaSalah.focus();

                kolomPertamaSalah.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
    );

    // =====================================
    // IKON MATA PASSWORD
    // =====================================

    document.querySelectorAll(
        '.bc-register-eye'
    ).forEach(function (tombol) {

        tombol.addEventListener(
            'click',
            function () {

                const targetId = tombol.getAttribute(
                    'data-target'
                );

                const input = document.getElementById(
                    targetId
                );

                if (!input) {
                    return;
                }

                const tampil =
                    input.type === 'password';

                input.type = tampil
                    ? 'text'
                    : 'password';

                const mataTerbuka = tombol.querySelector(
                    '.bc-eye-open'
                );

                const mataTertutup = tombol.querySelector(
                    '.bc-eye-closed'
                );

                if (mataTerbuka && mataTertutup) {

                    mataTerbuka.classList.toggle(
                        'bc-eye-hidden',
                        tampil
                    );

                    mataTertutup.classList.toggle(
                        'bc-eye-hidden',
                        !tampil
                    );
                }

                const konfirmasi =
                    targetId ===
                    'password_confirmation';

                const label = konfirmasi
                    ? 'konfirmasi kata sandi'
                    : 'kata sandi';

                tombol.setAttribute(
                    'aria-pressed',
                    tampil ? 'true' : 'false'
                );

                tombol.setAttribute(
                    'aria-label',
                    (tampil ? 'Sembunyikan ' : 'Tampilkan ')
                    + label
                );

                tombol.setAttribute(
                    'title',
                    (tampil ? 'Sembunyikan ' : 'Tampilkan ')
                    + label
                );

            }
        );
    });
});
</script>
@endpush
