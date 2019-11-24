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
        // @see https://www.codementor.io/chiemelachinedum/steps-to-enable-cors-on-a-lumen-api-backend-e5a0s1ecx
        if ($request->isMethod('OPTIONS')) {
            $headers = [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age'           => '86400',
                'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
            ];
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        foreach (self::ALLOWED_HOST_LIST as $allowedHost) {
            if (is_int(stripos($request->headers->get('origin'), $allowedHost))) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $request->headers->get('origin'))
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//                    ->header('Access-Control-Allow-Credentials', 'true')
//                    ->header('Access-Control-Max-Age', '86400')
                    ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization')
                ;
            }
        }
        return $next($request);
    }
}
