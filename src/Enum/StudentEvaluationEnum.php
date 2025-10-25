<?php

namespace App\Enum;

final class StudentEvaluationEnum
{
    public const int FIRST_TRIMESTER = 1;
    public const int SECOND_TRIMESTER = 2;
    public const int THRID_TRIMESTER = 3;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::FIRST_TRIMESTER => 'trimesters.first',
            self::SECOND_TRIMESTER => 'trimesters.second',
            self::THRID_TRIMESTER => 'trimesters.third',
        ];
    }
}
