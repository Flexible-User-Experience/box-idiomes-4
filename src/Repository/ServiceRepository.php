<?php

namespace App\Repository;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findAllEnabledSortedByPositionQB(): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.position', SortOrderTypeEnum::ASC);
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
