<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

class OpenPayUNetwork
{
    /** @var string OpenPayU EndPoint Url */
    protected static $openPayuEndPointUrl = '';

    /**
     * The function sets EndPointUrl param of OpenPayU
     * @access public
     * @param string $ep
     */
    public static function setOpenPayuEndPoint($ep)
    {
        OpenPayUNetwork::$openPayuEndPointUrl = $ep;
    }

    /**
     * This function checks the availability of cURL
     * @access private
     * @return bool
     */
    private static function isCurlInstalled()
    {
        if (in_array('curl', get_loaded_extensions()))
            return true;

        return false;
    }

    /**
     * The function returns the parameter EndPointUrl OpenPayU
     * @access public
     * @return string
     * @throws Exception
     */
    public static function getOpenPayuEndPoint()
    {
        if (empty(OpenPayUNetwork::$openPayuEndPointUrl))
            throw new Exception('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        return OpenPayUNetwork::$openPayuEndPointUrl;
    }

    /**
     * This function sends data to the EndPointUrl OpenPayU
     * @access public
     * @param string $doc
     * @return string
     * @throws Exception
     */
    public static function sendOpenPayuDocument($doc)
    {

        if (empty(OpenPayUNetwork::$openPayuEndPointUrl))
            throw new Exception('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        if (!OpenPayUNetwork::isCurlInstalled())
            throw new Exception('cURL is not available');

        $xml = urlencode($doc);
        return  OpenPayU::sendData(OpenPayUNetwork::$openPayuEndPointUrl, 'DOCUMENT=' . $xml);
    }

    /**
     * This function sends auth data to the EndPointUrl OpenPayU
     * @access public
     * @param string $doc
     * @param integer $merchantPosId
     * @param string $signatureKey
     * @param string $algorithm
     * @return string
     * @throws Exception
     */
    public static function sendOpenPayuDocumentAuth($doc, $merchantPosId, $signatureKey, $algorithm = 'MD5')
    {
        if (empty(OpenPayUNetwork::$openPayuEndPointUrl))
            throw new Exception('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        if (empty($signatureKey))
            throw new Exception('Merchant Signature Key should not be null or empty.');

        if (empty($merchantPosId))
            throw new Exception('MerchantPosId should not be null or empty.');

        $tosigndata = $doc . $signatureKey;
        $xml = urlencode($doc);
        $signature = '';
        if ($algorithm == 'MD5') {
            $signature = md5($tosigndata);
        } else if ($algorithm == 'SHA') {
            $signature = sha1($tosigndata);
        } else if ($algorithm == 'SHA-256' || $algorithm == 'SHA256' || $algorithm == 'SHA_256') {
            $signature = hash('sha256', $tosigndata);
        }
        $authData = 'sender=' . $merchantPosId .
            ';signature=' . $signature .
            ';algorithm=' . $algorithm .
            ';content=DOCUMENT';

        if (!OpenPayUNetwork::isCurlInstalled())
            throw new Exception('curl is not available');

        return OpenPayU::sendDataAuth(OpenPayUNetwork::$openPayuEndPointUrl, 'DOCUMENT=' . $xml, $authData);
    }

    /**
     * This function sends auth data to the EndPointUrl OpenPayU
     * @access public
     * @param string $url
     * @param string $doc
     * @param string $authData
     * @return string $response
     */
    public static function sendDataAuth($url, $doc, $authData)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $doc);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('OpenPayu-Signature:' . $authData));

        $response = curl_exec($ch);

        return $response;
    }

    /**
     * This function sends data to the EndPointUrl OpenPayU
     * @access public
     * @param string $url
     * @param string $doc
     * @return string $response
     */
    public static function sendData($url, $doc)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $doc);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        return $response;
    }
}