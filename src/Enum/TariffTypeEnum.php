<?php

namespace App\Enum;

/**
 * Class TariffTypeEnum.
 *
 * @category Enum
 */
class TariffTypeEnum
{
    const TARIFF_ONE_HOUR_PER_WEEK = 0;
    const TARIFF_ONE_AND_A_HALF_HOUR_PER_WEEK = 3;
    const TARIFF_TWO_HOUR_PER_WEEK = 1;
    const TARIFF_TWO_AND_A_HALF_HOUR_PER_WEEK = 11;
    const TARIFF_THREE_HOUR_PER_WEEK = 2;
    const TARIFF_THREE_AND_A_HALF_HOUR_PER_WEEK = 6;
    const TARIFF_FOUR_HOUR_PER_WEEK = 7;
    const TARIFF_FOUR_AND_A_HALF_HOUR_PER_WEEK = 8;
    const TARIFF_FIVE_HOUR_PER_WEEK = 9;
    const TARIFF_FIVE_AND_A_HALF_HOUR_PER_WEEK = 10;
    const TARIFF_PRIVATE_LESSON_PER_HOUR = 4;
    const TARIFF_SHARED_PRIVATE_LESSON_PER_HOUR = 5;
    const TARIFF_SPECIAL_TYPE = 12;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array_flip(self::getReversedEnumArray());
    }

    /**
     * @return array
     */
    public static function getReversedEnumArray()
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
        ];
    }

    /**
     * @return array
     */
    public static function getTranslatedEnumArray()
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
        ];
    }
}
