<?php

class AuthType_Oauth implements AuthType
{
    /**
     * @var OauthResultClientCredentials
     */
    private $oauthResult;

    public function __construct($clientId, $clientSecret)
    {
        if (empty($clientId)) {
            throw new OpenPayU_Exception_Configuration('ClientId is empty');
        }

        if (empty($clientSecret)) {
            throw new OpenPayU_Exception_Configuration('ClientSecret is empty');
        }

        try {
            $this->oauthResult = OpenPayU_Oauth::getAccessToken();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception('Oauth error: [code=' . $e->getCode() . '], [message=' . $e->getMessage() . ']');
        }
    }

    public function getHeaders()
    {
        return array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->oauthResult->getAccessToken()
        );
    }
}
