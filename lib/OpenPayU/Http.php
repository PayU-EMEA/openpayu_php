<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Http
{

    /**
     * @param string $pathUrl
     * @param string $data
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     */
    public static function doPost($pathUrl, $data, $authType)
    {
        $response = OpenPayU_HttpCurl::doPayuRequest('POST', $pathUrl, $authType, $data);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     */
    public static function doGet($pathUrl, $authType)
    {
        $response = OpenPayU_HttpCurl::doPayuRequest('GET', $pathUrl, $authType);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     */
    public static function doDelete($pathUrl, $authType)
    {
        $response = OpenPayU_HttpCurl::doPayuRequest('DELETE', $pathUrl, $authType);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param string $data
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     */
    public static function doPut($pathUrl, $data, $authType)
    {
        $response = OpenPayU_HttpCurl::doPayuRequest('PUT', $pathUrl, $authType, $data);

        return $response;
    }

    /**
     * @param $statusCode
     * @param null $message
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Request
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerMaintenance
     * @throws OpenPayU_Exception_ServerError
     */
    public static function throwHttpStatusException($statusCode, $message = null)
    {

        $response = $message->getResponse();
        $statusDesc = isset($response->status->statusDesc) ? $response->status->statusDesc : '';

        switch ($statusCode) {
            case 400:
                throw new OpenPayU_Exception_Request($message, $message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 401:
            case 403:
                throw new OpenPayU_Exception_Authorization($message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 404:
                throw new OpenPayU_Exception_Network($message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 408:
                throw new OpenPayU_Exception_ServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new OpenPayU_Exception_ServerError('PayU system is unavailable or your order is not processed.
                Error:
                [' . $statusDesc . ']', $statusCode);
                break;

            case 503:
                throw new OpenPayU_Exception_ServerMaintenance('Service unavailable', $statusCode);
                break;

            default:
                throw new OpenPayU_Exception_Network('Unexpected HTTP code response', $statusCode);
                break;

        }
    }

    /**
     * @param $statusCode
     * @param ResultError $resultError
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    public static function throwErrorHttpStatusException($statusCode, $resultError)
    {
        switch ($statusCode) {
            case 400:
                throw new OpenPayU_Exception($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 401:
            case 403:
                throw new OpenPayU_Exception_Authorization($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 404:
                throw new OpenPayU_Exception_Network($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 408:
                throw new OpenPayU_Exception_ServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new OpenPayU_Exception_ServerError('PayU system is unavailable. Error: [' . $resultError->getErrorDescription() . ']', $statusCode);
                break;

            case 503:
                throw new OpenPayU_Exception_ServerMaintenance('Service unavailable', $statusCode);
                break;

            default:
                throw new OpenPayU_Exception_Network('Unexpected HTTP code response', $statusCode);
                break;

        }
    }

}
