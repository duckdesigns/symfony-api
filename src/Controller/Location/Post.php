<?php
declare(strict_types = 1);

namespace App\Controller\Location;

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

    public function create(Request $request): JsonResponse
    {
        try
        {
            $postData = json_decode($request->getContent(), true);
            $form = $this->createForm(Form\Location::class, new Dto\Location());
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
            
            $entity = Entity\Location::createFromDto($form->getData());
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            
            return new JsonResponse(null, JsonResponse::HTTP_CREATED, ['Location' => '/locations/' . $entity->getId()]);
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return new JsonResponse(['error' => 'The server encountered an error, please try again later'],
                                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
