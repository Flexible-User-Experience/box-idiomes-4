<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class InvoiceRepository.
 *
 * @category Repository
 */
class InvoiceRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousInvoiceByStudentYearAndMonthOrNullQB(Student $student, $year, $month)
    {
        $qb = $this
            ->createQueryBuilder('i')
            ->where('i.student = :student')
            ->andWhere('i.year = :year')
            ->andWhere('i.month = :month')
            ->setParameter('student', $student)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setMaxResults(1)
        ;

        return $qb;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function findOnePreviousInvoiceByStudentYearAndMonthOrNullQ(Student $student, $year, $month)
    {
        return $this->findOnePreviousInvoiceByStudentYearAndMonthOrNullQB($student, $year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousInvoiceByStudentYearAndMonthOrNull(Student $student, $year, $month)
    {
        return $this->findOnePreviousInvoiceByStudentYearAndMonthOrNullQ($student, $year, $month)->getOneOrNullResult();
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousInvoiceByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
    {
        $qb = $this
            ->createQueryBuilder('i')
            ->where('i.student = :student')
            ->andWhere('i.year = :year')
            ->andWhere('i.month = :month')
            ->setParameter('student', $studentId)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setMaxResults(1)
        ;

        return $qb;
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function findOnePreviousInvoiceByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)
    {
        return $this->findOnePreviousInvoiceByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)->getQuery();
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousInvoiceByStudentIdYearAndMonthOrNull($studentId, $year, $month)
    {
        return $this->findOnePreviousInvoiceByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)->getOneOrNullResult();
    }

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMonthlyIncomingsAmountForDate(\DateTime $date)
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

        return is_null($query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)) ? 0 : floatval($query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR));
    }
}
