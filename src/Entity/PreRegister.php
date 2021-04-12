<?php

namespace App\Entity;

use App\Enum\PreRegisterSeasonEnum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreRegisterRepository")
 * @ORM\Table(name="pre_register")
 */
class PreRegister extends AbstractPerson
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $age;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $courseLevel;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $preferredTimetable;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $previousAcademy;

    /**
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private ?string $comments;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private int $season = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClassGroup")
     */
    protected ?ClassGroup $classGroup;

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
}
