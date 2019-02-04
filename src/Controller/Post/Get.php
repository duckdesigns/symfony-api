<?php
declare(strict_types = 1);

namespace App\Controller\Post;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Library\Http\JsonResponse;
use App\Library\Http\ResponseInterface;
use App\Repository\PostRepository;
use App\Repository\EventRepository;
use App\Entity;

class Get extends BaseController
{

    public function fetchAllByEvent(EventRepository $repository, string $eventId, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $event = $this->entityManager->find(Entity\Event::class, $eventId);
            
            if ($event === null)
            {
                return JsonResponse::createHttpBadRequest(['The specified event does not exist']);
            }
            
            return JsonResponse::createHal($repository->findBy(['event' => $eventId]));
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }

    public function fetch(PostRepository $repository, string $postId, Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $post = $repository->find($postId);
            if ($post !== null)
            {
                return JsonResponse::createHal($post);
            }
            
            return JsonResponse::createHttpNotFound('The specified post could not be found');
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
