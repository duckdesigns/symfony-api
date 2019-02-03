<?php
declare(strict_types = 1);

namespace App\Controller\Post;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form;
use App\Entity;
use App\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Post extends BaseController
{

    public function create(Request $request, string $eventId): JsonResponse
    {
        try
        {
            $event = $this->entityManager->find(Entity\Event::class, $eventId);
            
            if ($event === null)
            {
                return new JsonResponse(['errors' => ['The specified $event does not exist']],
                                        JsonResponse::HTTP_BAD_REQUEST);
            }
            $postData = json_decode($request->getContent(), true);
            $form = $this->createForm(Form\Post::class, new Dto\Post());
            $form->submit($postData);
            
            if ($form->isValid() === false)
            {
                $errors = [];
                foreach ($form->getErrors(true) as $error)
                {
                    $errors[] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
            }
            
            $post = Entity\Post::createFromDto($form->getData());
            $post->setEvent($event);
            
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            
            return new JsonResponse(null,
                                    JsonResponse::HTTP_CREATED,
                                    ['Location' => '/events/' . $event->getId() . '/posts/' . $post->getId()]);
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['error' => 'The server encountered an error, please try again later'],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
