<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use App\Entity\Traits\TrainingCenterTrait;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[UniqueEntity(['name', 'surname'])]
#[ORM\Table(name: 'student')]
class Student extends AbstractPerson
{
    use BankCreditorSepaTrait;
    use TrainingCenterTrait;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $schedule = null;

    #[ORM\Column(type: Types::TEXT, length: 4000, nullable: true)]
    private ?string $comments = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'students')]
    private ?Person $parent = null;

    #[ORM\ManyToOne(targetEntity: Bank::class, cascade: ['persist'])]
    #[Assert\Valid]
    protected ?Bank $bank = null;

    #[ORM\ManyToOne(targetEntity: Tariff::class)]
    #[ORM\JoinColumn(name: 'tariff_id', referencedColumnName: 'id')]
    private ?Tariff $tariff = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'students')]
    private Collection $events;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    private ?bool $hasImageRightsAccepted = false;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    private ?bool $hasSepaAgreementAccepted = false;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    private ?bool $isPaymentExempt = false;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    private ?bool $hasAcceptedInternalRegulations = false;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getBirthDateString(): string
    {
        return $this->getBirthDate() ? $this->getBirthDate()->format(AbstractBase::DATE_STRING_FORMAT) : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getYearsOld(): int
    {
        $today = new \DateTimeImmutable();

        return $today->diff($this->birthDate)->y;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(?string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getParent(): ?Person
    {
        return $this->parent;
    }

    public function setParent(?Person $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }

    public function setTariff(?Tariff $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function setEvents(Collection $events): self
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        return $this;
    }

    public function isHasImageRightsAccepted(): ?bool
    {
        return $this->hasImageRightsAccepted;
    }

    public function getHasImageRightsAccepted(): ?bool
    {
        return $this->isHasImageRightsAccepted();
    }

    public function setHasImageRightsAccepted(?bool $hasImageRightsAccepted): self
    {
        $this->hasImageRightsAccepted = $hasImageRightsAccepted;

        return $this;
    }

    public function isHasSepaAgreementAccepted(): ?bool
    {
        return $this->hasSepaAgreementAccepted;
    }

    public function getHasSepaAgreementAccepted(): ?bool
    {
        return $this->isHasSepaAgreementAccepted();
    }

    public function setHasSepaAgreementAccepted(?bool $hasSepaAgreementAccepted): self
    {
        $this->hasSepaAgreementAccepted = $hasSepaAgreementAccepted;

        return $this;
    }

    public function isPaymentExempt(): ?bool
    {
        return $this->isPaymentExempt;
    }

    public function getIsPaymentExempt(): ?bool
    {
        return $this->isPaymentExempt();
    }

    public function setIsPaymentExempt(?bool $isPaymentExempt): self
    {
        $this->isPaymentExempt = $isPaymentExempt;

        return $this;
    }

    public function getHasAcceptedInternalRegulations(): ?bool
    {
        return $this->hasAcceptedInternalRegulations;
    }

    public function isHasAcceptedInternalRegulations(): ?bool
    {
        return $this->getHasAcceptedInternalRegulations();
    }

    public function hasAcceptedInternalRegulations(): ?bool
    {
        return $this->getHasAcceptedInternalRegulations();
    }

    public function setHasAcceptedInternalRegulations(?bool $hasAcceptedInternalRegulations): self
    {
        $this->hasAcceptedInternalRegulations = $hasAcceptedInternalRegulations;

        return $this;
    }

    public function calculateMonthlyTariffWithExtraSonDiscount(int $discountExtraSon): float
    {
        $price = $this->getTariff()->getPrice();
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? ((($enabledSonsAmount - 1) * $discountExtraSon) / $enabledSonsAmount) : 0;
            $price -= $discount;
        }

        return $price;
    }

    public function calculateMonthlyDiscountWithExtraSonDiscount(int $discountExtraSon): float
    {
        $discount = 0;
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? round(($enabledSonsAmount - 1) * $discountExtraSon / $enabledSonsAmount, 2) : 0;
        }

        return $discount;
    }

    public function hasDiscount(): bool
    {
        if ($this->getParent()) {
            return $this->getParent()->getEnabledSonsAmount() > 1;
        }

        return false;
    }

    public function getMainEmailSubject(): ?string
    {
        $email = $this->getEmail();
        if ($this->getParent() && $this->getParent()->getEmail()) {
            $email = $this->getParent()->getEmail();
        }

        return $email;
    }

    public function canBeDeletedSafely(): bool
    {
        $result = false;
        if (is_null($this->getParent()) && 0 === count($this->getEvents())) {
            $result = true;
        }

        return $result;
    }
}
