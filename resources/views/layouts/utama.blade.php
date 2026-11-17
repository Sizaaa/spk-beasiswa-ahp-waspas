
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="author"
          content="Bagas Dwi Prasojo, Muhammad Azis, Muhammad Asyhadullah">

    <title>@yield('title', 'Beasiswa Cerdas')</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @stack('styles')
    @include('partials.tema-tombol')

    <!-- Sembunyikan navbar User lama.
         Konten dashboard dan formulir
         tidak diubah. -->
    <style>
        .bc-user-konten > header:first-of-type {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col
             bg-[#F5F8F7] text-slate-800">

    @php
        $menuKhususUser = request()->routeIs('user.*')
            && auth()->check()
            && auth()->user()->role === 'user';
    @endphp

    <div class="flex-1">

        @if ($menuKhususUser)
            @include('partials.navigasi-user')
        @endif

        <div class="{{ $menuKhususUser ? 'bc-user-konten' : '' }}">
            @yield('content')
        </div>

    </div>

    <!-- =====================================
         FOOTER GLOBAL
    ===================================== -->

    <footer class="bg-[#103F36] text-emerald-100">

        <div class="mx-auto max-w-6xl px-5 pt-10 pb-8">

            <!-- INFORMASI UTAMA -->
            <div class="grid gap-9
                        md:grid-cols-2
                        md:gap-12">

                <!-- IDENTITAS WEBSITE -->
                <div class="min-w-0">

                    <div class="flex items-center gap-3">

                        <!-- LOGO BUKU -->
                        <span
                            class="flex h-9 w-9 shrink-0
                                   items-center justify-center
                                   rounded-lg bg-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                class="h-7 w-7"
                                aria-hidden="true"
                            >
                                <path
                                    d="M4.5 5.5H6c2.33 0 4.17.47 6 1.9c1.83-1.43 3.67-1.9 6-1.9h1.5v11.2H18c-2.2 0-4 .35-6 1.8c-2-1.45-3.8-1.8-6-1.8H4.5V5.5Z"
                                    stroke="#264F73"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M12 7.4V18.5"
                                    stroke="#264F73"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M9.1 11.3l2.1 2.1l4-4.4"
                                    stroke="#264F73"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M3.7 7.2H4.5V17.7H6"
                                    stroke="#264F73"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M20.3 7.2H19.5V17.7H18"
                                    stroke="#264F73"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>

                        <h3 class="text-base font-extrabold
                                   text-white">
                            Beasiswa Cerdas
                        </h3>

                    </div>

                    <p class="mt-4 max-w-sm
                              text-sm leading-7
                              text-emerald-100">
                        Sistem Pendukung Keputusan
                        Beasiswa AHP–WASPAS
                    </p>

                </div>

                <!-- IDENTITAS KELOMPOK -->
                <div class="min-w-0 md:text-right">

                    <h3 class="text-base font-bold text-white">
                        Kelompok 4
                    </h3>

                    <p class="mt-1 text-sm text-emerald-200">
                        Universitas Pamulang
                    </p>

                    <p class="mt-4 text-xs font-semibold
                              uppercase tracking-wide
                              text-emerald-200">
                        Dibuat oleh
                    </p>

                    <div class="mt-2 space-y-1
                                text-sm leading-6
                                text-emerald-100">

                        <p>Bagas Dwi Prasojo</p>
                        <p>Muhammad Azis</p>
                        <p>Muhammad Asyhadullah</p>

                    </div>

                </div>

            </div>

            <!-- PEMBATAS -->
            <div class="mt-9 border-t
                        border-white/15 pt-5">

                <div class="flex flex-col
                            items-center justify-between
                            gap-3 text-center
                            md:flex-row md:text-left">

                    <p class="max-w-xl text-xs leading-6
                              text-emerald-200">
                        Data dan proses seleksi pada proyek
                        ini digunakan untuk simulasi akademik.
                    </p>

                    <p class="shrink-0 text-xs
                              text-emerald-200">
                        © {{ date('Y') }}
                        Beasiswa Cerdas · Kelompok 4
                    </p>

                </div>

            </div>

        </div>

    </footer>

    @stack('scripts')

</body>
</html>
