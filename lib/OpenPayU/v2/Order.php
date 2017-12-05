<?php

namespace PayU\OpenPayU\v2;

use PayU\OpenPayU\Configuration;
use PayU\OpenPayU\Exception\OpenPayUException;
use PayU\OpenPayU\Http;
use PayU\OpenPayU\HttpCurl;
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


/**
 * Class OpenPayU_Order
 */
class Order extends OpenPayU
{
    const ORDER_SERVICE = 'orders/';
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
     * @throws OpenPayUException
     */
    public static function create($order)
    {
        $data = Util::buildJsonFromArray($order);

        if (empty($data)) {
            throw new OpenPayUException('Empty message OrderCreateRequest');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        $pathUrl = Configuration::getServiceUrl() . self::ORDER_SERVICE;

        return self::verifyResponse(Http::doPost($pathUrl, $data, $authType), 'OrderCreateResponse');
    }

    /**
     * Retrieves information about the order
     *  - Sends to PayU OrderRetrieveRequest
     *
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return Result $result Response array with OrderRetrieveResponse
     * @throws OpenPayUException
     */
    public static function retrieve($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayUException('Empty value of orderId');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        $pathUrl = Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        return self::verifyResponse(Http::doGet($pathUrl, $authType), 'OrderRetrieveResponse');
    }

    /**
     * Cancels Order
     * - Sends to PayU OrderCancelRequest
     *
     * @param string $orderId PayU OrderId sent back in OrderCreateResponse
     * @return Result $result Response array with OrderCancelResponse
     * @throws OpenPayUException
     */
    public static function cancel($orderId)
    {
        if (empty($orderId)) {
            throw new OpenPayUException('Empty value of orderId');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        $pathUrl = Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderId;

        return self::verifyResponse(Http::doDelete($pathUrl, $authType), 'OrderCancelResponse');
    }

    /**
     * Updates Order status
     * - Sends to PayU OrderStatusUpdateRequest
     *
     * @param array $orderStatusUpdate A array containing full OrderStatus
     * @return Result $result Response array with OrderStatusUpdateResponse
     * @throws OpenPayUException
     */
    public static function statusUpdate($orderStatusUpdate)
    {
        if (empty($orderStatusUpdate)) {
            throw new OpenPayUException('Empty order status data');
        }

        try {
            $authType = self::getAuth();
        } catch (OpenPayUException $e) {
            throw new OpenPayUException($e->getMessage(), $e->getCode());
        }

        $data = Util::buildJsonFromArray($orderStatusUpdate);
        $pathUrl = Configuration::getServiceUrl() . self::ORDER_SERVICE . $orderStatusUpdate['orderId'] . '/status';

        return self::verifyResponse(Http::doPut($pathUrl, $data, $authType), 'OrderStatusUpdateResponse');
    }

    /**
     * Consume notification message
     *
     * @access public
     * @param $data string Request array received from with PayU OrderNotifyRequest
     * @return null|Result Response array with OrderNotifyRequest
     * @throws OpenPayUException
     */
    public static function consumeNotification($data)
    {
        if (empty($data)) {
            throw new OpenPayUException('Empty value of data');
        }

        $headers = Util::getRequestHeaders();
        $incomingSignature = HttpCurl::getSignature($headers);

        self::verifyDocumentSignature($data, $incomingSignature);

        return Order::verifyResponse(array('response' => $data, 'code' => 200), 'OrderNotifyRequest');
    }

    /**
     * Verify response from PayU
     *
     * @param array $response
     * @param string $messageName
     * @return null|Result
     */
    public static function verifyResponse($response, $messageName)
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

        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || $httpStatus == 301 || $httpStatus == 302)
        {
            return $result;
        } else {
            Http::throwHttpStatusException($httpStatus, $result);
        }
    }

    /**
     * Generate a form body for hosted order
     *
     * @access public
     * @param array $order an array containing full Order
     * @param array $params an optional array with form elements' params
     * @return string Response html form
     */
    public static function hostedOrderForm($order, $params = array())
    {
        $orderFormUrl = Configuration::getServiceUrl() . 'orders';

        $formFieldValuesAsArray = array();
        $htmlFormFields = Util::convertArrayToHtmlForm($order, '', $formFieldValuesAsArray);

        $signature = Util::generateSignData(
            $formFieldValuesAsArray,
            Configuration::getHashAlgorithm(),
            Configuration::getMerchantPosId(),
            Configuration::getSignatureKey()
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
