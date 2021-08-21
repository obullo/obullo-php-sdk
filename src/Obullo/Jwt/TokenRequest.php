<?php

namespace Obullo\Jwt;

use Obullo\Http\Request;
use Obullo\Http\RemoteAddress;
use Obullo\Jwt\Grants\GrantInterface;

/**
 * Copyright (C) 2022 Obullo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.
 */
class TokenRequest
{
    const ACCOUNT_ID = 'accountId';
    const API_KEY = 'apiKey';
    const API_KEY_SECRET = 'apiKeySecret';
    const USER_NAME = 'username';
    const USER_IP = 'userIp';
    const USER_AGENT = 'userAgent';
    const GRANTS = 'grants';

    protected $accountId;
    protected $apiKey;
    protected $apiKeySecret;
    protected $identity;
    protected $sslVerify = true;
    protected $remoteAddress;
    protected $userAgent;
    protected $grants = array();

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

    public function sslVerifyFile(string $verify)
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

    public function setRemoteAddress(RemoteAddress $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }

    public function getUserIp() : string
    {
        return $this->remoteAddress->getIpAddress();
    }

    public function setUserAgent(string $agent)
    {
        $this->userAgent = $agent;
    }

    public function getUserAgent() : string
    {
        if (! $this->userAgent) {
            $this->userAgent = trim($_SERVER['HTTP_USER_AGENT']);   
        }
        return substr($this->userAgent,0,255); // trim after 255 character
    }

    public function addGrant(GrantInterface $grant)
    {
        $this->grants[] = $grant;
    }

    public function send()
    {
        $payload = $this->createBody();
        $request = new Request;
        $result = $request->post($payload);

        return new TokenResponse($result);
    }
    
    private function createBody() : array
    {
        $body = [
            Self::ACCOUNT_ID => $this->getAccountId(),
            Self::API_KEY => $this->getApiKey(),
            Self::API_KEY_SECRET => $this->getApiKeySecret(),
            Self::USER_NAME => $this->getIdentity(),
            Self::USER_IP => $this->getUserIp(),
            Self::USER_AGENT => $this->getUserAgent(),
        ];
        foreach($this->grants as $grant) {
            $body[Self::GRANTS][$grant->getGrantKey()] = $grant->getPayload();
        }
        return $body;
    }
}