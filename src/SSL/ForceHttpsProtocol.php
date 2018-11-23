<?php
namespace LaravelCommons\SSL;

use Closure;

class ForceHttpProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->server->get('HTTP_X_FORWARDED_PROTO') != 'https') {
            // via ELB
            return redirect()->secure($request->getRequestUri());
        } else {
            // direct
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }
        return $next($request);
    }
}
