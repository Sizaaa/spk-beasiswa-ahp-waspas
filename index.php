
<?php
// Halaman Beranda SPK Beasiswa
// Kelompok 4 - Universitas Pamulang

// Memanggil bagian atas website
include 'includes/header.php';

// Memanggil menu navigasi
include 'includes/navbar.php';
?>

<!-- Isi utama halaman Beranda -->
<main class="max-w-4xl mx-auto p-6">

    <!-- Informasi jurnal -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h2 class="text-xl font-bold mb-3">
            Informasi Jurnal
        </h2>

        <p><b>Penulis:</b></p>
        <p>Bagas Dwi Prasojo</p>
        <p>Muhammad Azis</p>
        <p>Muhammad Asyhadullah</p>

        <p class="mt-4">
            <b>Mata Kuliah:</b>
            Sistem Informasi Manajemen
        </p>

        <p>
            <b>Universitas:</b> Universitas Pamulang
        </p>

        <p>
            <b>Tahun:</b> Oktober 2026
        </p>

    </section>

    <!-- Abstrak jurnal -->
    <section class="bg-white p-6 rounded-lg shadow mb-6">

        <h2 class="text-xl font-bold mb-3">
            Abstrak
        </h2>

        <p class="text-justify leading-7">
            Penyaluran beasiswa bagi mahasiswa atau siswa
            kurang mampu menuntut proses seleksi yang
            objektif, transparan, dan dapat
            dipertanggungjawabkan. Seleksi manual cenderung
            memakan waktu dan rawan subjektivitas karena
            banyak kriteria harus dipertimbangkan secara
            bersamaan. Artikel ini membahas rancangan
            sistem pendukung keputusan (SPK) untuk
            menentukan kelayakan penerima beasiswa kurang
            mampu dengan memadukan metode Analytic
            Hierarchy Process (AHP) dan Weighted Aggregated
            Sum Product Assessment (WASPAS). AHP dipakai
            untuk menurunkan bobot lima kriteria, yaitu
            penghasilan orang tua, jumlah tanggungan,
            nilai akademik, kondisi tempat tinggal, dan
            prestasi non-akademik, sedangkan WASPAS dipakai
            untuk menghitung nilai preferensi dan meranking
            calon penerima. Studi kasus berupa data
            simulasi lima calon penerima menghasilkan
            bobot kriteria dengan rasio konsistensi
            0,045 (di bawah batas 0,1) serta urutan
            prioritas A3, A5, A1, A4, dan A2.
            Pembahasan juga memuat keterkaitan SPK
            dengan sistem informasi manajemen dan
            peluang pengembangan dengan teknologi terbaru.
        </p>

    </section>

    <!-- Catatan penelitian -->
    <section class="bg-blue-50 p-4 rounded-lg">

        <p class="text-sm">
            <b>Catatan:</b> Data calon penerima beasiswa
            merupakan data simulasi, bukan data riil.
            Sistem dalam jurnal masih berada pada
            tahap perancangan.
        </p>

    </section>

</main>

<?php
// Memanggil bagian bawah website
include 'includes/footer.php';
?>
