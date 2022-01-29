<?php

namespace App\Entity;

use App\Enum\StudentPaymentEnum;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity({"dni", "name", "surname"})
 */
abstract class AbstractPerson extends AbstractBase
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $dischargeDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $dni;

    /**
     * @ORM\Column(type="string")
     */
    protected string $name;

    /**
     * @ORM\Column(type="string")
     */
    protected string $surname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email()
     */
    protected ?string $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     */
    protected ?City $city;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    protected int $payment = 0;

    protected ?Bank $bank = null;

    public function getDischargeDate(): ?DateTimeInterface
    {
        return $this->dischargeDate;
    }

    public function getDischargeDateString(): string
    {
        return $this->getDischargeDate() ? $this->getDischargeDate()->format('d/m/Y') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    public function setDischargeDate(?DateTimeInterface $dischargeDate): self
    {
        $this->dischargeDate = $dischargeDate;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return $this->name.' '.$this->surname;
    }

    public function getFullCanonicalName(): string
    {
        return $this->surname.', '.$this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPayment(): int
    {
        return $this->payment;
    }

    public function getPaymentString(): string
    {
        return StudentPaymentEnum::getEnumTranslatedArray()[$this->payment];
    }

    public function setPayment(int $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getDebtorMandate(): string
    {
        return $this->getDni().'-'.strtoupper(substr($this->getName(), 0, 1)).strtoupper(substr($this->getSurname(), 0, 1)).'-'.uniqid('', false);
    }

    public function getDebtorMandateSignDate(): string
    {
        return $this->getCreatedAt()->format('d-m-Y');
    }

    public function __toString(): string
    {
        return $this->id ? $this->getFullName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
