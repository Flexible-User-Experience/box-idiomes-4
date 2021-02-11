<?php

namespace App\Repository;

use App\Entity\Bank;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BankRepository.
 *
 * @category Repository
 */
class BankRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bank::class);
    }

    /**
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
     * @return Query
     */
    public function getStudentRelatedItemsQ(Student $student = null)
    {
        return $this->getStudentRelatedItemsQB($student)->getQuery();
    }

    /**
     * @return array
     */
    public function getStudentRelatedItems(Student $student = null)
    {
        return $this->getStudentRelatedItemsQ($student)->getResult();
    }
}
