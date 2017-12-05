<?php

namespace PayU\OpenPayU\AuthType;

use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;
use PayU\OpenPayU\Oauth\OauthResultClientCredentials;
use PayU\OpenPayU\Oauth as GrantOauth;

class Oauth implements AuthType
{
    /**
     * @var OauthResultClientCredentials
     */
    private $oauthResult;

    public function __construct($clientId, $clientSecret)
    {
        if (empty($clientId)) {
            throw new OpenPayUExceptionConfiguration('ClientId is empty');
        }

        if (empty($clientSecret)) {
            throw new OpenPayUExceptionConfiguration('ClientSecret is empty');
        }

        try {
            $this->oauthResult = GrantOauth::getAccessToken();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException('Oauth error: [code=' . $e->getCode() . '], [message=' . $e->getMessage() . ']');
        }

    }

    public function getHeaders()
    {
        return array(
            'Content-Type: application/json',
            'Accept: */*',
            'Authorization: Bearer ' . $this->oauthResult->getAccessToken()
        );
    }
}
