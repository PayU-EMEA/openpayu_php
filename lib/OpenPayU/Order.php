<?php
/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
*/

namespace OpenPayuSdk\OpenPayu;

if (!defined('OPENPAYU_LIBRARY'))
    exit;

class OpenPayU_Order extends OpenPayU
{
    /**
     * Function sending Order to PayU Service
     * @access public
     * @param array $order
     * @param bool $debug
     * @return object $result
     */
    public static function create($order, $debug = TRUE)
    {
        // preparing payu service for order initialization
        $OrderCreateRequestUrl = OpenPayU_Configuration::getServiceUrl() . 'co/openpayu/OrderCreateRequest';

        if ($debug)
            OpenPayU::addOutputConsole('OpenPayU endpoint for OrderCreateRequest message', $OrderCreateRequestUrl);

        // convert array to openpayu document
        $xml = OpenPayU::buildOrderCreateRequest($order);

        if ($debug)
            OpenPayU::addOutputConsole('OrderCreateRequest message', htmlentities($xml));

        $response = OpenPayU_Http::post($OrderCreateRequestUrl, $xml);
        $response = $response['response'];

        if ($debug)
            OpenPayU::addOutputConsole('OrderCreateRequest message', htmlentities($response));

        // verify response from PayU service
        $status = OpenPayU::verifyOrderCreateResponse($response);

        if ($debug)
            OpenPayU::addOutputConsole('OrderCreateResponse status', serialize($status));

        $result = new OpenPayU_Result();
        $result->setStatus($status);
        $result->setError($status['StatusCode']);

        if (isset($status['StatusDesc']))
            $result->setMessage($status['StatusDesc']);

        $result->setSuccess($status['StatusCode'] == 'OPENPAYU_SUCCESS' ? TRUE : FALSE);
        $result->setRequest($order);
        $result->setResponse(OpenPayU_Util::parseXmlDocument($response));

        return $result;
    }

    /**
     * Function retrieving Order data from PayU Service
     * @access public
     * @param string $sessionId
     * @param bool $debug
     * @return OpenPayU_Result $result
     */
    public static function retrieve($sessionId, $debug = TRUE)
    {
        $req = array(
            'ReqId' => md5(rand()),
            'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
            'SessionId' => $sessionId
        );

        // preparing payu service for order initialization
        $OrderRetrieveRequestUrl = OpenPayU_Configuration::getServiceUrl() . 'co/openpayu/OrderRetrieveRequest';

        if ($debug)
            OpenPayU::addOutputConsole('OpenPayU endpoint for OrderRetrieveRequest message', $OrderRetrieveRequestUrl);

        $oauthResult = OpenPayu_OAuth::accessTokenByClientCredentials();

        $OrderRetrieveRequestUrl .= '?oauth_token=' . $oauthResult->getAccessToken();

        $xml = OpenPayU::buildOrderRetrieveRequest($req);

        if ($debug)
            OpenPayU::addOutputConsole('OrderRetrieveRequest message', htmlentities($xml));

        $response = OpenPayU_Http::post($OrderRetrieveRequestUrl, $xml);
        $response = $response['response'];

        if ($debug)
            OpenPayU::addOutputConsole('OrderRetrieveResponse message', htmlentities($response));

        $status = OpenPayU::verifyOrderRetrieveResponseStatus($response);

        if ($debug)
            OpenPayU::addOutputConsole('OrderRetrieveResponse status', serialize($status));

        $result = new OpenPayU_Result();
        $result->setStatus($status);
        $result->setError($status['StatusCode']);

        if (isset($status['StatusDesc']))
            $result->setMessage($status['StatusDesc']);

        $result->setSuccess($status['StatusCode'] == 'OPENPAYU_SUCCESS' ? TRUE : FALSE);
        $result->setRequest($req);
        $result->setResponse($response);

        try {
            $assoc = OpenPayU_Util::parseXmlDocument($response);
            $result->setResponse($assoc);
        } catch (Exception $ex) {
            if ($debug)
                OpenPayU::addOutputConsole('OrderRetrieveResponse parse result exception', $ex->getMessage());
        }

        return $result;
    }

    /**
     * Function consume message
     * @access public
     * @param string $xml
     * @param boolean $response Show Response Xml
     * @param bool $debug
     * @return object $result
     */
    public static function consumeMessage($xml, $response = TRUE, $debug = TRUE)
    {
        $xml = stripslashes(urldecode($xml));
        $rq = OpenPayU_Util::parseXmlDocument($xml);

        $msg = $rq['OpenPayU']['OrderDomainRequest'];

        switch (key($msg)) {
            case 'OrderNotifyRequest':
                return self::consumeNotification($xml, $response, $debug);
                break;
            case 'ShippingCostRetrieveRequest':
                return self::consumeShippingCostRetrieveRequest($xml, $debug);
                break;
            default:
                return key($msg);
                break;
        }
    }

    /**
     * Function consume notification message
     * @access private
     * @param string $xml
     * @param boolean $response Show Response Xml
     * @param bool $debug
     * @return OpenPayU_Result $result
     */
    public static function consumeNotification($xml, $response = TRUE, $debug = TRUE)
    {
        if ($debug)
            OpenPayU::addOutputConsole('OrderNotifyRequest message', $xml);

        $xml = stripslashes(urldecode($xml));
        $request = OpenPayU_Util::parseXmlDocument($xml);

        $document = $request['OpenPayU']['OrderDomainRequest']['OrderNotifyRequest'];

        $reqId = $document['ReqId'];
        $sessionId = $document['SessionId'];

        if ($debug)
            OpenPayU::addOutputConsole('OrderNotifyRequest data, reqId', $reqId . ', sessionId: ' . $sessionId);

        // response to payu service
        $rsp = OpenPayU::buildOrderNotifyResponse($reqId);
        if ($debug)
            OpenPayU::addOutputConsole('OrderNotifyResponse message', $rsp);

        // show response
        if ($response == TRUE) {
            header("Content-Type:text/xml");
            echo $rsp;
        }

        // create OpenPayU Result object
        $result = new OpenPayU_Result();
        $result->setSessionId($sessionId);
        $result->setSuccess(TRUE);
        $result->setRequest($request);
        $result->setResponse($rsp);
        $result->setMessage('OrderNotifyRequest');

        // if everything is alright return full data sent from payu service to client
        return $result;
    }

    /**
     * Function consume shipping cost calculation request message
     * @access private
     * @param string $xml
     * @param bool $debug
     * @return OpenPayU_Result $result
     */
    public static function consumeShippingCostRetrieveRequest($xml, $debug = TRUE)
    {
        if ($debug)
            OpenPayU::addOutputConsole('consumeShippingCostRetrieveRequest message', $xml);

        $request = OpenPayU_Util::parseXmlDocument($xml);

        $document = $request['OpenPayU']['OrderDomainRequest']['ShippingCostRetrieveRequest'];

        $result = new OpenPayU_Result();
        $result->setCountryCode($document['CountryCode']);
        $result->setSessionId($document['SessionId']);
        $result->setReqId($document['ReqId']);
        $result->setMessage('ShippingCostRetrieveRequest');

        if ($debug)
            OpenPayU::addOutputConsole('consumeShippingCostRetrieveRequest reqId', $result->getReqId() . ', countryCode: ' . $result->getCountryCode());

        return $result;
    }

    /**
     * Function use to cancel
     * @access public
     * @param string $sessionId
     * @param bool $debug
     * @return OpenPayU_Result $result
     */
    public static function cancel($sessionId, $debug = TRUE)
    {

        $rq = array(
            'ReqId' => md5(rand()),
            'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
            'SessionId' => $sessionId
        );

        $result = new OpenPayU_Result();
        $result->setRequest($rq);

        $OrderCancelRequestUrl = OpenPayU_Configuration::getServiceUrl() . 'co/openpayu/OrderCancelRequest';

        if ($debug)
            OpenPayU::addOutputConsole('OpenPayU endpoint for OrderCancelRequest message', $OrderCancelRequestUrl);

        $oauthResult = OpenPayu_OAuth::accessTokenByClientCredentials();

        $xml = OpenPayU::buildOrderCancelRequest($rq);

        if ($debug)
            OpenPayU::addOutputConsole('OrderCancelRequest message', htmlentities($xml));

        $response = OpenPayU_Http::post($OrderCancelRequestUrl . '?oauth_token=' . $oauthResult->getAccessToken(), $xml);
        $response = $response['response'];

        if ($debug)
            OpenPayU::addOutputConsole('OrderCancelResponse message', htmlentities($response));

        // verify response from PayU service
        $status = OpenPayU::verifyOrderCancelResponseStatus($response);

        if ($debug)
            OpenPayU::addOutputConsole('OrderCancelResponse status', serialize($status));

        $result->setStatus($status);
        $result->setError($status['StatusCode']);

        if (isset($status['StatusDesc']))
            $result->setMessage($status['StatusDesc']);

        $result->setSuccess($status['StatusCode'] == 'OPENPAYU_SUCCESS' ? TRUE : FALSE);
        $result->setResponse(OpenPayU_Util::parseXmlDocument($response));

        return $result;
    }

    /**
     * Function use to update status
     * @access public
     * @param string $sessionId
     * @param string $status
     * @param bool $debug
     * @return OpenPayU_Result $result
     */
    public static function updateStatus($sessionId, $status, $debug = TRUE)
    {

        $rq = array(
            'ReqId' => md5(rand()),
            'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
            'SessionId' => $sessionId,
            'OrderStatus' => $status,
            'Timestamp' => date('c')
        );

        $result = new OpenPayU_Result();
        $result->setRequest($rq);

        $url = OpenPayU_Configuration::getServiceUrl() . 'co/openpayu/OrderStatusUpdateRequest';

        if ($debug)
            OpenPayU::addOutputConsole('OpenPayU endpoint for OrderStatusUpdateRequest message', $url);

        $oauthResult = OpenPayu_OAuth::accessTokenByClientCredentials();
        $url .= '?oauth_token=' . $oauthResult->getAccessToken();

        $xml = OpenPayU::buildOrderStatusUpdateRequest($rq);

        if ($debug)
            OpenPayU::addOutputConsole('OrderStatusUpdateRequest message', htmlentities($xml));

        $response = OpenPayU_Http::post($url, $xml);
        $response = $response['response'];

        if ($debug)
            OpenPayU::addOutputConsole('OrderStatusUpdateResponse message', htmlentities($response));

        // verify response from PayU service
        $status = OpenPayU::verifyOrderStatusUpdateResponseStatus($response);

        if ($debug)
            OpenPayU::addOutputConsole('OrderStatusUpdateResponse status', serialize($status));

        $result->setStatus($status);
        $result->setError($status['StatusCode']);

        if (isset($status['StatusDesc']))
            $result->setMessage($status['StatusDesc']);

        $result->setSuccess($status['StatusCode'] == 'OPENPAYU_SUCCESS' ? TRUE : FALSE);
        $result->setResponse(OpenPayU_Util::parseXmlDocument($response));

        return $result;
    }
}