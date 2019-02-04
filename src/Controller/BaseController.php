<?php
declare(strict_types = 1);

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    protected function clientAcceptsJson(array $acceptedTypes): bool
    {
        if (count($acceptedTypes) === 0)
        {
            return true;
        }
        
        if (in_array('*/*', $acceptedTypes) || in_array('application/json', $acceptedTypes))
        {
            return true;
        }
        
        return false;
    }
}
