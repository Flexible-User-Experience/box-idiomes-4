<?php

namespace App\Entity;

use App\Enum\StudentPaymentEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProviderRepository")
 * @UniqueEntity({"tic"})
 */
class Provider extends AbstractBase
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $tic;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $alias = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     */
    private City $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private int $paymentMethod = StudentPaymentEnum::BANK_ACCOUNT_NUMBER;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Iban()
     */
    private ?string $ibanForBankDraftPayment = null;

    public function getTic(): string
    {
        return $this->tic;
    }

    public function setTic(string $tic): self
    {
        $this->tic = $tic;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPaymentMethod(): int
    {
        return $this->paymentMethod;
    }

    public function getPaymentString(): string
    {
        return StudentPaymentEnum::getEnumTranslatedArray()[$this->getPaymentMethod()];
    }

    public function setPaymentMethod(int $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getIbanForBankDraftPayment(): ?string
    {
        return $this->ibanForBankDraftPayment;
    }

    public function setIbanForBankDraftPayment(?string $ibanForBankDraftPayment): self
    {
        $this->ibanForBankDraftPayment = $ibanForBankDraftPayment;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? ($this->alias ? $this->getName().' · '.$this->getAlias().' · '.$this->getTic() : $this->getName().' · '.$this->getTic()) : AbstractBase::DEFAULT_NULL_STRING;
    }
}
