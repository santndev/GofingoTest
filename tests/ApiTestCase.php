<?php

namespace App\Tests;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    private static $staticClient;
    /**
     * @var Client
     */
    protected Client $client;

    public static function setUpBeforeClass(): void
    {
        $baseUrl            = $_ENV['TEST_BASE_URL'];
        self::$staticClient = new Client(
            [
                'base_uri'    => $baseUrl,
                'http_errors' => false
            ]
        );
    }

    protected function setUp(): void
    {
        $this->client = self::$staticClient;
    }
}
