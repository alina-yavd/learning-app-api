<?php

namespace App\Repository;

use App\Entity\WordTranslation;
use App\Exception\EntityNotFoundException;
use App\Service\WordFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WordTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordTranslation[]    findAll()
 * @method WordTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WordTranslation::class);
    }

    /*
    * @throws EntityNotFoundException
    */
    public function getById(int $id): WordTranslation
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $item = $query->getOneOrNullResult();

        if (null == $item) {
            throw EntityNotFoundException::byId('Word translation', $id);
        }

        return $item;
    }

    public function getList(?WordFilter $filter): ?array
    {
        $query = $this->createQueryBuilder('t');

        if (null !== $filter) {
            if ($filter->hasExcludeId()) {
                $query->andWhere($query->expr()->notIn('t.id', $filter->getExcludeId()));
            }

            if ($filter->hasLanguage()) {
                $query->andWhere('t.language = :language')
                    ->setParameter('language', $filter->getLanguage());
            }
        }

        $query = $query->getQuery();

        return $query->getResult();
    }
}
