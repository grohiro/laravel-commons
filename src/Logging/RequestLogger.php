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
        $data = [
            'headers' => $request->header(),
            'json' => $request->json()->all(),
            'input' => $request->input(),
        ];
        $this->logger->debug(sprintf("%s %s", $request->method(), $request->fullUrl()), $data);

        $response = $next($request);

        $data = [
            'content' => $response->getContent(),
        ];
        $this->logger->debug('RESPONSE', $data);
        return $response;
    }
}
