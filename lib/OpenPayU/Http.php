<?php

namespace PayU\OpenPayU;

use PayU\OpenPayU\AuthType\AuthType;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionAuthorization;
use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;
use PayU\OpenPayU\Exception\OpenPayUExceptionNetwork;
use PayU\OpenPayU\Exception\OpenPayUExceptionServerError;
use PayU\OpenPayU\Exception\OpenPayUExceptionServerMaintenance;

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class Http
{

    /**
     * @param string $pathUrl
     * @param string $data
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayUExceptionConfiguration
     * @throws OpenPayUExceptionNetwork
     */
    public static function doPost($pathUrl, $data, $authType)
    {
        $response = HttpCurl::doPayuRequest('POST', $pathUrl, $authType, $data);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayUExceptionConfiguration
     * @throws OpenPayUExceptionNetwork
     */
    public static function doGet($pathUrl, $authType)
    {
        $response = HttpCurl::doPayuRequest('GET', $pathUrl, $authType);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayUExceptionConfiguration
     * @throws OpenPayUExceptionNetwork
     */
    public static function doDelete($pathUrl, $authType)
    {
        $response = HttpCurl::doPayuRequest('DELETE', $pathUrl, $authType);

        return $response;
    }

    /**
     * @param string $pathUrl
     * @param string $data
     * @param AuthType $authType
     * @return mixed
     * @throws OpenPayUExceptionConfiguration
     * @throws OpenPayUExceptionNetwork
     */
    public static function doPut($pathUrl, $data, $authType)
    {
        $response = HttpCurl::doPayuRequest('PUT', $pathUrl, $authType, $data);

        return $response;
    }

    /**
     * @param $statusCode
     * @param null $message
     * @throws OpenPayUException
     * @throws OpenPayUExceptionAuthorization
     * @throws OpenPayUExceptionNetwork
     * @throws OpenPayUExceptionServerMaintenance
     * @throws OpenPayUExceptionServerError
     */
    public static function throwHttpStatusException($statusCode, $message = null)
    {

        $response = $message->getResponse();
        $statusDesc = isset($response->status->statusDesc) ? $response->status->statusDesc : '';

        switch ($statusCode) {
            case 400:
                throw new OpenPayUException($message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 401:
            case 403:
                throw new OpenPayUExceptionAuthorization($message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 404:
                throw new OpenPayUExceptionNetwork($message->getStatus().' - '.$statusDesc, $statusCode);
                break;

            case 408:
                throw new OpenPayUExceptionServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new OpenPayUExceptionServerError('PayU system is unavailable or your order is not processed.
                Error:
                [' . $statusDesc . ']', $statusCode);
                break;

            case 503:
                throw new OpenPayUExceptionServerMaintenance('Service unavailable', $statusCode);
                break;

            default:
                throw new OpenPayUExceptionNetwork('Unexpected HTTP code response', $statusCode);
                break;

        }
    }

    /**
     * @param $statusCode
     * @param ResultError $resultError
     * @throws OpenPayUException
     * @throws OpenPayUExceptionAuthorization
     * @throws OpenPayUExceptionNetwork
     * @throws OpenPayUExceptionServerError
     * @throws OpenPayUExceptionServerMaintenance
     */
    public static function throwErrorHttpStatusException($statusCode, $resultError)
    {
        switch ($statusCode) {
            case 400:
                throw new OpenPayUException($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 401:
            case 403:
                throw new OpenPayUExceptionAuthorization($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 404:
                throw new OpenPayUExceptionNetwork($resultError->getError().' - '.$resultError->getErrorDescription(), $statusCode);
                break;

            case 408:
                throw new OpenPayUExceptionServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new OpenPayUExceptionServerError('PayU system is unavailable. Error: [' . $resultError->getErrorDescription() . ']', $resultError);
                break;

            case 503:
                throw new OpenPayUExceptionServerMaintenance('Service unavailable', $statusCode);
                break;

            default:
                throw new OpenPayUExceptionNetwork('Unexpected HTTP code response', $statusCode);
                break;

        }
    }

}
