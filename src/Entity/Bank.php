<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Repository\BankRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: 'bank')]
class Bank extends AbstractBase
{
    use NameTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $name = null;

    
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Iban]
    private ?string $accountNumber = null;

    
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Bic]
    private ?string $swiftCode = null;

    
    #[ORM\OneToOne(targetEntity: Person::class)]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    private ?Person $parent = null;

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function getBAN1part(): string
    {
        return strlen($this->accountNumber) < 4 ? '' : substr($this->accountNumber, 0, 4);
    }

    public function getBAN2part(): string
    {
        return strlen($this->accountNumber) < 8 ? '' : substr($this->accountNumber, 4, 4);
    }

    public function getBAN3part(): string
    {
        return strlen($this->accountNumber) < 12 ? '' : substr($this->accountNumber, 8, 4);
    }

    public function getBAN4part(): string
    {
        return strlen($this->accountNumber) < 16 ? '' : substr($this->accountNumber, 12, 4);
    }

    public function getBAN5part(): string
    {
        return strlen($this->accountNumber) < 20 ? '' : substr($this->accountNumber, 16, 4);
    }

    public function getBAN6part(): string
    {
        return strlen($this->accountNumber) < 24 ? '' : substr($this->accountNumber, 20, 4);
    }

    public function getIbanFormatNumber(): string
    {
        return $this->getBAN1part().' '.$this->getBAN2part().' '.$this->getBAN3part().' '.$this->getBAN4part().' '.$this->getBAN5part().' '.$this->getBAN6part();
    }

    public function setAccountNumber(?string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getSwiftCode(): ?string
    {
        return $this->swiftCode;
    }

    public function setSwiftCode(?string $swiftCode): self
    {
        $this->swiftCode = $swiftCode;

        return $this;
    }

    public function getParent(): ?Person
    {
        return $this->parent;
    }

    public function setParent(?Person $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getIbanFormatNumber() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
