<?php

namespace App\Http\Middleware;

use Closure;

class ConvertNullStrings
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $request->merge(
            array_map(function ($value) {
                return $value === 'null' ? null : $value;
            }, $request->all())
        );

        return $next($request);
    }
}
