<?php

namespace App\Entity;

use App\Enum\TeacherAbsenceTypeEnum;
use App\Repository\TeacherAbsenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TeacherAbsenceRepository::class)]
#[UniqueEntity(['teacher', 'day'])]
#[ORM\Table(name: 'teacher_absence')]
class TeacherAbsence extends AbstractBase
{
    #[ORM\ManyToOne(targetEntity: Teacher::class)]
    private Teacher $teacher;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $type = TeacherAbsenceTypeEnum::PERSONAL_ISSUES;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $day;

    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getTypeString(): string
    {
        return TeacherAbsenceTypeEnum::getReversedEnumArray()[$this->type];
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDay(): \DateTimeInterface
    {
        return $this->day;
    }

    public function getDayString(): string
    {
        return $this->getDay() ? $this->getDay()->format(AbstractBase::DATE_STRING_FORMAT) : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getCalendarTitle(): string
    {
        return '['.$this->getTypeString().'] '.$this->getTeacher()->getName();
    }

    public function __toString(): string
    {
        return $this->id ? $this->getDayString().' · '.$this->getTypeString().' · '.$this->getTeacher() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
