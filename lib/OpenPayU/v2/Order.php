<?php

/**
 * OpenPayU Order module
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

if (!defined('OPENPAYU_LIBRARY')) {
    exit;
}

/**
 * Class OpenPayU_Order
 */
class OpenPayU_Order extends OpenPayU
{
    const ORDER_SERVICE = 'orders/';
    const SUCCESS = 'SUCCESS';

    /**
     * Creates new Order
     * - Sends to PayU OrderCreateRequest
     *
     * @access public
     * @param array $order A array containing full Order
     * @return object $result Response array with OrderCreateResponse
     * @throws OpenPayU_Exception
     */
    public static function create($order)
    {
        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE;

        if (OpenPayU_Configuration::getDataFormat() == 'xml') {
            $data = OpenPayU_Util::buildXmlFromArray($order, 'OrderCreateRequest', '2.0', 'UTF-8');
        } elseif (OpenPayU_Configuration::getDataFormat() == 'json') {
            $data = OpenPayU_Util::buildJsonFromArray($order);
        }
        if (empty($data)) {
            throw new OpenPayU_Exception('Empty message OrderCreateRequest');
        }

        $result = self::verifyResponse(OpenPayU_Http::post($pathUrl, $data), 'OrderCreateResponse');

        return $result;
    }

    /**
     * Retrieves information about the order
     *  - Sends to PayU OrderRetrieveRequest
     *
     * @access public
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return OpenPayU_Result $result Response array with OrderRetrieveResponse
     * @throws OpenPayU_Exception
     */
    public static function retrieve($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Empty value of orderId');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        $result = self::verifyResponse(OpenPayU_Http::get($pathUrl, $pathUrl), 'OrderRetrieveResponse');

        return $result;
    }

    /**
     * Cancels Order
     * - Sends to PayU OrderCancelRequest
     *
     * @access public
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return OpenPayU_Result $result Response array with OrderCancelResponse
     * @throws OpenPayU_Exception
     */
    public static function cancel($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Empty value of orderId');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        $result = self::verifyResponse(OpenPayU_Http::delete($pathUrl, $pathUrl), 'OrderCancelResponse');
        return $result;
    }

    /**
     * Updates Order status
     * - Sends to PayU OrderStatusUpdateRequest
     *
     * @access public
     * @param string $orderStatus A array containing full OrderStatus
     * @return OpenPayU_Result $result Response array with OrderStatusUpdateResponse
     * @throws OpenPayU_Exception
     */
    public static function statusUpdate($orderStatusUpdate)
    {
        $data = array();
        if (empty($orderStatusUpdate)) {
            throw new OpenPayU_Exception('Empty order status data');
        }

        if (OpenPayU_Configuration::getDataFormat() == 'xml') {
            $data = OpenPayU_Util::buildXmlFromArray($orderStatusUpdate, 'OrderStatusUpdateRequest', '2.0', 'UTF-8');
        } elseif (OpenPayU_Configuration::getDataFormat() == 'json') {
            $data = OpenPayU_Util::buildJsonFromArray($orderStatusUpdate);
        }
        $orderId = $orderStatusUpdate['orderId'];

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId . '/status';

        $result = self::verifyResponse(OpenPayU_Http::put($pathUrl, $data), 'OrderStatusUpdateResponse');
        return $result;
    }

    /**
     * Consume notification message
     *
     * @access public
     * @param $data Request array received from with PayU OrderNotifyRequest
     * @return null|OpenPayU_Result Response array with OrderNotifyRequest
     * @throws OpenPayU_Exception
     */
    public static function consumeNotification($data)
    {
        $sslConnection = self::isSecureConnection();;

        if (empty($data)) {
            throw new OpenPayU_Exception('Empty value of data');
        }

        $headers = OpenPayU_Util::getRequestHeaders();

        if (isset($headers['Content-Type'])) {
            if (strstr($headers['Content-Type'], 'application/xml')) {
                OpenPayU_Configuration::setDataFormat('xml');
            } elseif (strstr($headers['Content-Type'], 'application/json')) {
                OpenPayU_Configuration::setDataFormat('json');
            }
        }
        $incomingSignature = OpenPayU_HttpCurl::getSignature($headers);

        if ($sslConnection) {
            self::verifyBasicAuthCredentials();
        } else {
            self::verifyDocumentSignature($data, $incomingSignature);
        }

        return OpenPayU_Order::verifyResponse(array('response' => $data, 'code' => 200), 'OrderNotifyRequest');
    }

    /**
     * Verify response from PayU
     *
     * @param string $response
     * @param string $messageName
     * @return null|OpenPayU_Result
     */
    public
    static function verifyResponse($response, $messageName)
    {
        $data = array();
        $httpStatus = $response['code'];

        if (OpenPayU_Configuration::getDataFormat() == 'xml') {
            $message = OpenPayU_Util::parseXmlDocument($response['response']);
        } elseif (OpenPayU_Configuration::getDataFormat() == 'json') {
            $message = OpenPayU_Util::convertJsonToArray($response['response'], true);
        }

        if (isset($message[$messageName])) {
            $data['status'] = isset($message['status']['statusCode']) ? $message['status']['statusCode'] : null;
            unset($message[$messageName]['Status']);
            $data['response'] = $message[$messageName];
        } elseif (isset($message)) {
            $data['response'] = $message;
            $data['status'] = isset($message['status']['statusCode']) ? $message['status']['statusCode'] : null;
            unset($message['status']);
        }

        $result = self::build($data);

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 302) {
            return $result;
        } else {
            OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
        }

        return null;
    }

    /**
     * Generate a form body for hosted order
     *
     * @access public
     * @param $order An array containing full Order
     * @param $submitButtonContent An optional string to be placed as submit button's content
     * @param $submitButtonClass An optional string containing CSS classes to be applied to submit button
     * @return string Response html form
     */
    public static function hostedOrderForm($order, $submitButtonContent = '', $submitButtonClass = '')
    {
        $orderFormUrl = OpenPayU_Configuration::getServiceUrl() . 'order';

        $usortedFormFieldValuesAsArray = array();
        $htmlFormFields = OpenPayU_Util::convertArrayToHtmlForm($order, "", $usortedFormFieldValuesAsArray);
        ksort($usortedFormFieldValuesAsArray);
        $sortedFormFieldValuesAsString = implode('', array_values($usortedFormFieldValuesAsArray));

        $signature = OpenPayU_Util::generateSignData(
            $sortedFormFieldValuesAsString,
            OpenPayU_Configuration::getHashAlgorithm(),
            OpenPayU_Configuration::getMerchantPosId(),
            OpenPayU_Configuration::getSignatureKey()
        );

        $htmlOutput = sprintf("<form method=\"POST\" action=\"%s\" id=\"payu-payment-form\">\n", $orderFormUrl);
        $htmlOutput .= $htmlFormFields;
        $htmlOutput .= sprintf('<input type="hidden" name="OpenPayu-Signature" value="%s" />', $signature);
        $htmlOutput .= sprintf("<button type=\"submit\" formtarget=\"_blank\" class=\"%s\">%s</button>", $submitButtonClass, $submitButtonContent);
        $htmlOutput .= "</form>\n";

        return $htmlOutput;
    }
}