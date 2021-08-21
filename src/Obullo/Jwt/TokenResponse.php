<?php

namespace Obullo\Jwt;

use Obullo\Jwt\Grants\GrantInterface;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class TokenResponse
{
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function getHostname()
    {
        return (string)$this->result['host'];
    }

    public function getJWT()
    {
        return (string)$this->result['token'];
    }

}