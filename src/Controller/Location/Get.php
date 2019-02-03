<?php
declare(strict_types = 1);

namespace App\Controller\Location;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\LocationRepository;
use App\Entity;

class Get extends BaseController
{

    public function fetch(LocationRepository $repository, string $locationId): Response
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return createNotAcceptableResponse();
            }
            
            $location = $repository->find($locationId);
            if ($location !== null)
            {
                return new JsonResponse($location);
            }
            
            return new JsonResponse(['errors' => ['The specified location could not be found']],
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
