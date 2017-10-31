<?php

class OpenPayU_Oauth
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
     * @throws OpenPayU_Exception_ServerError
     */
    public static function getAccessToken($clientId = null, $clientSecret = null)
    {
        if (OpenPayU_Configuration::getOauthGrantType() === OauthGrantType::TRUSTED_MERCHANT) {
            return self::retrieveAccessToken($clientId, $clientSecret);
        }

        $cacheKey = self::CACHE_KEY . OpenPayU_Configuration::getOauthClientId();

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
     * @throws OpenPayU_Exception_ServerError
     */
    private static function retrieveAccessToken($clientId, $clientSecret)
    {
        $authType = new AuthType_TokenRequest();

        $oauthUrl = OpenPayU_Configuration::getOauthEndpoint();
        $data = array(
            'grant_type' => OpenPayU_Configuration::getOauthGrantType(),
            'client_id' => $clientId ? $clientId : OpenPayU_Configuration::getOauthClientId(),
            'client_secret' => $clientSecret ? $clientSecret : OpenPayU_Configuration::getOauthClientSecret()
        );

        if (OpenPayU_Configuration::getOauthGrantType() === OauthGrantType::TRUSTED_MERCHANT) {
            $data['email'] = OpenPayU_Configuration::getOauthEmail();
            $data['ext_customer_id'] = OpenPayU_Configuration::getOauthExtCustomerId();
        }

        return self::parseResponse(OpenPayU_Http::doPost($oauthUrl, http_build_query($data, '', '&'), $authType));
    }

    /**
     * Parse response from PayU
     *
     * @param array $response
     * @return OauthResultClientCredentials
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    private static function parseResponse($response)
    {
        $httpStatus = $response['code'];

        if ($httpStatus == 500) {
            $result = new ResultError();
            $result->setErrorDescription($response['response']);
            OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);
        }

        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            throw new OpenPayU_Exception_ServerError('Incorrect json response. Response: [' . $response['response'] . ']');
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

        OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);

    }

    private static function getOauthTokenCache()
    {
        $oauthTokenCache = OpenPayU_Configuration::getOauthTokenCache();

        if (!$oauthTokenCache instanceof OauthCacheInterface) {
            $oauthTokenCache = new OauthCacheFile();
            OpenPayU_Configuration::setOauthTokenCache($oauthTokenCache);
        }

        self::$oauthTokenCache = $oauthTokenCache;
    }
}