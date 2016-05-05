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
            Exam::class
        ]);
    }

    /**
     * 註冊非 production 和 development 環境的 providers
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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
