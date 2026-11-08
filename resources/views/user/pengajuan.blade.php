
@extends('layouts.utama')

@section('title', 'Pengajuan Beasiswa - Beasiswa Cerdas')

@push('styles')
<style>
    .bcf-page {
        max-width: 1460px;
        margin: auto;
        padding: 32px 28px 48px;
        color: #183d34;
    }

    .bcf-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .bcf-title {
        font-size: clamp(25px, 3vw, 36px);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.2;
    }

    .bcf-desc {
        margin-top: 7px;
        color: #526675;
        font-size: 14px;
        line-height: 1.7;
    }

    .bcf-status {
        min-width: 238px;
        background: #f6fbf9;
        border: 1px solid #d7e8e0;
        border-radius: 15px;
        padding: 15px 24px;
        box-shadow: 0 4px 15px rgba(19, 66, 51, .05);
    }

    .bcf-status span {
        display: block;
        font-size: 12px;
        color: #526675;
    }

    .bcf-status strong {
        display: block;
        margin-top: 4px;
        font-size: 16px;
        color: #0e4b3b;
    }

    .bcf-grid {
        display: grid;
        grid-template-columns:
            minmax(0, 1.65fr)
            minmax(320px, .97fr);
        gap: 18px;
        align-items: start;
    }

    .bcf-col {
        display: grid;
        gap: 18px;
        min-width: 0;
    }

    .bcf-card {
        background: #fff;
        border: 1px solid #dbe7e4;
        border-radius: 18px;
        padding: 26px 29px;
        box-shadow: 0 5px 18px rgba(19, 56, 50, .035);
        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .bcf-card:hover {
        transform: translateY(-2px);
        border-color: #c1dcd1;
        box-shadow: 0 12px 30px rgba(19, 56, 50, .08);
    }

    .bcf-card h2 {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #103f36;
    }

    .bcf-rule {
        width: 55px;
        height: 3px;
        margin: 13px 0 20px;
        background: #11986c;
        border-radius: 10px;
    }

    .bcf-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 20px;
    }

    .bcf-field {
        min-width: 0;
    }

    .bcf-field.wide {
        grid-column: 1 / -1;
    }

    .bcf-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 650;
        color: #172b28;
    }

    .bcf-required {
        color: #c83943;
    }

    .bcf-control {
        display: block;
        width: 100%;
        height: 40px;
        border: 1px solid #b9cbd4;
        border-radius: 9px;
        background: #fff;
        padding: 8px 13px;
        font-size: 14px;
        color: #18303b;
        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    textarea.bcf-control {
        height: 70px;
        resize: vertical;
    }

    .bcf-control:focus {
        outline: none;
        border-color: #168663;
        box-shadow: 0 0 0 3px rgba(22, 134, 99, .12);
    }

    .bcf-control:disabled {
        background: #f2f5f4;
        color: #677671;
        cursor: not-allowed;
    }

    .bcf-control.bcf-invalid {
        border-color: #ea454c !important;
        box-shadow: 0 0 0 2px rgba(234, 69, 76, .07) !important;
    }

    .bcf-field-error {
        font-size: 12px;
        line-height: 1.5;
        color: #c72734;
        margin: 5px 0 0;
    }

    .bcf-field-error[hidden],
    .bcf-alert[hidden],
    .bcf-modal[hidden],
    .bcf-skeleton[hidden] {
        display: none !important;
    }

    .bcf-sub {
        font-size: 12px;
        line-height: 1.65;
        color: #64748b;
        margin-bottom: 15px;
    }

    .bcf-upload {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        border: 1px solid #d2dde2;
        background: #fff;
        border-radius: 12px;
        padding: 11px 15px;
        min-height: 64px;
        margin-top: 10px;
        transition:
            border-color .2s,
            background .2s,
            transform .2s;
    }

    .bcf-upload:hover {
        border-color: #8dcab4;
        background: #f7fcfa;
        transform: translateY(-1px);
    }

    .bcf-upload-info {
        min-width: 0;
    }

    .bcf-upload-name {
        font-size: 13px;
        font-weight: 700;
        color: #192e28;
    }

    .bcf-upload-caption {
        font-size: 12px;
        color: #607078;
        margin-top: 3px;
        overflow-wrap: anywhere;
    }

    .bcf-file-button {
        flex-shrink: 0;
        background: #d6f5e8;
        color: #084c3b;
        padding: 9px 13px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition:
            background .2s,
            transform .2s;
    }

    .bcf-file-button:hover {
        background: #bdf0db;
        transform: translateY(-1px);
    }

    .bcf-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .bcf-upload:focus-within {
        outline: 3px solid rgba(22, 134, 99, .18);
    }

    .bcf-consent {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #dce5e2;
        background: #fbfdfc;
        font-size: 13px;
        line-height: 1.7;
        color: #263d35;
    }

    .bcf-consent input {
        margin-top: 4px;
        width: 18px;
        height: 18px;
        accent-color: #0f8766;
        flex-shrink: 0;
    }

    .bcf-consent.is-invalid {
        border-color: #eb6872;
        background: #fff7f7;
    }

    .bcf-buttons {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 12px;
        margin-top: 17px;
    }

    .bcf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 45px;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition:
            transform .19s ease,
            box-shadow .19s ease,
            background .19s ease;
    }

    .bcf-btn:hover:not(:disabled) {
        transform: translateY(-2px);
    }

    .bcf-btn:active:not(:disabled) {
        transform: translateY(0) scale(.985);
    }

    .bcf-btn:focus-visible {
        outline: 3px solid #9ad9c0;
        outline-offset: 2px;
    }

    .bcf-btn-quiet {
        background: #edf2f3;
        color: #263c43;
        border-color: #dce6e8;
    }

    .bcf-btn-quiet:hover {
        background: #e2ebec;
    }

    .bcf-btn-primary {
        background: linear-gradient(115deg, #0b5c47, #119468);
        color: white;
        box-shadow: 0 3px 12px rgba(7, 107, 73, .12);
    }

    .bcf-btn-primary:hover {
        box-shadow: 0 8px 20px rgba(7, 107, 73, .25);
    }

    .bcf-note {
        font-size: 12px;
        line-height: 1.8;
        color: #637771;
        margin-top: 12px;
    }

    .bcf-alert {
        padding: 14px 17px;
        border: 1px solid #f0bcc1;
        background: #fff6f6;
        color: #a12835;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.75;
        margin-bottom: 18px;
    }

    .bcf-alert.success {
        border-color: #b8e1d2;
        background: #f2fbf7;
        color: #16664c;
    }

    /* Modal seperti referensi gambar */

    .bcf-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(21, 42, 37, .45);
        backdrop-filter: blur(3px);
    }

    .bcf-dialog {
        width: min(100%, 465px);
        background: white;
        border: 1px solid #e1ebe5;
        border-radius: 24px;
        padding: 30px 36px;
        box-shadow: 0 22px 75px rgba(20, 54, 40, .22);
        animation: bcf-show .22s ease;
    }

    .bcf-dialog-accent {
        width: 56px;
        height: 4px;
        background: linear-gradient(90deg, #00955f, #67c9a2);
        border-radius: 5px;
        margin-bottom: 19px;
    }

    .bcf-dialog-title {
        color: #0a4c3a;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -.035em;
    }

    .bcf-dialog p {
        font-size: 16px;
        line-height: 1.65;
        color: #53616b;
        margin-top: 12px;
    }

    .bcf-modal-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    /* Skeleton shimmer horizontal */

    .bcf-skeleton {
        margin-top: 18px;
        display: flex;
        align-items: center;
        gap: 26px;
        background: #fff;
        border: 1px solid #dce9e4;
        border-radius: 17px;
        padding: 24px;
    }

    .bcf-skeleton-label {
        font-weight: 650;
        white-space: nowrap;
        font-size: 14px;
        color: #173b31;
    }

    .bcf-shimmer-group {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .bcf-shimmer {
        height: 16px;
        border-radius: 20px;
        background: linear-gradient(
            100deg,
            #e1efe9 20%,
            #f9fffc 45%,
            #d9ece3 65%
        );
        background-size: 220% 100%;
        animation: bcf-flow 1.3s ease-in-out infinite;
    }

    @keyframes bcf-show {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.985);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes bcf-flow {
        from { background-position: 110% 0; }
        to { background-position: -110% 0; }
    }

    @media (max-width: 1000px) {
        .bcf-grid {
            grid-template-columns: minmax(0, 1fr);
        }
        .bcf-status {
            min-width: 205px;
        }
    }

    @media (max-width: 640px) {
        .bcf-page { padding: 22px 15px 36px; }
        .bcf-hero { align-items: flex-start; flex-direction: column; }
        .bcf-status { width: 100%; }
        .bcf-card { padding: 21px 17px; }
        .bcf-fields { grid-template-columns: 1fr; }
        .bcf-field.wide { grid-column: auto; }
        .bcf-buttons { grid-template-columns: 1fr 1fr; }
        .bcf-dialog { padding: 25px 22px; }
        .bcf-dialog-title { font-size: 23px; }
        .bcf-skeleton { flex-direction: column; align-items: stretch; }
        .bcf-shimmer-group { gap: 8px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .bcf-card, .bcf-upload,
        .bcf-btn, .bcf-file-button {
            transition: none;
        }
        .bcf-card:hover, .bcf-btn:hover,
        .bcf-upload:hover {
            transform: none;
        }
        .bcf-dialog, .bcf-shimmer {
            animation: none;
        }
    }
</style>
@endpush

@section('content')

@php
    $labelStatus = [
        'belum_mengisi' => 'Belum Mengajukan',
        'draf' => 'Draf Tersimpan',
        'diajukan' => 'Diajukan',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak pada Verifikasi',
    ];

    $dataDiri = [
        'nama_lengkap' => [
            'Nama Lengkap', 'text', 'Nama sesuai dokumen'
        ],
        'nisn' => [
            'NISN', 'text', 'Masukkan 10 digit NISN'
        ],
        'asal_sekolah' => [
            'Asal Sekolah', 'text', 'Nama sekolah'
        ],
        'kelas' => [
            'Kelas', 'text', 'Contoh: XII IPA 1'
        ],
        'nomor_hp' => [
            'Nomor HP', 'tel', 'Contoh: 081234567890'
        ],
    ];

    $penghasilanAsli = preg_replace(
        '/\D/',
        '',
        (string) old(
            'penghasilan_orang_tua',
            $form['penghasilan_orang_tua'] ?? ''
        )
    );

    $rupiah = $penghasilanAsli === ''
        ? ''
        : 'Rp ' . number_format(
            (float) $penghasilanAsli,
            0,
            ',',
            '.'
        );
@endphp

<main class="bcf-page">

    <div class="bcf-hero">
        <div>
            <h1 class="bcf-title">
                Formulir Pengajuan Beasiswa
            </h1>

            <p class="bcf-desc">
                Lengkapi formulir dan dokumen. Simpan Draf jika
                belum selesai, atau Kirim Pendaftaran untuk
                diperiksa Admin.
            </p>
        </div>

        <div class="bcf-status">
            <span>Status Pengajuan</span>
            <strong>{{ $labelStatus[$status] ?? $status }}</strong>
        </div>
    </div>

    @if (session('sukses'))
        <div class="bcf-alert success" role="status">
            {{ session('sukses') }}
        </div>
    @endif

    @if ($errors->has('aksi'))
        <div class="bcf-alert" role="alert">
            {{ $errors->first('aksi') }}
        </div>
    @endif

    @if (
        $status === 'perlu_perbaikan'
        && $pengajuan?->catatan_admin
    )
        <div class="bcf-alert" role="status">
            <strong>Catatan Admin:</strong>
            {{ $pengajuan->catatan_admin }}
        </div>
    @endif

    <form
        id="bcf-form"
        action="{{ route('user.pengajuan.simpan') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf

        <div class="bcf-grid">

            <!-- KOLOM KIRI -->
            <div class="bcf-col">

                <!-- DATA DIRI -->
                <section class="bcf-card">

                    <h2>Data Diri</h2>
                    <div class="bcf-rule"></div>

                    <div class="bcf-fields">

                        @foreach ($dataDiri as $nama => $opsi)

                            <div class="bcf-field">

                                <label
                                    class="bcf-label"
                                    for="{{ $nama }}"
                                >
                                    {{ $opsi[0] }}
                                    <span class="bcf-required">*</span>
                                </label>

                                <input
                                    class="bcf-control
                                        {{ $errors->has($nama)
                                            ? 'bcf-invalid' : '' }}"
                                    id="{{ $nama }}"
                                    name="{{ $nama }}"
                                    type="{{ $opsi[1] }}"
                                    placeholder="{{ $opsi[2] }}"
                                    value="{{ old(
                                        $nama,
                                        $form[$nama] ?? ''
                                    ) }}"
                                    @disabled(!$bolehEdit)
                                    @if ($nama === 'nisn')
                                        inputmode="numeric"
                                        maxlength="10"
                                    @endif
                                    aria-invalid="{{ $errors->has($nama)
                                        ? 'true' : 'false' }}"
                                >

                                @error($nama)
                                    <p
                                        class="bcf-field-error"
                                        data-error-for="{{ $nama }}"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        @endforeach

                        <div class="bcf-field wide">

                            <label
                                class="bcf-label"
                                for="alamat_lengkap"
                            >
                                Alamat Lengkap
                                <span class="bcf-required">*</span>
                            </label>

                            <textarea
                                class="bcf-control
                                    {{ $errors->has('alamat_lengkap')
                                        ? 'bcf-invalid' : '' }}"
                                id="alamat_lengkap"
                                name="alamat_lengkap"
                                @disabled(!$bolehEdit)
                                placeholder="Alamat lengkap siswa"
                            >{{ old(
                                'alamat_lengkap',
                                $form['alamat_lengkap'] ?? ''
                            ) }}</textarea>

                            @error('alamat_lengkap')
                                <p
                                    class="bcf-field-error"
                                    data-error-for="alamat_lengkap"
                                >
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>
                </section>

                <!-- DATA KELUARGA -->
                <section class="bcf-card">

                    <h2>Data Keluarga dan Kriteria</h2>
                    <div class="bcf-rule"></div>

                    <div class="bcf-fields">

                        <div class="bcf-field">
                            <label
                                class="bcf-label"
                                for="pekerjaan_orang_tua"
                            >
                                Pekerjaan Orang Tua / Wali
                                <span class="bcf-required">*</span>
                            </label>

                            <input
                                class="bcf-control
                                    {{ $errors->has('pekerjaan_orang_tua')
                                        ? 'bcf-invalid' : '' }}"
                                id="pekerjaan_orang_tua"
                                name="pekerjaan_orang_tua"
                                type="text"
                                value="{{ old(
                                    'pekerjaan_orang_tua',
                                    $form['pekerjaan_orang_tua'] ?? ''
                                ) }}"
                                @disabled(!$bolehEdit)
                            >

                            @error('pekerjaan_orang_tua')
                                <p
                                    class="bcf-field-error"
                                    data-error-for="pekerjaan_orang_tua"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bcf-field">
                            <label
                                class="bcf-label"
                                for="penghasilan_tampilan"
                            >
                                Penghasilan Orang Tua / Bulan (C1)
                                <span class="bcf-required">*</span>
                            </label>

                            <input
                                class="bcf-control
                                    {{ $errors->has('penghasilan_orang_tua')
                                        ? 'bcf-invalid' : '' }}"
                                id="penghasilan_tampilan"
                                inputmode="numeric"
                                type="text"
                                value="{{ $rupiah }}"
                                placeholder="Rp 1.500.000"
                                @disabled(!$bolehEdit)
                            >

                            <input
                                id="penghasilan_orang_tua"
                                name="penghasilan_orang_tua"
                                type="hidden"
                                value="{{ $penghasilanAsli }}"
                                @disabled(!$bolehEdit)
                            >

                            @error('penghasilan_orang_tua')
                                <p
                                    class="bcf-field-error"
                                    data-error-for="penghasilan_orang_tua"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bcf-field">
                            <label
                                class="bcf-label"
                                for="jumlah_tanggungan"
                            >
                                Jumlah Tanggungan (C2)
                                <span class="bcf-required">*</span>
                            </label>

                            <input
                                class="bcf-control
                                    {{ $errors->has('jumlah_tanggungan')
                                        ? 'bcf-invalid' : '' }}"
                                id="jumlah_tanggungan"
                                name="jumlah_tanggungan"
                                type="number"
                                min="0"
                                max="50"
                                step="1"
                                value="{{ old(
                                    'jumlah_tanggungan',
                                    $form['jumlah_tanggungan'] ?? ''
                                ) }}"
                                @disabled(!$bolehEdit)
                            >

                            @error('jumlah_tanggungan')
                                <p
                                    class="bcf-field-error"
                                    data-error-for="jumlah_tanggungan"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bcf-field">
                            <label
                                class="bcf-label"
                                for="nilai_akademik"
                            >
                                Rata-rata Nilai Rapor (C3)
                                <span class="bcf-required">*</span>
                            </label>

                            <input
                                class="bcf-control
                                    {{ $errors->has('nilai_akademik')
                                        ? 'bcf-invalid' : '' }}"
                                id="nilai_akademik"
                                name="nilai_akademik"
                                type="text"
                                inputmode="decimal"
                                placeholder="Contoh: 87,50"
                                value="{{ old(
                                    'nilai_akademik',
                                    $form['nilai_akademik'] ?? ''
                                ) }}"
                                @disabled(!$bolehEdit)
                            >

                            @error('nilai_akademik')
                                <p
                                    class="bcf-field-error"
                                    data-error-for="nilai_akademik"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        @foreach ([
                            'kondisi_tempat_tinggal'
                                => 'Kondisi Tempat Tinggal (C4)',
                            'prestasi_non_akademik'
                                => 'Prestasi Non-Akademik (C5)'
                        ] as $nama => $judul)

                            <div class="bcf-field">

                                <label
                                    class="bcf-label"
                                    for="{{ $nama }}"
                                >
                                    {{ $judul }}
                                    <span class="bcf-required">*</span>
                                </label>

                                <select
                                    class="bcf-control
                                        {{ $errors->has($nama)
                                            ? 'bcf-invalid' : '' }}"
                                    id="{{ $nama }}"
                                    name="{{ $nama }}"
                                    @disabled(!$bolehEdit)
                                >
                                    <option value="">Pilih skor</option>

                                    @for ($skor = 1; $skor <= 5; $skor++)
                                        <option
                                            value="{{ $skor }}"
                                            @selected(
                                                (string) old(
                                                    $nama,
                                                    $form[$nama] ?? ''
                                                ) === (string) $skor
                                            )
                                        >
                                            {{ $skor }}
                                        </option>
                                    @endfor
                                </select>

                                @error($nama)
                                    <p
                                        class="bcf-field-error"
                                        data-error-for="{{ $nama }}"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        @endforeach

                    </div>

                    <p class="bcf-note">
                        C1 merupakan kriteria Cost, sedangkan C2–C5
                        merupakan Benefit. Skor C4 lebih tinggi
                        menunjukkan kondisi tempat tinggal lebih
                        membutuhkan bantuan.
                    </p>

                </section>
            </div>

            <!-- KOLOM KANAN -->
            <div class="bcf-col">

                <!-- DOKUMEN PENDUKUNG -->
                <section class="bcf-card">

                    <h2>Dokumen Pendukung</h2>

                    <p class="bcf-sub">
                        Format PDF, JPG, atau PNG.
                        Maksimal 2 MB per berkas.
                    </p>

                    @foreach ($dokumen as $jenis => $item)

                        <div class="bcf-upload">

                            <div class="bcf-upload-info">

                                <div class="bcf-upload-name">

                                    {{ $item['nama'] }}

                                    @if ($item['wajib'])
                                        <span class="bcf-required">*</span>
                                    @else
                                        <span style="font-weight:400">
                                            (opsional)
                                        </span>
                                    @endif

                                </div>

                                <p
                                    class="bcf-upload-caption"
                                    id="bcf-nama-{{ $jenis }}"
                                >
                                    {{ $item['ada']
                                        ? 'Berkas tersimpan'
                                        : 'Belum ada berkas' }}
                                </p>

                                @if ($item['ada'])
                                    <a
                                        href="{{ route(
                                            'user.berkas',
                                            ['jenis' => $jenis]
                                        ) }}"
                                        style="
                                            font-size:12px;
                                            color:#087458;
                                            text-decoration:underline;
                                        "
                                    >
                                        Unduh berkas lama
                                    </a>
                                @endif

                                @error($jenis)
                                    <p
                                        class="bcf-field-error"
                                        data-error-for="{{ $jenis }}"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            @if ($bolehEdit)

                                <label
                                    class="bcf-file-button"
                                    for="{{ $jenis }}"
                                >
                                    Pilih Berkas
                                </label>

                                <input
                                    class="bcf-file-input"
                                    id="{{ $jenis }}"
                                    name="{{ $jenis }}"
                                    type="file"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    data-sudah-ada="{{ $item['ada']
                                        ? 1 : 0 }}"
                                >

                            @endif

                        </div>

                    @endforeach

                </section>

                <!-- PERSETUJUAN DAN TOMBOL -->
                <section class="bcf-card">

                    @if ($bolehEdit)

                        <label
                            class="bcf-consent
                                {{ $errors->has('setuju')
                                    ? 'is-invalid' : '' }}"
                            id="bcf-pernyataan"
                            for="setuju"
                        >
                            <input
                                id="setuju"
                                name="setuju"
                                type="checkbox"
                                value="1"
                                @checked(old('setuju') == '1')
                            >

                            <span>
                                Saya menyatakan bahwa data pengajuan
                                yang saya masukkan sesuai dan
                                digunakan untuk simulasi akademik.
                            </span>
                        </label>

                        @error('setuju')
                            <p
                                class="bcf-field-error"
                                data-error-for="setuju"
                            >
                                {{ $message }}
                            </p>
                        @enderror

                        <div
                            id="bcf-peringatan"
                            class="bcf-alert"
                            role="alert"
                            hidden
                        ></div>

                        <div class="bcf-buttons">
                            <button
                                id="bcf-draf"
                                class="bcf-btn bcf-btn-quiet"
                                type="submit"
                                name="aksi"
                                value="draf"
                            >
                                Simpan Draf
                            </button>

                            <button
                                id="bcf-kirim"
                                class="bcf-btn bcf-btn-primary"
                                type="submit"
                                name="aksi"
                                value="kirim"
                            >
                                Kirim Pendaftaran
                            </button>
                        </div>

                        <p class="bcf-note">
                            Simpan Draf boleh dilakukan meskipun data
                            belum lengkap. Pengiriman membutuhkan data,
                            tiga berkas wajib, serta persetujuan.
                        </p>

                    @else

                        <p class="bcf-note">
                            Formulir terkunci pada status saat ini.
                            Dokumen tersimpan masih bisa diunduh.
                            Jika Admin meminta perbaikan,
                            formulir dapat diubah kembali.
                        </p>

                    @endif

                </section>

            </div>
        </div>

        <!-- SHIMMER, HANYA TAMPIL SAAT SUBMIT -->
        @if ($bolehEdit)

            <div
                class="bcf-skeleton"
                id="bcf-proses"
                hidden
                role="status"
                aria-live="polite"
                aria-busy="true"
            >
                <div
                    id="bcf-proses-teks"
                    class="bcf-skeleton-label"
                >
                    Mengirim pengajuan...
                </div>

                <div
                    class="bcf-shimmer-group"
                    aria-hidden="true"
                >
                    <div class="bcf-shimmer"></div>
                    <div class="bcf-shimmer"></div>
                    <div class="bcf-shimmer"></div>
                </div>
            </div>

        @endif

    </form>

    <!-- MODAL KONFIRMASI -->
    @if ($bolehEdit)

        <div
            id="bcf-modal"
            class="bcf-modal"
            hidden
            role="dialog"
            aria-modal="true"
            aria-labelledby="bcf-modal-title"
            aria-describedby="bcf-modal-message"
        >

            <div class="bcf-dialog">

                <div class="bcf-dialog-accent"></div>

                <h2
                    id="bcf-modal-title"
                    class="bcf-dialog-title"
                >
                    Konfirmasi Pengajuan
                </h2>

                <p id="bcf-modal-message">
                    Kirim pengajuan sekarang?
                    Setelah dikirim, formulir akan terkunci
                    sampai Admin meminta perbaikan.
                </p>

                <div class="bcf-modal-actions">

                    <button
                        type="button"
                        class="bcf-btn bcf-btn-quiet"
                        id="bcf-batal"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        class="bcf-btn bcf-btn-primary"
                        id="bcf-ya"
                    >
                        Ya, Kirim Pengajuan
                    </button>

                </div>

            </div>
        </div>

    @endif

    <a
        href="{{ route('user.dashboard') }}"
        style="
            display:inline-block;
            margin-top:25px;
            color:#0b7457;
            font-size:13px;
            font-weight:700;
            text-decoration:underline;
        "
    >
        Kembali ke Dashboard User
    </a>

</main>
@endsection

@push('scripts')
<script>
(() => {
    'use strict';

    const form = document.getElementById('bcf-form');
    const kirim = document.getElementById('bcf-kirim');
    const draf = document.getElementById('bcf-draf');
    const modal = document.getElementById('bcf-modal');
    const setuju = document.getElementById('setuju');
    const rupiah = document.getElementById('penghasilan_tampilan');
    const angka = document.getElementById('penghasilan_orang_tua');

    if (!form || !kirim || !modal || !setuju) {
        return;
    }

    let konfirmasi = false;
    let memproses = false;
    let fokusSebelum = null;

    const ambil = nama => form.elements.namedItem(nama);

    const tampil = nama =>
        nama === 'penghasilan_orang_tua'
            ? rupiah
            : ambil(nama);

    const alertBox =
        document.getElementById('bcf-peringatan');

    // Hapus pesan ketika input diperbaiki.
    function bersihkan(nama) {
        const elemen = tampil(nama);

        if (elemen) {
            elemen.classList.remove('bcf-invalid');
            elemen.removeAttribute('aria-invalid');
        }

        if (nama === 'setuju') {
            document.getElementById('bcf-pernyataan')
                ?.classList.remove('is-invalid');
        }

        document.querySelectorAll('[data-error-for]')
            .forEach(p => {
                if (p.dataset.errorFor === nama) {
                    p.remove();
                }
            });

        if (alertBox) {
            alertBox.hidden = true;
        }
    }

    function salah(nama, pesan) {
        const elemen = tampil(nama);

        if (!elemen) {
            return false;
        }

        bersihkan(nama);

        elemen.classList.add('bcf-invalid');
        elemen.setAttribute('aria-invalid', 'true');

        const note = document.createElement('p');

        note.className = 'bcf-field-error';
        note.dataset.errorFor = nama;
        note.setAttribute('role', 'alert');
        note.textContent = pesan;

        if (nama === 'setuju') {
            document.getElementById('bcf-pernyataan')
                ?.classList.add('is-invalid');

            document.getElementById('bcf-pernyataan')
                ?.insertAdjacentElement('afterend', note);

        } else if (elemen.type === 'file') {

            elemen.closest('.bcf-upload')
                ?.insertAdjacentElement('afterend', note);

        } else {

            elemen.insertAdjacentElement('afterend', note);
        }

        elemen.focus();

        elemen.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        return false;
    }

    // Format rupiah.
    if (rupiah && angka) {
        rupiah.addEventListener('input', () => {
            const value = rupiah.value
                .replace(/\D/g, '')
                .replace(/^0+(?=\d)/, '');

            angka.value = value;

            rupiah.value = value
                ? 'Rp ' + new Intl.NumberFormat('id-ID')
                    .format(Number(value))
                : '';

            bersihkan('penghasilan_orang_tua');
        });
    }

    // Bersihkan pesan validasi saat data berubah.
    form.querySelectorAll('input, select, textarea')
        .forEach(el => {

            if (el.type === 'hidden'
                || el.type === 'submit') {
                return;
            }

            const nama =
                el.id === 'penghasilan_tampilan'
                    ? 'penghasilan_orang_tua'
                    : el.name;

            const eventName =
                el.type === 'checkbox'
                || el.type === 'file'
                || el.tagName === 'SELECT'
                    ? 'change'
                    : 'input';

            el.addEventListener(eventName, () => {
                bersihkan(nama);

                if (el.type === 'file') {
                    const label = document.getElementById(
                        'bcf-nama-' + nama
                    );

                    if (label && el.files[0]) {
                        label.textContent = el.files[0].name;
                    }
                }
            });
        });

    function cekBerkas() {
        for (const jenis of [
            'kk',
            'penghasilan',
            'rapor',
            'sertifikat'
        ]) {
            const el = ambil(jenis);

            if (!el || !el.files.length) {
                continue;
            }

            const file = el.files[0];

            if (file.size > 2 * 1024 * 1024) {
                return salah(
                    jenis,
                    'Ukuran berkas maksimal 2 MB.'
                );
            }

            if (!/\.(pdf|jpe?g|png)$/i.test(file.name)) {
                return salah(
                    jenis,
                    'Format berkas harus PDF, JPG, atau PNG.'
                );
            }
        }

        return true;
    }

    function cekKirim() {
        const wajib = {
            nama_lengkap: 'Nama lengkap',
            nisn: 'NISN',
            asal_sekolah: 'Asal sekolah',
            kelas: 'Kelas',
            nomor_hp: 'Nomor HP',
            alamat_lengkap: 'Alamat lengkap',
            pekerjaan_orang_tua: 'Pekerjaan orang tua',
            penghasilan_orang_tua: 'Penghasilan orang tua',
            jumlah_tanggungan: 'Jumlah tanggungan',
            nilai_akademik: 'Nilai akademik',
            kondisi_tempat_tinggal: 'Kondisi tempat tinggal',
            prestasi_non_akademik: 'Prestasi non-akademik'
        };

        for (const [nama, label] of Object.entries(wajib)) {
            if (!String(ambil(nama)?.value ?? '').trim()) {
                return salah(nama, label + ' wajib diisi.');
            }
        }

        if (!/^\d{10}$/.test(
            String(ambil('nisn').value).trim()
        )) {
            return salah(
                'nisn',
                'NISN harus berisi 10 angka.'
            );
        }

        if (!/^[0-9+(). \-]{8,20}$/.test(
            String(ambil('nomor_hp').value).trim()
        )) {
            return salah(
                'nomor_hp',
                'Format nomor HP tidak sesuai.'
            );
        }

        if (
            !/^\d+$/.test(String(angka.value))
            || Number(angka.value) < 1
            || Number(angka.value) > 1000000000000
        ) {
            return salah(
                'penghasilan_orang_tua',
                'Penghasilan tidak valid.'
            );
        }

        if (
            !/^\d+$/.test(
                String(ambil('jumlah_tanggungan').value)
            )
            || Number(
                ambil('jumlah_tanggungan').value
            ) > 50
        ) {
            return salah(
                'jumlah_tanggungan',
                'Jumlah tanggungan harus 0–50.'
            );
        }

        const rapor = String(
            ambil('nilai_akademik').value
        ).trim().replace(',', '.');

        if (
            !/^\d{1,3}(?:\.\d{1,2})?$/.test(rapor)
            || Number(rapor) > 100
        ) {
            return salah(
                'nilai_akademik',
                'Nilai rapor harus 0–100 dengan maksimal 2 desimal.'
            );
        }

        for (const nama of [
            'kondisi_tempat_tinggal',
            'prestasi_non_akademik'
        ]) {
            if (!/^[1-5]$/.test(
                String(ambil(nama).value)
            )) {
                return salah(
                    nama,
                    'Skor harus 1–5.'
                );
            }
        }

        const maksimum = {
            nama_lengkap: 100,
            asal_sekolah: 150,
            kelas: 20,
            nomor_hp: 20,
            alamat_lengkap: 3000,
            pekerjaan_orang_tua: 100
        };

        for (const [nama, max] of Object.entries(maksimum)) {
            if (String(ambil(nama).value).length > max) {
                return salah(
                    nama,
                    'Maksimal ' + max + ' karakter.'
                );
            }
        }

        if (!cekBerkas()) {
            return false;
        }

        for (const jenis of ['kk', 'penghasilan', 'rapor']) {
            const el = ambil(jenis);

            if (
                el
                && el.dataset.sudahAda !== '1'
                && !el.files.length
            ) {
                return salah(
                    jenis,
                    'Dokumen wajib belum diunggah.'
                );
            }
        }

        if (!setuju.checked) {
            return salah(
                'setuju',
                'Centang pernyataan kebenaran data sebelum mengirim.'
            );
        }

        return true;
    }

    const batal = document.getElementById('bcf-batal');
    const ya = document.getElementById('bcf-ya');

    function bukaModal() {
        fokusSebelum = document.activeElement;
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        batal.focus();
    }

    function tutupModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        fokusSebelum?.focus();
    }

    function shimmer(aksi) {
        const proses = document.getElementById('bcf-proses');

        if (proses) {
            document.getElementById(
                'bcf-proses-teks'
            ).textContent = aksi === 'draf'
                ? 'Menyimpan draf...'
                : 'Mengirim pengajuan...';

            proses.hidden = false;
        }

        form.setAttribute('aria-busy', 'true');
    }

    form.addEventListener('submit', event => {
        if (memproses) {
            event.preventDefault();
            return;
        }

        if (
            event.submitter === kirim
            && !konfirmasi
        ) {
            event.preventDefault();

            if (cekKirim()) {
                bukaModal();
            }

            return;
        }

        if (
            (event.submitter === draf
                || event.submitter === kirim)
            && !cekBerkas()
        ) {
            event.preventDefault();
            return;
        }

        memproses = true;

        shimmer(
            event.submitter === draf
                ? 'draf'
                : 'kirim'
        );
    });

    batal.addEventListener('click', tutupModal);

    modal.addEventListener('click', event => {
        if (event.target === modal) {
            tutupModal();
        }
    });

    document.addEventListener('keydown', event => {
        if (modal.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            tutupModal();
            return;
        }

        if (event.key === 'Tab') {
            if (
                event.shiftKey
                && document.activeElement === batal
            ) {
                event.preventDefault();
                ya.focus();

            } else if (
                !event.shiftKey
                && document.activeElement === ya
            ) {
                event.preventDefault();
                batal.focus();
            }
        }
    });

    ya.addEventListener('click', () => {
        if (memproses) {
            return;
        }

        konfirmasi = true;
        tutupModal();
        form.requestSubmit(kirim);
    });

    window.addEventListener('pageshow', event => {
        if (!event.persisted) {
            return;
        }

        memproses = false;
        konfirmasi = false;

        const proses = document.getElementById('bcf-proses');

        if (proses) {
            proses.hidden = true;
        }

        modal.hidden = true;
        form.removeAttribute('aria-busy');
        document.body.style.overflow = '';
    });
})();
</script>
@endpush
