<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Repository\ProvinceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProvinceRepository::class)]
#[UniqueEntity(['code', 'country'])]
#[ORM\Table(name: 'province')]
class Province extends AbstractBase
{
    use NameTrait;

    #[ORM\Column(type: Types::STRING)]
    private string $code;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $country;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getCode().' · '.$this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
