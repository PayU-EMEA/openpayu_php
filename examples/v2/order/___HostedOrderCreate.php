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

    $order['products']['product'][0]['name'] = 'Product1';
    $order['products']['product'][0]['unitPrice'] = 1000;
    $order['products']['product'][0]['quantity'] = 1;

    $order['products']['product'][1]['name'] = 'Product1';
    $order['products']['product'][1]['unitPrice'] = 2200;
    $order['products']['product'][1]['quantity'] = 1;

    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';
    $order['buyer']['language'] = 'pl_PL';

    $rsp = OpenPayU_Order::create($order);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Hosted Order Create - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
    <style type="text/css">
        #payu-payment-form button[type=submit]{
            border: 0px;
            height: 35px;
            width: 140px;
            background: url('http://static.payu.com/pl/standard/partners/buttons/payu_account_button_long_03.png');
            background-repeat: no-repeat;
            cursor: pointer;
        }
    </style>
</head>
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Hosted Order Form - OpenPayU v2</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <?php
        var_dump($order);
        var_dump($rsp);
        ?>
    </div>
</div>
</html>




