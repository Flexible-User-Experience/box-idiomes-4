<?php

namespace App\Entity\Traits;

use App\Entity\BankCreditorSepa;
use Doctrine\ORM\Mapping as ORM;

trait BankCreditorSepaTrait
{
    #[ORM\ManyToOne(targetEntity: BankCreditorSepa::class)]
    #[ORM\JoinColumn(name: 'bank_creditor_sepa_id', referencedColumnName: 'id', nullable: true)]
    private ?BankCreditorSepa $bankCreditorSepa = null;

    public function getBankCreditorSepa(): ?BankCreditorSepa
    {
        return $this->bankCreditorSepa;
    }

    public function setBankCreditorSepa(?BankCreditorSepa $bankCreditorSepa): self
    {
        $this->bankCreditorSepa = $bankCreditorSepa;

        return $this;
    }
}
