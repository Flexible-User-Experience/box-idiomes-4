<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\StudentAbsence;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class StudentAbsenceRepository.
 *
 * @category Repository
 */
class StudentAbsenceRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentAbsence::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getStudentAbsencesSortedByDateQB(Student $student)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.student = :student')
            ->setParameter('student', $student)
            ->orderBy('sa.day', 'DESC')
            ->addOrderBy('sa.type', 'ASC');
    }

    /**
     * @return Query
     */
    public function getStudentAbsencesSortedByDateQ(Student $student)
    {
        return $this->getStudentAbsencesSortedByDateQB($student)->getQuery();
    }

    /**
     * @return array
     */
    public function getStudentAbsencesSortedByDate(Student $student)
    {
        return $this->getStudentAbsencesSortedByDateQ($student)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getFilteredByBeginAndEndQB(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.day BETWEEN :startDate AND :endDate')
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
}
