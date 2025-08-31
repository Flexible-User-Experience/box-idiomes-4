<?php

namespace App\Entity;

use App\Entity\Traits\StudentTrait;
use App\Repository\StudentEvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: StudentEvaluationRepository::class)]
#[UniqueEntity(['student', 'course', 'evaluation'])]
#[ORM\Table(name: 'student_evaluation')]
class StudentEvaluation extends AbstractBase
{
    use StudentTrait;

    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Student::class)]
    private Student $student;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => false])]
    private ?bool $hasBeenNotified = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $notificationDate = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => false])]
    private ?bool $hasBeenAccepted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $acceptedDate;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => false])]
    private ?bool $hasBeenClosed = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $closedDate;

    public function isHasBeenNotified(): ?bool
    {
        return $this->hasBeenNotified;
    }

    public function getHasBeenNotified(): ?bool
    {
        return $this->isHasBeenNotified();
    }

    public function hasBeenNotified(): ?bool
    {
        return $this->isHasBeenNotified();
    }

    public function setHasBeenNotified(?bool $hasBeenNotified): self
    {
        $this->hasBeenNotified = $hasBeenNotified;

        return $this;
    }

    public function getNotificationDate(): ?\DateTimeInterface
    {
        return $this->notificationDate;
    }

    public function getNotificationDateString(): string
    {
        return self::convertDateTimeAsString($this->getNotificationDate());
    }

    public function setNotificationDate(?\DateTimeInterface $notificationDate): self
    {
        $this->notificationDate = $notificationDate;

        return $this;
    }

    public function isHasBeenAccepted(): ?bool
    {
        return $this->hasBeenAccepted;
    }

    public function getHasBeenAccepted(): ?bool
    {
        return $this->isHasBeenAccepted();
    }

    public function hasBeenAccepted(): ?bool
    {
        return $this->isHasBeenAccepted();
    }

    public function setHasBeenAccepted(?bool $hasBeenAccepted): self
    {
        $this->hasBeenAccepted = $hasBeenAccepted;

        return $this;
    }

    public function getAcceptedDate(): ?\DateTimeInterface
    {
        return $this->acceptedDate;
    }

    public function setAcceptedDate(?\DateTimeInterface $acceptedDate): self
    {
        $this->acceptedDate = $acceptedDate;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getDayString().' · '.$this->getStudent() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
