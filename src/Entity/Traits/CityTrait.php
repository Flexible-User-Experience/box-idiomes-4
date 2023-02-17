<?php

namespace App\Entity\Traits;

use App\Entity\City;
use Doctrine\ORM\Mapping as ORM;

trait CityTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     *
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    protected ?City $city = null;

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
