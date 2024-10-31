<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait BaseAmountTrait
{
    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private float $baseAmount;

    public function getBaseAmount(): float
    {
        return $this->baseAmount;
    }

    public function getBaseAmountString(): string
    {
        return number_format($this->baseAmount, 2, ',', '.');
    }

    public function getAmount(): float
    {
        return $this->getBaseAmount();
    }

    public function getAmountString(): string
    {
        return $this->getBaseAmountString();
    }

    public function setBaseAmount(float $baseAmount): self
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }
}
