<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Valueobject\Header\ContentType;
use App\Valueobject\Header\Location;

class Post extends BaseController
{

    public function create(Request $request): Response
    {
        $response = new Response();
        $response->headers->add(ContentType::createJson()->toArray());
        
        try
        {
            $postData = json_decode($request->getContent(), true);
            $event = new Event($postData['title']);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->headers->add(Location::create('/events/' . $event->getId())->toArray());
        }
        catch (\Throwable $t)
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent(json_encode(['error' => $t->getMessage()]));
        }
        
        return $response;
    }
}
