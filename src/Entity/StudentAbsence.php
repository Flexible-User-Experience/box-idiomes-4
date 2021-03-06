<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class StudentAbsence.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\StudentAbsenceRepository")
 * @ORM\Table(name="student_absence")
 * @UniqueEntity({"student", "day"})
 */
class StudentAbsence extends AbstractBase
{
    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Student")
     */
    private $student;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    private $day;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $hasBeenNotified = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $notificationDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $hasBeenAccepted = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $acceptedDate;

    /**
     * Methods.
     */

    /**
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Student $student
     *
     * @return $this
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getDayString()
    {
        return $this->getDay() ? $this->getDay()->format('d/m/Y') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    /**
     * @return $this
     */
    public function setDay(DateTime $day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     */
    public function getCalendarTitle()
    {
        return '[Alumne] '.$this->getStudent()->getName();
    }

    /**
     * @return bool
     */
    public function isHasBeenNotified()
    {
        return $this->hasBeenNotified;
    }

    /**
     * @return bool
     */
    public function getHasBeenNotified()
    {
        return $this->isHasBeenNotified();
    }

    /**
     * @return bool
     */
    public function hasBeenNotified()
    {
        return $this->isHasBeenNotified();
    }

    /**
     * @param bool $hasBeenNotified
     *
     * @return $this
     */
    public function setHasBeenNotified($hasBeenNotified)
    {
        $this->hasBeenNotified = $hasBeenNotified;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getNotificationDate()
    {
        return $this->notificationDate;
    }

    /**
     * @param DateTime $notificationDate
     *
     * @return $this
     */
    public function setNotificationDate($notificationDate)
    {
        $this->notificationDate = $notificationDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasBeenAccepted()
    {
        return $this->hasBeenAccepted;
    }

    /**
     * @return bool
     */
    public function getHasBeenAccepted()
    {
        return $this->isHasBeenAccepted();
    }

    /**
     * @return bool
     */
    public function hasBeenAccepted()
    {
        return $this->isHasBeenAccepted();
    }

    /**
     * @param bool $hasBeenAccepted
     *
     * @return $this
     */
    public function setHasBeenAccepted($hasBeenAccepted)
    {
        $this->hasBeenAccepted = $hasBeenAccepted;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAcceptedDate()
    {
        return $this->acceptedDate;
    }

    /**
     * @param DateTime $acceptedDate
     *
     * @return $this
     */
    public function setAcceptedDate($acceptedDate)
    {
        $this->acceptedDate = $acceptedDate;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getDayString().' · '.$this->getStudent() : '---';
    }
}
