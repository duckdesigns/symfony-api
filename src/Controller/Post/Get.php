<?php
declare(strict_types = 1);

namespace App\Controller\Post;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PostRepository;
use App\Repository\EventRepository;
use App\Entity;

class Get extends BaseController
{

    public function fetchAllByEvent(EventRepository $repository, string $eventId): JsonResponse
    {
        try
        {
            $event = $this->entityManager->find(Entity\Event::class, $eventId);
            
            if ($event === null)
            {
                return new JsonResponse(['errors' => ['The specified event does not exist']],
                                        JsonResponse::HTTP_BAD_REQUEST);
            }
            
            return new JsonResponse($repository->findBy(['event' => $eventId]));
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['errors' => ['The server encountered an error, please try again later']],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetch(PostRepository $repository, string $postId): JsonResponse
    {
        try
        {
            $post = $repository->find($postId);
            if ($post !== null)
            {
                return new JsonResponse($post);
            }
            
            return new JsonResponse(['errors' => ['The specified post could not be found']],
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
