<?php

namespace App\Enum;

final class TariffTypeEnum
{
    public const int TARIFF_ONE_HOUR_PER_WEEK = 0;
    public const int TARIFF_ONE_AND_A_HALF_HOUR_PER_WEEK = 3;
    public const int TARIFF_TWO_HOUR_PER_WEEK = 1;
    public const int TARIFF_TWO_AND_A_HALF_HOUR_PER_WEEK = 11;
    public const int TARIFF_THREE_HOUR_PER_WEEK = 2;
    public const int TARIFF_THREE_AND_A_HALF_HOUR_PER_WEEK = 6;
    public const int TARIFF_FOUR_HOUR_PER_WEEK = 7;
    public const int TARIFF_FOUR_AND_A_HALF_HOUR_PER_WEEK = 8;
    public const int TARIFF_FIVE_HOUR_PER_WEEK = 9;
    public const int TARIFF_FIVE_AND_A_HALF_HOUR_PER_WEEK = 10;
    public const int TARIFF_PRIVATE_LESSON_PER_HOUR = 4;
    public const int TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR = 5;
    public const int TARIFF_SPECIAL_TYPE = 12;
    public const int TARIFF_ONE_WEEK_INTESIVE = 13;
    public const int TARIFF_TWO_WEEKS_INTESIVE = 14;
    public const int TARIFF_THREE_WEEKS_INTESIVE = 15;
    public const int TARIFF_FOUR_FIVE_WEEKS_INTESIVE = 16;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::TARIFF_ONE_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_1',
            self::TARIFF_ONE_AND_A_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_4',
            self::TARIFF_TWO_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_2',
            self::TARIFF_TWO_AND_A_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_12',
            self::TARIFF_THREE_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_3',
            self::TARIFF_THREE_AND_A_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_7',
            self::TARIFF_FOUR_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_8',
            self::TARIFF_FOUR_AND_A_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_9',
            self::TARIFF_FIVE_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_10',
            self::TARIFF_FIVE_AND_A_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_11',
            self::TARIFF_PRIVATE_LESSON_PER_HOUR => 'backend.admin.tariff.tariff_5',
            self::TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR => 'backend.admin.tariff.tariff_6',
            self::TARIFF_SPECIAL_TYPE => 'backend.admin.tariff.tariff_12',
            self::TARIFF_ONE_WEEK_INTESIVE => 'backend.admin.tariff.tariff_13',
            self::TARIFF_TWO_WEEKS_INTESIVE => 'backend.admin.tariff.tariff_14',
            self::TARIFF_THREE_WEEKS_INTESIVE => 'backend.admin.tariff.tariff_15',
            self::TARIFF_FOUR_FIVE_WEEKS_INTESIVE => 'backend.admin.tariff.tariff_16',
        ];
    }

    public static function getTranslatedEnumArray(): array
    {
        return [
            self::TARIFF_ONE_HOUR_PER_WEEK => '1h /setmana',
            self::TARIFF_ONE_AND_A_HALF_HOUR_PER_WEEK => '1,5h / setmana',
            self::TARIFF_TWO_HOUR_PER_WEEK => '2h / setmana',
            self::TARIFF_TWO_AND_A_HALF_HOUR_PER_WEEK => '2,5h / setmana',
            self::TARIFF_THREE_HOUR_PER_WEEK => '3h / setmana',
            self::TARIFF_THREE_AND_A_HALF_HOUR_PER_WEEK => '3,5h / setmana',
            self::TARIFF_FOUR_HOUR_PER_WEEK => '4h / setmana',
            self::TARIFF_FOUR_AND_A_HALF_HOUR_PER_WEEK => '4,5h / setmana',
            self::TARIFF_FIVE_HOUR_PER_WEEK => '5h / setmana',
            self::TARIFF_FIVE_AND_A_HALF_HOUR_PER_WEEK => '5,5h / setmana',
            self::TARIFF_PRIVATE_LESSON_PER_HOUR => 'hora particular',
            self::TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR => 'hora particular compartida',
            self::TARIFF_SPECIAL_TYPE => 'preu especial',
            self::TARIFF_ONE_WEEK_INTESIVE => 'intensiu 1 setmana',
            self::TARIFF_TWO_WEEKS_INTESIVE => 'intensiu 2 setmanes',
            self::TARIFF_THREE_WEEKS_INTESIVE => 'intensiu 3 setmanes',
            self::TARIFF_FOUR_FIVE_WEEKS_INTESIVE => 'intensiu 4/5 setmanes',
        ];
    }
}
