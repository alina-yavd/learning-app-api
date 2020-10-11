<?php

namespace App\Repository;

use App\Entity\UserLearning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLearning|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLearning|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLearning[]    findAll()
 * @method UserLearning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLearningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLearning::class);
    }
}
