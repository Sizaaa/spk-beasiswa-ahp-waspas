<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Menggunakan tabel akun dari website lama
    protected $table = 'akun';

    // Kolom utama tabel
    protected $primaryKey = 'id';

    // Tabel akun lama tidak memiliki updated_at
    public $timestamps = false;

    // Data akun yang boleh disimpan
    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password_hash',
    ];

    // Password tidak ditampilkan
    protected $hidden = [
        'password_hash',
    ];

    // Password lama tersimpan di password_hash,
    // bukan kolom password bawaan Laravel
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }
}
