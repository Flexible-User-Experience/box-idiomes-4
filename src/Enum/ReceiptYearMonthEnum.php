<?php

namespace App\Enum;

use DateTimeImmutable;

class ReceiptYearMonthEnum
{
    public const int APP_FIRST_YEAR = 2017;

    public const int JANUARY = 1;
    public const int FEBRAURY = 2;
    public const int MARCH = 3;
    public const int APRIL = 4;
    public const int MAY = 5;
    public const int JUNE = 6;
    public const int JULY = 7;
    public const int AUGUST = 8;
    public const int SEPTEMBER = 9;
    public const int OCTOBER = 10;
    public const int NOVEMBER = 11;
    public const int DECEMBER = 12;

    public static function getMonthEnumArray(): array
    {
        return array_flip(self::getReversedMonthEnumArray());
    }

    public static function getReversedMonthEnumArray(): array
    {
        return [
            self::JANUARY => 'month.january',
            self::FEBRAURY => 'month.febraury',
            self::MARCH => 'month.march',
            self::APRIL => 'month.april',
            self::MAY => 'month.may',
            self::JUNE => 'month.june',
            self::JULY => 'month.july',
            self::AUGUST => 'month.august',
            self::SEPTEMBER => 'month.september',
            self::OCTOBER => 'month.october',
            self::NOVEMBER => 'month.november',
            self::DECEMBER => 'month.december',
        ];
    }

    public static function getTranslatedMonthEnumArray(): array
    {
        return [
            self::JANUARY => 'gener',
            self::FEBRAURY => 'febrer',
            self::MARCH => 'març',
            self::APRIL => 'abril',
            self::MAY => 'maig',
            self::JUNE => 'juny',
            self::JULY => 'juliol',
            self::AUGUST => 'agost',
            self::SEPTEMBER => 'setembre',
            self::OCTOBER => 'octubre',
            self::NOVEMBER => 'novembre',
            self::DECEMBER => 'desembre',
        ];
    }

    public static function getShortTranslatedMonthEnumArray(): array
    {
        return [
            self::JANUARY => 'gen',
            self::FEBRAURY => 'feb',
            self::MARCH => 'mar',
            self::APRIL => 'abr',
            self::MAY => 'mai',
            self::JUNE => 'jun',
            self::JULY => 'jul',
            self::AUGUST => 'ago',
            self::SEPTEMBER => 'set',
            self::OCTOBER => 'oct',
            self::NOVEMBER => 'nov',
            self::DECEMBER => 'des',
        ];
    }

    public static function getYearEnumArray(): array
    {
        return array_flip(self::getReversedYearEnumArray());
    }

    public static function getReversedYearEnumArray(): array
    {
        $result = [];
        $now = new DateTimeImmutable();
        $currentYear = (int) $now->format('Y');
        if (12 === (int) $now->format('m') && 15 < (int) $now->format('d')) {
            ++$currentYear;
        }
        $steps = $currentYear - self::APP_FIRST_YEAR + 1;
        for ($i = 0; $i < $steps; ++$i) {
            $year = $currentYear - $i;
            $result[(string) $year] = $year;
        }

        return $result;
    }
}
