<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Status Update - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order status update - OpenPayU v2</h1>
    </div>
    <div id="message"></div>
    <div id="unregisteredCardData">
        <?php
        if (isset($_POST['orderId'])) {
            try {

                $status_update = array(
                    "orderId" => stripslashes($_POST['orderId']),
                    "orderStatus" => stripslashes($_POST['orderStatus'])
                );

                $response = OpenPayU_Order::statusUpdate($status_update);

                echo '<pre>';
                var_dump($response);
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
                    <label class="control-label" for="status">Status</label>
                    <div class="controls">
                        <select name="orderStatus" id="status">
                            <option value="COMPLETED">COMPLETED</option>
                            <option value="NEW">NEW</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pay-button"></label>

                    <div class="controls">
                        <button class="btn btn-success" id="pay-button" type="submit">Update order status</button>
                    </div>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</div>
</html>

