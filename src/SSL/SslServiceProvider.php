<?php
namespace LaravelCommons\SSL;

use Illuminate\Support\ServiceProvider;

class SslServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $envs = config('laravel_commons.ssl.environments', []);
        if (!is_array($envs)) {
            throw new \Exception('The value of `laravel_commons.ssl.environments` must be an array');
        }

        if (in_array(config('app.env'), $envs)) {
            \URL::forceScheme('https');

            $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
            $kernel->pushMiddleware(ForceHttpProtocol::class);
        }
    }
}
