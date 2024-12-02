<?php

namespace App\Entity;

use App\Repository\SpendingCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpendingCategoryRepository::class)]
class SpendingCategory extends AbstractBase
{
    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
