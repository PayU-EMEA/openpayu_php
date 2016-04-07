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

class OpenPayU_UtilTest extends PHPUnit_Framework_TestCase
{

    const MERCHANT_POS_ID = 'MerchantPosId';
    const SIGNATURE_KEY = 'SignatureKey';

    public function verifyGenerateSignData()
    {
        return array(
            array('SHA', 'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35'),
            array('SHA-256', 'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35'),
            array('SHA-384', 'sender=MerchantPosId;algorithm=SHA-384;signature=ef1f0258e004b4ade604115289a374a0cb54899ff48fc65ca6a23d5dfc6332dfd5a3a63de88b65743cff5ecf8db4fda2'),
            array('SHA-512', 'sender=MerchantPosId;algorithm=SHA-512;signature=3ebb7cd2b9f60dacda25835f323fb15adda3eb729da71fd61c9bfd41b97c6eeea47949cee0aaf5fa1a4d8fa9c065369aaeffd34afc4f376bd11dd8bfdd3c564d')
        );
    }

    /**
     * @test
     * @dataProvider verifyGenerateSignData
     */
    public function shouldGenerateSignData($algorithm, $expected)
    {
        //when
        $signature = OpenPayU_Util::generateSignData(array('test'=>'OpenPayUData'), $algorithm, self::MERCHANT_POS_ID, self::SIGNATURE_KEY);

        //then
        $this->assertEquals($expected, $signature);
    }

    public function verifySignatureDataProvider()
    {
        return array(
            array('MD5', '8375034eb737d520c829fad4026a38aa', true),
            array('SHA-1', '52bb16149d1a5ccc8ac05f8e435c30d82efd5364', true),
            array('SHA-256', '8b2fd55b48f150347df56ce18d787335f32ced1d67f214016476f7c0a8f09981', true),
            array('SHA-256', 'incorrectSignature', false)
        );
    }

    /**
     * @test
     * @dataProvider verifySignatureDataProvider
     */
    public function shouldVerifySignature($algorithm, $signature, $result)
    {
        //when
        $valid = OpenPayU_Util::verifySignature('OpenPayUData', $signature, self::SIGNATURE_KEY, $algorithm);

        //then
        $this->assertEquals($valid, $result);
    }

    public function verifyParseSignatureDataProvider()
    {
        return array(
            array('', null),
            array(null, null),
            array('TEST', null),
            array(
                'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35',
                (object) array('sender' => 'MerchantPosId', 'algorithm' => 'SHA-256', 'signature' => 'e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35')
            )
        );
    }

    /**
     * @test
     * @dataProvider verifyParseSignatureDataProvider
     */
    public function shouldParseSignature($signature, $result)
    {
        //when
        $parsedSignature = OpenPayU_Util::parseSignature($signature);

        //then
        $this->assertEquals($parsedSignature, $result);
    }


    /**
     * @test
     */

    public function shouldSetSenderProperty(){

        //when
        $result = json_decode(OpenPayU_Util::buildJsonFromArray(array()));

        //then
        $this->assertEquals($result->properties[0]->name, 'sender');
        $this->assertEquals($result->properties[0]->value, OpenPayU_Configuration::getFullSenderName());
    }

}
