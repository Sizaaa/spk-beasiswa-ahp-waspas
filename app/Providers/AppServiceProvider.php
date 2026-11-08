<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as LaravelView;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pasang komponen konfirmasi global
        // pada layout Admin dan User.
        //
        // Komponen dimasukkan ke stack scripts
        // yang tersedia pada kedua layout.

        View::composer(
            [
                'layouts.admin',
                'layouts.utama',
            ],
            static function (LaravelView $layout): void {

                $factory = $layout->getFactory();

                $factory->startPush('scripts');

                try {

                    echo view(
                        'partials.konfirmasi-global'
                    )->render();

                } finally {

                    $factory->stopPush();
                }
            }
        );
    }
}
