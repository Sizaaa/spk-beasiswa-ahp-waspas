
{{--
|--------------------------------------------------------------------------
| BEASISWA CERDAS
| DESAIN MINIMALIS — PORTAL PUBLIK, USER, DAN ADMIN
|--------------------------------------------------------------------------
|
| Hanya mengatur tampilan.
|
| Tidak mengubah:
| - Konten dan urutan halaman
| - Posisi navbar maupun sidebar
| - Struktur tabel atau formulir
| - Fungsi tombol dan navigasi
| - Controller, route, dan database
| - Perhitungan AHP–WASPAS
| - Keputusan penerima beasiswa
|
| Digunakan bersama oleh:
| layouts/utama.blade.php
| layouts/admin.blade.php
|
--}}

<style>
    /* =========================================
       1. DESIGN TOKENS
    ========================================= */

    :root {
        --bc-green: #103f36;
        --bc-accent: #116759;

        --bc-green-hover: #0d5448;
        --bc-green-soft: #e9f3ee;

        --bc-background: #f6f7f5;
        --bc-surface: #ffffff;
        --bc-surface-muted: #f3f5f3;

        --bc-text: #1c2b27;
        --bc-text-muted: #64736e;

        --bc-border: #e0e6e1;
        --bc-border-strong: #cbd7cf;

        --bc-radius: 12px;
        --bc-radius-small: 8px;

        --bc-shadow:
            0 2px 8px rgba(16, 63, 54, .035);

        --bc-shadow-hover:
            0 4px 12px rgba(16, 63, 54, .055);

        /* Variabel tombol lama tetap tersedia */

        --bc-button-border: #b7cfc1;
        --bc-button-text: #115a48;
        --bc-button-hover: #f0f6f2;
        --bc-button-active: #116759;
    }

    /* =========================================
       2. TIPOGRAFI GLOBAL
    ========================================= */

    body {
        font-family:
            "Segoe UI",
            Arial,
            system-ui,
            sans-serif !important;

        color: var(--bc-text);

        background-color:
            var(--bc-background) !important;

        font-synthesis: none;

        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }

    body :is(h1, h2, h3, h4) {
        letter-spacing: -.018em;
    }

    body :is(p, li, label, td, th) {
        text-wrap: pretty;
    }

    body :is(
        p,
        span,
        td,
        th,
        label
    ) {
        overflow-wrap: break-word;
    }

    /* Teks kecil tetap mudah dibaca */

    .bc-page-description,
    .bc-panel-desc,
    .bc-stat-foot,
    .bc-cell-caption,
    .bc-muted-note {
        color: var(--bc-text-muted);
    }

    /* =========================================
       3. LATAR DAN PERMUKAAN
    ========================================= */

    /*
       Target hanya komponen permukaan.
       Grid, flex, width, height, posisi,
       serta padding tidak diganti.
    */

    main :is(
        section,
        article,
        div
    )[class*="bg-white"][class*="rounded"] {
        border-color:
            var(--bc-border) !important;

        box-shadow:
            var(--bc-shadow) !important;
    }

    main :is(
        section,
        article,
        div
    )[class*="bg-white"][class*="rounded-2xl"] {
        border-radius: 12px !important;
    }

    main :is(
        section,
        article,
        div
    )[class*="bg-white"][class*="rounded-3xl"] {
        border-radius: 15px !important;
    }

    /*
       Elemen yang memakai shadow besar
       dibuat lebih tenang dan realistis.
    */

    main :is(
        .shadow-lg,
        .shadow-xl,
        .shadow-2xl
    ) {
        box-shadow:
            0 4px 16px
            rgba(16, 63, 54, .065) !important;
    }

    /*
       Tidak mengubah panel status:
       diterima, cadangan, ditolak,
       perlu perbaikan, atau peringatan.
    */

    /* =========================================
       4. NAVBAR USER DAN BERANDA
    ========================================= */

    body > div > header,
    .bc-user-konten > header,
    header:has(
        nav[aria-label="Navigasi User"]
    ) {
        border-bottom-color:
            var(--bc-border) !important;
    }

    header:has(
        nav[aria-label="Navigasi User"]
    ) {
        background: #fff !important;

        box-shadow:
            0 1px 3px
            rgba(16, 63, 54, .035) !important;
    }

    /*
       Tetap navbar horizontal.
       Tidak membuat sidebar User.
    */

    header nav a {
        border-radius:
            var(--bc-radius-small);

        transition:
            background-color .15s ease,
            color .15s ease;
    }

    header nav a:not(
        [aria-current="page"]
    ):hover {
        background-color:
            #f0f5f1 !important;
    }

    header nav a[aria-current="page"] {
        background-color:
            var(--bc-accent) !important;

        color: #fff !important;

        box-shadow: none !important;
    }

    /*
       Dropdown mobile tetap menggunakan
       elemen details yang sudah ada.
    */

    header details > div {
        border-color:
            var(--bc-border) !important;

        border-radius: 12px !important;

        box-shadow:
            0 8px 24px
            rgba(16, 63, 54, .09) !important;
    }

    /* =========================================
       5. SIDEBAR ADMIN
    ========================================= */

    /*
       Warna sidebar tetap hijau tua.
       Lebar dan posisinya tidak diubah.
    */

    #adminSidebar {
        background: var(--bc-green) !important;
    }

    .bc-admin-nav {
        border-radius: 8px !important;

        font-weight: 550;

        box-shadow: none !important;

        transition:
            background-color .15s ease,
            color .15s ease !important;
    }

    .bc-admin-nav[aria-current="page"] {
        background:
            rgba(255, 255, 255, .13) !important;

        color: #fff !important;

        box-shadow:
            inset 3px 0 #a4d4ba !important;
    }

    .bc-admin-nav:not(
        [aria-current="page"]
    ):hover {
        background:
            rgba(255, 255, 255, .08) !important;

        box-shadow: none !important;
    }

    .bc-admin-nav-icon {
        opacity: .9;
    }

    /*
       Header Admin tetap di atas
       area konten sebelah kanan.
    */

    #adminSidebar + div > header,
    #adminSidebar ~ div > header {
        border-bottom-color:
            var(--bc-border) !important;
    }

    /* =========================================
       6. JUDUL HALAMAN ADMIN
    ========================================= */

    .bc-page-heading {
        border-color: var(--bc-border);
    }

    .bc-eyebrow {
        color: var(--bc-accent);
        letter-spacing: .075em;
    }

    .bc-page-title {
        color: var(--bc-green);

        font-weight: 750;
        letter-spacing: -.025em;
    }

    .bc-page-description {
        line-height: 1.65;
    }

    /* =========================================
       7. KARTU STATISTIK ADMIN
    ========================================= */

    .bc-stat {
        border: 1px solid
            var(--bc-border) !important;

        border-radius: 12px !important;

        background: var(--bc-surface) !important;

        box-shadow:
            var(--bc-shadow) !important;
    }

    .bc-stat-label {
        color: #596b63;

        font-weight: 550;
    }

    .bc-stat-value {
        color: var(--bc-green);

        font-weight: 750;
        letter-spacing: -.025em;
    }

    .bc-stat-icon {
        border-radius: 9px !important;
    }

    .bc-stat--teal .bc-stat-icon,
    .bc-stat--green .bc-stat-icon {
        color: #12664d;

        background: #e9f3ee;
    }

    /*
       Warna biru dan amber pada statistik
       tetap menjadi pembeda informasi.
       Hanya dibuat lebih lembut.
    */

    .bc-stat--blue .bc-stat-icon {
        color: #365d80;

        background: #edf2f6;
    }

    .bc-stat--amber .bc-stat-icon {
        color: #91621d;

        background: #fbf3e5;
    }

    /* =========================================
       8. PANEL ADMIN
    ========================================= */

    .bc-panel {
        border: 1px solid
            var(--bc-border) !important;

        border-radius: 12px !important;

        background: #fff !important;

        box-shadow:
            var(--bc-shadow) !important;
    }

    .bc-panel-header {
        border-bottom-color:
            var(--bc-border) !important;
    }

    .bc-panel-title {
        color: var(--bc-green);
        font-weight: 750;
    }

    .bc-mini-stat {
        background:
            var(--bc-surface-muted) !important;

        border-color:
            var(--bc-border) !important;

        border-radius: 9px !important;
    }

    .bc-mini-stat-value {
        color: var(--bc-green);
    }

    /* =========================================
       9. PANEL VERIFIKASI ADMIN
    ========================================= */

    /*
       Komponen verifikasi mempunyai
       kelas khusus yang sudah ada.

       Hanya warna, border, radius,
       dan shadow yang diperhalus.
    */

    .vra-card {
        border-color:
            var(--bc-border) !important;

        border-radius: 12px !important;

        box-shadow:
            var(--bc-shadow) !important;
    }

    .vra-head {
        border-color:
            var(--bc-border) !important;
    }

    .vra-note {
        border-radius: 9px !important;
    }

    .vra-input {
        border-radius: 8px !important;
    }

    .vra-btn {
        border-radius: 8px !important;

        box-shadow: none !important;
    }

    .vra-btn:hover {
        transform: none !important;
    }

    /*
       Modal dan dialog verifikasi
       tidak diubah posisi atau ukurannya.
    */

    .vra-dialog {
        border-radius: 12px !important;
    }

    /* =========================================
       10. TABEL ADMIN DAN USER
    ========================================= */

    /*
       Struktur tabel, jumlah kolom,
       urutan dan isi tetap sama.
    */

    .bc-table thead,
    main table thead {
        background:
            #f0f4f1 !important;
    }

    .bc-table thead th,
    main table thead th {
        color: #365349 !important;

        border-color:
            var(--bc-border) !important;

        font-weight: 650;
        letter-spacing: .005em;
    }

    .bc-table tbody td,
    main table tbody td {
        border-bottom-color:
            #e8ede9 !important;
    }

    .bc-table tbody tr:hover,
    main table tbody tr:hover {
        background:
            #f8faf8 !important;
    }

    .bc-cell-title {
        color: var(--bc-green);

        font-weight: 650;
    }

    /*
       Border tabel tidak diberi
       garis gelap atau bayangan besar.
    */

    main table {
        border-color:
            var(--bc-border);
    }

    /* =========================================
       11. INPUT DAN FORMULIR
    ========================================= */

    /*
       Berlaku untuk Login, Registrasi,
       Lupa Kata Sandi, formulir User,
       dan formulir Admin.

       Name, value, required, readonly,
       disabled, dan validasi tidak diubah.
    */

    :is(
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="search"],
        input[type="tel"],
        textarea,
        select
    ) {
        border-radius: 8px !important;

        box-shadow: none !important;

        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    :is(
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="search"],
        input[type="tel"],
        textarea,
        select
    ):not(
        [aria-invalid="true"]
    ):not(
        :disabled
    ):focus {
        border-color:
            var(--bc-accent) !important;

        box-shadow:
            0 0 0 3px
            rgba(17, 103, 89, .10) !important;

        outline: none;
    }

    input::placeholder,
    textarea::placeholder {
        color: #87978e;
        opacity: 1;
    }

    .bc-form-control {
        border-color:
            var(--bc-border-strong);

        border-radius: 8px !important;
    }

    /*
       Input yang salah tetap memakai
       warna error dari halaman asalnya.
    */

    input[aria-invalid="true"],
    textarea[aria-invalid="true"],
    select[aria-invalid="true"] {
        border-color: #d66b6b !important;
    }

    /* =========================================
       12. TOMBOL UTAMA
    ========================================= */

    /*
       Tombol utama:
       hijau solid, tanpa gradient,
       tanpa animasi naik.
    */

    :is(
        a,
        button,
        input[type="submit"],
        input[type="button"]
    ):is(
        .bc-btn--primary,
        .bc-btn-primary,
        .bcf-btn-primary,
        .bcv-btn-primary,
        .bvv-green,
        .bcg-accept,
        .bcc-confirm,

        .bg-emerald-600,
        .bg-emerald-700,
        .bg-emerald-800,
        .bg-green-600,
        .bg-green-700,
        .bg-teal-600,
        .bg-teal-700,

        [class*="bg-[#116759]" i],
        [class*="bg-[#103f36]" i],
        [class*="bg-[#047857]" i],
        [class*="bg-[#065f46]" i],
        [class*="bg-[#059669]" i]
    ):not(:is(
        aside *,
        footer *,
        nav *,
        [data-bc-keep-style],

        .is-danger,
        .is-warning,

        .bvv-danger,
        .bvv-warning,

        .bcg-danger,
        .bcg-warning,

        .bcc-danger,
        .bcc-warning
    )) {
        background-color:
            var(--bc-accent) !important;

        background-image: none !important;

        color: #fff !important;

        border: 1px solid
            var(--bc-accent) !important;

        border-radius: 8px !important;

        box-shadow: none !important;

        text-decoration: none;

        transition:
            background-color .15s ease,
            border-color .15s ease,
            color .15s ease !important;
    }

    :is(
        a,
        button,
        input[type="submit"],
        input[type="button"]
    ):is(
        .bc-btn--primary,
        .bc-btn-primary,
        .bcf-btn-primary,
        .bcv-btn-primary,
        .bvv-green,
        .bcg-accept,
        .bcc-confirm,

        .bg-emerald-600,
        .bg-emerald-700,
        .bg-emerald-800,
        .bg-green-600,
        .bg-green-700,
        .bg-teal-600,
        .bg-teal-700,

        [class*="bg-[#116759]" i],
        [class*="bg-[#103f36]" i],
        [class*="bg-[#047857]" i],
        [class*="bg-[#065f46]" i],
        [class*="bg-[#059669]" i]
    ):not(:is(
        aside *,
        footer *,
        nav *,
        [data-bc-keep-style],

        .is-danger,
        .is-warning,

        .bvv-danger,
        .bvv-warning,

        .bcg-danger,
        .bcg-warning,

        .bcc-danger,
        .bcc-warning
    )):hover:not(:disabled) {
        background-color:
            var(--bc-green-hover) !important;

        border-color:
            var(--bc-green-hover) !important;

        transform: none !important;

        box-shadow: none !important;
    }

    /* =========================================
       13. TOMBOL SEKUNDER
    ========================================= */

    :is(
        a,
        button,
        input[type="submit"],
        input[type="button"]
    ):is(
        .bc-btn--outline,
        .bc-btn-outline,
        .bcf-btn-outline,
        .bcv-btn-outline
    ):not(
        [data-bc-keep-style]
    ) {
        background: #fff !important;

        background-image: none !important;

        color: var(--bc-accent) !important;

        border: 1px solid
            var(--bc-button-border) !important;

        border-radius: 8px !important;

        box-shadow: none !important;
    }

    :is(
        a,
        button,
        input[type="submit"],
        input[type="button"]
    ):is(
        .bc-btn--outline,
        .bc-btn-outline,
        .bcf-btn-outline,
        .bcv-btn-outline
    ):hover:not(:disabled) {
        background: #f0f6f2 !important;

        border-color:
            var(--bc-accent) !important;

        transform: none !important;
    }

    /* =========================================
       14. TOMBOL UMUM
    ========================================= */

    /*
       Tidak mengganti tombol hapus,
       tolak, bahaya, dan peringatan.
    */

    :is(
        button,
        input[type="submit"],
        input[type="button"]
    ):not(:disabled) {
        cursor: pointer;
    }

    :is(
        button,
        a
    ):focus-visible {
        outline:
            2px solid var(--bc-accent);

        outline-offset: 3px;
    }

    button:disabled,
    input:disabled {
        cursor: not-allowed;
    }

    /* =========================================
       15. BADGE DAN STATUS
    ========================================= */

    /*
       Warna semantik tetap dipertahankan:
       - Hijau = terverifikasi / diterima
       - Kuning = menunggu / cadangan
       - Merah = ditolak / tidak diterima
    */

    .bc-status {
        border-radius: 6px !important;

        font-weight: 650;

        box-shadow: none !important;
    }

    .bc-status--terverifikasi {
        color: #166047;

        background: #e8f4eb;
    }

    .bc-status--diajukan {
        color: #48647b;

        background: #edf2f5;
    }

    .bc-status--perlu_perbaikan {
        color: #865a18;

        background: #faf0db;
    }

    .bc-status--ditolak {
        color: #a13f3f;

        background: #faeaea;
    }

    /* =========================================
       16. INFORMASI DAN CATATAN
    ========================================= */

    .bc-notice {
        border-color:
            var(--bc-border) !important;

        border-radius: 8px !important;

        background-color:
            #f5f8f5 !important;
    }

    /*
       Panel informasi hijau tetap hijau,
       hanya menggunakan warna yang tidak
       terlalu menyala.
    */

    main :is(
        section,
        article,
        div
    )[class*="bg-emerald-50"] {
        border-color:
            #d5e8db;
    }

    /*
       Tidak mengubah panel peringatan merah
       maupun kuning.
    */

    /* =========================================
       17. HOVER KARTU
    ========================================= */

    /*
       Tidak memberikan transform/gerakan.
       Tampilan tetap tenang dan profesional.
    */

    main :is(
        section,
        article,
        div
    )[class*="bg-white"][class*="rounded"] {
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    /*
       Kartu tidak dibuat klik otomatis.
       Fungsi klik tetap sesuai kode lama.
    */

    /* =========================================
       18. SCROLLBAR
    ========================================= */

    .bc-admin-sidebar,
    #adminSidebar {
        scrollbar-width: thin;

        scrollbar-color:
            #547e70 transparent;
    }

    .bc-admin-sidebar::-webkit-scrollbar,
    #adminSidebar::-webkit-scrollbar {
        width: 5px;
    }

    .bc-admin-sidebar::-webkit-scrollbar-thumb,
    #adminSidebar::-webkit-scrollbar-thumb {
        background: #547e70;

        border-radius: 5px;
    }

    /* =========================================
       19. KONSISTENSI VISUAL
    ========================================= */

    /*
       Tidak menyentuh:
       display, position, grid-template,
       flex-direction, width, height,
       margin, padding, atau urutan elemen.

       Dengan demikian, tata letak
       lama tetap dipertahankan.
    */

    .bc-btn,
    .bc-btn--primary,
    .bc-btn--outline {
        border-radius: 8px !important;
    }

    .bc-detail-item {
        border-bottom-color:
            var(--bc-border);
    }

    .bc-detail-label {
        color: #6b7c73;
    }

    .bc-detail-value {
        color: var(--bc-text);
    }

    /* =========================================
       20. AKSESIBILITAS
    ========================================= */

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            scroll-behavior: auto !important;

            animation-duration:
                .01ms !important;

            transition-duration:
                .01ms !important;
        }
    }
</style>
