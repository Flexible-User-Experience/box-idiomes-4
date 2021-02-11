<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BankCreditorSepa.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\BankCreditorSepaRepository")
 * @ORM\Table(name="bank_creditor_sepa")
 */
class BankCreditorSepa extends AbstractBase
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $organizationId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $creditorName;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Iban()
     */
    private string $iban;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Bic()
     */
    private string $bic;

    /**
     * Methods.
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): BankCreditorSepa
    {
        $this->name = $name;

        return $this;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function setOrganizationId(string $organizationId): BankCreditorSepa
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    public function getCreditorName(): string
    {
        return $this->creditorName;
    }

    public function setCreditorName(string $creditorName): BankCreditorSepa
    {
        $this->creditorName = $creditorName;

        return $this;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): BankCreditorSepa
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): string
    {
        return $this->bic;
    }

    public function setBic(string $bic): BankCreditorSepa
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getName().' · '.$this->getIban() : '---';
    }
}
