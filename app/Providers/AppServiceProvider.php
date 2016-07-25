<?php

namespace App\Providers;

use App\Exams\Exam;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerProviders();

        Relation::morphMap([
            Exam::class,
        ]);

        $this->checkMysqlSsl();
    }

    /**
     * 註冊非 production 和 development 環境的 providers.
     *
     * @return $this
     */
    protected function registerProviders()
    {
        if (! $this->app->environment(['production', 'development'])) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        return $this;
    }

    /**
     * Remove mysql options config if there is an invalid value.
     *
     * @return void
     */
    protected function checkMysqlSsl()
    {
        $options = config('database.connections.mysql.options');

        if (in_array(null, $options, true) || in_array('', $options, true)) {
            config(['database.connections.mysql.options' => []]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
