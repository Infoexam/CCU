<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Support\Collection;
use M6Web\Component\Firewall\Firewall;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        $user = $request->user();

        if (! is_null($role)) {
            $this->iptables($request->ip(), $role);
        } elseif (is_null($user)) {
            throw new UnauthorizedHttpException('Unauthorized');
        } elseif (! is_null($role) && ! $user->own($role)) {
            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }

    /**
     * Valid the request ip is valid or not.
     *
     * @param string $ip
     * @param string $role
     */
    protected function iptables($ip, $role)
    {
        // 取得對應身份之規則
        $ips = Cache::tags('config')
            ->get('iptables', new Collection())
            ->filter(function ($rule) use ($role) {
                return $rule['role'] === $role;
            })
            ->pluck('ip')
            ->toArray();

        // 防火牆檢查
        $allow = (new Firewall())->setDefaultState(false)
            ->addList(array_merge($ips, $this->privateNetwork()), 'whiteList', true)
            ->setIpAddress($ip)
            ->handle();

        if (! $allow) {
            throw new HttpException(451);
        }
    }

    /**
     * Get private network ip address.
     *
     * @return array
     */
    protected function privateNetwork()
    {
        return [
            '127.0.0.0/8',
            '::1',
        ];
    }
}
