<?php

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\CityTrait;
use App\Entity\Traits\NameTrait;
use App\Repository\TrainingCenterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TrainingCenterRepository::class)]
#[UniqueEntity(['name'])]
#[ORM\Table(name: 'training_center')]
class TrainingCenter extends AbstractBase
{
    use AddressTrait;
    use CityTrait;
    use NameTrait;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: City::class)]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id', nullable: false)]
    private ?City $city = null;

    public function __toString(): string
    {
        return $this->name ? $this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
