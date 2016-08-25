<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
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
     * OAuth protocol - client_id
     */
    private static $oauthClientId = '';

    /**
     * OAuth protocol - client_secret
     */
    private static $oauthClientSecret = '';

    /**
     * OAuth protocol - endpoint address
     */
    private static $oauthEndpoint = '';

    /**
     * OAuth protocol - methods for token cache
     */
    private static $oauthTokenCache = null;

    private static $serviceUrl = '';
    private static $hashAlgorithm = 'SHA-256';

    private static $sender = 'Generic';

    const API_VERSION = '2.1';
    const COMPOSER_JSON = "/composer.json";
    const DEFAULT_SDK_VERSION = 'PHP SDK 2.2.3';
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
