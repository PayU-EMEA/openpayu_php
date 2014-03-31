<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2013 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';

OpenPayU_Configuration::setApiVersion(2);
require_once realpath(dirname(__FILE__)) . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = file_get_contents('php://input');
    $data = stripslashes(trim($body));

    try{
        if (!empty($data)) {
            $result = OpenPayU_Order::consumeNotification($data);
        }

        if ($result->Response->Order->OrderId) {

            /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
            $order = OpenPayU_Order::retrieve($result->Response->Order->OrderId);

            /* If exists return OrderNotifyResponse */
            $rsp = OpenPayU::buildOrderNotifyResponse($result->Response->Order->OrderId);

            if (!empty($rsp)) {
                if (OpenPayU_Configuration::getDataFormat() == 'xml') {
                    header("Content-Type: text/xml");
                    echo $rsp;
                } elseif (OpenPayU_Configuration::getDataFormat() == 'json') {
                    header("Content-Type: application/json");
                    echo json_encode(OpenPayU_Util::parseXmlDocument(stripslashes($rsp)));
                }
            }
        }
    }
    catch(OpenPayU_Exception $e)
    {
        echo $e->getMessage();
    }
}