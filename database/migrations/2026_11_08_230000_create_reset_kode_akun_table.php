
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'reset_kode_akun',
            function (Blueprint $table): void {

                $table->id();

                // ID akun dari tabel lama "akun".
                // Tidak membuat foreign key karena
                // struktur tabel lama dipertahankan.
                $table->unsignedBigInteger('akun_id')
                    ->unique();

                // Kode OTP tidak disimpan sebagai
                // angka asli, tetapi dalam bentuk hash.
                $table->string('kode_hash', 255);

                // Maksimal 5 percobaan salah.
                $table->unsignedTinyInteger('percobaan')
                    ->default(0);

                // Masa berlaku kode 10 menit.
                $table->dateTime('kedaluwarsa_pada');

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('reset_kode_akun');
    }
};
