<?php

namespace App\Entity;

use App\Enum\EventClassroomTypeEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Event.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event extends AbstractBase
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $begin;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher")
     */
    private $teacher;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $classroom = 0;

    /**
     * @var ClassGroup
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClassGroup")
     */
    private $group;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Student", inversedBy="events")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $students;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     * @ORM\JoinColumn(name="previous_id", referencedColumnName="id", nullable=true)
     */
    private $previous;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     * @ORM\JoinColumn(name="next_id", referencedColumnName="id", nullable=true)
     */
    private $next;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(1)
     */
    private $dayFrequencyRepeat;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $until;

    /**
     * Methods.
     */

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @return DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @return string
     */
    public function getBeginString()
    {
        return $this->getBegin() ? $this->getBegin()->format('d/m/Y H:i') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    /**
     * @return Event
     */
    public function setBegin(DateTime $begin)
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getEndString()
    {
        return $this->getEnd() ? $this->getEnd()->format('d/m/Y H:i') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    /**
     * @return Event
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param Teacher $teacher
     *
     * @return Event
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return int
     */
    public function getClassroom()
    {
        return $this->classroom;
    }

    /**
     * @return string
     */
    public function getClassroomString()
    {
        return EventClassroomTypeEnum::getTranslatedEnumArray()[$this->classroom];
    }

    /**
     * @return string
     */
    public function getShortClassroomString()
    {
        return EventClassroomTypeEnum::getShortTranslatedEnumArray()[$this->classroom];
    }

    /**
     * @param int $classroom
     *
     * @return Event
     */
    public function setClassroom($classroom)
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * @return ClassGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param ClassGroup $group
     *
     * @return Event
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @return int
     */
    public function getStudentsAmount()
    {
        return count($this->getStudents());
    }

    /**
     * @return string
     */
    public function getStudentsString()
    {
        $result = [];
        /** @var Student $student */
        foreach ($this->getStudents() as $student) {
            $result[] = $student->getFullName();
        }

        return implode(' · ', $result);
    }

    /**
     * @param ArrayCollection $students
     *
     * @return Event
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    /**
     * @return $this
     */
    public function addStudent(Student $student)
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeStudent(Student $student)
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
        }

        return $this;
    }

    /**
     * @return Event
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param Event $previous
     *
     * @return Event
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @return Event
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param Event $next
     *
     * @return Event
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * @return int
     */
    public function getDayFrequencyRepeat()
    {
        return $this->dayFrequencyRepeat;
    }

    /**
     * @param int|null $dayFrequencyRepeat
     *
     * @return Event
     */
    public function setDayFrequencyRepeat($dayFrequencyRepeat)
    {
        $this->dayFrequencyRepeat = $dayFrequencyRepeat;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param DateTime|null $until
     *
     * @return Event
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validateEnd(ExecutionContextInterface $context)
    {
        if ($this->getEnd() < $this->getBegin()) {
            $context
                ->buildViolation('La data final ha de ser més gran que la data d\'inici')
                ->atPath('end')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function validateUntil(ExecutionContextInterface $context)
    {
        if (!is_null($this->getUntil()) && $this->getUntil() < $this->getEnd()) {
            $context
                ->buildViolation('La data de repeteció final ha de ser més gran que la data final')
                ->atPath('until')
                ->addViolation();
        }
    }

    /**
     * @return string
     */
    public function getCalendarTitle()
    {
        return '['.$this->getShortClassroomString().'] '.$this->getGroup()->getCode().' '.$this->getTeacher()->getName();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getBeginString().' · '.$this->getClassroomString().' · '.$this->getTeacher()->getName().' · '.$this->getGroup()->getCode() : '---';
    }
}
