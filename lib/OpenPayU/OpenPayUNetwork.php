<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2014 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://developers.payu.com
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
        self::$openPayuEndPointUrl = $ep;
    }

    /**
     * The function gets EndPointUrl param of OpenPayU
     * @access public
     * @return string;
     */
    public static function getOpenPayuEndPoint()
    {
        return self::$openPayuEndPointUrl;
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
     * @deprecated
     * @return string
     * @throws OpenPayU_Exception_Configuration
     */
    public static function OpenPayU_Exception_Configuration()
    {
        if (empty(self::$openPayuEndPointUrl))
            throw new OpenPayU_Exception_Configuration('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        return self::$openPayuEndPointUrl;
    }

    /**
     * This function sends data to the EndPointUrl OpenPayU
     * @access public
     * @deprecated
     * @param string $doc
     * @return string
     * @throws OpenPayU_Exception_Configuration
     */
    public static function sendOpenPayuDocument($doc)
    {

        if (empty(self::$openPayuEndPointUrl))
            throw new OpenPayU_Exception_Configuration('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        if (!self::isCurlInstalled())
            throw new OpenPayU_Exception_Configuration('cURL is not available');

        $xml = urlencode($doc);
        return OpenPayU::sendData(self::$openPayuEndPointUrl, 'DOCUMENT=' . $xml);
    }

    /**
     * This function sends auth data to the EndPointUrl OpenPayU
     * @access public
     * @param string $doc
     * @param integer $merchantPosId
     * @param string $signatureKey
     * @param string $algorithm
     * @return string
     * @throws OpenPayU_Exception_Configuration
     * @deprecated
     */
    public static function sendOpenPayuDocumentAuth($doc, $merchantPosId, $signatureKey, $algorithm = 'MD5')
    {
        if (empty(self::$openPayuEndPointUrl))
            throw new OpenPayU_Exception_Configuration('OpenPayUNetwork::$openPayuEndPointUrl is empty');

        if (empty($signatureKey))
            throw new OpenPayU_Exception_Configuration('Merchant Signature Key should not be null or empty.');

        if (empty($merchantPosId))
            throw new OpenPayU_Exception_Configuration('MerchantPosId should not be null or empty.');

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

        if (!self::isCurlInstalled())
            throw new OpenPayU_Exception_Configuration('curl is not available');

        return OpenPayU::sendDataAuth(self::$openPayuEndPointUrl, 'DOCUMENT=' . $xml, $authData);
    }

    /**
     * This function sends auth data to the EndPointUrl OpenPayU
     * @access public
     * @deprecated
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
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-OpenPayU-Signature:' . $authData));

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
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return $response;
    }
}