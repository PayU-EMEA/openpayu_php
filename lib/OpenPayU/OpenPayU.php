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

}