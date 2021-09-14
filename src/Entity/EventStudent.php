<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventStudentRepository")
 * @ORM\Table(name="events_students", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_DB5678D3EA12567", columns={"event_id", "student_id"})})
 * @UniqueEntity({"event", "student"})
 */
class EventStudent
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     */
    private Event $event;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Student")
     */
    private Student $student;

    /**
     * @ORM\Column(type="boolean", options={"default"=1})
     */
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

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;

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
