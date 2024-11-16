<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findWithUser(int $id): ?Post
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, u
            FROM App\Entity\Post p
            JOIN p.user u
            WHERE p.id = :id'
        )->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }
}