<?php

declare(strict_types=1);

/**
 * BEASISWA CERDAS
 * AUDIT NAVIGASI ADMIN DAN USER
 *
 * Jalankan:
 * php scripts/cek-navigasi.php
 *
 * Mode baca-saja.
 * Tidak mengubah database atau file website.
 */

$root = dirname(__DIR__);

$autoload = $root . '/vendor/autoload.php';
$bootstrap = $root . '/bootstrap/app.php';
$views = $root . '/resources/views';

if (
    !is_file($autoload) ||
    !is_file($bootstrap) ||
    !is_dir($views)
) {
    fwrite(
        STDERR,
        "File utama Laravel tidak ditemukan.\n"
    );

    exit(1);
}

require $autoload;

try {

    $app = require $bootstrap;

    $app->make(
        \Illuminate\Contracts\Console\Kernel::class
    )->bootstrap();

    $routes = $app['router']->getRoutes();

} catch (Throwable $e) {

    fwrite(
        STDERR,
        "Gagal membaca route Laravel: "
        . $e->getMessage()
        . "\n"
    );

    exit(1);
}

/**
 * ROUTE HALAMAN UTAMA
 *
 * Mengikuti nama route proyek
 * Beasiswa Cerdas yang telah dibuat.
 */

$groups = [

    'Halaman Publik' => [
        'beranda',
        'login',
        'register',
    ],

    'Halaman User' => [
        'user.dashboard',
        'user.pengajuan',
        'user.status',
        'user.profil',
        'user.panduan',
    ],

    'Halaman Admin' => [
        'admin.dashboard',
        'admin.verifikasi.index',
        'admin.calon-penerima.index',
        'admin.ahp.index',
        'admin.waspas.index',
        'admin.hasil.index',
        'admin.laporan.index',
        'admin.seleksi-pengajuan.index',
        'admin.lingkup.index',
        'admin.sensitivitas.index',
        'admin.validasi-penelitian.index',
    ],
];

$errors = [];
$warnings = [];

$passed = 0;
$linked = 0;
$scanned = 0;

/**
 * Nomor baris untuk menemukan
 * lokasi kode yang perlu diperiksa.
 */

function lineNumber(
    string $source,
    int $offset
): int {
    return substr_count(
        substr($source, 0, $offset),
        "\n"
    ) + 1;
}

function relativePath(
    string $path,
    string $root
): string {
    return str_replace(
        '\\',
        '/',
        substr(
            $path,
            strlen($root) + 1
        )
    );
}

echo "\n";
echo "BEASISWA CERDAS - AUDIT NAVIGASI\n";
echo "Mode baca-saja: tidak mengubah data.\n";
echo "\n";

/**
 * 1. PERIKSA MENU UTAMA
 */

foreach ($groups as $group => $names) {

    echo "[{$group}]\n";

    foreach ($names as $name) {

        $route = $routes->getByName($name);

        if ($route === null) {

            $errors[] =
                "Route tidak ditemukan: {$name}";

            echo "  [GAGAL] {$name}\n";

            continue;
        }

        if (
            !in_array(
                'GET',
                $route->methods(),
                true
            )
        ) {

            $errors[] =
                "Menu {$name} bukan halaman GET.";

            echo "  [GAGAL] {$name}\n";

            continue;
        }

        $passed++;

        echo "  [OK] {$name}\n";
    }

    echo "\n";
}

/**
 * 2. PERIKSA FILE BLADE
 *
 * Hanya file *.blade.php aktif.
 * File .bak-* tidak diperiksa.
 */

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $views,
        FilesystemIterator::SKIP_DOTS
    )
);

foreach ($iterator as $entry) {

    if (
        !$entry->isFile() ||
        !str_ends_with(
            $entry->getFilename(),
            '.blade.php'
        )
    ) {
        continue;
    }

    $path = $entry->getPathname();

    $source = file_get_contents($path);

    if ($source === false) {

        $errors[] =
            "File gagal dibaca: {$path}";

        continue;
    }

    $scanned++;

    $file = relativePath(
        $path,
        $root
    );

    /**
     * 3. PERIKSA route('nama.route')
     *
     * Hanya route yang menggunakan
     * nama literal.
     */

    preg_match_all(
        '~\broute\s*\(\s*(["\'])([^"\']+)\1~s',
        $source,
        $foundRoutes,
        PREG_OFFSET_CAPTURE
    );

    foreach ($foundRoutes[2] as $match) {

        [$name, $offset] = $match;

        if (
            str_contains($name, '*') ||
            str_ends_with($name, '.')
        ) {
            continue;
        }

        $linked++;

        if (
            $routes->getByName($name) === null
        ) {

            $errors[] =
                "{$file}:"
                . lineNumber($source, $offset)
                . " menggunakan route "
                . "'{$name}' yang tidak tersedia.";

        } else {

            $passed++;

        }
    }

    /**
     * 4. PERIKSA TEMPLATE BLADE
     */

    preg_match_all(
        '~@(?:extends|include|includeIf)\s*\(\s*(["\'])([^"\']+)\1~',
        $source,
        $foundViews,
        PREG_OFFSET_CAPTURE
    );

    foreach ($foundViews[2] as $match) {

        [$viewName, $offset] = $match;

        if (!view()->exists($viewName)) {

            $errors[] =
                "{$file}:"
                . lineNumber($source, $offset)
                . " memanggil tampilan "
                . "'{$viewName}' yang tidak tersedia.";

        } else {

            $passed++;

        }
    }

    /**
     * 5. PERIKSA LINK KOSONG
     *
     * href="#" terkadang digunakan
     * sebagai tombol sementara.
     */

    preg_match_all(
        '~\bhref\s*=\s*(["\'])\s*#\s*\1~i',
        $source,
        $placeholders,
        PREG_OFFSET_CAPTURE
    );

    foreach ($placeholders[0] as $match) {

        [$value, $offset] = $match;

        $warnings[] =
            "{$file}:"
            . lineNumber($source, $offset)
            . " memiliki tautan href=\"#\".";

    }

    /**
     * 6. PERIKSA KONFIRMASI BROWSER LAMA
     *
     * Mendeteksi:
     * onclick="return confirm(...)"
     * onsubmit="return confirm(...)"
     */

    preg_match_all(
        '~\bon(?:click|submit)\s*=\s*(["\'])[^"\']*\bconfirm\s*\(~i',
        $source,
        $confirms,
        PREG_OFFSET_CAPTURE
    );

    foreach ($confirms[0] as $match) {

        [$value, $offset] = $match;

        $warnings[] =
            "{$file}:"
            . lineNumber($source, $offset)
            . " masih memakai confirm() "
            . "pada atribut HTML.";

    }
}

/**
 * 7. LAPORAN
 */

echo "[Pemeriksaan Tampilan]\n";

echo "  File Blade diperiksa: {$scanned}\n";

echo "  Pemanggilan route: {$linked}\n";

echo "  Referensi valid: {$passed}\n";

echo "\n";

echo "[Masalah]\n";

if ($errors === []) {

    echo "  Tidak ditemukan masalah "
        . "route atau tampilan statis.\n";

} else {

    foreach ($errors as $error) {

        echo "  [GAGAL] {$error}\n";

    }
}

echo "\n";

echo "[Perlu Diperiksa di Browser]\n";

if ($warnings === []) {

    echo "  Tidak ditemukan tautan kosong "
        . "atau konfirmasi browser lama "
        . "pada atribut HTML.\n";

} else {

    foreach ($warnings as $warning) {

        echo "  [CEK] {$warning}\n";

    }
}

echo "\n";

echo "====================================\n";
echo "RINGKASAN AUDIT NAVIGASI\n";
echo "====================================\n";

echo "Masalah   : " . count($errors) . "\n";

echo "Perlu cek : " . count($warnings) . "\n";

echo "\n";

if ($errors === []) {

    echo "PEMERIKSAAN NAVIGASI STATIS LOLOS.\n";

} else {

    echo "ADA REFERENSI YANG PERLU DIPERBAIKI.\n";

}

echo "\n";
echo "Catatan: pemeriksaan ini belum "
    . "menggantikan pengujian klik "
    . "dan tampilan pada browser.\n";

exit($errors === [] ? 0 : 1);
