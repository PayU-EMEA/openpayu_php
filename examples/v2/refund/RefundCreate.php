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

if (isset($_POST['orderId']))
    $orderId = trim($_POST['orderId']);

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Refund</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Refund</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <?php
        if (isset($_POST['orderId'])) {
            $orderId = trim($_POST['orderId']);
            try {
                $refund = OpenPayU_Refund::create(
                    $orderId,
                    $_POST['description'],
                    isset($_POST['amount']) ? (int)$_POST['amount'] : null
                );

                echo '<pre>';
                var_dump($refund->Status->StatusCode);
                var_dump($refund->Response->Refund);
                echo '</pre>';
            } catch (OpenPayU_Exception $e) {
                echo '<pre>';
                var_dump((string)$e);
                echo '</pre>';
            }
        } else {
            ?>
            <form action="" method="post" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="order">Order Id</label>

                    <div class="controls">
                        <input class="span3" name="orderId" id="order" type="text" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="amount">Amount</label>

                    <div class="controls">
                        <input class="span1" name="amount" id="amount" type="text" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="description">Description</label>

                    <div class="controls">
                        <input class="span3" name="description" id="description" type="text" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pay-button"></label>

                    <div class="controls">
                        <button class="btn btn-success" id="pay-button" type="submit">Make refund</button>
                    </div>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</div>
</html>

