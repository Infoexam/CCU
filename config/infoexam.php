<?php

return [

    'SSO_URL' => env('SSO_URL'),

    'FIREWALL_ON' => env('FIREWALL_ON', false),

    'GITHUB_WEBHOOK_SECRET' => env('GITHUB_WEBHOOK_SECRET'),

    'COMPOSER_HOME' => env('COMPOSER_HOME', ''),

    'static_url' => env('STATIC_URL', ''),

    'image_dir' => env('IMAGE_DIR', public_path('assets/images')),

];
