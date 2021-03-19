<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Student;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate): QueryBuilder
    {
        return $this->createQueryBuilder('e')
            ->where('e.begin BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
    }

    public function getFilteredByBeginAndEndQ(DateTimeInterface $startDate, DateTimeInterface $endDate): Query
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    public function getFilteredByBeginAndEnd(DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        return $this->getFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    public function getEnabledFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate): QueryBuilder
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)
            ->andWhere('e.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    public function getEnabledFilteredByBeginAndEndQ(DateTimeInterface $startDate, DateTimeInterface $endDate): Query
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    public function getEnabledFilteredByBeginAndEnd(DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        return $this->getEnabledFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    public function getEnabledFilteredByBeginEndAndStudentQB(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student): QueryBuilder
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)
            ->join('e.students', 's')
            ->andWhere('s.id = :sid')
            ->setParameter('sid', $student->getId())
        ;
    }

    public function getEnabledFilteredByBeginEndAndStudentQ(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student): Query
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQB($startDate, $endDate, $student)->getQuery();
    }

    public function getEnabledFilteredByBeginEndAndStudent(DateTimeInterface $startDate, DateTimeInterface $endDate, Student $student): array
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQ($startDate, $endDate, $student)->getResult();
    }

    public function getPrivateLessonsByStudentYearAndMonthQB(Student $student, $year, $month): QueryBuilder
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

    public function getPrivateLessonsByStudentYearAndMonthQ(Student $student, $year, $month): Query
    {
        return $this->getPrivateLessonsByStudentYearAndMonthQB($student, $year, $month)->getQuery();
    }

    public function getPrivateLessonsByStudentYearAndMonth(Student $student, $year, $month): array
    {
        return $this->getPrivateLessonsByStudentYearAndMonthQ($student, $year, $month)->getResult();
    }

    public function getPrivateLessonsAmountByStudentYearAndMonth(Student $student, $year, $month): int
    {
        return count($this->getPrivateLessonsByStudentYearAndMonth($student, $year, $month));
    }

    public function getEnabledFilteredByDateQB(DateTimeInterface $date): QueryBuilder
    {
        return $this->createQueryBuilder('e')
            ->where('DATE(e.begin) = :searchedDate')
            ->andWhere('e.enabled = :enabled')
            ->setParameter('searchedDate', $date->format('Y-m-d'))
            ->setParameter('enabled', true);
    }

    public function getEnabledFilteredByDateQ(DateTimeInterface $date): Query
    {
        return $this->getEnabledFilteredByDateQB($date)->getQuery();
    }

    public function getEnabledFilteredByDate(DateTimeInterface $date): array
    {
        return $this->getEnabledFilteredByDateQ($date)->getResult();
    }

    public function getEnabledFilteredByDateSortedByBeginAndClassroomQB(DateTimeInterface $date): QueryBuilder
    {
        return $this->getEnabledFilteredByDateQB($date)
            ->join('e.group', 'g')
            ->orderBy('e.begin')
            ->addOrderBy('e.classroom')
            ->addOrderBy('g.name');
    }

    public function getEnabledFilteredByDateSortedByBeginAndClassroomQ(DateTimeInterface $date): Query
    {
        return $this->getEnabledFilteredByDateSortedByBeginAndClassroomQB($date)->getQuery();
    }

    public function getEnabledFilteredByDateSortedByBeginAndClassroom(DateTimeInterface $date): array
    {
        return $this->getEnabledFilteredByDateSortedByBeginAndClassroomQ($date)->getResult();
    }
}
