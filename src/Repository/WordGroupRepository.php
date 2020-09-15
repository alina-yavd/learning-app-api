<?php

namespace App\Repository;

use App\Entity\WordGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WordGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordGroup[]    findAll()
 * @method WordGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordGroupRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, WordGroup::class);
        $this->em = $entityManager;
    }

    public function create(WordGroup $group): void
    {
        $this->em->persist($group);
        $this->em->flush();
    }
}
