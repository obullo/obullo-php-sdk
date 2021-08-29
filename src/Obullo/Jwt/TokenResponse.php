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
    const TOKEN = 'token';

    public function __construct(array $response)
    {
        $this->response = $response;
    }
    public function getJWT()
    {
        return (string)$this->response[Self::TOKEN];
    }

}