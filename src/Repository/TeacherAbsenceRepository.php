<?php

namespace App\Repository;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Teacher;
use App\Entity\TeacherAbsence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class TeacherAbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeacherAbsence::class);
    }

    public function getTeacherAbsencesSortedByDateQB(Teacher $teacher): QueryBuilder
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('ta.day', SortOrderTypeEnum::DESC)
            ->addOrderBy('ta.type', SortOrderTypeEnum::ASC);
    }

    public function getTeacherAbsencesSortedByDateQ(Teacher $teacher): Query
    {
        return $this->getTeacherAbsencesSortedByDateQB($teacher)->getQuery();
    }

    public function getTeacherAbsencesSortedByDate(Teacher $teacher): array
    {
        return $this->getTeacherAbsencesSortedByDateQ($teacher)->getResult();
    }

    public function getFilteredByBeginAndEndQB(\DateTimeInterface $startDate, \DateTimeInterface $endDate): QueryBuilder
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.day BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
    }

    public function getFilteredByBeginAndEndQ(\DateTimeInterface $startDate, \DateTimeInterface $endDate): Query
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    public function getFilteredByBeginAndEnd(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->getFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }
}
