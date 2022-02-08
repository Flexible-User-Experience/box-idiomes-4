<?php

namespace App\Entity;

use App\Enum\StudentPaymentEnum;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpendingRepository")
 * @Vich\Uploadable
 */
class Spending extends AbstractBase
{
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private DateTimeInterface $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpendingCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private ?SpendingCategory $category = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     */
    private ?Provider $provider = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private float $baseAmount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isPayed = null;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $paymentDate = null;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private int $paymentMethod = StudentPaymentEnum::BANK_ACCOUNT_NUMBER;

    /**
     * @Vich\UploadableField(mapping="spending", fileNameProperty="document")
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"application/pdf", "application/x-pdf"}
     * )
     */
    private ?File $documentFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $document = null;

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getDateString(): string
    {
        return self::convertDateAsString($this->getDate());
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCategory(): ?SpendingCategory
    {
        return $this->category;
    }

    public function setCategory(?SpendingCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBaseAmount(): float
    {
        return $this->baseAmount;
    }

    public function getBaseAmountString(): string
    {
        return number_format($this->baseAmount, 2, ',', '.');
    }

    public function getAmount(): float
    {
        return $this->getBaseAmount();
    }

    public function getAmountString(): string
    {
        return $this->getBaseAmountString();
    }

    public function setBaseAmount(float $baseAmount): self
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    public function isPayed(): ?bool
    {
        return $this->isPayed;
    }

    public function getPayed(): ?bool
    {
        return $this->isPayed();
    }

    public function setIsPayed(?bool $isPayed): self
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    public function getPaymentDate(): ?DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function getPaymentDateString(): string
    {
        return self::convertDateAsString($this->getPaymentDate());
    }

    public function setPaymentDate(?DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getPaymentMethod(): int
    {
        return $this->paymentMethod;
    }

    public function getPaymentMethodString(): string
    {
        return array_key_exists($this->getPaymentMethod(), StudentPaymentEnum::getEnumTranslatedArray()) ? StudentPaymentEnum::getEnumTranslatedArray()[$this->getPaymentMethod()] : self::DEFAULT_NULL_STRING;
    }

    public function setPaymentMethod(int $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    public function setDocumentFile(?File $documentFile = null): self
    {
        $this->documentFile = $documentFile;
        if ($documentFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable('now');
        }

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getDateString().' · '.$this->getProvider().' · '.$this->getBaseAmountString() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
