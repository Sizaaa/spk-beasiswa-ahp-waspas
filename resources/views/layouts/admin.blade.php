
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="author"
        content="Kelompok 4 - Universitas Pamulang"
    >

    <title>
        @yield('title', 'Panel Admin - Beasiswa Cerdas')
    </title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        :root {
            --bc-green: #103f36;
            --bc-accent: #116759;
            --bc-border: #e2eae7;
            --bc-muted: #66778b;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background: #f4f8f7;
        }

        /* ========================================
           SCROLLBAR SIDEBAR
        ======================================== */

        .bc-admin-sidebar {
            scrollbar-width: thin;
            scrollbar-color: #5e8b7f transparent;
        }

        .bc-admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .bc-admin-sidebar::-webkit-scrollbar-thumb {
            background: #5e8b7f;
            border-radius: 10px;
        }

        /* ========================================
           NAVIGASI ADMIN
        ======================================== */

        .bc-admin-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
            border-radius: 12px;
            padding: 11px 13px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background .15s ease;
        }

        .bc-admin-nav[aria-current="page"] {
            color: white;
            background: #ffffff26;
            box-shadow: inset 3px 0 #9de5c4;
        }

        .bc-admin-nav:not([aria-current="page"]):hover {
            background: #ffffff13;
        }

        .bc-admin-nav-icon {
            display: inline-flex;
            width: 20px;
            flex-shrink: 0;
            justify-content: center;
            font-size: 17px;
        }

        /* ========================================
           KONTEN UTAMA
        ======================================== */

        .bc-admin-main {
            width: 100%;
            max-width: 1400px;
            margin-inline: auto;
            padding: 28px 26px 44px;
        }

        .bc-page-heading {
            margin-bottom: 24px;
        }

        .bc-eyebrow {
            color: #09836b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .bc-page-title {
            margin-top: 7px;
            font-size: 23px;
            line-height: 1.3;
            font-weight: 800;
            color: var(--bc-green);
        }

        .bc-page-description {
            margin-top: 7px;
            color: var(--bc-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* ========================================
           KARTU STATISTIK
        ======================================== */

        .bc-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 25px;
        }

        .bc-stat {
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 148px;
            padding: 21px 22px 19px;
            border: 1px solid var(--bc-border);
            border-radius: 17px;
            background: white;
            box-shadow: 0 3px 12px rgba(12, 52, 43, .035);
        }

        .bc-stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .bc-stat-label {
            color: #5f7080;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.5;
        }

        .bc-stat-icon {
            display: flex;
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 16px;
        }

        .bc-stat-value {
            margin-top: 13px;
            color: var(--bc-green);
            font-size: 31px;
            font-weight: 800;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .bc-stat-foot {
            margin-top: auto;
            padding-top: 9px;
            color: #728293;
            font-size: 11px;
            line-height: 1.45;
        }

        .bc-stat--teal .bc-stat-icon {
            color: #08755e;
            background: #ddf8ec;
        }

        .bc-stat--blue .bc-stat-icon {
            color: #1e51ba;
            background: #e9f0ff;
        }

        .bc-stat--green .bc-stat-icon {
            color: #087647;
            background: #def9eb;
        }

        .bc-stat--amber .bc-stat-icon {
            color: #a34f00;
            background: #fff1d9;
        }

        /* ========================================
           PANEL HALAMAN
        ======================================== */

        .bc-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--bc-border);
            border-radius: 19px;
            background: white;
            box-shadow: 0 4px 16px rgba(12, 52, 43, .035);
        }

        .bc-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 23px 25px;
            border-bottom: 1px solid #edf1f0;
        }

        .bc-panel-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--bc-green);
            line-height: 1.4;
        }

        .bc-panel-desc {
            margin-top: 5px;
            color: var(--bc-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .bc-panel-body {
            padding: 22px 25px 25px;
        }

        /* ========================================
           TABEL
        ======================================== */

        .bc-table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        .bc-table {
            width: 100%;
            min-width: 630px;
            text-align: left;
            border-collapse: collapse;
            font-size: 13px;
        }

        .bc-table thead {
            background: #f1f5f6;
        }

        .bc-table thead th {
            padding: 14px 16px;
            color: #43566a;
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap;
        }

        .bc-table thead th:first-child {
            border-radius: 9px 0 0 9px;
        }

        .bc-table thead th:last-child {
            border-radius: 0 9px 9px 0;
        }

        .bc-table tbody td {
            padding: 17px 16px;
            border-bottom: 1px solid #edf1f0;
            vertical-align: middle;
            color: #34465a;
        }

        .bc-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .bc-table tbody tr:hover {
            background: #f9fcfb;
        }

        .bc-cell-title {
            font-weight: 750;
            color: #173f37;
        }

        .bc-cell-caption {
            margin-top: 5px;
            color: #738195;
            font-size: 11px;
        }

        /* ========================================
           BADGE STATUS
        ======================================== */

        .bc-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 11px;
            font-size: 11px;
            font-weight: 750;
            line-height: 1.25;
            white-space: nowrap;
        }

        .bc-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .8;
        }

        .bc-status--diajukan {
            color: #225abf;
            background: #e8f0ff;
        }

        .bc-status--terverifikasi {
            color: #087b55;
            background: #def8e9;
        }

        .bc-status--perlu_perbaikan {
            color: #a26000;
            background: #fff3d9;
        }

        .bc-status--ditolak {
            color: #b33232;
            background: #ffebeb;
        }

        /* ========================================
           TOMBOL
        ======================================== */

        .bc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid transparent;
            border-radius: 11px;
            padding: 10px 15px;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.4;
            text-decoration: none;
            transition: transform .1s, background .15s;
            cursor: pointer;
        }

        .bc-btn:hover {
            transform: translateY(-1px);
        }

        .bc-btn:focus-visible,
        .bc-admin-nav:focus-visible {
            outline: 3px solid #a7e9d3;
            outline-offset: 2px;
        }

        .bc-btn--primary {
            background: var(--bc-accent);
            color: white;
        }

        .bc-btn--primary:hover {
            background: #0a5345;
        }

        .bc-btn--outline {
            border-color: #cdded7;
            background: white;
            color: #116759;
        }

        .bc-btn--amber {
            border-color: #f6d79d;
            background: #fff7e7;
            color: #855004;
        }

        .bc-btn--red {
            border-color: #f3c5c5;
            background: #fff0f0;
            color: #a72c2c;
        }

        /* ========================================
           FORM INPUT
        ======================================== */

        .bc-form-control {
            min-height: 42px;
            border: 1px solid #d4e1df;
            border-radius: 11px;
            background: white;
            padding: 9px 12px;
            color: #243c41;
            font-size: 13px;
            outline: none;
        }

        .bc-form-control:focus {
            border-color: #16846d;
            box-shadow: 0 0 0 3px #cdece0;
        }

        /* ========================================
           STATISTIK KECIL
        ======================================== */

        .bc-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .bc-mini-stat {
            padding: 17px;
            border: 1px solid #ecf1ef;
            border-radius: 13px;
            background: #f7faf9;
        }

        .bc-mini-stat-label {
            color: #67788b;
            font-size: 11px;
        }

        .bc-mini-stat-value {
            margin-top: 7px;
            color: var(--bc-green);
            font-size: 24px;
            font-weight: 800;
        }

        /* ========================================
           DETAIL PENDAFTAR
        ======================================== */

        .bc-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            align-items: start;
        }

        .bc-detail-item {
            padding: 13px 0;
            border-bottom: 1px solid #eef2f0;
        }

        .bc-detail-item:last-child {
            border-bottom: 0;
        }

        .bc-detail-label {
            color: #6b7d89;
            font-size: 11px;
            font-weight: 650;
        }

        .bc-detail-value {
            margin-top: 5px;
            color: #1c3b34;
            font-size: 13px;
            font-weight: 650;
            line-height: 1.6;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .bc-notice {
            border: 1px solid #e6eee9;
            border-radius: 12px;
            padding: 14px 16px;
            background: #f6faf8;
            color: #51675e;
            font-size: 12px;
            line-height: 1.7;
        }

        .bc-muted-note {
            color: #788895;
            font-size: 11px;
            line-height: 1.65;
        }

        /* ========================================
           RESPONSIF
        ======================================== */

        @media (max-width: 1199px) {
            .bc-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .bc-admin-main {
                padding: 20px 15px 32px;
            }

            .bc-stats {
                gap: 12px;
            }

            .bc-stat {
                min-height: 130px;
                padding: 15px 14px;
            }

            .bc-stat-value {
                font-size: 26px;
            }

            .bc-stat-label {
                font-size: 12px;
            }

            .bc-panel-header {
                padding: 18px 17px;
            }

            .bc-panel-body {
                padding: 16px 17px 19px;
            }

            .bc-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .bc-stats {
                grid-template-columns: 1fr 1fr;
                gap: 9px;
            }

            .bc-stat {
                padding: 12px;
                min-height: 133px;
                border-radius: 14px;
            }

            .bc-stat-icon {
                width: 26px;
                height: 26px;
            }

            .bc-stat-value {
                font-size: 25px;
            }

            .bc-mini-grid {
                grid-template-columns: 1fr;
            }

            .bc-page-title {
                font-size: 20px;
            }
        }
    </style>

    @stack('styles')

    @include('partials.tema-tombol')
</head>

<body class="min-h-screen text-slate-800">

@php
    $akunAdmin = auth()->user();

    $namaAdmin = $akunAdmin?->nama_lengkap
        ?: ($akunAdmin?->username ?? 'Administrator');

    $jumlahMenunggu = (int) (
        $menunggu ?? ($statistik->menunggu ?? 0)
    );

    $menuPerhitungan = [
        [
            'ikon' => '◎',
            'nama' => 'Perhitungan AHP',
            'route' => 'admin.ahp.index',
            'aktif' => 'admin.ahp.*',
        ],
        [
            'ikon' => '∑',
            'nama' => 'Perhitungan WASPAS',
            'route' => 'admin.waspas.index',
            'aktif' => 'admin.waspas.*',
        ],
        [
            'ikon' => '★',
            'nama' => 'Hasil Seleksi',
            'route' => 'admin.hasil.index',
            'aktif' => 'admin.hasil.*',
        ],
        [
            'ikon' => '▤',
            'nama' => 'Laporan',
            'route' => 'admin.laporan.index',
            'aktif' => 'admin.laporan.*',
        ],
    ];
@endphp

<div class="min-h-screen md:flex">

    <!-- ========================================
         OVERLAY SIDEBAR MOBILE
    ======================================== -->

    <div
        id="adminOverlay"
        class="fixed inset-0 z-40 hidden bg-slate-950/55 md:hidden"
    ></div>

    <!-- ========================================
         SIDEBAR ADMIN
    ======================================== -->

    <aside
        id="adminSidebar"
        aria-label="Sidebar administrator"
        class="bc-admin-sidebar fixed inset-y-0
               left-0 z-50 w-72 -translate-x-full
               overflow-y-auto bg-[#103F36]
               text-white transition-transform
               duration-200 md:sticky md:top-0
               md:h-screen md:shrink-0
               md:translate-x-0"
    >
        <div class="flex min-h-full flex-col">

            <!-- LOGO -->

            <div
                class="flex items-start justify-between
                       gap-3 border-b border-white/10
                       px-5 py-6"
            >
                <div>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="text-lg font-extrabold text-white"
                    >
                        ✦ Beasiswa Cerdas
                    </a>

                    <p class="mt-2 text-xs text-emerald-200">
                        Panel Administrator
                    </p>
                </div>

                <button
                    id="adminSidebarClose"
                    type="button"
                    aria-label="Tutup sidebar"
                    class="rounded-lg p-2 text-xl text-white
                           hover:bg-white/10 md:hidden"
                >
                    ✕
                </button>
            </div>

            <!-- NAVIGASI -->

            <nav
                aria-label="Navigasi Admin"
                class="space-y-1 px-3 py-5"
            >

                <!-- DASHBOARD -->

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="bc-admin-nav text-emerald-100"
                    @if (request()->routeIs('admin.dashboard'))
                        aria-current="page"
                    @endif
                >
                    <span
                        class="bc-admin-nav-icon"
                        aria-hidden="true"
                    >
                        ▦
                    </span>

                    <span>Dashboard Admin</span>
                </a>

                <!-- VERIFIKASI -->

                <a
                    href="{{ route('admin.verifikasi.index') }}"
                    class="bc-admin-nav text-emerald-100"
                    @if (request()->routeIs('admin.verifikasi.*'))
                        aria-current="page"
                    @endif
                >
                    <span
                        class="bc-admin-nav-icon"
                        aria-hidden="true"
                    >
                        ☑
                    </span>

                    <span class="flex-1">
                        Verifikasi Pengajuan
                    </span>

                    @if ($jumlahMenunggu > 0)
                        <span
                            class="rounded-full bg-amber-300
                                   px-2 py-0.5 text-xs
                                   font-bold text-slate-900"
                        >
                            {{ $jumlahMenunggu }}
                        </span>
                    @endif
                </a>

                <!-- DATA CALON PENERIMA -->

                <a
                    href="{{ route('admin.calon-penerima.index') }}"
                    class="bc-admin-nav text-emerald-100"
                    @if (request()->routeIs('admin.calon-penerima.*'))
                        aria-current="page"
                    @endif
                >
                    <span
                        class="bc-admin-nav-icon"
                        aria-hidden="true"
                    >
                        ♙
                    </span>

                    <span>Data Calon Penerima</span>
                </a>

                <div class="my-4 border-t border-white/10"></div>

                <!-- PERHITUNGAN AHP, WASPAS, HASIL, LAPORAN -->

                @foreach ($menuPerhitungan as $menu)
                    <a
                        href="{{ route($menu['route']) }}"
                        class="bc-admin-nav text-emerald-100"
                        @if (request()->routeIs($menu['aktif']))
                            aria-current="page"
                        @endif
                    >
                        <span
                            class="bc-admin-nav-icon"
                            aria-hidden="true"
                        >
                            {{ $menu['ikon'] }}
                        </span>

                        <span>{{ $menu['nama'] }}</span>
                    </a>
                @endforeach

                <div class="my-4 border-t border-white/10"></div>

                <!-- BERANDA PUBLIK -->

                <a
                    href="{{ route('beranda') }}"
                    class="bc-admin-nav text-emerald-100"
                >
                    <span
                        class="bc-admin-nav-icon"
                        aria-hidden="true"
                    >
                        ⌂
                    </span>

                    <span>Lihat Beranda Publik</span>
                </a>

                <!-- LOGOUT -->

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="bc-admin-nav w-full text-left
                               text-red-200 hover:bg-white/10"
                    >
                        <span
                            class="bc-admin-nav-icon"
                            aria-hidden="true"
                        >
                            ↪
                        </span>

                        <span>Logout</span>
                    </button>
                </form>

            </nav>

            <!-- FOOTER SIDEBAR -->

            <footer
                class="mt-auto px-5 pb-7 pt-5"
                aria-label="Identitas kelompok pengembang"
            >
                <div class="border-t border-white/15 pt-5">

                    <h2 class="text-sm font-bold text-white">
                        Kelompok 4
                    </h2>

                    <p class="mt-1 text-xs text-emerald-200">
                        Universitas Pamulang
                    </p>

                    <div
                        class="mt-4 space-y-2 text-xs
                               leading-5 text-emerald-50"
                    >
                        <p>Bagas Dwi Prasojo</p>
                        <p>Muhammad Azis</p>
                        <p>Muhammad Asyhadullah</p>
                    </div>

                </div>
            </footer>

        </div>
    </aside>

    <!-- ========================================
         KONTEN KANAN ADMIN
    ======================================== -->

    <div class="min-w-0 flex-1">

        <!-- ====================================
             HEADER ADMIN SERAGAM
        ==================================== -->

        <header
            class="sticky top-0 z-30 w-full
                   border-b border-slate-200
                   bg-white/95 px-5 py-4
                   backdrop-blur lg:flex
                   lg:min-h-[136px] lg:items-center
                   lg:px-8"
        >
            <div
                class="flex w-full flex-wrap
                       items-center justify-between
                       gap-4 lg:flex-nowrap"
            >

                <!-- JUDUL HALAMAN -->

                <div
                    class="flex min-w-0 flex-1
                           items-center gap-3"
                >
                    <button
                        id="adminSidebarOpen"
                        type="button"
                        aria-label="Buka navigasi Admin"
                        aria-controls="adminSidebar"
                        aria-expanded="false"
                        class="rounded-xl border
                               border-emerald-200
                               px-3 py-2 text-xl
                               text-[#103F36] md:hidden"
                    >
                        ☰
                    </button>

                    <div class="min-w-0 flex-1">

                        <p
                            class="text-[11px] font-bold
                                   uppercase tracking-widest
                                   text-emerald-700"
                        >
                            PANEL ADMINISTRATOR
                        </p>

                        <h1
                            class="mt-1 text-xl
                                   font-extrabold leading-tight
                                   text-[#103F36] md:text-2xl"
                        >
                            @yield('judul-admin', 'Dashboard Admin')
                        </h1>

                        <p
                            class="mt-2 text-xs leading-relaxed
                                   text-slate-500 sm:text-sm"
                        >
                            @yield(
                                'deskripsi-admin',
                                'Pengelolaan sistem beasiswa.'
                            )
                        </p>

                    </div>
                </div>

                <!-- PROFIL ADMIN -->

                <div class="flex shrink-0 items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0
                               items-center justify-center
                               rounded-full bg-emerald-100
                               text-sm font-bold text-[#105447]"
                    >
                        AD
                    </div>

                    <div class="min-w-0">

                        <p
                            class="max-w-36 truncate
                                   text-sm font-bold text-slate-800"
                        >
                            {{ $namaAdmin }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Administrator
                        </p>

                    </div>
                </div>

            </div>
        </header>

        <!-- KONTEN HALAMAN ADMIN -->

        @yield('admin-content')

    </div>

</div>

<!-- ========================================
     JAVASCRIPT SIDEBAR ADMIN
======================================== -->

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

        open.setAttribute(
            'aria-expanded',
            String(isOpen)
        );

        document.body.classList.toggle(
            'overflow-hidden',
            isOpen
        );
    }

    open.addEventListener('click', function () {
        toggleSidebar(true);
    });

    close.addEventListener('click', function () {
        toggleSidebar(false);
    });

    overlay.addEventListener('click', function () {
        toggleSidebar(false);
    });

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
