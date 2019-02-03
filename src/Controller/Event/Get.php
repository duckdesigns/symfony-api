<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EventRepository;
use App\Entity;

class Get extends BaseController
{

    public function fetchAllByLocation(EventRepository $repository, string $locationId, Request $request): Response
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return createNotAcceptableResponse();
            }
            
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

    public function fetchAll(EventRepository $repository, Request $request): Response
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return createNotAcceptableResponse();
            }
            
            $getParams = $request->query->all();
            if (isset($getParams['max-distance-from']))
            {
                $maxDistanceFromParams = explode(',', $getParams['max-distance-from']);
                $events = $repository->findByDistanceFromGeolocation((float) $maxDistanceFromParams[0],
                                                                    (float) $maxDistanceFromParams[1],
                                                                    (int) $maxDistanceFromParams[2]);
                return new JsonResponse($events->toArray());
            }
            
            return new JsonResponse($repository->findAll());
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['errors' => ['The server encountered an error, please try again later']],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetch(EventRepository $repository, string $eventId): Response
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return createNotAcceptableResponse();
            }
            
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
