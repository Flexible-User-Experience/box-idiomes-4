<?php

namespace App\Form\Model;

abstract class AbstractGenerateReceiptInvoiceModel
{
    protected int $year;
    protected int $month;

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
}
