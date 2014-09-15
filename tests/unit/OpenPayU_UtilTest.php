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

require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';

class OpenPayU_UtilTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateSignData()
    {
        $expected = 'sender=MerchantPosId;signature=52bb16149d1a5ccc8ac05f8e435c30d82efd5364;algorithm=SHA-1;content=DOCUMENT';

        $this->assertEquals(
            $expected,
            OpenPayU_Util::generateSignData('OpenPayUData', 'SHA-1', 'MerchantPosId', 'SignatureKey')
        );
    }

    public function testVerifySignature()
    {
        $valid = OpenPayU_Util::verifySignature(
            'OpenPayUData', '52bb16149d1a5ccc8ac05f8e435c30d82efd5364', 'SignatureKey', 'SHA-1'
        );
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldSetSenderProperty(){
        $array  = array();
        $result = OpenPayU_Util::setSenderProperty($array);
        $this->assertEquals($result['properties'][0]['name'], 'sender');
        $this->assertEquals($result['properties'][0]['value'], OpenPayU_Configuration::getFullSenderName());
    }
}
