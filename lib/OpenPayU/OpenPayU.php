<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU
{
    protected static function build($data)
    {
        $instance = new OpenPayU_Result();
        $instance->init($data);

        return $instance;
    }

    /**
     * @throws OpenPayU_Exception_Authorization
     */
    public static function verifyBasicAuthCredentials()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $user = (string)$_SERVER['PHP_AUTH_USER'];
        } else {
            throw new OpenPayU_Exception_Authorization('Empty user name');
        }

        if (isset($_SERVER['PHP_AUTH_PW'])) {
            $password = (string)$_SERVER['PHP_AUTH_PW'];
        } else {
            throw new OpenPayU_Exception_Authorization('Empty password');
        }

        if ($user !== OpenPayU_Configuration::getMerchantPosId() ||
            $password !== OpenPayU_Configuration::getSignatureKey()
        ) {
            throw new OpenPayU_Exception_Authorization("invalid credentials");
        }
    }

    /**
     * @param $data
     * @param $incomingSignature
     * @throws OpenPayU_Exception_Authorization
     */
    public static function verifyDocumentSignature($data, $incomingSignature)
    {
        $sign = OpenPayU_Util::parseSignature($incomingSignature);

        if (false === OpenPayU_Util::verifySignature(
                $data,
                $sign->signature,
                OpenPayU_Configuration::getSignatureKey(),
                $sign->algorithm
            )
        ) {
            throw new OpenPayU_Exception_Authorization('Invalid signature - ' . $sign->signature);
        }
    }

    /**
     * @return bool
     */
    public static function isSecureConnection()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
    }
}