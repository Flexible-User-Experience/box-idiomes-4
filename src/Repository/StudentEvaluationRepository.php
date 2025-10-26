<?php

namespace App\Repository;

use App\Entity\StudentEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StudentEvaluationRepository extends ServiceEntityRepository
{
    private const string ALIAS = 'sa';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentEvaluation::class);
    }
}
