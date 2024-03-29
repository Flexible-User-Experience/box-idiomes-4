<?php

namespace App\Form\Model;

use App\Entity\ClassGroup;
use App\Entity\Teacher;
use App\Entity\TrainingCenter;

class FilterCalendarEventModel
{
    private ?int $classroom = null;
    private ?Teacher $teacher = null;
    private ?ClassGroup $group = null;
    private ?TrainingCenter $trainingCenter = null;

    public function getClassroom(): ?int
    {
        return $this->classroom;
    }

    public function setClassroom(?int $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getGroup(): ?ClassGroup
    {
        return $this->group;
    }

    public function setGroup(?ClassGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getTrainingCenter(): ?TrainingCenter
    {
        return $this->trainingCenter;
    }

    public function setTrainingCenter(?TrainingCenter $trainingCenter): self
    {
        $this->trainingCenter = $trainingCenter;

        return $this;
    }
}
