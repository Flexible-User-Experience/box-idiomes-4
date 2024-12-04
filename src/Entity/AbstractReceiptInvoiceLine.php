<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractReceiptInvoiceLine extends AbstractBase
{
    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    protected Student $student;

    #[ORM\Column(type: Types::STRING)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::FLOAT)]
    protected float $units = 0.0;

    #[ORM\Column(type: Types::FLOAT)]
    protected float $priceUnit = 0.0;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    protected ?float $discount = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    protected ?float $total = null;

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUnits(): float
    {
        return $this->units;
    }

    public function setUnits(float $units): self
    {
        $this->units = $units;

        return $this;
    }

    public function getPriceUnit(): float
    {
        return $this->priceUnit;
    }

    public function setPriceUnit(float $priceUnit): self
    {
        $this->priceUnit = $priceUnit;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function calculateBaseAmount(): float
    {
        return $this->units * $this->priceUnit - $this->discount;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getDescription() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
