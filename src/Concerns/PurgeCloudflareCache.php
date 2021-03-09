<?php

namespace Suitmedia\Cloudflare\Concerns;

use Exception;
use GuzzleHttp\Client;

trait PurgeCloudflareCache
{
    /**
     * Purge entire cache for an application domain.
     *
     * @throws Exception
     *
     * @return void
     */
    public function purgeAll(): void
    {
        $this->sendPurgeRequest([
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
     * @return void
     */
    public function purgeByUrl(string $urls): void
    {
        $this->sendPurgeRequest([
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
     * @return void
     */
    public function purgeByPrefixes(array $prefixes): void
    {
        $this->sendPurgeRequest([
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
     * @return void
     */
    public function purgeByHosts(array $hosts): void
    {
        $this->sendPurgeRequest([
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
     * @return void
     */
    public function purgeByTags(array $tags): void
    {
        $this->sendPurgeRequest([
            'tags' => $tags,
        ]);
    }

    /**
     * Get the Cloudflare zone id
     *
     * @return string | null
     */
    protected function getZoneId(): string
    {
        $sitename = $this->getConfig('sitename');
        $guzzle = $this->getGuzzle();
        $response = $guzzle->request('GET', '/zones', ['query' => ['name' => $sitename]]);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($resp->getBody(), true);
            
            return $data['result'][0]['id'];
        }

        return null;
    }

    /**
     * Send the purge request to Cloudflare.
     *
     * @param array  $params
     *
     * @throws Exception
     *
     * @return void
     */
    protected function sendPurgeRequest(array $params): void
    {
        $guzzle = $this->getGuzzle();
        $zoneId = $this->getZoneId();
        $url = 'zones/'.$zoneId.'/purge_cache';
        $guzzle->request('POST', $url, ['body' => $params]);
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
