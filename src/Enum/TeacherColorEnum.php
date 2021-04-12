<?php

namespace App\Enum;

class TeacherColorEnum
{
    public const MAGENTA = 0;
    public const BLUE = 1;
    public const YELLOW = 2;
    public const GREEN = 3;

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
