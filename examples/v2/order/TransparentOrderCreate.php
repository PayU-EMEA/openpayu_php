<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */

    require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
    require_once realpath(dirname(__FILE__)) . '/../../config.php';

    $order = array();

    $order['notifyUrl'] = 'http://localhost/';
    $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
    $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();

    //$order['extOrderId'] = "ORDER_74632";

    $order['description'] = 'New order SDK';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 1000;

    $order['buyer']['email'] = 'test@exmaple.com';
    $order['buyer']['phone'] = '000000000';
    $order['buyer']['firstName'] = 'John';
    $order['buyer']['lastName'] = 'Kowalski';

    $order['buyer']['email'] = 'test@exmaple.com';
    $order['buyer']['phone'] = '000000000';
    $order['buyer']['firstName'] = 'John';
    $order['buyer']['lastName'] = 'Kowalski';
    $order['products']['products'][0]['name'] = 'Product first';
    $order['products']['products'][0]['unitPrice'] = 1000;
    $order['products']['products'][0]['quantity'] = 1;

    $response = OpenPayU_Order::create($order);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Transparent Order Create - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Transparent Order Create - OpenPayU v2</h1>
    </div>
    <h1>Request</h1>
    <div id="unregisteredCardData">
        <?php var_dump($order); ?>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <tr><th colspan="2">Important data from response</th></tr>
        </thead>
        <tbody>
        <tr><td>Order status</td><td><?=$response->getStatus()?></td></tr>
        <tr><td>Order id</td><td><?=$response->getResponse()->orderId?></td></tr>
        <tr><td>Redirect Uri</td><td><a href="<?=$response->getResponse()->redirectUri?>"><?=$response->getResponse()->redirectUri?></a></td></tr>
        </tbody>
    </table>
    <h1>Response</h1>
    <div id="unregisteredCardData">
        <?php var_dump($response); ?>
    </div>
</div>
</html>




