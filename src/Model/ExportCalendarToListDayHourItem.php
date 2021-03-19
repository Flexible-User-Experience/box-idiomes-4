<?php

namespace App\Model;

use App\Entity\Event;
use DateTimeInterface;

class ExportCalendarToListDayHourItem
{
    private string $rangeName;
    private DateTimeInterface $begin;
    private DateTimeInterface $end;
    private array $events;

    public function __construct(string $rangeName, DateTimeInterface $begin, DateTimeInterface $end)
    {
        $this->rangeName = $rangeName;
        $this->begin = $begin;
        $this->end = $end;
        $this->events = [];
    }

    public function getRangeName(): string
    {
        return $this->rangeName;
    }

    public function setRangeName(string $rangeName): self
    {
        $this->rangeName = $rangeName;

        return $this;
    }

    public function getBegin(): DateTimeInterface
    {
        return $this->begin;
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

    public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

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
