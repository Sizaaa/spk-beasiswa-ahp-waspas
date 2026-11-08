
{{--
    BEASISWA CERDAS
    TEMA TOMBOL TANPA KEDIPAN

    CSS bekerja sejak halaman pertama dirender.
    Tidak menggunakan JavaScript untuk mengganti warna.

    Tombol biasa       : putih + garis hijau
    Navigasi aktif     : hijau penuh
    Bahaya/peringatan  : warna khusus
    Sidebar dan footer : tidak diubah
--}}

<style>
    :root {
        --bc-button-border: #a9d3c3;
        --bc-button-text: #115a48;
        --bc-button-hover: #f0faf5;
        --bc-button-active: #116759;
    }

    /*
       WARNA DASAR TOMBOL

       Menargetkan nama kelas yang sudah
       dipakai pada berbagai halaman website.

       Hanya berlaku untuk tombol dan tautan,
       bukan kartu atau label status.
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

        .bg-emerald-500,
        .bg-emerald-600,
        .bg-emerald-700,
        .bg-emerald-800,
        .bg-emerald-900,

        .bg-green-500,
        .bg-green-600,
        .bg-green-700,
        .bg-green-800,

        .bg-teal-500,
        .bg-teal-600,
        .bg-teal-700,

        .from-emerald-700,
        .from-green-700,

        [class*="bg-[#116759]" i],
        [class*="bg-[#103f36]" i],
        [class*="bg-[#18584b]" i],
        [class*="bg-[#0e5145]" i],
        [class*="bg-[#0b5c47]" i],
        [class*="bg-[#119468]" i],
        [class*="bg-[#0f766e]" i],
        [class*="bg-[#0e7660]" i],
        [class*="bg-[#047857]" i],
        [class*="bg-[#065f46]" i],
        [class*="bg-[#059669]" i],

        [style*="background: #116759" i],
        [style*="background:#116759" i],
        [style*="background-color: #116759" i],
        [style*="background-color:#116759" i]
    ):not(:is(
        aside *,
        footer *,

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

        background: #ffffff !important;

        background-image: none !important;

        color: var(--bc-button-text) !important;

        border: 1px solid
            var(--bc-button-border) !important;

        box-shadow:
            0 2px 7px
            rgba(17, 90, 72, .035) !important;

        text-decoration: none;

        transition:
            background-color .2s ease,
            border-color .2s ease,
            color .2s ease,
            box-shadow .2s ease,
            transform .2s ease !important;
    }

    /*
       ANIMASI HOVER TOMBOL BIASA

       Tidak ada perubahan warna setelah
       halaman dimuat. Warna hover hanya
       berubah saat kursor berada di tombol.
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

        .bg-emerald-500,
        .bg-emerald-600,
        .bg-emerald-700,
        .bg-emerald-800,
        .bg-emerald-900,

        .bg-green-500,
        .bg-green-600,
        .bg-green-700,
        .bg-green-800,

        .bg-teal-500,
        .bg-teal-600,
        .bg-teal-700,

        .from-emerald-700,
        .from-green-700,

        [class*="bg-[#116759]" i],
        [class*="bg-[#103f36]" i],
        [class*="bg-[#18584b]" i],
        [class*="bg-[#0e5145]" i],
        [class*="bg-[#0b5c47]" i],
        [class*="bg-[#119468]" i],
        [class*="bg-[#0f766e]" i],
        [class*="bg-[#0e7660]" i],
        [class*="bg-[#047857]" i],
        [class*="bg-[#065f46]" i],
        [class*="bg-[#059669]" i],

        [style*="background: #116759" i],
        [style*="background:#116759" i],
        [style*="background-color: #116759" i],
        [style*="background-color:#116759" i]
    ):not(:is(
        aside *,
        footer *,

        [data-bc-keep-style],

        .is-danger,
        .is-warning,

        .bvv-danger,
        .bvv-warning,

        .bcg-danger,
        .bcg-warning,

        .bcc-danger,
        .bcc-warning
    )):hover:not(:disabled):not([aria-disabled="true"]) {

        background:
            var(--bc-button-hover) !important;

        background-image: none !important;

        color: #0a4c39 !important;

        border-color: #68b495 !important;

        box-shadow:
            0 7px 18px
            rgba(17, 90, 72, .09) !important;

        transform: translateY(-2px);
    }

    /*
       NAVIGASI / TAB AKTIF

       Hijau penuh hanya untuk halaman aktif.

       aria-current="page" biasanya sudah
       diberikan oleh Blade berdasarkan route.

       Tidak perlu menunggu JavaScript.
    */

    :is(
        nav,
        [role="tablist"],
        .bc-tabs,
        .bc-button-group
    )
    :is(
        a,
        button,
        [role="tab"]
    ):is(
        [aria-current="page"],
        [aria-selected="true"],
        [data-active="true"]
    ):not(:is(
        aside *,
        footer *,
        [data-bc-keep-style]
    )) {

        background:
            var(--bc-button-active) !important;

        background-image: none !important;

        border: 1px solid
            var(--bc-button-active) !important;

        color: #ffffff !important;

        box-shadow:
            0 5px 14px
            rgba(17, 103, 89, .12) !important;
    }

    /*
       HOVER NAVIGASI AKTIF
    */

    :is(
        nav,
        [role="tablist"],
        .bc-tabs,
        .bc-button-group
    )
    :is(
        a,
        button,
        [role="tab"]
    ):is(
        [aria-current="page"],
        [aria-selected="true"],
        [data-active="true"]
    ):not(:is(
        aside *,
        footer *,
        [data-bc-keep-style]
    )):hover {

        background: #0e5145 !important;

        background-image: none !important;

        border-color: #0e5145 !important;

        color: #ffffff !important;

        transform: translateY(-1px);
    }

    /*
       AKSESIBILITAS:
       Nonaktifkan animasi jika pengguna
       memilih pengurangan gerakan.
    */

    @media (prefers-reduced-motion: reduce) {

        :is(
            a,
            button,
            input[type="submit"],
            input[type="button"]
        ) {

            transition-duration: 0s !important;
        }
    }
</style>
