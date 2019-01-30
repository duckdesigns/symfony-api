<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{

    /** @var EventRepository */
    protected $repository;

    /** @var ObjectManager */
    protected $entityManager;

    public function __construct(EventRepository $repository, ObjectManager $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
