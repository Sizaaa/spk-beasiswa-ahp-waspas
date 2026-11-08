<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PembatasanRoleTest extends TestCase
{
    public function test_tamu_diarahkan_login_saat_membuka_admin(): void
    {
        $this->get('/admin/sensitivitas')
            ->assertRedirect('/login');
    }

    public function test_user_tidak_boleh_membuka_admin_atau_memasukkan_seleksi(): void
    {
        $akun = new User();

        $akun->id = 101;
        $akun->role = 'user';

        $this->actingAs($akun);

        $this->get('/admin/sensitivitas')
            ->assertForbidden();

        $this->get('/admin/hasil-seleksi')
            ->assertForbidden();

        $this->post(
            '/admin/seleksi-pengajuan/1/masukkan'
        )->assertForbidden();
    }

    public function test_admin_tidak_boleh_mengakses_pengajuan_milik_user(): void
    {
        $akun = new User();

        $akun->id = 102;
        $akun->role = 'admin';

        $this->actingAs($akun);

        $this->get('/user/pengajuan')
            ->assertForbidden();

        $this->post('/user/pengajuan')
            ->assertForbidden();
    }
}
