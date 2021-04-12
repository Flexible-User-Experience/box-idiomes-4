<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpendingCategoryRepository")
 */
class SpendingCategory extends AbstractBase
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
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
