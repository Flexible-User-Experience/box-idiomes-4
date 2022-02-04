<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

abstract class AbstractBase
{
    public const DEFAULT_NULL_STRING = '---';
    public const DEFAULT_NULL_DATE_STRING = '--/--/----';
    public const DEFAULT_NULL_DATETIME_STRING = '--/--/---- --:--';
    public const DATE_STRING_FORMAT = 'd/m/Y';
    public const DATETIME_STRING_FORMAT = 'd/m/Y H:i';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected ?DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $enabled = true;

    public function getId()
    {
        return $this->id;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAtString(): string
    {
        return self::convertDateTimeAsString($this->getCreatedAt());
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtString(): string
    {
        return self::convertDateTimeAsString($this->getUpdatedAt());
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function isEnabled(): bool
    {
        return $this->getEnabled();
    }

    public static function convertDateAsString(?DateTimeInterface $date): string
    {
        return $date ? $date->format(self::DATE_STRING_FORMAT) : self::DEFAULT_NULL_DATE_STRING;
    }

    public static function convertDateTimeAsString(?DateTimeInterface $date): string
    {
        return $date ? $date->format(self::DATETIME_STRING_FORMAT) : self::DEFAULT_NULL_DATETIME_STRING;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getId().' · '.$this->getCreatedAtString() : self::DEFAULT_NULL_STRING;
    }
}
