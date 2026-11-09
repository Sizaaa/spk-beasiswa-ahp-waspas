
@extends('layouts.utama')

@section('title', 'Lupa Kata Sandi - Beasiswa Cerdas')

@php
    $tahapAktif = $tahap
        ?? (!empty($emailTerkirim) ? 'kode' : 'email');

    if (!in_array(
        $tahapAktif,
        ['email', 'kode', 'password'],
        true
    )) {
        $tahapAktif = 'email';
    }
@endphp

@push('styles')
    @include('partials.auth-style')
@endpush

@section('content')

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

<main class="bc-auth-page">
    <div class="bc-auth-card">

        <!-- PANEL KIRI -->

        <aside class="bc-auth-side">

            <div>
                <p class="bc-auth-kicker">
                    ✦ PORTAL BEASISWA CERDAS
                </p>

                <h2 class="bc-auth-side-title">
                    Pulihkan akses akun
                    dengan aman.
                </h2>

                <p class="bc-auth-side-desc">
                    Gunakan Gmail terdaftar
                    untuk mendapatkan kode
                    verifikasi dan membuat
                    kata sandi baru.
                </p>
            </div>

            <div class="bc-auth-side-list">

                <div class="bc-auth-side-item">
                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        1
                    </span>

                    <div>
                        <p class="bc-auth-side-item-title">
                            Gmail Terdaftar
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Masukkan alamat Gmail
                            yang digunakan saat
                            membuat akun.
                        </p>
                    </div>
                </div>

                <div class="bc-auth-side-item">
                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        2
                    </span>

                    <div>
                        <p class="bc-auth-side-item-title">
                            Verifikasi Kode
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Masukkan kode enam digit
                            yang masih berlaku.
                        </p>
                    </div>
                </div>

                <div class="bc-auth-side-item">
                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        3
                    </span>

                    <div>
                        <p class="bc-auth-side-item-title">
                            Kata Sandi Baru
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Setelah kode benar,
                            buat kata sandi baru
                            untuk akun kamu.
                        </p>
                    </div>
                </div>

            </div>

            <p class="bc-auth-side-footer">
                Beasiswa Cerdas · Kelompok 4
                · Universitas Pamulang
            </p>

        </aside>

        <!-- PANEL KANAN -->

        <section class="bc-auth-content">

            <div class="bc-auth-logo" aria-hidden="true">
                BC
            </div>

            <p class="bc-auth-eyebrow">
                @if ($tahapAktif === 'email')
                    TAHAP 01 / 03
                @elseif ($tahapAktif === 'kode')
                    TAHAP 02 / 03
                @else
                    TAHAP 03 / 03
                @endif
            </p>

            <h1 class="bc-auth-title">

                @if ($tahapAktif === 'email')
                    Lupa Kata Sandi?
                @elseif ($tahapAktif === 'kode')
                    Verifikasi Kode
                @else
                    Buat Kata Sandi Baru
                @endif

            </h1>

            <p class="bc-auth-subtitle">

                @if ($tahapAktif === 'email')
                    Masukkan Gmail yang terdaftar
                    untuk menerima kode verifikasi.

                @elseif ($tahapAktif === 'kode')
                    Masukkan kode enam digit.
                    Kata sandi baru hanya dapat
                    dibuat setelah kode benar.

                @else
                    Kode sudah berhasil diverifikasi.
                    Sekarang buat kata sandi baru
                    untuk akun Beasiswa Cerdas.
                @endif

            </p>

            <!-- PESAN SUKSES -->

            @if (session('sukses'))
                <div
                    class="bc-auth-alert bc-auth-alert-success"
                    role="status"
                >
                    {{ session('sukses') }}
                </div>
            @endif

            <!-- PESAN ERROR -->

            @if ($errors->any())
                <div
                    class="bc-auth-alert bc-auth-alert-error"
                    role="alert"
                >
                    <strong>
                        Periksa kembali formulir:
                    </strong>

                    @foreach ($errors->all() as $error)
                        <p class="mt-1">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <!-- ==================================
                 TAHAP 1 — MASUKKAN GMAIL
            =================================== -->

            @if ($tahapAktif === 'email')

                <form
                    method="POST"
                    action="{{ route('password.send') }}"
                    class="bc-auth-form"
                >
                    @csrf

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
                            value="{{ old('email', '') }}"
                            autocomplete="email"
                            maxlength="254"
                            placeholder="nama@gmail.com"
                            required
                            autofocus
                            class="bc-auth-input"
                        >

                        <p class="bc-auth-hint">
                            Gunakan alamat @gmail.com
                            yang terdaftar pada akunmu.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="bc-auth-primary"
                    >
                        Kirim Kode Verifikasi
                    </button>
                </form>

            <!-- ==================================
                 TAHAP 2 — VERIFIKASI KODE
            =================================== -->

            @elseif ($tahapAktif === 'kode')

                <div class="bc-auth-email">
                    <small>Gmail yang digunakan</small>

                    <strong>
                        {{ $emailTerkirim }}
                    </strong>
                </div>

                <form
                    method="POST"
                    action="{{ route('password.verify') }}"
                    class="bc-auth-form"
                >
                    @csrf

                    <div class="bc-auth-field">
                        <label
                            for="kode"
                            class="bc-auth-label"
                        >
                            Kode Verifikasi 6 Digit
                        </label>

                        <input
                            type="text"
                            id="kode"
                            name="kode"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            minlength="6"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="000000"
                            required
                            autofocus
                            class="bc-auth-input bc-auth-code"
                        >

                        <p class="bc-auth-hint">
                            Kode berlaku selama
                            10 menit dan maksimal
                            5 kali kesalahan.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="bc-auth-primary"
                    >
                        Verifikasi Kode
                    </button>
                </form>

                <!-- KIRIM ULANG KODE -->

                <div class="bc-auth-resend">
                    <form
                        method="POST"
                        action="{{ route('password.send') }}"
                    >
                        @csrf

                        <label
                            for="email-ulang"
                            class="bc-auth-label"
                        >
                            Kirim ulang atau ganti Gmail
                        </label>

                        <input
                            type="email"
                            id="email-ulang"
                            name="email"
                            value="{{ $emailTerkirim }}"
                            autocomplete="email"
                            maxlength="254"
                            required
                            class="bc-auth-input"
                        >

                        <button
                            type="submit"
                            class="bc-auth-secondary"
                        >
                            Kirim Ulang Kode
                        </button>
                    </form>
                </div>

            <!-- ==================================
                 TAHAP 3 — KATA SANDI BARU
            =================================== -->

            @elseif ($tahapAktif === 'password')

                <div class="bc-auth-email">
                    <small>Gmail sudah diverifikasi</small>

                    <strong>
                        {{ $emailTerkirim }}
                    </strong>
                </div>

                <form
                    method="POST"
                    action="{{ route('password.reset') }}"
                    class="bc-auth-form"
                >
                    @csrf

                    <!-- KATA SANDI BARU -->

                    <div class="bc-auth-field">

                        <label
                            for="password-baru"
                            class="bc-auth-label"
                        >
                            Kata Sandi Baru
                        </label>

                        <div class="bc-auth-password-wrap">

                            <input
                                type="password"
                                id="password-baru"
                                name="password"
                                autocomplete="new-password"
                                minlength="10"
                                maxlength="72"
                                placeholder="Masukkan kata sandi baru"
                                required
                                class="bc-auth-input"
                            >

                            <button
                                type="button"
                                class="bc-auth-eye"
                                data-password-toggle="password-baru"
                                aria-controls="password-baru"
                                aria-label="Tampilkan kata sandi"
                                aria-pressed="false"
                                title="Tampilkan kata sandi"
                            >

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
                                        d="M17.479 17.499A10.75 10.75 0 0 1 2.062 12.35a1 1 0 0 1 0-.7 10.7 10.7 0 0 1-4.446-5.143"
                                    />
                                    <path d="m2 2 20 20" />
                                </svg>

                            </button>
                        </div>

                        <p class="bc-auth-hint">
                            Minimal 10 karakter,
                            terdiri atas huruf besar,
                            huruf kecil, dan angka.
                        </p>
                    </div>

                    <!-- KONFIRMASI KATA SANDI -->

                    <div class="bc-auth-field">

                        <label
                            for="password-konfirmasi"
                            class="bc-auth-label"
                        >
                            Konfirmasi Kata Sandi Baru
                        </label>

                        <div class="bc-auth-password-wrap">

                            <input
                                type="password"
                                id="password-konfirmasi"
                                name="password_confirmation"
                                autocomplete="new-password"
                                minlength="10"
                                maxlength="72"
                                placeholder="Ulangi kata sandi baru"
                                required
                                class="bc-auth-input"
                            >

                            <button
                                type="button"
                                class="bc-auth-eye"
                                data-password-toggle="password-konfirmasi"
                                aria-controls="password-konfirmasi"
                                aria-label="Tampilkan konfirmasi kata sandi"
                                aria-pressed="false"
                                title="Tampilkan konfirmasi kata sandi"
                            >

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

                    </div>

                    <button
                        type="submit"
                        class="bc-auth-primary"
                    >
                        Simpan Kata Sandi Baru
                    </button>

                </form>

                <div class="bc-auth-note">
                    Setelah kata sandi disimpan,
                    kamu akan kembali ke halaman
                    Login dan dapat masuk menggunakan
                    kata sandi baru.
                </div>

            @endif

            <!-- KEMBALI KE LOGIN -->

            <div class="bc-auth-bottom">
                Sudah ingat kata sandi?

                <a href="{{ route('login') }}">
                    Kembali ke Login
                </a>
            </div>

        </section>

    </div>
</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    document.querySelectorAll(
        '[data-password-toggle]'
    ).forEach(function (tombol) {

        tombol.addEventListener('click', function () {

            const target = tombol.getAttribute(
                'data-password-toggle'
            );

            const input = document.getElementById(target);

            if (!input) {
                return;
            }

            const tampil = input.type === 'password';

            input.type = tampil ? 'text' : 'password';

            const mataBiasa = tombol.querySelector(
                '.bc-auth-eye-open'
            );

            const mataDicoret = tombol.querySelector(
                '.bc-auth-eye-closed'
            );

            mataBiasa?.classList.toggle(
                'bc-auth-eye-hidden',
                tampil
            );

            mataDicoret?.classList.toggle(
                'bc-auth-eye-hidden',
                !tampil
            );

            const label = tampil
                ? 'Sembunyikan kata sandi'
                : 'Tampilkan kata sandi';

            tombol.setAttribute('aria-label', label);
            tombol.setAttribute('title', label);
            tombol.setAttribute(
                'aria-pressed',
                tampil ? 'true' : 'false'
            );

        });

    });
});
</script>
@endpush
