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
        /** @var \App\Accounts\User|null $user */
        $user = Auth::guard()->user();

        $role = 3 === func_num_args() ? func_get_arg(2) : null;

        if (is_null($user)) {
            $e = new UnauthorizedHttpException('Unauthorized');
        } elseif (is_string($role) && ! $user->is($role)) {
            $e = new AccessDeniedHttpException;
        }

        if (isset($e) && $request->is('api/*')) {
            throw $e;
        }

        return $next($request);
    }
}
