<?php
namespace LaravelCommons\Logging;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('laravel_commons.logging.query.enable', false)) {
            $logger = $this->app->make('logger.query');
            \Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($event) use ($logger) {
                $logger->debug($event->sql, $event->bindings, ['time' => $event->time]);
            });
        }

        $this->app->bind(Client::class, function ($app) {
            $opts = ['verify' => false];
            if (config('laravel_commons.logging.http.enable', false)) {
                $handler = HandlerStack::create();
                $handler->push(Middleware::log(
                    $app->make('logger.http'),
                    new MessageFormatter('[REQUEST]{method} {uri} {req_body} [RESPONSE]{code} {res_body}')
                ));
                $opts['handler'] = $handler;
            }
            return new Client($opts);
        });

        if (config('laravel_commons.logging.request.enable', false)) {
            $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
            $kernel->pushMiddleware(RequestLogger::class);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Incoming request log
        if (config('laravel_commons.logging.request.enable', false)) {
            $this->app->bind('logger.request', function () {
                $logger = new Logger('request');
                $logger->pushHandler(new StreamHandler(config('laravel_commons.logging.request.path'), config('laravel_commons.log_level', 'debug')));
                return $logger;
            });
        }

        // Guzzle HTTP request and response logger
        if (config('laravel_commons.logging.http.enable', false)) {
            $this->app->bind('logger.http', function () {
                $logger = new Logger('http');
                $logger->pushHandler(new StreamHandler(config('laravel_commons.logging.http.path'), config('laravel_commons.log_level', 'debug')));
                return $logger;
            });
        }

        // Query log
        if (config('laravel_commons.logging.query.enable', false)) {
            $this->app->bind('logger.query', function () {
                $logger = new Logger('query');
                $logger->pushHandler(new StreamHandler(config('laravel_commons.logging.query.path'), config('laravel_commons.log_level', 'debug')));
                return $logger;
            });
        }

        // Console
        if (config('laravel_commons.logging.console.enable', false)) {
            $this->app->bind('logger.console', function () {
                $logger = new Logger('console');
                $logger->pushHandler(new StreamHandler(config('laravel_commons.logging.console.path'), config('laravel_commons.log_level', 'debug')));
                $logger->pushHandler(new StreamHandler('php://stdout', config('laravel_commons.log_level', 'debug')));
                return $logger;
            });
        }
    }
}
