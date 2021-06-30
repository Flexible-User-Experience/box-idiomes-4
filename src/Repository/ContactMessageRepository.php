<?php

namespace App\Repository;

use App\Entity\ContactMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

class ContactMessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactMessage::class);
    }

    public function getNotReadMessagesAmount(): int
    {
        $qb = $this->createQueryBuilder('cm')
            ->where('cm.checked = :checked')
            ->setParameter('checked', false);

        return count($qb->getQuery()->getArrayResult());
    }
}
