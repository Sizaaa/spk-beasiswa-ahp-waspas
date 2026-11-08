<?php

namespace Tests\Unit;

use App\Services\PenetapanBeasiswa;
use PHPUnit\Framework\TestCase;

class PenetapanBeasiswaTest extends TestCase
{
    public function test_keputusan_mengikuti_kuota_dan_cadangan(): void
    {
        $layanan = new PenetapanBeasiswa();

        // Kuota 2 penerima dan 1 cadangan.
        self::assertSame(
            'diterima',
            $layanan->keputusan(1, 2, 1)
        );

        self::assertSame(
            'diterima',
            $layanan->keputusan(2, 2, 1)
        );

        self::assertSame(
            'cadangan',
            $layanan->keputusan(3, 2, 1)
        );

        self::assertSame(
            'tidak_diterima',
            $layanan->keputusan(4, 2, 1)
        );
    }
}
