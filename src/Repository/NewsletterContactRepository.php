<?php

namespace App\Repository;

use App\Entity\NewsletterContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

/**
 * Class NewsletterContactRepository.
 *
 * @category Repository
 */
class NewsletterContactRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NewsletterContact::class);
    }
}
