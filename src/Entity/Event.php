<?php

namespace App\Entity;

use App\Enum\EventClassroomTypeEnum;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'event')]
class Event extends AbstractBase
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $begin;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $end;

    #[ORM\ManyToOne(targetEntity: Teacher::class)]
    private Teacher $teacher;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $classroom = 0;

    #[ORM\ManyToOne(targetEntity: ClassGroup::class)]
    private ClassGroup $group;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'events')]
    #[ORM\OrderBy(['surname' => 'ASC', 'name' => 'ASC'])]
    private Collection $students;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'previous_id', referencedColumnName: 'id', nullable: true)]
    private ?Event $previous = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'next_id', referencedColumnName: 'id', nullable: true)]
    private ?Event $next = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $dayFrequencyRepeat = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $until = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    public function getBeginString(): string
    {
        return self::convertDateTimeAsString($this->getBegin());
    }

    public function setBegin(\DateTimeInterface $begin): self
    {
        $this->begin = $begin;

        return $this;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    public function getEndString(): string
    {
        return self::convertDateTimeAsString($this->getEnd());
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getClassroom(): int
    {
        return $this->classroom;
    }

    public function getClassroomString(): string
    {
        return EventClassroomTypeEnum::getTranslatedEnumArray()[$this->classroom];
    }

    public function getShortClassroomString(): string
    {
        return EventClassroomTypeEnum::getShortTranslatedEnumArray()[$this->classroom];
    }

    public function setClassroom(int $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getGroup(): ClassGroup
    {
        return $this->group;
    }

    public function setGroup(ClassGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function getStudentsAmount(): int
    {
        return count($this->getStudents());
    }

    public function getStudentsString(): string
    {
        $result = [];
        /** @var Student $student */
        foreach ($this->getStudents() as $student) {
            $result[] = $student->getFullName();
        }

        return implode(' · ', $result);
    }

    public function setStudents(Collection $students): self
    {
        $this->students = $students;

        return $this;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
        }

        return $this;
    }

    public function getPrevious(): ?Event
    {
        return $this->previous;
    }

    public function setPrevious(?Event $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    public function getNext(): ?Event
    {
        return $this->next;
    }

    public function setNext(?Event $next): self
    {
        $this->next = $next;

        return $this;
    }

    public function getDayFrequencyRepeat(): ?int
    {
        return $this->dayFrequencyRepeat;
    }

    public function setDayFrequencyRepeat(?int $dayFrequencyRepeat): self
    {
        $this->dayFrequencyRepeat = $dayFrequencyRepeat;

        return $this;
    }

    public function getUntil(): ?\DateTimeInterface
    {
        return $this->until;
    }

    public function getUntilString(): string
    {
        return self::convertDateTimeAsString($this->getUntil());
    }

    public function setUntil(?\DateTimeInterface $until): self
    {
        $this->until = $until;

        return $this;
    }

    #[Assert\Callback]
    public function validateEnd(ExecutionContextInterface $context): void
    {
        if ($this->getEnd() < $this->getBegin()) {
            $context
                ->buildViolation('La data final ha de ser més gran que la data d\'inici')
                ->atPath('end')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateUntil(ExecutionContextInterface $context): void
    {
        if (!is_null($this->getUntil()) && $this->getUntil() < $this->getEnd()) {
            $context
                ->buildViolation('La data de repeteció final ha de ser més gran que la data final')
                ->atPath('until')
                ->addViolation();
        }
    }

    public function getCalendarTitle(): string
    {
        return '['.$this->getShortClassroomString().'] '.$this->getGroup()->getCode().' '.$this->getTeacher()->getName();
    }

    public function __toString(): string
    {
        return $this->id ? $this->getBeginString().' · '.$this->getClassroomString().' · '.$this->getTeacher()->getName().' · '.$this->getGroup()->getCode() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
