[![Build Status](https://github.com/suitmedia/laravel-cloudflare/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/suitmedia/laravel-cloudflare/actions/workflows/main.yml)
[![codecov](https://codecov.io/gh/suitmedia/laravel-cloudflare/branch/main/graph/badge.svg?token=5EK3CL6SYE)](https://codecov.io/gh/suitmedia/laravel-cloudflare)
[![Total Downloads](https://poser.pugx.org/suitmedia/laravel-cloudflare/d/total.svg)](https://packagist.org/packages/richan-fongdasen/laravel-gcr-worker)
[![Latest Stable Version](https://poser.pugx.org/suitmedia/laravel-cloudflare/v/stable.svg)](https://packagist.org/packages/richan-fongdasen/laravel-gcr-worker)
[![License: MIT](https://poser.pugx.org/suitmedia/laravel-cloudflare/license.svg)](https://opensource.org/licenses/MIT)

# Laravel Cloudflare

> Purge Cloudflare Cache on Model Update

## Synopsis

This package offers easy ways to purge Cloudflare Cache on Model update.

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
| 8.x             | 1.x                        |

### Service Provider

Add the package service provider in your `config/app.php`

```php
'providers' => [
    // ...
    Suitmedia\Cloudflare\ServiceProvider::class,
];
```

### Alias

Add the package's alias in your `config/app.php`

```php
'aliases' => [
    // ...
    'CloudflareCache' => Suitmedia\Cloudflare\Facade::class,
];
```

## Publish package assets

Publish the package asset files using this `php artisan` command

```sh
$ php artisan vendor:publish --provider="Suitmedia\Cloudflare\ServiceProvider"
```

The command above would create new `laravel-cloudflare.php` file in your application's config directory.

## Configuration

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Site
    |--------------------------------------------------------------------------
    |
    | Specify the sitename of the Cloudflare.
    |
    */
    'sitename' => env('CLOUDFLARE_SITE', 'test.com'),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Authentication Email
    |--------------------------------------------------------------------------
    |
    | Specify the authentication email to access Cloudflare.
    |
    */
    'auth_email' => env('CLOUDFLARE_AUTH_EMAIL', 'example@domain.com'),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Authentication Key
    |--------------------------------------------------------------------------
    |
    | Specify the authentication key to access Cloudflare.
    |
    */
    'auth_key' => env('CLOUDFLARE_AUTH_KEY', 'test_auth_key'),
];
```

## Usage

This package assumes that the Page Rules has configured in your Cloudflare Dashboard. To configure the Page Rules, please refer to the [Page Rules Tutorial](https://support.cloudflare.com/hc/en-us/articles/218411427-Understanding-and-Configuring-Cloudflare-Page-Rules-Page-Rules-Tutorial-).

Depends on the way the Page Rules configured, Cloudflare will cache each page in our website for some time. The Cloudflare will then serve the page from its cache, and will not send the request to the application server. This become a problem when there are data updates from the server. Because Cloudflare does not know when the data is updated, so it still serve the outdated data to the user. We need to purge the cache stored in Cloudflare.

Cloudflare provide [API endpoints](https://api.cloudflare.com/#zone-purge-all-files) to purge its cache programmatically. This package utilize those API endpoints to purge the cache on model updates.

To start using this package, you need to add these credentials to the `.env` file:

```
CLOUDFLARE_SITE=registered-cloudflare-sitename.com
CLOUDFLARE_AUTH_EMAIL=cloudflare-account@email.com
CLOUDFLARE_AUTH_KEY=cloudflare-auth-key
```

You can find the Cloudflare Auth Key in the [API Tokens](https://dash.cloudflare.com/profile/api-tokens) section on the profile page in your Cloudflare account. Copy the value of the Global API Key to your .env file.

Add the `Suitmedia\Cloudflare\Model\Concerns\Cloudflare` to your model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Suitmedia\Cloudflare\Model\Concerns\Cloudflare;

class Post extends Model
{
    use Cloudflare;
}

```

Create a listener to the `Suitmedia\Cloudflare\Events\ModelHasUpdated` event:

```php
<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Suitmedia\Cloudflare\Events\ModelHasUpdated;

class PurgeCloudflareCache implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModelHasUpdated  $event
     * @return void
     */
    public function handle(ModelHasUpdated $event): void
    {
        //handle the Cloudflare purging
    }
}
```

Register the listener to the `EventServiceProvider`:

```php
protected $listen = [
    'Suitmedia\Cloudflare\Events\ModelHasUpdated' => [
        'App\Listeners\PurgeCloudflareCache',
    ]
];
```

There are several method that can be used to purge the Cloudflare cache:

- Purge all files: purge all resources in Cloudflare's cache.
- Purge by urls: remove one or more files from Cloudflare's cache either by specifying URLs.
- Purge by cache tags, hosts, or prefixes: emove one or more files from Cloudflare's cache either by specifying the host, the associated Cache-Tag, or a Prefix. **Please note that theses methods only available for Enterprise User.**

  ```php
  // purge all files
  \CloudflareCache::purgeAll();

  // purge by urls
  $urls = [
      'http://example.com/posts/post-1',
      'http://example.com/posts/post-2',
  ];
  \CloudflareCache::purgeByUrls($urls);

  // purge by Cache-Tag (only available for Enterprise user)
  $tags = [
      'news-tag',
      'posts-tag',
  ];
  \CloudflareCache::purgeByTags($tags);

  // purge by hosts (only available for Enterprise user)
  $hosts = [
    'https://example.com',
    'https://domain.com',
  ];
  \CloudflareCache::purgeByHosts($hosts);

  // purge by prefixes (only available for Enterprise user)
  $prefixes = [
    '/news',
    '/products/product-1',
  ];
  \CloudflareCache::purgeByPrefixes($prefixes);
  ```

## Credits

- [richan-fongdasen/laravel-varnishable](https://github.com/richan-fongdasen/laravel-varnishable) - The purging cache flow was inspired by this package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
