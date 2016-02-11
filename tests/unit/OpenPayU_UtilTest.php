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
        $expected = 'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35';

        $this->assertEquals(
            $expected,
            OpenPayU_Util::generateSignData(array('test'=>'OpenPayUData'), 'SHA', 'MerchantPosId', 'SignatureKey')
        );
    }

    public function testVerifySignature()
    {
        $valid = OpenPayU_Util::verifySignature(
            'OpenPayUData', '52bb16149d1a5ccc8ac05f8e435c30d82efd5364', 'SignatureKey', 'SHA-1'
        );
        $this->assertTrue($valid);
    }

    public function testShouldSetSenderProperty(){
        $array  = array();
        $result = json_decode(OpenPayU_Util::buildJsonFromArray($array));
        $this->assertEquals($result->properties[0]->name, 'sender');
        $this->assertEquals($result->properties[0]->value, OpenPayU_Configuration::getFullSenderName());
    }


}
