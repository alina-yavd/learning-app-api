<?php

namespace App\Repository;

use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\Service\WordGroupFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WordGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordGroup[]    findAll()
 * @method WordGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WordGroup::class);
    }

    /*
    * @throws EntityNotFoundException
    */
    public function getById(int $id): WordGroup
    {
        $query = $this->createQueryBuilder('g')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $item = $query->getOneOrNullResult();

        if (null == $item) {
            throw EntityNotFoundException::byId('Word group', $id);
        }

        return $item;
    }

    /*
    * @throws EntityNotFoundException
    */
    public function getByName(string $name): WordGroup
    {
        $query = $this->createQueryBuilder('g')
            ->where('g.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery();

        $item = $query->getOneOrNullResult();

        if (null == $item) {
            throw EntityNotFoundException::byName('Word group', $name);
        }

        return $item;
    }

    public function getByFilter(WordGroupFilter $filter): ?array
    {
        $query = $this->createQueryBuilder('g');

        if ($filter->hasIds()) {
            $query->andWhere($query->expr()->in('g.id', $filter->getIds()));
        }

        if ($filter->hasLanguage()) {
            $query->andWhere('g.language = :language')
                ->setParameter('language', $filter->getLanguage());
        }

        if ($filter->hasLanguage() && $filter->hasTranslation()) {
            $query->andWhere('g.translation = :translation')
                ->setParameter('translation', $filter->getTranslation());
        }

        $query = $query->getQuery();

        return $query->getResult();
    }

    public function create(WordGroup $group): void
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }
}
