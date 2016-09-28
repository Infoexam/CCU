<?php

return [
    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-XSS-Protection
     *
     * Reference: https://blogs.msdn.microsoft.com/ieinternals/2011/01/31/controlling-the-xss-filter/
     */

    'x-xss-protection' => '1; mode=block',

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => env('SECURITY_HEADER_HSTS_ENABLE', false),

        'max-age' => 15552000,

        'include-sub-domains' => true,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * When hashes is empty, hpkp will be ignored.
     */

    'hpkp' => [
        'hashes' => [
            //
        ],

        'include-sub-domains' => true,

        'max-age' => 15552000,

        'report-only' => false,

        'report-uri' => null,
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     *
     * If custom-csp is not null, csp will be ignored.
     *
     * Note: custom-csp does not support report-only.
     */

    'custom-csp' => env('SECURITY_HEADER_CUSTOM_CSP', null),

    'csp' => [
        'report-only' => false,

        'report-uri' => null,

        'upgrade-insecure-requests' => false,

        'base-uri' => [
            //
        ],

        'default-src' => [
            //
        ],

        'child-src' => [
            //
        ],

        'script-src' => [
            'allow' => [
                env('STATIC_URL', 'http://localhost'),
            ],

            'hashes' => [
                ['sha256' => 'NjVxv9AQfkNJLUZ04QSQUzDUuCIyKiETtAFrMxU9taw='],
            ],

            'nonces' => [
                //
            ],

            'self' => true,

            'unsafe-inline' => false,

            'unsafe-eval' => false,
        ],

        'style-src' => [
            'allow' => [
                env('STATIC_URL', 'http://localhost'),
            ],

            'self' => true,

            'unsafe-inline' => true,
        ],

        'img-src' => [
            'types' => [
                'https:',
            ],

            'self' => true,

            'data' => false,
        ],

        /*
         * The following directives are all use 'allow' and 'self' flag.
         */

        'font-src' => [
            'allow' => [
                env('STATIC_URL', 'http://localhost'),
            ],

            'self' => true,
        ],

        'connect-src' => [
            'allow' => [
                'wss://localhost:3000',
            ],

            'self' => true,
        ],

        'form-action' => [
            //
        ],

        'frame-ancestors' => [
            //
        ],

        'media-src' => [
            //
        ],

        'object-src' => [
            //
        ],

        'plugin-types' => [
            //
        ],
    ],
];
