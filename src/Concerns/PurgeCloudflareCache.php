<?php

namespace Suitmedia\Cloudflare\Concerns;

use Exception;
use GuzzleHttp\Client;
use Suitmedia\Cloudflare\CloudflareService;

trait PurgeCloudflareCache
{
    /**
     * Purge entire cache for an application domain.
     *
     * @throws Exception
     *
     * @return int
     */
    public function purgeAll(): int
    {
        return $this->sendPurgeRequest([
            'purge_everything' => true,
        ]);
    }

    /**
     * Purge the Cloudflare cache by urls
     *
     * @param array $urls
     *
     * @throws Exception
     *
     * @return int
     */
    public function purgeByUrls(array $urls): int
    {
        return $this->sendPurgeRequest([
            'files' => $urls,
        ]);
    }

    /**
     * Purge the Cloudflare by prefixes,
     * only available for Enterprise user.
     *
     * @param array $prefixes
     *
     * @throws Exception
     *
     * @return int
     */
    public function purgeByPrefixes(array $prefixes): int
    {
        return $this->sendPurgeRequest([
            'prefixes' => $prefixes,
        ]);
    }

    /**
     * Purge the Cloudflare by hosts,
     * only available for Enterprise user.
     *
     * @param array $hosts
     *
     * @throws Exception
     *
     * @return int
     */
    public function purgeByHosts(array $hosts): int
    {
        return $this->sendPurgeRequest([
            'hosts' => $hosts,
        ]);
    }

    /**
     * Purge the Cloudflare by tags,
     * only available for Enterprise user.
     *
     * @param array $tags
     *
     * @throws Exception
     *
     * @return int
     */
    public function purgeByTags(array $tags): int
    {
        return $this->sendPurgeRequest([
            'tags' => $tags,
        ]);
    }

    /**
     * Send the purge request to Cloudflare.
     *
     * @param array  $params
     *
     * @throws Exception
     *
     * @return int
     */
    protected function sendPurgeRequest(array $params): int
    {
        $guzzle = $this->getGuzzle();
        $url = CloudflareService::BASE_URI.'/zones/'.$this->getZoneId().'/purge_cache';
        $response = $guzzle->request('POST', $url, [
            'headers' => [
                'X-Auth-Key'   => $this->getConfig('auth_key'),
                'X-Auth-Email' => $this->getConfig('auth_email'),
            ],
            'body'    => json_encode($params),
        ]);

        return $response->getStatusCode();
    }

    /**
     * Get the Cloudflare zone id
     *
     * @return string | null
     */
    protected function getZoneId(): string
    {
        $cacheKey = 'laravel-cloudflare-zone-id';
        $ttl = \Carbon\Carbon::now()->addSeconds(86400);
        return \Cache::remember($cacheKey, $ttl, function () {
            $sitename = $this->getConfig('sitename');
            $guzzle = $this->getGuzzle();
            $response = $guzzle->request('GET', $this::BASE_URI.'/zones', [
                'query'   => ['name' => $sitename],
                'headers' => [
                    'X-Auth-Key'   => $this->getConfig('auth_key'),
                    'X-Auth-Email' => $this->getConfig('auth_email'),
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);

                return $data['result'][0]['id'];
            }

            return null;
        });        
    }

    /**
     * Get configuration value for a specific key.
     *
     * @param string $key
     *
     * @return mixed
     */
    abstract public function getConfig($key);

    /**
     * Get guzzle client object.
     *
     * @return \GuzzleHttp\Client
     */
    abstract public function getGuzzle(): Client;
}
