<?php

namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use KasperFM\NeoBlizzy\Http\Controllers\OAuth2Controller;
use KasperFM\NeoBlizzy\OAuth2\Providers\SC2Provider;

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
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../resources/database/migrations');

            $this->publishes([
                __DIR__.'/../resources/config/neoblizzy.php' => config_path('neoblizzy.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/database/migrations' => database_path('migrations'),
            ], 'neoblizzy-migrations');
        }


        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/neoblizzy.php',
            'neoblizzy'
        );

        $this->defineRoutes();
    }

    /**
     * Define the Sanctum routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        Route::group(['prefix' => 'neoblizzy'], function () {
            // SC2 routes
            Route::get(
                '/sc2auth',
                OAuth2Controller::class.'@sc2Auth'
            )->middleware('web')->name('neoblizzy.sc2auth');

            Route::get(
                '/sc2redirect/{profile}',
                OAuth2Controller::class.'@sc2Redirect'
            )->middleware('web')->name('neoblizzy.sc2redirect');


            // WoW routes
            Route::get(
                '/wowauth',
                OAuth2Controller::class.'@wowAuth'
            )->middleware('web')->name('neoblizzy.wowauth');

            Route::get(
                '/wowredirect/{profile}',
                OAuth2Controller::class.'@wowRedirect'
            )->middleware('web')->name('neoblizzy.wowredirect');
        });
    }
}
