<?php

namespace App\Repository;

use App\Entity\Bank;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class BankRepository.
 *
 * @category Repository
 */
class BankRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bank::class);
    }

    /**
     * @param Student|null $student
     *
     * @return QueryBuilder
     */
    public function getStudentRelatedItemsQB(Student $student = null)
    {
        $qb = $this->createQueryBuilder('b');

        if ($student instanceof Student && !is_null($student->getId())) {
            // $student is not null
            $qb
                ->where('b.parent = :parent')
                ->setParameter('parent', $student->getParent())
            ;
        } else {
            // $student is null
            $qb->where('b.id < 0');
        }

        return $qb;
    }

    /**
     * @param Student|null $student
     *
     * @return Query
     */
    public function getStudentRelatedItemsQ(Student $student = null)
    {
        return $this->getStudentRelatedItemsQB($student)->getQuery();
    }

    /**
     * @param Student|null $student
     *
     * @return array
     */
    public function getStudentRelatedItems(Student $student = null)
    {
        return $this->getStudentRelatedItemsQ($student)->getResult();
    }
}
