<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Collection;

class EventRepository extends ServiceEntityRepository
{

    /** @var ObjectManager */
    private $entityManager;

    public function __construct(RegistryInterface $registry, ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        parent::__construct($registry, Event::class);
    }

    public function findByDistanceFromGeolocation(float $latitude, float $longitude, int $maxDistance): Collection
    {
        $sql = 'SELECT id, (6371*acos(cos(radians(:originLatitude))*cos(radians(latitude))*cos(radians(longitude)-radians(:originLongitude))+sin(radians(:originLatitude))* sin( radians(latitude))))
                AS distance
                FROM location
                HAVING distance < :maxDistance
                ORDER BY distance;';
        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->bindValue('originLatitude', $latitude);
        $statement->bindValue('originLongitude', $longitude);
        $statement->bindValue('maxDistance', $maxDistance);
        $statement->execute();
        
        return $statement->fetchAll();
    }
}