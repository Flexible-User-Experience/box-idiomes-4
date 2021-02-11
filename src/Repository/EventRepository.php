<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Student;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class EventRepository.
 *
 * @category Repository
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->createQueryBuilder('e')
            ->where('e.begin BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
    }

    /**
     * @return Query
     */
    public function getFilteredByBeginAndEndQ(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    /**
     * @return array
     */
    public function getFilteredByBeginAndEnd(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)
            ->andWhere('e.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    /**
     * @return Query
     */
    public function getEnabledFilteredByBeginAndEndQ(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledFilteredByBeginAndEnd(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getEnabledFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledFilteredByBeginEndAndStudentQB(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)
            ->join('e.students', 's')
            ->andWhere('s.id = :sid')
            ->setParameter('sid', $student->getId())
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledFilteredByBeginEndAndStudentQ(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQB($startDate, $endDate, $student)->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledFilteredByBeginEndAndStudent(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQ($startDate, $endDate, $student)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getPrivateLessonsByStudentYearAndMonthQB(Student $student, $year, $month)
    {
        return $this->createQueryBuilder('e')
            ->join('e.students', 's')
            ->join('e.group', 'cg')
            ->where('YEAR(e.begin) = :year')
            ->andWhere('MONTH(e.begin) = :month')
            ->andWhere('s.id = :sid')
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->andWhere('e.enabled = :enabled')
            ->setParameter('sid', $student->getId())
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('isForPrivateLessons', true)
            ->setParameter('enabled', true);
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getPrivateLessonsByStudentYearAndMonthQ(Student $student, $year, $month)
    {
        return $this->getPrivateLessonsByStudentYearAndMonthQB($student, $year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getPrivateLessonsByStudentYearAndMonth(Student $student, $year, $month)
    {
        return $this->getPrivateLessonsByStudentYearAndMonthQ($student, $year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return float
     */
    public function getPrivateLessonsAmountByStudentYearAndMonth(Student $student, $year, $month)
    {
        return floatval(count($this->getPrivateLessonsByStudentYearAndMonth($student, $year, $month)));
    }
}
