<?php

namespace App\Trait;

trait MockUtilsTrait
{
    public static function twoDigitWithZero(int $number): string
    {
        return str_pad($number, 2, "0", STR_PAD_LEFT);
    }
}
