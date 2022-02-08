<?php

namespace App\Pdf;

use App\Service\SmartAssetsHelperService;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractReceiptInvoiceBuilderPdf
{
    protected TCPDFController $tcpdf;
    protected SmartAssetsHelperService $sahs;
    protected TranslatorInterface $ts;
    protected string $pwt;    // project web title
    protected string $bn;     // boss name
    protected string $bd;     // boss dni
    protected string $ba;     // boss address
    protected string $bc;     // boss city
    protected string $ib;     // IBAN bussines
    protected string $locale; // default locale useful in CLI

    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, TranslatorInterface $ts, ParameterBagInterface $pb)
    {
        $this->tcpdf = $tcpdf;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->pwt = $pb->get('project_web_title');
        $this->bn = $pb->get('boss_name');
        $this->bd = $pb->get('boss_dni');
        $this->ba = $pb->get('boss_address');
        $this->bc = $pb->get('boss_city');
        $this->ib = $pb->get('iban_business');
        $this->locale = $pb->get('default_locale');
    }

    protected function floatStringFormat($val): string
    {
        return number_format($val, 2, ',', '.');
    }

    protected function floatMoneyFormat($val): string
    {
        return $this->floatStringFormat($val).' €';
    }

    /**
     * Convert an hexadecimal color code to its RGB equivalent.
     *
     * @param string $hexStr         (hexadecimal color value)
     * @param bool   $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
     * @param string $seperator      (to separate RGB values. Applicable only if second parameter is true.)
     *
     * @return string|array|bool (depending on second parameter. Returns False if invalid hex color value)
     */
    protected function hex2RGBarray(string $hexStr, bool $returnAsString = false, string $seperator = ','): string | array | bool
    {
        $hexStr = preg_replace('/[^0-9A-Fa-f]/', '', $hexStr); // Gets a proper hex string
        $rgbArray = [];
        if (6 === strlen($hexStr)) { // if a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray[] = 0xFF & ($colorVal >> 0x10);
            $rgbArray[] = 0xFF & ($colorVal >> 0x8);
            $rgbArray[] = 0xFF & $colorVal;
        } elseif (3 === strlen($hexStr)) { // if shorthand notation, need some string manipulations
            $rgbArray[] = hexdec(str_repeat($hexStr[0], 2));
            $rgbArray[] = hexdec(str_repeat($hexStr[1], 2));
            $rgbArray[] = hexdec(str_repeat($hexStr[2], 2));
        } else {
            return false; // invalid hex color code
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    }
}
