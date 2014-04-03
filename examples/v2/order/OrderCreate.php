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

    $order['continueUrl'] = 'http://localhost/';
    $order['notifyUrl'] = 'http://localhost/';
    $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
    $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
    $order['description'] = 'New order';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 3200;
    $order['extOrderId'] = '1342';

    $order['products']['products'][0]['name'] = 'Product1';
    $order['products']['products'][0]['unitPrice'] = 1000;
    $order['products']['products'][0]['quantity'] = 1;

    $order['products']['products'][1]['name'] = 'Product2';
    $order['products']['products'][1]['unitPrice'] = 2200;
    $order['products']['products'][1]['quantity'] = 1;

    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';

    $response = OpenPayU_Order::create($order);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Create Order - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Create Order - OpenPayU v2</h1>
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




