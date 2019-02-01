<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Valueobject\Message;

class Put extends BaseController
{

    public function update(EventRepository $repository, Request $request, int $id): JsonResponse
    {
        $response = new JsonResponse();
        
        try
        {
            // ensure content is not empty and valid json
            // ensure all relevant values exist / are valid
            
            $putData = json_decode($request->getContent(), true);
            $event = $repository->find($id);
            $event->setTitle($putData['title']);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        catch (\Throwable $t)
        {
            $this->logger->error(Message\Log::createFromThrowable($throwable));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
