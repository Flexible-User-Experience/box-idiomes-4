<?php

namespace App\Enum;

final class TeacherColorEnum
{
    public const int MAGENTA = 0;
    public const int BLUE = 1;
    public const int YELLOW = 2;
    public const int GREEN = 3;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::MAGENTA => 'color.magenta',
            self::BLUE => 'color.blue',
            self::YELLOW => 'color.yellow',
            self::GREEN => 'color.green',
        ];
    }
}
