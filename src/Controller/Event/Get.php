<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Get extends BaseController
{

    public function fetchAll(): JsonResponse
    {
        $response = new JsonResponse();
        
        try
        {
            $events = $this->repository->findAll();
            $response->setData($events);
        }
        catch (\Throwable $t)
        {
            $this->logger->addError($t->getMessage());
            $response->setData(['error' => $t->getMessage()]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }

    public function fetch(int $id): JsonResponse
    {
        $response = new JsonResponse();
        
        try
        {
            $event = $this->repository->find($id);
            ($event !== null) ? $response->setData($event) : $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        catch (\Throwable $t)
        {
            $this->logger->addError($t->getMessage());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
