<?php

namespace Obullo\Utils;

use function intval;
use function random_int;
use function strlen;
use function sprintf;
use function ord;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class Random
{
    /**
     * Generate secure random integer (12-19 characters)
     * 
     * start value = 100000000000
     * end value = 9223372036854775807
     * 
     * @return int
     */
    public function generateInteger() : int
    {
        $randomInteger = random_int(100000000000,PHP_INT_MAX);
        return intval($randomInteger);
    }

    /**
     * Generate crc64 hashed integer from string
     * 
     * @param   $str
     * @return string
     */
    public function generateHash($str) : int
    {
        $value = Self::crc64($str, '%u');
        return intval($value);
    }

    /**
    * @param string $string
    * @param string $format
    * @return mixed
    *
    * Formats:
    *  crc64('php'); // afe4e823e7cef190
    *  crc64('php', '0x%x'); // 0xafe4e823e7cef190
    *  crc64('php', '0x%X'); // 0xAFE4E823E7CEF190
    *  crc64('php', '%d'); // -5772233581471534704 signed int
    *  crc64('php', '%u'); // 12674510492238016912 unsigned int
    */
    protected static function crc64($string, $format = '%x')
    {
        static $crc64tab;
        if ($crc64tab === null) {
            $crc64tab = Self::crc64Table();
        }
        $crc = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            $crc = $crc64tab[($crc ^ ord($string[$i])) & 0xff] ^ (($crc >> 8) & ~(0xff << 56));
        }
        return sprintf($format, $crc);
    }

    /**
    * @return array
    */
    protected static function crc64Table()
    {
        $crc64tab = [];
        // ECMA polynomial
        $poly64rev = (0xC96C5795 << 32) | 0xD7870F42;
        // ISO polynomial
        // $poly64rev = (0xD8 << 56);
        for ($i = 0; $i < 256; $i++) {
            for ($part = $i, $bit = 0; $bit < 8; $bit++) {
                if ($part & 1) {
                    $part = (($part >> 1) & ~(0x8 << 60)) ^ $poly64rev;
                } else {
                    $part = ($part >> 1) & ~(0x8 << 60);
                }
            }
           $crc64tab[$i] = $part;
        }
        return $crc64tab;
    }
}