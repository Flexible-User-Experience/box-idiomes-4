<?php

namespace App\Repository;

use App\Entity\ContactMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

/**
 * Class ContactMessageRepository.
 *
 * @category Repository
 */
class ContactMessageRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactMessage::class);
    }

    /**
     * @return int
     */
    public function getNotReadMessagesAmount()
    {
        $qb = $this->createQueryBuilder('cm')
            ->where('cm.checked = :checked')
            ->setParameter('checked', false);

        return count($qb->getQuery()->getArrayResult());
    }
}
