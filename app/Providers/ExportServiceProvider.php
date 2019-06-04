<?php

namespace App\Providers;

use App\Components\Export\Contract\Export;
use App\Components\Export\ExportEXCEL;
use Illuminate\Support\ServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot ()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register ()
    {
        $this->app->bind(Export::class, ExportEXCEL::class);
    }
}
