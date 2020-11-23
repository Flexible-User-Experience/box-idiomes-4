<?php

namespace App\Repository;

use App\Entity\BankCreditorSepa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

/**
 * Class BankCreditorSepa.
 *
 * @category Repository
 */
class BankCreditorSepaRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankCreditorSepa::class);
    }
}
