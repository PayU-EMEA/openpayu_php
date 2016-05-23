<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Retrieve extends OpenPayU
{

    const PAYMETHODS_SERVICE = 'paymethods';

    /**
     * Get Pay Methods from POS
     * @param string $lang
     * @return null|OpenPayU_Result
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Configuration
     */
    public static function payMethods($lang = null)
    {

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof AuthType_Oauth) {
            throw new OpenPayU_Exception_Configuration('Retrieve works only with OAuth');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::PAYMETHODS_SERVICE;
        if ($lang !== null) {
            $pathUrl .= '?lang=' . $lang;
        }

        $response = self::verifyResponse(OpenPayU_Http::doGet($pathUrl, $authType));

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

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 302 || $httpStatus == 400 || $httpStatus == 404) {
            return $result;
        } else {
            OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
        }

    }
}