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
    require_once realpath(dirname(__FILE__)) . '/../../config.php';

    $order = array();

    $order['ContinueUrl'] = 'http://localhost/';
    $order['NotifyUrl'] = 'http://localhost/';
    $order['CustomerIp'] = '127.0.0.1';
    $order['MerchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
    $order['Description'] = 'New order';
    $order['CurrencyCode'] = 'PLN';
    $order['TotalAmount'] = 3200;
    $order['ExtOrderId'] = '1342';

    $order['Products']['Product'][0]['Name'] = 'Product1';
    $order['Products']['Product'][0]['UnitPrice'] = 1000;
    $order['Products']['Product'][0]['Quantity'] = 1;

    $order['Products']['Product'][1]['Name'] = 'Product2';
    $order['Products']['Product'][1]['UnitPrice'] = 2200;
    $order['Products']['Product'][1]['Quantity'] = 1;

    $order['Buyer']['Email'] = 'dd@ddd.pl';
    $order['Buyer']['Phone'] = '123123123';
    $order['Buyer']['FirstName'] = 'Jan';
    $order['Buyer']['LastName'] = 'Kowalski';

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
            <h1>Generated Order Form - OpenPayU v2</h1>
        </div>
        <div id="message"></div>
        <div id="unregisteredCardData">
<?php
    var_dump($order);
    echo $rsp
?>
        </div>
    </div>
    </html>




