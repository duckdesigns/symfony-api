<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Delete extends BaseController
{

    public function delete(int $id): JsonResponse
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        
        try
        {
            if ($this->repository->removeById($id) === false)
            {
                $this->logger->info("Event with Id $id could not be deleted");
            }
        }
        catch (\Throwable $t)
        {
            $this->logger->addError($t->getMessage());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }
}
