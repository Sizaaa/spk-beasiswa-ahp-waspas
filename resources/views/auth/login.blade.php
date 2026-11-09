
@extends('layouts.utama')

@section('title', 'Login - Beasiswa Cerdas')

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
            href="{{ route('beranda') }}"
            class="bc-auth-back"
        >
            ← Kembali ke Beranda
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

                <h1 class="bc-auth-side-title">
                    Selamat datang kembali.
                </h1>

                <p class="bc-auth-side-desc">
                    Masuk untuk mengelola pengajuan,
                    memantau proses seleksi,
                    serta melihat keputusan akhir
                    beasiswa berdasarkan sistem
                    AHP dan WASPAS.
                </p>
            </div>

            <div class="bc-auth-side-list">

                <div class="bc-auth-side-item">
                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        ✓
                    </span>

                    <div>
                        <p class="bc-auth-side-item-title">
                            Pengajuan Terpantau
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Informasi pengajuan dan
                            pemeriksaan administrasi
                            dalam satu portal.
                        </p>
                    </div>
                </div>

                <div class="bc-auth-side-item">
                    <span
                        class="bc-auth-side-icon"
                        aria-hidden="true"
                    >
                        ★
                    </span>

                    <div>
                        <p class="bc-auth-side-item-title">
                            Hasil Seleksi Transparan
                        </p>

                        <p class="bc-auth-side-item-desc">
                            Keputusan akhir ditampilkan
                            setelah diumumkan oleh Admin.
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

        <section
            class="bc-auth-content"
            aria-labelledby="judul-login"
        >

            <div class="bc-auth-logo" aria-hidden="true">
                BC
            </div>

            <h2
                id="judul-login"
                class="bc-auth-title"
            >
                Masuk ke Akun
            </h2>

            <p class="bc-auth-subtitle">
                Masukkan username atau email
                dan kata sandi yang terdaftar.
            </p>

            <!-- PESAN BERHASIL -->

            @if (session('sukses'))
                <div
                    class="bc-auth-alert bc-auth-alert-success"
                    role="status"
                >
                    {{ session('sukses') }}
                </div>
            @endif

            <!-- INFORMASI SAAT SUDAH LOGIN -->

            @auth
                <div
                    class="bc-auth-alert bc-auth-alert-success"
                    role="status"
                >
                    Kamu sedang login sebagai

                    <strong>
                        {{ auth()->user()->nama_lengkap
                            ?: auth()->user()->username }}
                    </strong>.

                    <p class="mt-1">
                        Kamu dapat memasukkan akun lain
                        untuk berganti akun.
                    </p>
                </div>
            @endauth

            <!-- PESAN ERROR -->

            @if ($errors->any())
                <div
                    class="bc-auth-alert bc-auth-alert-error"
                    role="alert"
                >
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- FORM LOGIN -->

            <form
                method="POST"
                action="{{ route('login.proses') }}"
                class="bc-auth-form"
            >
                @csrf

                <div class="bc-auth-field">
                    <label
                        for="login"
                        class="bc-auth-label"
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
                        class="bc-auth-input"
                    >
                </div>

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
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            required
                            class="bc-auth-input"
                        >

                        <button
                            type="button"
                            class="bc-auth-eye"
                            data-password-toggle="password"
                            aria-label="Tampilkan kata sandi"
                            aria-controls="password"
                            aria-pressed="false"
                            title="Tampilkan kata sandi"
                        >

                            <!-- IKON MATA -->

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

                            <!-- IKON MATA DICORET -->

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

                    <div class="bc-auth-forgot">
                        <a href="{{ route('password.forgot') }}">
                            Lupa Kata Sandi?
                        </a>
                    </div>
                </div>

                <button
                    type="submit"
                    class="bc-auth-primary"
                >
                    Masuk ke Beasiswa Cerdas
                </button>

            </form>

            <!-- DAFTAR AKUN -->

            <div class="bc-auth-bottom">
                <p>Belum mempunyai akun?</p>

                <a
                    href="{{ route('register') }}"
                    class="bc-auth-secondary bc-auth-link-block"
                >
                    Daftar Akun User
                </a>
            </div>

            <!-- LOGOUT SAAT SUDAH LOGIN -->

            @auth
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="mt-4 text-center"
                >
                    @csrf

                    <button
                        type="submit"
                        class="text-xs font-bold text-red-700 underline"
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
