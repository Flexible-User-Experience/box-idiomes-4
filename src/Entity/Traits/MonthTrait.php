<?php

namespace App\Entity\Traits;

use App\Enum\InvoiceYearMonthEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait MonthTrait
{
    #[ORM\Column(type: Types::INTEGER)]
    protected int $month = 0;

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getMonthNameString(): string
    {
        return InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$this->getMonth()];
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }
}
