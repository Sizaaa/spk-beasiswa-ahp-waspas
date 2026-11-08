<?php

declare(strict_types=1);

/**
 * BEASISWA CERDAS
 * Perapian empat konfirmasi lama.
 *
 * Jalankan:
 * php scripts/rapikan-konfirmasi-admin.php
 *
 * Tidak mengubah controller, route,
 * database, atau perhitungan.
 */

$root = dirname(__DIR__);

$targets = [
    'resources/views/admin/ahp.blade.php',
    'resources/views/admin/calon-penerima.blade.php',
    'resources/views/admin/riwayat-seleksi.blade.php',
    'resources/views/admin/seleksi-pengajuan.blade.php',
];

// ========================================
// 1. PERIKSA MODAL GLOBAL
// ========================================

$globalFile = $root .
    '/resources/views/partials/konfirmasi-global.blade.php';

$global = is_file($globalFile)
    ? file_get_contents($globalFile)
    : false;

if (
    $global === false ||
    !str_contains($global, 'data-bc-confirm') ||
    !str_contains($global, 'bcc-overlay')
) {
    fwrite(
        STDERR,
        "Komponen modal global yang sesuai belum ditemukan.\n"
        . "Tidak ada file yang diubah.\n"
    );

    exit(1);
}

// Pastikan modal global terpasang di layout Admin.

$providerFile = $root .
    '/app/Providers/AppServiceProvider.php';

$layoutFile = $root .
    '/resources/views/layouts/admin.blade.php';

$provider = is_file($providerFile)
    ? file_get_contents($providerFile)
    : false;

$layout = is_file($layoutFile)
    ? file_get_contents($layoutFile)
    : false;

$viaProvider =
    is_string($provider) &&
    is_string($layout) &&
    str_contains(
        $provider,
        'partials.konfirmasi-global'
    ) &&
    str_contains(
        $layout,
        "@stack('scripts')"
    );

$viaLayout =
    is_string($layout) &&
    (
        str_contains(
            $layout,
            "@include('partials.konfirmasi-global')"
        ) ||
        str_contains(
            $layout,
            '@include("partials.konfirmasi-global")'
        )
    );

if (!$viaProvider && !$viaLayout) {
    fwrite(
        STDERR,
        "Pemasangan modal global pada layout Admin "
        . "belum terdeteksi.\n"
        . "Tidak ada file yang diubah.\n"
    );

    exit(1);
}

// ========================================
// 2. POLA KONFIRMASI YANG AMAN DIUBAH
// ========================================

/*
 * Hanya menangani bentuk:
 *
 * onclick="return confirm('Pesan');"
 *
 * atau:
 *
 * onsubmit="return confirm('Pesan');"
 *
 * Jika terdapat JavaScript lain dalam
 * atribut tersebut, tidak akan diganti.
 */

$pattern = <<<'REGEX'
~\b(onclick|onsubmit)\s*=\s*"return\s+(?:window\.)?confirm\s*\(\s*'([^']*)'\s*\)\s*;?\s*"~i
REGEX;

$changes = [];

// ========================================
// 3. PERIKSA KEEMPAT FILE SEBELUM MENGUBAH
// ========================================

foreach ($targets as $relative) {

    $path = $root . '/' . $relative;

    if (
        !is_file($path) ||
        ($original = file_get_contents($path)) === false
    ) {
        fwrite(
            STDERR,
            "File tidak ditemukan: {$relative}\n"
            . "Tidak ada file yang diubah.\n"
        );

        exit(1);
    }

    $count = preg_match_all(
        $pattern,
        $original
    );

    if ($count !== 1) {

        fwrite(
            STDERR,
            "Periksa manual: {$relative}\n"
            . "Pola cocok {$count} kali, seharusnya 1.\n"
            . "Tidak ada file yang diubah.\n"
        );

        exit(1);
    }

    $updated = preg_replace_callback(
        $pattern,

        static function (array $match): string {

            $message = htmlspecialchars(
                $match[2],
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            );

            return 'data-bc-confirm="' .
                $message .
                '"';
        },

        $original,
        1
    );

    if (
        $updated === null ||
        $updated === $original
    ) {
        fwrite(
            STDERR,
            "Gagal memproses: {$relative}\n"
        );

        exit(1);
    }

    $changes[$path] = [
        'relative' => $relative,
        'original' => $original,
        'updated' => $updated,
    ];
}

// ========================================
// 4. SIMPAN CADANGAN DI LUAR PROYEK
// ========================================

$home = getenv('USERPROFILE')
    ?: getenv('HOME');

if (!$home) {
    fwrite(
        STDERR,
        "Folder pengguna tidak ditemukan.\n"
    );

    exit(1);
}

$backupDir =
    rtrim($home, '/\\') .
    '/Documents/Cadangan-Beasiswa-Cerdas/' .
    'konfirmasi-' . date('Ymd-His');

if (
    !is_dir($backupDir) &&
    !mkdir($backupDir, 0775, true) &&
    !is_dir($backupDir)
) {
    fwrite(
        STDERR,
        "Gagal membuat folder cadangan.\n"
    );

    exit(1);
}

foreach ($changes as $path => $change) {

    $backup = $backupDir .
        '/' . basename($path) . '.backup';

    if (
        file_put_contents(
            $backup,
            $change['original'],
            LOCK_EX
        ) === false
    ) {
        fwrite(
            STDERR,
            "Gagal membuat cadangan.\n"
            . "File website belum diubah.\n"
        );

        exit(1);
    }
}

// ========================================
// 5. SIMPAN PERUBAHAN
// ========================================

$written = [];

foreach ($changes as $path => $change) {

    if (
        file_put_contents(
            $path,
            $change['updated'],
            LOCK_EX
        ) === false
    ) {

        // Pulihkan file jika terjadi masalah.

        foreach ($written as $done) {

            file_put_contents(
                $done,
                $changes[$done]['original'],
                LOCK_EX
            );
        }

        fwrite(
            STDERR,
            "Gagal menyimpan. "
            . "Perubahan sebelumnya dipulihkan.\n"
        );

        exit(1);
    }

    $written[] = $path;

    echo '[OK] ' .
        $change['relative'] .
        "\n";
}

echo "\n";
echo "Selesai: 4 konfirmasi diperbarui.\n";
echo "Cadangan: {$backupDir}\n";
echo "Tidak ada file .bak baru di folder Admin.\n";
