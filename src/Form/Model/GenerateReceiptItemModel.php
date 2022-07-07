<?php

namespace App\Form\Model;

class GenerateReceiptItemModel
{
    protected int $studentId;
    protected string $studentName;
    protected float $units;
    protected float $unitPrice;
    protected ?float $discount;
    protected bool $isReadyToGenerate;
    protected bool $isPreviouslyGenerated;
    protected bool $isPrivateLessonType;

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getStudentName(): string
    {
        return $this->studentName;
    }

    public function setStudentName(string $studentName): self
    {
        $this->studentName = $studentName;

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

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

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

    public function isReadyToGenerate(): bool
    {
        return $this->isReadyToGenerate;
    }

    public function getIsReadyToGenerate(): bool
    {
        return $this->isReadyToGenerate();
    }

    public function setIsReadyToGenerate(bool $isReadyToGenerate): self
    {
        $this->isReadyToGenerate = $isReadyToGenerate;

        return $this;
    }

    public function isPreviouslyGenerated(): bool
    {
        return $this->isPreviouslyGenerated;
    }

    public function getIsPreviouslyGenerated(): bool
    {
        return $this->isPreviouslyGenerated();
    }

    public function setIsPreviouslyGenerated($isPreviouslyGenerated): self
    {
        $this->isPreviouslyGenerated = $isPreviouslyGenerated;

        return $this;
    }

    public function isPrivateLessonType(): bool
    {
        return $this->isPrivateLessonType;
    }

    public function getPrivateLessonType(): bool
    {
        return $this->isPrivateLessonType();
    }

    public function setIsPrivateLessonType($isPrivateLessonType): self
    {
        $this->isPrivateLessonType = $isPrivateLessonType;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->getUnits() * $this->getUnitPrice() - $this->getDiscount();
    }
}
