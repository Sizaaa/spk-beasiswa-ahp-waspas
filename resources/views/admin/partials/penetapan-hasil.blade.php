
@php
    /*
    |--------------------------------------------------------------------------
    | INFORMASI LINGKUP PENETAPAN
    |--------------------------------------------------------------------------
    */

    $modePenetapan = $lingkupInfo['mode']
        ?? \App\Services\LingkupSeleksi::ARTIKEL;

    $khususPendaftar = $modePenetapan ===
        \App\Services\LingkupSeleksi::PENDAFTAR;

    /*
    |--------------------------------------------------------------------------
    | NILAI AWAL
    |--------------------------------------------------------------------------
    */

    $layananPenetapan = null;

    $arsipTersedia = collect();

    $arsipTerpilih = 0;

    $rincian = null;

    $penetapan = null;

    $kesalahanArsip = null;

    $jumlahPeserta = 0;

    $tabelPenetapanSiap = false;

    /*
    |--------------------------------------------------------------------------
    | HANYA BACA DATA PENETAPAN UNTUK PENDAFTAR
    |--------------------------------------------------------------------------
    |
    | Tidak membaca penetapan dari data penelitian A1–A5
    | atau data gabungan demonstrasi.
    |
    */

    if ($khususPendaftar) {

        $tabelPenetapanSiap =
            \Illuminate\Support\Facades\Schema::hasTable(
                'penetapan_seleksi'
            )
            &&
            \Illuminate\Support\Facades\Schema::hasTable(
                'penetapan_peserta'
            );

        if ($tabelPenetapanSiap) {

            $layananPenetapan = app(
                \App\Services\PenetapanBeasiswa::class
            );

            $arsipTersedia = $layananPenetapan
                ->daftarArsip();

            $arsipDariUrl = request()->query('arsip');

            $arsipTerpilih = filter_var(
                $arsipDariUrl,
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                    ],
                ]
            ) ?: (int) (
                $arsipTersedia->first()?->id ?? 0
            );

            if ($arsipTerpilih > 0) {

                try {

                    $rincian = $layananPenetapan
                        ->pratinjau($arsipTerpilih);

                } catch (
                    \Illuminate\Validation\ValidationException $e
                ) {

                    $kesalahanArsip = collect(
                        $e->errors()
                    )->flatten()->implode(' ');

                }

            }

            $penetapan = $rincian['penetapan']
                ?? null;

            $jumlahPeserta = count(
                $rincian['baris'] ?? []
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | NILAI AWAL KUOTA
    |--------------------------------------------------------------------------
    */

    $kuotaAwal = old(
        'kuota',
        $penetapan?->kuota
            ?? ($jumlahPeserta > 0 ? 1 : 0)
    );

    $cadanganAwal = old(
        'jumlah_cadangan',
        $penetapan?->jumlah_cadangan ?? 0
    );
@endphp

<!-- =====================================================
     PANEL PENETAPAN PENERIMA
     SELALU TAMPIL PADA HALAMAN HASIL SELEKSI
===================================================== -->

<section
    class="bc-panel"
    id="penetapan-penerima"
    aria-labelledby="judul-penetapan"
>

    <!-- JUDUL -->

    <div class="bc-panel-header">

        <div class="min-w-0">

            <h2
                class="bc-panel-title"
                id="judul-penetapan"
            >
                Penetapan Penerima Beasiswa
            </h2>

            <p class="bc-panel-desc">
                Tentukan siswa penerima beasiswa
                berdasarkan hasil WASPAS yang sudah
                tersimpan, kemudian umumkan keputusan
                kepada masing-masing pendaftar.
            </p>

        </div>

    </div>

    <div class="bc-panel-body space-y-5">

        <!-- =========================================
             MODE PENELITIAN ATAU GABUNGAN
        ========================================== -->

        @if (!$khususPendaftar)

            <div
                class="rounded-xl border border-amber-200
                       bg-amber-50 p-5"
                role="status"
            >

                <p class="text-base font-extrabold text-amber-900">
                    Penetapan Penerima Belum Dapat Dilakukan
                </p>

                <p class="mt-3 text-sm leading-7 text-amber-900">

                    @if (
                        $modePenetapan ===
                        \App\Services\LingkupSeleksi::ARTIKEL
                    )

                        Lingkup seleksi yang sekarang aktif
                        adalah

                        <strong>
                            Penelitian A1–A5
                        </strong>.

                        Data tersebut digunakan untuk
                        pengujian metode AHP dan WASPAS,
                        bukan untuk menetapkan penerima
                        beasiswa yang sebenarnya.

                    @else

                        Lingkup seleksi yang sekarang aktif
                        adalah

                        <strong>
                            Gabungan untuk Demonstrasi
                        </strong>.

                        Data gabungan tidak boleh digunakan
                        untuk keputusan akhir penerima beasiswa.

                    @endif

                </p>

                <p class="mt-3 text-sm leading-7 text-amber-900">
                    Untuk menetapkan penerima,
                    ubah lingkup menjadi

                    <strong>
                        Pendaftar Terverifikasi
                    </strong>.

                    Setelah itu, pastikan pendaftar
                    sudah masuk ke alternatif WASPAS
                    dan hasil perhitungannya sudah
                    disimpan sebagai arsip.
                </p>

                <div class="mt-4 flex flex-wrap gap-3">

                    <a
                        href="{{ route('admin.lingkup.index') }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Ubah ke Lingkup Pendaftar
                    </a>

                    <a
                        href="{{ route(
                            'admin.seleksi-pengajuan.index'
                        ) }}"
                        class="bc-btn bc-btn--outline"
                    >
                        Periksa Pengajuan Siap Seleksi
                    </a>

                </div>

            </div>

        <!-- =========================================
             TABEL PENETAPAN BELUM TERSEDIA
        ========================================== -->

        @elseif (!$tabelPenetapanSiap)

            <div
                class="rounded-xl border border-amber-200
                       bg-amber-50 p-5"
                role="alert"
            >

                <p class="font-extrabold text-amber-900">
                    Database Penetapan Belum Siap
                </p>

                <p class="mt-2 text-sm leading-7 text-amber-900">
                    Fitur penetapan sudah terhubung
                    ke halaman Hasil Seleksi, tetapi
                    tabel penyimpanan penetapan
                    belum tersedia pada database.

                    Periksa migrasi

                    <strong>
                        create_penetapan_beasiswa
                    </strong>

                    terlebih dahulu.

                    Jangan menjalankan pengumuman
                    sebelum migrasi berhasil.
                </p>

            </div>

        <!-- =========================================
             MODE PENDAFTAR DAN TABEL SIAP
        ========================================== -->

        @else

            <!-- PESAN VALIDASI -->

            @if ($errors->any())

                <div
                    role="alert"
                    class="rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm text-red-800"
                >
                    <p class="mb-2 font-bold">
                        Penetapan belum dapat diproses:
                    </p>

                    @foreach ($errors->all() as $error)
                        <p class="mt-1">
                            {{ $error }}
                        </p>
                    @endforeach

                </div>

            @endif

            <!-- PENJELASAN SUMBER HASIL -->

            <div
                class="rounded-xl border border-slate-200
                       bg-slate-50 p-4"
            >

                <p class="font-bold text-[#103F36]">
                    Sumber Hasil Seleksi
                </p>

                <p class="mt-2 text-sm leading-7 text-slate-600">
                    Penetapan menggunakan hasil WASPAS
                    dari arsip Riwayat Perhitungan.

                    Data asli, bobot, dan nilai peringkat
                    tersimpan dalam arsip, sehingga
                    keputusan akhir tidak bergantung pada
                    perhitungan WASPAS terbaru.
                </p>

                <a
                    href="{{ route('admin.riwayat.index') }}"
                    class="bc-btn bc-btn--outline mt-3"
                >
                    Buka Riwayat Perhitungan
                </a>

            </div>

            <!-- =====================================
                 BELUM ADA ARSIP PENDAFTAR
            ====================================== -->

            @if ($arsipTersedia->isEmpty())

                <div
                    class="rounded-xl border border-amber-200
                           bg-amber-50 p-5"
                    role="status"
                >

                    <p class="font-extrabold text-amber-900">
                        Belum Ada Arsip Pendaftar Terverifikasi
                    </p>

                    <p class="mt-2 text-sm leading-7 text-amber-900">

                        Sebelum menetapkan penerima,
                        pastikan pengajuan terverifikasi
                        sudah masuk ke alternatif WASPAS.

                        Kemudian simpan hasil perhitungan
                        melalui fitur Riwayat Perhitungan.

                    </p>

                    <div class="mt-4 flex flex-wrap gap-3">

                        <a
                            href="{{ route(
                                'admin.seleksi-pengajuan.index'
                            ) }}"
                            class="bc-btn bc-btn--outline"
                        >
                            Pengajuan Siap Seleksi
                        </a>

                        <a
                            href="{{ route(
                                'admin.waspas.index'
                            ) }}"
                            class="bc-btn bc-btn--outline"
                        >
                            Lihat Perhitungan WASPAS
                        </a>

                        <a
                            href="{{ route(
                                'admin.riwayat.index'
                            ) }}"
                            class="bc-btn bc-btn--outline"
                        >
                            Buka Riwayat Perhitungan
                        </a>

                    </div>

                </div>

            @else

                <!-- =================================
                     PILIH ARSIP WASPAS
                ================================== -->

                <form
                    method="GET"
                    action="{{ route('admin.hasil.index') }}"
                    class="flex flex-wrap items-end gap-3"
                >

                    <label
                        for="pilih-arsip"
                        class="min-w-[220px] flex-1
                               text-sm font-bold text-[#103F36]"
                    >

                        Pilih Hasil Perhitungan

                        <select
                            id="pilih-arsip"
                            name="arsip"
                            class="mt-2 w-full rounded-xl
                                   border border-slate-300
                                   bg-white px-3 py-3 text-sm"
                        >

                            @foreach ($arsipTersedia as $arsip)

                                <option
                                    value="{{ $arsip->id }}"
                                    @selected(
                                        $arsipTerpilih === (int) $arsip->id
                                    )
                                >

                                    Arsip #{{ $arsip->id }}
                                    —
                                    {{ $arsip->jumlah_alternatif }}
                                    peserta
                                    —
                                    {{ $arsip->created_at }}

                                </option>

                            @endforeach

                        </select>

                    </label>

                    <button
                        type="submit"
                        class="bc-btn bc-btn--outline"
                    >
                        Tampilkan
                    </button>

                </form>

            @endif

            <!-- =====================================
                 ARSIP TIDAK VALID
            ====================================== -->

            @if ($kesalahanArsip)

                <p
                    role="alert"
                    class="rounded-xl border border-red-200
                           bg-red-50 p-4 text-sm text-red-800"
                >
                    {{ $kesalahanArsip }}
                </p>

            @endif

            <!-- =====================================
                 ARSIP VALID DAN BERHASIL DIBACA
            ====================================== -->

            @if ($rincian)

                <!-- IDENTITAS ARSIP -->

                <div
                    class="rounded-xl border border-emerald-100
                           bg-emerald-50 p-4"
                >

                    <p class="font-bold text-[#103F36]">
                        Arsip #{{ $arsipTerpilih }}
                    </p>

                    <p class="mt-1 text-sm text-slate-600">
                        {{ $jumlahPeserta }}
                        peserta tercatat dalam
                        hasil WASPAS yang dipilih.
                    </p>

                    <p
                        class="mt-2 text-sm font-bold
                               {{ $penetapan?->status === 'diumumkan'
                                   ? 'text-emerald-800'
                                   : 'text-amber-800' }}"
                    >

                        @if (
                            $penetapan?->status === 'diumumkan'
                        )

                            Hasil Sudah Diumumkan

                        @elseif ($penetapan)

                            Draf Tersimpan
                            — Belum Diumumkan

                        @else

                            Belum Ada Penetapan

                        @endif

                    </p>

                </div>

                <!-- =================================
                     FORM SIMPAN DRAF
                ================================== -->

                @if (
                    $penetapan?->status !== 'diumumkan'
                )

                    <div
                        class="rounded-xl border border-slate-200
                               bg-white p-5"
                    >

                        <h3
                            class="text-base font-extrabold
                                   text-[#103F36]"
                        >
                            Atur Kuota Penerima Beasiswa
                        </h3>

                        <p
                            class="mt-2 text-sm leading-7
                                   text-slate-600"
                        >
                            Sistem akan menentukan
                            peserta yang diterima dan
                            cadangan berdasarkan urutan
                            peringkat pada arsip WASPAS.
                        </p>

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.hasil.penetapan.draf'
                            ) }}"
                            class="mt-5 flex flex-wrap
                                   items-end gap-3"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="arsip_id"
                                value="{{ $arsipTerpilih }}"
                            >

                            <label
                                class="text-sm font-bold
                                       text-[#103F36]"
                            >

                                Kuota Penerima

                                <input
                                    type="number"
                                    name="kuota"
                                    required
                                    min="1"
                                    max="{{ $jumlahPeserta }}"
                                    value="{{ $kuotaAwal }}"
                                    class="mt-2 block w-36
                                           rounded-xl border
                                           border-slate-300
                                           bg-white px-3 py-3"
                                >

                            </label>

                            <label
                                class="text-sm font-bold
                                       text-[#103F36]"
                            >

                                Jumlah Cadangan

                                <input
                                    type="number"
                                    name="jumlah_cadangan"
                                    required
                                    min="0"
                                    max="{{ $jumlahPeserta }}"
                                    value="{{ $cadanganAwal }}"
                                    class="mt-2 block w-36
                                           rounded-xl border
                                           border-slate-300
                                           bg-white px-3 py-3"
                                >

                            </label>

                            <button
                                type="submit"
                                class="bc-btn bc-btn--primary"
                                data-bc-confirm="Simpan draf penetapan berdasarkan arsip WASPAS dan kuota yang dipilih?"
                            >
                                Simpan Draf Penetapan
                            </button>

                        </form>

                        <p
                            class="mt-3 text-xs
                                   leading-6 text-slate-500"
                        >
                            Jumlah penerima ditambah
                            jumlah cadangan tidak boleh
                            melebihi jumlah peserta.

                            Menyimpan draf belum
                            mengumumkan hasil kepada User.
                        </p>

                    </div>

                @else

                    <div
                        class="rounded-xl border border-emerald-200
                               bg-emerald-50 p-4 text-sm
                               leading-7 text-emerald-900"
                    >
                        Keputusan sudah diumumkan
                        dan dikunci.

                        Kuota maupun daftar penerima
                        tidak dapat diubah melalui
                        penetapan ini.
                    </div>

                @endif

                <!-- =================================
                     RINGKASAN HASIL PENETAPAN
                ================================== -->

                @if ($penetapan)

                    <div class="grid gap-3 sm:grid-cols-3">

                        <div
                            class="rounded-xl border
                                   border-slate-200
                                   bg-white p-4"
                        >

                            <p class="text-xs text-slate-500">
                                Diterima
                            </p>

                            <p
                                class="mt-2 text-2xl
                                       font-extrabold
                                       text-[#103F36]"
                            >
                                {{ $penetapan->kuota }}
                            </p>

                        </div>

                        <div
                            class="rounded-xl border
                                   border-slate-200
                                   bg-white p-4"
                        >

                            <p class="text-xs text-slate-500">
                                Cadangan
                            </p>

                            <p
                                class="mt-2 text-2xl
                                       font-extrabold
                                       text-[#103F36]"
                            >
                                {{ $penetapan->jumlah_cadangan }}
                            </p>

                        </div>

                        <div
                            class="rounded-xl border
                                   border-slate-200
                                   bg-white p-4"
                        >

                            <p class="text-xs text-slate-500">
                                Tidak Diterima
                            </p>

                            <p
                                class="mt-2 text-2xl
                                       font-extrabold
                                       text-[#103F36]"
                            >
                                {{
                                    max(
                                        0,
                                        $jumlahPeserta
                                            - $penetapan->kuota
                                            - $penetapan->jumlah_cadangan
                                    )
                                }}
                            </p>

                        </div>

                    </div>

                    <!-- RINCIAN KEPUTUSAN -->

                    <details
                        class="rounded-xl border border-slate-200
                               bg-white p-4"
                    >

                        <summary
                            class="cursor-pointer text-sm
                                   font-bold text-[#103F36]"
                        >
                            Lihat Rincian Keputusan
                            Setiap Peserta
                        </summary>

                        <div class="mt-4 space-y-2">

                            @foreach (
                                $rincian['baris'] as $item
                            )

                                @php
                                    $keputusan = $layananPenetapan
                                        ->keputusan(
                                            $item['peringkat'],
                                            (int) $penetapan->kuota,
                                            (int) $penetapan->jumlah_cadangan
                                        );

                                    $labelKeputusan = match (
                                        $keputusan
                                    ) {
                                        'diterima' => 'Diterima',
                                        'cadangan' => 'Cadangan',
                                        default => 'Tidak Diterima',
                                    };

                                    $warnaKeputusan = match (
                                        $keputusan
                                    ) {
                                        'diterima' =>
                                            'bg-emerald-100 text-emerald-800',

                                        'cadangan' =>
                                            'bg-amber-100 text-amber-800',

                                        default =>
                                            'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <div
                                    class="flex flex-wrap items-center
                                           justify-between gap-3
                                           border-b border-slate-100
                                           py-3 text-sm"
                                >

                                    <div class="min-w-0">

                                        <p
                                            class="font-bold
                                                   text-[#103F36]"
                                        >
                                            {{ $item['nama'] }}
                                        </p>

                                        <p
                                            class="mt-1 text-xs
                                                   text-slate-500"
                                        >
                                            {{ $item['kode'] }}
                                            · Peringkat
                                            {{ $item['peringkat'] }}
                                        </p>

                                    </div>

                                    <span
                                        class="rounded-full px-3 py-1
                                               text-xs font-bold
                                               {{ $warnaKeputusan }}"
                                    >
                                        {{ $labelKeputusan }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </details>

                @endif

                <!-- =================================
                     TOMBOL UMUMKAN HASIL
                ================================== -->

                @if (
                    $penetapan?->status === 'draf'
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.hasil.penetapan.umumkan'
                        ) }}"
                        class="rounded-xl border
                               border-amber-200
                               bg-amber-50 p-5"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="arsip_id"
                            value="{{ $arsipTerpilih }}"
                        >

                        <h3
                            class="text-base font-extrabold
                                   text-amber-900"
                        >
                            Konfirmasi Pengumuman Hasil
                        </h3>

                        <p
                            class="mt-3 text-sm
                                   leading-7 text-amber-900"
                        >
                            Periksa kembali kuota
                            penerima, jumlah cadangan,
                            dan keputusan setiap peserta.

                            Setelah diumumkan, hasil
                            dapat dilihat masing-masing
                            User melalui halaman
                            Status Pengajuan.

                            Keputusan yang sudah
                            diumumkan akan dikunci.
                        </p>

                        <button
                            type="submit"
                            class="bc-btn bc-btn--primary mt-4"
                            data-bc-confirm="Yakin mengumumkan hasil seleksi kepada seluruh peserta? Keputusan akan dikunci setelah diumumkan."
                        >
                            Umumkan Hasil Seleksi
                        </button>

                    </form>

                @endif

            @endif

        @endif

    </div>

</section>
