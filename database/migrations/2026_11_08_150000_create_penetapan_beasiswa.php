
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu penetapan untuk satu arsip perhitungan WASPAS.
        Schema::create('penetapan_seleksi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('riwayat_seleksi_id')->unique();
            $table->unsignedBigInteger('admin_id');

            $table->unsignedInteger('kuota');
            $table->unsignedInteger('jumlah_cadangan')->default(0);

            $table->string('status', 15)->default('draf');
            $table->timestamp('diumumkan_pada')->nullable();

            $table->timestamps();

            $table->foreign('riwayat_seleksi_id')
                ->references('id')
                ->on('riwayat_seleksi')
                ->restrictOnDelete();
        });

        // Keputusan untuk setiap peserta dalam penetapan.
        Schema::create('penetapan_peserta', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penetapan_seleksi_id')
                ->constrained('penetapan_seleksi')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedInteger('peringkat');

            $table->decimal('nilai_waspas', 15, 10);

            $table->string('keputusan', 20);

            $table->unique(
                ['penetapan_seleksi_id', 'pengajuan_id'],
                'unik_peserta_penetapan'
            );

            $table->index('pengajuan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penetapan_peserta');
        Schema::dropIfExists('penetapan_seleksi');
    }
};
