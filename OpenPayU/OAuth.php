<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

class OpenPayU_OAuth extends OpenPayUOAuth
{
    /**
     * Function returns authorize by code response
     * @access public
     * @param string $code
     * @param string $returnUri
     * @param bool $debug
     * @return OpenPayU_ResultOAuth $result
     */
    public static function accessTokenByCode($code, $returnUri, $debug = TRUE)
    {

        $url = OpenPayU_Configuration::getServiceUrl() . 'user/oauth/authorize';

        $result = new OpenPayU_ResultOAuth();
        $result->setUrl($url);
        $result->setCode($code);

        if ($debug) {
            OpenPayU::addOutputConsole('retrieve accessToken, authorization code mode, url', $url);
            OpenPayU::addOutputConsole('return_uri', $returnUri);
        }

        try {
            OpenPayU::setOpenPayuEndPoint($url);
            $json = OpenPayuOAuth::getAccessTokenByCode($code, OpenPayU_Configuration::getClientId(), OpenPayU_Configuration::getClientSecret(), $returnUri);

            $result->setAccessToken($json->{'access_token'});
            if (isSet($json->{'payu_user_email'})) {
                $result->setPayuUserEmail($json->{'payu_user_email'});
            }
            if (isSet($json->{'payu_user_id'})) {
                $result->setPayuUserId($json->{'payu_user_id'});
            }
            $result->setExpiresIn($json->{'expires_in'});
            $result->setRefreshToken($json->{'refresh_token'});
            $result->setSuccess(1);
        } catch (Exception $ex) {
            $result->setSuccess(0);
            $result->setError($ex->getMessage());
        }

        return $result;
    }

    /**
     * Function returns authorize by client credentials response
     * @access public
     * @param bool $debug
     * @return OpenPayU_ResultOAuth $result
     */
    public static function accessTokenByClientCredentials($debug = TRUE)
    {
        $url = OpenPayU_Configuration::getServiceUrl() . 'oauth/authorize';

        $result = new OpenPayU_ResultOAuth();
        $result->setUrl($url);

        OpenPayU::setOpenPayuEndPoint($url);

        if ($debug)
            OpenPayU::addOutputConsole('retrieve accessToken', 'retrieve accessToken, client credentials mode, url: ' . $url);

        try {
            OpenPayU::setOpenPayuEndPoint($url);
            $json = OpenPayUOAuth::getAccessTokenByClientCredentials(OpenPayU_Configuration::getClientId(), OpenPayU_Configuration::getClientSecret());

            $result->setAccessToken($json->{'access_token'});
            if (isSet($json->{'payu_user_email'})) {
                $result->setPayuUserEmail($json->{'payu_user_email'});
            }
            if (isSet($json->{'payu_user_id'})) {
                $result->setPayuUserId($json->{'payu_user_id'});
            }
            $result->setExpiresIn($json->{'expires_in'});
            $result->setRefreshToken($json->{'refresh_token'});
            $result->setSuccess(1);
        } catch (Exception $ex) {
            $result->setSuccess(0);
            $result->setError($ex->getMessage());
        }

        return $result;
    }
}