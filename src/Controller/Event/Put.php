<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Valueobject\Header\ContentType;

class Put extends BaseController
{

    public function update(Request $request, int $id): Response
    {
        $response = new Response();
        $response->headers->add(ContentType::createJson()->toArray());
        
        try
        {
            $putData = json_decode($request->getContent(), true);
            $event = $this->repository->find($id);
            $event->setTitle($putData['title']);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        catch (\Throwable $t)
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
