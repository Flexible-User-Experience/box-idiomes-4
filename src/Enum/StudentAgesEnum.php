<?php

namespace App\Enum;

final class StudentAgesEnum
{
    private const int AGE_3 = 3;
    private const int AGE_4 = 4;
    private const int AGE_5 = 5;
    private const int AGE_6 = 6;
    private const int AGE_7 = 7;
    private const int AGE_8 = 8;
    private const int AGE_9 = 9;
    private const int AGE_10 = 10;
    private const int AGE_11 = 11;
    private const int AGE_12 = 12;
    private const int AGE_13 = 13;
    private const int AGE_14 = 14;
    private const int AGE_15 = 15;
    private const int AGE_16 = 16;
    private const int AGE_17 = 17;
    private const int AGE_18 = 18;
    private const int AGE_19 = 19;
    public const int AGE_20_plus = 20;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::AGE_3 => 'backend.admin.student.age_3',
            self::AGE_4 => 'backend.admin.student.age_4',
            self::AGE_5 => 'backend.admin.student.age_5',
            self::AGE_6 => 'backend.admin.student.age_6',
            self::AGE_7 => 'backend.admin.student.age_7',
            self::AGE_8 => 'backend.admin.student.age_8',
            self::AGE_9 => 'backend.admin.student.age_9',
            self::AGE_10 => 'backend.admin.student.age_10',
            self::AGE_11 => 'backend.admin.student.age_11',
            self::AGE_12 => 'backend.admin.student.age_12',
            self::AGE_13 => 'backend.admin.student.age_13',
            self::AGE_14 => 'backend.admin.student.age_14',
            self::AGE_15 => 'backend.admin.student.age_15',
            self::AGE_16 => 'backend.admin.student.age_16',
            self::AGE_17 => 'backend.admin.student.age_17',
            self::AGE_18 => 'backend.admin.student.age_18',
            self::AGE_19 => 'backend.admin.student.age_19',
            self::AGE_20_plus => 'backend.admin.student.age_20_plus',
        ];
    }

    public static function getReversedEnumTranslatedArray(): array
    {
        return array_flip(self::getEnumTranslatedArray());
    }

    public static function getEnumTranslatedArray(): array
    {
        return [
            self::AGE_3 => '3 anys',
            self::AGE_4 => '4 anys',
            self::AGE_5 => '5 anys',
            self::AGE_6 => '6 anys',
            self::AGE_7 => '7 anys',
            self::AGE_8 => '8 anys',
            self::AGE_9 => '9 anys',
            self::AGE_10 => '10 anys',
            self::AGE_11 => '11 anys',
            self::AGE_12 => '12 anys',
            self::AGE_13 => '13 anys',
            self::AGE_14 => '14 anys',
            self::AGE_15 => '15 anys',
            self::AGE_16 => '16 anys',
            self::AGE_17 => '17 anys',
            self::AGE_18 => '18 anys',
            self::AGE_19 => '19 anys',
            self::AGE_20_plus => '20 anys o més',
        ];
    }
}
