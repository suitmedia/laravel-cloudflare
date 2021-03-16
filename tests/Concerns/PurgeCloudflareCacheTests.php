<?php

namespace Suitmedia\Cloudflare\Tests\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
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
        $this->mock = new MockHandler([
            new Response(200, [], json_encode(['result' => [['id' => 'test']]]))
        ]);
        
        $stack = HandlerStack::create($this->mock);
        $this->guzzle = new Client([
            'handler'  => $stack,
            'base_uri' => CloudflareService::BASE_URI,
            'headers'  => [
                'X-Auth-Key'   => $this->app['config']['laravel-cloudflare.auth_key'],
                'X-Auth-Email' => $this->app['config']['laravel-cloudflare.auth_email'],
            ],
        ]);
        $this->service = new CloudflareService($this->guzzle);
    }

    /** @test */
    public function it_can_send_purge_cache_request_to_purge_the_entire_cache()
    {
        $options = ['body' => ['purge_everything' => true]];
        
        $this->mock->append(new Response(200, [], json_encode(['result' => [['id' => 'test']]])));

        $statusCode = $this->service->purgeAll();
        $this->assertEquals(200, $statusCode);
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
        $this->mock->append(new Response(200, [], json_encode(['result' => [['id' => 'test']]])));

        $statusCode = $this->service->purgeByUrls([
            'http://localhost:8000/products',
            'http://localhost:8000/products/product-1',
        ]);
        $this->assertEquals(200, $statusCode);
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
        $this->mock->append(new Response(200, [], json_encode(['result' => [['id' => 'test']]])));
        $statusCode = $this->service->purgeByPrefixes(['/products', '/news']);
        $this->assertEquals(200, $statusCode);
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
        $this->mock->append(new Response(200, [], json_encode(['result' => [['id' => 'test']]])));
        $statusCode = $this->service->purgeByHosts([
            'http://localhost:8000',
            'http://example.com'
        ]);
        $this->assertEquals(200, $statusCode);
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
        $this->mock->append(new Response(200, [], json_encode(['result' => [['id' => 'test']]])));
        $statusCode = $this->service->purgeByTags(['product-tag', 'news-tag']);
        $this->assertEquals(200, $statusCode);
    }
}
