<?php

namespace App\Entity;

use App\Enum\UserRolesEnum;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="admin_user")
 * @UniqueEntity("username")
 */
class User extends AbstractBase implements PasswordAuthenticatedUserInterface, UserInterface
{
    public const DEFAULT_ROLE_USER = UserRolesEnum::ROLE_USER;

    /**
     * @ORM\Column(name="username", type="string", length=180, nullable=false, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(name="username_canonical", type="string", length=180, nullable=false, unique=true)
     */
    private string $usernameCanonical;

    /**
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     * @Assert\Email()
     */
    private string $email;

    /**
     * @ORM\Column(name="email_canonical", type="string", length=180, unique=true)
     */
    private string $emailCanonical;

    /**
     * @ORM\Column(name="firstname", type="string", length=64, nullable=true)
     */
    private ?string $firstname = null;

    /**
     * @ORM\Column(name="lastname", type="string", length=64, nullable=true)
     */
    private ?string $lastname = null;

    /**
     * @ORM\Column(name="phone", type="string", length=64, nullable=true)
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(name="website", type="string", length=64, nullable=true)
     */
    private ?string $website = null;

    /**
     * @ORM\Column(name="timezone", type="string", length=64, nullable=true)
     */
    private ?string $timezone = null;

    /**
     * @ORM\Column(name="facebook_uid", type="string", length=255, nullable=true)
     */
    private ?string $facebookUid = null;

    /**
     * @ORM\Column(name="facebook_name", type="string", length=255, nullable=true)
     */
    private ?string $facebookName = null;

    /**
     * @ORM\Column(name="facebook_data", type="json", nullable=true)
     */
    private ?array $facebookData = null;

    /**
     * @ORM\Column(name="twitter_uid", type="string", length=255, nullable=true)
     */
    private ?string $twitterUid = null;

    /**
     * @ORM\Column(name="twitter_name", type="string", length=255, nullable=true)
     */
    private ?string $twitterName = null;

    /**
     * @ORM\Column(name="twitter_data", type="json", nullable=true)
     */
    private ?array $twitterkData = null;

    /**
     * @ORM\Column(name="gplus_uid", type="string", length=255, nullable=true)
     */
    private ?string $gplusUid = null;

    /**
     * @ORM\Column(name="gplus_name", type="string", length=255, nullable=true)
     */
    private ?string $gplusName = null;

    /**
     * @ORM\Column(name="gplus_data", type="json", nullable=true)
     */
    private ?array $gplusData = null;

    /**
     * @ORM\Column(name="biography", type="string", length=1000, nullable=true)
     */
    private ?string $biography = null;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private ?string $gender = null;

    /**
     * @ORM\Column(name="locale", type="string", length=8, nullable=true)
     */
    private ?string $locale = null;

    /**
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(name="two_step_code", type="string", length=255, nullable=true)
     */
    private ?string $twoStepVerificationCode = null;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private string $salt;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private string $password;

    private ?string $plainPassword = null;

    /**
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private ?string $confirmationToken;

    /**
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $passwordRequestedAt;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $lastLogin;

    /**
     * @ORM\Column(name="password_changed_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $passwordChangedAt;

    /**
     * @ORM\Column(name="roles", type="json")
     */
    private array $roles = [];

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
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

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
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
        $this->setUpdatedAt(new DateTimeImmutable());
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

    public function getPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?DateTimeInterface $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getPasswordChangedAt(): ?DateTimeInterface
    {
        return $this->passwordChangedAt;
    }

    public function setPasswordChangedAt(?DateTimeInterface $passwordChangedAt): self
    {
        $this->passwordChangedAt = $passwordChangedAt;

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
        return $this->getFirstname() && $this->getLastname() ? $this->getFirstname().' '.$this->getLastname() : $this->getUsername();
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getFacebookUid(): ?string
    {
        return $this->facebookUid;
    }

    public function setFacebookUid(?string $facebookUid): self
    {
        $this->facebookUid = $facebookUid;

        return $this;
    }

    public function getFacebookName(): ?string
    {
        return $this->facebookName;
    }

    public function setFacebookName(?string $facebookName): self
    {
        $this->facebookName = $facebookName;

        return $this;
    }

    public function getFacebookData(): ?array
    {
        return $this->facebookData;
    }

    public function setFacebookData(?array $facebookData): self
    {
        $this->facebookData = $facebookData;

        return $this;
    }

    public function getTwitterUid(): ?string
    {
        return $this->twitterUid;
    }

    public function setTwitterUid(?string $twitterUid): self
    {
        $this->twitterUid = $twitterUid;

        return $this;
    }

    public function getTwitterName(): ?string
    {
        return $this->twitterName;
    }

    public function setTwitterName(?string $twitterName): self
    {
        $this->twitterName = $twitterName;

        return $this;
    }

    public function getTwitterkData(): ?array
    {
        return $this->twitterkData;
    }

    public function setTwitterkData(?array $twitterkData): self
    {
        $this->twitterkData = $twitterkData;

        return $this;
    }

    public function getGplusUid(): ?string
    {
        return $this->gplusUid;
    }

    public function setGplusUid(?string $gplusUid): self
    {
        $this->gplusUid = $gplusUid;

        return $this;
    }

    public function getGplusName(): ?string
    {
        return $this->gplusName;
    }

    public function setGplusName(?string $gplusName): self
    {
        $this->gplusName = $gplusName;

        return $this;
    }

    public function getGplusData(): ?array
    {
        return $this->gplusData;
    }

    public function setGplusData(?array $gplusData): self
    {
        $this->gplusData = $gplusData;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
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

    public function setRoles(array $roles): self
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
