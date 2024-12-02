<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Enum\TariffTypeEnum;
use App\Repository\TariffRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: TariffRepository::class)]
#[UniqueEntity(['type', 'year'])]
#[ORM\Table(name: 'tariff')]
class Tariff extends AbstractBase
{
    use NameTrait;

    #[ORM\Column(type: Types::INTEGER)]
    private int $year;

    #[ORM\Column(type: Types::FLOAT)]
    private float $price;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $type = TariffTypeEnum::TARIFF_ONE_HOUR_PER_WEEK;

    #[ORM\Column(type: Types::STRING, nullable: true)]
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

    public function __toString(): string
    {
        return $this->id ? $this->getYear().' · '.$this->getTypeString().' · '.$this->getPriceString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
