<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    private const ALLOWED_HOST_LIST = [
        '172.18.0.3',
        'react.localhost',
        'http://localhost',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach (self::ALLOWED_HOST_LIST as $allowedHost) {
            if (is_int(stripos($request->headers->get('origin'), $allowedHost))) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $request->headers->get('origin'))
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
            }
        }

        return $next($request);
    }
}
