<?php

namespace App\Repository;

use App\Entity\Spending;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\AbstractQuery;

class SpendingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Spending::class);
    }

    public function getMonthlyExpensesAmountForDate(DateTimeInterface $date): int
    {
        $begin = clone $date;
        $end = clone $date;
        $begin->modify('first day of this month');
        $end->modify('last day of this month');
        $query = $this->createQueryBuilder('i')
            ->select('SUM(i.baseAmount) as amount')
            ->where('i.date >= :begin')
            ->andWhere('i.date <= :end')
            ->setParameter('begin', $begin->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'))
            ->getQuery()
        ;

        return is_null($query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)) ? 0 : (int) $query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }
}
