<?php

namespace App\Entity;

use App\Enum\TariffTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TariffRepository")
 * @ORM\Table(name="tariff")
 * @UniqueEntity({"type", "year"})
 */
class Tariff extends AbstractBase
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $year;

    /**
     * @ORM\Column(type="float")
     */
    private float $price;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private int $type = TariffTypeEnum::TARIFF_ONE_HOUR_PER_WEEK;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $name;

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPriceString(): string
    {
        return $this->price.'€';
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getTypeString(): string
    {
        return TariffTypeEnum::getTranslatedEnumArray()[$this->type];
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getYear().' · '.$this->getTypeString().' · '.$this->getPriceString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
