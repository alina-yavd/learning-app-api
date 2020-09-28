<?php

namespace App\Repository;

use App\Entity\Word;
use App\Exception\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    /*
     * @throws EntityNotFoundException
     */
    public function getById($id): Word
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $item = $query->getOneOrNullResult();

        if (null == $item) {
            throw EntityNotFoundException::byId('Word', $id);
        }

        return $item;
    }

    public function findOneRandom(): Word
    {
        $wordIds = $this
            ->createQueryBuilder('w')
            ->select('w.id')
            ->getQuery()
            ->getResult();

        if (empty($wordIds)) {
            throw EntityNotFoundException::general('Word');
        }

        $randomKey = array_rand($wordIds);

        return $this->find($wordIds[$randomKey]['id']);
    }
}
