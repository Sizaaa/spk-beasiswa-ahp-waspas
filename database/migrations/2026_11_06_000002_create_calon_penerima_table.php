<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_penerima', function (Blueprint $table): void {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100)->nullable();
            $table->unsignedBigInteger('penghasilan_orang_tua');
            $table->unsignedSmallInteger('jumlah_tanggungan');
            $table->decimal('nilai_akademik', 5, 2);
            $table->unsignedTinyInteger('kondisi_tempat_tinggal');
            $table->unsignedTinyInteger('prestasi_non_akademik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_penerima');
    }
};
