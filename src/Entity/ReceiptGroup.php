<?php

namespace App\Entity;

use App\Entity\Traits\BaseAmountTrait;
use App\Entity\Traits\MonthTrait;
use App\Entity\Traits\TrainingCenterTrait;
use App\Entity\Traits\YearTrait;
use App\Repository\ReceiptGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReceiptGroupRepository::class)]
#[ORM\Table(name: 'receipt_group')]
class ReceiptGroup extends AbstractBase
{
    use BaseAmountTrait;
    use MonthTrait;
    use TrainingCenterTrait;
    use YearTrait;

    #[ORM\OneToMany(mappedBy: 'receiptGroup', targetEntity: Receipt::class)]
    #[Assert\Valid]
    private ?Collection $receipts;

    #[ORM\ManyToOne(targetEntity: BankCreditorSepa::class)]
    private ?BankCreditorSepa $bankCreditorSepa = null;

    public function __construct()
    {
        $this->receipts = new ArrayCollection();
    }

    public function getReceipts(): ?Collection
    {
        return $this->receipts;
    }

    public function setReceipts(?Collection $receipts): self
    {
        $this->receipts = $receipts;

        return $this;
    }

    public function addReceipt(Receipt $receipt): self
    {
        if (!$this->receipts->contains($receipt)) {
            $receipt->setReceiptGroup($this);
            $this->receipts->add($receipt);
        }

        return $this;
    }

    public function removeReceipt(Receipt $receipt): self
    {
        if ($this->receipts->contains($receipt)) {
            $this->receipts->removeElement($receipt);
        }

        return $this;
    }

    public function getBankCreditorSepa(): ?BankCreditorSepa
    {
        return $this->bankCreditorSepa;
    }

    public function setBankCreditorSepa(?BankCreditorSepa $bankCreditorSepa): self
    {
        $this->bankCreditorSepa = $bankCreditorSepa;

        return $this;
    }

    public function calculateTotal(): float
    {
        $result = 0.0;
        /** @var Receipt $receipt */
        foreach ($this->receipts as $receipt) {
            $result += $receipt->getBaseAmount();
        }

        return $result;
    }

    public function getReceiptNumber(): string
    {
        return sprintf('%s/%s', $this->getYear(), $this->getMonth());
    }

    public function __toString(): string
    {
        return $this->id ? $this->getReceiptNumber().' · '.$this->getTrainingCenter().' · '.$this->getBaseAmountString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
