<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="city")
 * @UniqueEntity({"postalCode", "province"})
 */
class City extends AbstractBase
{
    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string")
     */
    private string $postalCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Province")
     */
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
