<?php

namespace App\Entity;

use App\Repository\BankCreditorSepaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BankCreditorSepaRepository::class)]
#[ORM\Table(name: 'bank_creditor_sepa')]
class BankCreditorSepa extends AbstractBase
{
    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $organizationId;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $creditorName;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\Iban]
    private string $iban;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\Bic]
    private string $bic;

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

    public function __toString(): string
    {
        return $this->id ? $this->getName().' · '.$this->getIban() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
