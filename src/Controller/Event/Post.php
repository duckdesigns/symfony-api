<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\BaseController;
use App\Entity;
use App\Form;
use App\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Library\Http\JsonResponse;
use App\Library\Http\ResponseInterface;

class Post extends BaseController
{

    public function create(Request $request, string $id): ResponseInterface
    {
        try
        {
            if ($this->clientAcceptsJson($request->getAcceptableContentTypes()) === false)
            {
                return Response::createHttpNotAcceptable();
            }
            
            $location = $this->entityManager->find(Entity\Location::class, $id);
            
            if ($location === null)
            {
                return JsonResponse::createHttpBadRequest(['The specified location does not exist']);
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
                
                return JsonResponse::createHttpBadRequest($errors);
            }
            
            $event = Entity\Event::createFromDto($form->getData());
            $event->setLocation($location);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            return JsonResponse::createHttpCreated('/events/' . $event->getId());
        }
        catch (\Throwable $t)
        {
            $this->logger->error($t->getMessage());
            
            return JsonResponse::createHttpInternalServerError();
        }
    }
}
