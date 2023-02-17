<?php

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\CityTrait;
use App\Entity\Traits\NameTrait;
use App\Enum\StudentPaymentEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity({"dni", "name", "surname"})
 */
abstract class AbstractPerson extends AbstractBase
{
    use AddressTrait;
    use CityTrait;
    use NameTrait;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTimeInterface $dischargeDate = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $dni = null;

    /**
     * @ORM\Column(type="string")
     */
    protected ?string $name = null;

    /**
     * @ORM\Column(type="string")
     */
    protected ?string $surname = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $phone = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Email()
     */
    protected ?string $email = null;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    protected int $payment = 0;

    protected ?Bank $bank = null;

    public function getDischargeDate(): ?\DateTimeInterface
    {
        return $this->dischargeDate;
    }

    public function getDischargeDateString(): string
    {
        return self::convertDateAsString($this->getDischargeDate());
    }

    public function setDischargeDate(?\DateTimeInterface $dischargeDate): self
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

    public function getFullName(): string
    {
        return $this->name.' '.$this->surname;
    }

    public function getFullCanonicalName(): string
    {
        return $this->surname.', '.$this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
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
