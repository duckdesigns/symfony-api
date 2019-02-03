<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostRepository extends ServiceEntityRepository
{

    /** @var ObjectManager */
    private $entityManager;

    public function __construct(RegistryInterface $registry, ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        parent::__construct($registry, Post::class);
    }
}