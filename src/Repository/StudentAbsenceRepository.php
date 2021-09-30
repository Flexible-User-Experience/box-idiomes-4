<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\StudentAbsence;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class StudentAbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentAbsence::class);
    }

    public function getStudentAbsencesSortedByDateQB(Student $student): QueryBuilder
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.student = :student')
            ->setParameter('student', $student)
            ->orderBy('sa.day', 'DESC')
            ->addOrderBy('sa.type', 'ASC');
    }

    public function getStudentAbsencesSortedByDateQ(Student $student): Query
    {
        return $this->getStudentAbsencesSortedByDateQB($student)->getQuery();
    }

    public function getStudentAbsencesSortedByDate(Student $student): array
    {
        return $this->getStudentAbsencesSortedByDateQ($student)->getResult();
    }

    public function getFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate): QueryBuilder
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.day BETWEEN :startDate AND :endDate')
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
}
