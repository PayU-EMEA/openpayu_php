<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

use PHPUnit\Framework\TestCase;

require_once realpath(__DIR__) . '/../../TestHelper.php';

class AuthType_BasicTest extends TestCase
{
    const POS_ID = 'PosId';
    const SIGNATURE_KEY = 'SignatureKey';

    private $expectedHeaders;

    /**
     * @test
     */
    public function shouldExceptionWhenEmptyPosId()
    {
        $this->expectExceptionMessage("PosId is empty");
        $this->expectException(OpenPayU_Exception_Configuration::class);
        //when
        new AuthType_Basic(null, null);
    }

    /**
     * @test
     */
    public function shouldExceptionWhenEmptySignatureId()
    {
        $this->expectExceptionMessage("SignatureKey is empty");
        $this->expectException(OpenPayU_Exception_Configuration::class);
        //when
        new AuthType_Basic(self::POS_ID, null);
    }

    /**
     * @test
     */
    public function shouldGetCorrectHeaders()
    {
        //given
        $this->expectedHeaders = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic UG9zSWQ6U2lnbmF0dXJlS2V5'
        );

        //when
        $authBasic = new AuthType_Basic(self::POS_ID, self::SIGNATURE_KEY);

        //then
        $this->assertEquals($this->expectedHeaders, $authBasic->getHeaders());
    }


}
