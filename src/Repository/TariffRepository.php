<?php

namespace App\Repository;

use App\Entity\Tariff;
use App\Enum\TariffTypeEnum;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class TariffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function findGroupLessonsSortedByYearAndPriceQB(): QueryBuilder
    {
        return $this->findAllSortedByYearAndPriceQB()
            ->where('t.type != :private')
            ->andWhere('t.type != :shared')
            ->setParameter('private', TariffTypeEnum::TARIFF_PRIVATE_LESSON_PER_HOUR)
            ->setParameter('shared', TariffTypeEnum::TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR);
    }

    public function findGroupLessonsSortedByYearAndPriceQ(): Query
    {
        return $this->findGroupLessonsSortedByYearAndPriceQB()->getQuery();
    }

    public function findGroupLessonsSortedByYearAndPrice(): array
    {
        return $this->findGroupLessonsSortedByYearAndPriceQ()->getResult();
    }

    public function findAllSortedByYearAndPriceQB(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.year', 'DESC')
            ->addOrderBy('t.price', 'ASC');
    }

    public function findAllSortedByYearAndPriceQ(): Query
    {
        return $this->findAllSortedByYearAndPriceQB()->getQuery();
    }

    public function findAllSortedByYearAndPrice(): array
    {
        return $this->findAllSortedByYearAndPriceQ()->getResult();
    }

    public function findCurrentPrivateLessonTariffQB(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->where('t.type = :type')
            ->setParameter('type', TariffTypeEnum::TARIFF_PRIVATE_LESSON_PER_HOUR)
            ->orderBy('t.year', 'DESC')
            ->setMaxResults(1);
    }

    public function findCurrentPrivateLessonTariffQ(): Query
    {
        return $this->findCurrentPrivateLessonTariffQB()->getQuery();
    }

    public function findCurrentPrivateLessonTariff(): Tariff
    {
        $result = $this->findCurrentPrivateLessonTariffQ()->getOneOrNullResult();

        if (is_null($result)) {
            $today = new DateTimeImmutable();
            $result = new Tariff();
            $result
                ->setName('default empty tariff')
                ->setYear((int) $today->format('Y'))
                ->setType(TariffTypeEnum::TARIFF_PRIVATE_LESSON_PER_HOUR)
                ->setPrice(0)
            ;
        }

        return $result;
    }

    public function findCurrentSharedPrivateLessonTariffQB(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->where('t.type = :type')
            ->setParameter('type', TariffTypeEnum::TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR)
            ->orderBy('t.year', 'DESC')
            ->setMaxResults(1);
    }

    public function findCurrentSharedPrivateLessonTariffQ(): Query
    {
        return $this->findCurrentSharedPrivateLessonTariffQB()->getQuery();
    }

    public function findCurrentSharedPrivateLessonTariff(): Tariff
    {
        $result = $this->findCurrentSharedPrivateLessonTariffQ()->getOneOrNullResult();

        if (is_null($result)) {
            $today = new DateTimeImmutable();
            $result = new Tariff();
            $result
                ->setName('default empty shared tariff')
                ->setYear((int) $today->format('Y'))
                ->setType(TariffTypeEnum::TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR)
                ->setPrice(0)
            ;
        }

        return $result;
    }
}
