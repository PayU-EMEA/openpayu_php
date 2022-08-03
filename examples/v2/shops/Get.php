<?php

/**
 * OpenPayU Examples
 *
 * @copyright  Copyright (c) PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

if (isset($_POST['publicShopId'])) {
    $publicShopId = trim($_POST['publicShopId']);
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Retrieving shop data</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
    <script type="text/javascript" src="../../layout/js/jquery.min.js"></script>
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1>Retrieving shop data</h1>
    </div>

    <?php
    if (!empty($publicShopId)) {
        try {
            $shop = OpenPayU_Shop::get($publicShopId);
            echo '<pre>'.$shop.'</pre>';
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
                <label class="control-label" for="publicShopId">Public Shop Id</label>
                <div class="controls">
                    <input class="span5" name="publicShopId" id="publicShopId" type="text" value=""/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="pay-button"></label>

                <div id="msg"></div>
                <div class="controls">
                    <button class="btn btn-success" id="pay-button" type="submit">Get shop</button>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
</body>
</html>
