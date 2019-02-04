<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use App\Library\Http\Response;
use App\Library\Http\ResponseInterface;
use App\Repository\EventRepository;
use App\Entity;
use App\Library\Http\JsonResponse;

class Get extends BaseController
{

    public function fetchAllByLocation(EventRepository $repository, string $locationId, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $location = $this->entityManager->find(Entity\Location::class, $locationId);
            
            if ($location === null)
            {
                return JsonResponse::createHttpBadRequest(['The specified location does not exist']);
            }
            
            return JsonResponse::createHal($repository->findBy(['location' => $locationId]));
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }

    public function fetchAll(EventRepository $repository, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $getParams = $request->query->all();
            if (isset($getParams['max-distance-from']))
            {
                $maxDistanceFromParams = explode(',', $getParams['max-distance-from']);
                $events = $repository->findByDistanceFromGeolocation((float) $maxDistanceFromParams[0],
                                                                    (float) $maxDistanceFromParams[1],
                                                                    (int) $maxDistanceFromParams[2]);
                return JsonResponse::createHal($events->toArray());
            }
            
            return JsonResponse::createHal($repository->findAll());
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }

    public function fetch(EventRepository $repository, string $eventId, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $event = $repository->find($eventId);
            if ($event !== null)
            {
                return JsonResponse::createHal($event);
            }
            
            return JsonResponse::createHttpNotFound(['The specified event could not be found']);
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
