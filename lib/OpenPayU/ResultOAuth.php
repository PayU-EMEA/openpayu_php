<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

class OpenPayU_ResultOAuth
{
    private $url = '';
    private $code = '';
    private $accessToken = '';
    private $payuUserEmail = '';
    private $payuUserId = '';
    private $expiresIn = '';
    private $refreshToken = '';
    private $success = '';
    private $error = '';

    /**
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @access public
     * @param $value
     */
    public function setUrl($value)
    {
        $this->url = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @access public
     * @param $value
     */
    public function setCode($value)
    {
        $this->code = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @access public
     * @param $value
     */
    public function setAccessToken($value)
    {
        $this->accessToken = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getPayuUserEmail()
    {
        return $this->payuUserEmail;
    }

    /**
     * @access public
     * @param $value
     */
    public function setPayuUserEmail($value)
    {
        $this->payuUserEmail = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getPayuUserId()
    {
        return $this->payuUserId;
    }

    /**
     * @access public
     * @param $value
     */
    public function setPayuUserId($value)
    {
        $this->payuUserId = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * @access public
     * @param $value
     */
    public function setExpiresIn($value)
    {
        $this->expiresIn = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @access public
     * @param $value
     */
    public function setRefreshToken($value)
    {
        $this->refreshToken = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @access public
     * @param $value
     */
    public function setSuccess($value)
    {
        $this->success = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @access public
     * @param $value
     */
    public function setError($value)
    {
        $this->error = $value;
    }

}