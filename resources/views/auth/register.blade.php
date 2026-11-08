
@extends('layouts.utama')

@section('title', 'Registrasi User - Beasiswa Cerdas')

@section('content')

<!-- NAVBAR -->
<header class="bg-white border-b border-emerald-100">

    <div class="max-w-6xl mx-auto px-5 py-5
                flex items-center justify-between gap-4">

        <a href="{{ route('beranda') }}"
           class="text-xl font-extrabold text-[#105447]">
            ✦ Beasiswa Cerdas
        </a>

        <a href="{{ route('login') }}"
           class="text-sm font-semibold text-emerald-700">
            Sudah Punya Akun? Login
        </a>

    </div>

</header>

<!-- HALAMAN REGISTRASI -->
<main class="max-w-xl mx-auto px-5 py-12">

    <div class="bg-white border border-slate-200
                rounded-3xl shadow-sm p-7">

        <!-- JUDUL -->
        <div class="text-center">

            <div class="w-14 h-14 mx-auto rounded-2xl
                        bg-emerald-100 text-[#105447]
                        flex items-center justify-center
                        text-2xl font-bold">
                BC
            </div>

            <h1 class="text-2xl font-extrabold
                       text-[#103F36] mt-5">
                Daftar Akun Beasiswa
            </h1>

            <p class="text-sm text-slate-500 mt-3">
                Buat akun User untuk mengakses
                layanan pengajuan beasiswa.
            </p>

        </div>

        <!-- INFORMASI -->
        <div class="bg-emerald-50 border border-emerald-200
                    rounded-xl p-4 mt-6 text-sm
                    text-emerald-900">

            Pendaftaran ini khusus untuk
            <strong>calon pendaftar beasiswa (User)</strong>.

            Akun Admin tidak dapat dibuat melalui
            formulir ini.

        </div>

        <!-- FORM REGISTRASI -->
        <!-- novalidate menghilangkan popup error bawaan browser -->
        <form
            id="form-register"
            method="POST"
            action="{{ route('register.proses') }}"
            class="mt-7 space-y-5"
            novalidate
        >

            @csrf

            <!-- ===========================
                 NAMA LENGKAP
            ============================ -->
            <div>

                <label for="nama_lengkap"
                       class="block text-sm font-semibold mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama_lengkap"
                    id="nama_lengkap"
                    value="{{ old('nama_lengkap') }}"
                    maxlength="100"
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                    required
                    aria-invalid="{{ $errors->has('nama_lengkap') ? 'true' : 'false' }}"
                    aria-describedby="error-nama_lengkap"
                    class="w-full border rounded-xl px-4 py-3
                           outline-none focus:border-emerald-600
                           {{ $errors->has('nama_lengkap')
                              ? 'border-red-500 bg-red-50'
                              : 'border-slate-300' }}"
                >

                <p
                    id="error-nama_lengkap"
                    class="mt-2 text-sm font-medium
                           text-red-600
                           {{ $errors->has('nama_lengkap') ? '' : 'hidden' }}"
                    role="alert"
                    aria-live="polite"
                >
                    {{ $errors->first('nama_lengkap') }}
                </p>

            </div>

            <!-- ===========================
                 USERNAME
            ============================ -->
            <div>

                <label for="username"
                       class="block text-sm font-semibold mb-2">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    value="{{ old('username') }}"
                    minlength="3"
                    maxlength="50"
                    autocomplete="username"
                    placeholder="Contoh: siswa_01"
                    required
                    aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                    aria-describedby="error-username"
                    class="w-full border rounded-xl px-4 py-3
                           outline-none focus:border-emerald-600
                           {{ $errors->has('username')
                              ? 'border-red-500 bg-red-50'
                              : 'border-slate-300' }}"
                >

                <p class="text-xs text-slate-500 mt-2">
                    Gunakan 3–50 karakter: huruf,
                    angka, atau garis bawah (_).
                </p>

                <p
                    id="error-username"
                    class="mt-2 text-sm font-medium
                           text-red-600
                           {{ $errors->has('username') ? '' : 'hidden' }}"
                    role="alert"
                    aria-live="polite"
                >
                    {{ $errors->first('username') }}
                </p>

            </div>

            <!-- ===========================
                 EMAIL
            ============================ -->
            <div>

                <label for="email"
                       class="block text-sm font-semibold mb-2">
                    Alamat Gmail
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    maxlength="254"
                    autocomplete="email"
                    placeholder="contoh@gmail.com"
                    required
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    aria-describedby="error-email"
                    class="w-full border rounded-xl px-4 py-3
                           outline-none focus:border-emerald-600
                           {{ $errors->has('email')
                              ? 'border-red-500 bg-red-50'
                              : 'border-slate-300' }}"
                >

                <p class="text-xs text-slate-500 mt-2">
                    Gunakan alamat yang berakhiran @gmail.com.
                </p>

                <p
                    id="error-email"
                    class="mt-2 text-sm font-medium
                           text-red-600
                           {{ $errors->has('email') ? '' : 'hidden' }}"
                    role="alert"
                    aria-live="polite"
                >
                    {{ $errors->first('email') }}
                </p>

            </div>

            <!-- ===========================
                 PASSWORD
            ============================ -->
            <div>

                <label for="password"
                       class="block text-sm font-semibold mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    minlength="10"
                    maxlength="72"
                    autocomplete="new-password"
                    placeholder="Buat password"
                    required
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    aria-describedby="error-password"
                    class="w-full border rounded-xl px-4 py-3
                           outline-none focus:border-emerald-600
                           {{ $errors->has('password')
                              ? 'border-red-500 bg-red-50'
                              : 'border-slate-300' }}"
                >

                <p class="text-xs text-slate-500 mt-2 leading-6">
                    Minimal 10 karakter, maksimal 72,
                    harus mengandung huruf besar,
                    huruf kecil, dan angka.
                </p>

                <!-- NOTIFIKASI PASSWORD -->
                <p
                    id="error-password"
                    class="mt-2 text-sm font-medium
                           text-red-600
                           {{ $errors->has('password') ? '' : 'hidden' }}"
                    role="alert"
                    aria-live="polite"
                >
                    {{ $errors->first('password') }}
                </p>

            </div>

            <!-- ===========================
                 KONFIRMASI PASSWORD
            ============================ -->
            <div>

                <label for="password_confirmation"
                       class="block text-sm font-semibold mb-2">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    minlength="10"
                    maxlength="72"
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    required
                    aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                    aria-describedby="error-password_confirmation"
                    class="w-full border rounded-xl px-4 py-3
                           outline-none focus:border-emerald-600
                           {{ $errors->has('password_confirmation')
                              ? 'border-red-500 bg-red-50'
                              : 'border-slate-300' }}"
                >

                <!-- NOTIFIKASI KONFIRMASI -->
                <p
                    id="error-password_confirmation"
                    class="mt-2 text-sm font-medium
                           text-red-600
                           {{ $errors->has('password_confirmation') ? '' : 'hidden' }}"
                    role="alert"
                    aria-live="polite"
                >
                    {{ $errors->first('password_confirmation') }}
                </p>

            </div>

            <!-- TAMPILKAN PASSWORD -->
            <label class="flex items-center gap-3
                          text-sm text-slate-600 cursor-pointer">

                <input
                    type="checkbox"
                    id="lihat_password"
                    class="rounded"
                >

                Tampilkan password dan konfirmasi password

            </label>

            <!-- TOMBOL DAFTAR -->
            <button
                type="submit"
                class="w-full bg-[#116759]
                       hover:bg-[#0E5247]
                       text-white font-bold
                       rounded-xl px-5 py-3"
            >
                Daftar Akun User
            </button>

        </form>

        <!-- LINK LOGIN -->
        <p class="text-center text-sm text-slate-600 mt-7">

            Sudah mempunyai akun?

            <a href="{{ route('login') }}"
               class="font-bold text-emerald-700 hover:underline">
                Login di sini
            </a>

        </p>

    </div>

</main>

@endsection

<!-- =====================================
     JAVASCRIPT VALIDASI FORMULIR
===================================== -->

@push('scripts')

<script>
    // Mengambil formulir
    const formRegister = document.getElementById('form-register');

    // Daftar kolom yang diperiksa
    const daftarKolom = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'password_confirmation'
    ];

    // =====================================
    // MEMBUAT PESAN VALIDASI
    // =====================================

    function cekIsiKolom(namaKolom) {

        const kolom = document.getElementById(namaKolom);
        const nilai = kolom.value;
        const teks = nilai.trim();

        // Nama lengkap
        if (namaKolom === 'nama_lengkap') {

            if (teks === '') {
                return 'Nama lengkap wajib diisi.';
            }

            if ([...teks].length < 3) {
                return 'Nama lengkap minimal 3 karakter.';
            }

            if ([...teks].length > 100) {
                return 'Nama lengkap maksimal 100 karakter.';
            }
        }

        // Username
        if (namaKolom === 'username') {

            if (teks === '') {
                return 'Username wajib diisi.';
            }

            if (teks.length < 3) {
                return 'Username minimal 3 karakter.';
            }

            if (teks.length > 50) {
                return 'Username maksimal 50 karakter.';
            }

            if (!/^[a-zA-Z0-9_]+$/.test(teks)) {
                return 'Username hanya boleh huruf, angka, dan garis bawah (_).';
            }
        }

        // Email
        if (namaKolom === 'email') {

            if (teks === '') {
                return 'Alamat Gmail wajib diisi.';
            }

            if (teks.length > 254) {
                return 'Alamat Gmail terlalu panjang.';
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(teks)) {
                return 'Format alamat email tidak valid.';
            }

            if (!teks.toLowerCase().endsWith('@gmail.com')) {
                return 'Email harus menggunakan alamat @gmail.com.';
            }
        }

        // Password
        if (namaKolom === 'password') {

            if (nilai === '') {
                return 'Password wajib diisi.';
            }

            if ([...nilai].length < 10) {
                return 'Password minimal harus 10 karakter.';
            }

            if ([...nilai].length > 72) {
                return 'Password maksimal 72 karakter.';
            }

            if (!/[A-Z]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 huruf besar.';
            }

            if (!/[a-z]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 huruf kecil.';
            }

            if (!/[0-9]/.test(nilai)) {
                return 'Password harus mengandung minimal 1 angka.';
            }
        }

        // Konfirmasi password
        if (namaKolom === 'password_confirmation') {

            const password = document.getElementById('password').value;

            if (nilai === '') {
                return 'Konfirmasi password wajib diisi.';
            }

            if (nilai !== password) {
                return 'Konfirmasi password tidak sama dengan password.';
            }
        }

        // Kosong berarti tidak ada kesalahan
        return '';
    }

    // =====================================
    // MENAMPILKAN PESAN MERAH DI BAWAH KOLOM
    // =====================================

    function tampilkanValidasi(namaKolom) {

        const kolom = document.getElementById(namaKolom);

        const tempatPesan = document.getElementById(
            'error-' + namaKolom
        );

        const pesan = cekIsiKolom(namaKolom);

        if (pesan !== '') {

            // Menampilkan tulisan merah
            tempatPesan.textContent = pesan;
            tempatPesan.classList.remove('hidden');

            // Mengubah garis kolom menjadi merah
            kolom.classList.remove('border-slate-300');
            kolom.classList.add('border-red-500', 'bg-red-50');

            kolom.setAttribute('aria-invalid', 'true');

            return false;

        } else {

            // Menghilangkan pesan jika sudah benar
            tempatPesan.textContent = '';
            tempatPesan.classList.add('hidden');

            kolom.classList.remove(
                'border-red-500',
                'bg-red-50'
            );

            kolom.classList.add('border-slate-300');

            kolom.setAttribute('aria-invalid', 'false');

            return true;
        }
    }

    // =====================================
    // MEMERIKSA SAAT USER MENGETIK
    // =====================================

    daftarKolom.forEach(function(namaKolom) {

        const kolom = document.getElementById(namaKolom);

        // Memeriksa secara langsung saat mengetik
        kolom.addEventListener('input', function() {

            tampilkanValidasi(namaKolom);

            // Jika password diubah, periksa lagi
            // apakah konfirmasinya masih sama
            if (namaKolom === 'password') {

                const konfirmasi = document.getElementById(
                    'password_confirmation'
                );

                if (konfirmasi.value !== '') {

                    tampilkanValidasi(
                        'password_confirmation'
                    );
                }
            }
        });

        // Memeriksa ketika berpindah kolom
        kolom.addEventListener('blur', function() {

            tampilkanValidasi(namaKolom);

        });
    });

    // =====================================
    // MEMERIKSA SEMUA KOLOM SAAT DAFTAR
    // =====================================

    formRegister.addEventListener('submit', function(event) {

        let kolomPertamaSalah = null;

        daftarKolom.forEach(function(namaKolom) {

            const benar = tampilkanValidasi(namaKolom);

            if (!benar && kolomPertamaSalah === null) {

                kolomPertamaSalah = document.getElementById(
                    namaKolom
                );
            }
        });

        // Jika ada yang salah, jangan kirim form
        if (kolomPertamaSalah !== null) {

            event.preventDefault();

            // Fokus ke kolom pertama yang salah
            kolomPertamaSalah.focus();

            kolomPertamaSalah.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });

    // =====================================
    // TAMPILKAN / SEMBUNYIKAN PASSWORD
    // =====================================

    const lihatPassword = document.getElementById(
        'lihat_password'
    );

    lihatPassword.addEventListener('change', function() {

        const tipe = this.checked ? 'text' : 'password';

        document.getElementById('password').type = tipe;

        document.getElementById(
            'password_confirmation'
        ).type = tipe;
    });
</script>

@endpush
