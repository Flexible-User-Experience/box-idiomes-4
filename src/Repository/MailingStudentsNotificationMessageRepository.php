<?php

namespace App\Repository;

use App\Entity\MailingStudentsNotificationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class MailingStudentsNotificationMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailingStudentsNotificationMessage::class);
    }
}
