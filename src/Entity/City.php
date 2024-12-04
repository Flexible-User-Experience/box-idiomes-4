<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[UniqueEntity(['postalCode', 'province'])]
#[ORM\Table(name: 'city')]
class City extends AbstractBase
{
    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $postalCode;

    #[ORM\ManyToOne(targetEntity: Province::class)]
    private Province $province;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getProvince(): Province
    {
        return $this->province;
    }

    public function setProvince(Province $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCanonicalPostalString(): string
    {
        return $this->getPostalCode().' '.$this->getName().' ('.$this->getProvince()->getName().')';
    }

    public function __toString(): string
    {
        return $this->id ? $this->getPostalCode().' · '.$this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
