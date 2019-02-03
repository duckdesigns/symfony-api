<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use App\Tests\Util;

class LocationApiTest extends WebTestCase
{
    use Util\Database;

    /** @var Client */
    private $client;

    public function setup()
    {
        $this->client = self::createClient();
    }

    public function tearDown()
    {
        $this->truncateDatabaseTables();
    }

    public function testCreate()
    {
        $locationInput = ['title' => 'Berghain', 'latitude' => '41.40338', 'longitude' => '2.17403'];
        $this->client->request('POST', '/locations', [], [], [], json_encode($locationInput));
        
        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $locationLocation = $this->client->getResponse()->headers->get('Location');
        
        $this->client->request('GET', $locationLocation);
        $fetchedLocation = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertSame($locationInput['title'], $fetchedLocation['title']);
        $this->assertSame($locationInput['latitude'], $fetchedLocation['latitude']);
        $this->assertSame($locationInput['longitude'], $fetchedLocation['longitude']);
    }
}