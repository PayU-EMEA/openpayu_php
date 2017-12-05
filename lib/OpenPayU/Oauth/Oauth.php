<?php

namespace PayU\OpenPayU\Oauth;

use PayU\OpenPayU\AuthType\TokenRequest;
use PayU\OpenPayU\Configuration;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionAuthorization;
use PayU\OpenPayU\Exception\OpenPayUExceptionNetwork;
use PayU\OpenPayU\Exception\OpenPayUExceptionServerError;
use PayU\OpenPayU\Exception\OpenPayUExceptionServerMaintenance;
use PayU\OpenPayU\Http;
use PayU\OpenPayU\Oauth\Cache\OauthCacheFile;
use PayU\OpenPayU\Oauth\Cache\OauthCacheInterface;
use PayU\OpenPayU\ResultError;
use PayU\OpenPayU\Util;

class Oauth
{
    /**
     * @var OauthCacheInterface
     */
    private static $oauthTokenCache;

    const CACHE_KEY = 'AccessToken';

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return OauthResultClientCredentials
     * @throws OpenPayUExceptionServerError
     */
    public static function getAccessToken($clientId = null, $clientSecret = null)
    {
        if (Configuration::getOauthGrantType() === OauthGrantType::TRUSTED_MERCHANT) {
            return self::retrieveAccessToken($clientId, $clientSecret);
        }

        $cacheKey = self::CACHE_KEY . Configuration::getOauthClientId();

        self::getOauthTokenCache();

        $tokenCache = self::$oauthTokenCache->get($cacheKey);

        if ($tokenCache instanceof OauthResultClientCredentials && !$tokenCache->hasExpire()) {
            return $tokenCache;
        }

        self::$oauthTokenCache->invalidate($cacheKey);
        $response =  self::retrieveAccessToken($clientId, $clientSecret);
        self::$oauthTokenCache->set($cacheKey, $response);

        return $response;
    }

    /**
     * @param $clientId
     * @param $clientSecret
     * @return OauthResultClientCredentials
     * @throws OpenPayUExceptionServerError
     */
    private static function retrieveAccessToken($clientId, $clientSecret)
    {
        $authType = new TokenRequest();

        $oauthUrl = Configuration::getOauthEndpoint();
        $data = array(
            'grant_type' => Configuration::getOauthGrantType(),
            'client_id' => $clientId ? $clientId : Configuration::getOauthClientId(),
            'client_secret' => $clientSecret ? $clientSecret : Configuration::getOauthClientSecret()
        );

        if (Configuration::getOauthGrantType() === OauthGrantType::TRUSTED_MERCHANT) {
            $data['email'] = Configuration::getOauthEmail();
            $data['ext_customer_id'] = Configuration::getOauthExtCustomerId();
        }

        return self::parseResponse(Http::doPost($oauthUrl, http_build_query($data, '', '&'), $authType));
    }

    /**
     * Parse response from PayU
     *
     * @param array $response
     * @return OauthResultClientCredentials
     * @throws OpenPayUException
     * @throws OpenPayUExceptionAuthorization
     * @throws OpenPayUExceptionNetwork
     * @throws OpenPayUExceptionServerError
     * @throws OpenPayUExceptionServerMaintenance
     */
    private static function parseResponse($response)
    {
        $httpStatus = $response['code'];

        if ($httpStatus == 500) {
            $result = new ResultError();
            $result->setErrorDescription($response['response']);
            Http::throwErrorHttpStatusException($httpStatus, $result);
        }

        $message = Util::convertJsonToArray($response['response'], true);

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            throw new OpenPayUExceptionServerError('Incorrect json response. Response: [' . $response['response'] . ']');
        }

        if ($httpStatus == 200) {
            $result = new OauthResultClientCredentials();
            $result->setAccessToken($message['access_token'])
                ->setTokenType($message['token_type'])
                ->setExpiresIn($message['expires_in'])
                ->setGrantType($message['grant_type'])
                ->calculateExpireDate(new \DateTime());

            return $result;
        }

        $result = new ResultError();
        $result->setError($message['error'])
            ->setErrorDescription($message['error_description']);

        Http::throwErrorHttpStatusException($httpStatus, $result);

    }

    private static function getOauthTokenCache()
    {
        $oauthTokenCache = Configuration::getOauthTokenCache();

        if (!$oauthTokenCache instanceof OauthCacheInterface) {
            $oauthTokenCache = new OauthCacheFile();
            Configuration::setOauthTokenCache($oauthTokenCache);
        }

        self::$oauthTokenCache = $oauthTokenCache;
    }
}
