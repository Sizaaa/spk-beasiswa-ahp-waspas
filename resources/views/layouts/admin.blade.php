<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Kelompok 4 - Universitas Pamulang">
    <title>@yield('title', 'Administrasi Beasiswa - Beasiswa Cerdas')</title>

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

        /* Custom Scrollbar for Sidebar */
        .bc-admin-sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.18) transparent;
        }
        .bc-admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .bc-admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
        }
        .bc-admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Nav Item Styles */
        .bc-admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #A1B8B0;
            text-decoration: none;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .bc-admin-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.07);
            transform: translateX(2px);
        }
        .bc-admin-nav-item[aria-current="page"] {
            color: #FFFFFF;
            font-weight: 700;
            background: linear-gradient(90deg, rgba(32, 166, 122, 0.22) 0%, rgba(32, 166, 122, 0.08) 100%);
            box-shadow: inset 3px 0 0 #20A67A;
        }
        .bc-admin-nav-item[aria-current="page"] .bc-nav-icon-wrap {
            color: #20A67A;
            background: rgba(32, 166, 122, 0.18);
        }

        .bc-nav-icon-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            font-size: 14px;
            color: #8CAEA2;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }
        .bc-admin-nav-item:hover .bc-nav-icon-wrap {
            background: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
        }

        /* Main Container */
        .bc-admin-main {
            width: 100%;
            max-width: 1440px;
            margin-inline: auto;
            padding: 28px 30px 52px;
        }

        /* Consistent Panel System */
        .bc-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--bc-border);
            border-radius: 16px;
            background: var(--bc-surface);
            box-shadow: 0 4px 20px -2px rgba(16, 63, 54, 0.05), 0 2px 6px -1px rgba(16, 63, 54, 0.02);
            transition: box-shadow 0.2s ease;
        }
        .bc-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid #EEF3F0;
            background: #FAFCFB;
        }
        .bc-panel-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--bc-green);
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bc-panel-desc {
            margin-top: 4px;
            color: var(--bc-text-secondary);
            font-size: 12px;
            line-height: 1.6;
        }
        .bc-panel-body {
            padding: 24px;
        }

        /* Stat Grid */
        .bc-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }
        .bc-stat {
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 20px 22px;
            border: 1px solid var(--bc-border);
            border-radius: 16px;
            background: #FFFFFF;
            box-shadow: 0 2px 10px rgba(16, 63, 54, 0.04);
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .bc-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 63, 54, 0.08);
            border-color: #CFDED8;
        }
        .bc-stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }
        .bc-stat-label {
            color: var(--bc-text-secondary);
            font-size: 13px;
            font-weight: 600;
        }
        .bc-stat-icon {
            display: flex;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 17px;
        }
        .bc-stat-value {
            margin-top: 14px;
            color: var(--bc-green);
            font-size: 32px;
            font-weight: 800;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.02em;
        }
        .bc-stat-foot {
            margin-top: auto;
            padding-top: 12px;
            color: var(--bc-muted);
            font-size: 11px;
            line-height: 1.5;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .bc-stat--teal .bc-stat-icon {
            color: #0B6B58;
            background: #E6F7F2;
        }
        .bc-stat--blue .bc-stat-icon {
            color: #1A56DB;
            background: #EBF2FE;
        }
        .bc-stat--green .bc-stat-icon {
            color: #0E7044;
            background: #E8F8EE;
        }
        .bc-stat--amber .bc-stat-icon {
            color: #B45309;
            background: #FEF3C7;
        }

        @media (max-width: 1024px) {
            .bc-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 640px) {
            .bc-admin-main {
                padding: 18px 16px 36px;
            }
            .bc-stats {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }
    </style>

    @stack('styles')
    @include('partials.tema-tombol')
</head>

<body class="min-h-full">

@php
    $akunAdmin = auth()->user();
    $namaAdmin = $akunAdmin?->nama_lengkap ?: ($akunAdmin?->username ?? 'Administrator');
    $jumlahMenunggu = (int) ($menunggu ?? ($statistik->menunggu ?? 0));
@endphp

<div class="min-h-screen flex flex-col md:flex-row">

    <!-- OVERLAY SIDEBAR MOBILE -->
    <div
        id="adminOverlay"
        class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-xs transition-opacity md:hidden"
    ></div>

    <!-- SIDEBAR ADMIN PREMIUM SAAS -->
    <aside
        id="adminSidebar"
        aria-label="Sidebar administrator"
        class="bc-admin-sidebar fixed inset-y-0 left-0 z-50 w-72 -translate-x-full overflow-y-auto
               border-r border-white/10 text-white transition-transform duration-250 ease-in-out
               md:sticky md:top-0 md:h-screen md:shrink-0 md:translate-x-0 flex flex-col"
        style="background: linear-gradient(180deg, #0A2620 0%, #103F36 50%, #0D332C 100%);"
    >
        <!-- BRAND LOGO & SYSTEM IDENTIFIER -->
        <div class="flex items-center justify-between border-b border-white/10 px-6 py-5 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#20A67A] to-[#116759] shadow-lg shadow-[#20A67A]/25 border border-white/20">
                    <span class="text-white text-lg font-extrabold">✦</span>
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight text-white block leading-tight">
                        Beasiswa Cerdas
                    </span>
                    <span class="inline-flex items-center gap-1.5 mt-0.5 text-[11px] font-semibold text-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#20A67A]"></span>
                        SPK AHP–WASPAS
                    </span>
                </div>
            </a>

            <button
                id="adminSidebarClose"
                type="button"
                aria-label="Tutup sidebar"
                class="rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white md:hidden"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- NAVIGATION LINKS -->
        <nav aria-label="Navigasi Admin" class="flex-1 overflow-y-auto px-4 py-4 space-y-6">

            <!-- SECTION: MENU UTAMA -->
            <div>
                <p class="px-3 pb-2 text-[10px] font-extrabold tracking-wider text-[#759B90] uppercase">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">▦</span>
                        <span class="flex-1">Dashboard</span>
                    </a>

                    <a
                        href="{{ route('admin.verifikasi.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.verifikasi.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">☑</span>
                        <span class="flex-1">Verifikasi Pengajuan</span>
                        @if ($jumlahMenunggu > 0)
                            <span class="rounded-full bg-[#20A67A] px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm">
                                {{ $jumlahMenunggu }}
                            </span>
                        @endif
                    </a>

                    <a
                        href="{{ route('admin.calon-penerima.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.calon-penerima.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">♙</span>
                        <span class="flex-1">Data Calon Penerima</span>
                    </a>

                    <a
                        href="{{ route('admin.seleksi-pengajuan.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.seleksi-pengajuan.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">⇶</span>
                        <span class="flex-1">Pengajuan Siap Seleksi</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: METODE SPK & PENGAMBILAN KEPUTUSAN -->
            <div>
                <p class="px-3 pb-2 text-[10px] font-extrabold tracking-wider text-[#759B90] uppercase">
                    Metode & Analisis SPK
                </p>
                <div class="space-y-1">
                    <a
                        href="{{ route('admin.lingkup.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.lingkup.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">⚙</span>
                        <span class="flex-1">Lingkup Seleksi</span>
                    </a>

                    <a
                        href="{{ route('admin.ahp.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.ahp.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">◎</span>
                        <span class="flex-1">Perhitungan AHP</span>
                    </a>

                    <a
                        href="{{ route('admin.waspas.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.waspas.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">∑</span>
                        <span class="flex-1">Perhitungan WASPAS</span>
                    </a>

                    <a
                        href="{{ route('admin.hasil.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.hasil.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">★</span>
                        <span class="flex-1">Hasil & Penetapan</span>
                    </a>

                    <a
                        href="{{ route('admin.sensitivitas.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.sensitivitas.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">📈</span>
                        <span class="flex-1">Analisis Sensitivitas</span>
                    </a>

                    <a
                        href="{{ route('admin.validasi-penelitian.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.validasi-penelitian.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">✓</span>
                        <span class="flex-1">Validasi Penelitian</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: DOKUMENTASI & LAPORAN -->
            <div>
                <p class="px-3 pb-2 text-[10px] font-extrabold tracking-wider text-[#759B90] uppercase">
                    Arsip & Laporan
                </p>
                <div class="space-y-1">
                    <a
                        href="{{ route('admin.riwayat.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.riwayat.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">⏱</span>
                        <span class="flex-1">Riwayat Seleksi</span>
                    </a>

                    <a
                        href="{{ route('admin.laporan.index') }}"
                        class="bc-admin-nav-item"
                        @if (request()->routeIs('admin.laporan.*')) aria-current="page" @endif
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">▤</span>
                        <span class="flex-1">Laporan & Cetak</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: NAVIGASI SISTEM -->
            <div>
                <p class="px-3 pb-2 text-[10px] font-extrabold tracking-wider text-[#759B90] uppercase">
                    Sistem
                </p>
                <div class="space-y-1">
                    <a
                        href="{{ route('beranda') }}"
                        class="bc-admin-nav-item"
                    >
                        <span class="bc-nav-icon-wrap" aria-hidden="true">⌂</span>
                        <span class="flex-1">Lihat Beranda Publik</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="bc-admin-nav-item w-full text-left text-red-300 hover:text-red-100 hover:bg-red-950/40"
                        >
                            <span class="bc-nav-icon-wrap !text-red-300" aria-hidden="true">↪</span>
                            <span class="flex-1">Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>

        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="mt-auto border-t border-white/10 p-4 shrink-0 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/10 font-bold text-xs text-emerald-200">
                    K4
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">Kelompok 4</p>
                    <p class="text-[11px] text-emerald-300/80 truncate">Universitas Pamulang</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- KONTEN KANAN ADMIN -->
    <div class="min-w-0 flex-1 flex flex-col">

        <!-- TOPBAR ADMIN SAAS (HEIGHT ~72px, MINIMALIST & CRISP) -->
        <header
            class="sticky top-0 z-30 w-full border-b border-[#E3EBE6] bg-white/90 backdrop-blur-md px-6 py-3.5 transition-all"
        >
            <div class="flex items-center justify-between gap-4">

                <!-- KIRI: HAMBURGER & BREADCRUMB / JUDUL -->
                <div class="flex items-center gap-3.5 min-w-0">
                    <button
                        id="adminSidebarOpen"
                        type="button"
                        aria-label="Buka navigasi Admin"
                        aria-controls="adminSidebar"
                        aria-expanded="false"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#E3EBE6] text-[#103F36] hover:bg-[#F5F8F6] md:hidden shrink-0"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#116759]">
                                Administrasi SPK
                            </span>
                            <span class="text-slate-300">/</span>
                            <span class="text-xs font-medium text-slate-500 truncate">
                                @yield('judul-admin', 'Dashboard Admin')
                            </span>
                        </div>
                        <h1 class="text-lg font-extrabold text-[#103F36] leading-tight truncate mt-0.5">
                            @yield('judul-admin', 'Dashboard Admin')
                        </h1>
                    </div>
                </div>

                <!-- KANAN: STATUS SISTEM & PROFIL ADMIN -->
                <div class="flex shrink-0 items-center gap-4">

                    <!-- Live Indicator -->
                    <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200/60 text-[11px] font-semibold text-emerald-800">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>Sistem Aktif</span>
                    </div>

                    <!-- Divider -->
                    <div class="hidden sm:block h-6 w-px bg-slate-200"></div>

                    <!-- Admin Profile Card -->
                    <div class="flex items-center gap-3 pl-1">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-[#103F36] to-[#20A67A] text-white text-xs font-extrabold shadow-sm">
                            AD
                        </div>
                        <div class="hidden md:block min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate leading-tight">
                                {{ $namaAdmin }}
                            </p>
                            <p class="text-[10px] font-semibold text-[#116759] uppercase tracking-wide">
                                Super Administrator
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <!-- KONTEN UTAMA HALAMAN ADMIN -->
        <main class="flex-1 bg-[#F5F8F6]">
            @yield('admin-content')
        </main>

    </div>

</div>

<!-- JAVASCRIPT SIDEBAR RESPONSIVE -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');
    const open = document.getElementById('adminSidebarOpen');
    const close = document.getElementById('adminSidebarClose');

    function toggleSidebar(isOpen) {
        if (window.innerWidth >= 768) {
            isOpen = false;
        }

        sidebar.classList.toggle('-translate-x-full', !isOpen);
        sidebar.classList.toggle('translate-x-0', isOpen);
        overlay.classList.toggle('hidden', !isOpen);

        if (open) {
            open.setAttribute('aria-expanded', String(isOpen));
        }

        document.body.classList.toggle('overflow-hidden', isOpen);
    }

    if (open) {
        open.addEventListener('click', function () {
            toggleSidebar(true);
        });
    }

    if (close) {
        close.addEventListener('click', function () {
            toggleSidebar(false);
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            toggleSidebar(false);
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            toggleSidebar(false);
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            toggleSidebar(false);
        }
    });
});
</script>

@stack('scripts')

</body>
</html>