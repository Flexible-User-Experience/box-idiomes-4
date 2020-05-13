<?php

namespace App\Repository;

use App\Entity\Teacher;
use App\Entity\TeacherAbsence;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class TeacherAbsenceRepository.
 *
 * @category Repository
 */
class TeacherAbsenceRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeacherAbsence::class);
    }

    /**
     * @param Teacher $teacher
     *
     * @return QueryBuilder
     */
    public function getTeacherAbsencesSortedByDateQB(Teacher $teacher)
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('ta.day', 'DESC')
            ->addOrderBy('ta.type', 'ASC');
    }

    /**
     * @param Teacher $teacher
     *
     * @return Query
     */
    public function getTeacherAbsencesSortedByDateQ(Teacher $teacher)
    {
        return $this->getTeacherAbsencesSortedByDateQB($teacher)->getQuery();
    }

    /**
     * @param Teacher $teacher
     *
     * @return array
     */
    public function getTeacherAbsencesSortedByDate(Teacher $teacher)
    {
        return $this->getTeacherAbsencesSortedByDateQ($teacher)->getResult();
    }

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     *
     * @return QueryBuilder
     */
    public function getFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.day BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
    }

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     *
     * @return Query
     */
    public function getFilteredByBeginAndEndQ(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     *
     * @return array
     */
    public function getFilteredByBeginAndEnd(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->getFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }
}
