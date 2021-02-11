<?php

namespace App\Repository;

use App\Entity\ClassGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ClassGroupRepository.
 *
 * @category Repository
 */
class ClassGroupRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClassGroup::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByCodeQB()
    {
        return $this->createQueryBuilder('cg')
            ->where('cg.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('cg.code', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedByCodeQ()
    {
        return $this->getEnabledSortedByCodeQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedByCode()
    {
        return $this->getEnabledSortedByCodeQ()->getResult();
    }
}
