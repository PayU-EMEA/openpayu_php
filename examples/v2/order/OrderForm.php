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

$order = array();

$order['notifyUrl'] = 'http://localhost'.dirname($_SERVER['REQUEST_URI']).'/OrderNotify.php';
$order['continueUrl'] = 'http://localhost'.dirname($_SERVER['REQUEST_URI']).'/../../layout/success.php';

$order['customerIp'] = '127.0.0.1';
$order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
$order['description'] = 'New order';
$order['currencyCode'] = 'PLN';
$order['totalAmount'] = 3200;
$order['extOrderId'] = uniqid('', true);

$order['products'][0]['name'] = 'Product1';
$order['products'][0]['unitPrice'] = 1000;
$order['products'][0]['quantity'] = 1;

$order['products'][1]['name'] = 'Product2';
$order['products'][1]['unitPrice'] = 2200;
$order['products'][1]['quantity'] = 1;

$order['buyer']['email'] = 'dd@ddd.pl';
$order['buyer']['phone'] = '123123123';
$order['buyer']['firstName'] = 'Jan';
$order['buyer']['lastName'] = 'Kowalski';
$order['buyer']['language'] = 'en';

$rsp = OpenPayU_Order::hostedOrderForm($order);
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Generated Order Form - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
    <style type="text/css">
        #payu-payment-form button[type=submit]{
            border: 0px;
            height: 50px;
            width: 290px;
            background: url('http://static.payu.com/pl/standard/partners/buttons/payu_account_button_long_03.png') no-repeat;
            cursor: pointer;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Generated Order Form - OpenPayU v2.1</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <pre>
            <?php echo htmlentities($rsp); ?>
        </pre>
        <?php echo $rsp; ?>

    </div>
</div>
</html>