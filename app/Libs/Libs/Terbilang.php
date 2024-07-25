<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs;

/**
 * Description of JamsostekCalculator
 *
 * @author blackhat
 */
class Terbilang
{
    private $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function toString() {
        $string = self::getTerbilang($this->number);

        return trim($string . ' rupiah');
    }

    public static function getTerbilang($number) {
        $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($number < 12)
            return " " . $angka[$number];
        else if ($number < 20)
            return self::getTerbilang($number - 10) . " belas";
        else if ($number < 100)
            return self::getTerbilang($number / 10) . " puluh" . self::getTerbilang($number % 10);
        else if ($number < 200)
            return "seratus" . self::getTerbilang($number - 100);
        else if ($number < 1000)
            return self::getTerbilang($number / 100) . " ratus" . self::getTerbilang($number % 100);
        else if ($number < 2000)
            return "seribu" . self::getTerbilang($number - 1000);
        else if ($number < 1000000)
            return self::getTerbilang($number / 1000) . " ribu" . self::getTerbilang($number % 1000);
        else if ($number < 1000000000)
            return self::getTerbilang($number / 1000000) . " juta" . self::getTerbilang($number % 1000000);
    }
}
