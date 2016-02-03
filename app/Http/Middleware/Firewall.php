<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use M6Web\Component\Firewall\Firewall as FirewallChecker;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class Firewall
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->domain = $this->getDomain($request);

        $this->checkForMaintenance();

        if (config('infoexam.firewall_on')) {
            $this->checkFirewall($request);
        }

        return $next($request);
    }

    /**
     * 確認是否是維護模式
     */
    protected function checkForMaintenance()
    {
        $maintenance = Cache::tags('config')->get('websiteMaintenance');

        if (! is_null($maintenance) && in_array($this->domain, ['student', 'exam'])) {
            $maintenance = $maintenance->get($this->domain);

            if ($maintenance['maintenance']) {
                throw new ServiceUnavailableHttpException(null, $maintenance['message'] ?: null);
            }
        }
    }

    /**
     * 防火牆過濾
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function checkFirewall($request)
    {
        $filter = $this->getFilter();

        if (is_null($filter)) {
            return;
        }

        // 過濾出需檢查的 IP 位址
        $ipList = Cache::tags('config')->get('ipRules', collect())
            ->filter(function ($ipRule) use ($filter) {
                return $ipRule[$this->domain] === $filter;
            })->all();

        // 防火牆檢查
        $allow = (new FirewallChecker())->setDefaultState(! $filter)
            ->addList(array_keys($ipList), 'ban', $filter)
            ->setIpAddress($request->ip())
            ->handle();

        if (! $allow) {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * 取得 firewall 的過濾條件
     *
     * @return bool|null
     */
    protected function getFilter()
    {
        if (in_array($this->domain, ['api', 'others'])) {
            return null;
        } else if (in_array($this->domain, ['admin', 'exam'])) {
            return true;
        }

        return false;
    }

    /**
     * 取得訪問領域
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function getDomain($request)
    {
        $domain = $request->segment(1);

        if (in_array($domain, ['api', 'admin', 'exam'])) {
            return $domain;
        } else if (in_array($domain, ['deploy', 'opcache-reset'])) {
            return 'others';
        }

        return 'student';
    }
}
