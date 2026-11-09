<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_beasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id');
            $table->string('status')->default('belum mengajukan'); // Sesuaikan dengan logika aplikasi
            // Tambahkan kolom lain jika diperlukan di sini
            $table->timestamps();

            // Relasi ke tabel akun
            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_beasiswa');
    }
};