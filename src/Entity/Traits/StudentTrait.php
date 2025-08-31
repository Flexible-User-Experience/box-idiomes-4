<?php

namespace App\Entity\Traits;

use App\Entity\Student;

trait StudentTrait
{
    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
