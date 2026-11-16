@php
    $modePenetapan = $lingkupInfo['mode'] ?? \App\Services\LingkupSeleksi::ARTIKEL;
    $khususPendaftar = $modePenetapan === \App\Services\LingkupSeleksi::PENDAFTAR;

    $layananPenetapan = null;
    $arsipTersedia = collect();
    $arsipTerpilih = 0;
    $rincian = null;
    $penetapan = null;
    $kesalahanArsip = null;
    $jumlahPeserta = 0;
    $tabelPenetapanSiap = false;

    if ($khususPendaftar) {
        $tabelPenetapanSiap = \Illuminate\Support\Facades\Schema::hasTable('penetapan_seleksi')
            && \Illuminate\Support\Facades\Schema::hasTable('penetapan_peserta');

        if ($tabelPenetapanSiap) {
            $layananPenetapan = app(\App\Services\PenetapanBeasiswa::class);
            $arsipTersedia = $layananPenetapan->daftarArsip();

            $arsipDariUrl = request()->query('arsip');
            $arsipTerpilih = filter_var($arsipDariUrl, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
                ?: (int) ($arsipTersedia->first()?->id ?? 0);

            if ($arsipTerpilih > 0) {
                try {
                    $rincian = $layananPenetapan->pratinjau($arsipTerpilih);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    $kesalahanArsip = collect($e->errors())->flatten()->implode(' ');
                }
            }

            $penetapan = $rincian['penetapan'] ?? null;
            $jumlahPeserta = count($rincian['baris'] ?? []);
        }
    }

    $kuotaAwal = old('kuota', $penetapan?->kuota ?? ($jumlahPeserta > 0 ? 1 : 0));
    $cadanganAwal = old('jumlah_cadangan', $penetapan?->jumlah_cadangan ?? 0);
@endphp

<!-- PANEL PENETAPAN PENERIMA BEASISWA (PREMIUM SAAS) -->
<section class="rounded-2xl border border-[#E3EBE6] bg-white shadow-sm overflow-hidden" id="penetapan-penerima" aria-labelledby="judul-penetapan">

    <div class="border-b border-[#EEF3F0] px-6 py-4.5 bg-[#FAFCFB] flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-[#103F36] flex items-center gap-2" id="judul-penetapan">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-extrabold text-[#103F36]">✓</span>
                <span>Penetapan Kuota & Pengumuman Penerima Beasiswa</span>
            </h2>
            <p class="text-xs text-[#64746D] mt-0.5">
                Tentukan kuota kelulusan pendaftar berdasarkan hasil skor WASPAS terverifikasi dan publikasikan ke akun pendaftar.
            </p>
        </div>
    </div>

    <div class="p-6 space-y-6">

        <!-- KONDISI 1: JIKA LINGKUP BUKAN PENDAFTAR (PENELITIAN A1-A5 ATAU GABUNGAN) -->
        @if (!$khususPendaftar)
            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-6 space-y-4" role="status">
                <div class="flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-200/80 text-amber-900 font-extrabold text-sm">
                        🔒
                    </span>
                    <div class="space-y-1">
                        <h3 class="text-sm font-extrabold text-amber-950">
                            Penetapan Penerima Belum Dapat Dilakukan
                        </h3>
                        <p class="text-xs text-amber-900/90 leading-relaxed">
                            @if ($modePenetapan === \App\Services\LingkupSeleksi::ARTIKEL)
                                Lingkup seleksi yang sekarang aktif adalah <strong>Penelitian A1–A5</strong>. Data tersebut digunakan untuk pengujian matematis metode AHP dan WASPAS, bukan untuk menetapkan siswa penerima beasiswa yang sebenarnya.
                            @else
                                Lingkup seleksi yang sekarang aktif adalah <strong>Gabungan untuk Demonstrasi</strong>. Data gabungan tidak boleh digunakan untuk penetapan kelulusan beasiswa.
                            @endif
                        </p>
                        <p class="text-xs text-amber-900/80 leading-relaxed pt-1">
                            Untuk menetapkan penerima beasiswa, ubah lingkup seleksi menjadi <strong>Pendaftar Terverifikasi</strong>.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2 pl-11">
                    <a href="{{ route('admin.hasil.index', ['lingkup' => 'pendaftar']) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-[#103F36] px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-[#103F36]/20 hover:bg-[#116759] transition">
                        <span>Ubah ke Lingkup Pendaftar</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('admin.seleksi-pengajuan.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-white/90 px-4 py-2.5 text-xs font-bold text-amber-950 hover:bg-white transition">
                        <span>Periksa Pengajuan Siap Seleksi</span>
                    </a>
                </div>
            </div>

        <!-- KONDISI 2: TABEL PENETAPAN BELUM TERSEDIA DI DATABASE -->
        @elseif (!$tabelPenetapanSiap)
            <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-6 space-y-2 text-xs text-rose-900" role="alert">
                <h3 class="text-sm font-bold text-rose-950">Database Penetapan Belum Tersedia</h3>
                <p>Tabel <code>penetapan_seleksi</code> atau <code>penetapan_peserta</code> belum ditemukan. Silakan jalankan migrasi database terlebih dahulu.</p>
            </div>

        <!-- KONDISI 3: MODE PENDAFTAR & DATABASE SIAP -->
        @else

            <!-- Alert Validasi -->
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4.5 text-xs text-red-900" role="alert">
                    <p class="font-bold text-sm mb-1">Penetapan belum dapat diproses:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Sumber Arsip Penjelasan -->
            <div class="rounded-xl border border-[#E3EBE6] bg-[#F7FAF8] p-4 text-xs text-[#64746D] flex items-center justify-between gap-4">
                <div>
                    <span class="font-bold text-[#103F36] block">Sumber Snapshot Perhitungan:</span>
                    <span>Penetapan menggunakan snapshot hasil WASPAS yang tersimpan dalam arsip Riwayat Seleksi.</span>
                </div>
                <a href="{{ route('admin.riwayat.index') }}" class="shrink-0 text-xs font-bold text-[#116759] hover:underline">
                    Lihat Riwayat Arsip →
                </a>
            </div>

            <!-- JIKA BELUM ADA ARSIP -->
            @if ($arsipTersedia->isEmpty())
                <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-6 space-y-3" role="status">
                    <p class="text-sm font-bold text-amber-950">Belum Ada Arsip Pendaftar Terverifikasi</p>
                    <p class="text-xs text-amber-900/90 leading-relaxed">
                        Sebelum menetapkan penerima, pastikan calon penerima telah masuk ke alternatif WASPAS, lalu simpan hasil perhitungan ke dalam Riwayat Perhitungan.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('admin.seleksi-pengajuan.index') }}" class="rounded-xl border border-amber-300 bg-white px-3.5 py-2 text-xs font-bold text-amber-950">
                            Pengajuan Siap Seleksi
                        </a>
                        <a href="{{ route('admin.waspas.index') }}" class="rounded-xl bg-[#103F36] px-4 py-2 text-xs font-bold text-white">
                            Hitung & Simpan WASPAS →
                        </a>
                    </div>
                </div>
            @else
                <!-- SELECT ARSIP WASPAS -->
                <form method="GET" action="{{ route('admin.hasil.index') }}" class="flex flex-wrap items-end gap-3 bg-[#FAFCFB] p-4 rounded-xl border border-[#E3EBE6]">
                    <div class="min-w-[240px] flex-1 text-xs">
                        <label for="pilih-arsip" class="font-bold text-[#103F36] block mb-1">
                            Pilih Arsip Snapshot WASPAS:
                        </label>
                        <select id="pilih-arsip" name="arsip" class="w-full rounded-xl border border-[#CFDED8] bg-white px-3.5 py-2 text-xs font-semibold text-[#172B25] focus:border-[#20A67A] focus:outline-none">
                            @foreach ($arsipTersedia as $arsip)
                                <option value="{{ $arsip->id }}" @selected($arsipTerpilih === (int) $arsip->id)>
                                    Arsip #{{ $arsip->id }} — {{ $arsip->jumlah_alternatif }} calon pendaftar — {{ date('d M Y H:i', strtotime($arsip->created_at)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="rounded-xl border border-[#116759] bg-white px-4 py-2 text-xs font-bold text-[#116759] hover:bg-[#116759] hover:text-white transition">
                        Tampilkan Arsip
                    </button>
                </form>
            @endif

            <!-- JIKA ARSIP DIBACA DENGAN BAIK -->
            @if ($rincian)
                <!-- Arsip Status Pill -->
                <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50/60 p-4">
                    <div class="space-y-0.5">
                        <span class="text-xs font-bold text-[#103F36]">Arsip Terpilih: #{{ $arsipTerpilih }}</span>
                        <p class="text-[11px] text-[#64746D]">{{ $jumlahPeserta }} peserta terdaftar dalam snapshot seleksi ini.</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $penetapan?->status === 'diumumkan' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                        {{ $penetapan?->status === 'diumumkan' ? '✓ Hasil Resmi Diumumkan' : ($penetapan ? 'Draf Tersimpan (Belum Diumumkan)' : 'Belum Ada Draf') }}
                    </span>
                </div>

                <!-- FORM KUOTA PENERIMA JIKA BELUM DIUMUMKAN -->
                @if ($penetapan?->status !== 'diumumkan')
                    <div class="rounded-xl border border-[#E3EBE6] bg-white p-5 space-y-4">
                        <div>
                            <h3 class="text-sm font-extrabold text-[#103F36]">Konfigurasi Kuota Beasiswa</h3>
                            <p class="text-xs text-[#64746D] mt-0.5">
                                Tentukan jumlah kuota penerima utama dan cadangan. Peserta akan dialokasikan otomatis sesuai urutan ranking WASPAS.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('admin.hasil.penetapan.draf') }}" class="flex flex-wrap items-end gap-4 pt-1">
                            @csrf
                            <input type="hidden" name="arsip_id" value="{{ $arsipTerpilih }}">

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#103F36] block">Kuota Penerima Utama (Diterima):</label>
                                <input type="number" name="kuota" required min="1" max="{{ $jumlahPeserta }}" value="{{ $kuotaAwal }}"
                                       class="w-36 rounded-xl border border-[#CFDED8] bg-white px-3.5 py-2 text-xs font-bold text-[#172B25] focus:border-[#20A67A] focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#103F36] block">Jumlah Cadangan:</label>
                                <input type="number" name="jumlah_cadangan" required min="0" max="{{ $jumlahPeserta }}" value="{{ $cadanganAwal }}"
                                       class="w-36 rounded-xl border border-[#CFDED8] bg-white px-3.5 py-2 text-xs font-bold text-[#172B25] focus:border-[#20A67A] focus:outline-none">
                            </div>

                            <button type="submit" class="rounded-xl bg-[#103F36] px-5 py-2 text-xs font-bold text-white shadow-md shadow-[#103F36]/20 hover:bg-[#116759] transition"
                                    data-bc-confirm="Simpan draf penetapan berdasarkan kuota ini?">
                                Simpan Draf Kuota
                            </button>
                        </form>
                    </div>
                @else
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 text-xs font-semibold text-emerald-950">
                        🔒 Keputusan hasil seleksi untuk arsip ini telah resmi diumumkan dan dikunci. Kuota penerima tidak dapat diubah kembali.
                    </div>
                @endif

                <!-- SUMMARY KUOTA 3 KARTU -->
                @if ($penetapan)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 text-center">
                            <span class="text-[10px] font-extrabold uppercase text-emerald-800 tracking-wider">Kuota Diterima</span>
                            <span class="text-2xl font-extrabold text-emerald-900 mt-1 block tabular-nums">{{ $penetapan->kuota }}</span>
                            <span class="text-[10px] text-emerald-700">Peringkat 1 s.d. {{ $penetapan->kuota }}</span>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 text-center">
                            <span class="text-[10px] font-extrabold uppercase text-amber-800 tracking-wider">Kandidat Cadangan</span>
                            <span class="text-2xl font-extrabold text-amber-900 mt-1 block tabular-nums">{{ $penetapan->jumlah_cadangan }}</span>
                            <span class="text-[10px] text-amber-700">Peringkat {{ $penetapan->kuota + 1 }} s.d. {{ $penetapan->kuota + $penetapan->jumlah_cadangan }}</span>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <span class="text-[10px] font-extrabold uppercase text-slate-600 tracking-wider">Tidak Diterima</span>
                            <span class="text-2xl font-extrabold text-slate-800 mt-1 block tabular-nums">
                                {{ max(0, $jumlahPeserta - $penetapan->kuota - $penetapan->jumlah_cadangan) }}
                            </span>
                            <span class="text-[10px] text-slate-500">Pendaftar lainnya</span>
                        </div>
                    </div>

                    <!-- RINCIAN SETIAP PESERTA (ACCORDION) -->
                    <details class="rounded-xl border border-[#E3EBE6] bg-[#FAFCFB] p-4 transition group">
                        <summary class="cursor-pointer list-none font-bold text-[#103F36] text-xs flex items-center justify-between">
                            <span>Pratinjau Status Keputusan Semua Peserta ({{ $jumlahPeserta }})</span>
                            <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                        </summary>

                        <div class="mt-4 divide-y divide-[#EEF3F0] rounded-lg border border-[#E3EBE6] bg-white overflow-hidden text-xs">
                            @foreach ($rincian['baris'] as $item)
                                @php
                                    $keputusan = $layananPenetapan->keputusan($item['peringkat'], (int)$penetapan->kuota, (int)$penetapan->jumlah_cadangan);
                                    $badgeClass = match ($keputusan) {
                                        'diterima' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'cadangan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    $label = match ($keputusan) {
                                        'diterima' => '✓ DITERIMA',
                                        'cadangan' => 'CADANGAN',
                                        default => 'TIDAK DITERIMA',
                                    };
                                @endphp
                                <div class="p-3 flex items-center justify-between gap-3 hover:bg-[#F9FCFA]">
                                    <div class="flex items-center gap-3">
                                        <span class="font-mono text-slate-400 font-bold w-6 text-center">#{{ $item['peringkat'] }}</span>
                                        <div>
                                            <p class="font-bold text-[#172B25]">{{ $item['nama'] }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">{{ $item['kode'] }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-extrabold border {{ $badgeClass }}">
                                        {{ $label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </details>

                    <!-- TOMBOL UMUMKAN JIKA MASIH DRAF -->
                    @if ($penetapan?->status === 'draf')
                        <form method="POST" action="{{ route('admin.hasil.penetapan.umumkan') }}"
                              class="rounded-xl border border-amber-200 bg-amber-50/80 p-5 space-y-3">
                            @csrf
                            <input type="hidden" name="arsip_id" value="{{ $arsipTerpilih }}">
                            <h4 class="text-sm font-extrabold text-amber-950">Publikasikan Hasil Pengumuman Resmi</h4>
                            <p class="text-xs text-amber-900/90 leading-relaxed">
                                Setelah diumumkan, keputusan resmi akan langsung terlihat pada Dashboard dan halaman Status Pengajuan milik akun pendaftar. Keputusan akan <strong>dikunci permanen</strong>.
                            </p>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#20A67A] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#20A67A]/25 hover:bg-[#1B8E68] transition"
                                    data-bc-confirm="Yakin ingin mempublikasikan pengumuman hasil beasiswa kepada seluruh siswa? Keputusan akan dikunci permanen.">
                                <span>Umumkan Hasil Sekarang</span>
                                <span>📢</span>
                            </button>
                        </form>
                    @endif
                @endif
            @endif
        @endif

    </div>
</section>
