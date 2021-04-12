<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class City.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="city")
 * @UniqueEntity({"postalCode", "province"})
 */
class City extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $postalCode;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Province")
     */
    private $province;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     *
     * @return City
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return Province
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param Province $province
     *
     * @return City
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @return string
     */
    public function getCanonicalPostalString()
    {
        return $this->getPostalCode().' '.$this->getName().' ('.$this->getProvince()->getName().')';
    }

    public function __toString(): string
    {
        return $this->id ? $this->getPostalCode().' · '.$this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
