<?php

namespace App\Model;

/**
 * Dummy class File to use as a faked entity. (Only for FileManager integration backend pourpose).
 */
class FileDummy
{
    private ?string $name;

    public function __construct($name)
    {
        $this->name = $name;
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

    public function __toString(): string
    {
        return $this->name ? $this->getName() : '---';
    }
}
