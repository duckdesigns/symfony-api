<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use App\Entity;
use Symfony\Component\Routing\Annotation\Route;
use App\Form;
use App\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Post extends BaseController
{

    public function create(Request $request, string $id): JsonResponse
    {
        try
        {
            $location = $this->entityManager->find(Entity\Location::class, $id);
            
            if ($location === null)
            {
                return new JsonResponse(['errors' => ['The specified location does not exist']],
                                        JsonResponse::HTTP_BAD_REQUEST);
            }
            
            $postData = json_decode($request->getContent(), true);
            $form = $this->createForm(Form\Event::class, new Dto\Event());
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
            
            $event = Entity\Event::createFromDto($form->getData());
            $event->setLocation($location);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            return new JsonResponse(null,
                                    JsonResponse::HTTP_CREATED,
                                    ['Location' => '/events/' . $event->getId()]);
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['error' => 'The server encountered an error, please try again later'],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
