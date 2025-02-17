<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: 'invoice')]
class Invoice extends AbstractReceiptInvoice
{
    public const int TAX_IRPF = 15;
    public const int TAX_IVA = 0;

    #[ORM\ManyToOne(targetEntity: Receipt::class)]
    #[ORM\JoinColumn(name: 'receipt_id', referencedColumnName: 'id')]
    private ?Receipt $receipt;

    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceLine::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Collection $lines;

    #[ORM\Column(type: Types::FLOAT, options: ['default' => 0])]
    private float $taxPercentage = self::TAX_IVA;

    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['default' => 15])]
    private ?float $irpfPercentage = self::TAX_IRPF;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $totalAmount = null;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
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

    public function basicAddLine(InvoiceLine $line): self
    {
        $this->lines->add($line);

        return $this;
    }

    public function addLine(InvoiceLine $line): self
    {
        if (!$this->lines->contains($line)) {
            $line->setInvoice($this);
            $this->lines->add($line);
            $this->setBaseAmount($this->getBaseAmount() + $line->getTotal());
            $this->setDiscountApplied($this->getStudent()->hasDiscount());
        }

        return $this;
    }

    public function removeLine(InvoiceLine $line): self
    {
        if ($this->lines->contains($line)) {
            $this->lines->removeElement($line);
            $this->setBaseAmount($this->getBaseAmount() - $line->getTotal());
        }

        return $this;
    }

    public function getTaxPercentage(): float
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage(float $taxPercentage): self
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    public function getIrpfPercentage(): ?float
    {
        return $this->irpfPercentage;
    }

    public function setIrpfPercentage(?float $irpfPercentage): self
    {
        $this->irpfPercentage = $irpfPercentage;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function getTotalAmountString(): string
    {
        return number_format($this->totalAmount, 2, ',', '.').'€';
    }

    public function setTotalAmount(?float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getInvoiceNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'/'.$this->getId();
    }

    public function getSluggedInvoiceNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'-'.$this->getId();
    }

    public function getUnderscoredInvoiceNumber(): string
    {
        $date = new DateTimeImmutable();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'_'.$this->getId();
    }

    public function calculateBaseAmount(): float
    {
        $result = 0.0;
        /** @var InvoiceLine $line */
        foreach ($this->lines as $line) {
            $result += $line->calculateBaseAmount();
        }

        return $result;
    }

    public function calculateTaxPercentage(): float
    {
        return $this->getBaseAmount() * ($this->getTaxPercentage() / 100);
    }

    public function calculateIrpfPercentatge(): float
    {
        return $this->getBaseAmount() * ($this->getIrpfPercentage() / 100);
    }

    public function calculateTotalAmount(): float
    {
        return $this->getBaseAmount() + $this->calculateTaxPercentage() - $this->calculateIrpfPercentatge();
    }

    public function calculateIrpfOverhead($value): float
    {
        return $value / (1 - ($this->getIrpfPercentage() / 100));
    }

    public function __toString(): string
    {
        return $this->id ? $this->getInvoiceNumber().' · '.$this->getStudent().' · '.$this->getTotalAmountString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
