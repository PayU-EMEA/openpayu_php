<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

if (!defined('CURL_SSLVERSION_TLSv1_2')) {
    define('CURL_SSLVERSION_TLSv1_2', 6);
}

class OpenPayU_HttpCurl
{
    /**
     * @var
     */
    static $headers;

    /**
     * @param $requestType
     * @param $pathUrl
     * @param $data
     * @param AuthType $auth
     * @return array
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     */
    public static function doPayuRequest($requestType, $pathUrl, $auth, $data = null)
    {
        if (empty($pathUrl)) {
            throw new OpenPayU_Exception_Configuration('The endpoint is empty');
        }

        $ch = curl_init($pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $auth->getHeaders());
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'OpenPayU_HttpCurl::readHeader');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

        if ($proxy = self::getProxy()) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            if ($proxyAuth = self::getProxyAuth()) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
            }
        }

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($response === false) {
            throw new OpenPayU_Exception_Network(curl_error($ch));
        }
        curl_close($ch);

        return array('code' => $httpStatus, 'response' => trim($response));
    }

    /**
     * @param array $headers
     *
     * @return mixed
     */
    public static function getSignature($headers)
    {
        foreach($headers as $name => $value)
        {
            if(preg_match('/X-OpenPayU-Signature/i', $name) || preg_match('/OpenPayu-Signature/i', $name))
                return $value;
        }

        return null;
    }

    /**
     * @param resource $ch
     * @param string $header
     * @return int
     */
    public static function readHeader($ch, $header)
    {
        if( preg_match('/([^:]+): (.+)/m', $header, $match) ) {
            self::$headers[$match[1]] = trim($match[2]);
        }

        return strlen($header);
    }

    private static function getProxy()
    {
        return OpenPayU_Configuration::getProxyHost() != null ? OpenPayU_Configuration::getProxyHost()
            . (OpenPayU_Configuration::getProxyPort() ? ':' . OpenPayU_Configuration::getProxyPort() : '') : false;
    }

    private static function getProxyAuth()
    {
        return OpenPayU_Configuration::getProxyUser() != null ? OpenPayU_Configuration::getProxyUser()
            . (OpenPayU_Configuration::getProxyPassword() ? ':' . OpenPayU_Configuration::getProxyPassword() : '') : false;
    }

}
