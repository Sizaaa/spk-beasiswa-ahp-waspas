<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Kelompok 4 - Universitas Pamulang">
    <title>@yield('title', 'Beasiswa Cerdas - SPK AHP & WASPAS')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        :root {
            --bc-green: #103F36;
            --bc-green-hover: #0B2C26;
            --bc-accent: #116759;
            --bc-mint: #20A67A;
            --bc-mint-light: #E8F7F2;
            --bc-bg: #F5F8F6;
            --bc-surface: #FFFFFF;
            --bc-border: #E3EBE6;
            --bc-text-primary: #172B25;
            --bc-text-secondary: #64746D;
            --bc-muted: #84968F;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bc-bg);
            color: var(--bc-text-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Sembunyikan navbar User lama jika ada di dalam view anak */
        .bc-user-konten > header:first-of-type {
            display: none !important;
        }
    </style>

    @stack('styles')
    @include('partials.tema-tombol')
</head>

<body class="min-h-full flex flex-col bg-[#F5F8F6] text-[#172B25]">

    @php
        $menuKhususUser = request()->routeIs('user.*')
            && auth()->check()
            && auth()->user()->role === 'user';
    @endphp

    <div class="flex-1 flex flex-col">
        @if ($menuKhususUser)
            @include('partials.navigasi-user')
        @endif

        <div class="flex-1 {{ $menuKhususUser ? 'bc-user-konten' : '' }}">
            @yield('content')
        </div>
    </div>

    <!-- FOOTER GLOBAL PREMIUM SAAS -->
    <footer class="mt-auto border-t border-white/10 text-emerald-100/90 py-12" style="background: linear-gradient(180deg, #103F36 0%, #0A2620 100%);">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-white/10">
                <!-- Col 1: Brand & Desc -->
                <div class="md:col-span-6 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#20A67A] to-[#116759] text-white shadow-md">
                            ✦
                        </div>
                        <span class="text-lg font-extrabold text-white tracking-tight">
                            Beasiswa Cerdas
                        </span>
                    </div>
                    <p class="text-xs text-emerald-200/80 leading-relaxed max-w-md">
                        Sistem Pendukung Keputusan (SPK) Seleksi Penerima Beasiswa Berprestasi dan Kurang Mampu Berbasis Integrasi Metode Analytical Hierarchy Process (AHP) dan Weighted Aggregated Sum Product Assessment (WASPAS).
                    </p>
                    <div class="flex items-center gap-2 pt-1 text-[11px] text-emerald-300 font-semibold">
                        <span class="inline-block w-2 h-2 rounded-full bg-[#20A67A]"></span>
                        <span>Universitas Pamulang · Sistem Informasi Manajemen</span>
                    </div>
                </div>

                <!-- Col 2: Tim Pengembang -->
                <div class="md:col-span-6 md:text-right space-y-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-300">
                        Kelompok 4 Peneliti
                    </p>
                    <div class="space-y-1 text-xs text-emerald-100">
                        <p class="font-medium">Bagas Dwi Prasojo <span class="text-emerald-300/60 font-mono text-[11px]">(221011400262)</span></p>
                        <p class="font-medium">Muhammad Azis <span class="text-emerald-300/60 font-mono text-[11px]">(221011401344)</span></p>
                        <p class="font-medium">Muhammad Asyhadullah <span class="text-emerald-300/60 font-mono text-[11px]">(221011401211)</span></p>
                    </div>
                </div>
            </div>

            <!-- Bottom Subfooter -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <p class="text-[11px] text-emerald-300/70">
                    &copy; {{ date('Y') }} Beasiswa Cerdas. Dikembangkan untuk keperluan penelitian akademik dan perancangan skripsi.
                </p>
                <div class="flex items-center gap-4 text-xs font-semibold text-emerald-200">
                    <a href="{{ route('beranda') }}" class="hover:text-white transition">Beranda</a>
                    <span class="text-emerald-400/40">·</span>
                    <a href="{{ route('login') }}" class="hover:text-white transition">Portal Masuk</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>