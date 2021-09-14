<?php

namespace Obullo\Http;

use GuzzleHttp\Client as GuzzleClient;
use Obullo\Exception\TokenRequestCurlErrorException;
use Obullo\Exception\TokenRequestRestException;
use Obullo\Exception\TokenRequestVerifyFileException;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class Request
{    
    //  default port 8080 olacak her node içinde bu uygulama 8080 den çalışacak.
    //  https://token.obullo.com olacak ana adres, nginx load balancer ile 
    //  istekler dağıtılacak.
    //  
    //  PROD_URL: https://token.obullo.com/v1/getAccessToken
    //  
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
        if (function_exists('curl_version')) {
            $result = $this->curlPost($payload);
            return $result;
        }
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

    protected function curlPost(array $payload)
    {
        $result = array();
        $ch = curl_init(Self::getUrl()); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // it should be default 2, 1 no longer supported

        if (! empty($this->verifyFile) && ! file_exists($this->verifyFile)) {
            throw new TokenRequestVerifyFileException('Verify file does not exists');
        } else {
            curl_setopt($ch, CURLOPT_CAINFO, $this->verifyFile);
        }
        $res = curl_exec($ch);
        if ($res === false) {
            throw new TokenRequestCurlErrorException(curl_error($ch));
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $result = json_decode($res, true);
        } else {
            throw new TokenRequestRestException(
                sprintf(
                    'Cannot connect to the rest server. Status code: %s',
                    $statusCode
                )
            );
        }
        curl_close($ch);
        return $result; 
    }

    protected function curlGet()
    {

    }

}