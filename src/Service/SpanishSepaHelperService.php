<?php

namespace App\Service;

/**
 * Class SpanishSepaHelperService.
 *
 * @category Service
 */
class SpanishSepaHelperService
{
    public const string COUNTRY_CODE = 'ES';
    public const string SUFIX = '000';
    public const string MINFIX = '00';

    /**
     * @param string $nif
     */
    public function getSpanishCreditorIdFromNif($nif): string
    {
        $composition = $nif.self::COUNTRY_CODE.self::MINFIX;
        $conversionWithoutLetters = $this->letterToNumberConversion($composition);
        $controlDigits = $this->controlDigitsCalculation($conversionWithoutLetters);

        return self::COUNTRY_CODE.$controlDigits.self::SUFIX.$nif;
    }

    private function letterToNumberConversion(string $value): string
    {
        return str_replace(
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            ['10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35'],
            $value
        );
    }

    private function controlDigitsCalculation(string $value): string
    {
        $result = (int) $value;
        $result = 98 - ($result % 97);

        if ($result < 10) {
            $result = '0'.$result;
        } else {
            $result = (string) $result;
        }

        return $result;
    }
}
