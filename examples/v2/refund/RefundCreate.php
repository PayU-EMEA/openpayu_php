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

                switch ($refund->getStatus()){
                    case 'SUCCESS':
                        echo '<div class="alert alert-success">Żądanie zostało wykonane poprawnie. Poniżej znajdziesz odpowiedź serwera.</div>';
                        echo '<pre>';
                        echo '<br>';
                        var_dump($refund->getResponse()->refund);
                        echo '</pre>';
                        break;
                    case 'DATA_NOT_FOUND':
                        echo '<div class="alert alert-warning">DATA_NOT_FOUND: W systemie PayU brak danych, które wskazano w żądaniu.</div>';
                        break;
                    case 'WARNING_CONTINUE_3_DS':
                        echo '<div class="alert alert-warning">WARNING_CONTINUE_3_DS: Wymagana autoryzacja 3DS. System sprzedawcy musi wykonać przekierowanie w celu kontynuacji procesu płatności (można skorzystać z metody OpenPayU.authorize3DS()).</div>';
                        break;
                    case 'WARNING_CONTINUE_CVV':
                        echo '<div class="alert alert-warning">WARNING_CONTINUE_CVV: Wymagana autoryzacja CVV/CVC. Wywołaj metodę OpenPayU.authorizeCVV().</div>';
                        break;
                    case 'ERROR_SYNTAX':
                        echo '<div class="alert alert-warning">ERROR_SYNTAX: Błędna składnia żądania.</div>';
                        break;
                    case 'ERROR_VALUE_INVALID':
                        echo '<div class="alert alert-warning">ERROR_VALUE_INVALID: Jedna lub więcej wartości jest nieprawidłowa.</div>';
                        break;
                    case 'ERROR_VALUE_MISSING':
                        echo '<div class="alert alert-warning">ERROR_VALUE_MISSING: Brakuje jednej lub więcej wartości.</div>';
                        break;
                    case 'BUSINESS_ERROR':
                        echo '<div class="alert alert-warning">BUSINESS_ERROR: System PayU jest niedostępny. Spróbuj ponownie później.</div>';
                        break;
                    case 'ERROR_INTERNAL':
                        echo '<div class="alert alert-warning">ERROR_INTERNAL: System PayU jest niedostępny. Spróbuj ponownie później.</div>';
                        break;
                    case 'GENERAL_ERROR':
                        echo '<div class="alert alert-warning">GENERAL_ERROR: Wystąpił niespodziewany błąd. Spróbuj ponownie później.</div>';
                        break;
                }

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

