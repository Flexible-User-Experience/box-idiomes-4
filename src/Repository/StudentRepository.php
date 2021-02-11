<?php

namespace App\Repository;

use App\Entity\ClassGroup;
use App\Entity\PreRegister;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class StudentRepository.
 *
 * @category Repository
 */
class StudentRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->getAllSortedByNameQB()
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedByNameQ()
    {
        return $this->getEnabledSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedByName()
    {
        return $this->getEnabledSortedByNameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getAllSortedByNameQB()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->addOrderBy('s.surname', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getAllSortedByNameQ()
    {
        return $this->getAllSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getAllSortedByName()
    {
        return $this->getAllSortedByNameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameQB()
    {
        return $this->getAllSortedBySurnameQB()
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedBySurnameQ()
    {
        return $this->getEnabledSortedBySurnameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedBySurname()
    {
        return $this->getEnabledSortedBySurnameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getAllSortedBySurnameQB()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getAllSortedBySurnameQ()
    {
        return $this->getAllSortedBySurnameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getAllSortedBySurname()
    {
        return $this->getAllSortedBySurnameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameValidTariffQB()
    {
        return $this->getEnabledSortedBySurnameQB()->andWhere('s.tariff IS NOT NULL');
    }

    /**
     * @return Query
     */
    public function getEnabledSortedBySurnameValidTariffQ()
    {
        return $this->getEnabledSortedBySurnameValidTariffQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedBySurnameWithValidTariff()
    {
        return $this->getEnabledSortedBySurnameValidTariffQ()->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsByYearAndMonthQB($year, $month)
    {
        return $this->createQueryBuilder('s')
            ->join('s.events', 'e')
            ->join('e.group', 'cg')
            ->where('YEAR(e.begin) = :year')
            ->andWhere('MONTH(e.begin) = :month')
            ->andWhere('e.enabled = :enabled')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('enabled', true)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsByYearAndMonthQ($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsByYearAndMonth($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQB($year, $month)
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurnameQ($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurname($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)
            ->andWhere('s.tariff IS NOT NULL')
            ->andWhere('s.isPaymentExempt = :exempt')
            ->setParameter('exempt', false)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', true)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', false)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getStudentsInClassGroupQB(ClassGroup $classGroup)
    {
        return $this->createQueryBuilder('s')
            ->join('s.events', 'e')
            ->join('e.group', 'cg')
            ->where('cg.id = :id')
            ->setParameter('id', $classGroup->getId());
    }

    /**
     * @return Query
     */
    public function getStudentsInClassGroupQ(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupQB($classGroup)->getQuery();
    }

    /**
     * @return array
     */
    public function getStudentsInClassGroup(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupQ($classGroup)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getStudentsInClassGroupSortedByNameQB(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupQB($classGroup)
            ->orderBy('s.surname')
            ->addOrderBy('s.name');
    }

    /**
     * @return Query
     */
    public function getStudentsInClassGroupSortedByNameQ(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupSortedByNameQB($classGroup)->getQuery();
    }

    /**
     * @return array
     */
    public function getStudentsInClassGroupSortedByName(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupSortedByNameQ($classGroup)->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getPreviouslyStoredStudentsFromPreRegisterQB(PreRegister $preRegister)
    {
        return $this->getAllSortedBySurnameQB()
            ->where('s.name = :name')
            ->andWhere('s.surname = :surname')
            ->setParameter('name', $preRegister->getName())
            ->setParameter('surname', $preRegister->getSurname());
    }

    /**
     * @return Query
     */
    public function getPreviouslyStoredStudentsFromPreRegisterQ(PreRegister $preRegister)
    {
        return $this->getPreviouslyStoredStudentsFromPreRegisterQB($preRegister)->getQuery();
    }

    /**
     * @return array
     */
    public function getPreviouslyStoredStudentsFromPreRegister(PreRegister $preRegister)
    {
        return $this->getPreviouslyStoredStudentsFromPreRegisterQ($preRegister)->getResult();
    }
}
