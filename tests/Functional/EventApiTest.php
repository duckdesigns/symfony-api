<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

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

    /**
     *
     * @depends App\Tests\Functional\LocationApiTest::testCreate
     */
    public function testFetchAllByLocation()
    {
        $locationInput = ['title' => 'Berghain', 'latitude' => '41.40338', 'longitude' => '2.17403'];
        $this->client->request('POST', '/locations', [], [], [], json_encode($locationInput));
        $locationLocation = $this->client->getResponse()->headers->get('Location');
        
        $eventInput = ['title' => 'Kit Kat Club'];
        $this->client->request('POST', $locationLocation . '/events', [], [], [], json_encode($eventInput));
        $eventLocation = $this->client->getResponse()->headers->get('Location');
        
        $this->client->request('GET', $eventLocation);
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $fetchedEvent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($eventInput['title'], $fetchedEvent['title']);
    }

    public function testCreate()
    {
        $locationInput = ['title' => 'Berghain', 'latitude' => '41.40338', 'longitude' => '2.17403'];
        $this->client->request('POST', '/locations', [], [], [], json_encode($locationInput));
        $locationLocation = $this->client->getResponse()->headers->get('Location');
        
        $eventInput = ['title' => 'Kit Kat Club'];
        $this->client->request('POST', $locationLocation . '/events', [], [], [], json_encode($eventInput));
        
        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $eventLocation = $this->client->getResponse()->headers->get('Location');
        
        $this->client->request('GET', $eventLocation);
        $fetchedEvent = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertSame($eventInput['title'], $fetchedEvent['title']);
    }
}