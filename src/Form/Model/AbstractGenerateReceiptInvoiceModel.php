<?php

namespace App\Form\Model;

use App\Entity\TrainingCenter;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractGenerateReceiptInvoiceModel
{
    protected ?int $year = null;
    protected ?int $month = null;
    protected ?TrainingCenter $trainingCenter = null;
    protected ArrayCollection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getTrainingCenter(): ?TrainingCenter
    {
        return $this->trainingCenter;
    }

    public function setTrainingCenter(?TrainingCenter $trainingCenter): self
    {
        $this->trainingCenter = $trainingCenter;

        return $this;
    }

    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getTotalAmount(): float
    {
        $result = 0.0;
        /** @var GenerateInvoiceItemModel $item */
        foreach ($this->getItems() as $item) {
            $result += $item->getTotal();
        }

        return $result;
    }
}
