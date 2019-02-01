<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EventRepository;
use App\Valueobject\Message;

class Delete extends BaseController
{

    public function delete(EventRepository $repository, int $id): JsonResponse
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        
        try
        {
            if ($repository->removeById($id) === false)
            {
                $this->logger->warning("Event with Id $id could not be deleted");
            }
        }
        catch (\Throwable $t)
        {
            $this->logger->error((string) Message\Log::createFromThrowable($t));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
