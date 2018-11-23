<?php
namespace LaravelCommons;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * LaravelCommons base provider
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * boot()
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config/laravel_commons.php' => config_path('laravel_commons.php'),
        ]);
    }

    /**
     * register()
     */
    public function register()
    {
        $this->app->register(Logging\LoggerServiceProvider::class);
        $this->app->register(SSL\SslServiceProvider::class);
    }
}
