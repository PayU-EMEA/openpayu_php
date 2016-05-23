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
    <title>Retrive Paymethods - OpenPayU v2</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Retrive Paymethods - OpenPayU v2</h1>
    </div>
    <?php
    $response = null;
    try {
        $response = OpenPayU_Retrive::paymethods();
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
        <h1>Paymethods</h1>

        <?php
            $paymethods = $response->getResponse();
        ?>

        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th colspan="4">payByLinks</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>payType</td>
                <td>Name of payType</td>
                <td>Status</td>
                <td style="width: 120px">Image</td>
            </tr>
            <?php if ($paymethods->payByLinks):
                foreach ($paymethods->payByLinks as $payByLink):
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
            </tbody>
        </table>

    <?php endif; ?>


    <h1>Response</h1>
    <div id="unregisteredCardData">
        <?php var_dump($response); ?>
    </div>
</div>
</html>