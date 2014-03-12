<?php 

/**
 *	Retrieving access_token after login in payu service, second step in OAuth processing.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)		
 */

session_start();

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

$myUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] .$_SERVER['PHP_SELF'];			
	
// After customer login PayU service redirect back user to merchant with ?code=... paramater. 
// Parameter code is used to retrieve accessToken in OAuth autorization code mode from PayU service.
$result = OpenPayU_OAuth::accessTokenByCode($_GET['code'], $myUrl);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Before summary page</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
    <script type="text/javascript" src="../../layout/js/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Before summary page</h1>
    </div>
<?php
    echo '<pre>';
    if ($result->getSuccess()) {
        echo "<strong>accessTokenByCode</strong> success<br/>";
    } else {
        echo "accessTokenByCode error: " . $result->getError();
    }
    echo '</pre>';

// print some debug data (optional)
    echo '<h4>Debug console</h4><pre>';
    OpenPayU_Order::printOutputConsole();
    echo '</pre>';
?>
    <br>
    <form method="GET" action="<?php echo OpenPayu_Configuration::getSummaryUrl();?>">
        <input type="hidden" name="sessionId" value="<?php echo $_SESSION['sessionId'];?>">
        <input type="hidden" name="oauth_token" value="<?php echo $result->getAccessToken();?>">
        <input type="submit" class="btn btn-primary" value="Next step (summary page) >">
    </form>
</body>
</html>