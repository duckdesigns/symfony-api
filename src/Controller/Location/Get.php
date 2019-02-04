<?php
declare(strict_types = 1);

namespace App\Controller\Location;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Library\Http\JsonResponse;
use App\Library\Http\ResponseInterface;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Request;

class Get extends BaseController
{

    public function fetch(LocationRepository $repository, string $locationId, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $location = $repository->find($locationId);
            if ($location !== null)
            {
                return JsonResponse::createHal($location);
            }
            
            return JsonResponse::createHttpNotFound('The specified location could not be found');
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
