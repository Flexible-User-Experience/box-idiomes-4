<?php

namespace App\Model;

class Color
{
    private string $hexadecimalRgbCoordinates; // include hashbang
    private array $decimalRgbCoordinatesArray; // [red, green, blue]

    public function __construct($hexadecimalRgbCoordinates)
    {
        $this->hexadecimalRgbCoordinates = $hexadecimalRgbCoordinates;
        $this->decimalRgbCoordinatesArray = self::hex2rgb($hexadecimalRgbCoordinates);
    }

    public function getHexadecimalRgbCoordinates(): string
    {
        return $this->hexadecimalRgbCoordinates;
    }

    public function setHexadecimalRgbCoordinates(string $hexadecimalRgbCoordinates): self
    {
        $this->hexadecimalRgbCoordinates = $hexadecimalRgbCoordinates;

        return $this;
    }

    public function getDecimalRgbCoordinatesArray(): array
    {
        return $this->decimalRgbCoordinatesArray;
    }

    public function setDecimalRgbCoordinatesArray(array $decimalRgbCoordinatesArray): self
    {
        $this->decimalRgbCoordinatesArray = $decimalRgbCoordinatesArray;

        return $this;
    }

    public function getRedDecimalValue(): int
    {
        return $this->getDecimalRgbCoordinatesArray()[0];
    }

    public function getGreenDecimalValue(): int
    {
        return $this->getDecimalRgbCoordinatesArray()[1];
    }

    public function getBlueDecimalValue(): int
    {
        return $this->getDecimalRgbCoordinatesArray()[2];
    }

    public static function hex2rgb($hex): array
    {
        $hex = str_replace('#', '', $hex);
        if (3 === strlen($hex)) {
            $r = hexdec($hex[0]);
            $g = hexdec($hex[1]);
            $b = hexdec($hex[2]);
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return [$r, $g, $b];
    }
}
