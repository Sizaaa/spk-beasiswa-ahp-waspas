
# BEASISWA CERDAS
### Panduan Instalasi, Menjalankan, dan Pengelolaan Proyek GitHub

Repository ini berisi source code aplikasi **Beasiswa Cerdas**, yaitu Sistem Pendukung Keputusan Penerima Beasiswa Kurang Mampu menggunakan metode AHP dan WASPAS.

Website dikembangkan menggunakan **Laravel 13, PHP, MySQL, Blade, CSS, dan JavaScript**.

Panduan ini dibuat agar anggota kelompok atau pengembang lain dapat mengambil proyek dari GitHub, menjalankannya di komputer sendiri, serta melakukan pembaruan kode dengan benar.

**Repository:**
https://github.com/Sizaaa/spk-beasiswa-ahp-waspas

---

## 1. Aplikasi yang Wajib Disiapkan

Sebelum mengambil proyek, instal aplikasi berikut.

| Aplikasi | Fungsi |
|----------|--------|
| Git | Mengambil dan mengirim perubahan kode ke GitHub |
| Laragon | Menjalankan PHP dan MySQL secara lokal |
| PHP 8.3 atau lebih baru | Menjalankan Laravel 13 |
| Composer | Menginstal dependensi Laravel |
| Node.js dan npm | Menginstal serta membangun aset website |
| Visual Studio Code | Membuka dan mengedit source code |
| Browser | Mengakses dan menguji website |

**Link unduhan resmi:**

- Git: https://git-scm.com/downloads
- Laragon: https://laragon.org
- PHP: https://www.php.net
- Composer: https://getcomposer.org
- Node.js: https://nodejs.org
- Visual Studio Code: https://code.visualstudio.com

Pastikan Git, PHP, Composer, Node.js, dan npm sudah dapat digunakan melalui terminal.

Untuk mengecek instalasi, buka PowerShell lalu jalankan:

```powershell
git --version
php -v
composer --version
node -v
npm -v
```

Jika masing-masing menampilkan versi, berarti aplikasi telah dikenali komputer.

**Catatan:** Jika PHP belum dikenali, pastikan terminal menggunakan PHP dari Laragon dan pengaturan PATH telah sesuai.

---

## 2. Cara Mengambil Proyek dari GitHub (Clone)

Bagian ini dilakukan ketika pertama kali mengambil proyek.

### Langkah 1 — Jalankan Laragon

Buka Laragon, kemudian aktifkan layanan yang diperlukan melalui tombol **Start All**.

Pastikan MySQL berjalan.

### Langkah 2 — Buka Terminal

Gunakan terminal PowerShell melalui Visual Studio Code, Windows Terminal, atau Laragon.

Masuk ke folder proyek Laragon:

```powershell
cd C:\laragon\www
```

### Langkah 3 — Clone Repository

Salin perintah berikut:

```powershell
git clone https://github.com/Sizaaa/spk-beasiswa-ahp-waspas.git
```

Git akan membuat folder bernama:

```text
C:\laragon\www\spk-beasiswa-ahp-waspas
```

### Langkah 4 — Masuk ke Folder Proyek

```powershell
cd C:\laragon\www\spk-beasiswa-ahp-waspas
```

### Langkah 5 — Buka di Visual Studio Code

Jika perintah `code` sudah tersedia:

```powershell
code .
```

Atau buka VS Code secara manual:

**File → Open Folder → spk-beasiswa-ahp-waspas**

**Penting:** Perintah `git clone` hanya perlu dilakukan sekali untuk setiap salinan proyek. Jika folder proyek sudah ada, gunakan `git pull` untuk mengambil pembaruan, bukan clone ulang di dalam folder yang sama.

---

## 3. Cara Menginstal Laravel Setelah Clone

Setelah source code berhasil diambil, buka terminal dalam folder proyek.

### Langkah 1 — Instal Dependensi PHP

```powershell
composer install
```

Perintah ini akan mengunduh dependensi Laravel sesuai `composer.lock` dan membuat folder `vendor`.

**Jangan mengunggah folder `vendor` ke GitHub.**

### Langkah 2 — Instal Dependensi Frontend

```powershell
npm install
```

Perintah ini menginstal paket frontend yang dibutuhkan proyek.

**Jangan mengunggah folder `node_modules` ke GitHub.**

### Langkah 3 — Buat File Environment

Jika tersedia file `.env.example`, jalankan:

```powershell
Copy-Item .env.example .env
```

Kemudian buat kunci aplikasi:

```powershell
php artisan key:generate
```

File `.env` berisi konfigurasi lokal dan tidak boleh diunggah ke GitHub.

---

## 4. Cara Menghubungkan Database MySQL

### Langkah 1 — Jalankan MySQL

Buka Laragon, lalu pastikan layanan MySQL berjalan.

### Langkah 2 — Buat Database

Buka aplikasi pengelola database yang tersedia di Laragon, kemudian buat database:

```text
spk_beasiswa
```

Jika menggunakan SQL, jalankan:

```sql
CREATE DATABASE spk_beasiswa;
```

### Langkah 3 — Atur File .env

Buka file `.env` di VS Code dan sesuaikan:

```env
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
```

Pengaturan username dan password database harus mengikuti konfigurasi MySQL masing-masing komputer.

### Langkah 4 — Siapkan Tabel Database

Periksa terlebih dahulu file migrasi yang tersedia dalam folder `database/migrations`.

Jika proyek menyediakan migrasi lengkap yang sesuai dengan struktur database, jalankan:

```powershell
php artisan migrate
```

**Perhatian:**

- Jangan menjalankan `migrate:fresh` pada database yang berisi data penting.
- Repository GitHub tidak otomatis menyertakan database lokal.
- Data akun, data pendaftar, dan dokumen pribadi tidak boleh diunggah ke repository publik.
- Jika migrasi atau data awal belum lengkap, hubungi pengelola proyek untuk memperoleh prosedur penyiapan database yang benar.

---

## 5. Cara Menjalankan Website

Pastikan semua dependensi dan konfigurasi sudah selesai.

### Langkah 1 — Siapkan Folder Penyimpanan

Laravel memerlukan folder penyimpanan untuk cache, session, view, dan log.

Jika folder tersebut belum ada setelah clone, jalankan:

```powershell
New-Item -ItemType Directory -Force `
    storage\framework\cache\data, `
    storage\framework\sessions, `
    storage\framework\views, `
    storage\logs, `
    bootstrap\cache
```

Kemudian jalankan:

```powershell
php artisan storage:link
```

### Langkah 2 — Bangun Aset Website

```powershell
npm run build
```

### Langkah 3 — Bersihkan Cache Konfigurasi

```powershell
php artisan optimize:clear
```

### Langkah 4 — Jalankan Laravel

```powershell
php artisan serve
```

Jika berhasil, Laravel biasanya menampilkan:

```text
Server running on [http://127.0.0.1:8000]
```

Buka browser dan akses:

http://127.0.0.1:8000

**Catatan:** Terminal yang menjalankan `php artisan serve` harus tetap terbuka selama website digunakan.

---

## 6. Cara Mengambil Update Terbaru dari GitHub (Pull)

Jika proyek sudah pernah di-clone dan ingin mendapatkan perubahan terbaru, tidak perlu melakukan clone ulang.

### Langkah 1 — Masuk ke Folder Proyek

```powershell
cd C:\laragon\www\spk-beasiswa-ahp-waspas
```

### Langkah 2 — Periksa Perubahan Lokal

```powershell
git status
```

Pastikan pekerjaan lokal sudah disimpan atau di-commit sebelum mengambil pembaruan.

### Langkah 3 — Pindah ke Branch Utama

```powershell
git switch main
```

### Langkah 4 — Ambil Pembaruan

```powershell
git pull origin main
```

Jika terdapat perubahan pada dependensi, jalankan kembali:

```powershell
composer install
npm install
npm run build
php artisan optimize:clear
```

Jika ada migrasi database baru, periksa perubahan migrasinya sebelum menjalankan `php artisan migrate`.

---

## 7. Cara Membuat Branch untuk Mengerjakan Fitur

Untuk menghindari konflik antarpengembang, sebaiknya setiap anggota mengerjakan perubahan melalui branch masing-masing.

### Langkah 1 — Perbarui Branch Utama

```powershell
git switch main
git pull origin main
```

### Langkah 2 — Buat Branch Baru

Contoh:

```powershell
git switch -c fitur/perbaikan-dashboard
```

Nama branch dapat disesuaikan dengan pekerjaan.

Contoh lainnya:

```text
fitur/tampilan-user
fitur/perbaikan-ahp
fitur/laporan
perbaikan/navigasi
```

### Langkah 3 — Mulai Mengerjakan

Buka proyek di VS Code, lakukan perubahan, kemudian simpan file.

---

## 8. Cara Menyimpan Perubahan ke Git (Commit)

Setelah melakukan perubahan kode, ikuti langkah berikut.

### Langkah 1 — Periksa File yang Berubah

```powershell
git status
```

### Langkah 2 — Tambahkan Perubahan

Untuk menambahkan file yang sudah diperiksa:

```powershell
git add .
```

Atau tambahkan file tertentu:

```powershell
git add resources/views/admin/dashboard.blade.php
```

### Langkah 3 — Simpan Commit

```powershell
git commit -m "Perbaikan tampilan dashboard admin"
```

Gunakan pesan commit yang menjelaskan pekerjaan.

Contoh:

```text
Menambahkan halaman pengajuan beasiswa
Memperbaiki navigasi admin
Memperbarui perhitungan AHP
Memperbaiki responsivitas halaman user
Memperbarui README
```

Commit hanya menyimpan perubahan di repository Git lokal, belum mengunggahnya ke GitHub.

---

## 9. Cara Mengirim Perubahan ke GitHub (Push)

Jika sedang bekerja pada branch fitur, jalankan:

```powershell
git push -u origin fitur/perbaikan-dashboard
```

Ganti nama branch sesuai branch yang sedang digunakan.

Untuk melihat branch aktif:

```powershell
git branch --show-current
```

Setelah branch pernah dihubungkan dengan GitHub, pembaruan berikutnya biasanya cukup menggunakan:

```powershell
git push
```

Jika Git meminta autentikasi, masuk menggunakan akun GitHub yang memiliki akses ke repository.

**Jangan menggunakan `git push --force` tanpa persetujuan pengelola repository.**

---

## 10. Cara Membuat Pull Request

Setelah branch berhasil di-push:

1. Buka https://github.com/Sizaaa/spk-beasiswa-ahp-waspas
2. Cari branch yang baru diunggah.
3. Klik **Compare & pull request**.
4. Pastikan tujuan penggabungan adalah branch `main`.
5. Isi judul dan deskripsi perubahan.
6. Klik **Create pull request**.
7. Tunggu pemeriksaan dan persetujuan pengelola repository.

Jika disetujui, perubahan dapat digabungkan ke branch utama.

---

## 11. Cara Mengambil Perubahan dari Anggota Lain

Untuk mendapatkan perubahan yang sudah digabungkan ke branch `main`, jalankan:

```powershell
git switch main
git pull origin main
```

Jika ingin memperbarui branch pekerjaan dengan perubahan terbaru dari `main`, pastikan pekerjaan di branch sudah di-commit terlebih dahulu.

Kemudian:

```powershell
git switch fitur/perbaikan-dashboard
git merge main
```

Jika muncul konflik, periksa file yang ditandai oleh Git dan selesaikan konflik sebelum melakukan commit lanjutan.

---

## 12. Cara Menjalankan Pengujian

Sebelum mengirim perubahan penting, jalankan pengujian otomatis.

### Pengujian Laravel

```powershell
php artisan test
```

### Audit Sistem Beasiswa Cerdas

```powershell
php artisan beasiswa:final-check
```

### Pemeriksaan Navigasi

Jika skrip pemeriksaan tersedia:

```powershell
php scripts/cek-navigasi.php
```

Pastikan tidak ada kesalahan sebelum perubahan diajukan untuk digabungkan.

---

## 13. Kesalahan yang Sering Terjadi

### A. `git` tidak dikenali

Penyebab: Git belum diinstal atau belum masuk PATH.

Solusi:
- Instal Git.
- Tutup dan buka kembali terminal.
- Jalankan `git --version`.

### B. `Could not resolve host: github.com`

Penyebab: Komputer tidak dapat menemukan alamat GitHub melalui DNS.

Solusi:
- Periksa koneksi internet.
- Coba buka https://github.com melalui browser.
- Jalankan `ipconfig /flushdns`.
- Coba hotspot atau jaringan internet lain.
- Periksa pengaturan proxy/VPN.

### C. `composer` tidak dikenali

Penyebab: Composer belum diinstal atau PATH belum sesuai.

Solusi:
- Instal Composer.
- Pastikan PHP sudah dapat digunakan.
- Buka terminal baru.
- Jalankan `composer --version`.

### D. `vendor/autoload.php` tidak ditemukan

Penyebab: Dependensi Composer belum diinstal.

Solusi:

```powershell
composer install
```

### E. `No application encryption key has been specified`

Penyebab: `APP_KEY` belum dibuat.

Solusi:

```powershell
php artisan key:generate
```

### F. Database Connection Failed

Penyebab:
- MySQL belum berjalan.
- Database belum dibuat.
- Konfigurasi `.env` tidak sesuai.

Solusi:
- Jalankan MySQL di Laragon.
- Periksa nama database, username, dan password.
- Jalankan `php artisan optimize:clear`.

### G. Halaman Laravel tampil tetapi CSS tidak muncul

Solusi:

```powershell
npm install
npm run build
php artisan optimize:clear
```

Kemudian refresh browser menggunakan Ctrl + F5.

### H. `git push` ditolak

Penyebab yang mungkin:
- Branch GitHub memiliki perubahan lebih baru.
- Akun belum memiliki izin.
- Autentikasi GitHub bermasalah.

Solusi:
- Periksa pesan kesalahan Git.
- Pastikan sudah login menggunakan akun yang benar.
- Ambil perubahan terbaru dan selesaikan konflik jika diperlukan.
- Hindari `git push --force`.

---

## 14. Aturan Kerja Kelompok

Agar pengembangan tetap rapi, setiap anggota diharapkan:

1. Mengambil update terbaru sebelum mengerjakan fitur.
2. Menggunakan branch terpisah untuk pekerjaan baru.
3. Tidak mengubah fitur anggota lain tanpa koordinasi.
4. Menggunakan pesan commit yang jelas.
5. Menjalankan pengujian setelah mengubah kode.
6. Tidak mengunggah file `.env` atau data pribadi.
7. Tidak mengunggah folder `vendor` dan `node_modules`.
8. Tidak mengunggah database asli atau dokumen siswa.
9. Membuat Pull Request sebelum penggabungan ke branch utama.
10. Memastikan perubahan tidak merusak perhitungan AHP dan WASPAS.

---

## 15. Ringkasan Perintah Git

| Perintah | Fungsi |
|----------|--------|
| `git clone URL` | Mengambil repository untuk pertama kali |
| `git status` | Melihat perubahan file |
| `git branch` | Melihat daftar branch |
| `git branch --show-current` | Melihat branch aktif |
| `git switch main` | Berpindah ke branch utama |
| `git switch -c nama-branch` | Membuat branch baru |
| `git pull origin main` | Mengambil update terbaru |
| `git add .` | Menambahkan perubahan ke staging |
| `git commit -m "pesan"` | Menyimpan perubahan di Git lokal |
| `git push` | Mengirim perubahan ke GitHub |
| `git log --oneline` | Melihat riwayat commit |

---

## 16. Anggota Pengembang

**Kelompok 4 — Universitas Pamulang**

- Bagas Dwi Prasojo
- Muhammad Azis
- Muhammad Asyhadullah

---

## Catatan Penting

Repository ini digunakan untuk menyimpan kode sumber **Beasiswa Cerdas versi Laravel**.

Database lokal dan dokumen pengajuan tidak disediakan dalam repository publik karena alasan keamanan dan privasi.

Clone repository tidak otomatis menghasilkan akun administrator atau data penelitian. Periksa panduan penyiapan database dan data awal yang disepakati tim.

GitHub bukan layanan hosting aplikasi Laravel. Untuk menjalankan website secara online, diperlukan hosting yang mendukung PHP, Laravel, dan MySQL.

**Pastikan selalu mengambil update terbaru dan menjalankan pengujian sebelum mengirim perubahan.**
