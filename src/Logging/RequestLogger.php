<?php
namespace LaravelCommons\Logging;

use Closure;

class RequestLogger
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    public function __construct()
    {
        $this->logger = resolve('logger.request');
        $this->debug = config('laravel_commons.logging.request', false);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->debug) {
            $data = [
                'headers' => $request->header(),
                'json' => $request->json()->all(),
                'input' => $request->input(),
            ];
            $this->logger->debug(sprintf("%s %s", $request->method(), $request->fullUrl()), $data);
        }
        $response = $next($request);
        if ($this->debug) {
            $data = [
                'content' => $response->getContent(),
            ];
            $this->logger->debug('RESPONSE', $data);
        }
        return $response;
    }
}
