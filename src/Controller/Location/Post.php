<?php
declare(strict_types = 1);

namespace App\Controller\Location;

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

    public function create(Request $request): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
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
                
                return JsonResponse::createHttpBadRequest($errors);
            }
            
            $entity = Entity\Location::createFromDto($form->getData());
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            
            return JsonResponse::createHttpCreated('/locations/' . $entity->getId());
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
