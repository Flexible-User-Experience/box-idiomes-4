<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $schedule;

    /**
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private ?string $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="students")
     */
    private ?Person $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank", cascade={"persist"})
     * @Assert\Valid
     */
    protected ?Bank $bank = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tariff")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id")
     */
    private ?Tariff $tariff;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="students")
     */
    private $events;

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

    public function getBirthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getBirthDateString(): string
    {
        return $this->getBirthDate() ? $this->getBirthDate()->format('d/m/Y') : AbstractBase::DEFAULT_NULL_DATE_STRING;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    public function getYearsOld()
    {
        $today = new \DateTime();
        $interval = $today->diff($this->birthDate);

        return $interval->y;
    }

    /**
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     *
     * @return Student
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     *
     * @return Student
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Person
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Person $parent
     *
     * @return Student
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Tariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param Tariff $tariff
     *
     * @return Student
     */
    public function setTariff($tariff)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     *
     * @return Student
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(Event $event)
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }
    }

    public function removeEvent(Event $event)
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }
    }

    /**
     * @return bool
     */
    public function isHasImageRightsAccepted()
    {
        return $this->hasImageRightsAccepted;
    }

    /**
     * @return bool
     */
    public function getHasImageRightsAccepted()
    {
        return $this->isHasImageRightsAccepted();
    }

    /**
     * @param bool $hasImageRightsAccepted
     *
     * @return $this
     */
    public function setHasImageRightsAccepted($hasImageRightsAccepted)
    {
        $this->hasImageRightsAccepted = $hasImageRightsAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasSepaAgreementAccepted()
    {
        return $this->hasSepaAgreementAccepted;
    }

    /**
     * @return bool
     */
    public function getHasSepaAgreementAccepted()
    {
        return $this->isHasSepaAgreementAccepted();
    }

    /**
     * @param bool $hasSepaAgreementAccepted
     *
     * @return $this
     */
    public function setHasSepaAgreementAccepted($hasSepaAgreementAccepted)
    {
        $this->hasSepaAgreementAccepted = $hasSepaAgreementAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaymentExempt()
    {
        return $this->isPaymentExempt;
    }

    /**
     * @return bool
     */
    public function getIsPaymentExempt()
    {
        return $this->isPaymentExempt();
    }

    /**
     * @param bool $isPaymentExempt
     *
     * @return $this
     */
    public function setIsPaymentExempt($isPaymentExempt)
    {
        $this->isPaymentExempt = $isPaymentExempt;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasAcceptedInternalRegulations()
    {
        return $this->hasAcceptedInternalRegulations;
    }

    /**
     * @return bool
     */
    public function isHasAcceptedInternalRegulations()
    {
        return $this->getHasAcceptedInternalRegulations();
    }

    /**
     * @return bool
     */
    public function hasAcceptedInternalRegulations()
    {
        return $this->getHasAcceptedInternalRegulations();
    }

    /**
     * @param bool $hasAcceptedInternalRegulations
     *
     * @return $this
     */
    public function setHasAcceptedInternalRegulations($hasAcceptedInternalRegulations)
    {
        $this->hasAcceptedInternalRegulations = $hasAcceptedInternalRegulations;

        return $this;
    }

    /**
     * @return float
     */
    public function calculateMonthlyTariff()
    {
        $price = $this->getTariff()->getPrice();
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? ((($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON) / $enabledSonsAmount) : 0;
            $price = $price - $discount;
        }

        return $price;
    }

    /**
     * @return float|int
     */
    public function calculateMonthlyDiscount()
    {
        $discount = 0;
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? round(($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON / $enabledSonsAmount, 2) : 0;
        }

        return $discount;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        if ($this->getParent()) {
            return $this->getParent()->getEnabledSonsAmount() > 1 ? true : false;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getMainEmailSubject()
    {
        $email = $this->getEmail();
        if ($this->getParent() && $this->getParent()->getEmail()) {
            $email = $this->getParent()->getEmail();
        }

        return $email;
    }

    public function canBeDeletedSafely()
    {
        $result = false;
        if (is_null($this->getParent()) && 0 === count($this->getEvents())) {
            $result = true;
        }

        return $result;
    }
}
