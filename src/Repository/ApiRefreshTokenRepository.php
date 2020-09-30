<?php

namespace App\Repository;

use App\Entity\ApiRefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiRefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiRefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiRefreshToken[]    findAll()
 * @method ApiRefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiRefreshTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiRefreshToken::class);
    }
}
