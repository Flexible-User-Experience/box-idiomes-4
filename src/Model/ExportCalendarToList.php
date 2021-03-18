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

    public function addDay(ExportCalendarToListDayItem $dayItem)
    {
        $this->days[] = $dayItem;
    }
}
