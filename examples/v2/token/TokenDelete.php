<?php

/**
 * OpenPayU Examples
 *
 * @copyright  Copyright (c) 2011-2017 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

if (isset($_POST['token'])) {
    $token = trim($_POST['token']);
}
?>
<!doctype html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Token</title>
        <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../layout/css/style.css">
        <script type="text/javascript" src="../../layout/js/jquery.min.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                <h1>Token</h1>
            </div>
            <div id="message"></div>
            <div id="unregisteredCardData">
                <?php
                if (!empty($token)) {

                    try {
                        $tokenDelete = OpenPayU_Token::delete($token);

                        $status_desc = OpenPayU_Util::statusDesc($tokenDelete->getStatus());

                        echo '<div class="alert alert-success">SUCCESS</div>';

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
                            <label class="control-label" for="token">Token</label>

                            <div class="controls">
                                <input class="span5" name="token" id="token" type="text" value=""/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="pay-button"></label>

                            <div id="msg"></div>
                            <div class="controls">
                                <button class="btn btn-success" id="pay-button" type="submit">Delete token</button>
                            </div>
                        </div>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>

