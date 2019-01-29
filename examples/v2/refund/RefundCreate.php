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
    <script type="text/javascript" src="../../layout/js/jquery.min.js"></script>
</head>
<body>
<script type="text/javascript">
    $(document).ready(function(){
        $('#amount').blur(function(){
            if($('#amount').val()!= 0 && $('#amount').val()<200){
                $('#msg').html('<div class="alert alert-danger">Kwota zwrotu częściowego nie może być mniejsza niż 200 ' +
                    'groszy!</div>')
            }else{
                $('#msg').html('')
            }
        })
    })

</script>
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

                $status_desc = OpenPayU_Util::statusDesc($refund->getStatus());
                if($refund->getStatus() == 'SUCCESS'){
                    echo '<div class="alert alert-success">SUCCESS: '.$status_desc;
                    echo '</div>';
                }else{
                    echo '<div class="alert alert-warning">'.$refund->getStatus().': '.$status_desc;
                    echo '</div>';
                }

                echo '<pre>';
                echo '<br>';
                print_r($refund->getResponse());
                echo '</pre>';

            } catch (OpenPayU_Exception $e) {
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

                    <div id="msg"></div>
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

