
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel baru untuk arsip perhitungan.
        // Tidak mengubah data akun, pengajuan,
        // maupun calon penerima.

        Schema::create(
            'riwayat_seleksi',
            function (Blueprint $table): void {

                $table->id();

                $table->unsignedBigInteger(
                    'admin_id'
                );

                $table->string(
                    'admin_nama',
                    100
                );

                $table->string(
                    'lingkup',
                    20
                );

                $table->string(
                    'mode_bobot',
                    20
                );

                $table->unsignedInteger(
                    'jumlah_alternatif'
                );

                // Salinan lengkap data dan hasil.
                $table->longText(
                    'snapshot_json'
                );

                // Pemeriksaan perubahan isi snapshot.
                $table->char(
                    'checksum_sha256',
                    64
                );

                $table->timestamp(
                    'created_at'
                )->useCurrent();

                $table->index('created_at');
            }
        );
    }

    public function down(): void
    {
        // Jangan melakukan rollback jika tabel
        // sudah berisi arsip yang penting.
        // Rollback menghapus tabel riwayat.

        Schema::dropIfExists(
            'riwayat_seleksi'
        );
    }
};
