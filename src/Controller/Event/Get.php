<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Valueobject\Header\ContentType;

class Get extends BaseController
{

    /**
     * @Route("/events", name="fetchAll", methods={"GET"})
     */
    public function fetchAll(): Response
    {
        $response = new Response();
        $response->headers->add(ContentType::createJson()->toArray());
        
        try
        {
            $events = $this->repository->findAll();
            
            $response->setContent(json_encode($events));
            $response->setStatusCode(Response::HTTP_OK);
        }
        catch (\Throwable $t)
        {
            $response->setContent(json_encode(['error' => $t->getMessage()]));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }

    /**
     * @Route("/events/{id}", name="fetch", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function fetch(int $id): Response
    {
        $response = new Response();
        $response->headers->add(ContentType::createJson()->toArray());
        
        try
        {
            $event = $this->repository->find($id);
            if ($event !== null)
            {
                $response->setContent(json_encode($event));
                $response->setStatusCode(Response::HTTP_OK);
            }
            else
            {
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }
        catch (\Throwable $t)
        {
            $response->setContent(json_encode(['error' => $t->getMessage()]));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
