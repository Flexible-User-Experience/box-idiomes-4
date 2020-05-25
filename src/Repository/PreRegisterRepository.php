<?php

namespace App\Repository;

use App\Entity\PreRegister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

/**
 * Class PreRegisterRepository.
 *
 * @category Repository
 */
class PreRegisterRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PreRegister::class);
    }
}
