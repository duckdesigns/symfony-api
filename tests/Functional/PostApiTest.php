<?php
declare(strict_types = 1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class PostApiTest extends WebTestCase
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
     * @depends App\Tests\Functional\EventApiTest::testCreate
     */
    public function testFetchAllByEvent()
    {
        $locationInput = ['title' => 'Berghain', 'latitude' => '41.40338', 'longitude' => '2.17403'];
        $this->client->request('POST', '/locations', [], [], [], json_encode($locationInput));
        $locationLocation = $this->client->getResponse()->headers->get('Location');
        
        $eventInput = ['title' => 'Kit Kat Club'];
        $this->client->request('POST', $locationLocation . '/events', [], [], [], json_encode($eventInput));
        $eventLocation = $this->client->getResponse()->headers->get('Location');
        
        $postInput = [
                        'title' => 'Fetter Schuppen hier',
                        'content' => 'Hab mir die ganze Nacht die Eier abgetanzt. Drogen waren toll!'];
        $this->client->request('POST', $eventLocation . '/posts', [], [], [], json_encode($postInput));
        $postLocation = $this->client->getResponse()->headers->get('Location');
        
        $this->client->request('GET', $postLocation);
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $fetchedPost = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame($postInput['title'], $fetchedPost['title']);
        $this->assertSame($postInput['content'], $fetchedPost['content']);
    }
}