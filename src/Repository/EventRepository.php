<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EventRepository extends ServiceEntityRepository
{

    /** @var ObjectManager */
    private $entityManager;

    public function __construct(RegistryInterface $registry, ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        parent::__construct($registry, Event::class);
    }

    public function removeById(int $id): bool
    {
        $event = $this->find($id);
        if ($event !== null)
        {
            $this->entityManager->remove($event);
            $this->entityManager->flush();
            
            return true;
        }
        
        return false;
    }
}