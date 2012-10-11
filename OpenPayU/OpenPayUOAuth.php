<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

class OpenPayuOAuth extends OpenPayUBase
{

    /**
     * @access public
     * @param string $code
     * @param string $oauth_client_name
     * @param string $oauth_client_secret
     * @param string $page_redirect
     * @return mixed
     * @throws Exception
     */
    public static function getAccessTokenByCode($code, $oauth_client_name, $oauth_client_secret, $page_redirect)
    {
        $params = 'code=' . $code . '&client_id=' . $oauth_client_name . '&client_secret=' . $oauth_client_secret . '&grant_type=authorization_code&redirect_uri=' . $page_redirect;

        $response = OpenPayU::sendData(OpenPayUNetwork::getOpenPayuEndPoint(), $params);

        $resp_json = json_decode($response);
        OpenPayU::addOutputConsole('oauth response', $response);

        if(isset($resp_json->{"access_token"}))
            $access_token = $resp_json->{"access_token"};

        if (empty($access_token))
            throw new Exception('access_token is empty, error: ' . $response);

        return $resp_json;
    }

    /**
     * @access public
     * @param string $code
     * @param string $oauth_client_name
     * @param string $oauth_client_secret
     * @param string $page_redirect
     * @return mixed
     * @throws Exception
     */
    public static function getAccessToken($code, $oauth_client_name, $oauth_client_secret, $page_redirect)
    {
        $params = 'code=' . $code . '&client_id=' . $oauth_client_name . '&client_secret=' . $oauth_client_secret . '&grant_type=authorization_code&redirect_uri=' . $page_redirect;

        $response = OpenPayU::sendData(OpenPayUNetwork::getOpenPayuEndPoint(), $params);

        $resp_json = json_decode($response);
        OpenPayU::addOutputConsole('oauth response', $response);

        if(isset($resp_json->{"access_token"}))
            $access_token = $resp_json->{"access_token"};

        if (empty($access_token))
            throw new Exception('access_token is empty, error: ' . $response);

        return $access_token;
    }

    /**
     * @access public
     * @param string $oauth_client_name
     * @param string $oauth_client_secret
     * @return mixed
     * @throws Exception
     */
    public static function getAccessTokenByClientCredentials($oauth_client_name, $oauth_client_secret)
    {
        $params = 'client_id=' . $oauth_client_name . '&client_secret=' . $oauth_client_secret . '&grant_type=client_credentials';

        $response = OpenPayU::sendData(OpenPayUNetwork::getOpenPayuEndPoint(), $params);

        $resp_json = json_decode($response);
        OpenPayU::addOutputConsole('oauth response', $response);

        if(isset($resp_json->{"access_token"}))
            $access_token = $resp_json->{'access_token'};

        if (empty($access_token))
            throw new Exception('access_token is empty, error: ' . $response);

        return $resp_json;
    }

    /**
     * @access public
     * @param string $oauth_client_name
     * @param string $oauth_client_secret
     * @return mixed
     * @throws Exception
     */
    public static function getAccessTokenOnly($oauth_client_name, $oauth_client_secret)
    {
        $params = 'client_id=' . $oauth_client_name . '&client_secret=' . $oauth_client_secret . '&grant_type=client_credentials';

        $response = OpenPayU::sendData(OpenPayUNetwork::getOpenPayuEndPoint(), $params);

        $resp_json = json_decode($response);
        OpenPayU::addOutputConsole('oauth response', $response);

        if(isset($resp_json->{"access_token"}))
            $access_token = $resp_json->{'access_token'};

        if (empty($access_token))
            throw new Exception('access_token is empty, error: ' . $response);

        return $access_token;
    }
}