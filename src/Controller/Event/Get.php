<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EventRepository;
use App\Valueobject\Message;

class Get extends BaseController
{

    public function fetchAll(EventRepository $repository): JsonResponse
    {
        $response = new JsonResponse();
        
        try
        {
            $events = $repository->findAll();
            $response->setData($events);
        }
        catch (\Throwable $t)
        {
            $this->logger->error((string) Message\Log::createFromThrowable($t));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }

    public function fetch(EventRepository $repository, int $id): JsonResponse
    {
        $response = new JsonResponse();
        
        try
        {
            $event = $repository->find($id);
            if ($event !== null)
            {
                $response->setData($event);
            }
            else
            {
                $response->setData(Message\User::create404()->toErrorArray());
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }
        catch (\Throwable $t)
        {
            $this->logger->error((string) Message\Log::createFromThrowable($t));
            $response->setData(Message\User::create500()->toErrorArray());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
