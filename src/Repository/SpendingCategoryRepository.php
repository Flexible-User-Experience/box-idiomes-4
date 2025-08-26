<?php

namespace App\Repository;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\SpendingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class SpendingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpendingCategory::class);
    }

    public function getEnabledSortedByNameQB(): QueryBuilder
    {
        return $this->createQueryBuilder('sc')
            ->where('sc.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('sc.name', SortOrderTypeEnum::ASC)
        ;
    }

    public function getEnabledSortedByNameQ(): Query
    {
        return $this->getEnabledSortedByNameQB()->getQuery();
    }

    public function getEnabledSortedByName(): array
    {
        return $this->getEnabledSortedByNameQ()->getResult();
    }
}
