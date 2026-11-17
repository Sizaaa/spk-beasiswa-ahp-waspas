<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Kelompok 4 Peneliti">
    <title>@yield('title', 'Beasiswa Cerdas - SPK AHP & WASPAS')</title>

    <!-- Browser Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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

    <!-- FOOTER GLOBAL INSTITUTIONAL BRANDING -->
    <footer class="mt-auto border-t border-slate-800 text-slate-300 py-12" style="background: linear-gradient(180deg, #0F1E36 0%, #0A1424 100%);">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-white/10">
                <!-- Col 1: Brand & Sistem Info -->
                <div class="md:col-span-7 space-y-3">
                    @include('partials.app-logo', [
                        'theme' => 'dark',
                        'subtext' => 'Sistem Pendukung Keputusan Kelayakan Penerima Beasiswa',
                        'class' => 'h-10 w-10',
                        'titleClass' => 'text-lg',
                        'subtextClass' => 'text-blue-300',
                    ])
                    <p class="text-xs text-slate-300/80 leading-relaxed max-w-md pt-1">
                        Sistem Pendukung Keputusan Kelayakan Penerima Beasiswa Kurang Mampu berbasis integrasi metode <strong>Analytical Hierarchy Process (AHP)</strong> dan <strong>Weighted Aggregated Sum Product Assessment (WASPAS)</strong>.
                    </p>
                    <div class="flex items-center gap-2 pt-1 text-[11px] font-bold text-blue-300">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-500/20 text-blue-200 border border-blue-400/30">
                            AHP · WASPAS
                        </span>
                        <span class="text-slate-400">·</span>
                        <span class="text-slate-300">Teknik Informatika · Universitas Pamulang</span>
                    </div>
                </div>

                <!-- Col 2: Identitas Kelompok Peneliti PERSIS Sesuai Ketentuan -->
                <div class="md:col-span-5 md:text-right space-y-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-400">
                        Kelompok 4 Peneliti
                    </p>
                    <div class="space-y-1.5 text-xs text-slate-200">
                        <p class="font-medium flex items-center md:justify-end gap-2">
                            <span>Bagas Dwi Prasojo</span>
                            <span class="text-blue-300/70">·</span>
                            <span class="font-mono text-[11px] text-blue-200 font-semibold">241011402570</span>
                        </p>
                        <p class="font-medium flex items-center md:justify-end gap-2">
                            <span>Muhammad Azis</span>
                            <span class="text-blue-300/70">·</span>
                            <span class="font-mono text-[11px] text-blue-200 font-semibold">241011400392</span>
                        </p>
                        <p class="font-medium flex items-center md:justify-end gap-2">
                            <span>Muhammad Asyhadullah</span>
                            <span class="text-blue-300/70">·</span>
                            <span class="font-mono text-[11px] text-blue-200 font-semibold">241011402474</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Subfooter -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <p class="text-[11px] text-slate-400">
                    &copy; {{ date('Y') }} SPK Beasiswa Cerdas. Dikembangkan untuk keperluan penelitian akademik.
                </p>
                <div class="flex items-center gap-4 text-xs font-semibold text-slate-300">
                    <a href="{{ route('beranda') }}" class="hover:text-white transition">Beranda</a>
                    <span class="text-slate-600">·</span>
                    <a href="{{ route('login') }}" class="hover:text-white transition">Portal Masuk</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>