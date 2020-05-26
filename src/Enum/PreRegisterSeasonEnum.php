<?php

namespace App\Enum;

/**
 * PreRegisterSeasonEnum class.
 *
 * @category Enum
 */
class PreRegisterSeasonEnum
{
    const SEASON_JULY_2020 = 0;
    const SEASON_SEPTEMBER_2020 = 1;
    const SEASON_JULY_2021 = 2;
    const SEASON_SEPTEMBER_2021 = 3;
    const SEASON_JULY_2022 = 4;
    const SEASON_SEPTEMBER_2022 = 5;

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
        return array(
            self::SEASON_JULY_2020 => 'seasons.july_2020',
            self::SEASON_SEPTEMBER_2020 => 'seasons.september_2020',
            self::SEASON_JULY_2021 => 'seasons.july_2021',
            self::SEASON_SEPTEMBER_2021 => 'seasons.september_2021',
            self::SEASON_JULY_2022 => 'seasons.july_2022',
            self::SEASON_SEPTEMBER_2022 => 'seasons.september_2022',
        );
    }
}
