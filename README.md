# Beasiswa Cerdas
## Panduan Instalasi, Menjalankan Website, dan Kolaborasi GitHub

Repository ini berisi source code website **Beasiswa Cerdas**, sebuah Sistem Pendukung Keputusan penerima beasiswa kurang mampu menggunakan metode AHP dan WASPAS.

Website dibangun menggunakan **Laravel 13, PHP, MySQL, Blade, CSS, dan JavaScript**.

Panduan ini ditujukan untuk anggota Kelompok 4 dan pengembang yang ingin mengambil proyek, menjalankannya di komputer lokal, serta bekerja sama melalui GitHub.

**Repository:** https://github.com/Sizaaa/spk-beasiswa-ahp-waspas

---

## 1. Aplikasi yang Harus Diinstal

Sebelum menjalankan proyek, siapkan aplikasi berikut:

| Aplikasi | Fungsi | Link |
|---|---|---|
| Git | Mengambil dan mengirim kode | https://git-scm.com |
| Laragon | Server PHP dan MySQL lokal | https://laragon.org |
| PHP 8.3 atau lebih baru | Menjalankan Laravel 13 | https://www.php.net |
| Composer | Menginstal dependensi Laravel | https://getcomposer.org |
| Node.js dan npm | Mengelola aset frontend | https://nodejs.org |
| VS Code | Mengedit kode website | https://code.visualstudio.com |

Buka PowerShell dan periksa instalasi:

    git --version
    php -v
    composer --version
    node -v
    npm -v

Jika versi masing-masing aplikasi muncul, berarti terminal sudah mengenalinya.

---

## 2. Cara Mengambil Proyek dari GitHub (Clone)

Clone dilakukan ketika pertama kali mengambil proyek.

**Langkah 1:** Buka Laragon dan jalankan MySQL.

**Langkah 2:** Buka PowerShell.

Masuk ke folder Laragon:

    cd C:\laragon\www

**Langkah 3:** Clone repository:

    git clone https://github.com/Sizaaa/spk-beasiswa-ahp-waspas.git

**Langkah 4:** Masuk ke folder proyek:

    cd spk-beasiswa-ahp-waspas

**Langkah 5:** Buka proyek menggunakan VS Code:

    code .

Jika perintah `code` tidak dikenali, buka VS Code lalu pilih File > Open Folder.

PENTING: Clone hanya dilakukan sekali. Untuk mengambil perubahan terbaru, gunakan `git pull`.

---

## 3. Cara Menginstal Dependensi Laravel

Pastikan terminal berada di folder proyek.

Instal dependensi PHP:

    composer install

Instal dependensi frontend:

    npm install

Folder `vendor` dan `node_modules` dibuat secara lokal dan tidak perlu diunggah ke GitHub.

---

## 4. Cara Mengatur File .env

Setelah clone, buat file environment lokal.

Pada PowerShell:

    Copy-Item .env.example .env

Kemudian jalankan:

    php artisan key:generate

Buka file `.env` dan sesuaikan database:

    APP_NAME="Beasiswa Cerdas"
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://127.0.0.1:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=spk_beasiswa
    DB_USERNAME=root
    DB_PASSWORD=

Gunakan username dan password MySQL sesuai komputer masing-masing.

Jangan pernah mengunggah file `.env` ke GitHub.

---

## 5. Cara Menyiapkan Database

1. Jalankan MySQL melalui Laragon.
2. Buka pengelola database.
3. Buat database bernama `spk_beasiswa`.
4. Sesuaikan konfigurasi `.env`.
5. Periksa migrasi dalam folder `database/migrations`.

Jika migrasi sudah lengkap dan sesuai dengan struktur proyek, jalankan:

    php artisan migrate

PERHATIAN:

- Jangan menggunakan `php artisan migrate:fresh` pada database berisi data penting.
- Database pribadi dan dokumen pendaftar tidak tersedia dalam repository publik.
- Jika struktur database atau data awal belum tersedia, hubungi pengelola proyek.
- Clone tidak otomatis menyediakan akun administrator.

---

## 6. Cara Menjalankan Website

Pastikan dependensi sudah diinstal dan database sudah dikonfigurasi.

Jika direktori storage belum tersedia, buat menggunakan PowerShell:

    New-Item -ItemType Directory -Force storage\framework\cache\data, storage\framework\sessions, storage\framework\views, storage\logs, bootstrap\cache

Siapkan tautan penyimpanan:

    php artisan storage:link

Bangun aset frontend:

    npm run build

Bersihkan cache:

    php artisan optimize:clear

Jalankan Laravel:

    php artisan serve

Buka website di browser:

http://127.0.0.1:8000

Terminal `php artisan serve` perlu tetap terbuka selama website digunakan.

---

## 7. Cara Mengambil Update Terbaru (Git Pull)

Jika proyek sudah pernah di-clone, jangan clone ulang.

Buka terminal di folder proyek:

    cd C:\laragon\www\spk-beasiswa-ahp-waspas

Periksa perubahan lokal:

    git status

Pastikan pekerjaan lokal sudah disimpan atau di-commit.

Pindah ke branch utama:

    git switch main

Ambil perubahan terbaru:

    git pull origin main

Jika ada pembaruan dependensi, jalankan:

    composer install
    npm install
    npm run build
    php artisan optimize:clear

Periksa migrasi jika ada perubahan database.

---

## 8. Cara Membuat Branch untuk Pekerjaan Baru

Untuk bekerja secara berkelompok, sebaiknya gunakan branch terpisah.

Perbarui branch utama:

    git switch main
    git pull origin main

Buat branch baru:

    git switch -c fitur/perbaikan-dashboard

Contoh nama branch lain:

- `fitur/pengajuan-beasiswa`
- `fitur/perhitungan-ahp`
- `fitur/laporan`
- `perbaikan/tampilan-user`

Gunakan nama sesuai pekerjaan.

---

## 9. Cara Menyimpan Perubahan (Git Commit)

Setelah mengedit file menggunakan VS Code:

**Periksa perubahan:**

    git status

**Tambahkan file:**

    git add .

Atau tambahkan file tertentu:

    git add resources/views/admin/dashboard.blade.php

**Simpan commit:**

    git commit -m "Perbaikan tampilan dashboard admin"

Gunakan pesan commit yang menjelaskan perubahan.

Jangan memasukkan file `.env`, dokumen pribadi, atau data database ke staging.

---

## 10. Cara Mengirim Perubahan ke GitHub (Git Push)

Untuk mengunggah branch baru:

    git push -u origin fitur/perbaikan-dashboard

Sesuaikan nama branch.

Jika branch sudah pernah di-push:

    git push

Periksa branch yang sedang digunakan:

    git branch --show-current

Jangan menggunakan `git push --force` tanpa persetujuan pengelola proyek.

---

## 11. Cara Membuat Pull Request

Setelah branch berhasil di-push:

1. Buka https://github.com/Sizaaa/spk-beasiswa-ahp-waspas
2. Pilih branch pekerjaan.
3. Klik **Compare & pull request**.
4. Pastikan tujuan penggabungan adalah `main`.
5. Isi judul dan deskripsi perubahan.
6. Klik **Create pull request**.
7. Tunggu pemeriksaan sebelum perubahan digabungkan.

---

## 12. Cara Mengambil Perubahan Anggota Lain

Setelah pekerjaan anggota lain digabungkan ke `main`:

    git switch main
    git pull origin main

Jika ingin memasukkan pembaruan `main` ke branch pekerjaan sendiri:

    git switch fitur/perbaikan-dashboard
    git merge main

Pastikan pekerjaan sudah disimpan sebelum melakukan merge.

Jika terjadi konflik, periksa file yang bermasalah sebelum melanjutkan.

---

## 13. Cara Menguji Website

Jalankan pengujian Laravel:

    php artisan test

Jalankan pemeriksaan final:

    php artisan beasiswa:final-check

Jika tersedia, jalankan pemeriksaan navigasi:

    php scripts/cek-navigasi.php

Periksa juga fungsi website langsung di browser sebelum mengirim perubahan.

---

## 14. Solusi Kesalahan yang Sering Terjadi

### GitHub Tidak Bisa Diakses

Jika muncul:

    Could not resolve host: github.com

Periksa koneksi internet, DNS, proxy/VPN, dan coba buka GitHub melalui browser.

### Composer Tidak Dikenali

Periksa pemasangan Composer dan PHP:

    composer --version
    php -v

### Folder vendor Tidak Ada

Jalankan:

    composer install

### APP_KEY Belum Ada

Jalankan:

    php artisan key:generate

### Database Tidak Terhubung

Periksa MySQL pada Laragon dan pengaturan database pada `.env`.

Kemudian jalankan:

    php artisan optimize:clear

### Tampilan CSS Tidak Muncul

Jalankan:

    npm install
    npm run build

Kemudian tekan Ctrl + F5 pada browser.

### Git Push Ditolak

Periksa autentikasi, izin repository, branch aktif, dan perubahan terbaru pada GitHub.

Jangan langsung menggunakan `git push --force`.

---

## 15. Ringkasan Perintah Git

| Perintah | Keterangan |
|---|---|
| `git clone URL` | Mengambil proyek pertama kali |
| `git status` | Memeriksa perubahan |
| `git switch main` | Berpindah ke branch utama |
| `git pull origin main` | Mengambil update |
| `git switch -c nama-branch` | Membuat branch |
| `git add .` | Menambahkan perubahan |
| `git commit -m "pesan"` | Menyimpan commit lokal |
| `git push` | Mengirim perubahan ke GitHub |
| `git branch --show-current` | Melihat branch aktif |
| `git log --oneline` | Melihat riwayat commit |

---

## 16. Aturan Kerja Kelompok

1. Selalu mengambil update sebelum mengerjakan fitur.
2. Gunakan branch terpisah untuk pekerjaan baru.
3. Periksa perubahan sebelum commit.
4. Jangan mengunggah file `.env`.
5. Jangan mengunggah data pribadi atau dokumen siswa.
6. Jangan mengunggah folder `vendor` atau `node_modules`.
7. Jalankan pengujian sebelum mengirim perubahan.
8. Jangan mengubah fitur anggota lain tanpa koordinasi.
9. Buat Pull Request sebelum menggabungkan pekerjaan.
10. Pastikan perhitungan AHP dan WASPAS tidak terganggu.

---

## 17. Tim Pengembang

**Kelompok 4 — Universitas Pamulang**

- Bagas Dwi Prasojo
- Muhammad Azis
- Muhammad Asyhadullah

---

**Catatan:** GitHub digunakan untuk menyimpan kode sumber. Untuk menjalankan website Laravel secara online, diperlukan hosting yang mendukung PHP dan MySQL.

**Beasiswa Cerdas — Laravel 13, AHP dan WASPAS**
