<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getEnabledSortedBySurnameQB(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.surname', 'ASC')
            ->addOrderBy('p.name', 'ASC');
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
