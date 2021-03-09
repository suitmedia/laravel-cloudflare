<?php

namespace Suitmedia\Cloudflare\Tests\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Container\Container;
use Suitmedia\Cloudflare\Tests\TestCase;
use Suitmedia\Cloudflare\CloudflareService;

class PurgeCloudflareCacheTests extends TestCase
{
    /**
     * Mocked guzzle http client object.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Dummy guzzle response
     *
     * @var Response
     */
    protected $response;

    /**
     * API endpoint
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Cloudflare service object.
     *
     * @var \Suitmedia\Cloudflare\CloudflareService
     */
    protected $service;

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp() :void
    {
        parent::setUp();

        $this->guzzle = \Mockery::mock(Client::class);
        $this->service = new CloudflareService($this->guzzle);
        $this->sitename = $this->service::BASE_URI;

        $this->response = Container::getInstance()->make(Response::class, []);
    }

    /** @test */
    public function it_can_send_purge_cache_request_to_purge_the_entire_cache()
    {
        $options = ['body' => ['purge_everything' => true]];
        $this->guzzle->shouldReceive('request')
            ->with('POST', $options)
            ->times(1)
            ->andReturn($this->response);

        $this->service->purgeAll();
    }

    /** @test */
    public function it_can_send_purge_cache_requests_based_on_the_given_urls()
    {
        $options = [
            'body' => [
                'files' => [
                    'http://localhost:8000/products',
                    'http://localhost:8000/products/product-1',
                ]
            ]
        ];

        $this->guzzle->shouldReceive('request')
            ->with('POST', $options)->times(1)
            ->andReturn($this->response);

        $this->service->purgeUrls([
            'http://localhost:8000/products',
            'http://localhost:8000/products/product-1',
        ]);
    }

    /** @test */
    public function it_can_send_purge_cache_requests_based_on_the_given_prefixes()
    {
        $options = [
            'body' => [
                'prefixes' => [
                    '/products',
                    '/news',
                ]
            ]
        ];

        $this->guzzle->shouldReceive('request')
            ->with('POST', $options)->times(1)
            ->andReturn($this->response);

        $this->service->purgePrefixes(['/products', '/news']);
    }

    /** @test */
    public function it_can_send_purge_cache_requests_based_on_the_given_hosts()
    {
        $options = [
            'body' => [
                'hosts' => [
                    'http://localhost:8000',
                    'http://example.com',
                ]
            ]
        ];

        $this->guzzle->shouldReceive('request')
            ->with('POST', $options)->times(1)
            ->andReturn($this->response);

        $this->service->purgeHosts([
            'http://localhost:8000',
            'http://example.com'
        ]);
    }

    /** @test */
    public function it_can_send_purge_cache_requests_based_on_the_given_tags()
    {
        $options = [
            'body' => [
                'tags' => [
                    'product-tag',
                    'news-tag',
                ]
            ]
        ];

        $this->guzzle->shouldReceive('request')
            ->with('POST', $options)->times(1)
            ->andReturn($this->response);

        $this->service->purgeHosts(['product-tag', 'news-tag']);
    }
}
