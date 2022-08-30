<?php

namespace App\Repository;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\ClassGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class ClassGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassGroup::class);
    }

    public function getEnabledSortedByCodeQB(): QueryBuilder
    {
        return $this->createQueryBuilder('cg')
            ->where('cg.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('cg.code', SortOrderTypeEnum::ASC);
    }

    public function getEnabledSortedByCodeQ(): Query
    {
        return $this->getEnabledSortedByCodeQB()->getQuery();
    }

    public function getEnabledSortedByCode(): array
    {
        return $this->getEnabledSortedByCodeQ()->getResult();
    }
}
