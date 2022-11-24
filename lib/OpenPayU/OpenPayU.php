<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU
{
    protected static function build($data)
    {
        $instance = new OpenPayU_Result();

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
     * @throws OpenPayU_Exception_Authorization
     */
    public static function verifyDocumentSignature($data, $incomingSignature)
    {
        $sign = OpenPayU_Util::parseSignature($incomingSignature);

        if ($sign === null || !array_key_exists('signature', $sign) || !array_key_exists('algorithm', $sign)) {
            throw new OpenPayU_Exception_Authorization('Signature not found');
        }

        if (false === OpenPayU_Util::verifySignature(
                $data,
                $sign['signature'],
                OpenPayU_Configuration::getSignatureKey(),
                $sign['algorithm'])
        ) {
            throw new OpenPayU_Exception_Authorization('Invalid signature - ' . $sign['signature']);
        }
    }

    /**
     * @return AuthType
     * @throws OpenPayU_Exception
     */
    protected static function getAuth()
    {
        if (OpenPayU_Configuration::getOauthClientId() && OpenPayU_Configuration::getOauthClientSecret()) {
            $authType = new AuthType_Oauth(OpenPayU_Configuration::getOauthClientId(), OpenPayU_Configuration::getOauthClientSecret());
        } else {
            $authType = new AuthType_Basic(OpenPayU_Configuration::getMerchantPosId(), OpenPayU_Configuration::getSignatureKey());
        }

        return $authType;
    }


}