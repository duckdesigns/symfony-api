<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Valueobject\Header\Location;
use Symfony\Component\HttpFoundation\JsonResponse;

class Post extends BaseController
{

    public function create(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        
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
            $this->logger->addError($t->getMessage());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
