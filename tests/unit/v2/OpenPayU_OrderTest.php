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

        $this->_order['ContinueUrl'] = 'http://localhost/';
        $this->_order['NotifyUrl'] = 'http://localhost/';
        $this->_order['CustomerIp'] = '127.0.0.1';
        $this->_order['MerchantPosId'] = '45654';
        $this->_order['Description'] = 'New order';
        $this->_order['CurrencyCode'] = 'PLN';
        $this->_order['TotalAmount'] = 1000;
        $this->_order['ExtOrderId'] = '1342';
        $this->_order['ValidityTime'] = 48000;

        $this->_order['Products']['Product'][0]['Name'] = 'Product1';
        $this->_order['Products']['Product'][0]['UnitPrice'] = 1000;
        $this->_order['Products']['Product'][0]['Quantity'] = 1;

        $this->_order['PaymentMethods']['PaymentMethod'][0]['Type'] = 'PBL';

        $this->_order['Buyer']['Email'] = 'dd@ddd.pl';
        $this->_order['Buyer']['Phone'] = '123123123';
        $this->_order['Buyer']['FirstName'] = 'Jan';
        $this->_order['Buyer']['LastName'] = 'Kowalski';
        $this->_order['Buyer']['Language'] = 'pl_PL';
        $this->_order['Buyer']['NIN'] = '123456';

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
        $orderId = $this->_order['ExtOrderId'];
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderCancelResponse', 'cancel', $orderId);
        $this->assertEquals('', $mock->cancel($orderId));
    }

    public function testStatusUpdate()
    {
        $mock = $this->mockOpenPayU_HttpVerifyResponse('orderStatusUpdateResponse', 'statusUpdate', 'Z963D5JQR2230925GUEST000P01');
        $this->assertEquals('', $mock->statusUpdate('Z963D5JQR2230925GUEST000P01'));
    }

}
