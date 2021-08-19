<?php

namespace Obullo\Http;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class Client
{    
    const URL = 'https://token.obullo.local:3000/v1/getAccessToken';

    protected function getUrl()
    {
        return Self::Url;
    }



}