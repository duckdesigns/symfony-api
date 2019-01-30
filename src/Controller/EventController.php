<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;

class EventController extends AbstractController
{

    /** @var EventRepository */
    private $repository;

    /** @var ObjectManager */
    private $entityManager;

    public function __construct(EventRepository $repository, ObjectManager $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/events", name="list", methods={"GET"})
     */
    public function list(): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        try
        {
            $events = $this->repository->findAll();
            
            $response->setContent(json_encode($events));
            $response->setStatusCode(Response::HTTP_OK);
        }
        catch (\Throwable $t)
        {
            $response->setContent($this->jsonUtil->encode(['error' => $t->getMessage()]));
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }

    /**
     * @route("/events/{id}", name="fetch", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function fetch(int $id): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        
        $event = $repository->find($id);
        $response->setContent(json_encode($event));
        
        return $response;
    }

    /**
     * @route("/events", name="create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        try
        {
            $postData = json_decode($request->getContent(), true);
            $event = new Event($postData['title']);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->headers->set('Location', '/events/' . $event->getId());
        }
        catch (\Throwable $t)
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent(json_encode(['error' => $t->getMessage()]));
        }
        
        return $response;
    }

    /**
     * @route("/events/{id}", name="fetch", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        try
        {
            $putData = json_decode($request->getContent(), true);
            $event = $this->repository->find($id);
            $event->setTitle($putData['title']);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        catch (\Throwable $t)
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $response;
    }

    /**
     * @route("/events/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        
        $event = $this->repository->find($id);
        if ($event !== null)
        {
            $entityManager->remove($entity);
            $entityManager->flush();
        }
        
        return $response;
    }
}
