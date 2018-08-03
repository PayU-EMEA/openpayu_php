<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */
class OpenPayU_Configuration
{
    private static $_availableEnvironment = array('custom', 'secure', 'sandbox');
    private static $_availableHashAlgorithm = array('SHA', 'SHA-256', 'SHA-384', 'SHA-512');

    private static $env = 'secure';

    /**
     * Merchant Pos ID for Auth Basic and Notification Consume
     */
    private static $merchantPosId = '';

    /**
     * Signature Key for Auth Basic and Notification Consume
     */
    private static $signatureKey = '';

    /**
     * OAuth protocol - default type
     */
    private static $oauthGrantType = OauthGrantType::CLIENT_CREDENTIAL;
    /**
     * OAuth protocol - client_id
     */
    private static $oauthClientId = '';

    /**
     * OAuth protocol - client_secret
     */
    private static $oauthClientSecret = '';

    /**
     * OAuth protocol - email
     */
    private static $oauthEmail = '';

    /**
     * OAuth protocol - extCustomerId
     */
    private static $oauthExtCustomerId;

    /**
     * OAuth protocol - endpoint address
     */
    private static $oauthEndpoint = '';

    /**
     * OAuth protocol - methods for token cache
     */
    private static $oauthTokenCache = null;

    /**
     * Proxy - host
     */
    private static $proxyHost = null;

    /**
     * Proxy - port
     */
    private static $proxyPort = null;

    /**
     * Proxy - user
     */
    private static $proxyUser = null;

    /**
     * Proxy - password
     */
    private static $proxyPassword = null;

    private static $serviceUrl = '';
    private static $hashAlgorithm = 'SHA-256';

    private static $sender = 'Generic';

    const API_VERSION = '2.1';
    const COMPOSER_JSON = "/composer.json";
    const DEFAULT_SDK_VERSION = 'PHP SDK 2.2.10';
    const OAUTH_CONTEXT = 'pl/standard/user/oauth/authorize';

    /**
     * @return string
     */
    public static function getApiVersion()
    {
        return self::API_VERSION;
    }

    /**
     * @param string
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setHashAlgorithm($value)
    {
        if (!in_array($value, self::$_availableHashAlgorithm)) {
            throw new OpenPayU_Exception_Configuration('Hash algorithm "' . $value . '"" is not available');
        }

        self::$hashAlgorithm = $value;
    }

    /**
     * @return string
     */
    public static function getHashAlgorithm()
    {
        return self::$hashAlgorithm;
    }

    /**
     * @param string $environment
     * @param string $domain
     * @param string $api
     * @param string $version
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setEnvironment($environment = 'secure', $domain = 'payu.com', $api = 'api/', $version = 'v2_1/')
    {
        $environment = strtolower($environment);
        $domain = strtolower($domain) . '/';

        if (!in_array($environment, self::$_availableEnvironment)) {
            throw new OpenPayU_Exception_Configuration($environment . ' - is not valid environment');
        }

        self::$env = $environment;

        if ($environment == 'secure') {
            self::$serviceUrl = 'https://' . $environment . '.' . $domain . $api . $version;
            self::$oauthEndpoint = 'https://' . $environment . '.' . $domain . self::OAUTH_CONTEXT;
        } else if ($environment == 'sandbox') {
            self::$serviceUrl = 'https://secure.snd.' . $domain . $api . $version;
            self::$oauthEndpoint = 'https://secure.snd.' . $domain . self::OAUTH_CONTEXT;
        } else if ($environment == 'custom') {
            self::$serviceUrl = $domain . $api . $version;
            self::$oauthEndpoint = $domain . self::OAUTH_CONTEXT;
        }
    }

    /**
     * @return string
     */
    public static function getServiceUrl()
    {
        return self::$serviceUrl;
    }

    /**
     * @return string
     */
    public static function getOauthEndpoint()
    {
        return self::$oauthEndpoint;
    }

    /**
     * @return string
     */
    public static function getEnvironment()
    {
        return self::$env;
    }

    /**
     * @param string
     */
    public static function setMerchantPosId($value)
    {
        self::$merchantPosId = trim($value);
    }

    /**
     * @return string
     */
    public static function getMerchantPosId()
    {
        return self::$merchantPosId;
    }

    /**
     * @param string
     */
    public static function setSignatureKey($value)
    {
        self::$signatureKey = trim($value);
    }

    /**
     * @return string
     */
    public static function getSignatureKey()
    {
        return self::$signatureKey;
    }

    /**
     * @return string
     */
    public static function getOauthGrantType()
    {
        return self::$oauthGrantType;
    }

    /**
     * @param string $oauthGrantType
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setOauthGrantType($oauthGrantType)
    {
        if ($oauthGrantType !== OauthGrantType::CLIENT_CREDENTIAL && $oauthGrantType !== OauthGrantType::TRUSTED_MERCHANT) {
            throw new OpenPayU_Exception_Configuration('Oauth grand type "' . $oauthGrantType . '"" is not available');
        }

        self::$oauthGrantType = $oauthGrantType;
    }

    /**
     * @return string
     */
    public static function getOauthClientId()
    {
        return self::$oauthClientId;
    }

    /**
     * @return string
     */
    public static function getOauthClientSecret()
    {
        return self::$oauthClientSecret;
    }

    /**
     * @param mixed $oauthClientId
     */
    public static function setOauthClientId($oauthClientId)
    {
        self::$oauthClientId = trim($oauthClientId);
    }

    /**
     * @param mixed $oauthClientSecret
     */
    public static function setOauthClientSecret($oauthClientSecret)
    {
        self::$oauthClientSecret = trim($oauthClientSecret);
    }

    /**
     * @return mixed
     */
    public static function getOauthEmail()
    {
        return self::$oauthEmail;
    }

    /**
     * @param mixed $oauthEmail
     */
    public static function setOauthEmail($oauthEmail)
    {
        self::$oauthEmail = $oauthEmail;
    }

    /**
     * @return mixed
     */
    public static function getOauthExtCustomerId()
    {
        return self::$oauthExtCustomerId;
    }

    /**
     * @param mixed $oauthExtCustomerId
     */
    public static function setOauthExtCustomerId($oauthExtCustomerId)
    {
        self::$oauthExtCustomerId = $oauthExtCustomerId;
    }

    /**
     * @return null | OauthCacheInterface
     */
    public static function getOauthTokenCache()
    {
        return self::$oauthTokenCache;
    }

    /**
     * @param OauthCacheInterface $oauthTokenCache
     * @throws OpenPayU_Exception_Configuration
     */
    public static function setOauthTokenCache($oauthTokenCache)
    {
        if (!$oauthTokenCache instanceof OauthCacheInterface) {
            throw new OpenPayU_Exception_Configuration('Oauth token cache class is not instance of OauthCacheInterface');
        }
        self::$oauthTokenCache = $oauthTokenCache;
    }

    /**
     * @return string | null
     */
    public static function getProxyHost()
    {
        return self::$proxyHost;
    }

    /**
     * @param string | null $proxyHost
     */
    public static function setProxyHost($proxyHost)
    {
        self::$proxyHost = $proxyHost;
    }

    /**
     * @return int | null
     */
    public static function getProxyPort()
    {
        return self::$proxyPort;
    }

    /**
     * @param int | null $proxyPort
     */
    public static function setProxyPort($proxyPort)
    {
        self::$proxyPort = $proxyPort;
    }

    /**
     * @return string | null
     */
    public static function getProxyUser()
    {
        return self::$proxyUser;
    }

    /**
     * @param string | null $proxyUser
     */
    public static function setProxyUser($proxyUser)
    {
        self::$proxyUser = $proxyUser;
    }

    /**
     * @return string | null
     */
    public static function getProxyPassword()
    {
        return self::$proxyPassword;
    }

    /**
     * @param string | null $proxyPassword
     */
    public static function setProxyPassword($proxyPassword)
    {
        self::$proxyPassword = $proxyPassword;
    }

    /**
     * @param string $sender
     */
    public static function setSender($sender)
    {
        self::$sender = $sender;
    }

    /**
     * @return string
     */
    public static function getSender()
    {
        return self::$sender;
    }

    /**
     * @return string
     */
    public static function getFullSenderName()
    {
        return sprintf("%s@%s", self::getSender(), self::getSdkVersion());
    }

    /**
     * @return string
     */
    public static function getSdkVersion()
    {
        $composerFilePath = self::getComposerFilePath();
        if (file_exists($composerFilePath)) {
            $fileContent = file_get_contents($composerFilePath);
            $composerData = json_decode($fileContent);
            if (isset($composerData->version) && isset($composerData->extra[0]->engine)) {
                return sprintf("%s %s", $composerData->extra[0]->engine, $composerData->version);
            }
        }

        return self::DEFAULT_SDK_VERSION;
    }

    /**
     * @return string
     */
    private static function getComposerFilePath()
    {
        return realpath(dirname(__FILE__)) . '/../../' . self::COMPOSER_JSON;
    }
}
