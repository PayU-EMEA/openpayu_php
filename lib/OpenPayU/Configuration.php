<?php
/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2014 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://developers.payu.com
	http://twitter.com/openpayu
*/

class OpenPayU_Configuration
{
    private static $_availableEnvironment = array('custom', 'secure');
    public static $env = 'secure';
    public static $merchantPosId = '';
    public static $posAuthKey = '';
    public static $clientId = '';
    public static $clientSecret = '';
    public static $signatureKey = '';

    public static $serviceUrl = '';
    public static $summaryUrl = '';
    public static $authUrl = '';
    public static $serviceDomain = '';

    private static $apiVersion = 2;
    private static $_availableHashAlgorithm = array('MD5', 'SHA', 'SHA1', 'SHA-1', 'SHA-256', 'SHA256', 'SHA_256');
    private static $hashAlgorithm = 'SHA-1';

    private static $_availableDataFormat = array('xml', 'json');
    private static $dataFormat = 'json';

    /**
     * @access public
     * @param int $version
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setApiVersion($version)
    {
        if(empty($version))
            throw new OpenPayU_Exception_Configuration('Invalid API version');

        self::$apiVersion = intval($version);
    }

    /**
     * @return int
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @access public
     * @param string
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setHashAlgorithm($value)
    {
        if(!in_array($value, self::$_availableHashAlgorithm))
            throw new OpenPayU_Exception_Configuration($value . ' - is not available');

        self::$hashAlgorithm = $value;
    }

    /**
     * @access public
     * @return string
     */
    public static function getHashAlgorithm()
    {
        return self::$hashAlgorithm;
    }

    /**
     * @access public
     * @param string $value
     * @param string $domain
     * @param string $country
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setEnvironment($value = 'secure', $domain = 'payu.pl', $country = 'pl')
    {
        $value = strtolower($value);
        $domain = strtolower($domain) . '/';
        $country = strtolower($country) . '/';
        $service = 'standard/';

        if(!in_array($value, self::$_availableEnvironment))
            throw new OpenPayU_Exception_Configuration($value . ' - is not valid environment');

        if (self::getApiVersion() >= 2) {
            $country = 'api/';
            $service = 'v2/';
        }

        if ($value == 'secure') {
            self::$env = $value;

            if(self::getApiVersion() >= 2)
                $domain = 'payu.com/';

            self::$serviceDomain = $domain;

            self::$serviceUrl = 'https://' . $value . '.' . $domain . $country . $service;
            self::$summaryUrl = self::$serviceUrl . 'co/summary';
            self::$authUrl = self::$serviceUrl . 'oauth/user/authorize';
        } else if ($value == 'custom') {
            self::$env = $value;

            self::$serviceUrl = $domain . $country . $service;
            self::$summaryUrl = self::$serviceUrl . 'co/summary';
            self::$authUrl = self::$serviceUrl . 'oauth/user/authorize';
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

    /**
     * @return string
     */
    public static function getDataFormat($withDot = false)
    {
        if ($withDot){
            return ".".self::$dataFormat;
        }

        return self::$dataFormat;
    }
}