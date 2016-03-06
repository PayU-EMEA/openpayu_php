<?php

class OauthResultClientCredentials
{

    /**
     * @var string
     */
    private $accessToken;
    /**
     * @var string
     */
    private $tokenType;
    /**
     * @var string
     */
    private $expiresIn;
    /**
     * @var string
     */
    private $grantType;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return OauthResultClientCredentials
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     * @return OauthResultClientCredentials
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * @param string $expiresIn
     * @return OauthResultClientCredentials
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @param string $grantType
     * @return OauthResultClientCredentials
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
        return $this;
    }


}