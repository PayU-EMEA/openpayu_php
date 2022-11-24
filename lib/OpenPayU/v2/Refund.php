<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Refund extends OpenPayU
{
    /**
     * Function make refund for order
     * @param $orderId
     * @param $description
     * @param null|int $amount Amount of refund in pennies
     * @param null|string $extCustomerId Marketplace external customer ID
     * @param null|string $extRefundId Marketplace external refund ID
     * @return null|OpenPayU_Result
     * @throws OpenPayU_Exception
     */
    public static function create($orderId, $description, $amount = null, $extCustomerId = null, $extRefundId = null)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Invalid orderId value for refund');
        }

        if (empty($description)) {
            throw new OpenPayU_Exception('Invalid description of refund');
        }
        $refund = array(
            'orderId' => $orderId,
            'refund' => array('description' => $description)
        );

        if (!empty($amount)) {
            $refund['refund']['amount'] = $amount;
        }

        if (!empty($extCustomerId)) {
            $refund['refund']['extCustomerId'] = $extCustomerId;
        }

        if (!empty($extRefundId)) {
            $refund['refund']['extRefundId'] = $extRefundId;
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl().'orders/'. $refund['orderId'] . '/refund';

        $data = OpenPayU_Util::buildJsonFromArray($refund);

        $result = self::verifyResponse(OpenPayU_Http::doPost($pathUrl, $data, $authType), 'RefundCreateResponse');

        return $result;
    }

    /**
     * @param string $response
     * @param string $messageName
     * @return OpenPayU_Result
     */
    public static function verifyResponse($response, $messageName='')
    {
        $data = array();
        $httpStatus = $response['code'];

        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

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
            OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
        }
    }
}