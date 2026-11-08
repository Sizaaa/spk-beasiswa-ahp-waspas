<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminCalonPenerimaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('calon_penerima', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('kode', 20)->unique();
            $table->string('nama', 100)->nullable();
            $table->unsignedBigInteger('penghasilan_orang_tua');
            $table->unsignedSmallInteger('jumlah_tanggungan');
            $table->decimal('nilai_akademik', 5, 2);
            $table->unsignedTinyInteger('kondisi_tempat_tinggal');
            $table->unsignedTinyInteger('prestasi_non_akademik');
            $table->timestamps();
        });

        foreach ([
            ['A1', 1500000, 3, 85, 3, 2],
            ['A2', 2500000, 2, 90, 2, 4],
            ['A3', 1000000, 5, 80, 4, 1],
            ['A4', 3000000, 4, 88, 2, 3],
            ['A5', 1800000, 3, 92, 3, 5],
        ] as [$kode, $penghasilan, $tanggungan, $akademik, $kondisi, $prestasi]) {
            DB::table('calon_penerima')->insert([
                'kode' => $kode,
                'nama' => $kode,
                'penghasilan_orang_tua' => $penghasilan,
                'jumlah_tanggungan' => $tanggungan,
                'nilai_akademik' => $akademik,
                'kondisi_tempat_tinggal' => $kondisi,
                'prestasi_non_akademik' => $prestasi,
            ]);
        }
    }

    public function test_admin_can_view_search_and_count_alternatives(): void
    {
        $this->loginAs('admin');

        $this->get(route('admin.calon-penerima.index'))
            ->assertOk()
            ->assertSee('Jumlah seluruh alternatif')
            ->assertSee('A1')
            ->assertSee('A5');

        $this->get(route('admin.calon-penerima.index', ['cari' => 'A4']))
            ->assertOk()
            ->assertSee('A4')
            ->assertDontSee('>A1<');
    }

    public function test_admin_can_create_update_and_delete_an_alternative(): void
    {
        $this->loginAs('admin');

        $data = $this->dataAlternatif('A6');

        $this->post(route('admin.calon-penerima.store'), $data)
            ->assertRedirect(route('admin.calon-penerima.index'))
            ->assertSessionHas('sukses');

        $alternatif = DB::table('calon_penerima')
            ->where('kode', 'A6')
            ->first();

        $this->assertNotNull($alternatif);

        $data['nama'] = 'Nama Diperbarui';
        $this->put(
            route('admin.calon-penerima.update', $alternatif->id),
            $data
        )
            ->assertRedirect(route('admin.calon-penerima.index'))
            ->assertSessionHas('sukses');

        $this->assertDatabaseHas('calon_penerima', [
            'id' => $alternatif->id,
            'nama' => 'Nama Diperbarui',
        ]);

        $this->delete(
            route('admin.calon-penerima.destroy', $alternatif->id)
        )
            ->assertRedirect(route('admin.calon-penerima.index'))
            ->assertSessionHas('sukses');

        $this->assertDatabaseMissing('calon_penerima', [
            'id' => $alternatif->id,
        ]);
    }

    public function test_invalid_scores_and_duplicate_codes_are_rejected(): void
    {
        $this->loginAs('admin');

        $data = $this->dataAlternatif('A6');
        $data['kode'] = 'a1';
        $data['kondisi_tempat_tinggal'] = 6;

        $this->from(route('admin.calon-penerima.index'))
            ->post(route('admin.calon-penerima.store'), $data)
            ->assertRedirect(route('admin.calon-penerima.index'))
            ->assertSessionHasErrors([
                'kode',
                'kondisi_tempat_tinggal',
            ]);

        $this->assertDatabaseCount('calon_penerima', 5);
    }

    public function test_initial_research_alternatives_cannot_be_edited_or_deleted(): void
    {
        $this->loginAs('admin');

        foreach (['A1', 'A2', 'A3', 'A4', 'A5'] as $kode) {
            $alternatif = DB::table('calon_penerima')
                ->where('kode', $kode)
                ->first();

            $this->put(
                route('admin.calon-penerima.update', $alternatif->id),
                $this->dataAlternatif($kode)
            )->assertForbidden();

            $this->delete(
                route('admin.calon-penerima.destroy', $alternatif->id)
            )->assertForbidden();
        }

        $this->assertDatabaseCount('calon_penerima', 5);

        foreach ([
            ['A1', 1500000, 3, 85, 3, 2],
            ['A2', 2500000, 2, 90, 2, 4],
            ['A3', 1000000, 5, 80, 4, 1],
            ['A4', 3000000, 4, 88, 2, 3],
            ['A5', 1800000, 3, 92, 3, 5],
        ] as [$kode, $penghasilan, $tanggungan, $akademik, $kondisi, $prestasi]) {
            $this->assertDatabaseHas('calon_penerima', [
                'kode' => $kode,
                'penghasilan_orang_tua' => $penghasilan,
                'jumlah_tanggungan' => $tanggungan,
                'nilai_akademik' => $akademik,
                'kondisi_tempat_tinggal' => $kondisi,
                'prestasi_non_akademik' => $prestasi,
            ]);
        }
    }

    public function test_non_admin_cannot_manage_alternatives(): void
    {
        $this->loginAs('user');

        $this->get(route('admin.calon-penerima.index'))
            ->assertForbidden();
    }

    private function loginAs(string $role): void
    {
        $user = (new User)->forceFill([
            'id' => 1,
            'nama_lengkap' => 'Pengguna Tes',
            'username' => 'penggunates',
            'role' => $role,
        ]);

        $this->actingAs($user);
    }

    private function dataAlternatif(string $kode): array
    {
        return [
            'kode' => $kode,
            'nama' => 'Calon ' . $kode,
            'penghasilan_orang_tua' => 1200000,
            'jumlah_tanggungan' => 3,
            'nilai_akademik' => 85.5,
            'kondisi_tempat_tinggal' => 3,
            'prestasi_non_akademik' => 2,
        ];
    }
}
