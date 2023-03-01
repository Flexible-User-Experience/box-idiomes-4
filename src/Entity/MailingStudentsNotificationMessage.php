<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 *
 * @ORM\Entity(repositoryClass="App\Repository\MailingStudentsNotificationMessageRepository")
 */
class MailingStudentsNotificationMessage extends AbstractBase
{
    /**
     * @ORM\Column(type="text", length=10000, nullable=false)
     */
    private string $message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $sendDate = null;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default"=0})
     */
    private bool $isSended = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $filterStartDate = null;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $filterEndDate = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $filteredClassroom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher")
     *
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id", nullable=true)
     */
    private ?Teacher $filteredTeacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClassGroup")
     *
     * @ORM\JoinColumn(name="class_group_id", referencedColumnName="id", nullable=true)
     */
    private ?ClassGroup $filteredClassGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrainingCenter")
     *
     * @ORM\JoinColumn(name="training_center_id", referencedColumnName="id", nullable=true)
     */
    private ?ClassGroup $filteredTrainingCenter;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default"=0})
     */
    private int $totalTargetStudents = 0;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default"=0})
     */
    private int $totalDeliveredErrors = 0;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(?\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    public function isSended(): bool
    {
        return $this->isSended;
    }

    public function setIsSended(bool $isSended): self
    {
        $this->isSended = $isSended;

        return $this;
    }

    public function getFilterStartDate(): ?\DateTimeInterface
    {
        return $this->filterStartDate;
    }

    public function setFilterStartDate(?\DateTimeInterface $filterStartDate): self
    {
        $this->filterStartDate = $filterStartDate;

        return $this;
    }

    public function getFilterEndDate(): ?\DateTimeInterface
    {
        return $this->filterEndDate;
    }

    public function setFilterEndDate(?\DateTimeInterface $filterEndDate): self
    {
        $this->filterEndDate = $filterEndDate;

        return $this;
    }

    public function getFilteredClassroom(): int
    {
        return $this->filteredClassroom;
    }

    public function setFilteredClassroom(int $filteredClassroom): self
    {
        $this->filteredClassroom = $filteredClassroom;

        return $this;
    }

    public function getFilteredTeacher(): ?Teacher
    {
        return $this->filteredTeacher;
    }

    public function setFilteredTeacher(?Teacher $filteredTeacher): self
    {
        $this->filteredTeacher = $filteredTeacher;

        return $this;
    }

    public function getFilteredClassGroup(): ?ClassGroup
    {
        return $this->filteredClassGroup;
    }

    public function setFilteredClassGroup(?ClassGroup $filteredClassGroup): self
    {
        $this->filteredClassGroup = $filteredClassGroup;

        return $this;
    }

    public function getFilteredTrainingCenter(): ?ClassGroup
    {
        return $this->filteredTrainingCenter;
    }

    public function setFilteredTrainingCenter(?ClassGroup $filteredTrainingCenter): self
    {
        $this->filteredTrainingCenter = $filteredTrainingCenter;

        return $this;
    }

    public function getTotalTargetStudents(): int
    {
        return $this->totalTargetStudents;
    }

    public function setTotalTargetStudents(int $totalTargetStudents): self
    {
        $this->totalTargetStudents = $totalTargetStudents;

        return $this;
    }

    public function getTotalDeliveredErrors(): int
    {
        return $this->totalDeliveredErrors;
    }

    public function setTotalDeliveredErrors(int $totalDeliveredErrors): self
    {
        $this->totalDeliveredErrors = $totalDeliveredErrors;

        return $this;
    }
}
