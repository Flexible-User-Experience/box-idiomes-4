<?php

namespace App\Entity;

use App\Entity\Traits\TrainingCenterTrait;
use App\Model\Color;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassGroupRepository")
 *
 * @ORM\Table(name="class_group")
 *
 * @UniqueEntity({"code"})
 */
class ClassGroup extends AbstractBase
{
    use TrainingCenterTrait;

    /**
     * @ORM\Column(type="string")
     */
    private string $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $book;

    /**
     * @ORM\Column(type="string")
     */
    private string $color;

    private ?Color $colorRgbArray = null;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private ?bool $isForPrivateLessons;

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCodeAndBook(): string
    {
        $result = '';
        if ($this->getCode()) {
            $result = $this->getCode();
            if ($this->getBook()) {
                $result .= ' ('.$this->getBook().')';
            }
        }

        return $result;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBook(): ?string
    {
        return $this->book;
    }

    public function setBook(?string $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        $this->colorRgbArray = new Color($color);

        return $this;
    }

    public function getColorRgbArray(): Color
    {
        if (!$this->colorRgbArray) {
            $this->colorRgbArray = new Color($this->color);
        }

        return $this->colorRgbArray;
    }

    public function setColorRgbArray(Color $colorRgbArray): self
    {
        $this->colorRgbArray = $colorRgbArray;

        return $this;
    }

    public function isForPrivateLessons(): ?bool
    {
        return $this->isForPrivateLessons;
    }

    public function getIsForPrivateLessons(): ?bool
    {
        return $this->isForPrivateLessons();
    }

    public function setIsForPrivateLessons(?bool $isForPrivateLessons): self
    {
        $this->isForPrivateLessons = $isForPrivateLessons;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getCode().($this->name ? ' · '.$this->getName() : '') : AbstractBase::DEFAULT_NULL_STRING;
    }
}
