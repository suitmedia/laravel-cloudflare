<?php

namespace Suitmedia\Cloudflare;

use GuzzleHttp\Client;
use Suitmedia\Cloudflare\Concerns\PurgeCloudflareCache;

class CloudflareService
{
    use PurgeCloudflareCache;

    const BASE_URI = 'https://api.cloudflare.com/client/v4';

    /**
     * Cloudflare configurations.
     *
     * @var array
     */
    protected $config;

    /**
     * Guzzle client object.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Class constructor.
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->loadConfig();
    }

    /**
     * Get configuration value for a specific key.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return data_get($this->config, $key);
    }

    /**
     * Get guzzle client object.
     *
     * @return \GuzzleHttp\Client
     */
    public function getGuzzle(): Client
    {
        return $this->guzzle;
    }

    /**
     * Load the configurations.
     *
     * @return void
     */
    public function loadConfig(): void
    {
        $this->config = app('config')->get('laravel-cloudflare');
    }

    /**
     * Set configuration value for a specific key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setConfig($key, $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Replace the guzzle http client object with
     * a new one.
     *
     * @param \GuzzleHttp\Client $guzzle
     *
     * @return void
     */
    public function setGuzzle(Client $guzzle): void
    {
        $this->guzzle = $guzzle;
    }
}
