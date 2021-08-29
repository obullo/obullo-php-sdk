<?php

namespace Obullo\Jwt;

use Obullo\Http\Request;
use Obullo\Utils\Random;
use Obullo\Jwt\Grants\GrantInterface;
use Obullo\Exception\TokenRequestOriginException;
use Obullo\Exception\TokenRequestRestException;

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
    const USER_ID = 'userId';
    const USER_NAME = 'username';
    const ORIGIN = 'origin';
    const GRANTS = 'grants';

    protected $accountId;
    protected $apiKey;
    protected $apiKeySecret;
    protected $identity;
    protected $verifyFile;
    protected $origin;
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

        // generate hashed user id from username
        //
        $this->hashUserId($identity);
    }

    public function setVerifyFile(string $verifyFile)
    {
        $this->verifyFile = $verifyFile;
    }

    public function getVerifyFile()
    {
        return $this->verifyFile;
    }

    public function setOrigin(string $domain)
    {
        $scheme = parse_url($domain, PHP_URL_SCHEME);
        if ($scheme != 'https') {
            throw new TokenRequestOriginException("Origin url protocol must be secure");
        }
        if (false == filter_var($domain, FILTER_VALIDATE_URL)) {
            throw new TokenRequestOriginException("Origin url is invalid");
        }
        $this->origin = $domain;
    }

    public function getOrigin() : string
    {
        return $this->origin;
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

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function addGrant(GrantInterface $grant)
    {
        $this->grants[] = $grant;
    }

    protected function hashUserId() : int
    {
        $random = new Random;
        $this->userId = $random->generateHash($this->getIdentity());
        return $this->userId;
    }

    public function send()
    {
        $payload = $this->createBody();
        $request = new Request;
        $request->setVerifyFile($this->getVerifyFile());
        $response = $request->post($payload);
        if (! empty($response['error'])) {
            throw new TokenRequestRestException($response['error']);
        }
        return new TokenResponse($response);
    }
    
    private function createBody() : array
    {
        $body = [
            Self::ACCOUNT_ID => $this->getAccountId(),
            Self::API_KEY => $this->getApiKey(),
            Self::API_KEY_SECRET => $this->getApiKeySecret(),
            Self::USER_NAME => $this->getIdentity(),
            Self::USER_ID => $this->getUserId(),
            Self::ORIGIN => $this->getOrigin(),
        ];
        foreach ($this->grants as $grant) {
            $body[Self::GRANTS][$grant->getGrantKey()] = $grant->getPayload();
        }
        return $body;
    }
}