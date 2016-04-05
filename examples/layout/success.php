<?php

/**
 * OpenPayU Examples
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../config.php';

if(isset($_GET['error']))
    header('Location: ' . EXAMPLES_DIR . 'layout/error.php?error=' . $_GET['error']);

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order create successful</title>
    <link rel="stylesheet" href="<?php echo EXAMPLES_DIR;?>layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo EXAMPLES_DIR;?>layout/css/style.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order create - successful</h1>
    </div>
    <legend>THANK YOU PAGE</legend>
</div>
</body>
</html>