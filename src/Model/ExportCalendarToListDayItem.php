<?php

namespace App\Model;

use App\Entity\Event;

class ExportCalendarToListDayItem
{
    private string $weekdayName;
    private \DateTimeInterface $day;
    private array $events;
    private array $hours;

    public function __construct(string $weekdayName, \DateTimeInterface $day)
    {
        $this->weekdayName = $weekdayName;
        $this->day = $day;
        $this->events = [];
        $this->hours = [];
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

    public function getDay(): \DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function setEvents(array $events): self
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event): self
    {
        $this->events[] = $event;

        return $this;
    }

    public function getHours(): array
    {
        return $this->hours;
    }

    public function setHours(array $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function addHour(ExportCalendarToListDayHourItem $hours): self
    {
        $this->hours[] = $hours;

        return $this;
    }

    public function getMaxStudentRows(): int
    {
        $maxStudentRows = 0;
        /** @var ExportCalendarToListDayHourItem $hour */
        foreach ($this->getHours() as $hour) {
            if ($hour->getMaxStudentRows() > $maxStudentRows) {
                $maxStudentRows = $hour->getMaxStudentRows();
            }
        }

        return $maxStudentRows;
    }
}
