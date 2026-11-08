
@extends('layouts.utama')

@section('title', 'Profil Akun - Beasiswa Cerdas')

@section('content')

<main class="mx-auto max-w-4xl px-5 py-10">

    <p class="text-xs font-bold uppercase
              tracking-wide text-emerald-700">
        Portal Pendaftar
    </p>

    <h1 class="mt-2 text-3xl font-extrabold
               text-[#103F36]">
        Profil / Data Akun
    </h1>

    <p class="mt-3 text-sm leading-7 text-slate-600">
        Informasi akun yang digunakan untuk
        mengakses Beasiswa Cerdas.
    </p>

    <section class="mt-7 overflow-hidden rounded-2xl
                    border border-slate-100
                    bg-white shadow-sm">

        <div class="border-b border-slate-100 p-6">

            <h2 class="text-lg font-bold text-[#103F36]">
                Identitas Akun
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Informasi ini dibaca dari
                akun yang sedang login.
            </p>

        </div>

        <dl class="divide-y divide-slate-100 px-6">

            <div class="grid gap-2 py-5 sm:grid-cols-3">

                <dt class="text-sm text-slate-500">
                    Nama Lengkap
                </dt>

                <dd class="break-words text-sm
                           font-semibold sm:col-span-2">
                    {{ $akun->nama_lengkap ?: '-' }}
                </dd>

            </div>

            <div class="grid gap-2 py-5 sm:grid-cols-3">

                <dt class="text-sm text-slate-500">
                    Username
                </dt>

                <dd class="break-words text-sm
                           font-semibold sm:col-span-2">
                    {{ $akun->username }}
                </dd>

            </div>

            <div class="grid gap-2 py-5 sm:grid-cols-3">

                <dt class="text-sm text-slate-500">
                    Email
                </dt>

                <dd class="break-all text-sm
                           font-semibold sm:col-span-2">
                    {{ $akun->email ?: '-' }}
                </dd>

            </div>

            <div class="grid gap-2 py-5 sm:grid-cols-3">

                <dt class="text-sm text-slate-500">
                    Jenis Akun
                </dt>

                <dd class="text-sm font-semibold
                           sm:col-span-2">
                    Pendaftar (User)
                </dd>

            </div>

        </dl>

    </section>

    <div class="mt-6 rounded-2xl border
                border-blue-100 bg-blue-50
                p-5 text-sm leading-7 text-blue-900">

        Halaman profil ini hanya menampilkan
        informasi akun.

        Perubahan email, nama, dan kata sandi
        belum tersedia agar sistem login yang
        sudah berjalan tidak berubah tanpa
        pengujian.

    </div>

    <a
        href="{{ route('user.dashboard') }}"
        class="mt-7 inline-block rounded-xl
               bg-[#116759] px-5 py-3
               text-sm font-bold text-white"
    >
        Kembali ke Dashboard
    </a>

</main>

@endsection
