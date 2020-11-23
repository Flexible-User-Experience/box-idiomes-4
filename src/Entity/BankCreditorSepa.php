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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return BankCreditorSepa
     */
    public function setName(string $name): BankCreditorSepa
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     *
     * @return BankCreditorSepa
     */
    public function setOrganizationId(string $organizationId): BankCreditorSepa
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreditorName(): string
    {
        return $this->creditorName;
    }

    /**
     * @param string $creditorName
     *
     * @return BankCreditorSepa
     */
    public function setCreditorName(string $creditorName): BankCreditorSepa
    {
        $this->creditorName = $creditorName;

        return $this;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     *
     * @return BankCreditorSepa
     */
    public function setIban(string $iban): BankCreditorSepa
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param string $bic
     *
     * @return BankCreditorSepa
     */
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
        return $this->id ? $this->getName().' ('.$this->getIban().')' : '---';
    }
}
