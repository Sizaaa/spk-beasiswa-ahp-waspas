
@extends('layouts.utama')

@section('title', 'Dashboard ' . $peran . ' - Beasiswa Cerdas')

@section('content')

<!-- NAVBAR -->
<header class="bg-white border-b border-emerald-100">

    <div class="max-w-6xl mx-auto px-5 py-5
                flex flex-wrap items-center
                justify-between gap-4">

        <a href="{{ route('beranda') }}"
           class="text-xl font-extrabold text-[#105447]">

            ✦ Beasiswa Cerdas

        </a>

        <div class="flex items-center gap-4">

            <a href="{{ route('beranda') }}"
               class="text-sm font-semibold text-slate-600">

                Beranda

            </a>

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="bg-red-50 text-red-700
                               font-bold px-4 py-2
                               rounded-xl text-sm">

                    Logout

                </button>

            </form>

        </div>

    </div>

</header>

<!-- DASHBOARD -->
<main class="max-w-5xl mx-auto px-5 py-12">

    <div class="bg-[#103F36] text-white
                rounded-3xl p-8">

        <p class="text-sm text-emerald-200">

            PORTAL {{ strtoupper($peran) }}

        </p>

        <h1 class="text-3xl font-extrabold mt-3">

            Selamat Datang,
            {{ auth()->user()->nama_lengkap
                ?: auth()->user()->username }}

        </h1>

        <p class="mt-4 text-emerald-100">

            Kamu berhasil masuk menggunakan
            sistem autentikasi Laravel.

        </p>

    </div>

    <!-- DATA AKUN -->
    <section class="bg-white border border-slate-200
                    rounded-2xl p-7 mt-6">

        <h2 class="text-xl font-bold text-[#103F36]">

            Informasi Akun

        </h2>

        <div class="grid md:grid-cols-2 gap-5 mt-6">

            <div class="bg-slate-50 p-4 rounded-xl">

                <p class="text-xs text-slate-500">
                    Username
                </p>

                <p class="font-bold mt-2">

                    {{ auth()->user()->username }}

                </p>

            </div>

            <div class="bg-slate-50 p-4 rounded-xl">

                <p class="text-xs text-slate-500">
                    Role
                </p>

                <p class="font-bold mt-2">

                    {{ ucfirst(auth()->user()->role) }}

                </p>

            </div>

            <div class="bg-slate-50 p-4 rounded-xl
                        md:col-span-2">

                <p class="text-xs text-slate-500">
                    Email
                </p>

                <p class="font-bold mt-2">

                    {{ auth()->user()->email ?: 'Belum tersedia' }}

                </p>

            </div>

        </div>

    </section>

    <!-- INFORMASI MIGRASI -->
    <section class="mt-6 bg-amber-50
                    border border-amber-200
                    rounded-2xl p-6">

        <h2 class="font-bold text-amber-900">

            Dashboard Sementara

        </h2>

        @if ($peran === 'Admin')

            <p class="text-sm text-amber-900 mt-2
                      leading-7">

                Login Admin sudah menggunakan Laravel.
                Dashboard Admin, verifikasi pengajuan,
                CRUD, AHP, dan WASPAS akan dipindahkan
                dari proyek PHP Native berikutnya.

            </p>

        @else

            <p class="text-sm text-amber-900 mt-2
                      leading-7">

                Login User sudah menggunakan Laravel.
                Dashboard User, formulir pengajuan,
                status pendaftaran, dan dokumen
                akan dipindahkan berikutnya.

            </p>

        @endif

    </section>

</main>

@endsection
