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

    OpenPayU_Configuration::setApiVersion(2);
    require_once realpath(dirname(__FILE__)) . '/../../config.php';

    $order = array();

    //$order['ContinueUrl'] = 'http://localhost/';
    $order['notifyUrl'] = 'http://localhost/'; //ok
    $order['customerIp'] = $_SERVER['REMOTE_ADDR']; //ok
    $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId(); //ok
    $order['description'] = 'New order SDK'; //ok
    $order['currencyCode'] = 'PLN'; //ok
    $order['totalAmount'] = 1000; //ok

    $order['buyer']['email'] = 'test@exmaple.com';
    $order['buyer']['phone'] = '000000000';
    $order['buyer']['firstName'] = 'John';
    $order['buyer']['lastName'] = 'Kowalski';

    $order['products']['products'][0]['name'] = 'Product first';
    $order['products']['products'][0]['unitPrice'] = 1000;
    $order['products']['products'][0]['quantity'] = 1;

    $order['shippingMethods']['shippingMethods']['0']['price'] = 1000;
    $order['shippingMethods']['shippingMethods']['0']['country'] = 'PL';
    $order['shippingMethods']['shippingMethods']['0']['name'] = 'Courier Express';

    $rsp = OpenPayU_Order::create($order);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Cancel - OpenPayU v2</title>
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




