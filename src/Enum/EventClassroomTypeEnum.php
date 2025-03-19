<?php

namespace App\Enum;

final class EventClassroomTypeEnum
{
    public const int CLASSROOM_1 = 0;
    public const int CLASSROOM_2 = 1;
    public const int CLASSROOM_3 = 2;
    public const int CLASSROOM_4 = 3;
    public const int CLASSROOM_5 = 4;
    public const int CLASSROOM_6 = 5;
    public const int CLASSROOM_7 = 6;
    public const int CLASSROOM_8 = 7;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::CLASSROOM_1 => 'backend.admin.event.classroom_1',
            self::CLASSROOM_2 => 'backend.admin.event.classroom_2',
            self::CLASSROOM_3 => 'backend.admin.event.classroom_3',
            self::CLASSROOM_4 => 'backend.admin.event.classroom_4',
            self::CLASSROOM_5 => 'backend.admin.event.classroom_5',
            self::CLASSROOM_7 => 'backend.admin.event.classroom_7',
            self::CLASSROOM_8 => 'backend.admin.event.classroom_8',
            self::CLASSROOM_6 => 'backend.admin.event.classroom_6',
        ];
    }

    public static function getTranslatedEnumArray(): array
    {
        return [
            self::CLASSROOM_1 => 'Aula 1',
            self::CLASSROOM_2 => 'Aula 2',
            self::CLASSROOM_3 => 'Aula 3',
            self::CLASSROOM_4 => 'Aula 4',
            self::CLASSROOM_5 => 'Aula 5',
            self::CLASSROOM_7 => 'Aula 6',
            self::CLASSROOM_8 => 'Aula Camarles 1',
            self::CLASSROOM_6 => 'Aula Online',
        ];
    }

    public static function getShortTranslatedEnumArray(): array
    {
        return [
            self::CLASSROOM_1 => '1',
            self::CLASSROOM_2 => '2',
            self::CLASSROOM_3 => '3',
            self::CLASSROOM_4 => '4',
            self::CLASSROOM_5 => '5',
            self::CLASSROOM_7 => '6',
            self::CLASSROOM_8 => 'Camarles 1',
            self::CLASSROOM_6 => 'Online',
        ];
    }
}
