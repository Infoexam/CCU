<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roles = array_reverse(func_get_args());

        // 移除 $request 及 $next 兩個參數
        array_pop($roles);
        array_pop($roles);

        if (null === ($user = Auth::user())) {
            throw new UnauthorizedHttpException('Unauthorized');
        } else if (count($roles) && ! $user->hasRole($roles)) {
            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }
}
