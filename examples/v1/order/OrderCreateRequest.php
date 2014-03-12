<?php

/**
 *	OrderCreateRequest message processing. This is order initialization phase.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)
 */

session_start();

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';


// openpayu service configuration
// some preprocessing
$directory = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$myUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] .$directory;

$_SESSION['sessionId'] = md5(rand() . rand() . rand() . rand());

// shippingCost structure
$shippingCost = array(
    'CountryCode' => 'PL',
    'ShipToOtherCountry' => 'true',
    'ShippingCostList' => array(
        array(
            'ShippingCost' => array(
                'Type' => 'courier_0',
                'CountryCode' => 'PL',
                'Price' => array(
                    'Gross' => '1220', 'Net' => '0', 'Tax' => '0', 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
                )
            )
        ),
        array(
            'ShippingCost' => array(
                'Type' => 'courier_1',
                'CountryCode' => 'PL',
                'Price' => array(
                    'Gross' => '861', 'Net' => '700', 'Tax' => '161', 'TaxRate' => '23', 'CurrencyCode' => 'PLN'
                )
            )
        )
    )
);

// initialization of order is done with OrderCreateRequest message sent.

// important!, dont use urlencode() function in associative array, in connection with sendOpenPayuDocumentAuth() function.
// urlencoding is done inside OpenPayU SDK, file openpayu.php.

$item = array(
    'Quantity' => 1,
    'Product' => array (
        'Name' => 'random test product',
        'UnitPrice' => array (
            'Gross' => 12300, 'Net' => 0, 'Tax' => 0, 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
        )
    )
);

// shoppingCart structure
$shoppingCart = array(
    'GrandTotal' => 24600,
    'CurrencyCode' => 'PLN',
    'ShoppingCartItems' => array (
        array ('ShoppingCartItem' => $item),
        array ('ShoppingCartItem' => $item)
    )
);

// Order structure
$order = array (
    'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
    'SessionId' => $_SESSION['sessionId'],
    'OrderUrl' => $myUrl . '/layout/page_cancel.php?order=' . rand(), // is url where customer will see in myaccount, and will be able to use to back to shop.
    'OrderCreateDate' => date("c"),
    'InvoiceDisabled' => 'false', // options: false or true
    'OrderDescription' => 'random description (' . md5(rand()) . ')',
    'MerchantAuthorizationKey' => OpenPayU_Configuration::getPosAuthKey(),
    'OrderType' => 'MATERIAL', // options: MATERIAL or VIRTUAL
    'ShoppingCart' => $shoppingCart
);

// OrderCreateRequest structure
$OCReq = array (
    'ReqId' =>  md5(rand()),
    'CustomerIp' => '127.0.0.1', // note, this should be real ip of customer retrieved from $_SERVER['REMOTE_ADDR']
    'NotifyUrl' => $myUrl . '/OrderNotifyRequest.php', // url where payu service will send notification with order processing status changes
    'OrderCancelUrl' => $myUrl . '/layout/page_cancel.php',
    'OrderCompleteUrl' => $myUrl . '/layout/page_success.php',
    'Order' => $order,
    'ShippingCost' => array(
        'AvailableShippingCost' => $shippingCost,
        'ShippingCostsUpdateUrl' => $myUrl . '/ShippingCostRetrieveRequest.php' // this is url where payu checkout service will send shipping costs retrieve request
    )
);

# if logged customer in eshop
$customer = array(
    'Email' => 'example@mail.address.com',
    'FirstName' => 'Jan',
    'LastName' => 'Kowalski',
    'Phone' => '01234567',
    'Language' => 'pl_PL',
    /* Shipping address*/
    'Shipping' => array(
        'Street' => 'Marcelinska',
        'HouseNumber' => '90',
        'ApartmentNumber' => '',
        'PostalCode' => '69-456',
        'City' => 'Poznan',
        'CountryCode' => 'PL',
        'AddressType' => 'SHIPPING',
        'RecipientName' => 'Jan Kowalski'
    ),
    /* Invoice billing data */
    'Invoice' => array(
        'Street' => 'Marcelinska',
        'HouseNumber' => '90',
        'ApartmentNumber' => '',
        'PostalCode' => '60-324',
        'City' => 'Poznan',
        'CountryCode' => 'PL',
        'AddressType' => 'BILLING',
        'RecipientName' => 'PayU SA',
        'TIN' => '779-23-08-495'
    )
);


if(!empty($customer))
    $OCReq['Customer'] = $customer;

// send message OrderCreateRequest, $result->response = OrderCreateResponse message
$result = OpenPayU_Order::create($OCReq);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Create Example</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/style.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order create Example</h1>
    </div>
<?php
if ($result->getSuccess()) {

    echo '<h4>Debug console</h4><pre>';
    OpenPayU_Order::printOutputConsole();
    echo '</pre>';

    $result = OpenPayU_OAuth::accessTokenByClientCredentials();
?>
<form method="GET" action="<?php echo OpenPayU_Configuration::getAuthUrl(); ?>">
    <fieldset>
        <legend>Process with user authentication</legend>
        <p>During this process, you will be asked to login before moving on to the summary.</p>
        <input type="hidden" name="redirect_uri" value="<?php echo $myUrl . "../CustomerAuthorization
        .php";?>">
        <input type="hidden" name="response_type" value="code">
        <input type="hidden" name="client_id" value="<?php echo OpenPayU_Configuration::getClientId(); ?>">
        <p><input type="submit" class="btn btn-primary" value="Next step (user authorization) >"></p>
    </fieldset>
</form>

<form method="GET" action="<?php echo OpenPayu_Configuration::getSummaryUrl();?>">
    <fieldset>
        <legend>Process without user authentication, redirect to summary</legend>
        <p>During this process, you will be taken to a summary</p>
        <input type="hidden" name="sessionId" value="<?php echo $_SESSION['sessionId'];?>">
        <input type="hidden" name="oauth_token" value="<?php echo $result->getAccessToken();?>">
        <p>
            <label for="showLoginDialogSelect">Show login dialog:</label>
            <select name="showLoginDialog" id="showLoginDialogSelect">
                <option value="False">No</option>
                <option value="True">Yes</option>
            </select>
            <input type="submit" class="btn btn-primary" value="Next step (summary page) >">
        </p>
    </fieldset>
</form>
<?php
} else {
    echo '<h4>Debug console</h4><pre>';
    OpenPayU_Order::printOutputConsole();
    echo '<br/><strong>ERROR:</strong><br />' . $result->getError() . ' ' . $result->getMessage() . '</pre>';
}
?>
</div>
</body>
</html>