<?php
declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class EventApiTest extends WebTestCase
{

    /** @var Client */
    private $client;

    public function setup()
    {
        $this->client = self::createClient();
    }

    public function testFetchAll()
    {
        $this->client->request('GET', '/events');
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
