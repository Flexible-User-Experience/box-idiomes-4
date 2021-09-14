<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry as RegistryInterface;

class EventStudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventStudent::class);
    }

    public function getItemsByEvent(Event $event): array
    {
        return $this->createQueryBuilder('es')
            ->join('es.student', 's')
            ->where('es.event = :event')
            ->setParameter('event', $event)
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
