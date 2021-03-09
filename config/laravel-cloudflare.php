<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Site
    |--------------------------------------------------------------------------
    |
    | Specify the sitename of the Cloudflare.
    |
    */
    'sitename' => env('CLOUDFLARE_SITE'),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Authentication Email
    |--------------------------------------------------------------------------
    |
    | Specify the authentication email to access Cloudflare.
    |
    */
    'auth_email' => env('CLOUDFLARE_AUTH_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Authentication Key
    |--------------------------------------------------------------------------
    |
    | Specify the authentication key to access Cloudflare.
    |
    */
    'auth_key' => env('CLOUDFLARE_AUTH_KEY'),
];
