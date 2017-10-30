<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Token extends OpenPayU
{

    const TOKENS_SERVICE = 'tokens';

    /**
     * Deleting a payment token
     * @param string $token
     * @return null|OpenPayU_Result
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Configuration
     */
    public static function delete($token)
    {

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof AuthType_Oauth) {
            throw new OpenPayU_Exception_Configuration('Delete token works only with OAuth');
        }

        if (OpenPayU_Configuration::getOauthGrantType() !== OauthGrantType::TRUSTED_MERCHANT) {
            throw new OpenPayU_Exception_Configuration('Token delete request is available only for trusted_merchant');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::TOKENS_SERVICE . '/' . $token;

        $response = self::verifyResponse(OpenPayU_Http::doDelete($pathUrl, $authType));

        return $response;
    }

    /**
     * @param string $response
     * @return null|OpenPayU_Result
     */
    public static function verifyResponse($response)
    {
        $data = array();
        $httpStatus = $response['code'];

        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

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
            OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
        }
    }
}
