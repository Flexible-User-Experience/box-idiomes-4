<?php

namespace App\Entity;

use App\Repository\NewsletterContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterContactRepository::class)]
#[ORM\Table]
class NewsletterContact extends AbstractBase
{
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $email;

    private bool $privacy;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isPrivacy(): bool
    {
        return $this->privacy;
    }

    public function setPrivacy(bool $privacy): self
    {
        $this->privacy = $privacy;

        return $this;
    }
}
