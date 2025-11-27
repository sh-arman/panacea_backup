<?php

namespace Panacea\Providers;

use Illuminate\Support\ServiceProvider;
use Panacea\Services\ShortCodeActivationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix for Laravel 5.5+ with older MySQL versions
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sentinel.activations', function ($app) {
            $config = $app['config']->get('cartalyst.sentinel.activations');
            return new ShortCodeActivationRepository($config['model'], $config['expires']);
        });
    }
}
