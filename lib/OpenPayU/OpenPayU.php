<?php

namespace PayU\OpenPayU;

use PayU\OpenPayU\AuthType\AuthType;
use PayU\OpenPayU\AuthType\Basic;
use PayU\OpenPayU\AuthType\Oauth;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Exception\OpenPayUExceptionAuthorization;

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU
{
    protected static function build($data)
    {
        $instance = new Result();

        if (array_key_exists('status', $data) && $data['status'] == 'WARNING_CONTINUE_REDIRECT') {
            $data['status'] = 'SUCCESS';
            $data['response']['status']['statusCode'] = 'SUCCESS';
        }

        $instance->init($data);

        return $instance;
    }

    /**
     * @param $data
     * @param $incomingSignature
     * @throws OpenPayUExceptionAuthorization
     */
    public static function verifyDocumentSignature($data, $incomingSignature)
    {
        $sign = Util::parseSignature($incomingSignature);

        if (false === Util::verifySignature(
                $data,
                $sign->signature,
                Configuration::getSignatureKey(),
                $sign->algorithm)
        ) {
            throw new OpenPayUExceptionAuthorization('Invalid signature - ' . $sign->signature);
        }
    }

    /**
     * @return AuthType
     * @throws OpenPayUException
     */
    protected static function getAuth()
    {
        if (Configuration::getOauthClientId() && Configuration::getOauthClientSecret()) {
            $authType = new Oauth(Configuration::getOauthClientId(), Configuration::getOauthClientSecret());
        } else {
            $authType = new Basic(Configuration::getMerchantPosId(), Configuration::getSignatureKey());
        }

        return $authType;
    }


}