<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use App\Tests\Util;

class EventApiTest extends WebTestCase
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

    /**
     *
     * @depends App\Tests\Functional\LocationApiTest::testCreate
     */
    public function testCreate()
    {
        $locationLocation = $this->createLocation('Berghain', 52.51082, 13.44235);
        $eventTitle = 'Kit Kat Club';
        $eventLocation = $this->createEventAt($eventTitle, $locationLocation);
        
        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $eventLocation = $this->client->getResponse()->headers->get('Location');
        
        $this->client->request('GET', $eventLocation);
        $fetchedEvent = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertSame($eventTitle, $fetchedEvent['title']);
    }

    /**
     *
     * @depends App\Tests\Functional\LocationApiTest::testCreate
     */
    public function testFetchAllByLocation()
    {
        $locationLocation = $this->createLocation('Berghain', 52.51082, 13.44235);
        $eventTitle = 'Kit Kat Club';
        $eventLocation = $this->createEventAt($eventTitle, $locationLocation);
        
        $this->client->request('GET', $eventLocation);
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $fetchedEvent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($eventTitle, $fetchedEvent['title']);
    }

    public function testFetchAllByMaxDistanceFromGeolocation()
    {
        // should truncate first;
        $berghainLocation = $this->createLocation('Berghain Berlin', 52.51082, 13.44235);
        $kvuLocation = $this->createLocation('KVU Berlin', 52.53579, 13.45193);
        
        $kitKatClubEventTitle = 'Kit Kat Club';
        $konnyKleinkunstpunkTitle = 'Konny Kleinkunstpunk';
        $this->createEventAt($kitKatClubEventTitle, $berghainLocation);
        $this->createEventAt($konnyKleinkunstpunkTitle, $kvuLocation);
        
        $this->client->request('GET', '/events?max-distance-from=52.51082,13.44235,1');
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $fetchedEvents = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(1, $fetchedEvents);
        
        $kitKatClubEvent = array_filter($fetchedEvents,
                                        function (array $event) use ($kitKatClubEventTitle)
                                        {
                                            return $event['title'] === $kitKatClubEventTitle;
                                        });
        $this->assertSame($kitKatClubEventTitle, reset($kitKatClubEvent)['title']);
        
        $this->client->request('GET', '/events?max-distance-from=52.51082,13.44235,5');
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $fetchedEvents = json_decode($this->client->getResponse()->getContent(), true);
        
        $kitKatClubEvent = array_filter($fetchedEvents,
                                        function (array $event) use ($kitKatClubEventTitle)
                                        {
                                            return $event['title'] === $kitKatClubEventTitle;
                                        });
        $konnyKleinkunstpunkEvent = array_filter($fetchedEvents,
                                                function (array $event) use ($konnyKleinkunstpunkTitle)
                                                {
                                                    return $event['title'] === $konnyKleinkunstpunkTitle;
                                                });
        $this->assertSame($konnyKleinkunstpunkTitle, reset($konnyKleinkunstpunkEvent)['title']);
        $this->assertSame($kitKatClubEventTitle, reset($kitKatClubEvent)['title']);
    }

    // need to test the unhappy path :-)
    private function createLocation(string $title, float $latitude, float $longitude): string
    {
        $locationInput = ['title' => $title, 'latitude' => $latitude, 'longitude' => $longitude];
        $this->client->request('POST', '/locations', [], [], [], json_encode($locationInput));
        
        return $this->client->getResponse()->headers->get('Location');
    }

    private function createEventAt(string $title, string $locationLocation): string
    {
        $eventInput = ['title' => $title];
        $this->client->request('POST', $locationLocation . '/events', [], [], [], json_encode($eventInput));
        
        return $this->client->getResponse()->headers->get('Location');
    }
}