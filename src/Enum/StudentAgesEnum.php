<?php

namespace App\Enum;

class StudentAgesEnum
{
    private const AGE_4 = 4;
    private const AGE_5 = 5;
    private const AGE_6 = 6;
    private const AGE_7 = 7;
    private const AGE_8 = 8;
    private const AGE_9 = 9;
    private const AGE_10 = 10;
    private const AGE_11 = 11;
    private const AGE_12 = 12;
    private const AGE_13 = 13;
    private const AGE_14 = 14;
    private const AGE_15 = 15;
    private const AGE_16 = 16;
    private const AGE_17 = 17;
    private const AGE_18 = 18;
    private const AGE_19 = 19;
    private const AGE_20 = 20;
    private const AGE_21 = 21;
    private const AGE_22 = 22;
    private const AGE_23 = 23;
    private const AGE_24 = 24;
    private const AGE_25 = 25;
    private const AGE_26 = 26;
    private const AGE_27 = 27;
    private const AGE_28 = 28;
    private const AGE_29 = 29;
    private const AGE_30 = 30;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
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
            self::AGE_20 => 'backend.admin.student.age_20',
            self::AGE_21 => 'backend.admin.student.age_21',
            self::AGE_22 => 'backend.admin.student.age_22',
            self::AGE_23 => 'backend.admin.student.age_23',
            self::AGE_24 => 'backend.admin.student.age_24',
            self::AGE_25 => 'backend.admin.student.age_25',
            self::AGE_26 => 'backend.admin.student.age_26',
            self::AGE_27 => 'backend.admin.student.age_27',
            self::AGE_28 => 'backend.admin.student.age_28',
            self::AGE_29 => 'backend.admin.student.age_29',
            self::AGE_30 => 'backend.admin.student.age_30',
        ];
    }

    public static function getReversedEnumTranslatedArray(): array
    {
        return array_flip(self::getEnumTranslatedArray());
    }

    public static function getEnumTranslatedArray(): array
    {
        return [
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
            self::AGE_20 => '20 anys',
            self::AGE_21 => '21 anys',
            self::AGE_22 => '22 anys',
            self::AGE_23 => '23 anys',
            self::AGE_24 => '24 anys',
            self::AGE_25 => '25 anys',
            self::AGE_26 => '26 anys',
            self::AGE_27 => '27 anys',
            self::AGE_28 => '28 anys',
            self::AGE_29 => '29 anys',
            self::AGE_30 => '30 anys',
        ];
    }
}
