<?php

return [

    'sso_url' => env('SSO_URL'),

    'firewall_on' => env('FIREWALL_ON', false),

    'github_webhook_secret' => env('GITHUB_WEBHOOK_SECRET'),

    'assets_dir' => env('ASSETS_DIR'),

    'composer_home' => env('COMPOSER_HOME', ''),

    'composer_path' => env('COMPOSER_PATH'),

    'image_dir' => env('IMAGE_DIR', public_path('assets/images')),

    'static_url' => env('STATIC_URL', ''),

];
