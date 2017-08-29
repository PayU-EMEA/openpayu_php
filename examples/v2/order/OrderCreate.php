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
$order['merchantPosId'] = OpenPayU_Configuration::getOauthClientId() ? OpenPayU_Configuration::getOauthClientId() : OpenPayU_Configuration::getMerchantPosId();
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

$order['buyer']['email'] = 'test_buyer_email@payu.com';
$order['buyer']['phone'] = '123123123';
$order['buyer']['firstName'] = 'Jan';
$order['buyer']['lastName'] = 'Kowalski';
$order['buyer']['language'] = 'en';

/*~~~~~~~~ optional part DELIVERY data ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
//Add delivery informations
$order['buyer']['delivery']['recipientName'] = 'Robert Nowak';
$order['buyer']['delivery']['recipientEmail'] = 'test_buyer_email@payu.com';
$order['buyer']['delivery']['recipientPhone'] = '+48 456 123 789';
$order['buyer']['delivery']['street'] = 'Bar St. 155';
$order['buyer']['delivery']['postalBox'] = 'Warsaw';
$order['buyer']['delivery']['postalCode'] = '22-222';
$order['buyer']['delivery']['city'] = 'Warsaw';
$order['buyer']['delivery']['state'] = 'Masovian district';
$order['buyer']['delivery']['countryCode'] = 'PL';



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
    <?php try {
        $response = OpenPayU_Order::create($order);
        $status_desc = OpenPayU_Util::statusDesc($response->getStatus());
        if ($response->getStatus() == 'SUCCESS') {
            echo '<div class="alert alert-success">SUCCESS: ' . $status_desc;
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning">' . $response->getStatus() . ': ' . $status_desc;
            echo '</div>';
        }
    } catch (OpenPayU_Exception $e) {
        echo '<pre>';
        var_dump((string)$e);
        echo '</pre>';
    }
    ?>

    <h1>Request</h1>

    <div id="unregisteredCardData">
        <?php var_dump($order); ?>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th colspan="2">Important data from response</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Order status</td>
            <td><?= $response->getStatus() ?></td>
        </tr>
        <?php if ($response->getStatus() == 'SUCCESS'): ?>
            <tr>
                <td>Order id</td>
                <td><?= $response->getResponse()->orderId ?></td>
            </tr>
            <tr>
                <td>Redirect Uri</td>
                <td><a href="<?= $response->getResponse()->redirectUri ?>"><?= $response->getResponse()->redirectUri ?></a>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <h1>Response</h1>

    <div id="unregisteredCardData">
        <?php var_dump($response); ?>
    </div>
</div>
</html>