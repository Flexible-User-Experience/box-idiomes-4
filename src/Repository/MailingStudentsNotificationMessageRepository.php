<?php

namespace App\Repository;

use App\Entity\AbstractBase;
use App\Entity\MailingStudentsNotificationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class MailingStudentsNotificationMessageRepository extends ServiceEntityRepository
{
    private ParameterBagInterface $pb;

    public function __construct(ManagerRegistry $registry, ParameterBagInterface $pb)
    {
        parent::__construct($registry, MailingStudentsNotificationMessage::class);
        $this->pb = $pb;
    }

    public function getTodayAvailableNotificationsAmount(): int
    {
        $amount = 0;
        $today = new \DateTimeImmutable();
        $items = $this->createQueryBuilder('m')
            ->where('DATE(m.sendDate) = :today')
            ->setParameter('today', $today->format(AbstractBase::DATABASE_DATE_STRING_FORMAT))
            ->getQuery()
            ->getResult()
        ;
        /** @var MailingStudentsNotificationMessage $item */
        foreach ($items as $item) {
            $amount += $item->getTotalTargetStudents();
        }

        return $this->pb->get('mailing_per_day_limit') - $amount;
    }
}
