<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2018 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

/**
 * Class OpenPayU_Order
 */
class OpenPayU_Order extends OpenPayU
{
    const ORDER_SERVICE = 'orders/';
    const ORDER_TRANSACTION_SERVICE = 'transactions';
    const SUCCESS = 'SUCCESS';

    /**
     * @var array Default form parameters
     */
    protected static $defaultFormParams = array(
        'formClass' => '',
        'formId' => 'payu-payment-form',
        'submitClass' => '',
        'submitId' => '',
        'submitContent' => '',
        'submitTarget' => '_blank'
    );

    /**
     * Creates new Order
     * - Sends to PayU OrderCreateRequest
     *
     * @param array $order A array containing full Order
     * @return object $result Response array with OrderCreateResponse
     * @throws OpenPayU_Exception
     */
    public static function create($order)
    {
        $data = OpenPayU_Util::buildJsonFromArray($order);

        if (empty($data)) {
            throw new OpenPayU_Exception('Empty message OrderCreateRequest');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE;

        $result = self::verifyResponse(OpenPayU_Http::doPost($pathUrl, $data, $authType), 'OrderCreateResponse');

        return $result;
    }

    /**
     * Retrieves information about the order
     *  - Sends to PayU OrderRetrieveRequest
     *
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return OpenPayU_Result $result Response array with OrderRetrieveResponse
     * @throws OpenPayU_Exception
     */
    public static function retrieve($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Empty value of orderId');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        $result = self::verifyResponse(OpenPayU_Http::doGet($pathUrl, $authType), 'OrderRetrieveResponse');

        return $result;
    }

    /**
     * Retrieves information about the order transaction
     *  - Sends to PayU TransactionRetrieveRequest
     *
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return OpenPayU_Result $result Response array with TransactionRetrieveResponse
     * @throws OpenPayU_Exception
     */
    public static function retrieveTransaction($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Empty value of orderId');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId . '/' . self::ORDER_TRANSACTION_SERVICE;

        $result = self::verifyResponse(OpenPayU_Http::doGet($pathUrl, $authType), 'TransactionRetrieveResponse');

        return $result;
    }

    /**
     * Cancels Order
     * - Sends to PayU OrderCancelRequest
     *
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return OpenPayU_Result $result Response array with OrderCancelResponse
     * @throws OpenPayU_Exception
     */
    public static function cancel($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayU_Exception('Empty value of orderId');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        $result = self::verifyResponse(OpenPayU_Http::doDelete($pathUrl, $authType), 'OrderCancelResponse');
        return $result;
    }

    /**
     * Updates Order status
     * - Sends to PayU OrderStatusUpdateRequest
     *
     * @param array $orderStatusUpdate A array containing full OrderStatus
     * @return OpenPayU_Result $result Response array with OrderStatusUpdateResponse
     * @throws OpenPayU_Exception
     */
    public static function statusUpdate($orderStatusUpdate)
    {
        if (empty($orderStatusUpdate)) {
            throw new OpenPayU_Exception('Empty order status data');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        $data = OpenPayU_Util::buildJsonFromArray($orderStatusUpdate);
        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderStatusUpdate['orderId'] . '/status';

        $result = self::verifyResponse(OpenPayU_Http::doPut($pathUrl, $data, $authType), 'OrderStatusUpdateResponse');

        return $result;
    }

    /**
     * Consume notification message
     *
     * @access public
     * @param $data string Request array received from with PayU OrderNotifyRequest
     * @return null|OpenPayU_Result Response array with OrderNotifyRequest
     * @throws OpenPayU_Exception
     */
    public static function consumeNotification($data)
    {
        if (empty($data)) {
            throw new OpenPayU_Exception('Empty value of data');
        }

        $headers = OpenPayU_Util::getRequestHeaders();
        $incomingSignature = OpenPayU_HttpCurl::getSignature($headers);

        self::verifyDocumentSignature($data, $incomingSignature);

        return OpenPayU_Order::verifyResponse(array('response' => $data, 'code' => 200), 'OrderNotifyRequest');
    }

    /**
     * Verify response from PayU
     *
     * @param array $response
     * @param string $messageName
     * @return null|OpenPayU_Result
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    public static function verifyResponse($response, $messageName)
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

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 301 || $httpStatus == 302) {
            return $result;
        }

        OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
    }

    /**
     * Generate a form body for hosted order
     *
     * @access public
     * @param array $order an array containing full Order
     * @param array $params an optional array with form elements' params
     * @return string Response html form
     * @throws OpenPayU_Exception_Configuration
     */
    public static function hostedOrderForm($order, $params = array())
    {
        $orderFormUrl = OpenPayU_Configuration::getServiceUrl() . 'orders';

        $formFieldValuesAsArray = array();
        $htmlFormFields = OpenPayU_Util::convertArrayToHtmlForm($order, '', $formFieldValuesAsArray);

        $signature = OpenPayU_Util::generateSignData(
            $formFieldValuesAsArray,
            OpenPayU_Configuration::getHashAlgorithm(),
            OpenPayU_Configuration::getMerchantPosId(),
            OpenPayU_Configuration::getSignatureKey()
        );

        $formParams = array_merge(self::$defaultFormParams, $params);

        $htmlOutput = sprintf("<form method=\"POST\" action=\"%s\" id=\"%s\" class=\"%s\">\n", $orderFormUrl, $formParams['formId'], $formParams['formClass']);
        $htmlOutput .= $htmlFormFields;
        $htmlOutput .= sprintf("<input type=\"hidden\" name=\"OpenPayu-Signature\" value=\"%s\" />", $signature);
        $htmlOutput .= sprintf("<button type=\"submit\" formtarget=\"%s\" id=\"%s\" class=\"%s\">%s</button>", $formParams['submitTarget'], $formParams['submitId'], $formParams['submitClass'], $formParams['submitContent']);
        $htmlOutput .= "</form>\n";

        return $htmlOutput;
    }

}
