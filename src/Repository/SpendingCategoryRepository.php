<?php

namespace App\Repository;

use App\Entity\SpendingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SpendingCategoryRepository.
 *
 * @category Repository
 */
class SpendingCategoryRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpendingCategory::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->createQueryBuilder('sc')
            ->where('sc.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('sc.name', 'ASC')
            ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedByNameQ()
    {
        return $this->getEnabledSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedByName()
    {
        return $this->getEnabledSortedByNameQ()->getResult();
    }
}
