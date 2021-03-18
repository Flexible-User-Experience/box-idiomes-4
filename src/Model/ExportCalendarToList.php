<?php

namespace App\Model;

class ExportCalendarToList
{
    private array $days;

    public function __construct()
    {
        $this->days = [];
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function setDays(array $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function addDay(ExportCalendarToListDayItem $dayItem): self
    {
        $this->days[] = $dayItem;

        return $this;
    }

    public function getDaysAmount(): int
    {
        return count($this->getDays());
    }

    public function hasDays(): int
    {
        return $this->getDaysAmount() > 0;
    }
}
