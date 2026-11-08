
@if ($pengajuan && $status === 'terverifikasi')

<section
    class="mt-6 rounded-2xl border border-emerald-100
           bg-white p-6 shadow-sm"
    aria-labelledby="judul-hasil-akhir"
>
    <p
        class="text-xs font-bold uppercase
               tracking-wide text-emerald-700"
    >
        Tahap Akhir Seleksi
    </p>

    <h2
        id="judul-hasil-akhir"
        class="mt-2 text-xl font-extrabold text-[#103F36]"
    >
        Hasil Akhir Beasiswa
    </h2>

    @if ($hasilAkhir)

        {{-- Hasil sudah diumumkan Admin --}}
        <div
            class="mt-4 rounded-xl border p-4
                   {{ $hasilAkhir->keputusan === 'diterima'
                      ? 'border-emerald-200 bg-emerald-50'
                      : 'border-slate-200 bg-slate-50' }}"
        >
            <p class="text-lg font-extrabold text-[#103F36]">

                {{ match ($hasilAkhir->keputusan) {
                    'diterima' =>
                        'Selamat, Anda Dinyatakan Diterima',

                    'cadangan' =>
                        'Anda Masuk Daftar Cadangan',

                    default =>
                        'Anda Belum Dinyatakan Diterima',
                } }}

            </p>

            <p class="mt-2 text-sm leading-7 text-slate-700">

                {{ match ($hasilAkhir->keputusan) {
                    'diterima' =>
                        'Anda telah ditetapkan sebagai penerima beasiswa. Silakan mengikuti informasi lanjutan dari panitia.',

                    'cadangan' =>
                        'Anda tercatat sebagai peserta cadangan. Tunggu informasi lanjutan dari panitia.',

                    default =>
                        'Terima kasih telah mengikuti seleksi. Anda belum termasuk penerima beasiswa pada penetapan ini.',
                } }}

            </p>
        </div>

        <dl
            class="mt-4 grid gap-4
                   border-t border-slate-100
                   pt-4 sm:grid-cols-2"
        >
            <div>
                <dt class="text-xs font-semibold text-slate-500">
                    Status Administrasi
                </dt>

                <dd class="mt-1 text-sm font-bold text-[#103F36]">
                    Terverifikasi
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-slate-500">
                    Keputusan Akhir
                </dt>

                <dd class="mt-1 text-sm font-bold text-[#103F36]">
                    {{ match ($hasilAkhir->keputusan) {
                        'diterima' => 'Diterima',
                        'cadangan' => 'Cadangan',
                        default => 'Tidak Diterima',
                    } }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-slate-500">
                    Tanggal Pengumuman
                </dt>

                <dd class="mt-1 text-sm font-bold text-[#103F36]">
                    {{
                        \Illuminate\Support\Carbon::parse(
                            $hasilAkhir->diumumkan_pada
                        )->format('d-m-Y H:i')
                    }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-slate-500">
                    Dasar Keputusan
                </dt>

                <dd class="mt-1 text-sm font-bold text-[#103F36]">
                    Hasil seleksi resmi berdasarkan WASPAS
                </dd>
            </div>
        </dl>

    @else

        {{-- Belum ada keputusan yang dipublikasikan --}}
        <div
            class="mt-4 rounded-xl border border-slate-200
                   bg-slate-50 p-4"
        >
            <p class="font-bold text-[#103F36]">
                Menunggu Pengumuman Hasil Seleksi
            </p>

            <p class="mt-2 text-sm leading-7 text-slate-600">
                Pengajuan kamu sudah lolos verifikasi
                administrasi.

                Keputusan akhir belum diumumkan oleh Admin.
                Silakan pantau halaman ini secara berkala.
            </p>
        </div>

    @endif

</section>

@endif
