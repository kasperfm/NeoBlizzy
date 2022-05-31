<?php

namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class NeoBlizzyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('NeoBlizzy',function() {
            return new NeoBlizzy;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/neoblizzy.php' => config_path('neoblizzy.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/neoblizzy.php',
            'neoblizzy'
        );
    }
}
