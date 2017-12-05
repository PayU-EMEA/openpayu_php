<?php

namespace PayU\OpenPayU\v2;

use PayU\OpenPayU\AuthType\Oauth;
use PayU\OpenPayU\Configuration;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;
use PayU\OpenPayU\Http;
use PayU\OpenPayU\OpenPayU;
use PayU\OpenPayU\Result;
use PayU\OpenPayU\Util;

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class Retrieve extends OpenPayU
{

    const PAYMETHODS_SERVICE = 'paymethods';

    /**
     * Get Pay Methods from POS
     * @param string $lang
     * @return null|Result
     * @throws OpenPayUException
     * @throws OpenPayUExceptionConfiguration
     */
    public static function payMethods($lang = null)
    {

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof Oauth) {
            throw new OpenPayUExceptionConfiguration('Retrieve works only with OAuth');
        }

        $pathUrl = Configuration::getServiceUrl() . self::PAYMETHODS_SERVICE;
        if ($lang !== null) {
            $pathUrl .= '?lang=' . $lang;
        }

        return self::verifyResponse(Http::doGet($pathUrl, $authType));
    }

    /**
     * @param string $response
     * @return null|Result
     */
    public static function verifyResponse($response)
    {
        $data = array();
        $httpStatus = $response['code'];

        $message = Util::convertJsonToArray($response['response'], true);

        $data['status'] = isset($message['status']['statusCode']) ? $message['status']['statusCode'] : null;

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
        } elseif (isset($message)) {
            $data['response'] = $message;
            unset($message['status']);
        }

        $result = self::build($data);

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 302 || $httpStatus == 400 || $httpStatus == 404) {
            return $result;
        } else {
            Http::throwHttpStatusException($httpStatus, $result);
        }

    }
}
