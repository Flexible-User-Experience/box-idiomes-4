<?php

namespace App\Repository;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getEnabledSortedBySurnameQB(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.surname', SortOrderTypeEnum::ASC)
            ->addOrderBy('p.name', SortOrderTypeEnum::ASC);
    }

    public function getEnabledSortedBySurnameQ(): Query
    {
        return $this->getEnabledSortedBySurnameQB()->getQuery();
    }

    public function getEnabledSortedBySurname(): array
    {
        return $this->getEnabledSortedBySurnameQ()->getResult();
    }
}
