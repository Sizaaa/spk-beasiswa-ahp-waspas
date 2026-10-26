
<?php
// Simulasi WASPAS - SPK Beasiswa Kelompok 4
// Menggunakan bobot jurnal atau bobot hasil AHP simulasi

session_start();


// ============================================
// 1. DATA KRITERIA DAN BOBOT JURNAL
// ============================================

$kriteria = [
    [
        'kode' => 'C1',
        'nama' => 'Penghasilan Orang Tua',
        'jenis' => 'Cost',
        'bobot' => 0.400
    ],
    [
        'kode' => 'C2',
        'nama' => 'Jumlah Tanggungan',
        'jenis' => 'Benefit',
        'bobot' => 0.250
    ],
    [
        'kode' => 'C3',
        'nama' => 'Nilai Akademik',
        'jenis' => 'Benefit',
        'bobot' => 0.150
    ],
    [
        'kode' => 'C4',
        'nama' => 'Kondisi Tempat Tinggal',
        'jenis' => 'Benefit',
        'bobot' => 0.120
    ],
    [
        'kode' => 'C5',
        'nama' => 'Prestasi Non-Akademik',
        'jenis' => 'Benefit',
        'bobot' => 0.080
    ]
];


// ============================================
// 2. DATA ALTERNATIF SIMULASI JURNAL
// ============================================

$alternatif = [
    [
        'kode' => 'A1',
        'c1' => 1500000,
        'c2' => 3,
        'c3' => 85,
        'c4' => 3,
        'c5' => 2
    ],
    [
        'kode' => 'A2',
        'c1' => 2500000,
        'c2' => 2,
        'c3' => 90,
        'c4' => 2,
        'c5' => 4
    ],
    [
        'kode' => 'A3',
        'c1' => 1000000,
        'c2' => 5,
        'c3' => 80,
        'c4' => 4,
        'c5' => 1
    ],
    [
        'kode' => 'A4',
        'c1' => 3000000,
        'c2' => 4,
        'c3' => 88,
        'c4' => 2,
        'c5' => 3
    ],
    [
        'kode' => 'A5',
        'c1' => 1800000,
        'c2' => 3,
        'c3' => 92,
        'c4' => 3,
        'c5' => 5
    ]
];


// ============================================
// 3. MEMILIH SUMBER BOBOT
// ============================================

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'] ?? '';

    // Menggunakan bobot dari Kalkulator AHP
    if ($aksi === 'gunakan_ahp') {

        $hasilAHP = $_SESSION['ahp_terakhir'] ?? null;

        $bobotAHP = $hasilAHP['bobot'] ?? null;
        $crAHP = $hasilAHP['cr'] ?? null;

        $valid = is_array($bobotAHP)
            && count($bobotAHP) === 5
            && is_numeric($crAHP)
            && is_finite((float)$crAHP)
            && $crAHP >= 0
            && $crAHP <= 0.1;

        $total = 0;

        // Memeriksa bobot setiap kriteria
        if ($valid) {

            foreach ($kriteria as $item) {

                $w = $bobotAHP[$item['kode']] ?? null;

                if (
                    !is_numeric($w) ||
                    !is_finite((float)$w) ||
                    $w <= 0
                ) {
                    $valid = false;
                    break;
                }

                $total += (float)$w;
            }
        }

        // Menyimpan bobot aktif jika valid
        if ($valid && abs($total - 1) < 0.0001) {

            $_SESSION['bobot_waspas_aktif'] = $bobotAHP;
            $_SESSION['cr_bobot_waspas'] = (float)$crAHP;

            header('Location: simulasi.php');
            exit;
        }

        $pesan = 'Bobot AHP belum tersedia atau belum konsisten.';

    } elseif ($aksi === 'bobot_jurnal') {

        // Mengembalikan bobot asli jurnal
        unset(
            $_SESSION['bobot_waspas_aktif'],
            $_SESSION['cr_bobot_waspas']
        );

        header('Location: simulasi.php');
        exit;
    }
}


// ============================================
// 4. MENENTUKAN BOBOT YANG AKTIF
// ============================================

$pakaiAHP = isset($_SESSION['bobot_waspas_aktif']);

if ($pakaiAHP) {

    foreach ($kriteria as &$item) {

        $item['bobot'] = (float)
            $_SESSION['bobot_waspas_aktif'][$item['kode']];
    }

    unset($item);
}


// ============================================
// 5. MEMANGGIL HEADER DAN NAVBAR
// ============================================

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="max-w-5xl mx-auto p-6">

    <!-- Judul halaman -->
    <section class="mb-6">

        <h2 class="text-2xl font-bold text-blue-900 mb-2">
            Simulasi AHP dan WASPAS
        </h2>

        <p class="text-gray-600">
            Perhitungan peringkat calon penerima beasiswa
            menggunakan DATA SIMULASI.
        </p>

    </section>


    <!-- 1. SUMBER BOBOT -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-2">
            1. Sumber Bobot dan Kriteria
        </h3>

        <?php if ($pakaiAHP): ?>

            <p class="text-blue-900 font-semibold mb-3">
                Sedang menggunakan bobot dari Kalkulator AHP
                (simulasi).
            </p>

            <p class="text-sm mb-4">
                CR bobot simulasi AHP:
                <?= number_format(
                    $_SESSION['cr_bobot_waspas'],
                    4,
                    ',',
                    '.'
                ) ?>
            </p>

            <!-- Tombol kembali ke bobot jurnal -->
            <form method="post" class="mb-4">

                <input
                    type="hidden"
                    name="aksi"
                    value="bobot_jurnal"
                >

                <button
                    type="submit"
                    class="bg-gray-200 rounded px-4 py-2 hover:bg-gray-300"
                >
                    Kembali ke Bobot Jurnal
                </button>

            </form>

        <?php else: ?>

            <p class="text-green-800 font-semibold mb-3">
                Sedang menggunakan bobot dari jurnal (default).
            </p>

            <p class="text-sm mb-4">
                CR yang dilaporkan jurnal: 0,045.

                Untuk memakai bobot percobaan, buka
                <a
                    href="ahp.php"
                    class="text-blue-700 underline"
                >
                    Kalkulator AHP
                </a>.
            </p>

        <?php endif; ?>

        <?php if ($pesan): ?>

            <p class="text-red-700 mb-3">
                <?= htmlspecialchars($pesan) ?>
            </p>

        <?php endif; ?>


        <!-- Tabel bobot yang sedang digunakan -->
        <div class="overflow-x-auto">

            <table class="w-full border-collapse text-sm">

                <thead class="bg-blue-900 text-white">

                    <tr>
                        <th class="border p-3">Kode</th>
                        <th class="border p-3">Kriteria</th>
                        <th class="border p-3">Jenis</th>
                        <th class="border p-3">Bobot Aktif</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($kriteria as $item): ?>

                    <tr>

                        <td class="border p-3 text-center">
                            <?= $item['kode'] ?>
                        </td>

                        <td class="border p-3">
                            <?= htmlspecialchars($item['nama']) ?>
                        </td>

                        <td class="border p-3 text-center">
                            <?= $item['jenis'] ?>
                        </td>

                        <td class="border p-3 text-center">
                            <?= number_format(
                                $item['bobot'],
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

    </section>


    <!-- 2. DATA CALON PENERIMA -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-2">
            2. Input Data Calon Penerima
        </h3>

        <p class="text-sm text-gray-600 mb-4">
            Data awal A1–A5 merupakan data simulasi
            berdasarkan jurnal. Ubah nilainya untuk
            mencoba skenario penilaian lain.
        </p>

        <div class="overflow-x-auto">

            <table
                id="tabel-input"
                class="w-full border-collapse text-sm"
            >

                <thead class="bg-blue-900 text-white">

                    <tr>
                        <th class="border p-3">Alternatif</th>
                        <th class="border p-3">C1 (Rp)</th>
                        <th class="border p-3">C2</th>
                        <th class="border p-3">C3</th>
                        <th class="border p-3">C4</th>
                        <th class="border p-3">C5</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($alternatif as $data): ?>

                    <tr data-kode="<?= $data['kode'] ?>">

                        <td class="border p-2 font-bold text-center">
                            <?= $data['kode'] ?>
                        </td>

                        <?php for ($i = 1; $i <= 5; $i++): ?>

                        <td class="border p-2">

                            <input
                                aria-label="<?= $data['kode'] ?> C<?= $i ?>"
                                data-kriteria="c<?= $i ?>"
                                type="number"
                                step="<?= $i === 3 ? 'any' : '1' ?>"
                                value="<?= $data['c' . $i] ?>"
                                class="border rounded p-2 w-28"
                            >

                        </td>

                        <?php endfor; ?>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <p class="text-xs text-gray-500 mt-3">
            C1: penghasilan positif.
            C2: jumlah tanggungan minimal 0.
            C3: nilai akademik 0–100.
            C4 dan C5: skor 1–5.
            Nilai C4 lebih tinggi berarti kondisi
            tempat tinggal semakin memprihatinkan.
        </p>


        <!-- Parameter Lambda -->
        <div class="mt-5">

            <label for="lambda" class="font-bold">
                Parameter WASPAS (λ)
            </label>

            <select
                id="lambda"
                class="border rounded p-2 ml-2"
            >

                <option value="0">
                    0 (WPM)
                </option>

                <option value="0.5" selected>
                    0,5 (Gabungan)
                </option>

                <option value="1">
                    1 (WSM)
                </option>

            </select>

        </div>


        <!-- Tombol perhitungan -->
        <div class="flex flex-wrap gap-3 mt-5">

            <button
                type="button"
                id="btn-hitung"
                class="bg-blue-900 text-white rounded px-5 py-2 hover:bg-blue-800"
            >
                Hitung Ulang
            </button>

            <button
                type="button"
                id="btn-reset"
                class="bg-gray-200 rounded px-5 py-2 hover:bg-gray-300"
            >
                Reset Data
            </button>

        </div>

        <p
            id="pesan-error"
            role="alert"
            class="hidden text-red-700 mt-3"
        ></p>

    </section>


    <!-- 3. HASIL NORMALISASI -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-4">
            3. Hasil Normalisasi WASPAS
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full border-collapse text-sm">

                <thead class="bg-blue-900 text-white">

                    <tr>

                        <th class="border p-3">
                            Alternatif
                        </th>

                        <?php foreach ($kriteria as $item): ?>

                        <th class="border p-3">
                            <?= $item['kode'] ?>
                        </th>

                        <?php endforeach; ?>

                    </tr>

                </thead>

                <tbody id="hasil-normalisasi"></tbody>

            </table>

        </div>

    </section>


    <!-- 4. HASIL AKHIR WASPAS -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-4">
            4. Hasil Akhir WASPAS dan Peringkat
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full border-collapse text-sm">

                <thead class="bg-blue-900 text-white">

                    <tr>
                        <th class="border p-3">Alternatif</th>
                        <th class="border p-3">WSM</th>
                        <th class="border p-3">WPM</th>
                        <th class="border p-3">Nilai Q</th>
                        <th class="border p-3">Peringkat</th>
                    </tr>

                </thead>

                <tbody id="hasil-peringkat"></tbody>

            </table>

        </div>

    </section>


    <!-- 5. HASIL REKOMENDASI -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-3">
            5. Hasil Rekomendasi
        </h3>

        <p
            id="hasil-terbaik"
            class="font-bold text-blue-900"
        >
            -
        </p>

        <p class="text-sm text-gray-600 mt-3">
            Hasil ini merupakan rekomendasi berdasarkan
            DATA SIMULASI, bukan keputusan penerimaan
            beasiswa yang sebenarnya.
        </p>

    </section>


    <!-- 6. ANALISIS SENSITIVITAS -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h3 class="text-xl font-bold mb-3">
            6. Analisis Sensitivitas
        </h3>

        <p class="text-sm text-gray-600 mb-4">
            Perbandingan nilai Q dan peringkat untuk
            λ = 0, λ = 0,5, dan λ = 1 menggunakan
            bobot yang sedang aktif.
        </p>

        <div class="overflow-x-auto">

            <table class="w-full border-collapse text-sm">

                <thead class="bg-blue-900 text-white">

                    <tr>
                        <th class="border p-3">Alt.</th>
                        <th class="border p-3">Q (λ=0)</th>
                        <th class="border p-3">Rank</th>
                        <th class="border p-3">Q (λ=0,5)</th>
                        <th class="border p-3">Rank</th>
                        <th class="border p-3">Q (λ=1)</th>
                        <th class="border p-3">Rank</th>
                    </tr>

                </thead>

                <tbody id="hasil-sensitivitas"></tbody>

            </table>

        </div>

    </section>


    <!-- Catatan penelitian -->
    <section class="bg-blue-50 rounded-lg p-4">

        <p class="text-sm leading-6">

            <b>Catatan:</b>

            Seluruh data calon penerima adalah
            DATA SIMULASI, bukan data riil.

            Bobot jurnal tidak berubah ketika
            bobot percobaan AHP digunakan.

            Tombol Reset Data mengembalikan
            nilai calon dan parameter λ,
            bukan sumber bobot.

        </p>

    </section>

</main>


<script>
// ============================================
// JAVASCRIPT PERHITUNGAN WASPAS
// ============================================

// Mengambil data dari PHP
const dataAwal = <?= json_encode(
    $alternatif,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;

const kriteria = <?= json_encode(
    $kriteria,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;


// Format angka Indonesia
function tampilAngka(nilai, digit = 4) {

    return nilai.toLocaleString('id-ID', {
        minimumFractionDigits: digit,
        maximumFractionDigits: digit
    });
}


// ============================================
// 1. MENGAMBIL DATA INPUT
// ============================================

function ambilData() {

    const baris = document.querySelectorAll(
        '#tabel-input tbody tr'
    );

    return Array.from(baris).map(tr => {

        const nilai = [];

        for (let i = 1; i <= 5; i++) {

            const input = tr.querySelector(
                `[data-kriteria="c${i}"]`
            );

            if (input.value.trim() === '') {

                throw new Error(
                    `${tr.dataset.kode} C${i} belum diisi.`
                );
            }

            const angka = Number(input.value);

            if (!Number.isFinite(angka)) {

                throw new Error(
                    `${tr.dataset.kode} C${i} tidak valid.`
                );
            }

            nilai.push(angka);
        }

        const [c1, c2, c3, c4, c5] = nilai;

        // Validasi penghasilan
        if (!Number.isInteger(c1) || c1 <= 0) {

            throw new Error(
                `${tr.dataset.kode}: C1 harus bilangan bulat positif.`
            );
        }

        // Validasi jumlah tanggungan
        if (!Number.isInteger(c2) || c2 < 0) {

            throw new Error(
                `${tr.dataset.kode}: C2 harus bilangan bulat minimal 0.`
            );
        }

        // Validasi nilai akademik
        if (c3 < 0 || c3 > 100) {

            throw new Error(
                `${tr.dataset.kode}: C3 harus antara 0 dan 100.`
            );
        }

        // Validasi skor kondisi rumah dan prestasi
        if (
            ![c4, c5].every(
                v => Number.isInteger(v) && v >= 1 && v <= 5
            )
        ) {

            throw new Error(
                `${tr.dataset.kode}: C4 dan C5 harus bilangan bulat 1–5.`
            );
        }

        return {
            kode: tr.dataset.kode,
            nilai: nilai
        };
    });
}


// ============================================
// 2. MENGURUTKAN PERINGKAT
// ============================================

function peringkatWaspas(hasil, lambda) {

    return hasil

        // Menghitung nilai Q
        .map(a => ({
            ...a,
            q: lambda * a.wsm + (1 - lambda) * a.wpm
        }))

        // Mengurutkan Q dari tertinggi
        .sort((a, b) =>
            b.q - a.q || a.kode.localeCompare(b.kode)
        )

        // Memberikan nomor peringkat
        .map((a, index) => ({
            ...a,
            peringkat: index + 1
        }));
}


// ============================================
// 3. PERHITUNGAN UTAMA WASPAS
// ============================================

function hitungWaspas() {

    const pesan = document.getElementById('pesan-error');

    try {

        const data = ambilData();

        const lambda = Number(
            document.getElementById('lambda').value
        );


        // Menentukan nilai minimum dan maksimum
        const pembanding = kriteria.map((item, i) => {

            const kolom = data.map(
                a => a.nilai[i]
            );

            if (item.jenis === 'Cost') {

                return Math.min(...kolom);

            } else {

                return Math.max(...kolom);
            }
        });


        // Memeriksa pembagian dengan nol
        pembanding.forEach((nilai, i) => {

            if (nilai <= 0) {

                throw new Error(
                    `Nilai pembanding ${kriteria[i].kode} adalah 0.`
                );
            }
        });


        // ====================================
        // NORMALISASI WASPAS
        // ====================================

        const normalisasi = data.map(a => ({

            kode: a.kode,

            nilai: a.nilai.map((nilai, i) => {

                if (kriteria[i].jenis === 'Cost') {

                    return pembanding[i] / nilai;

                } else {

                    return nilai / pembanding[i];
                }
            })

        }));


        // ====================================
        // MENGHITUNG WSM DAN WPM
        // ====================================

        const hasil = normalisasi.map(a => {

            let wsm = 0;
            let wpm = 1;

            a.nilai.forEach((nilai, i) => {

                const bobot = kriteria[i].bobot;

                // Weighted Sum Model
                wsm += nilai * bobot;

                // Weighted Product Model
                wpm *= Math.pow(nilai, bobot);
            });

            return {
                kode: a.kode,
                wsm: wsm,
                wpm: wpm
            };
        });


        // Menghitung Q dan peringkat
        const ranking = peringkatWaspas(
            hasil,
            lambda
        );


        // ====================================
        // MENAMPILKAN NORMALISASI
        // ====================================

        document.getElementById(
            'hasil-normalisasi'
        ).innerHTML = normalisasi.map(a => `

            <tr>

                <td class="border p-3 text-center font-bold">
                    ${a.kode}
                </td>

                ${a.nilai.map(nilai => `

                    <td class="border p-3 text-center">
                        ${tampilAngka(nilai, 3)}
                    </td>

                `).join('')}

            </tr>

        `).join('');


        // ====================================
        // MENAMPILKAN HASIL AKHIR
        // ====================================

        document.getElementById(
            'hasil-peringkat'
        ).innerHTML = ranking.map(a => `

            <tr>

                <td class="border p-3 text-center font-bold">
                    ${a.kode}
                </td>

                <td class="border p-3 text-center">
                    ${tampilAngka(a.wsm)}
                </td>

                <td class="border p-3 text-center">
                    ${tampilAngka(a.wpm)}
                </td>

                <td class="border p-3 text-center font-bold">
                    ${tampilAngka(a.q)}
                </td>

                <td class="border p-3 text-center">
                    ${a.peringkat}
                </td>

            </tr>

        `).join('');


        // ====================================
        // HASIL REKOMENDASI
        // ====================================

        const terbaik = ranking[0];

        document.getElementById(
            'hasil-terbaik'
        ).textContent =
            `${terbaik.kode} - Nilai Q: ${tampilAngka(terbaik.q)} (Peringkat 1)`;


        // ====================================
        // ANALISIS SENSITIVITAS
        // ====================================

        const sensitivitas = [0, 0.5, 1].map(
            lam => peringkatWaspas(hasil, lam)
        );

        document.getElementById(
            'hasil-sensitivitas'
        ).innerHTML = data.map(a => {

            const r = sensitivitas.map(
                daftar => daftar.find(
                    x => x.kode === a.kode
                )
            );

            return `

                <tr>

                    <td class="border p-3 text-center font-bold">
                        ${a.kode}
                    </td>

                    ${r.map(x => `

                        <td class="border p-3 text-center">
                            ${tampilAngka(x.q)}
                        </td>

                        <td class="border p-3 text-center">
                            ${x.peringkat}
                        </td>

                    `).join('')}

                </tr>

            `;
        }).join('');


        // Menghilangkan pesan error
        pesan.classList.add('hidden');
        pesan.textContent = '';

    } catch (error) {

        // Menampilkan pesan jika input salah
        pesan.textContent = error.message;
        pesan.classList.remove('hidden');

        // Menghapus hasil lama agar tidak membingungkan
        document.getElementById(
            'hasil-normalisasi'
        ).innerHTML = '';

        document.getElementById(
            'hasil-peringkat'
        ).innerHTML = '';

        document.getElementById(
            'hasil-sensitivitas'
        ).innerHTML = '';

        document.getElementById(
            'hasil-terbaik'
        ).textContent = 'Periksa kembali input.';
    }
}


// ============================================
// 4. MENGEMBALIKAN DATA AWAL
// ============================================

function resetData() {

    const baris = document.querySelectorAll(
        '#tabel-input tbody tr'
    );

    baris.forEach((tr, index) => {

        for (let i = 1; i <= 5; i++) {

            const input = tr.querySelector(
                `[data-kriteria="c${i}"]`
            );

            input.value = dataAwal[index]['c' + i];
        }
    });

    document.getElementById('lambda').value = '0.5';

    hitungWaspas();
}


// ============================================
// 5. MENGAKTIFKAN TOMBOL
// ============================================

document.getElementById(
    'btn-hitung'
).addEventListener('click', hitungWaspas);

document.getElementById(
    'btn-reset'
).addEventListener('click', resetData);

document.getElementById(
    'lambda'
).addEventListener('change', hitungWaspas);


// Menghitung ketika halaman pertama dibuka
hitungWaspas();

</script>

<?php
include 'includes/footer.php';
?>
