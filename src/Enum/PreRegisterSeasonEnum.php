<?php

namespace App\Enum;

final class PreRegisterSeasonEnum
{
    public const int SEASON_JULY_2020 = 0;
    public const int SEASON_SEPTEMBER_2020 = 1;
    public const int SEASON_JULY_2021 = 2;
    public const int SEASON_SEPTEMBER_2021 = 3;
    public const int SEASON_JULY_2022 = 4;
    public const int SEASON_SEPTEMBER_2022 = 5;
    public const int SEASON_JULY_2023 = 6;
    public const int SEASON_SEPTEMBER_2023 = 7;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::SEASON_JULY_2020 => 'seasons.july_2020',
            self::SEASON_SEPTEMBER_2020 => 'seasons.september_2020',
            self::SEASON_JULY_2021 => 'seasons.july_2021',
            self::SEASON_SEPTEMBER_2021 => 'seasons.september_2021',
            self::SEASON_JULY_2022 => 'seasons.july_2022',
            self::SEASON_SEPTEMBER_2022 => 'seasons.september_2022',
            self::SEASON_JULY_2023 => 'seasons.july_2023',
            self::SEASON_SEPTEMBER_2023 => 'seasons.september_2023',
        ];
    }
}
