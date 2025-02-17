<?php

namespace App\Entity;

use App\Enum\PreRegisterSeasonEnum;
use App\Repository\PreRegisterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreRegisterRepository::class)]
#[ORM\Table(name: 'pre_register')]
class PreRegister extends AbstractPerson
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $age = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $courseLevel = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $preferredTimetable = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $previousAcademy = null;

    #[ORM\Column(type: Types::TEXT, length: 4000, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $season = 0;

    #[ORM\ManyToOne(targetEntity: ClassGroup::class)]
    protected ?ClassGroup $classGroup = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    protected ?bool $hasBeenPreviousCustomer = false;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    protected ?bool $wantsToMakeOfficialExam = false;

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): PreRegister
    {
        $this->age = $age;

        return $this;
    }

    public function getCourseLevel(): ?string
    {
        return $this->courseLevel;
    }

    public function setCourseLevel(?string $courseLevel): PreRegister
    {
        $this->courseLevel = $courseLevel;

        return $this;
    }

    public function getPreferredTimetable(): ?string
    {
        return $this->preferredTimetable;
    }

    public function setPreferredTimetable(?string $preferredTimetable): PreRegister
    {
        $this->preferredTimetable = $preferredTimetable;

        return $this;
    }

    public function getPreviousAcademy(): ?string
    {
        return $this->previousAcademy;
    }

    public function setPreviousAcademy(?string $previousAcademy): PreRegister
    {
        $this->previousAcademy = $previousAcademy;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): PreRegister
    {
        $this->comments = $comments;

        return $this;
    }

    public function getSeason(): int
    {
        return $this->season;
    }

    public function getSeasonString(): ?string
    {
        return PreRegisterSeasonEnum::getReversedEnumArray()[$this->getSeason()];
    }

    public function setSeason(int $season): PreRegister
    {
        $this->season = $season;

        return $this;
    }

    public function getClassGroup(): ?ClassGroup
    {
        return $this->classGroup;
    }

    public function setClassGroup(?ClassGroup $classGroup): PreRegister
    {
        $this->classGroup = $classGroup;

        return $this;
    }

    public function getHasBeenPreviousCustomer(): ?bool
    {
        return $this->hasBeenPreviousCustomer;
    }

    public function getHasBeenPreviousCustomerString(): string
    {
        return $this->getHasBeenPreviousCustomer() ? 'sí' : 'no';
    }

    public function setHasBeenPreviousCustomer(?bool $hasBeenPreviousCustomer): self
    {
        $this->hasBeenPreviousCustomer = $hasBeenPreviousCustomer;

        return $this;
    }

    public function getWantsToMakeOfficialExam(): ?bool
    {
        return $this->wantsToMakeOfficialExam;
    }

    public function getWantsToMakeOfficialExamString(): string
    {
        return $this->getWantsToMakeOfficialExam() ? 'sí' : 'no';
    }

    public function setWantsToMakeOfficialExam(?bool $wantsToMakeOfficialExam): self
    {
        $this->wantsToMakeOfficialExam = $wantsToMakeOfficialExam;

        return $this;
    }

    public function getEnabledString(): string
    {
        return $this->getEnabled() ? 'sí' : 'no';
    }

    public function getStudendCreatedString(): string
    {
        return $this->getEnabledString();
    }
}
