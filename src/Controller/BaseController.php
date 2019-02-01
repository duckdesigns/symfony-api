<?php
declare(strict_types = 1);

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class BaseController extends AbstractController
{
    /** @var ObjectManager */
    protected $entityManager;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(ObjectManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
}
