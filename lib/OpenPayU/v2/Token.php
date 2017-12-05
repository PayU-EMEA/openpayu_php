<?php

namespace PayU\OpenPayU\v2;

use PayU\OpenPayU\AuthType\Oauth;
use PayU\OpenPayU\Configuration;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;
use PayU\OpenPayU\Http;
use PayU\OpenPayU\Oauth\OauthGrantType;
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

class Token extends OpenPayU
{

    const TOKENS_SERVICE = 'tokens';

    /**
     * Deleting a payment token
     * @param string $token
     * @return null|Result
     * @throws OpenPayUException
     * @throws OpenPayUExceptionConfiguration
     */
    public static function delete($token)
    {

        $authType = self::getAuth();

        if (!$authType instanceof Oauth) {
            throw new OpenPayUExceptionConfiguration('Delete token works only with OAuth');
        }

        if (Configuration::getOauthGrantType() !== OauthGrantType::TRUSTED_MERCHANT) {
            throw new OpenPayUExceptionConfiguration('Token delete request is available only for trusted_merchant');
        }

        $pathUrl = Configuration::getServiceUrl() . self::TOKENS_SERVICE . '/' . $token;

        return self::verifyResponse(Http::doDelete($pathUrl, $authType));
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

        if ($httpStatus == 204) {
            return $result;
        } else {
            Http::throwHttpStatusException($httpStatus, $result);
        }
    }
}
