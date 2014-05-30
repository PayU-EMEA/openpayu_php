<?php
/**
 * OpenPayU Token module
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

class OpenPayU_Token extends OpenPayU
{
    /**
     * @deprecated
     * @param array $data
     * @return OpenPayU_Result
     */
    public static function create($data)
    {
        $pathUrl = OpenPayU_Configuration::getServiceUrl() . 'token' . OpenPayU_Configuration::getDataFormat(true);

        $xml = OpenPayU_Util::buildXmlFromArray($data, 'TokenCreateRequest');

        $result = self::verifyResponse(OpenPayU_Http::post($pathUrl, $xml), 'TokenCreateResponse');

        return $result;
    }

    /**
     * @param string $response
     * @param string $messageName
     * @return null|OpenPayU_Result
     */
    public static function verifyResponse($response, $messageName)
    {
        $data = array();
        $httpStatus = $response['code'];
        $message = OpenPayU_Util::parseXmlDocument($response['response']);

        if (isset($message['OpenPayU'][$messageName])) {
            $status = $message['OpenPayU'][$messageName]['Status'];
            $data['Status'] = $status;
            unset($message['OpenPayU'][$messageName]['Status']);
            $data['Response'] = $message['OpenPayU'][$messageName];
        }
        elseif(isset($message['OpenPayU']))
        {
            $status = $message['OpenPayU']['Status'];
            $data['Status'] = $status;
            unset($message['OpenPayU']['Status']);
        }

        $result = self::build($data);

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 302)
            return $result;
        else {
            OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
        }

        return null;
    }
}
