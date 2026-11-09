
@extends('layouts.utama')

@section('title', 'Registrasi User - Beasiswa Cerdas')

{{-- 
    Menggunakan stylesheet yang sama dengan
    halaman Login dan Lupa Kata Sandi.

    Tidak mengubah route, controller,
    nama input, atau validasi registrasi.
--}}

@push('styles')
    @include('partials.auth-style')

    <style>
        /*
        |--------------------------------------------------------------------------
        | PENYESUAIAN KHUSUS REGISTRASI
        |--------------------------------------------------------------------------
        | Hanya untuk komponen registrasi.
        | Ukuran utama mengikuti auth-style.
        */

        .bc-auth-register-side {
            justify-content: flex-start;
            gap: 27px;
        }

        .bc-auth-register-side .bc-auth-side-footer {
            margin-top: auto;
        }

        .bc-auth-register-content {
            justify-content: flex-start;
        }

        .bc-auth-register-notice {
            margin-top: 20px;
            padding: 12px 14px;
            border: 1px solid #cfe4d5;
            border-radius: 8px;
            background: #eff7f1;
            color: #285c43;
            font-size: 11.5px;
            line-height: 1.75;
        }

        .bc-auth-register-notice strong {
            font-weight: 750;
        }

        .bc-auth-register-error {
            margin-top: 7px;
            color: #b44242;
            font-size: 11.5px;
            font-weight: 600;
            line-height: 1.6;
        }

        .bc-auth-register-error.hidden {
            display: none;
        }

        .bc-auth-register-content .bc-auth-hint {
            margin-top: 6px;
        }

        .bc-auth-register-content .bc-auth-form {
            margin-top: 23px;
        }

        .bc-auth-register-content .bc-auth-field + .bc-auth-field {
            margin-top: 18px;
        }

        @media (max-width: 820px) {
            .bc-auth-register-side {
                gap: 17px;
            }

            .bc-auth-register-content {
                padding-top: 30px;
            }
        }
    </style>
@endpush

@section('content')

<!-- ==========================================
     NAVBAR REGISTRASI
========================================== -->

<header class="bc-auth-topbar">

    <div class="bc-auth-topbar-inner">

        <a
            href="{{ route('beranda') }}"
            class="bc-auth-brand"
        >
            ✦ Beasiswa Cerdas
        </a>

        <a
            href="{{ route('login') }}"
            class="bc-auth-back"
        >
            ← Kembali ke Login
        </a>

    </div>

</header>

<!-- ==========================================
     HALAMAN REGISTRASI
========================================== -->

<main class="bc-auth-page">

    <div class="bc-auth-card">

        <!-- ==================================
             PANEL HIJAU KIRI
        =================================== -->

        <aside
            class="bc-auth-side bc-auth-register-side"
        >

            <!-- INFORMASI UTAMA -->

            <div>

                <p class="bc-auth-kicker">
                    ✦ PORTAL BEASISWA CERDAS
                </p>

                <h1 class="bc-auth-side-title">
                    Mulai langkahmu bersama
                    Beasiswa Cerdas.
                </h1>

                <p class="bc-auth-side-desc">
                    Buat akun untuk mengajukan
                    beasiswa, melengkapi dokumen,
                    memantau tahapan seleksi,
                    serta mengetahui keputusan
                    akhir yang diumumkan Admin.
                </p>

            </div>

            <!-- INFORMASI FITUR -->

            <div class="bc-auth-side-list">

                <!-- INFORMASI 1 -->

                <div class="bc-auth-side-item">

                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        ✓
                    </span>

                    <div>

                        <p class="bc-auth-side-item-title">
                            Pengajuan Lebih Terarah
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Lengkapi data dan
                            dokumen melalui
                            formulir pendaftaran
                            yang tersedia.
                        </p>

                    </div>

                </div>

                <!-- INFORMASI 2 -->

                <div class="bc-auth-side-item">

                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        ★
                    </span>

                    <div>

                        <p class="bc-auth-side-item-title">
                            Proses Seleksi Transparan
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Pantau pemeriksaan
                            administrasi dan
                            hasil seleksi
                            setelah diumumkan.
                        </p>

                    </div>

                </div>

                <!-- INFORMASI 3 -->

                <div class="bc-auth-side-item">

                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        ✦
                    </span>

                    <div>

                        <p class="bc-auth-side-item-title">
                            Akun Pribadi
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Setiap User menggunakan
                            akun sendiri untuk
                            mengakses pengajuan
                            dan informasi hasilnya.
                        </p>

                    </div>

                </div>

            </div>

            <!-- IDENTITAS KELOMPOK -->

            <p class="bc-auth-side-footer">
                Beasiswa Cerdas · Kelompok 4
                · Universitas Pamulang
            </p>

        </aside>

        <!-- ==================================
             PANEL PUTIH KANAN
        =================================== -->

        <section
            class="bc-auth-content bc-auth-register-content"
            aria-labelledby="judul-register"
        >

            <!-- JUDUL -->

            <div>

                <div
                    class="bc-auth-logo"
                    aria-hidden="true"
                >
                    BC
                </div>

                <h2
                    id="judul-register"
                    class="bc-auth-title"
                >
                    Daftar Akun Beasiswa
                </h2>

                <p class="bc-auth-subtitle">
                    Isi informasi berikut
                    untuk membuat akun User
                    Beasiswa Cerdas.
                </p>

            </div>

            <!-- INFORMASI PENDAFTARAN -->

            <div
                class="bc-auth-register-notice"
                role="note"
            >
                Pendaftaran ini khusus untuk

                <strong>
                    calon pendaftar beasiswa (User)
                </strong>.

                Akun Admin tidak dapat dibuat
                melalui formulir ini.
            </div>

            <!-- ==================================
                 FORM REGISTRASI
            =================================== -->

            <form
                id="form-register"
                method="POST"
                action="{{ route('register.proses') }}"
                class="bc-auth-form"
                novalidate
            >

                @csrf

                <!-- ==================================
                     NAMA LENGKAP
                =================================== -->

                <div class="bc-auth-field">

                    <label
                        for="nama_lengkap"
                        class="bc-auth-label"
                    >
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        maxlength="100"
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        required
                        aria-invalid="{{ $errors->has('nama_lengkap') ? 'true' : 'false' }}"
                        aria-describedby="error-nama_lengkap"
                        class="bc-auth-input"
                    >

                    <p
                        id="error-nama_lengkap"
                        class="bc-auth-register-error
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

                <div class="bc-auth-field">

                    <label
                        for="username"
                        class="bc-auth-label"
                    >
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        minlength="3"
                        maxlength="50"
                        autocomplete="username"
                        placeholder="Contoh: siswa_01"
                        required
                        aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                        aria-describedby="error-username"
                        class="bc-auth-input"
                    >

                    <p class="bc-auth-hint">
                        Gunakan 3–50 karakter:
                        huruf, angka, atau
                        garis bawah (_).
                    </p>

                    <p
                        id="error-username"
                        class="bc-auth-register-error
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

                <div class="bc-auth-field">

                    <label
                        for="email"
                        class="bc-auth-label"
                    >
                        Alamat Gmail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        maxlength="254"
                        autocomplete="email"
                        placeholder="contoh@gmail.com"
                        required
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="error-email"
                        class="bc-auth-input"
                    >

                    <p class="bc-auth-hint">
                        Gunakan alamat email
                        berakhiran @gmail.com.
                    </p>

                    <p
                        id="error-email"
                        class="bc-auth-register-error
                               {{ $errors->has('email') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('email') }}
                    </p>

                </div>

                <!-- ==================================
                     KATA SANDI
                =================================== -->

                <div class="bc-auth-field">

                    <label
                        for="password"
                        class="bc-auth-label"
                    >
                        Kata Sandi
                    </label>

                    <div class="bc-auth-password-wrap">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Buat kata sandi"
                            required
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="error-password"
                            class="bc-auth-input"
                        >

                        <!-- TOMBOL IKON MATA -->

                        <button
                            type="button"
                            class="bc-auth-eye"
                            data-password-toggle="password"
                            aria-label="Tampilkan kata sandi"
                            aria-controls="password"
                            aria-pressed="false"
                            title="Tampilkan kata sandi"
                        >

                            <!-- MATA TERBUKA -->

                            <svg
                                class="bc-auth-eye-open"
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

                            <!-- MATA DICORET -->

                            <svg
                                class="bc-auth-eye-closed bc-auth-eye-hidden"
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

                    <p class="bc-auth-hint">
                        Minimal 10 karakter,
                        maksimal 72 karakter,
                        terdiri atas huruf besar,
                        huruf kecil, dan angka.
                    </p>

                    <p
                        id="error-password"
                        class="bc-auth-register-error
                               {{ $errors->has('password') ? '' : 'hidden' }}"
                        role="alert"
                        aria-live="polite"
                    >
                        {{ $errors->first('password') }}
                    </p>

                </div>

                <!-- ==================================
                     KONFIRMASI KATA SANDI
                =================================== -->

                <div class="bc-auth-field">

                    <label
                        for="password_confirmation"
                        class="bc-auth-label"
                    >
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="bc-auth-password-wrap">

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            minlength="10"
                            maxlength="72"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi"
                            required
                            aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                            aria-describedby="error-password_confirmation"
                            class="bc-auth-input"
                        >

                        <!-- IKON MATA KONFIRMASI -->

                        <button
                            type="button"
                            class="bc-auth-eye"
                            data-password-toggle="password_confirmation"
                            aria-label="Tampilkan konfirmasi kata sandi"
                            aria-controls="password_confirmation"
                            aria-pressed="false"
                            title="Tampilkan konfirmasi kata sandi"
                        >

                            <!-- MATA TERBUKA -->

                            <svg
                                class="bc-auth-eye-open"
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

                            <!-- MATA DICORET -->

                            <svg
                                class="bc-auth-eye-closed bc-auth-eye-hidden"
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
                        class="bc-auth-register-error
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
                    class="bc-auth-primary"
                >
                    Daftar Akun User
                </button>

            </form>

            <!-- ==================================
                 SUDAH PUNYA AKUN
            =================================== -->

            <div class="bc-auth-bottom">

                <p>
                    Sudah mempunyai akun?
                </p>

                <a
                    href="{{ route('login') }}"
                    class="bc-auth-secondary bc-auth-link-block"
                >
                    Masuk ke Beasiswa Cerdas
                </a>

            </div>

        </section>

    </div>

</main>

@endsection

<!-- ==========================================
     JAVASCRIPT VALIDASI REGISTRASI
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

    /*
    |--------------------------------------------------------------------------
    | KOLOM YANG DIPERIKSA
    |--------------------------------------------------------------------------
    */

    const daftarKolom = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'password_confirmation'
    ];

    /*
    |--------------------------------------------------------------------------
    | VALIDASI NILAI
    |--------------------------------------------------------------------------
    */

    function cekIsiKolom(namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        if (!kolom) {
            return '';
        }

        const nilai = kolom.value;
        const teks = nilai.trim();

        // Nama lengkap

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

        // Username

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

        // Alamat Gmail

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

        // Kata sandi

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

        // Konfirmasi kata sandi

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

    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN VALIDASI
    |--------------------------------------------------------------------------
    */

    function tampilkanValidasi(namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        const tempatPesan = document.getElementById(
            'error-' + namaKolom
        );

        if (!kolom || !tempatPesan) {
            return true;
        }

        const pesan = cekIsiKolom(namaKolom);

        if (pesan !== '') {

            tempatPesan.textContent = pesan;
            tempatPesan.classList.remove('hidden');

            kolom.setAttribute(
                'aria-invalid',
                'true'
            );

            return false;
        }

        tempatPesan.textContent = '';
        tempatPesan.classList.add('hidden');

        kolom.setAttribute(
            'aria-invalid',
            'false'
        );

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI SAAT MENGETIK DAN KELUAR INPUT
    |--------------------------------------------------------------------------
    */

    daftarKolom.forEach(function (namaKolom) {

        const kolom = document.getElementById(
            namaKolom
        );

        if (!kolom) {
            return;
        }

        kolom.addEventListener('input', function () {

            tampilkanValidasi(namaKolom);

            if (namaKolom === 'password') {

                const konfirmasi = document.getElementById(
                    'password_confirmation'
                );

                if (
                    konfirmasi &&
                    konfirmasi.value !== ''
                ) {
                    tampilkanValidasi(
                        'password_confirmation'
                    );
                }
            }
        });

        kolom.addEventListener('blur', function () {
            tampilkanValidasi(namaKolom);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | VALIDASI SAAT SUBMIT
    |--------------------------------------------------------------------------
    */

    formRegister.addEventListener('submit', function (event) {

        let kolomPertamaSalah = null;

        daftarKolom.forEach(function (namaKolom) {

            const benar = tampilkanValidasi(
                namaKolom
            );

            if (!benar && kolomPertamaSalah === null) {
                kolomPertamaSalah = document.getElementById(
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
    });

    /*
    |--------------------------------------------------------------------------
    | IKON MATA KATA SANDI
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '[data-password-toggle]'
    ).forEach(function (tombol) {

        tombol.addEventListener('click', function () {

            const targetId = tombol.getAttribute(
                'data-password-toggle'
            );

            const input = document.getElementById(
                targetId
            );

            if (!input) {
                return;
            }

            const tampil = input.type === 'password';

            input.type = tampil
                ? 'text'
                : 'password';

            const mataTerbuka = tombol.querySelector(
                '.bc-auth-eye-open'
            );

            const mataTertutup = tombol.querySelector(
                '.bc-auth-eye-closed'
            );

            if (mataTerbuka && mataTertutup) {

                mataTerbuka.classList.toggle(
                    'bc-auth-eye-hidden',
                    tampil
                );

                mataTertutup.classList.toggle(
                    'bc-auth-eye-hidden',
                    !tampil
                );
            }

            const konfirmasi =
                targetId === 'password_confirmation';

            const label = konfirmasi
                ? 'konfirmasi kata sandi'
                : 'kata sandi';

            const keterangan =
                (tampil ? 'Sembunyikan ' : 'Tampilkan ')
                + label;

            tombol.setAttribute(
                'aria-pressed',
                tampil ? 'true' : 'false'
            );

            tombol.setAttribute(
                'aria-label',
                keterangan
            );

            tombol.setAttribute(
                'title',
                keterangan
            );
        });
    });
});
</script>
@endpush
