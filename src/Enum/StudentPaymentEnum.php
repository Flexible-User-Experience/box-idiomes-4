<?php

namespace App\Enum;

class StudentPaymentEnum
{
    public const BANK_ACCOUNT_NUMBER = 0;
    public const CASH = 1;
    public const BANK_TRANSFER = 2;

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
    {
        return [
            self::BANK_ACCOUNT_NUMBER => 'backend.admin.student.bank',
            self::CASH => 'backend.admin.student.cash',
            self::BANK_TRANSFER => 'backend.admin.student.transfer',
        ];
    }

    public static function getEnumTranslatedArray(): array
    {
        return [
            self::BANK_ACCOUNT_NUMBER => 'gir bancari',
            self::CASH => 'efectiu',
            self::BANK_TRANSFER => 'transferencia',
        ];
    }
}
