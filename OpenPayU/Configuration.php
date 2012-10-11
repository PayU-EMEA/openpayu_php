<?php

/*
	OpenPayU Standard Library
	
	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

class OpenPayU_Configuration
{
    public static $env = 'sandbox';
    public static $merchantPosId = '';
    public static $posAuthKey = '';
    public static $clientId = '';
    public static $clientSecret = '';
    public static $signatureKey = '';

    public static $serviceUrl = '';
    public static $summaryUrl = '';
    public static $authUrl = '';
    public static $serviceDomain = '';

    /**
     * @access public
     * @param string $value
     * @param string $domain
     * @param string $country
     * @throws Exception
     */
    public static function setEnvironment($value = 'sandbox', $domain = 'payu.pl', $country = 'pl')
    {
        $value = strtolower($value);
        $domain = strtolower($domain);
        $country = strtolower($country);

        if ($value == 'sandbox' || $value == 'secure') {
            self::$env = $value;
            self::$serviceDomain = $domain;

            self::$serviceUrl = 'https://' . $value . '.' . $domain . '/' . $country . '/standard/';
            self::$summaryUrl = self::$serviceUrl . 'co/summary';
            self::$authUrl = self::$serviceUrl . 'oauth/user/authorize';
        } else if ($value == 'custom') {
            self::$env = $value;

            self::$serviceUrl = $domain . '/' . $country . '/standard/';
            self::$summaryUrl = self::$serviceUrl . 'co/summary';
            self::$authUrl = self::$serviceUrl . 'oauth/user/authorize';
        } else {
            throw new Exception('Invalid value:' . $value . ' for environment. Proper values are: "sandbox" or "secure".');
        }
    }

    /**
     * @access public
     * @return string
     */
    public static function getServiceUrl()
    {
        return self::$serviceUrl;
    }

    /**
     * @access public
     * @return string
     */
    public static function getSummaryUrl()
    {
        return self::$summaryUrl;
    }

    /**
     * @access public
     * @return string
     */
    public static function getAuthUrl()
    {
        return self::$authUrl;
    }

    /**
     * @access public
     * @return string
     */
    public static function getEnvironment()
    {
        return self::$env;
    }

    /**
     * @access public
     * @param string
     */
    public static function setMerchantPosId($value)
    {
        self::$merchantPosId = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getMerchantPosId()
    {
        return self::$merchantPosId;
    }

    /**
     * @access public
     * @param string
     */
    public static function setPosAuthKey($value)
    {
        self::$posAuthKey = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getPosAuthKey()
    {
        return self::$posAuthKey;
    }

    /**
     * @access public
     * @param string
     */
    public static function setClientId($value)
    {
        self::$clientId = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getClientId()
    {
        return self::$clientId;
    }

    /**
     * @access public
     * @param string
     */
    public static function setClientSecret($value)
    {
        self::$clientSecret = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getClientSecret()
    {
        return self::$clientSecret;
    }

    /**
     * @access public
     * @param string
     */
    public static function setSignatureKey($value)
    {
        self::$signatureKey = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getSignatureKey()
    {
        return self::$signatureKey;
    }

}