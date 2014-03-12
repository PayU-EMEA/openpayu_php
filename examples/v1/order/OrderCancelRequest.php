<?php

/**
 *	Order cancel testing, it might be used independly from the main payment process.
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
    <title>Order cancel example</title>
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
    <script type="text/javascript" src="layout/js/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order cancel example</h1>
    </div>
    <?php
    if(!empty($_POST['sessionId']))
    {
        $result = OpenPayU_Order::cancel($_POST['sessionId'], true);
        echo '<h4>Debug console</h4><pre>';
        OpenPayU_Order::printOutputConsole();
        echo '</pre>';
        exit;
    }
    else
    {
    ?>
        <form action="" method="post">
            <label for="session">SessionId:</label> <input type="text" id="session" name="sessionId" value="" />
            <input type="submit" class="btn btn-primary" value="Send Request" />
        </form>
    <?php
    }
?>
</div>
<script type="text/javascript">
    $('#session').focus();
</script>
</body>
</html>