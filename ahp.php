
<?php
// Kalkulator AHP - SPK Beasiswa Kelompok 4
// Data input perbandingan adalah simulasi, bukan matriks asli jurnal.
session_start();

$kriteria = [
    'C1' => 'Penghasilan Orang Tua',
    'C2' => 'Jumlah Tanggungan',
    'C3' => 'Nilai Akademik',
    'C4' => 'Kondisi Tempat Tinggal',
    'C5' => 'Prestasi Non-Akademik'
];

// Bobot asli dari jurnal
$bobotJurnal = [0.400, 0.250, 0.150, 0.120, 0.080];

$kode = array_keys($kriteria);
$n = count($kode);

$opsi = [
    '1/9', '1/8', '1/7', '1/6', '1/5',
    '1/4', '1/3', '1/2', '1',
    '2', '3', '4', '5', '6', '7', '8', '9'
];

$matriks = array_fill(0, $n, array_fill(0, $n, 1.0));
$normalisasi = [];
$bobot = [];

$lambdaMax = 0;
$ci = 0;
$cr = 0;

$hasilAda = false;
$error = '';


// ============================================
// PROSES PERHITUNGAN AHP
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = $_POST['banding'] ?? [];
    $valid = true;

    // 1. Membuat matriks perbandingan 5x5
    for ($i = 0; $i < $n; $i++) {

        for ($j = $i + 1; $j < $n; $j++) {

            $pilihan = $input[$i][$j] ?? '';

            if (
                !is_string($pilihan) ||
                !in_array($pilihan, $opsi, true)
            ) {
                $valid = false;
                continue;
            }

            // Mengubah pecahan seperti 1/3 menjadi angka
            if (str_contains($pilihan, '/')) {
                [$a, $b] = explode('/', $pilihan);
                $nilai = (float)$a / (float)$b;
            } else {
                $nilai = (float)$pilihan;
            }

            // Nilai perbandingan dan kebalikannya
            $matriks[$i][$j] = $nilai;
            $matriks[$j][$i] = 1 / $nilai;
        }
    }

    if (!$valid) {

        $error = 'Pilih seluruh 10 nilai perbandingan terlebih dahulu.';

    } else {

        // 2. Menghitung jumlah setiap kolom
        $jumlahKolom = array_fill(0, $n, 0.0);

        for ($j = 0; $j < $n; $j++) {

            for ($i = 0; $i < $n; $i++) {
                $jumlahKolom[$j] += $matriks[$i][$j];
            }
        }

        // 3. Normalisasi matriks AHP
        for ($i = 0; $i < $n; $i++) {

            for ($j = 0; $j < $n; $j++) {

                $normalisasi[$i][$j] =
                    $matriks[$i][$j] / $jumlahKolom[$j];
            }

            // 4. Menghitung bobot prioritas
            $bobot[$i] = array_sum($normalisasi[$i]) / $n;
        }

        // 5. Menghitung Lambda Maksimum
        $jumlahLambda = 0.0;

        for ($i = 0; $i < $n; $i++) {

            $aw = 0.0;

            for ($j = 0; $j < $n; $j++) {

                $aw += $matriks[$i][$j] * $bobot[$j];
            }

            $jumlahLambda += $aw / $bobot[$i];
        }

        $lambdaMax = $jumlahLambda / $n;

        // 6. Menghitung Consistency Index
        $ci = max(0, ($lambdaMax - $n) / ($n - 1));

        // 7. Menghitung Consistency Ratio
        // Random Index untuk 5 kriteria = 1,12
        $cr = $ci / 1.12;

        $hasilAda = true;

        // Menyimpan bobot AHP ke session PHP
        // Session digunakan agar bobot dapat dibaca
        // oleh halaman simulasi.php
        $_SESSION['ahp_terakhir'] = [
            'bobot' => array_combine($kode, $bobot),
            'cr' => $cr
        ];
    }
}


// ============================================
// MEMANGGIL TAMPILAN WEBSITE
// ============================================

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="max-w-5xl mx-auto p-6">

    <!-- Judul halaman -->
    <section class="mb-6">

        <h2 class="text-2xl font-bold text-blue-900 mb-2">
            Kalkulator AHP
        </h2>

        <p class="text-gray-600">
            Perbandingan kriteria untuk menghitung
            bobot prioritas dan konsistensi.
        </p>

    </section>


    <!-- 1. Bobot jurnal -->
    <section class="bg-white rounded-lg shadow p-6 mb-6">

        <h3 class="text-xl font-bold mb-4">
            1. Hasil AHP yang Dilaporkan dalam Jurnal
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full border-collapse text-sm">

                <thead class="bg-blue-900 text-white">

                    <tr>
                        <th class="border p-3">Kode</th>
                        <th class="border p-3">Kriteria</th>
                        <th class="border p-3">Bobot</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($kode as $i => $c): ?>

                    <tr>

                        <td class="border p-3 text-center">
                            <?= $c ?>
                        </td>

                        <td class="border p-3">
                            <?= htmlspecialchars($kriteria[$c]) ?>
                        </td>

                        <td class="border p-3 text-center">
                            <?= number_format(
                                $bobotJurnal[$i],
                                3,
                                ',',
                                '.'
                            ) ?>
                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <p class="mt-3 text-sm">
            <b>CR jurnal:</b> 0,045
        </p>

        <p class="text-sm text-gray-600 mt-2">
            Matriks perbandingan asli jurnal belum tersedia.
            Nilai di atas merupakan hasil yang dilaporkan
            dalam jurnal, bukan hasil dari formulir ini.
        </p>

    </section>


    <!-- 2. Form input perbandingan -->
    <section class="bg-white rounded-lg shadow p-6 mb-6">

        <h3 class="text-xl font-bold mb-2">
            2. Input Perbandingan Berpasangan
        </h3>

        <p class="text-sm text-gray-600 mb-4">
            DATA SIMULASI. Pilih 1 jika kedua kriteria sama
            penting. Pilih 3 jika kriteria pertama tiga kali
            lebih penting, atau 1/3 jika kriteria kedua
            tiga kali lebih penting.
        </p>

        <form method="post">

            <div class="overflow-x-auto">

                <table class="w-full border-collapse text-sm">

                    <thead class="bg-blue-900 text-white">

                        <tr>
                            <th class="border p-3">
                                Kriteria Pertama
                            </th>

                            <th class="border p-3">
                                Kriteria Kedua
                            </th>

                            <th class="border p-3">
                                Nilai Perbandingan
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php for ($i = 0; $i < $n; $i++): ?>

                            <?php for ($j = $i + 1; $j < $n; $j++): ?>

                            <tr>

                                <td class="border p-3">
                                    <?= $kode[$i] ?>
                                </td>

                                <td class="border p-3">
                                    <?= $kode[$j] ?>
                                </td>

                                <td class="border p-3">

                                    <select
                                        name="banding[<?= $i ?>][<?= $j ?>]"
                                        required
                                        class="border rounded p-2 w-full"
                                    >

                                        <option value="">
                                            Pilih nilai
                                        </option>

                                        <?php foreach ($opsi as $pilihan): ?>

                                        <option
                                            value="<?= $pilihan ?>"
                                            <?=
                                                ($_POST['banding'][$i][$j] ?? '') === $pilihan
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= $pilihan ?>
                                        </option>

                                        <?php endforeach; ?>

                                    </select>

                                </td>

                            </tr>

                            <?php endfor; ?>

                        <?php endfor; ?>

                    </tbody>

                </table>

            </div>

            <button
                type="submit"
                class="bg-blue-900 text-white rounded px-5 py-2 mt-4 hover:bg-blue-800"
            >
                Hitung AHP
            </button>

        </form>

        <?php if ($error): ?>

        <p class="text-red-700 mt-4">
            <?= htmlspecialchars($error) ?>
        </p>

        <?php endif; ?>

    </section>


    <?php if ($hasilAda): ?>

        <!-- 3 dan 4. Matriks perbandingan dan normalisasi -->
        <?php
        $daftarTabel = [
            '3. Matriks Perbandingan' => $matriks,
            '4. Normalisasi Matriks AHP' => $normalisasi
        ];
        ?>

        <?php foreach ($daftarTabel as $judul => $tabel): ?>

        <section class="bg-white rounded-lg shadow p-6 mb-6">

            <h3 class="text-xl font-bold mb-4">
                <?= $judul ?>
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse text-sm">

                    <thead class="bg-blue-900 text-white">

                        <tr>

                            <th class="border p-3">
                                Kriteria
                            </th>

                            <?php foreach ($kode as $c): ?>

                            <th class="border p-3">
                                <?= $c ?>
                            </th>

                            <?php endforeach; ?>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($tabel as $i => $baris): ?>

                        <tr>

                            <td class="border p-3 font-bold">
                                <?= $kode[$i] ?>
                            </td>

                            <?php foreach ($baris as $angka): ?>

                            <td class="border p-3 text-center">
                                <?= number_format($angka, 4, ',', '.') ?>
                            </td>

                            <?php endforeach; ?>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </section>

        <?php endforeach; ?>


        <!-- 5. Hasil perhitungan AHP -->
        <section class="bg-white rounded-lg shadow p-6 mb-6">

            <h3 class="text-xl font-bold mb-4">
                5. Bobot dan Konsistensi Hasil Simulasi
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse text-sm">

                    <thead class="bg-blue-900 text-white">

                        <tr>
                            <th class="border p-3">
                                Kriteria
                            </th>

                            <th class="border p-3">
                                Bobot
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($kode as $i => $c): ?>

                        <tr>

                            <td class="border p-3 text-center">
                                <?= $c ?>
                            </td>

                            <td class="border p-3 text-center">
                                <?= number_format(
                                    $bobot[$i],
                                    4,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div class="mt-4 text-sm space-y-2">

                <p>
                    <b>Lambda Maksimum:</b>
                    <?= number_format($lambdaMax, 4, ',', '.') ?>
                </p>

                <p>
                    <b>Consistency Index (CI):</b>
                    <?= number_format($ci, 4, ',', '.') ?>
                </p>

                <p>
                    <b>Random Index (RI):</b>
                    1,12
                </p>

                <p>
                    <b>Consistency Ratio (CR):</b>
                    <?= number_format($cr, 4, ',', '.') ?>
                </p>

            </div>

            <?php if ($cr <= 0.1): ?>

                <p class="text-green-700 font-bold mt-3">
                    Hasil Konsisten (CR ≤ 0,1)
                </p>

                <!-- Mengirim pilihan penggunaan bobot ke WASPAS -->
                <form action="simulasi.php" method="post" class="mt-5">

                    <input
                        type="hidden"
                        name="aksi"
                        value="gunakan_ahp"
                    >

                    <button
                        type="submit"
                        class="bg-blue-900 text-white rounded px-5 py-2 hover:bg-blue-800"
                    >
                        Gunakan Bobot Ini di WASPAS
                    </button>

                </form>

            <?php else: ?>

                <p class="text-red-700 font-bold mt-3">
                    Belum Konsisten (CR > 0,1)
                </p>

                <p class="text-sm text-gray-600 mt-2">
                    Perbaiki nilai perbandingan terlebih dahulu
                    sebelum menggunakan bobot di WASPAS.
                </p>

            <?php endif; ?>

        </section>

    <?php endif; ?>


    <!-- Catatan -->
    <section class="bg-blue-50 rounded-lg p-4">

        <p class="text-sm leading-6">
            <b>Catatan:</b>
            Seluruh nilai perbandingan yang diinput
            merupakan DATA SIMULASI.

            Hasil AHP dari pengguna dapat digunakan
            untuk mencoba perhitungan WASPAS,
            tetapi tidak mengubah bobot asli jurnal.
        </p>

    </section>

</main>

<?php
include 'includes/footer.php';
?>
