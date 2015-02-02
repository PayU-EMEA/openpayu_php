<?php
/**
 * OpenPayU_HttpCurl
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 */
class OpenPayU_HttpCurl implements OpenPayU_HttpProtocol
{
    /**
     * @var
     */
    static $headers;

    /**
     * @param $requestType
     * @param $pathUrl
     * @param $data
     * @param $signature
     * @return mixed
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_Authorization
     */
    public static function doRequest($requestType, $pathUrl, $data, $posId, $signatureKey)
    {
        if (empty($pathUrl))
            throw new OpenPayU_Exception_Configuration('The end point is empty');

        if (empty($posId)) {
            throw new OpenPayU_Exception_Configuration('PosId is empty');
        }

        if (empty($signatureKey)) {
            throw new OpenPayU_Exception_Configuration('SignatureKey is empty');
        }

        $userNameAndPassword = $posId.":".$signatureKey;

        $header = array();

        if(OpenPayU_Configuration::getApiVersion() >= 2)
        {
            $header[] = 'Content-Type:application/json';
            $header[] = 'Accept:application/json';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'OpenPayU_HttpCurl::readHeader');
        curl_setopt($ch, CURLOPT_POSTFIELDS, (OpenPayU_Configuration::getApiVersion() < 2) ? 'DOCUMENT=' . urlencode($data) : $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $userNameAndPassword);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($response === false)
            throw new OpenPayU_Exception_Network(curl_error($ch));

        curl_close($ch);

        return array('code' => $httpStatus, 'response' => trim($response));
    }

    /**
     * @param $headers
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
    }

    /**
     * @param $ch
     * @param $header
     * @return int
     */
    public static function readHeader($ch, $header)
    {
        if( preg_match('/([^:]+): (.+)/m', $header, $match) ) {
            self::$headers[$match[1]] = trim($match[2]);
        }

        return strlen($header);
    }

    /**
     * @param  $headers
     */
    public static function setHeaders($headers)
    {
        self::$headers = $headers;
    }

    /**
     * @return mixed
     */
    public static function getHeader()
    {
        return self::$headers;
    }
}
