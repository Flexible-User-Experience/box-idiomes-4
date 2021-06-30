<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findAllEnabledSortedByPositionQB(): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.position', 'ASC');
    }

    public function findAllEnabledSortedByPositionQ(): Query
    {
        return $this->findAllEnabledSortedByPositionQB()->getQuery();
    }

    public function findAllEnabledSortedByPosition(): array
    {
        return $this->findAllEnabledSortedByPositionQ()->getResult();
    }
}
