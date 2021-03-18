<?php

namespace App\Model;

use App\Entity\Event;
use DateTimeInterface;

class ExportCalendarToListDayItem
{
    private string $weekdayName;
    private DateTimeInterface $day;
    private array $events;

    public function __construct(string $weekdayName, DateTimeInterface $day)
    {
        $this->weekdayName = $weekdayName;
        $this->day = $day;
        $this->events = [];
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
}
