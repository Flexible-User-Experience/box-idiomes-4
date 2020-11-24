<?php

namespace App\Repository;

use App\Entity\BankCreditorSepa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class BankCreditorSepaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankCreditorSepa::class);
    }

    public function getEnabledSortedByNameQB(): QueryBuilder
    {
        return $this->getAllSortedByNameQB()
            ->where('c.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    public function getEnabledSortedByNameQ(): Query
    {
        return $this->getEnabledSortedByNameQB()->getQuery();
    }

    public function getEnabledSortedByName(): array
    {
        return $this->getEnabledSortedByNameQ()->getResult();
    }

    public function getAllSortedByNameQB(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');
    }

    public function getAllSortedByNameQ(): Query
    {
        return $this->getAllSortedByNameQB()->getQuery();
    }

    public function getAllSortedByName(): array
    {
        return $this->getAllSortedByNameQ()->getResult();
    }
}
