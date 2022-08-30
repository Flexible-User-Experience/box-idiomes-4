<?php

namespace App\Model;

use App\Entity\AbstractBase;
use DateTime;
use DateTimeInterface;

class BeginEndSchoolYearMoment
{
    private DateTimeInterface $begin;
    private DateTimeInterface $end;

    public function __construct(int $schoolYear)
    {
        $this->begin = new DateTime();
        $this->begin->setDate($schoolYear, 8, 31);
        $this->begin->setTime(0, 0);
        $this->end = new DateTime();
        $this->end->setDate($schoolYear + 1, 9, 1);
        $this->end->setTime(0, 0);
    }

    public function getBegin(): DateTimeInterface
    {
        return $this->begin;
    }

    public function getBeginString(): string
    {
        return AbstractBase::convertDateTimeAsString($this->getBegin());
    }

    public function setBegin(DateTimeInterface $begin): self
    {
        $this->begin = $begin;

        return $this;
    }

    public function getEnd(): DateTimeInterface
    {
        return $this->end;
    }

    public function getEndString(): string
    {
        return AbstractBase::convertDateTimeAsString($this->getEnd());
    }

    public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}
