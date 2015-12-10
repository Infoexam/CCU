<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use M6Web\Component\Firewall\Firewall;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Firewalls
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
        if (! env('FIREWALL_ON', false)) {
            return $next($request);
        }

        /** @var $ipRules \Illuminate\Support\Collection */

        $ipRules = Cache::tags('config')->get('ipRules', collect());

        // 初始化設定
        switch ($request->segment(1)) {
            case 'api':
                return $next($request);
            case 'admin':
            case 'exam':
                $rule = [
                    'target' => $request->segment(1),
                    'filter' => true,
                ];
                break;
            default :
                $rule = [
                    'target' => 'student',
                    'filter' => false,
                ];
                break;
        }

        // 過濾出需檢查的 IP 位址
        $list = $ipRules->filter(function ($ipRule) use ($rule) {
            return $ipRule[$rule['target']] === $rule['filter'];
        });

        // 防火牆檢查
        $allow = (new Firewall())->setDefaultState(! $rule['filter'])
            ->addList(array_keys($list->all()), 'ban', $rule['filter'])
            ->setIpAddress($request->ip())
            ->handle();

        if (! $allow) {
            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }
}
