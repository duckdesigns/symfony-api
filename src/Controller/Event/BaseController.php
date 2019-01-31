<?php
declare(strict_types = 1);

namespace App\Controller\Event;

use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class BaseController extends AbstractController
{

    /** @var EventRepository */
    protected $repository;

    /** @var ObjectManager */
    protected $entityManager;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(EventRepository $repository, ObjectManager $entityManager, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
}
