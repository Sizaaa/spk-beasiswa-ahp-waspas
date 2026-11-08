<?php

declare(strict_types=1);

/**
 * BEASISWA CERDAS
 * PERAPIAN BAHASA TAMPILAN ADMIN
 *
 * Pratinjau:
 * php scripts/rapikan-bahasa-admin.php --preview
 *
 * Terapkan:
 * php scripts/rapikan-bahasa-admin.php --apply
 *
 * Tidak mengubah:
 * - Database
 * - Route dan controller
 * - Nama variabel PHP
 * - Rumus AHP dan WASPAS
 * - JavaScript dan CSS
 */

$mode = $argv[1] ?? '--preview';

if (!in_array($mode, ['--preview', '--apply'], true)) {
    fwrite(
        STDERR,
        "Gunakan --preview atau --apply.\n"
    );

    exit(1);
}

$root = dirname(__DIR__);

$folder = $root . '/resources/views/admin';

$layout = $root .
    '/resources/views/layouts/admin.blade.php';

if (!is_dir($folder)) {
    fwrite(
        STDERR,
        "Folder tampilan Admin tidak ditemukan.\n"
    );

    exit(1);
}

/**
 * DAFTAR PENYESUAIAN BAHASA
 *
 * Istilah metode ilmiah tetap dipertahankan
 * jika diperlukan untuk penelitian.
 *
 * Tulisan "Jumlah seluruh alternatif"
 * sengaja tidak diubah karena merupakan
 * bagian dari tes otomatis yang sudah lulus.
 */
$replace = [

    // =====================================
    // VALIDASI PENELITIAN
    // =====================================

    'Apakah Data A1–A5 di MySQL Sesuai Tabel 6?'
        => 'Apakah Data Penelitian A1–A5 Sesuai dengan Artikel?',

    'Apakah Data A1-A5 di MySQL Sesuai Tabel 6?'
        => 'Apakah Data Penelitian A1–A5 Sesuai dengan Artikel?',

    'Pemeriksaan langsung terhadap data tersimpan, tanpa perubahan.'
        => 'Data yang tersimpan dicocokkan dengan penelitian tanpa mengubah isinya.',

    'Total alternatif di MySQL:'
        => 'Jumlah seluruh calon yang tercatat:',

    'C1 (Rp) MySQL'
        => 'C1 · Penghasilan Orang Tua (Rp)',

    '5 / 5 data cocok'
        => '5 dari 5 data sesuai',

    'Tidak sesuai Tabel 6'
        => 'Belum sesuai dengan Artikel (Tabel 6)',

    'Sesuai Tabel 6'
        => 'Sesuai dengan Artikel (Tabel 6)',

    'Data A1–A5 di MySQL'
        => 'Data Penelitian A1–A5',

    'Data A1-A5 di MySQL'
        => 'Data Penelitian A1–A5',

    // =====================================
    // BAHASA PENYIMPANAN DATA
    // =====================================

    'Data MySQL'
        => 'Data Tersimpan',

    'di MySQL'
        => 'dalam sistem',

    'MySQL'
        => 'penyimpanan data',

    'tabel calon_penerima'
        => 'daftar calon penerima',

    'tabel pengajuan_beasiswa'
        => 'data pengajuan siswa',

    'Database'
        => 'Data Tersimpan',

    'database'
        => 'sistem penyimpanan data',

    // =====================================
    // RIWAYAT DAN KEUTUHAN ARSIP
    // =====================================

    'Pemeriksaan MySQL dan checksum arsip'
        => 'Pemeriksaan data tersimpan dan keutuhan arsip',

    'Checksum mendeteksi perubahan isi snapshot tanpa pembaruan checksum; bukan pengganti audit keamanan database.'
        => 'Pemeriksaan ini membantu mendeteksi perubahan pada arsip, tetapi bukan jaminan keamanan seluruh sistem.',

    'Nilai ini tidak diambil ulang dari tabel calon_penerima.'
        => 'Nilai ini berasal dari saat riwayat disimpan, bukan dari data terbaru.',

    'Checksum tidak sesuai'
        => 'Keutuhan Arsip Perlu Diperiksa',

    'Checksum sesuai'
        => 'Keutuhan Arsip Sesuai',

    'checksum arsip'
        => 'keutuhan arsip',

    'Checksum'
        => 'Pemeriksaan Keutuhan Arsip',

    'SHA-256:'
        => 'Kode Pemeriksaan Arsip:',

    'SHA-256'
        => 'Kode Pemeriksaan Arsip',

    'Snapshot'
        => 'Salinan Hasil Perhitungan',

    'snapshot'
        => 'salinan hasil perhitungan',

    // =====================================
    // DATA CALON PENERIMA
    // =====================================

    'Peringkat Alternatif WASPAS'
        => 'Peringkat Calon Penerima',

    'Alternatif Penerima Beasiswa'
        => 'Daftar Calon Penerima Beasiswa',

    'Data Alternatif WASPAS'
        => 'Data Calon Penerima untuk Penilaian',

    'Data alternatif AHP–WASPAS'
        => 'data calon penerima yang akan dinilai',

    'Daftar Alternatif'
        => 'Daftar Calon Penerima',

    'Total Alternatif'
        => 'Total Calon yang Dinilai',

    'Alternatif Lain'
        => 'Calon Penerima Lain',

    'Target Data Alternatif'
        => 'Target Jumlah Calon yang Dinilai',

    'Kode alternatif'
        => 'Kode Calon Penerima',

    'Kode Alternatif'
        => 'Kode Calon Penerima',

    'Nama Alternatif'
        => 'Nama Calon Penerima',

    'Peringkat alternatif'
        => 'Peringkat calon penerima',

    // =====================================
    // PERHITUNGAN AHP DAN WASPAS
    // =====================================

    'Nilai Qi'
        => 'Nilai Akhir (Qi)',

    'Nilai Qᵢ'
        => 'Nilai Akhir (Qi)',

    'Lingkup Perhitungan Aktif'
        => 'Kelompok Data yang Sedang Dinilai',

    'Lingkup perhitungan aktif'
        => 'Kelompok data yang sedang dinilai',

    'Lingkup:'
        => 'Kelompok Data Seleksi:',

    'Mode bobot:'
        => 'Sumber Bobot Penilaian:',

    'Bobot aktif:'
        => 'Bobot Penilaian yang Digunakan:',

    'Hasil Pemeriksaan'
        => 'Keterangan',

    'Normalisasi matriks'
        => 'Penyesuaian Nilai (Normalisasi)',

    'Tabel Normalisasi'
        => 'Penyesuaian Nilai (Normalisasi)',

    'Nilai normalisasi'
        => 'Nilai setelah penyesuaian (normalisasi)',

    'Bobot yang tercantum di artikel'
        => 'Bobot penilaian menurut artikel penelitian',

    'CR artikel'
        => 'nilai konsistensi artikel (CR)',

    'CR = '
        => 'Nilai Konsistensi (CR) = ',

    // =====================================
    // PENJELASAN KRITERIA
    // =====================================

    'C1 adalah Cost, sedangkan C2 sampai C5 adalah Benefit.'
        => 'C1 memprioritaskan penghasilan yang lebih rendah. C2–C5 memprioritaskan nilai yang lebih tinggi.',

    'C1 Cost; C2–C5 Benefit.'
        => 'C1 memprioritaskan nilai lebih rendah; C2–C5 nilai lebih tinggi.',

    'Cost (min/x)'
        => 'Penghasilan: lebih rendah lebih diprioritaskan',

    'Benefit (x/max)'
        => 'Nilai lebih tinggi lebih diprioritaskan',
];

// Dahulukan frasa paling panjang.
uksort(
    $replace,
    static fn (string $a, string $b): int =>
        strlen($b) <=> strlen($a)
);

/**
 * MEMISAHKAN TEKS DARI KODE
 *
 * Bagian berikut tidak boleh diganti:
 * - Ekspresi Blade
 * - PHP
 * - JavaScript
 * - CSS
 * - Komentar
 * - Tag HTML
 * - Direktif Blade
 */
$pattern = '~(\{\{--[\s\S]*?--\}\}|<!--[\\s\\S]*?-->|@php\\b[\\s\\S]*?@endphp\\b|<\\?php[\\s\\S]*?\\?>|<script\\b[^>]*>[\\s\\S]*?</script\\s*>|<style\\b[^>]*>[\\s\\S]*?</style\\s*>|\\{!![\\s\\S]*?!!\\}|\\{\\{[\\s\\S]*?\\}\\}|@verbatim\\b[\\s\\S]*?@endverbatim\\b|@\\w+(?:\\s*\\([^)]*\\))?|<[^>]*>)~iu';

$files = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $folder,
        FilesystemIterator::SKIP_DOTS
    )
);

foreach ($iterator as $file) {

    if (
        $file->isFile() &&
        str_ends_with(
            $file->getFilename(),
            '.blade.php'
        )
    ) {
        $files[] = $file->getPathname();
    }
}

// Ikut memeriksa layout sidebar Admin.
if (is_file($layout)) {
    $files[] = $layout;
}

$files = array_values(array_unique($files));

sort($files, SORT_STRING);

$changedFiles = 0;
$totalChanges = 0;

$timestamp = date('Ymd-His');

foreach ($files as $file) {

    $original = file_get_contents($file);

    if ($original === false) {
        throw new RuntimeException(
            "Gagal membaca: {$file}"
        );
    }

    $pieces = preg_split(
        $pattern,
        $original,
        -1,
        PREG_SPLIT_DELIM_CAPTURE
    );

    if ($pieces === false) {
        throw new RuntimeException(
            "Gagal memeriksa: {$file}"
        );
    }

    $count = 0;

    foreach ($pieces as $i => $piece) {

        /**
         * Posisi genap adalah teks tampilan.
         * Posisi ganjil adalah token HTML/Blade/PHP.
         */

        if (($i % 2) === 0) {

            if (trim($piece) === '') {
                continue;
            }

            $pieces[$i] = str_ireplace(
                array_keys($replace),
                array_values($replace),
                $piece,
                $hits
            );

            $count += $hits;

            continue;
        }

        /**
         * Atribut yang terlihat oleh pengguna.
         *
         * Hanya:
         * placeholder, title, aria-label, alt.
         *
         * Tidak mengubah href, class, name,
         * id, route, atau data-*.
         */

        if (preg_match('/^<[a-z]/i', $piece)) {

            $pieces[$i] = preg_replace_callback(
                '/\b(placeholder|title|aria-label|alt)\s*=\s*(["\'])(.*?)\2/iu',

                static function (array $m)
                    use ($replace, &$count): string {

                    if (
                        str_contains($m[3], '{{') ||
                        str_contains($m[3], '@')
                    ) {
                        return $m[0];
                    }

                    $value = str_ireplace(
                        array_keys($replace),
                        array_values($replace),
                        $m[3],
                        $hits
                    );

                    $count += $hits;

                    return $m[1] . '=' .
                        $m[2] . $value . $m[2];
                },

                $piece

            ) ?? $piece;
        }

        /**
         * Label statis dalam array @php
         * yang ditampilkan pada kartu Admin.
         */

        if (preg_match('/^@php\b/i', $piece)) {

            $pieces[$i] = preg_replace_callback(
                '/(["\'](?:judul|keterangan|label|deskripsi)["\']\s*=>\s*)(["\'])([^"\']*)(\2)/iu',

                static function (array $m)
                    use ($replace, &$count): string {

                    $value = str_ireplace(
                        array_keys($replace),
                        array_values($replace),
                        $m[3],
                        $hits
                    );

                    $count += $hits;

                    return $m[1] .
                        $m[2] .
                        $value .
                        $m[4];
                },

                $piece

            ) ?? $piece;
        }
    }

    $output = implode('', $pieces);

    /**
     * Judul/deskripsi halaman yang memakai
     * @section('deskripsi-admin', '...')
     */
    $output = preg_replace_callback(
        '/(@section\(\s*[\'"](?:deskripsi-admin|judul-admin)[\'"]\s*,\s*[\'"])([^\'"]*)([\'"]\s*\))/iu',

        static function (array $m)
            use ($replace, &$count): string {

            $value = str_ireplace(
                array_keys($replace),
                array_values($replace),
                $m[2],
                $hits
            );

            $count += $hits;

            return $m[1] .
                $value .
                $m[3];
        },

        $output
    );

    if (
        $output === null ||
        $output === $original
    ) {
        continue;
    }

    $changedFiles++;
    $totalChanges += $count;

    $relative = substr(
        $file,
        strlen($root) + 1
    );

    echo sprintf(
        "[%s] %s : %d perubahan\n",

        $mode === '--apply'
            ? 'UBAH'
            : 'TINJAU',

        str_replace(
            '\\',
            '/',
            $relative
        ),

        $count
    );

    if ($mode === '--apply') {

        // Simpan cadangan.
        $backup = $file .
            '.bak-' . $timestamp;

        if (!copy($file, $backup)) {
            throw new RuntimeException(
                "Gagal membuat cadangan: {$backup}"
            );
        }

        if (
            file_put_contents(
                $file,
                $output,
                LOCK_EX
            ) === false
        ) {
            throw new RuntimeException(
                "Gagal menyimpan: {$file}"
            );
        }
    }
}

echo "\n";

echo "Jumlah file: {$changedFiles}\n";

echo "Jumlah perubahan teks: {$totalChanges}\n";

if ($mode === '--preview') {

    echo "Mode pratinjau: file belum diubah.\n";

} else {

    echo "Perubahan berhasil diterapkan.\n";

    echo "Cadangan .bak-{$timestamp} dibuat.\n";
}

/**
 * PEMERIKSAAN SISA ISTILAH TEKNIS
 *
 * Hanya mencari dalam teks tampilan,
 * bukan ekspresi PHP dan JavaScript.
 */
$terms =
    '/\b(?:mysql|database|snapshot|checksum|sha-256|backend|frontend|runtime|migration|controller|query|session)\b|\btabel\s+[a-z]+_[a-z_]+/iu';

$remaining = [];

foreach ($files as $file) {

    $content = (string) file_get_contents(
        $file
    );

    $parts = preg_split(
        $pattern,
        $content,
        -1,
        PREG_SPLIT_DELIM_CAPTURE
    );

    if (!is_array($parts)) {
        continue;
    }

    foreach ($parts as $i => $part) {

        if (($i % 2) !== 0) {
            continue;
        }

        if (
            preg_match(
                $terms,
                $part,
                $match
            )
        ) {

            $remaining[] =
                str_replace(
                    '\\',
                    '/',
                    substr(
                        $file,
                        strlen($root) + 1
                    )
                )
                . ': '
                . trim($match[0]);
        }
    }
}

if ($remaining !== []) {

    echo "\nIstilah yang masih perlu diperiksa:\n";

    foreach (
        array_slice(
            array_unique($remaining),
            0,
            30
        ) as $item
    ) {
        echo "- {$item}\n";
    }

} else {

    echo "\nTidak ditemukan istilah teknis umum ";
    echo "pada teks statis halaman Admin.\n";
}

echo "\nSelesai.\n";
