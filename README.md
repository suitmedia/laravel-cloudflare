# Laravel Cloudflare

> Simple and easy varnish integration in Laravel

## Synopsis

This package offers easy ways to integrate your Laravel application with Varnish Cache.

## Table of contents

- [Setup](#setup)
- [Publish package assets](#publish-package-assets)
- [Configuration](#configuration)
- [Usage](#usage)
- [Credits](#credits)
- [License](#license)

## Setup

Install the package via Composer :

```sh
$ composer require suitmedia/laravel-cloudflare
```

### Laravel version compatibility

| Laravel version | Laravel Cloudflare version |
| :-------------- | :------------------------- |
| 8.x             | 0.x                        |

### Service Provider

Add the package service provider in your `config/app.php`

```php
'providers' => [
    // ...
    RichanFongdasen\Varnishable\ServiceProvider::class,
];
```

### Alias

Add the package's alias in your `config/app.php`

```php
'aliases' => [
    // ...
    'Varnishable' => RichanFongdasen\Varnishable\Facade::class,
];
```

## Publish package assets

Publish the package asset files using this `php artisan` command

```sh
$ php artisan vendor:publish --provider="RichanFongdasen\Varnishable\ServiceProvider"
```

The command above would create new `varnishable.php` file in your application's config directory.

## Configuration

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Varnish hosts
    |--------------------------------------------------------------------------
    |
    | Specify the hostnames of your varnish instances. You can use array
    | to specify multiple varnish instances.
    |
    */
    'varnish_hosts' => env('VARNISH_HOST', '127.0.0.1'),

    /*
    |--------------------------------------------------------------------------
    | Varnish port
    |--------------------------------------------------------------------------
    |
    | Specify the port number that your varnish instances are listening to.
    |
    */
    'varnish_port' => env('VARNISH_PORT', 6081),

    /*
    |--------------------------------------------------------------------------
    | Cache duration
    |--------------------------------------------------------------------------
    |
    | Specify the default varnish cache duration in minutes.
    |
    */
    'cache_duration' => env('VARNISH_DURATION', 60 * 24),

    /*
    |--------------------------------------------------------------------------
    | Cacheable header
    |--------------------------------------------------------------------------
    |
    | Specify the custom HTTP header that we should add, so Varnish can
    | recognize any responses containing the header and cache them.
    |
    */
    'cacheable_header' => 'X-Varnish-Cacheable',

    /*
    |--------------------------------------------------------------------------
    | Uncacheable header
    |--------------------------------------------------------------------------
    |
    | Specify the custom HTTP header that we should add, so Varnish won't
    | cache any reponses containing this header.
    |
    */
    'uncacheable_header' => 'X-Varnish-Uncacheable',

    /*
    |--------------------------------------------------------------------------
    | Use ETag Header
    |--------------------------------------------------------------------------
    |
    | Please specify if you want to use ETag header for any of your static
    | contents.
    |
    */
    'use_etag' => true,

    /*
    |--------------------------------------------------------------------------
    | Use Last-Modified Header
    |--------------------------------------------------------------------------
    |
    | Please specify if you want to use Last-Modified header for any of your
    | static contents.
    |
    */
    'use_last_modified' => true,

    /*
    |--------------------------------------------------------------------------
    | ESI capability header
    |--------------------------------------------------------------------------
    |
    | Please specify the ESI capability header that the varnish server would
    | send if there is any ESI support.
    |
    */
    'esi_capability_header' => 'Surrogate-Capability',

    /*
    |--------------------------------------------------------------------------
    | ESI reply header
    |--------------------------------------------------------------------------
    |
    | Please specify the HTTP header that you want to send as a reply
    | in response to ESI capability header which the varnish server sent in
    | current request.
    |
    */
    'esi_reply_header' => 'Surrogate-Control',

];
```

## Usage

This section is currently under construction.

## Credits

- [spatie/laravel-varnish](https://github.com/spatie/laravel-varnish) - Some concepts in this repository was inspired by this package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
