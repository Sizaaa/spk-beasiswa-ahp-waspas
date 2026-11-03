# SPK Beasiswa — AHP & WASPAS

Website Sistem Pendukung Keputusan Kelayakan Penerima Beasiswa Kurang Mampu menggunakan metode **Analytic Hierarchy Process (AHP)** dan **Weighted Aggregated Sum Product Assessment (WASPAS)**.

Proyek ini dibuat untuk mata kuliah Sistem Informasi Manajemen, Teknik Informatika, Universitas Pamulang.

### Anggota Kelompok 4
- Bagas Dwi Prasojo
- Muhammad Azis
- Muhammad Asyhadullah

## 1. Teknologi yang Digunakan

- **PHP Native** — Bahasa pemrograman website.
- **HTML** — Struktur halaman.
- **Tailwind CSS (CDN)** — Tampilan website.
- **JavaScript** — Fitur interaktif dan simulasi.
- **Laragon** — Server lokal.
- **VS Code** — Editor kode.
- **Git & GitHub** — Pengelolaan kode dan kolaborasi.

MySQL digunakan jika diperlukan pada pengembangan berikutnya.

## 2. Cara Menjalankan Proyek

**Clone repository (khusus yang belum memiliki proyek):**

1. Masuk ke direktori `C:\laragon\www`.
2. Buka terminal pada direktori tersebut.
3. Jalankan perintah:

   `git clone https://github.com/Sizaaa/spk-beasiswa-ahp-waspas.git`

4. Masuk ke folder proyek:

   `cd spk-beasiswa-ahp-waspas`

5. Buka folder proyek melalui VS Code.
6. Jalankan Apache di Laragon.
7. Akses `http://localhost/spk-beasiswa-ahp-waspas/`.

Ganti `URL_REPOSITORY` dengan URL GitHub proyek ini.

## 3. Cara Mengambil Update Terbaru

Sebelum mulai mengerjakan tugas, jalankan:

`git switch main`

`git pull origin main`

Perintah ini digunakan untuk mengambil pembaruan terbaru dari branch utama.

## 4. Cara Membuat Branch Baru

Setiap anggota mengerjakan tugas menggunakan branch masing-masing.

Contoh:

`git switch -c fitur/pendahuluan`

Sesuaikan nama branch dengan fitur atau halaman yang dikerjakan.

Jangan mengerjakan perubahan langsung di branch `main`.

## 5. Cara Membuat atau Menambahkan File

1. Buka proyek melalui VS Code.
2. Pada bagian Explorer, pilih folder tujuan.
3. Klik kanan, lalu pilih **New File**.
4. Masukkan nama file, misalnya `pendahuluan.php`.
5. Tambahkan kode sesuai tugas.
6. Simpan menggunakan **Ctrl + S**.
7. Jalankan dan periksa hasilnya melalui Laragon.

**Catatan:**
- File halaman baru dapat dibuat di folder utama proyek.
- Gunakan `includes/header.php`, `includes/navbar.php`, dan `includes/footer.php` untuk bagian website yang digunakan bersama.
- Jangan menghapus atau mengubah file milik anggota lain tanpa koordinasi.
- Gunakan isi jurnal sebagai sumber konten website.

## 6. Cara Menyimpan Perubahan ke Git

Setelah selesai mengerjakan kode, jalankan perintah berikut melalui terminal VS Code.

**Cek perubahan file:**

`git status`

**Tambahkan semua perubahan:**

`git add .`

Atau untuk menambahkan satu file tertentu:

`git add nama_file.php`

**Simpan perubahan (commit):**

`git commit -m "Menambahkan halaman pendahuluan"`

Gunakan pesan commit sesuai pekerjaan yang dilakukan.

## 7. Cara Mengirim Perubahan ke GitHub

Setelah commit berhasil, jalankan:

`git push -u origin fitur/pendahuluan`

Sesuaikan nama branch dengan branch yang sedang digunakan.

Jika branch sudah pernah diunggah dan terhubung dengan GitHub, pembaruan berikutnya cukup menggunakan:

`git push`

## 8. Cara Membuat Pull Request

Setelah perubahan berhasil dikirim ke GitHub:

1. Buka repository proyek di GitHub.
2. Pilih branch yang sudah diunggah.
3. Klik **Compare & pull request**.
4. Pastikan tujuan penggabungan adalah branch `main`.
5. Tulis penjelasan singkat mengenai perubahan.
6. Klik **Create pull request**.

Perubahan akan diperiksa terlebih dahulu sebelum digabungkan ke branch utama.

**Jangan melakukan merge sendiri tanpa persetujuan pengelola proyek.**

## 9. Aturan Pengerjaan Kelompok

- Gunakan branch terpisah untuk setiap tugas.
- Selalu ambil pembaruan terbaru sebelum memulai pekerjaan baru.
- Jangan langsung melakukan push ke `main`.
- Gunakan nama file dan branch yang jelas.
- Lakukan pengujian sebelum commit dan push.
- Jangan mengubah nilai perhitungan yang sudah ditetapkan dalam jurnal.
- Semua halaman yang menampilkan data calon penerima wajib mencantumkan keterangan bahwa data bersifat simulasi.
- Jika terjadi konflik kode, diskusikan sebelum melakukan merge.

## 10. Catatan Proyek

Website dikembangkan berdasarkan penelitian menggunakan data simulasi, bukan data penerima beasiswa yang sebenarnya.

Fitur dan halaman website akan terus diperbarui sesuai perkembangan tugas kelompok.

---

**Kelompok 4 — Universitas Pamulang — 2026**