
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

    <!-- FOOTER GLOBAL -->
    <footer class="bg-[#103F36] text-emerald-100 py-8">

        <div class="max-w-6xl mx-auto px-5
                    flex flex-col justify-between
                    gap-6 md:flex-row">

            <div>

                <h3 class="font-bold text-white">
                    ✦ Beasiswa Cerdas
                </h3>

                <p class="text-sm mt-2">
                    Sistem Pendukung Keputusan
                    Beasiswa AHP–WASPAS
                </p>

            </div>

            <div class="text-sm md:text-right">

                <p class="font-bold text-white">
                    Kelompok 4 · Universitas Pamulang
                </p>

                <p class="mt-3 text-emerald-100">
                    Dibuat oleh:
                </p>

                <p class="mt-1">Bagas Dwi Prasojo</p>
                <p class="mt-1">Muhammad Azis</p>
                <p class="mt-1">Muhammad Asyhadullah</p>

            </div>

        </div>

        <div class="max-w-6xl mx-auto px-5 mt-7">

            <div class="border-t border-white/15 pt-5">

                <p class="text-center text-xs text-emerald-200">
                    Data dan proses seleksi pada proyek
                    ini digunakan untuk simulasi akademik.
                </p>

                <p class="text-center text-xs
                          text-emerald-200 mt-2">
                    © {{ date('Y') }}
                    Beasiswa Cerdas · Kelompok 4
                </p>

            </div>

        </div>

    </footer>

    @stack('scripts')

</body>
</html>
