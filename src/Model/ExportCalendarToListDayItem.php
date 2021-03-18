<?php

namespace App\Model;

use DateTimeInterface;

class ExportCalendarToListDayItem
{
    private string $weekdayName;
    private DateTimeInterface $day;

    public function __construct(string $weekdayName, DateTimeInterface $day)
    {
        $this->weekdayName = $weekdayName;
        $this->day = $day;
    }

    public function getWeekdayName(): string
    {
        return $this->weekdayName;
    }

    public function setWeekdayName(string $weekdayName): self
    {
        $this->weekdayName = $weekdayName;

        return $this;
    }

    public function getDay(): DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }
}
