<?php

namespace App\Repository;

use App\Entity\Language;
use App\Exception\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Language|null find($id, $lockMode = null, $lockVersion = null)
 * @method Language|null findOneBy(array $criteria, array $orderBy = null)
 * @method Language[]    findAll()
 * @method Language[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    /*
    * @throws EntityNotFoundException
    */
    public function getById(int $id): Language
    {
        $query = $this->createQueryBuilder('l')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();

        $item = $query->getOneOrNullResult();

        if (null == $item) {
            throw EntityNotFoundException::byId('Language', $id);
        }

        return $item;
    }

    public function create(Language $language): void
    {
        $this->_em->persist($language);
        $this->_em->flush();
    }
}
