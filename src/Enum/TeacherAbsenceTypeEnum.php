<?php

namespace App\Enum;

final class TeacherAbsenceTypeEnum
{
    public const int PERSONAL_ISSUES = 0;
    public const int TRAINING = 1;
    public const int OTHER_ISSUES = 2;
    public const int HOLIDAYS = 3;
    public const int SICK_LEAVE = 4;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::PERSONAL_ISSUES => 'Assumptes personals',
            self::TRAINING => 'Formació',
            self::OTHER_ISSUES => 'Altres motius',
            self::HOLIDAYS => 'Vacances',
            self::SICK_LEAVE => 'Baixa laboral',
        ];
    }
}
