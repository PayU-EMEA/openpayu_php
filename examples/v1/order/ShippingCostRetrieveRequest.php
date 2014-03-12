<?php

/**
 *    ShippingCostRetrieveRequest message processing.
 *
 * @copyright  Copyright (c) 2011-2012, PayU
 * @license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

$xml = stripslashes($_POST['DOCUMENT']);

$result = OpenPayU_Order::consumeMessage($xml);

$cc = 'PL';
$rspId = '123123123';

if ($result->getMessage() == 'ShippingCostRetrieveRequest') {
    $arr = array(
        'CountryCode' => $cc,
        'ShipToOtherCountry' => 'true',
        'ShippingCostList' => array(
            array(
                'ShippingCost' => array(
                    'Type' => 'recalculated_courier_0',
                    'CountryCode' => $cc,
                    'Price' => array(
                        'Gross' => '1220', 'Net' => 220, 'Tax' => '22', "TaxRate" => 22, "CurrencyCode" => "PLN"
                    )
                )
            ),
            array(
                'ShippingCost' => array(
                    'Type' => 'recalculated_courier_1',
                    'CountryCode' => $cc,
                    'Price' => array(
                        'Gross' => '2440', 'Net' => 440, 'Tax' => '22', "TaxRate" => 22, "CurrencyCode" => "PLN"
                    )
                )
            )
        )
    );

    $xml = OpenPayU::buildShippingCostRetrieveResponse($arr, $rspId, $cc);
    header("Content-type: text/xml");
    echo $xml;
}

?>