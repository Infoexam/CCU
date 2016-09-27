<?php

namespace App\Providers;

use App\Extensions\InfoexamUserProvider;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('infoexam', function ($app, array $config) {
            return new InfoexamUserProvider($app['hash'], $config['model']);
        });
    }
}
