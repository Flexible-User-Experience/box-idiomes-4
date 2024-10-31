<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait YearTrait
{
    /**
     * @ORM\Column(type="integer")
     */
    protected int $year = 0;

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
