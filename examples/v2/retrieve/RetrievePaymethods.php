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
    <title>Retrieve Pay Methods - OpenPayU v2.1</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Retrieve Pay Methods - OpenPayU v2.1</h1>
    </div>
    <?php
    $response = null;
    try {
        $response = OpenPayU_Retrieve::payMethods();
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

    <?php if ($response && $response->getStatus() == 'SUCCESS'): ?>
        <h1>Pay Methods</h1>

        <?php
        $payMethods = $response->getResponse();
        ?>

        <table class="table table-hover table-bordered">
            <tr>
                <th colspan="4">Pay By Links</th>
            </tr>
            <tr>
                <td>payType</td>
                <td>Name of payType</td>
                <td>Status</td>
                <td style="width: 120px">Image</td>
            </tr>
            <?php if ($payMethods->payByLinks):
                foreach ($payMethods->payByLinks as $payByLink):
                    ?>
                    <tr>
                        <td><?php echo $payByLink->value; ?></td>
                        <td><?php echo $payByLink->name; ?></td>
                        <td><?php echo $payByLink->status; ?></td>
                        <td><img src="<?php echo $payByLink->brandImageUrl; ?>" style="width: 100px"></td>
                    </tr>
                    <?php
                endforeach;
            endif;
            ?>
            <?php if ($payMethods->cardTokens): ?>
                <tr>
                    <th colspan="4">Card Tokens</th>
                </tr>
                <tr>
                    <td>Card Info</td>
                    <td>Token</td>
                    <td>Status</td>
                    <td style="width: 120px">Image</td>
                </tr>

                <?php foreach ($payMethods->cardTokens as $cardToken):
                    print_r($cardToken);
                    ?>
                    <tr>
                        <td>Card: <?php echo $cardToken->cardNumberMasked; ?><br>
                            Expiry: <?php echo $cardToken->cardExpirationMonth; ?> / <?php echo $cardToken->cardExpirationYear; ?><br>
                            Brand:<?php echo $cardToken->cardBrand; ?>
                        </td>
                        <td><?php echo $cardToken->value; ?></td>
                        <td><?php echo $cardToken->status; ?></td>
                        <td><img src="<?php echo $cardToken->brandImageUrl; ?>" style="width: 100px"></td>
                    </tr>
                    <?php
                endforeach;
            endif;
            ?>
        </table>

    <?php endif; ?>

    <h1>Response</h1>
    <div id="unregisteredCardData">
        <?php var_dump($response); ?>
    </div>
</div>
</html>