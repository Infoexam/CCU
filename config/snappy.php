<?php

return [

    'pdf' => [
        'enabled' => true,
        'binary'  => PHP_OS === 'Darwin' ? '/usr/local/bin/wkhtmltopdf' : base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => PHP_OS === 'Darwin' ? '/usr/local/bin/wkhtmltoimage' : base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltoimage-amd64'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
