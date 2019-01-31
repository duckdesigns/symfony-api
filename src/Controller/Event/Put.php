<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class Put extends BaseController
{

    public function update(Request $request, int $id): JsonResponse
    {
        $response = new JsonResponse();
        
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
            $this->logger->addError($t->getMessage());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
