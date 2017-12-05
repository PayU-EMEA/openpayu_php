<?php

namespace PayU\OpenPayU\v2;

use PayU\OpenPayU\Configuration;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Http;
use PayU\OpenPayU\OpenPayU;
use PayU\OpenPayU\Result;
use PayU\OpenPayU\Util;

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class Refund extends OpenPayU
{
    /**
     * Function make refund for order
     * @param $orderId
     * @param $description
     * @param int $amount Amount of refund in pennies
     * @return null | Result
     * @throws OpenPayUException
     */
    public static function create($orderId, $description, $amount = null)
    {
        if (empty($orderId)) {
            throw new OpenPayUException('Invalid orderId value for refund');
        }

        if (empty($description)) {
            throw new OpenPayUException('Invalid description of refund');
        }
        $refund = array(
            'orderId' => $orderId,
            'refund' => array('description' => $description)
        );

        if (!empty($amount)) {
            $refund['refund']['amount'] = (int)$amount;
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        $pathUrl = Configuration::getServiceUrl().'orders/'. $refund['orderId'] . '/refund';

        $data = Util::buildJsonFromArray($refund);

        return self::verifyResponse(Http::doPost($pathUrl, $data, $authType), 'RefundCreateResponse');
    }

    /**
     * @param string $response
     * @param string $messageName
     * @return Result
     */
    public static function verifyResponse($response, $messageName='')
    {
        $data = array();
        $httpStatus = $response['code'];

        $message = Util::convertJsonToArray($response['response'], true);

        $data['status'] = isset($message['status']['statusCode']) ? $message['status']['statusCode'] : null;

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
        } elseif (isset($message[$messageName])) {
            unset($message[$messageName]['Status']);
            $data['response'] = $message[$messageName];
        } elseif (isset($message)) {
            $data['response'] = $message;
            unset($message['status']);
        }

        $result = self::build($data);

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 302) {
            return $result;
        } else {
            Http::throwHttpStatusException($httpStatus, $result);
        }
    }
}