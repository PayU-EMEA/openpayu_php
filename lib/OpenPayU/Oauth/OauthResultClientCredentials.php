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
     * @var DateTime
     */
    private $expireDate;

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

    /**
     * @return DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param DateTime $date
     */
    public function calculateExpireDate($date)
    {
        $this->expireDate = $date->add(new DateInterval('PT' . ($this->expiresIn - 60) . 'S'));
    }

    public function hasExpire()
    {
        return ($this->expireDate <= new DateTime());
    }

}