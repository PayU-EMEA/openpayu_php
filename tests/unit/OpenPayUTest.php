<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';

class OpenPayUTest extends PHPUnit_Framework_TestCase
{

    const INCOMING_SIGNATURE = 'sender=145227;algorithm=SHA-256;signature=846b2de129d2200443bd72abe691ce174fcec064f89dc529d6a5d98a046cbb4d';

    protected function setUp()
    {
        OpenPayU_Configuration::setEnvironment('secure');
        OpenPayU_Configuration::setMerchantPosId('145227');
        OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50');
    }

    /**
     * @test
     * @expectedException OpenPayU_Exception_Authorization
     * @expectedExceptionMessage Invalid signature - 846b2de129d2200443bd72abe691ce174fcec064f89dc529d6a5d98a046cbb4d
     */
    public function shouldNotVerifySignature()
    {
        //when
        OpenPayU::verifyDocumentSignature('TEST_FAIL', self::INCOMING_SIGNATURE);

    }

    /**
     * @test
     */
    public function shouldVerifySignature()
    {
        //when
        OpenPayU::verifyDocumentSignature('TEST_OK', self::INCOMING_SIGNATURE);
    }

}
