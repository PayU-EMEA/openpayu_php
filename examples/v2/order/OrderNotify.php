<?php

/**
 * OpenPayU Examples
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = file_get_contents('php://input');
    $data = trim($body);

    try {
        if (!empty($data)) {
            $result = OpenPayU_Order::consumeNotification($data);
        }

        if ($result->getResponse()->order->orderId) {

            /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
            $order = OpenPayU_Order::retrieve($result->getResponse()->order->orderId);
            if($order->getStatus() == 'SUCCESS'){
                //the response should be status 200
                header("HTTP/1.1 200 OK");
            }
        }
    } catch (OpenPayU_Exception $e) {
        echo $e->getMessage();
    }
}