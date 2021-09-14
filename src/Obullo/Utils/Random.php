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
     * Generate secure random integer (8-19 characters)
     * 
     * start value = 10000000
     * end value = 9223372036854775
     * 
     * @return int
     */
    public function generateInteger() : int
    {
        $randomInteger = random_int(10000000, PHP_INT_MAX / 1000);
        return intval($randomInteger);
    }
}