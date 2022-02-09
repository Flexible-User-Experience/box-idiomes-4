<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 * @ORM\Table(name="student")
 * @UniqueEntity({"name", "surname"})
 */
class Student extends AbstractPerson
{
    use BankCreditorSepaTrait;

    public const DISCOUNT_PER_EXTRA_SON = 5;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $birthDate = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $schedule = null;

    /**
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private ?string $comments = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="students")
     */
    private ?Person $parent = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank", cascade={"persist"})
     * @Assert\Valid
     */
    protected ?Bank $bank = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tariff")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id")
     */
    private ?Tariff $tariff = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="students")
     */
    private Collection $events;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private ?bool $hasImageRightsAccepted = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private ?bool $hasSepaAgreementAccepted = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private ?bool $isPaymentExempt = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private ?bool $hasAcceptedInternalRegulations = false;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getBirthDateString(): string
    {
        return $this->getBirthDate() ? $this->getBirthDate()->format('d/m/Y') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    public function setBirthDate(?DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getYearsOld(): int
    {
        $today = new DateTimeImmutable();

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

    public function calculateMonthlyTariff(): float
    {
        $price = $this->getTariff()->getPrice();
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? ((($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON) / $enabledSonsAmount) : 0;
            $price -= $discount;
        }

        return $price;
    }

    public function calculateMonthlyDiscount(): float
    {
        $discount = 0;
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? round(($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON / $enabledSonsAmount, 2) : 0;
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

    public function getMainEmailSubject(): string
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
