<?php

namespace App\Enum;

final class SchoolYearChoicesGeneratorEnum
{
    public static function getSchoolYearChoicesArray(): array
    {
        return array_flip(self::getReversedSchoolYearChoicesArray());
    }

    public static function getReversedSchoolYearChoicesArray(): array
    {
        $result = [];
        $now = new \DateTimeImmutable();
        $currentYear = (int) $now->format('Y');
        if (12 === (int) $now->format('m') && 15 < (int) $now->format('d')) {
            ++$currentYear;
        }
        $steps = $currentYear - ReceiptYearMonthEnum::APP_FIRST_YEAR + 1;
        for ($i = 0; $i < $steps; ++$i) {
            $year = $currentYear - $i;
            $result[(string) $year] = ((string) $year).'/'.((string) ($year + 1));
        }

        return $result;
    }
}
