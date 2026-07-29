<?php

/**
 * OpenPayU Examples
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(__DIR__) . '/../../../lib/openpayu.php';
require_once realpath(__DIR__) . '/../../config.php';

$domain = trim($_POST['domain'] ?? '');
$displayName = trim($_POST['displayName'] ?? '');

?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Create Apple Pay Session</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Create Apple Pay Session</h1>
    </div>

    <?php
    if (!empty($domain) && !empty($displayName)) {
        try {
            $response = OpenPayU_ApplePay::createSession($domain, $displayName);
            $status_desc = OpenPayU_Util::statusDesc($response->getStatus());

            if ($response->getStatus() === 'SUCCESS') {
                echo '<div class="alert alert-success">SUCCESS: ' . $status_desc;
                echo '</div>';
                echo '<pre>'.json_encode($response->getResponse()).'</pre>';
            } else {
                echo '<div class="alert alert-warning">' . $response->getStatus() . ': ' . $status_desc;
                echo '</div>';
            }
        } catch (OpenPayU_Exception $e) {
            echo '<pre>';
            echo 'Error code: ' . $e->getCode();
            echo '<br>';
            echo 'Error message: ' . $e->getMessage();
            echo '<br>';
            echo '</pre>';
        }
    } else {
        ?>
        <form action="" method="post" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="domain">Domain</label>
                <div class="controls">
                    <input class="span5" name="domain" id="domain" type="text" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="displayName">Display Name</label>
                <div class="controls">
                    <input class="span5" name="displayName" id="displayName" type="text" value="" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="pay-button"></label>

                <div id="msg"></div>
                <div class="controls">
                    <button class="btn btn-success" id="pay-button" type="submit">Create Session</button>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
</body>
</html>
