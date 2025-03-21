<?php

namespace App\Entity;

use App\Enum\UserRolesEnum;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username')]
#[ORM\Table(name: 'admin_user')]
class User extends AbstractBase implements PasswordAuthenticatedUserInterface, UserInterface
{
    public const string DEFAULT_ROLE_USER = UserRolesEnum::ROLE_USER;

    #[ORM\ManyToOne(targetEntity: Teacher::class)]
    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id', nullable: true)]
    private ?Teacher $teacher = null;

    #[ORM\Column(name: 'username', type: Types::STRING, length: 180, unique: true, nullable: false)]
    private string $username;

    #[ORM\Column(name: 'username_canonical', type: Types::STRING, length: 180, unique: true, nullable: false)]
    private string $usernameCanonical;

    #[ORM\Column(name: 'email', type: Types::STRING, length: 180, nullable: false)]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(name: 'email_canonical', type: Types::STRING, length: 180, unique: true)]
    private string $emailCanonical;

    #[ORM\Column(name: 'firstname', type: Types::STRING, length: 64, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(name: 'lastname', type: Types::STRING, length: 64, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(name: 'phone', type: Types::STRING, length: 64, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(name: 'gender', type: Types::STRING, length: 1, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(name: 'locale', type: Types::STRING, length: 8, nullable: true)]
    private ?string $locale = null;

    #[ORM\Column(name: 'token', type: Types::STRING, length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(name: 'two_step_code', type: Types::STRING, length: 255, nullable: true)]
    private ?string $twoStepVerificationCode = null;

    #[ORM\Column(name: 'salt', type: Types::STRING, length: 255, nullable: true)]
    private ?string $salt = null;

    #[ORM\Column(name: 'password', type: Types::STRING, length: 255, nullable: false)]
    private string $password;

    private ?string $plainPassword = null;

    #[ORM\Column(name: 'confirmation_token', type: Types::STRING, length: 255, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Column(name: 'last_login', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\Column(name: 'roles', type: Types::JSON)]
    private ?array $roles = [];

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUsernameCanonical(): string
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical(string $usernameCanonical): self
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function getEmailCanonical(): string
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical(string $emailCanonical): self
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstname() && $this->getLastname() ? sprintf('%s %s', $this->getFirstname(), $this->getLastname()) : $this->getUsername();
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTwoStepVerificationCode(): ?string
    {
        return $this->twoStepVerificationCode;
    }

    public function setTwoStepVerificationCode(?string $twoStepVerificationCode): self
    {
        $this->twoStepVerificationCode = $twoStepVerificationCode;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::DEFAULT_ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        $this->roles[] = $role;

        return $this;
    }

    public function eraseCredentials(): self
    {
        $this->plainPassword = null;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId() ? $this->getUsername() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
