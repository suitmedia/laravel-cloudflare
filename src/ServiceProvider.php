<?php

namespace Suitmedia\Cloudflare;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            realpath(dirname(__DIR__).'/config/laravel-cloudflare.php') => config_path('laravel-cloudflare.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = realpath(dirname(__DIR__).'/config/laravel-cloudflare.php');

        if ($configPath !== false) {
            $this->mergeConfigFrom($configPath, 'laravel-cloudflare');
        }

        $this->app->singleton(ClouflareService::class, function (): CloudflareService {
            return new CloudflareService(new Client([
                'base_uri'              => Client::BASE_URI,
                RequestOptions::HEADERS => [
                    'X-Auth-Key'   => $this->app['config']['auth_key'],
                    'X-Auth-Email' => $this->app['config']['auth_email'],
                ],
            ]));
        });

        $this->app->singleton(CloudflareObserver::class, function (): CloudflareObserver {
            return new CloudflareObserver();
        });
    }
}
