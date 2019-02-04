<?php
declare(strict_types = 1);

namespace App\Controller\Post;

use App\Controller\BaseController;
use App\Form;
use App\Entity;
use App\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Library\Http\JsonResponse;
use App\Library\Http\ResponseInterface;

class Post extends BaseController
{

    public function create(Request $request, string $eventId): ResponseInterface
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
                return JsonResponse::createHttpBadRequest(['The specified $event does not exist']);
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
                
                return JsonResponse::createHttpBadRequest($errors);
            }
            
            $post = Entity\Post::createFromDto($form->getData());
            $post->setEvent($event);
            
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            
            return JsonResponse::createHttpCreated('/events/' . $event->getId() . '/posts/' . $post->getId());
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
