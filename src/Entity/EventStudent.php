<?php

namespace App\Entity;

use App\Entity\Traits\StudentTrait;
use App\Repository\EventStudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EventStudentRepository::class)]
#[UniqueEntity(['event', 'student'])]
#[ORM\Table(name: 'events_students')]
#[ORM\UniqueConstraint(name: 'UNIQ_DB5678D3EA12567', columns: ['event_id', 'student_id'])]
class EventStudent
{
    use StudentTrait;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Event::class)]
    private Event $event;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Student::class)]
    private Student $student;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    private bool $hasAttendedTheClass = true;

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function isHasAttendedTheClass(): bool
    {
        return $this->hasAttendedTheClass;
    }

    public function getHasAttendedTheClass(): bool
    {
        return $this->isHasAttendedTheClass();
    }

    public function hasAttendedTheClass(): bool
    {
        return $this->isHasAttendedTheClass();
    }

    public function setHasAttendedTheClass(bool $hasAttendedTheClass): self
    {
        $this->hasAttendedTheClass = $hasAttendedTheClass;

        return $this;
    }

    public function __toString(): string
    {
        return 'EID#'.$this->getEvent()->getId().' · SID#'.$this->getStudent()->getId().' · '.($this->hasAttendedTheClass() ? 'yes' : 'no');
    }
}
