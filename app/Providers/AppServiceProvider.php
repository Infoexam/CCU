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
        $this->registerProviders();

        $this->morphMap();
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
        }

        return $this;
    }

    /**
     * Set the morph map for polymorphic relations.
     *
     * @return $this
     */
    protected function morphMap()
    {
        Relation::morphMap([
            \App\Websites\Announcement::class,
            \App\Exams\Apply::class,
            \App\Categories\Category::class,
            \App\Accounts\Certificate::class,
            \App\Configs\Config::class,
            \App\Exams\Exam::class,
            \App\Websites\Faq::class,
            \App\Exams\Listing::class,
            \App\Exams\Option::class,
            \App\Exams\Paper::class,
            \App\Exams\Question::class,
            \App\Accounts\Receipt::class,
            \App\Exams\Result::class,
            \App\Accounts\User::class,
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
