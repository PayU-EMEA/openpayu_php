<?php
/**
 * BEFORE USING THIS EXAMPLE, PLEASE ENSURE THAT YOU HAVE AN AGREEMENT SIGNED FOR THIS PAYMENT METHOD
 */


/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2013 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['data']['token'])) {

        $card_token = trim($_POST['data']['token']);

        try {
            $order = array(
                'CustomerIp' => $_SERVER['REMOTE_ADDR'],
                'NotifyUrl' => EXAMPLES_DIR . 'v2/OrderNotify.php',
                'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
                'ContinueUrl' => EXAMPLES_DIR . 'layout/success.php',
                'ValidityTime' => '48000',
                'Description' => '...',
                'CurrencyCode' => 'PLN',
                'TotalAmount' => '10000',
                'Buyer' => array(
                    'Email' => 'dd@ddd.pl',
                    'Phone' => '123123123',
                    'FirstName' => 'Jaroslaw',
                    'LastName' => 'Testowy',
                    'Language' => 'pl_PL',
                    'NIN' => '123123123'
                ),
                'Products' => array(
                    'Product' => array(
                        'Name' => 'Mouse',
                        'UnitPrice' => '10000',
                        'Quantity' => '1'
                    )
                )
            );

            $order['PayMethods']['PayMethod'] = array('Type' => 'CARD_TOKEN', 'Value' => $card_token);

            $order_result = OpenPayU_Order::create($order);
            $return = array('status' => $order_result->Status);

            if (!isset($order_result->Response->RedirectUri)) {
                $return['RedirectUri'] = $order['ContinueUrl'];
            } else {
                $return['RedirectUri'] = $order_result->Response->RedirectUri;
            }

        } catch (Exception $e) {
            $return = array('status' => $e->getCode() . ' ' . $e->getMessage());
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }
}