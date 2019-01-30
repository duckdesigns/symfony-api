<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Controller\Event\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class Delete extends BaseController
{

    /**
     * @Route("/events/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        
        $event = $this->repository->find($id);
        if ($event !== null)
        {
            $this->entityManager->remove($event);
            $this->entityManager->flush();
        }
        
        return $response;
    }
}
