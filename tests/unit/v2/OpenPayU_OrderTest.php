<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../../TestHelper.php';

class OpenPayU_OrderTest extends PHPUnit_Framework_TestCase {

    private $_order = array();

    protected function setUp()
    {
        OpenPayU_Configuration::setEnvironment('secure'); // production
        OpenPayU_Configuration::setMerchantPosId('145227'); // POS ID (Checkout)
        OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50'); //Second MD5 key. You will find it in admin panel.

        $this->initializeOrderData();
    }

    private function mockOpenPayU_HttpVerifyResponse($orderResponseType, $method, $with){

        $mock = $this->getMockBuilder('OpenPayU_Order')
            ->disableOriginalConstructor()
            ->getMock();

        $returnValue = file_get_contents(realpath(dirname(__FILE__)) . '/../../resources/'. $orderResponseType .'.txt');

        $mock->expects($this->any())
            ->method($method)->with($with)
            ->will($this->returnValue($returnValue));

        return $mock;
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
        $expectedForm = file_get_contents(realpath(dirname(__FILE__)) . '/../../resources/hostedOrderForm.txt');
        $this->assertEquals($expectedForm, OpenPayU_Order::hostedOrderForm($this->_order));
    }

    public function testCreate()
    {
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderCreateResponse', 'create', $this->_order);
        $this->assertEquals('', $mock->create($this->_order));
    }

    public function testRetrieve()
    {
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderRetrieveResponse', 'retrieve', $this->_order);
        $this->assertEquals('', $mock->retrieve('Z963D5JQR2230925GUEST000P01'));
    }


    public function testCancel()
    {
        $orderId = $this->_order['extOrderId'];
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderCancelResponse', 'cancel', $orderId);
        $this->assertEquals('', $mock->cancel($orderId));
    }

    public function testStatusUpdate()
    {
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderStatusUpdateResponse', 'statusUpdate', 'Z963D5JQR2230925GUEST000P01');
        $this->assertEquals('', $mock->statusUpdate('Z963D5JQR2230925GUEST000P01'));
    }

}
