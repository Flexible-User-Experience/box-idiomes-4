<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptRepository")
 * @ORM\Table(name="receipt")
 * @UniqueEntity(fields={"month", "year", "student", "person", "isForPrivateLessons"})
 */
class Receipt extends AbstractReceiptInvoice
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReceiptLine", mappedBy="receipt", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     */
    private ?Collection $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    public function getLines(): ?Collection
    {
        return $this->lines;
    }

    public function setLines(?Collection $lines): self
    {
        $this->lines = $lines;

        return $this;
    }

    public function addLine(ReceiptLine $line): self
    {
        if (!$this->lines->contains($line)) {
            $line->setReceipt($this);
            $this->lines->add($line);
            $this->setBaseAmount($this->getBaseAmount() + $line->getTotal());
            $this->setDiscountApplied($this->getStudent()->hasDiscount());
        }

        return $this;
    }

    public function removeLine(ReceiptLine $line): self
    {
        if ($this->lines->contains($line)) {
            $this->lines->removeElement($line);
            $this->setBaseAmount($this->getBaseAmount() - $line->getTotal());
        }

        return $this;
    }

    public function getReceiptNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'/'.$this->getId();
    }

    public function getSluggedReceiptNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'-'.$this->getId();
    }

    public function getUnderscoredReceiptNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'_'.$this->getId();
    }

    public function calculateTotalBaseAmount(): float
    {
        $result = 0.0;
        /** @var ReceiptLine $line */
        foreach ($this->lines as $line) {
            $result += $line->calculateBaseAmount();
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getReceiptNumber().' · '.$this->getStudent().' · '.$this->getBaseAmountString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
