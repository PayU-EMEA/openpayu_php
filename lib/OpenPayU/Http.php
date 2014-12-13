<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 */
class OpenPayU_Http
{
    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function post($pathUrl, $data)
    {
        //$signature = OpenPayU_Util::generateSignData($data, OpenPayU_Configuration::getHashAlgorithm(), OpenPayU_Configuration::getMerchantPosId(), OpenPayU_Configuration::getSignatureKey());

        $posId = OpenPayU_Configuration::getMerchantPosId();
        $signatureKey = OpenPayU_Configuration::getSignatureKey();

        $response = OpenPayU_HttpCurl::doRequest('POST', $pathUrl, $data, $posId, $signatureKey);

        return $response;
    }

    public static function postWithSignature($pathUrl, $data)
    {
        //$signature = OpenPayU_Util::generateSignData($data, OpenPayU_Configuration::getHashAlgorithm(), OpenPayU_Configuration::getMerchantPosId(), OpenPayU_Configuration::getSignatureKey());

        $posId = OpenPayU_Configuration::getMerchantPosId();
        $signatureKey = OpenPayU_Configuration::getSignatureKey();

        $response = OpenPayU_HttpCurl::doRequest('POST', $pathUrl, $data, $posId, $signatureKey);

        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function get($pathUrl, $data)
    {
        $posId = OpenPayU_Configuration::getMerchantPosId();
        $signatureKey = OpenPayU_Configuration::getSignatureKey();

        $response = OpenPayU_HttpCurl::doRequest('GET', $pathUrl, $data, $posId, $signatureKey);

        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function put($pathUrl, $data)
    {
        $posId = OpenPayU_Configuration::getMerchantPosId();
        $signatureKey = OpenPayU_Configuration::getSignatureKey();

        $response = OpenPayU_HttpCurl::doRequest('PUT', $pathUrl, $data, $posId, $signatureKey);

        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function delete($pathUrl, $data)
    {
        $posId = OpenPayU_Configuration::getMerchantPosId();
        $signatureKey = OpenPayU_Configuration::getSignatureKey();

        $response = OpenPayU_HttpCurl::doRequest('DELETE', $pathUrl, $data, $posId, $signatureKey);

        return $response;
    }

    /**
     *
     *
     * @param $statusCode
     * @param null $message
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerMaintenance
     * @throws OpenPayU_Exception_ServerError
     */
    public static function throwHttpStatusException($statusCode, $message = null)
    {
        switch ($statusCode) {
            default:
                throw new OpenPayU_Exception_Network('Unexpected HTTP code response', $statusCode);
                break;

            case 400:
                throw new OpenPayU_Exception(trim($message->Status->StatusCode . (isset($message->Status->StatusDesc) ?
                    ' - ' . $message->Status->StatusDesc : '')), $statusCode);
                break;

            case 401:
                throw new OpenPayU_Exception_Authorization(trim($message->Status->StatusCode .
                    (isset($message->Status->StatusDesc) ? ' - ' . $message->Status->StatusDesc : '')), $statusCode);
                break;

            case 403:
                throw new OpenPayU_Exception_Authorization(trim($message->Status->StatusCode), $statusCode);
                break;

            case 404:
                throw new OpenPayU_Exception_Network('Data indicated in the request is not available in the PayU system.');
                break;

            case 408:
                throw new OpenPayU_Exception_ServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new OpenPayU_Exception_ServerError('PayU system is unavailable or your order is not processed.
                Error:
                [' . (isset($message->Status->StatusDesc) ?
                    $message->Status->StatusDesc : '') . ']', $statusCode);
                break;

            case 503:
                throw new OpenPayU_Exception_ServerMaintenance('Service unavailable', $statusCode);
                break;
        }
    }
}
