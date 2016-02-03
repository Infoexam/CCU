<?php

namespace App\Providers;

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
        $this->registerProviders()
            ->setMorphMap();
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
     * 設定 morphMap 關聯關係
     *
     * @return $this
     *
     * @link http://yish.im/2016/01/20/Laravel-morphMap-future/
     */
    protected function setMorphMap()
    {
        Relation::morphMap([
            'announcement' => \App\Infoexam\Website\Announcement::class,
            'exam-question' => \App\Infoexam\Exam\Question::class,
            'exam-option' => \App\Infoexam\Exam\Option::class,
        ]);

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
