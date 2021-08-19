<?php

namespace Obullo\Jwt;

use Obullo\Jwt\Grants\GrantInterface;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class TokenRequest
{
    protected $accountId;
    protected $apiKey;
    protected $apiKeySecret;
    protected $identity;
    protected $sslVerify = true;
    protected $body = array();
    protected $userIp;
    protected $userAgent;

    public function __construct(
        string $accountId,
        string $apiKey,
        string $apiKeySecret,
        string $identity
    )
    {
        $this->setAccountId($accountId);
        $this->setApiKey($apiKey);
        $this->setApiKeySecret($apiKeySecret);
        $this->setIdentity($identity);
    }

    public function sslVerify(bool $verify)
    {
        $this->sslVerify = $verify;
    }

    public function setAccountId(string $accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId() : string
    {
        return $this->accountId;
    }

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    public function setApiKeySecret(string $apiKeySecret)
    {
        $this->apiKeySecret = $apiKeySecret;
    }

    public function getApiKeySecret() : string
    {
        return $this->apiKeySecret;
    }

    public function setIdentity(string $identity)
    {
        $this->identity = $identity;
    }

    public function getIdentity() : string
    {
        return $this->identity;
    }

    public function setUserIp(string $ip)
    {
        $this->userIp = $ip;
    }

    public function getUserIp() : string
    {
        if ($this->userIp) {
            return $this->userIp;
        }
        $remoteAddress = new RemoteAddress();
        return $remoteAddress->getIpAddress();
    }

    public function setUserAgent(string $agent)
    {
        $this->userAgent = $agent;
    }

    public function getUserAgent() : string
    {
        if ($this->userAgent) {
            return $this->userAgent;
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }


    public function addGrant(GrantInterface $object)
    {

    }

    public function send()
    {

    }

    private function createGrants()
    {

    }

    private function createJsonBody()
    {
        $this->body = [
            'ACCOUNT_ID' => $this->getAccountId(),
            'API_KEY' => $this->getApiKey(),
            'API_KEY_SECRET' => $this->getApiKeySecret(),
            'username' => $this->getIdentity(),
            'ip' => $this->getUserIp(),
            'agent' => $this->getUserAgent(),
        ];
        $this->body['GRANTS']['video']['roomId'] = 1234567891011125;
    }


}