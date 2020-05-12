<?php

namespace App\Repository;

use App\Entity\ReceiptLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

/**
 * Class ReceiptLineRepository.
 *
 * @category Repository
 */
class ReceiptLineRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceiptLine::class);
    }
}
