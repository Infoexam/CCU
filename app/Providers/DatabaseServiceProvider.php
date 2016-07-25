<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PDO;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $config = config('database');

        if ('mysql' === $config['default'] && isset($config['connections']['mysql']['options'])) {
            $this->verifyMysqlSsl($config['connections']['mysql']);
        }
    }

    /**
     * Verify mysql ssl config is valid or not.
     *
     * @param array $config
     *
     * @return void
     */
    protected function verifyMysqlSsl($config)
    {
        $indexes = [PDO::MYSQL_ATTR_SSL_KEY, PDO::MYSQL_ATTR_SSL_CERT, PDO::MYSQL_ATTR_SSL_CA];

        if (3 === count(array_diff($indexes, array_keys($config['options'])))) {
            return;
        }

        foreach ($indexes as $index) {
            if (! isset($config['options'][$index]) || empty($config['options'][$index])) {
                foreach ($indexes as $key) {
                    unset($config['options'][$key]);
                }

                config(['database.connections.mysql.options' => $config['options']]);

                break;
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
