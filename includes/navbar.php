
<?php
// Navbar Website SPK Beasiswa
// Kelompok 4 - Universitas Pamulang

// Mengetahui halaman yang sedang dibuka
$halamanAktif = basename($_SERVER["SCRIPT_NAME"]);

// Daftar menu website
$menu = [
    ["nama" => "Beranda", "file" => "index.php"],
    ["nama" => "Pendahuluan", "file" => "pendahuluan.php"],
    ["nama" => "Tinjauan Pustaka", "file" => "tinjauan-pustaka.php"],
    ["nama" => "Metode", "file" => "metode.php"],
    ["nama" => "Hasil", "file" => "hasil.php"],
    ["nama" => "Kalkulator AHP", "file" => "ahp.php"],
    ["nama" => "Simulasi WASPAS", "file" => "simulasi.php"],
    ["nama" => "Daftar Pustaka", "file" => "daftar-pustaka.php"]
];

// Alamat dasar website di Laragon
$alamatWebsite = "/spk-beasiswa-ahp-waspas/";
?>

<!-- Menu navigasi website -->
<nav class="bg-white shadow">

    <div class="max-w-5xl mx-auto px-6 py-4">

        <div class="flex flex-wrap items-center gap-5 text-sm">

            <?php foreach ($menu as $item): ?>

                <?php
                // Memeriksa apakah file halaman sudah tersedia
                $tersedia = file_exists(
                    __DIR__ . "/../" . $item["file"]
                );

                // Memeriksa apakah menu sedang aktif
                $aktif = $halamanAktif === $item["file"];

                // Membuat alamat tujuan menu
                $alamatMenu = $alamatWebsite . $item["file"];
                ?>

                <?php if ($tersedia): ?>

                    <!-- Menu yang bisa diklik -->
                    <a
                        href="<?= htmlspecialchars($alamatMenu); ?>"
                        class="<?=
                            $aktif
                            ? 'text-blue-900 font-bold border-b-2 border-blue-900 pb-1'
                            : 'text-gray-700 hover:text-blue-900 hover:underline'
                        ?>"
                    >
                        <?= htmlspecialchars($item["nama"]); ?>
                    </a>

                <?php else: ?>

                    <!-- Menu yang halamannya belum tersedia -->
                    <span
                        class="text-gray-400 cursor-not-allowed"
                        title="Halaman belum tersedia"
                    >
                        <?= htmlspecialchars($item["nama"]); ?>
                    </span>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>

    </div>

</nav>
