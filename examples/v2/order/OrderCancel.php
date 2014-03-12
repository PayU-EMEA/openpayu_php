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


?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Cancel - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Cancel order - OpenPayU v2</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <?php
        if (isset($_POST['orderId'])) {
            try {
                $response = OpenPayU_Order::cancel(stripslashes($_POST['orderId']));
                echo '<pre>';
                var_dump($response->Status);
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
                    <label class="control-label" for="pay-button"></label>

                    <div class="controls">
                        <button class="btn btn-success" id="pay-button" type="submit">Cancel order</button>
                    </div>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</div>
</html>

