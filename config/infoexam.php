<?php

return [

    'sso_url' => env('SSO_URL'),

    'firewall_on' => env('FIREWALL_ON', false),

    'github_webhook_secret' => env('GITHUB_WEBHOOK_SECRET'),

    'composer_home' => env('COMPOSER_HOME', ''),

    'composer_path' => env('COMPOSER_PATH'),

    'static_dir' => env('STATIC_DIR'),

    'static_url' => env('STATIC_URL', ''),

];
