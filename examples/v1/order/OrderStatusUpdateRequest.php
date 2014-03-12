<?php

/**
 *	Order status testing, it might be used independly from the main payment process.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)		
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order status update example</title>
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
    <script type="text/javascript" src="layout/js/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order status update example</h1>
    </div>
    <?php
        if(!empty($_POST['sessionId']))
        {
            $result = OpenPayU_Order::updateStatus($_POST['sessionId'], $_POST['status'], true);

            echo '<h4>Debug console</h4><pre>';
            OpenPayU_Order::printOutputConsole();
            echo '</pre>';
        }
    ?>
    <form action="" method="post">
        <p><label for="session">sessionId:</label> <input type="text" id="session" name="sessionId" value="" /></p>
        <p>
            <label for="stat">Status:</label>
            <select name="status" id="stat">
                <option value="ORDER_STATUS_NEW">ORDER_STATUS_NEW</option>
                <option value="ORDER_STATUS_PENDING">ORDER_STATUS_PENDING</option>
                <option value="ORDER_STATUS_CANCEL">ORDER_STATUS_CANCEL</option>
                <option value="ORDER_STATUS_REJECT">ORDER_STATUS_REJECT</option>
                <option value="ORDER_STATUS_COMPLETE">ORDER_STATUS_COMPLETE</option>
                <option value="ORDER_STATUS_SENT">ORDER_STATUS_SENT</option>
            </select>
        </p>
        <input type="submit" class="btn btn-primary" value="Send Request" />
    </form>
</div>
<script type="text/javascript">
    $('#session').focus();
</script>
</body>
</html>