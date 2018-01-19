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
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Cancel - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
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
                $status_desc = OpenPayU_Util::statusDesc($response->getStatus());
                if($response->getStatus() == 'SUCCESS'){
                    echo '<div class="alert alert-success">SUCCESS: '.$status_desc;
                    echo '</div>';
                }else{
                    echo '<div class="alert alert-warning">'.$response->getStatus().': '.$status_desc;
                    echo '</div>';
                }

                echo '<pre>';
                echo '<br>';
                print_r($response->getResponse());
                echo '</pre>';
            }catch (OpenPayU_Exception $e) {
                echo '<pre>';
                echo 'Error code: '.$e->getCode();
                echo '<br>';
                echo 'Error message: '.$e->getMessage();
                echo '<br>';
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

