<?php

namespace Suitmedia\Cloudflare\Tests;

use GuzzleHttp\Client;
use Suitmedia\Cloudflare\CloudflareService;

class CloudflareServiceTests extends TestCase
{
    /**
     * Cloudflare service object.
     *
     * @var \Suitmedia\Cloudflare\CloudflareService
     */
    protected $service;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(CloudflareService::class);
    }

    /** @test */
    public function it_returns_configuration_values_correctly()
    {
        $this->assertEquals('test.com', $this->service->getConfig('sitename'));
        $this->assertEquals('example@domain.com', $this->service->getConfig('auth_email'));
        $this->assertEquals('test_auth_key', $this->service->getConfig('auth_key'));
    }

    /** @test */
    public function it_returns_guzzle_client_object_as_expected()
    {
        $guzzle = $this->service->getGuzzle();

        $this->assertInstanceOf(Client::class, $guzzle);
    }

    /** @test */
    public function it_can_set_sitename_values_at_runtime()
    {
        $this->service->setConfig('sitename', 'example.com');

        $this->assertEquals('example.com', $this->service->getConfig('sitename'));
    }

    /** @test */
    public function it_can_replace_the_guzzle_client_object_with_a_new_one()
    {
        $newGuzzle = new Client(['base_uri' => 'https://laravel.com/', 'timeout' => 10]);

        $this->service->setGuzzle($newGuzzle);

        $guzzle = $this->service->getGuzzle();
        $this->assertInstanceOf(Client::class, $guzzle);

        $this->assertEquals(10, $guzzle->getConfig('timeout'));
        $this->assertEquals('laravel.com', $guzzle->getConfig('base_uri')->getHost());
    }
}
