<?php

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\CityTrait;
use App\Entity\Traits\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingCenterRepository")
 *
 * @ORM\Table(name="training_center")
 *
 * @UniqueEntity({"name"})
 */
class TrainingCenter extends AbstractBase
{
    use AddressTrait;
    use CityTrait;
    use NameTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     *
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    private ?City $city = null;

    public function __toString(): string
    {
        return $this->name ? $this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
