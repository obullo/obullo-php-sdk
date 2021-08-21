<?php

namespace Obullo\Http;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class Request
{    
    const URL = 'https://token.obullo.local:3000/v1/getAccessToken';
    protected $verifyFile;

    protected static function getUrl()
    {
        return Self::URL;
    }

    public function setVerifyFile($certFile) 
    {
        $this->verifyFile = $certFile;
    }

    public function getVerifyFile()
    {
        return $this->verifyFile;
    }

    public function post(array $payload)
    {
        $client = new GuzzleClient(['verify' => $this->verifyFile]);
        $headers = [
            'Accept' => 'application/json',
        ];
        $response = $client->post(
            Self::getUrl(),
            [
                'headers' => $headers,
                'json' => $payload
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $result = json_decode($response->getBody(), true);
            return $result;
        }
    }

    public function get()
    {

    }
}