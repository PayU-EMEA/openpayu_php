<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../TestHelper.php';

class OpenPayU_OrderTest extends PHPUnit_Framework_TestCase {

    private $_order = array();

    protected function setUp()
    {
        OpenPayU_Configuration::setEnvironment('secure');
        OpenPayU_Configuration::setMerchantPosId('145227');
        OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50');

        $this->initializeOrderData();
    }


    private function initializeOrderData(){

        $this->_order['continueUrl'] = 'http://localhost/';
        $this->_order['notifyUrl'] = 'http://localhost/';
        $this->_order['customerIp'] = '127.0.0.1';
        $this->_order['merchantPosId'] = '45654';
        $this->_order['description'] = 'New order';
        $this->_order['currencyCode'] = 'PLN';
        $this->_order['totalAmount'] = 1000;
        $this->_order['extOrderId'] = '1342';
        $this->_order['validityTime'] = 48000;

        $this->_order['products'][0]['name'] = 'Product1';
        $this->_order['products'][0]['unitPrice'] = 1000;
        $this->_order['products'][0]['quantity'] = 1;

        $this->_order['paymentMethods'][0]['type'] = 'PBL';

        $this->_order['buyer']['email'] = 'dd@ddd.pl';
        $this->_order['buyer']['phone'] = '123123123';
        $this->_order['buyer']['firstName'] = 'Jan';
        $this->_order['buyer']['lastName'] = 'Kowalski';
        $this->_order['buyer']['language'] = 'pl_PL';
        $this->_order['buyer']['nIN'] = '123456';

    }

    public function testHostedOrderForm()
    {
        //given
        $expectedForm = file_get_contents(realpath(dirname(__FILE__)) . '/../../resources/hostedOrderForm.txt');
        OpenPayU_Configuration::setHashAlgorithm('SHA');

        //when
        $form = OpenPayU_Order::hostedOrderForm($this->_order);

        //then
        $this->assertEquals($expectedForm, $form);
    }

}
