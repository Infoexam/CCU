<?php

return [

    'x_content_type_options' => 'nosniff',

    'x_frame_options' => 'sameorigin',

    'x_xss_protection' => '1; mode=block',

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     */
    'csp' => [
        'rule' => "default-src 'none'; script-src 'self' 'unsafe-eval' https: cdn-infoexam.ccu.edu.tw cdnjs.cloudflare.com ajax.googleapis.com; style-src 'self' 'unsafe-inline' https: cdn-infoexam.ccu.edu.tw cdnjs.cloudflare.com fonts.googleapis.com; img-src 'self' https:; font-src https: cdnjs.cloudflare.com fonts.gstatic.com; connect-src 'self'",

        /*
         * The URIs that should be excluded to add CSP header.
         */
        'except' => [
            'api/*',
        ],
    ],

    /*
     * Make sure you enable https first.
     */
    'force_https' => env('FORCE_HTTPS', false),

    /*
     * HTTP Strict Transport Security
     *
     * https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Note: hsts will only add when the request is secure or config is set to force https
     */
    'hsts' => [
        'enable' => true,

        'max_age' => 15552000,

        'include_sub_domains' => false,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * Note: hpkp will only add when the request is secure or config is set to force https
     */
    'hpkp' => [
        'enable' => true,

        'pins' => [
            'EdPa1nDt22bZYh+8O/dPc4NvelDJV1dIX1sSB48KLYQ=',
            'klO23nT2ehFDXCfx3eHTDRESMz3asj1muO+4aIdjiuY=',
            'YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg=',
        ],

        'max_age' => 300,

        'include_sub_domains' => false,
    ],

];
