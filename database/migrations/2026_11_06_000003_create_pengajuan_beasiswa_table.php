<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_beasiswa', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('akun_id')->unique();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('nisn', 20)->nullable()->unique();
            $table->string('asal_sekolah', 100)->nullable();
            $table->string('kelas', 20)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('pekerjaan_orang_tua', 100)->nullable();
            $table->unsignedBigInteger('penghasilan_orang_tua')->nullable();
            $table->unsignedSmallInteger('jumlah_tanggungan')->nullable();
            $table->unsignedTinyInteger('kondisi_tempat_tinggal')->nullable();
            $table->decimal('nilai_akademik', 5, 2)->nullable();
            $table->unsignedTinyInteger('prestasi_non_akademik')->nullable();
            $table->string('status', 30)->default('draf');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diajukan_pada')->nullable();
            $table->timestamps();

            $table->foreign('akun_id')
                ->references('id')
                ->on('akun')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_beasiswa');
    }
};
