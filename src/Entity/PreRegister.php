<?php

namespace App\Entity;

use App\Enum\PreRegisterSeasonEnum;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PreRegister.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\PreRegisterRepository")
 * @ORM\Table(name="pre_register")
 */
class PreRegister extends AbstractPerson
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $courseLevel;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $preferredTimetable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $previousAcademy;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $comments;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $season = 0;

    /**
     * @var ClassGroup
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClassGroup")
     */
    protected $classGroup;

    /**
     * Methods.
     */

    /**
     * @return string|null
     */
    public function getAge(): ?string
    {
        return $this->age;
    }

    /**
     * @param string|null $age
     *
     * @return $this
     */
    public function setAge(?string $age): PreRegister
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCourseLevel(): ?string
    {
        return $this->courseLevel;
    }

    /**
     * @param string|null $courseLevel
     *
     * @return PreRegister
     */
    public function setCourseLevel(?string $courseLevel): PreRegister
    {
        $this->courseLevel = $courseLevel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreferredTimetable(): ?string
    {
        return $this->preferredTimetable;
    }

    /**
     * @param string|null $preferredTimetable
     *
     * @return PreRegister
     */
    public function setPreferredTimetable(?string $preferredTimetable): PreRegister
    {
        $this->preferredTimetable = $preferredTimetable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreviousAcademy(): ?string
    {
        return $this->previousAcademy;
    }

    /**
     * @param string|null $previousAcademy
     *
     * @return PreRegister
     */
    public function setPreviousAcademy(?string $previousAcademy): PreRegister
    {
        $this->previousAcademy = $previousAcademy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

    /**
     * @param string|null $comments
     *
     * @return PreRegister
     */
    public function setComments(?string $comments): PreRegister
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeason(): int
    {
        return $this->season;
    }

    /**
     * @return string|null
     */
    public function getSeasonString(): ?string
    {
        return PreRegisterSeasonEnum::getReversedEnumArray()[$this->getSeason()];
    }

    /**
     * @param int $season
     *
     * @return $this
     */
    public function setSeason(int $season): PreRegister
    {
        $this->season = $season;

        return $this;
    }

    /**
     * @return ClassGroup|null
     */
    public function getClassGroup(): ?ClassGroup
    {
        return $this->classGroup;
    }

    /**
     * @param ClassGroup|null $classGroup
     *
     * @return $this
     */
    public function setClassGroup(?ClassGroup $classGroup): PreRegister
    {
        $this->classGroup = $classGroup;

        return $this;
    }
}
