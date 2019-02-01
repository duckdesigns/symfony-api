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

    public function testFetchReturns200()
    {
        $postData = '{"title":"banane"}';
        $this->client->request('POST', '/events', [], [], [], $postData);
        $location = $this->client->getResponse()->headers->get('Location');
        $this->client->request('GET', $location);
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
    
    public function testFetchReturns404()
    {
        $path = '/events/999';
        $this->client->request('DELETE', $path);
        $this->client->request('GET', $path);
        
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateReturns201()
    {
        $this->client->request('POST', '/events', [], [], [], '{"title":"banane"}');
        
        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
    }
    
    public function testCreateReturns500OnBadData()
    {
        $this->client->request('POST', '/events', [], [], [], '{}');
        
        $this->assertSame(500, $this->client->getResponse()->getStatusCode());
    }
    
    public function testUpdate()
    {
        $postData = '{"title":"banane"}';
        $this->client->request('POST', '/events', [], [], [], $postData);
        $location = $this->client->getResponse()->headers->get('Location');
        $this->client->request('PUT', $location, [], [], [], '{"title":"banane"}');
        
        $this->assertSame(204, $this->client->getResponse()->getStatusCode());
    }
    
    public function testDelete()
    {
        $this->client->request('DELETE', '/events/3');
        
        $this->assertSame(204, $this->client->getResponse()->getStatusCode());
    }
}
