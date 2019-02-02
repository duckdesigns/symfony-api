<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EventRepository;
use App\Entity;

class Get extends BaseController
{

    public function fetchAllByLocation(EventRepository $repository, string $locationId): JsonResponse
    {
        try
        {
            $location = $this->entityManager->find(Entity\Location::class, $locationId);
            
            if ($location === null)
            {
                return new JsonResponse(['errors' => ['The specified location does not exist']],
                                        JsonResponse::HTTP_BAD_REQUEST);
            }
            
            return new JsonResponse($repository->findBy(['location' => $locationId]));
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['errors' => ['The server encountered an error, please try again later']],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetch(EventRepository $repository, string $eventId): JsonResponse
    {
        try
        {
            $event = $repository->find($eventId);
            if ($event !== null)
            {
                return new JsonResponse($event);
            }
            
            return new JsonResponse(['errors' => ['The specified event could not be found']],
                                    JsonResponse::HTTP_NOT_FOUND);
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            return new JsonResponse(['errors' => ['The server encountered an error, please try again later']],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
