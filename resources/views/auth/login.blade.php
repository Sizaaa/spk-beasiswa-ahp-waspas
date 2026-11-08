
@extends('layouts.utama')

@section('title', 'Login - Beasiswa Cerdas')

@section('content')

<!-- NAVBAR -->
<header class="bg-white border-b border-emerald-100">

    <div class="max-w-6xl mx-auto px-5 py-5
                flex items-center justify-between gap-4">

        <a href="{{ route('beranda') }}"
           class="text-xl font-extrabold text-[#105447]">

            ✦ Beasiswa Cerdas

        </a>

        <a href="{{ route('beranda') }}"
           class="text-sm font-semibold text-emerald-700">

            Kembali ke Beranda

        </a>

    </div>

</header>

<!-- FORM LOGIN -->
<main class="max-w-md mx-auto px-5 py-16">

    <div class="bg-white border border-slate-200
                rounded-3xl shadow-sm p-7">

        <div class="text-center">

            <div class="w-14 h-14 mx-auto
                        rounded-2xl bg-emerald-100
                        text-[#105447] flex
                        items-center justify-center
                        text-2xl font-bold">

                BC

            </div>

            <h1 class="text-2xl font-extrabold
                       text-[#103F36] mt-5">

                Masuk ke Beasiswa Cerdas

            </h1>

            <p class="text-sm text-slate-500 mt-3">

                Gunakan akun Admin atau User
                yang sudah terdaftar.

            </p>

        </div>

        <!-- PESAN SUKSES -->
        @if (session('sukses'))

            <div class="bg-emerald-50 border
                        border-emerald-200
                        text-emerald-800
                        p-4 rounded-xl mt-6 text-sm">

                {{ session('sukses') }}

            </div>

        @endif

        <!-- INFORMASI AKUN AKTIF -->
        @auth

            <div class="bg-blue-50 border
                        border-blue-200 rounded-xl
                        p-4 mt-6 text-sm text-blue-900">

                Kamu sedang login sebagai

                <strong>
                    {{ auth()->user()->nama_lengkap
                        ?: auth()->user()->username }}
                </strong>.

                <p class="mt-2">

                    Kamu bisa memasukkan akun lain
                    untuk berganti akun.

                </p>

            </div>

        @endauth

        <!-- PESAN ERROR -->
        @if ($errors->any())

            <div class="bg-red-50 border border-red-200
                        text-red-800 rounded-xl
                        p-4 mt-6 text-sm">

                {{ $errors->first() }}

            </div>

        @endif

        <!-- FORM LOGIN -->
        <form
            method="POST"
            action="{{ route('login.proses') }}"
            class="space-y-5 mt-7"
        >

            @csrf

            <!-- USERNAME / EMAIL -->
            <div>

                <label for="login"
                       class="block text-sm font-semibold
                              text-slate-700 mb-2">

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
                    class="w-full border border-slate-300
                           rounded-xl px-4 py-3
                           focus:outline-none
                           focus:border-emerald-600"
                >

            </div>

            <!-- PASSWORD -->
            <div>

                <label for="password"
                       class="block text-sm font-semibold
                              text-slate-700 mb-2">

                    Password

                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    required
                    class="w-full border border-slate-300
                           rounded-xl px-4 py-3
                           focus:outline-none
                           focus:border-emerald-600"
                >

                <label class="flex items-center gap-2
                              mt-3 text-sm text-slate-600">

                    <input
                        type="checkbox"
                        id="lihat_password"
                    >

                    Tampilkan password

                </label>

            </div>

            <!-- TOMBOL MASUK -->
            <button
                type="submit"
                class="w-full bg-[#116759]
                       hover:bg-[#0E5247]
                       text-white font-bold
                       rounded-xl px-5 py-3"
            >

                Masuk

            </button>

        </form>

        <!-- LINK REGISTRASI -->
        <div class="mt-7 border-t border-slate-200 pt-6">

            <p class="text-center text-sm text-slate-600">

                Belum mempunyai akun?

            </p>

            <a
                href="{{ route('register') }}"
                class="mt-3 block w-full rounded-xl
                       border border-emerald-600
                       px-5 py-3 text-center
                       font-bold text-emerald-700
                       hover:bg-emerald-50"
            >

                Daftar Akun User

            </a>

        </div>

        <!-- LOGOUT UNTUK GANTI AKUN -->
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

    </div>

</main>

<!-- JAVASCRIPT PASSWORD -->
<script>

    const tampilPassword =
        document.getElementById('lihat_password');

    const inputPassword =
        document.getElementById('password');

    tampilPassword.addEventListener('change', function () {

        inputPassword.type =
            this.checked ? 'text' : 'password';

    });

</script>

@endsection
