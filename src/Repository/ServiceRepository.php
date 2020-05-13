<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class ServiceRepository.
 *
 * @category Repository
 */
class ServiceRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @return QueryBuilder
     */
    public function findAllEnabledSortedByPositionQB()
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.position', 'ASC');
    }

    /**
     * @return Query
     */
    public function findAllEnabledSortedByPositionQ()
    {
        return $this->findAllEnabledSortedByPositionQB()->getQuery();
    }

    /**
     * @return array
     */
    public function findAllEnabledSortedByPosition()
    {
        return $this->findAllEnabledSortedByPositionQ()->getResult();
    }
}
