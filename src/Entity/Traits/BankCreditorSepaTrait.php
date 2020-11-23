<?php

namespace App\Entity\Traits;

use App\Entity\BankCreditorSepa;
use Doctrine\ORM\Mapping as ORM;

/**
 * BankCreditorSepaTrait trait
 *
 * @category Trait
 * @package  App\Entity\Traits
 * @author   David Romaní <david@flux.cat>
 */
Trait BankCreditorSepaTrait
{
    /**
     * @var BankCreditorSepa
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\BankCreditorSepa")
     * @ORM\JoinColumn(name="bank_creditor_sepa_id", referencedColumnName="id", nullable=true)
     */
    private $bankCreditorSepa;

    /**
     * @return BankCreditorSepa|null
     */
    public function getBankCreditorSepa(): ?BankCreditorSepa
    {
        return $this->bankCreditorSepa;
    }

    /**
     * @param BankCreditorSepa|null $bankCreditorSepa
     *
     * @return $this|null
     */
    public function setBankCreditorSepa(?BankCreditorSepa $bankCreditorSepa)
    {
        $this->bankCreditorSepa = $bankCreditorSepa;

        return $this;
    }
}
